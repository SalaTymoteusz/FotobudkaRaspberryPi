<?php

namespace WTM\Controllers;

if ( ! defined( 'ABSPATH' ) ) exit; 

use WTM\Functions;


class Product extends Functions{

    protected function add_filters(){
        if(is_admin()){
            add_filter('manage_product_posts_columns', [$this,'add_posts_thumbnail_column'], 0);
        }
    }

    protected function add_actions(){
        add_action('init', [$this,'init_post_type'],10,3);
        add_action( 'pre_get_posts', [$this,'show_all_on_archive_page'] );

        add_action('acf/init', [$this,'register_archive_page_option']);

        if(is_admin()){
            add_action('manage_product_posts_custom_column', [$this,'add_posts_thumbnail_column_content'], 5, 2);
        }
    }


    public function init_post_type(){
        $labels = array(
            'name'               => _x( 'Produkty', 'post type general name', 'fotobudka' ),
            'singular_name'      => _x( 'Produkt', 'post type singular name', 'fotobudka' ),
            'menu_name'          => _x( 'Produkty', 'admin menu', 'fotobudka' ),
            'name_admin_bar'     => _x( 'Produkt', 'add new on admin bar', 'fotobudka' ),
            'add_new'            => _x( 'Dodaj produkt', 'book', 'fotobudka' ),
            'add_new_item'       => __( 'Dodaj nowy produkt', 'fotobudka' ),
            'new_item'           => __( 'Nowy produkt', 'fotobudka' ),
            'edit_item'          => __( 'Edytuj produkt', 'fotobudka' ),
            'view_item'          => __( 'Zobacz produkt', 'fotobudka' ),
            'all_items'          => __( 'Wszystkie produkty', 'fotobudka' ),
            'search_items'       => __( 'Szukaj produktów', 'fotobudka' ),
            'parent_item_colon'  => __( 'Produkt nadrzedny:', 'fotobudka' ),
            'not_found'          => __( 'Nie znaleziono produktów.', 'fotobudka' ),
            'not_found_in_trash' => __( 'Brak produktów w koszu.', 'fotobudka' )
        );
    
        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Opis.', 'fotobudka' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => false,
            'capability_type'    => 'post',
            'has_archive'        => __('produkty','fotobudka'),
            'rewrite'            => array( 'slug' => __('produkty','fotobudka') ),
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-products',
            'supports'           => ['title','thumbnail']
        );
    
        register_post_type( 'product', $args );
    }



    public function show_all_on_archive_page($query){
        if ( !is_admin() && is_post_type_archive( 'product' ) && $query->is_main_query() ) {
            $query->set( 'posts_per_page', -1 );
        }
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



    public function register_archive_page_option(){
        if( !function_exists('acf_add_options_page') )
        return;

        // register options page.
        $option_page = acf_add_options_page(array(
            'page_title'    => __('Ustawienia strony archiwum','fotobudka'),
            'menu_title'    => __('Ustawienia strony archiwum'),
            'menu_slug'     => 'theme-general-settings',
            'capability'    => 'edit_posts',
            'redirect'      => false,
            'parent_slug'   => 'edit.php?post_type=product'
        ));
    }

}