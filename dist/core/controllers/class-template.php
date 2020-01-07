<?php

namespace WTM\Controllers;

if ( ! defined( 'ABSPATH' ) ) exit; 

use WTM\Functions;
use WTM\Model\Minifier\Minifier;
use WTM\Model\Template as TemplateModel;
use WTM\Model\Tools;

class Template extends Functions{

    protected function remove_actions(){
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'feed_links', 2);
        remove_action('wp_head', 'index_rel_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'feed_links_extra', 3);
        remove_action('wp_head', 'start_post_rel_link', 10, 0);
        remove_action('wp_head', 'parent_post_rel_link', 10, 0);
        remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
    }
    

    protected function add_actions(){
        add_action('init',[$this,'set_global_variables']);
        add_action('init',[$this,'set_optimization']);
        add_action('after_setup_theme', [$this,'add_supports']);
        add_action( 'wp_enqueue_scripts', [$this,'remove_assets'] );
        add_action( 'wp_enqueue_scripts', [$this,'add_assets'] );
        add_action( 'wp_footer', [$this,'add_maps_script'], 1);
        add_action( 'wp_footer', [$this,'add_analitics_script'], 99);
        add_action( 'wp_footer', [$this,'display_cookie_popup'], 99);
    }


    protected function add_filters(){
        
        add_filter('sanitize_title',['WTM\Model\Tools','strip_shortcodes'],1,1);
        add_filter('the_generator', [$this,'secure_generator'], 10, 2 );
        add_filter('xmlrpc_enabled', '__return_false');

        add_filter('the_content',['WTM\Model\Tools','add_hard_space_between_polish_words'],99,1);
        add_filter('the_title',['WTM\Model\Tools','add_hard_space_between_polish_words'],99,1);

        add_filter('the_title', 'do_shortcode' );

        add_filter('script_loader_src',  [__CLASS__,'set_assets_version'], 999, 2 );
        add_filter('style_loader_src',  [__CLASS__,'set_assets_version'], 999, 2 );
        // add_filter( 'style_loader_tag',  [$this,'set_assets_async'], 10, 4 );

        add_filter('tiny_mce_plugins', [$this,'disable_emojis_tinymce'] );
        add_filter('wp_resource_hints', [$this,'disable_emojis_remove_dns_prefetch'], 10, 2 );

        add_filter('body_class', [$this,'add_body_class'], 10);
    }


    protected function remove_filters(){
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    }


    public function secure_generator( $generator, $type ) {
        return '';
    }



    public function set_global_variables(){
        global $site_name;
        $site_name = get_bloginfo('name'); 
    }


    public function set_optimization(){
        new Minifier([
            'enable' => get_option('enable_optimizer') > 0,
            'external_js' => get_option('external_js') > 0,
            'external_css' => get_option('external_css') > 0,
        ]);
    }

    public function add_supports(){
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support( 'custom-logo' );
        add_theme_support('post-formats', array('gallery') );
    }



    public function remove_assets(){
        wp_deregister_style('twentyfifteen-style');
        wp_deregister_script('wp-embed');
        wp_deregister_script('jquery');
        wp_deregister_style('wp-block-library');
    }


    public function add_assets(){
        global $site_name;
        // wp_register_style('fontawesome', '//use.fontawesome.com/releases/v5.9.0/css/all.css');
        wp_register_style('font', '//fonts.googleapis.com/css?family=Montserrat:300,400,600,700&display=swap&subset=latin-ext');
        wp_register_style('slider', '//unpkg.com/flickity@2/dist/flickity.min.css');
        wp_register_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css');
        wp_register_style('stylesheet',  get_template_directory_uri() . '/assets/css/template.min.css');
    
    
    
        // REGISTER SCRIPTS
        wp_register_script('jquery', '//cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js', [], null, true);
        // wp_register_script('popper_js', '//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js', array('jquery'),'', true );
        wp_register_script('bootstrap_js', '//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js', ['jquery'] ,'', true );
        wp_register_script('flickity_js', '//cdnjs.cloudflare.com/ajax/libs/flickity/2.2.1/flickity.pkgd.min.js', ['jquery'] ,'', true );
        wp_register_script('fontawesome_js', '//kit.fontawesome.com/92f5bd0674.js', ['jquery'] ,'', true );
    
        // wp_register_script('markerclusterer_js', get_template_directory_uri() . '/js/markerclusterer.min.js', array('jquery') ,'', true );
        wp_register_script('images_js', get_template_directory_uri() . '/assets/js/lazy.min.js', ['jquery'] ,'', true );
        wp_register_script('global_js', get_template_directory_uri() . '/assets/js/global.min.js', ['jquery'] ,'', true );
         wp_register_script('parallax', get_template_directory_uri() . '/assets/js/simpleParallax.min.js', [] ,'', true );
    
        // LOAD FILES

        wp_enqueue_style('font');  
        wp_enqueue_style('bootstrap');  
        wp_enqueue_style('stylesheet');
    
    
        wp_enqueue_script('jquery');
    
        // wp_enqueue_script('markerclusterer_js');
        wp_enqueue_script('images_js');

        
        wp_enqueue_script('bootstrap_js');
        wp_enqueue_script('flickity_js');
        wp_enqueue_script('parallax');
        wp_enqueue_script('global_js');
        

        wp_localize_script( 
            'global_js', 
            'wtm_global_vars', 
            array( 
                '_wtm_nonce' => wp_create_nonce( 'wtm_ajax_action' ),
                '_wtm_ajax' => admin_url( 'admin-ajax.php' ),
                '_wtm_site_name' => $site_name,
                '_wtm_map_marker' => get_theme_mod('map_marker')
            ));
        
        wp_enqueue_script('fontawesome_js');
    }


    public static function set_assets_version($src, $handle){
        $home_url = get_home_url();
        $home_path = Tools::get_home_path();

        if( strpos($src, $home_url) ===  false)
        return $src;   

        $src_url = explode('?', $src)[0];
        $src_path = str_replace($home_url, $home_path, $src_url);
        if (!file_exists($src_path)) {
            return $src;   
        }
        
        $version = md5(filemtime($src_path));
        $parsed = parse_url($src);

        if(isset($parsed['query']) && !empty($parsed['query'])){
            parse_str($parsed['query'], $params);
            $params['ver'] = $version;
            $params = http_build_query($params);

            $src = "{$parsed['scheme']}://{$parsed['host']}{$parsed['path']}?{$params}";

        }else{
            $src = add_query_arg( 'ver', $version, $src_url );
        }
        return $src;
    }





    public function set_assets_async( $html, $handle, $href, $media ){

        $html = str_replace("media='$media'", "media='nope!' onload='this.media=\"$media\"'", $html);  
    
        return $html;
    }    



    public function add_maps_script(){
        if($key = get_option('map_key')){
            TemplateModel::get_template('_partials/global/map-script',['map_key' => $key]);
        }
    }

    public function add_analitics_script(){
        if($key = get_option('analitics_id')){
            TemplateModel::get_template('_partials/global/analitics',['analitics_id' => $key]);
        }
    }


    public function add_body_class($classes) {

        $classes[] = "user-".Tools::get_client_browser();
        $classes[] = "user-".Tools::get_client_device();
        return $classes;
    }
    


    public function disable_emojis_tinymce( $plugins ) {
        if ( is_array( $plugins ) ) {
            return array_diff( $plugins, array( 'wpemoji' ) );
        } else {
            return array();
        }
    }


    public function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
        if ( 'dns-prefetch' == $relation_type ) {
            /** This filter is documented in wp-includes/formatting.php */
            $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
        
            $urls = array_diff( $urls, array( $emoji_svg_url ) );
        }
        return $urls;
    }

    public function display_cookie_popup(){
        if(!isset($_COOKIE['ALLOW_COOKIE']) || empty($_COOKIE['ALLOW_COOKIE'])){
            TemplateModel::get_template_part('global/cookie','popup');
        }
    }

}
