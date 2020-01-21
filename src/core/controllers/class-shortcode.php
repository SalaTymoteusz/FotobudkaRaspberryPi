<?php

namespace WTM\Controllers;

if ( ! defined( 'ABSPATH' ) ) exit; 

use WTM\Functions;
use WTM\Model\Template;
use WP_Query;

class Shortcode extends Functions{

    protected function __construct(){
        add_shortcode( 'b', [$this,'display_bold_text'] );
        add_shortcode( 'green', [$this,'display_green_text'] );

        add_shortcode( 'h2', [$this,'display_header_two'] );
        add_shortcode( 'h3', [$this,'display_header_three'] );

        add_shortcode( 'display_branch', [$this,'display_branch'] );
    }

    public function display_bold_text($atts, $content = null)
    {	
        if(is_admin()){
            if(wp_doing_ajax())
                return '<strong>' . $content . '</strong>';
            else
                return '[b]' . $content . '[/b]';
        }
        return '<strong>' . $content . '</strong>';
    }

    public function display_green_text($atts, $content = null){

        if(is_admin()){
            if(wp_doing_ajax())
                return '<span class="text-primary">' . $content . '</span>';
            else
                return '[green]' . $content . '[/green]';
        }
        return '<span class="text-primary">' . $content . '</span>';
    }


    public function display_header_two($atts, $content = null){

        $class = isset($atts['class']) ? $atts['class'] : false;

        remove_filter( 'the_content', 'wpautop' );
        $content = apply_filters('the_content', $content);
        $content = trim($content);
        add_filter( 'the_content', 'wpautop' );

        if(is_admin()){
            if(wp_doing_ajax())
                return '<h2'.($class ? ' class="'.$class.'"' : '').'>' . $content . '</h2>';
            else
                return '[h2'.($class ? ' class="'.$class.'"' : '').']' . $content . '[/h2]';
        }

        return '<h2'.($class ? ' class="'.$class.'"' : '').'>' . $content . '</h2>';
    }


    public function display_header_three($atts, $content = null){

        $class = isset($atts['class']) ? $atts['class'] : false;

        remove_filter( 'the_content', 'wpautop' );
        $content = apply_filters('the_content', $content);
        $content = trim($content);
        add_filter( 'the_content', 'wpautop' );

        if(is_admin()){
            if(wp_doing_ajax())
                return '<h3'.($class ? ' class="'.$class.'"' : '').'>' . $content . '</h3>';
            else
                return '[h3'.($class ? ' class="'.$class.'"' : '').']' . $content . '[/h3]';
        }
        return '<h3'.($class ? ' class="'.$class.'"' : '').'>' . $content . '</h3>';
    }


}