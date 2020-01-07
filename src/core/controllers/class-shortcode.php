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





    public function display_branch(){

        $args = [
            'post_type' => 'branch',
            'posts_per_page' => -1,
            'fields' => 'ids'
        ];
        $branches_ids = (new WP_Query($args))->posts;

        if(!$branches_ids)
        return;

        $branches = [];

        foreach($branches_ids as $id){
            $branch = [
                'id' => $id,
                'title' => get_the_title($id),
                'phone' => get_field('phone',$id),
                'phone_mobile' => get_field('phone_mobile',$id),
                'email' => get_field('email',$id),
                'address' => get_field('address',$id),
                'location' => get_field('location',$id),
                'location_link' => get_field('location_link',$id),
            ];


            $branches[] = $branch;
        }

        wp_localize_script( 'global_js', 'wtm_branches', $branches );

        remove_filter( 'the_content', 'wpautop' );
        $branches_output = Template::get_template('_partials/section/section-branch',['return'=>true,'branches' => $branches]);
        add_filter( 'the_content', 'wpautop' );
        
        return $branches_output;
    }

}