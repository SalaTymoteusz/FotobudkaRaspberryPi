<?php


namespace WTM\Controllers\Admin;

if ( ! defined( 'ABSPATH' ) ) exit; 

use WTM\Functions;
use WTM\Model\Template;
use WTM\Model\Validate;
use WTM\Model\Helper_Form;
use WP_Error;

class Admin extends Functions{

    protected $id;

    protected $title;

    protected $parent;

    protected $sections;

    protected $properties;

    protected $permission;

    protected $menu_title;

    protected $menu_position = null;

    protected $icon_url = '';

    protected static $permission_values;

    private static $instance_autoload = [
        'WTM\Controllers\Admin\Template_Options',
        'WTM\Controllers\Admin\Sortable',
        'WTM\Controllers\Admin\Google',
        'WTM\Controllers\Admin\Optimizer',
        //'WTM\Controllers\Admin\Promotion',
        'WTM\Controllers\Admin\Extras',
        
    ];

    public function __construct(){
        if(is_admin()){
            parent::__construct();
        }   

    }

    private static function load_core()
    {   
        if (!isset(self::$instance_autoload) || empty(self::$instance_autoload)) {
            return;
        }

        foreach (self::$instance_autoload as $instance) {
            // $instance = "WTM\\Core\\$instance";
            if(!isset(self::$instances[$instance]) || empty(self::$instances[$instance]))
                self::$instances[$instance] = new $instance();
        }
    }



    
    public function load_admin_controllers(){
        self::load_core();
    }

    protected function add_actions(){
        
        add_action( 'init', [$this,'load_admin_controllers'],1);

        add_action( 'admin_init', [$this,'init_properties']);
        add_action( 'admin_menu', [$this,'init_page']);
        add_action( 'admin_init', [$this,'init_content']);
        
        add_action( 'admin_notices', [$this, 'display_alerts']);

        add_action( 'admin_post_wtm_save_admin', [$this, 'post_process']);
        add_action( 'admin_action_duplicate_post_as_draft', [$this,'duplicate_post_as_draft'] );

        

    }

    protected function add_filters(){
        add_filter( 'post_row_actions', [$this,'add_duplicate_post_button'], 10, 2 );
    }






    /**
     * add_duplicate_post_button function
     *
     * function add link to duplicate post in admin listing post
     * 
     * @param array $actions
     * @param object $post
     * @return array $actions
     */
    public function add_duplicate_post_button( $actions, $post ) {
        if (current_user_can('edit_posts')) {
            $actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ) . '" title="Duplicate this item" rel="permalink">'.__('Duplikuj').'</a>';
        }
        return $actions;
    }


    /**
     * duplicate_post_as_draft function
     *
     * action duplicate post and save this as draft
     * 
     * @return void
     */
    public function duplicate_post_as_draft(){
        global $wpdb;
        if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'wtm_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
            wp_die('No post to duplicate has been supplied!');
        }
     
        /*
         * Nonce verification
         */
        if ( !isset( $_GET['duplicate_nonce'] ) || !wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) )
            return;
     
        /*
         * get the original post id
         */
        $post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
        /*
         * and all the original post data then
         */
        $post = get_post( $post_id );
     
        /*
         * if you don't want current user to be the new post author,
         * then change next couple of lines to this: $new_post_author = $post->post_author;
         */
        $current_user = wp_get_current_user();
        $new_post_author = $current_user->ID;
     
        /*
         * if post data exists, create the post duplicate
         */
        if (isset( $post ) && $post != null) {
     
            /*
             * new post data array
             */
            $args = array(
                'comment_status' => $post->comment_status,
                'ping_status'    => $post->ping_status,
                'post_author'    => $new_post_author,
                'post_content'   => $post->post_content,
                'post_excerpt'   => $post->post_excerpt,
                'post_name'      => $post->post_name,
                'post_parent'    => $post->post_parent,
                'post_password'  => $post->post_password,
                'post_status'    => 'draft',
                'post_title'     => $post->post_title,
                'post_type'      => $post->post_type,
                'to_ping'        => $post->to_ping,
                'menu_order'     => $post->menu_order
            );
     
            /*
             * insert the post by wp_insert_post() function
             */
            $new_post_id = wp_insert_post( $args );
     
            /*
             * get all current post terms ad set them to the new post draft
             */
            $taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
            foreach ($taxonomies as $taxonomy) {
                $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
                wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
            }
     
            /*
             * duplicate all post meta just in two SQL queries
             */
            $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
            if (count($post_meta_infos)!=0) {
                $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
                foreach ($post_meta_infos as $meta_info) {
                    $meta_key = $meta_info->meta_key;
                    if( $meta_key == '_wp_old_slug' ) continue;
                    $meta_value = addslashes($meta_info->meta_value);
                    $sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
                }
                $sql_query.= implode(" UNION ALL ", $sql_query_sel);
                $wpdb->query($sql_query);
            }
     
     
            /*
             * finally, redirect to the edit post screen for the new draft
             */
            wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
            exit;
        } else {
            wp_die('Post creation failed, could not find original post: ' . $post_id);
        }
    }



    public function post_process(){
        
        check_admin_referer( 'wtm_save_admin' );

        $redirect = Validate::check_variable($_POST['_wp_http_referer']) ? $_POST['_wp_http_referer'] : admin_url();

        $current_page = $_POST['page'];
        if($current_page !== $this->get_id()){
            return false;
        }

        $properties = $this->get_properties();

        if(!Validate::check_variable($properties)){
            return false;
        }

        $errors = [];
        $values = [];
        foreach($properties as $prop => $prop_details){
            if(is_numeric($prop)){
                $prop = $prop_details;
            }

            $value = '';

            if(Validate::check_variable($_POST[$prop])){
                $value = $_POST[$prop];
            }

            if(is_string($prop_details)){
                update_option($prop,$value,true);
                continue;
            }

            $prop_label = Validate::check_variable($prop_details['label']) ? $prop_details['label'] : $prop;
            $validate = Validate::check_variable($prop_details['validate']) ? $prop_details['validate'] : false;
            $required = Validate::check_variable($prop_details['required']) ? $prop_details['required'] : false;
            

            if($required){
                if(!Validate::check_variable($value)){
                    $errors[] = new WP_Error("empty_{$prop}", __( "Pole {$prop_label} jest wymagane", "fotobudka" ));
                    continue;
                }
            }

            if(!empty($value) && $validate){
                if(!Validate::$validate($value)){
                    $errors[] = new WP_Error("bad_{$prop}_format", __( "Pole {$prop_label} jest niepoprawne", "fotobudka" ));
                    continue;
                }
            }

            $values[$prop] = $value;
        }



        if(!empty($errors))
            set_transient('wtm_admin_notices', $errors);
        else{
            if(Validate::check_variable($values)){

                foreach($values as $prop => $prop_value){
                    update_option($prop,$prop_value,true);
                }
            }

            set_transient('wtm_admin_notices', __( "Dane zapisane prawidłowo", "fotobudka" ));
    
        }

        
        wp_redirect($redirect);
        exit;
    }




    public function init_page(){
        if($this->check_id() && $this->get_title() && $this->get_permission()){

            if(!$this->get_parent()){
                add_menu_page(
                    $this->get_title(),
                    $this->get_menu_title(),
                    $this->get_permission(),
                    $this->get_id(), 
                    [$this,'display_content'],
                    $this->get_icon_url(),
                    $this->get_menu_position()
                );
            }
            elseif($this->get_parent()){
                add_submenu_page(
                    "{$this->get_parent()}", 
                    $this->get_title(), 
                    $this->get_menu_title(), 
                    $this->get_permission(),
                    $this->get_id(),
                    [$this,'display_content'],
                    $this->get_menu_position()
                );
            }
            
        }
    }

    public function display_content(){
        Template::get_template('_partials/admin/page');
    }


    public function init_content(){
        if(!$this->check_id() || !$this->check_sections())
        return;

        foreach($this->get_sections() as $section => $title){
            add_settings_section(
                "{$this->get_id()}_$section",
                __($title,'fotobudka'),
                [$this,'init_fields'],
                $this->get_id()
            );
        }
        
    }


    public function init_fields(){}



    
    public function render_field($args = array()){
        echo Helper_Form::render_field_static($args);
    }



    public function display_alerts(){
        $alerts = get_transient('wtm_admin_notices');
        if(!$alerts)
        return;

        if(!is_array($alerts))
        $alerts = [$alerts];

        Template::get_template('_partials/admin/alerts',['alerts' => $alerts]);

        delete_transient('wtm_admin_notices');
        
    }










    protected function check_id(){
        return !empty($this->id) && is_string($this->id) && trim($this->id) !== '';
    }


    protected function check_properties(){
        return !empty($this->properties) && is_array($this->properties);
    }
     


    public function init_properties(){
        if(!$this->check_properties() || !$this->check_id())
        return;

        foreach($this->get_properties() as $prop => $prop_details){
            if(is_numeric($prop)){
                register_setting($this->get_id(),$prop_details);
            }
            elseif(is_string($prop)){
                register_setting($this->get_id(),$prop);
            }
            
        }

        return true;
    }



    public function get_properties(){
        return $this->properties;
    }



    protected function check_sections(){
        return !empty($this->sections) && is_array($this->sections);
    }



    public function get_sections(){
        return $this->sections;
    }

    public function get_property_value($prop){
        if(!Validate::check_variable($this->properties[$prop])){
            return false;
        }

        return get_option($prop);
    }


    public function get_id(){
        return $this->id;
    }

    public function get_title(){
        return $this->title;
    }


    public function get_permission(){
        return $this->permission;
    }

    public function get_parent(){
        return $this->parent;
    }


    public function get_menu_title(){
        return $this->menu_title ?? $this->get_title();
    }


    public function get_menu_position(){
        return $this->menu_position;
    }

    public function get_icon_url(){
        return $this->icon_url;
    }
}
