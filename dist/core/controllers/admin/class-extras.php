<?php

namespace WTM\Controllers\Admin;

if ( ! defined( 'ABSPATH' ) ) exit; 

class Extras extends Admin{

    protected function add_actions(){
        add_action('acf/init', [$this,'add_page']);
    }


    public function add_page(){
        if( !function_exists('acf_add_options_page') )
        return;

        // register options page.
        $option_page = acf_add_options_page(array(
            'page_title'    => __('Dodatkowe','fotobudka'),
            'menu_title'    => __('Dodatkowe','fotobudka'),
            'menu_slug'     => 'extras',
            'capability'    => 'edit_posts',
            'redirect'      => false,
            'icon_url'      => 'dashicons-plus-alt',
            'autoload'      => true,
        ));
    }
}