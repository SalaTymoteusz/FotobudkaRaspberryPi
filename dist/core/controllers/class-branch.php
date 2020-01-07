<?php

namespace WTM\Controllers;

if ( ! defined( 'ABSPATH' ) ) exit; 

use WTM\Functions;


class Branch extends Functions{
    protected function add_actions(){
        add_action('init', [$this,'init_post_type'],10,3);
    }


    public function init_post_type(){
        $labels = array(
            'name'               => _x( 'Oddziały', 'post type general name', 'fotobudka' ),
            'singular_name'      => _x( 'Oddział', 'post type singular name', 'fotobudka' ),
            'menu_name'          => _x( 'Oddziały', 'admin menu', 'fotobudka' ),
            'name_admin_bar'     => _x( 'Oddział', 'add new on admin bar', 'fotobudka' ),
            'add_new'            => _x( 'Dodaj oddział', 'book', 'fotobudka' ),
            'add_new_item'       => __( 'Dodaj nowy oddział', 'fotobudka' ),
            'new_item'           => __( 'Nowy oddział', 'fotobudka' ),
            'edit_item'          => __( 'Edytuj oddział', 'fotobudka' ),
            'view_item'          => __( 'Zobacz oddział', 'fotobudka' ),
            'all_items'          => __( 'Wszystkie oddziały', 'fotobudka' ),
            'search_items'       => __( 'Szukaj oddziałów', 'fotobudka' ),
            'parent_item_colon'  => __( 'Oddział nadrzedny:', 'fotobudka' ),
            'not_found'          => __( 'Nie znaleziono odziałów.', 'fotobudka' ),
            'not_found_in_trash' => __( 'Brak odziałów w koszu.', 'fotobudka' )
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
            'menu_icon'          => 'dashicons-location-alt',
            'supports'           => ['title','revisions']
        );
    
        register_post_type( 'branch', $args );
    }


}