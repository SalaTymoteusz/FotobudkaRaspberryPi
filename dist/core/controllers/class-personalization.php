<?php

namespace WTM\Controllers;

if ( ! defined( 'ABSPATH' ) ) exit; 

use WTM\Functions;
use WP_Customize_Image_Control;

class Personalization extends Functions{

    protected function add_actions(){
        add_action( 'customize_register', [$this, 'add_settings'] );
        add_action( 'customize_register', [$this, 'add_settings_fields'] );
    }


    public function add_settings( $wp_customize ){
        $wp_customize->add_setting( 'footer_logo' );
        $wp_customize->add_setting( 'map_marker' ); 
        
    }
    

    public function add_settings_fields( $wp_customize ){
        
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'footer_logo', array(
            'label'    => __( 'Logo stopki','fotobudka'),
            'section'  => 'title_tagline',
            'settings' => 'footer_logo',
        ) ) );
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'map_marker', array(
            'label'    => __( 'Marker mapy','fotobudka'),
            'section'  => 'title_tagline',
            'settings' => 'map_marker',
        ) ) );
    }
}