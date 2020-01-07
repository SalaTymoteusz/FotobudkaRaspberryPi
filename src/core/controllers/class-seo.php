<?php

namespace WTM\Controllers;

if ( ! defined( 'ABSPATH' ) ) exit; 

use WTM\Functions;
use WTM\Model\Tools;
use WTM\Model\Template;

class Seo extends Functions{

    protected function add_actions(){
        add_action('edit_form_after_title', [$this,'display_seo_metabox'],1,1);
        add_action('add_meta_boxes', [$this,'add_seo_metabox']);
        add_action('save_post', [$this,'save_seo_metabox_data']);
        add_action('wp_head',[$this,'display_head_seo_data'],1);


    }

    protected function add_filters(){
        add_filter('document_title_separator', [$this,'set_title_separator']);
        add_filter('document_title_parts', [$this,'remove_tagline_from_document_title'], 100,1);
        add_filter('document_title_parts', [$this,'remove_shortcode_from_document_title'], 10,1);
    }


    public function display_seo_metabox() {
        # Get the globals:
        global $post, $wp_meta_boxes;

        $screen = get_current_screen();

            # Output the "advanced" meta boxes:
        do_meta_boxes( get_current_screen(), 'meta', $post );

            # Remove the initial "advanced" meta boxes:
        unset($wp_meta_boxes[$screen->post_type]['meta']);

    }

    
    public function add_seo_metabox(){

        $post_type = get_post_types(array(
            'public' => true
        ));

       
        foreach ($post_type as $post) {
            if($post == 'attachment')
            continue;
    
            add_meta_box(
                'seo-metabox',
                'Seo',
                [$this,'render_seo_metabox'],
                $post,
                'meta',
                'high'
            );
        }

    }

    public function render_seo_metabox($post){

        $meta_index = get_post_meta($post->ID, 'meta_index', true);
        if($meta_index === ''){
            $meta_index = get_option('blog_public');
        }

        Template::get_template('_partials/admin/seo-metabox',[
            'meta_title' =>  get_post_meta($post->ID, 'meta_title', true),
            'key_words' =>  get_post_meta($post->ID, 'key_words', true),
            'meta_description' =>  get_post_meta($post->ID, 'meta_description', true),
            'slider' =>  get_post_meta($post->ID, 'slider', true),
            'meta_index' => $meta_index,
            'post' => $post
        ]);
    }


    public function save_seo_metabox_data($post_id){   
        
        if (array_key_exists('meta_index', $_POST)) {
            update_post_meta(
                $post_id,
                'meta_index',
                (int)$_POST['meta_index']
            );
        }
        if (array_key_exists('meta_title', $_POST)) {
            update_post_meta(
                $post_id,
                'meta_title',
                esc_html($_POST['meta_title'])
            );
        }
        if (array_key_exists('key_words', $_POST)) {
            update_post_meta(
                $post_id,
                'key_words',
                esc_html($_POST['key_words'])
            );
        }
        if (array_key_exists('meta_description', $_POST)) {
            update_post_meta(
                $post_id,
                'meta_description',
                esc_html($_POST['meta_description'])
            );
        }
        if (array_key_exists('slider', $_POST)) {
            update_post_meta(
                $post_id,
                'slider',
                $_POST['slider']
            );
        }
        
    }



    public function set_title_separator($sep){
        return '|';
    }


    public function remove_shortcode_from_document_title($title){

        foreach($title as $key => $title_part){
            $title[$key] = Tools::strip_shortcodes($title_part);
        }
        return $title;
    }



    public function remove_tagline_from_document_title($title){
        
        if(is_admin())
        return;
        global $post;
    
        if(isset($title['tagline'])){
            unset($title['tagline']);
        }
    
    
        if(is_archive()){
            $postTypeLabels = get_queried_object(); 
            $title['title'] = $postTypeLabels->label;
        }
        else if(!empty($post)){
            $meta_title = get_post_meta($post->ID, 'meta_title', true);
            if($meta_title && trim($meta_title) !== ''){
                $title['title'] = $meta_title;
                if(isset($title['site'])){
                    unset($title['site']);
                }
            }
        }
        return $title;
    }


    public static function get_document_title($post = null){
        $post = get_post( $post );

        if ( ! $post ) {
            return '';
        }
    
        $title = apply_filters( 'pre_get_document_title', '' );
        if ( ! empty( $title ) ) {
            return $title;
        }
      
        $title = array(
            'title' => $post->post_title,
        );
     
     
        // Append the description or site title to give context.
        if ( $post->ID === get_option('page_on_front') ) {
            $title['tagline'] = get_bloginfo( 'description', 'display' );
        } else {
            $title['site'] = get_bloginfo( 'name', 'display' );
        }
     
        $sep = apply_filters( 'document_title_separator', '-' );
    
        $title = implode( " $sep ", array_filter( $title ) );
        $title = wptexturize( $title );
        $title = convert_chars( $title );
        $title = esc_html( $title );
        $title = capital_P_dangit( $title );
     
        return $title;
    }


    public function display_head_seo_data(){
        global $post,$wp_query;
        if(!isset($post) || empty($post))
            return;


        $meta_index = get_option('blog_public');
        if(!$meta_index){
            $index = "noindex, nofollow";
            printf("<meta name='googlebot' content='noindex, nofollow' />" );
        }
        else{
            $meta_index = get_post_meta($post->ID, 'meta_index', true);
            if($meta_index === ''){
                $meta_index = 1;
            }
            $index = !$meta_index ? 'noindex, nofollow' : 'index, follow';
        }
        
        if(is_archive()){
            $postTypeLabels = get_queried_object(); 
            $title = $postTypeLabels->label . ' | ' . get_bloginfo('name');
        }
        else{
            $title = apply_filters('the_title',$post->post_title); 
            if((int)$post->ID === (int)get_option('page_on_front'))
                $title = get_bloginfo("name");
            if(get_post_meta($post->ID, 'meta_title', true))
                $title = get_post_meta($post->ID, 'meta_title', true);
            
        }
    

        $description = '';
        $keywords = '';
        $url = get_the_permalink($post->ID);
        $site_name = get_bloginfo('name');
        $locale = str_replace('-', '_', get_bloginfo('language'));


        if( get_post_meta($post->ID, 'meta_description', true) )
            $description = get_post_meta($post->ID, 'meta_description', true);
        elseif( get_bloginfo( 'description' ) )
            $description = get_bloginfo( 'description' );

        if(get_post_meta($post->ID, 'key_words', true))
            $keywords = get_post_meta($post->ID, 'key_words', true);
        
        $image = false;
        if($thumb = get_post_thumbnail_id( $post->ID )){
            $image = wp_get_attachment_image_src( $thumb, 'full', false );
        }

        Template::get_template('_partials/global/head',[
            'index' => $index,
            'description' => $description,
            'keywords' => $keywords,
            'url' => $url,
            'title' =>  $title,
            'locale' => $locale,
            'post' => $post,
            'image' => $image
        ]);
    }
}