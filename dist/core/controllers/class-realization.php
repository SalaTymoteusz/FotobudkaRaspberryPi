<?php

namespace WTM\Controllers;

if ( ! defined( 'ABSPATH' ) ) exit; 

use WTM\Functions;


class Realization extends Functions{

    protected function add_filters(){

        if(is_admin()){
            add_filter('manage_realization_posts_columns', [$this,'add_posts_thumbnail_column'], 0);
        }
    }


    protected function add_actions(){
        add_action('init', [$this,'init_post_type'],10,3);
        if(is_admin()){
            add_action('manage_realization_posts_custom_column', [$this,'add_posts_thumbnail_column_content'], 5, 2);
        }
    }

    public function init_post_type(){
        $labels = array(
            'name'               => _x( 'Realizacje', 'post type general name', 'fotobudka' ),
            'singular_name'      => _x( 'Realizacja', 'post type singular name', 'fotobudka' ),
            'menu_name'          => _x( 'Realizacje', 'admin menu', 'fotobudka' ),
            'name_admin_bar'     => _x( 'Realizacja', 'add new on admin bar', 'fotobudka' ),
            'add_new'            => _x( 'Dodaj realizacje', 'book', 'fotobudka' ),
            'add_new_item'       => __( 'Dodaj nową realizacje', 'fotobudka' ),
            'new_item'           => __( 'Nowa realizacja', 'fotobudka' ),
            'edit_item'          => __( 'Edytuj realizacje', 'fotobudka' ),
            'view_item'          => __( 'Zobacz realizacje', 'fotobudka' ),
            'all_items'          => __( 'Wszystkie realizacje', 'fotobudka' ),
            'search_items'       => __( 'Szukaj realizacji', 'fotobudka' ),
            'parent_item_colon'  => __( 'Realizacja nadrzedna:', 'fotobudka' ),
            'not_found'          => __( 'Nie znaleziono realizacji.', 'fotobudka' ),
            'not_found_in_trash' => __( 'Brak realizacji w koszu.', 'fotobudka' )
        );
    
        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Opis.', 'fotobudka' ),
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-portfolio',
            'supports'           => ['title','revisions','thumbnail','excerpt']
        );
    
        register_post_type( 'realization', $args );
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