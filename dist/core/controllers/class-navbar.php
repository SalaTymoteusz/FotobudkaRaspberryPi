<?php

namespace WTM\Controllers;

if ( ! defined( 'ABSPATH' ) ) exit; 

use WTM\Functions;

class Navbar extends Functions{


    protected function add_actions(){
        add_action('after_setup_theme', [$this,'add_menu']);
    }


    public function add_menu(){
        register_nav_menus( array(
            'primary' => __('Menu głowne', 'fotobudka'),
        ) );
    }
}