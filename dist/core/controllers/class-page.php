<?php

namespace WTM\Controllers;

if ( ! defined( 'ABSPATH' ) ) exit; 

use WTM\Functions;

class Page extends Functions{

    protected function add_filters(){

        if(is_admin()){
            add_filter('manage_page_posts_columns', [$this,'add_posts_thumbnail_column'], 0);
        }
    }
    protected function add_actions(){
        add_action('init',[$this,'add_support_excerpt_field']);
        
        if(is_admin()){
            add_action('admin_head', [$this,'posts_thumbnail_column_style']);
            add_action('manage_page_posts_custom_column', [$this,'add_posts_thumbnail_column_content'], 5, 2);
        }
    }

    public function add_support_excerpt_field(){
        add_post_type_support( 'page', 'excerpt' );        
    }


    public function posts_thumbnail_column_style(){
        echo '
        <style>
        .widefat th.column-thumbnail, .widefat td.column-thumbnail { 
            width: 100px;
          }</style>
        ';
        
    }
    public function add_posts_thumbnail_column($columns){
        $columns = array_slice($columns, 0, 1, true) + ["thumbnail" => __('Miniaturka','fotobudka')] + array_slice($columns, 1, count($columns) - 1, true);
        return $columns;
    }



    public function add_posts_thumbnail_column_content($column_name, $id){
        if($column_name === 'thumbnail'){

            if(has_post_thumbnail($id)){
                $image = get_the_post_thumbnail($id,'wtm-admin-thumbnail-mini');
            }
            else{
                $image = sprintf('<img src="%1$s" title="%2$s" alt="%2$s" width="50" height="50">',
                '//cdn4.iconfinder.com/data/icons/solid-part-6/128/image_icon-48.png',
                __('Brak zdjęcia','fotobudka'));
            }

            edit_post_link($image,'','',$id);
        }
    }
}