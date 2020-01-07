<?php

namespace WTM\Controllers;

if ( ! defined( 'ABSPATH' ) ) exit; 

use WTM\Functions;

class Contact_Form7 extends Functions{


    public function __construct(){
        if($this->check_plugin_activated()){
            parent::__construct();
        }
    }

    protected function add_actions(){
        add_action( 'wp_enqueue_scripts', [$this,'override_assets'],99);
    }

    protected function add_filters(){
        add_filter('wpcf7_autop_or_not', '__return_false' );
        add_filter('wpcf7_form_elements', [$this, 'remove_span_wrap']);
        add_filter('wpcf7_ajax_json_echo', [$this, 'ajax_feedback_data'],10,2);
    }

    public function remove_span_wrap($content) {
        $content = preg_replace('/<(span).*?class="\s*(?:.*\s)?wpcf7-form-control-wrap(?:\s[^"]+)?\s*"[^\>]*>(.*)<\/\1>/i', '\2', $content);
    
        $content = str_replace('<br />', '', $content);
            
        return $content;
    }

    private function check_plugin_activated(){
        return in_array('contact-form-7/wp-contact-form-7.php',apply_filters('active_plugins', get_option('active_plugins')) );
    }

    public function override_assets(){        

        wp_deregister_script('contact-form-7');
        wp_register_script('contact-form-7', get_template_directory_uri() . '/assets/js/form.min.js', ['jquery'] ,'', true );
        wp_enqueue_script('contact-form-7');

        $wpcf7 = array(
            'apiSettings' => array(
                'root' => esc_url_raw( rest_url( 'contact-form-7/v1' ) ),
                'namespace' => 'contact-form-7/v1',
            ),
        );
        if ( defined( 'WP_CACHE' ) and WP_CACHE ) {
            $wpcf7['cached'] = 1;
        }
        if ( function_exists('wpcf7_support_html5_fallback') && wpcf7_support_html5_fallback() ) {
            $wpcf7['jqueryUi'] = 1;
        }
        wp_localize_script( 'contact-form-7', 'wpcf7', $wpcf7 );
    }

    public function ajax_feedback_data($response, $result){

        if ( 'validation_failed' == $result['status']) {

            foreach ( $response['invalidFields'] as &$field ) {
                $name = str_replace('span.wpcf7-form-control-wrap.','input[name=\'',$field['into']).'\']';
                $field['into'] = $name;
            }
    
        }

        return $response;
    }
}