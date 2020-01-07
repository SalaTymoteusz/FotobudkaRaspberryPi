<?php

namespace WTM\Controllers;

if ( ! defined( 'ABSPATH' ) ) exit; 

use WTM\Functions;


class Comment extends Functions{

    protected function add_actions(){
        add_action('init', [$this,'disable_comments_admin_bar']);

        add_action('admin_init', [$this,'disable_comments_dashboard']);
        add_action('admin_init', [$this,'remove_support_comments_post_types']);
        add_action('admin_init', [$this,'disable_comments_admin_menu_redirect']);

        add_action('admin_menu', [$this,'disable_comments_admin_menu']);

    }

    protected function add_filters(){
        add_filter('comments_open', [$this,'disable_comments_status'], 20, 2);
        add_filter('pings_open', [$this,'disable_comments_status'], 20, 2);

        add_filter('comments_array', [$this,'disable_comments_hide_existing_comments'], 10, 2);

    }


    public function remove_support_comments_post_types(){
        $post_types = get_post_types();
        foreach ($post_types as $post_type) {
            if(post_type_supports($post_type, 'comments')) {
                remove_post_type_support($post_type, 'comments');
                remove_post_type_support($post_type, 'trackbacks');
            }
        }
    }

    public function disable_comments_status() {
        return false;
    }


    public function disable_comments_hide_existing_comments($comments) {
        return [];
    }


    public function disable_comments_admin_menu() {
        remove_menu_page('edit-comments.php');
    }

    public function disable_comments_admin_menu_redirect() {
        global $pagenow;
        if ($pagenow === 'edit-comments.php') {
            wp_redirect(admin_url()); exit;
        }
    }


    public function disable_comments_dashboard() {
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    }


    public function disable_comments_admin_bar() {
        if (is_admin_bar_showing()) {
            remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
        }
    }

}