<?php

namespace WTM\Controllers;

if ( ! defined( 'ABSPATH' ) ) exit; 

use WTM\Functions;

class Mod_Rewrite extends Functions{

    protected function add_actions(){
        add_action('init', [$this,'override_base_params'], 0, 10);
        add_action('init', [$this,'add_posts_rewrite_rules'], 0, 10);
    }


    public function override_base_params() {
        global $wp_rewrite;
        $wp_rewrite->author_base        = 'autor';
        $wp_rewrite->search_base        = 'szukaj';
        $wp_rewrite->comments_base      = 'komentarz';
        $wp_rewrite->pagination_base    = 'strona';
    }

    public function add_posts_rewrite_rules() {

        if(!is_admin())
        return;

        global $wp_rewrite;

        if($page_for_posts = get_option('page_for_posts')){
            $page_path =  get_page_uri( $page_for_posts );
    
            $wp_rewrite->add_rule( 
                $page_path.'/'.$wp_rewrite->pagination_base.'/?([0-9]{1,})/?$', 
                'index.php?pagename='.$page_path.'&paged=$matches[1]', 
                'top' );
            $wp_rewrite->add_rule( 
                $page_path.'/(.+?)/'.$wp_rewrite->pagination_base.'/?([0-9]{1,})/?$', 
                'index.php?category_name=$matches[1]&paged=$matches[2]', 
                'top' );
            $wp_rewrite->add_rule( 
                $page_path.'/(.+?)/([^/]+)/?$', 
                'index.php?category_name=$matches[1]&name=$matches[2]', 
                'top' );
    
        }
        
    
        $wp_rewrite->flush_rules();
    }
}
