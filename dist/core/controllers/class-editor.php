<?php

namespace WTM\Controllers;

if ( ! defined( 'ABSPATH' ) ) exit; 

use WTM\Functions;

class Editor extends Functions{

    protected function add_filters(){
        add_filter( 'tiny_mce_before_init', [$this,'enable_advanced_buttons'] );
        add_filter('mce_buttons_3', [$this,'enable_more_buttons']);

        add_filter('mce_buttons', [$this,'enable_justify_button_to_mce'], 5);
    }

    public function enable_more_buttons($buttons) {

        $buttons[] = 'fontselect';
        $buttons[] = 'fontsizeselect';
        $buttons[] = 'styleselect';
        $buttons[] = 'backcolor';
        $buttons[] = 'newdocument';
        $buttons[] = 'cut';
        $buttons[] = 'copy';
        $buttons[] = 'charmap';
        $buttons[] = 'hr';
        $buttons[] = 'visualaid';
    
        return $buttons;
    }

    
    public function enable_advanced_buttons( $in ) {

        $in['wordpress_adv_hidden'] = FALSE;

        return $in;
    }
    

    public function enable_justify_button_to_mce($buttons)
    {
        $position = array_search('alignright', $buttons);
    
        if (! is_int($position)) {
    
            return array_merge($buttons, ['alignjustify']);
        }
    
        return array_merge(
            array_slice($buttons, 0, $position + 1),
            ['alignjustify'],
            array_slice($buttons, $position + 1)
        );
    }
}