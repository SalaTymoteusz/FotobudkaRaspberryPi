<?php

namespace WTM\Controllers\Admin;

if ( ! defined( 'ABSPATH' ) ) exit; 

use WTM\Model\Tools;

class Sortable extends Admin{
    protected $id = 'sortable';
    
    protected $title = 'Ustawinia sortowania';

    protected $menu_title = 'Sortowania';

    protected $parent = 'options-general.php';

    protected $permission = 'manage_options';
    
    protected $sections = [
        'posts' => 'Sortowalne typu postów',
        'taxonomies' => 'Sortowalne kategorii',
    ];

    protected $properties = [
        'sortable_posts' => ['label' => 'Sortowalne typu postów', 'validate' => false,'required' => false],
        'sortable_taxonomies' => ['label' => 'Sortowalne kategorii', 'validate' => false, 'required' => false],
    ];
    
    private $sortable_posts;

    private $sortable_taxonomies;
    
    private $default_sort_posts = 'date|desc';

    private $default_sort_taxonomies = 'name|asc';

    private $available_sort_posts;

    private $available_sort_taxonomies;


    public function __construct(){
        $this->set_sortable_posts();
        $this->set_sortable_taxonomies();
        $this->set_available_sort_posts();
        $this->set_available_sort_taxonomies();
        parent::__construct();
    }




    protected function add_actions(){
        parent::add_actions();

        add_action( 'admin_head',[$this, 'add_styles_to_header'] );
        add_action( 'admin_body_class', [$this, 'add_classes_to_body'] );
        add_action( 'admin_init', [$this, 'register_posts_sortable_columns'] );
        add_action( 'admin_init', [$this, 'register_taxonomies_sortable_columns'] );
        add_action( 'admin_enqueue_scripts', [$this, 'register_scripts'] );
        add_action( 'admin_notices', [$this, 'response_update_html_sortable'] );
        add_action( 'wp_ajax_wtm_admin_sortable', [$this, 'update_sortable'] );
    }

    protected function add_filters(){
        parent::add_filters();
        add_filter( 'wp_insert_post_data', [$this, 'update_order_for_new_post'], 99, 2 );
        add_filter( 'pre_update_option_sortable_taxonomies',[$this, 'check_sortable_taxonomy_terms_for_term_meta'], 10, 2 );
    }

    public function init_fields(){
        $registered_post_types = get_post_types( '', 'objects' );

		// Remove Media, Nav Menu Items, and Revisions
		unset( $registered_post_types['attachment'] );
		unset( $registered_post_types['nav_menu_item'] );
        unset( $registered_post_types['revision'] );
        unset( $registered_post_types['schema'] );
        unset( $registered_post_types['wpcf7_contact_form'] );
        unset( $registered_post_types['acf-field'] );
        unset( $registered_post_types['acf-field-group'] );
        unset( $registered_post_types['wp_block'] );
        unset( $registered_post_types['user_request'] );
        unset( $registered_post_types['oembed_cache'] );
        unset( $registered_post_types['customize_changeset'] );
        unset( $registered_post_types['custom_css'] );
        
        if(!empty($registered_post_types)){
            
            foreach($registered_post_types as $post_type => $post_object){

                $value = $this->default_sort_posts;             
                
                if(isset($this->sortable_posts[$post_type]) && in_array($this->sortable_posts[$post_type],array_keys($this->available_sort_posts))){
                    $value = $this->sortable_posts[$post_type];
                }

                add_settings_field(
                    "sortable_posts_$post_type", // ID
                    __($post_object->label, 'fotobudka'), // Title 
                    array( $this, 'render_field' ), // Callback
                    $this->get_id(),// Page
                    "{$this->get_id()}_posts",
                    array(
                        'type' => 'select',
                        'value' => $value,
                        'name' => "sortable_posts[$post_type]",
                        'id' => "sortable_posts_$post_type",
                        'class_input' => 'regular-text',
                        'options' => $this->available_sort_posts
                    )
                );
            }

        }



        $registered_taxonomies = get_taxonomies( '', 'objects' );
        unset( $registered_taxonomies['nav_menu'] );
		unset( $registered_taxonomies['link_category'] );

        if(!empty($registered_taxonomies)){
            
            foreach($registered_taxonomies as $taxonomy => $taxonomy_object){

                $value = $this->default_sort_taxonomies;             
                
                if(isset($this->sortable_taxonomies[$taxonomy]) && in_array($this->sortable_taxonomies[$taxonomy],array_keys($this->available_sort_taxonomies))){
                    $value = $this->sortable_taxonomies[$taxonomy];
                }

                add_settings_field(
                    "sortable_taxonomies_$taxonomy", // ID
                    __($taxonomy_object->label, 'fotobudka'), // Title 
                    array( $this, 'render_field' ), // Callback
                    $this->get_id(),// Page
                    "{$this->get_id()}_taxonomies",
                    array(
                        'type' => 'select',
                        'value' => $value,
                        'name' => "sortable_taxonomies[$taxonomy]",
                        'id' => "sortable_taxonomies_$taxonomy",
                        'class_input' => 'regular-text',
                        'options' => $this->available_sort_taxonomies
                    )
                );
            }
        }     
    }



    private function set_sortable_posts()
    {        
        $this->sortable_posts = $this->get_property_value('sortable_posts');
    }

    private function set_sortable_taxonomies()
    {        
        $this->sortable_taxonomies = $this->get_property_value('sortable_taxonomies');
    }

    private function set_available_sort_posts(){
        $this->available_sort_posts = [
            'date|desc' => __('Wg daty utworzenia Od NAJMŁODSZYCH do NAJSTARSZYCH', 'fotobudka'),
            'date|asc' => __('Wg daty utworzenia Od NAJSTARSZYCH do NAJMŁODSZYCH', 'fotobudka'),
            'modified|desc' => __('Wg daty modyfikacji Od NAJMŁODSZYCH do NAJSTARSZYCH', 'fotobudka'),
            'modified|asc' => __('Wg daty modyfikacji Od NAJSTARSZYCH do NAJMŁODSZYCH', 'fotobudka'),
            'title|asc' => __('Wg tytułu od A do Z', 'fotobudka'),
            'title|desc' => __('Wg tytułu od Z do A', 'fotobudka'),
            'name|asc' => __('Wg url od A do Z', 'fotobudka'),
            'name|desc' => __('Wg url od Z do A', 'fotobudka'),
            'ID|asc' => __('Wg ID rosnąco', 'fotobudka'),
            'ID|desc' => __('Wg ID malejąco', 'fotobudka'),
            'rand' => __('Losowe', 'fotobudka'),
            'custom' => __('Własne sortowanie', 'fotobudka'),
        ];
    }

    private function set_available_sort_taxonomies(){
        $this->available_sort_taxonomies = [
            'name|asc' => __('Wg tytułu od A do Z', 'fotobudka'),
            'name|desc' => __('Wg tytułu od Z do A', 'fotobudka'),
            'slug|asc' => __('Wg url od A do Z', 'fotobudka'),
            'slug|desc' => __('Wg url od Z do A', 'fotobudka'),
            'id|asc' => __('Wg ID rosnąco', 'fotobudka'),
            'id|desc' => __('Wg ID malejąco', 'fotobudka'),
            'description|asc' => __('Wg opisu rosnąco', 'fotobudka'),
            'description|desc' => __('Wg opisu malejąco', 'fotobudka'),
            'custom' => __('Własne sortowanie', 'fotobudka'),
        ];
    }

    public function add_styles_to_header(){
        global $_wp_admin_css_colors;
        $user_admin_color = get_user_option( 'admin_color' );
        $colors = $_wp_admin_css_colors[$user_admin_color]->colors; ?>
<style>
    .wp-list-table #the-list tr:hover .sortable-posts-order {
        border-left-color: <?php echo $colors[3];
        ?>;
    }

    .sortable-posts-placeholder {
        background: <?php echo $colors[3];
        ?> !important;
    }

</style>
<?php
 
    }


    public function add_classes_to_body( $classes )
    {
        $classes .= ' sortable-posts';
        return $classes;
    }


    public function register_scripts(){

        global $wp_query;

        // Set the starting point for the menu_order.
        if( isset( $wp_query->query_vars['paged'] ) ) {
            $start = ($wp_query->query_vars['paged'] == 1 || $wp_query->query_vars['paged'] == 0 ) ? 1 : ($wp_query->query_vars['posts_per_page'] * $wp_query->query_vars['paged']) - $wp_query->query_vars['posts_per_page'] + 1;
        }else{
            $start = 1;
        }

        // // Get the object type to send to REST API
        $obj_type = get_current_screen();
        
        // // Create settings for localization            
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script( 'admin_sortable_js', get_template_directory_uri() . '/assets/admin/js/sortable.js', ['jquery'] );
        wp_localize_script( 'admin_sortable_js', 'wtm_admin_sortable', [
            'ajax'		=> esc_url_raw( admin_url('admin-ajax.php') ),
            'nonce'		=> wp_create_nonce( 'wtm_admin_sortable' ),
            'start'		=> $start,
            'obj_type'	=> $obj_type->base,
            'taxonomy'	=> (isset($wp_query->query_vars['taxonomy']) ? $wp_query->query_vars['taxonomy'] : ''),
            'taxonomy_term'	=> (isset($wp_query->query_vars['term']) ? $wp_query->query_vars['term'] : ''),
        ]);

			// CSS
        wp_enqueue_style( 'admin-sortable', get_template_directory_uri() . '/assets/admin/css/sortable.css');
    }


    public function register_posts_sortable_columns(){

        if(empty($this->sortable_posts))
        return ;

        foreach( (array) $this->sortable_posts as $post_type => $post_sort ){
            
            if( post_type_exists( $post_type ) && $post_sort === 'custom'){
                add_filter( "manage_{$post_type}_posts_columns", [$this, 'create_sortable_column']);
                add_action( "manage_{$post_type}_posts_custom_column", [$this, 'manage_posts_sortable_column']);
                add_filter( "manage_edit-{$post_type}_sortable_columns", [$this, 'remove_sortable_columns'] );
            }
        }
    }

    public function register_taxonomies_sortable_columns(){
        if(empty($this->sortable_taxonomies))
        return ;


        foreach( (array) $this->sortable_taxonomies as $tax => $tax_sort ){
            
            if( taxonomy_exists( $tax ) && $tax_sort === 'custom'){
                add_filter( "manage_edit-{$tax}_columns", [$this, 'create_sortable_column'], 10, 1 );
				add_filter( "manage_{$tax}_custom_column", [$this, 'manage_taxanomies_sortable_column'], 10, 3 );
				add_filter( "manage_edit-{$tax}_sortable_columns", [$this, 'remove_sortable_columns'] );
				add_action( 'create_term', [$this, 'update_order_for_new_term'], 10, 3 );
            }
        }
    }



    public function create_sortable_column( $columns ){
			$order = array( 'sortable-posts-order' => '<span class="dashicons dashicons-sort"></span>' );
			$columns = array_merge( $order, $columns );
			return $columns;
    }



    public function manage_posts_sortable_column( $column ){
        global $post, $wp_query;

        if( $column == 'sortable-posts-order' ){
            $order = $post->menu_order;
            if(isset($wp_query->query_vars['taxonomy'])) {
                $filter_inside_posts = apply_filters('sortable_post_inside_tax', array());
                if( is_array( $filter_inside_posts ) ){
                    foreach ( $filter_inside_posts as $combo ) {
                        if($combo['post_type'] === $post->post_type && $combo['taxonomy'] === $wp_query->query_vars['taxonomy']){
                            $order = get_post_meta( $post->ID, '_sortable_posts_order_' . $wp_query->query_vars['taxonomy'] . '_' . $wp_query->query_vars['term'], true );
                            break;
                        }
                    }
                }
            }
            echo '<strong class="sortable-posts-order-position">' . $order . '</strong>';
        }
    }


    public function manage_taxanomies_sortable_column( $output, $column, $term_id ){
		$term_position = get_term_meta( $term_id, 'term_order', true );

		if( $column == 'sortable-posts-order' ) {

			// Display 0 if term_position is equal to nothing
			if( empty($term_position)  ) {
				$term_position = 0;
            }


			$output .= sprintf( '<strong class="sortable-posts-order-position">%1$s</strong>', $term_position );
		}

		return $output;
	}






    public function remove_sortable_columns( $columns ){
        $columns = array();
        return $columns;
    }
    


    public function update_order_for_new_post( $data, $postarr ){
        $post_type = $data['post_type'];

        if(isset($this->sortable_posts[$post_type]) && $this->sortable_posts[$post_type] == 'custom'){
            // global $wpdb;
            // $data['menu_order'] = $wpdb->get_var(
            // 	"SELECT MAX(menu_order)+1 AS menu_order FROM {$wpdb->posts} WHERE post_type='{$type}'"
            // );
            $data['menu_order'] = 0;
        }

        return $data;
    }


    public function update_order_for_new_term( $term_id, $tt_id, $taxonomy )
	{
		if( in_array( $taxonomy, $this->sortable_taxes ) ) {

			// Gather all terms that have a term_order meta_key
			$args = array(
				'meta_query' => array(
					'meta_key'		=> sanitize_key( 'term_order' ),
					'meta_compare'	=> 'EXISTS',
				),
				'hide_empty' => false,

			);

			// Gather previously added terms to count them
			// $terms = get_terms( $taxonomy, $args );
			// $term_count = count( $terms );
			// $term_count = abs( $term_count + 1 );
			$term_count = 0;

			add_term_meta( $term_id, 'term_order', $term_count, true );
		}
		return;
	}





    public function check_sortable_taxonomy_terms_for_term_meta( $new_value, $old_value )
	{
		// If values are not arrays then save an array
		if( ! is_array( $new_value ) || ! is_array( $old_value ) ) {
			return array();
		}

		// Compare arrays to see if they have changed
		$array_compare = array_diff( $new_value, $old_value );

		// Continue if arrays are different
		if( ! empty( $array_compare ) ) {

			// Search for missing term_meta if values have changed
			foreach( (array) $array_compare as $taxonomy ) {

				// Get the terms for this taxonomy
				$terms = get_terms( $taxonomy, array( 'hide_empty' => 0 ) );

				if( ! empty( $terms ) ) {
					// Search terms for term_order meta
					foreach( $terms as $term ) {
						$term_order = get_term_meta( $term->term_id, 'term_order', true );

						// If the term_order is empty then add the metadata
						// Need to rework this to add default value as increment of the highest previous value
						if( empty( $term_order ) ) {
							add_term_meta( $term->term_id, 'term_order', '0', true );
						}
					}
				}

			}

		}

		// Save new value
		return $new_value;
    }
    


    public function response_update_html_sortable(){
        echo '<div id="sortable-posts-status">
        <strong id="sortable-posts-status-head"></strong>
        <div id="sortable-posts-status-message"></div>
    </div>';
    }


    public function update_sortable(){

		$order = Tools::get_value('order');

		if( ! is_array( $order ) || empty( $order ) ) {
            wp_send_json_error(__( 'Kolejność musi być tablicą', 'fotobudka' ),400);
            wp_die();
        }
        

        $object_type = Tools::get_value('object_type');

        $data = false;
        if( $object_type == 'edit' ) {
			$data = $this->update_post_sort_order();

		} elseif( $object_type == 'edit-tags' ) {
			$data = $this->update_taxonomy_sort_order();
		} 
		/**
		 * @edit fotobudka.pl
		 */
		elseif( $object_type == 'product_page_product_attribute' ) {
			$data = $this->update_attributes_sort_order();
		}
		elseif( $object_type == 'product_page_product_attribute_group' ) {
			$data = $this->update_attributes_groups_sort_order();
		} 
		/**
		 * end edit
		 */
		else {
            wp_send_json_error(__( 'Niestety tego typu obiektu nie można obecnie posortować', 'fotobudka' ),400);
            wp_die();
        }        
        

        if ( $data === false ) {
            wp_send_json_error(__( 'Sortowanie nie powidło się. Spróbuj ponownie.', 'fotobudka' ),400);
            wp_die();
        }
        
        wp_send_json_success(__( 'Kolejność zaaktalizowana pomyślnie.', 'fotobudka' ));
        wp_die();
    }




    private function update_taxonomy_sort_order(){
        $order_array = [];
        $order = Tools::get_value('order');

        foreach( (array) $order as $post_id ){
			$post_id = sanitize_key( $post_id );
			$order_array[] = str_replace( ['post-', 'tag-', 'attribute-', 'attribute-group-'], '', $post_id );
        }      
        
        foreach( (array) $order_array as $term_id ) {
			// Get the position in the array
			$position = array_search( $term_id, $order_array );
			$position = abs( $position + 1 );

			// Update the term_order
			update_term_meta( $term_id, 'term_order', $position );
		}
		return;
    }




    private function update_post_sort_order(){

        $order_array = [];
        $order = Tools::get_value('order');
        $taxonomy = sanitize_key(Tools::get_value('taxonomy'));
        $taxonomy_term = sanitize_key(Tools::get_value('taxonomy_term'));
        $start = Tools::get_value('start');
        $post_type = Tools::get_value('post_type');


        foreach( (array) $order as $post_id ){
			$post_id = sanitize_key( $post_id );
			$order_array[] = str_replace( ['post-', 'tag-', 'attribute-', 'attribute-group-'], '', $post_id );
        } 


		// Check if the combination of post type and taxonomy	
		$inside_tax = false;
		if( !empty($taxonomy) && !empty($taxonomy_term) ) {
            $filter_inside_posts = apply_filters('wtm_sortable_post_inside_tax',[]);
            if( is_array( $filter_inside_posts ) ){
                foreach ( $filter_inside_posts as $combo ) {
                    if($combo['post_type'] === $post_type && $combo['taxonomy'] === $taxonomy){
                        $inside_tax = true;
                        break;
                    }
                }
            }
        }
        

		if ( ! $inside_tax ) {
		  global $wpdb;

		  // Select items based on the starting point
		  $wpdb->query( "SELECT @i:= $start-1" );

		  // Order needs to be a comma separated string
		  $order_array = esc_sql( implode( ', ', $order_array ) );

		  // Insert the new order
		  $new_order = $wpdb->query(
			  "UPDATE {$wpdb->posts} SET menu_order = ( @i:= @i+1 )
			  WHERE ID IN ( $order_array ) ORDER BY FIELD( ID, $order_array );"
		  , ARRAY_A );
		  return $new_order;
		} else {
		  foreach( (array) $order_array as $term_id ) {
			// Get the position in the array
			$position = array_search( $term_id, $order_array );
			$position = abs( $position + 1 );
			
			// Update the term_order
			update_post_meta( $term_id, '_sortable_posts_order_' . $taxonomy . '_' . $taxonomy_term, $position );
		  }
		  return;
		}
    }
    

    private  function update_attributes_sort_order(){	
        $order_array = [];
        $order = Tools::get_value('order');

        foreach( (array) $order as $post_id ){
			$post_id = sanitize_key( $post_id );
			$order_array[] = str_replace( ['post-', 'tag-', 'attribute-', 'attribute-group-'], '', $post_id );
        } 


		foreach( (array) $order_array as $attribute_id ) {
			// Get the position in the array
			$position = array_search( $attribute_id, $order_array );
			$position = abs( $position + 1 );
			$attribute = new Attribute($attribute_id);
			$attribute->position = $position;
			$attribute->save();

		}
		return;
    }
    



    private function update_attributes_groups_sort_order(){	
		$order_array = [];
        $order = Tools::get_value('order');

        foreach( (array) $order as $post_id ){
			$post_id = sanitize_key( $post_id );
			$order_array[] = str_replace( ['post-', 'tag-', 'attribute-', 'attribute-group-'], '', $post_id );
        } 


		foreach( (array) $order_array as $attribute_group_id ) {
			// Get the position in the array
			$position = array_search( $attribute_group_id, $order_array );
			$position = abs( $position + 1 );
			$attribute = new Attribute_Group((int)$attribute_group_id);
			$attribute->position = $position;
			$attribute->save();

		}
		return;
	}

}
