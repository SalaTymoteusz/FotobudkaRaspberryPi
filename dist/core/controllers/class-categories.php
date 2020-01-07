<?php

namespace WTM\Controllers;

if ( ! defined( 'ABSPATH' ) ) exit; 

use WTM\Functions;


class Categories extends Functions{

    protected function remove_actions(){}

    protected function remove_filters(){}


    protected function add_actions(){
        add_action( 'init', [$this,'init_product_categories_custom_taxonomy'],0);
    }

    protected function add_filters(){
    }


    public function init_product_categories_custom_taxonomy(){ 
        $labels = array(
            'name' => _x( 'Kategorie', 'taxonomy general name' ),
            'singular_name' => _x( 'Kategoria', 'taxonomy singular name' ),
            'search_items' =>  __( 'Znajdź Kategorię' ),
            'all_items' => __( 'Wszystkie Kategorie' ),
            'parent_item' => __( 'Kategoria Nadrzędna' ),
            'parent_item_colon' => __( 'Kategoria Nadrzędna:' ),
            'edit_item' => __( 'Edytuj Kategorię' ), 
            'update_item' => __( 'Aktualizuj Kategorię' ),
            'add_new_item' => __( 'Dodaj nową Kategorię' ),
            'new_item_name' => __( 'Nazwa nowej Kategorii' ),
            'menu_name' => __( 'Kategorie' ),
          );     
         
          register_taxonomy('categories',array('product'), array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'category' ),
          ));

    }

}
