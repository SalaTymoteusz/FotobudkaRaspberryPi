<?php

namespace WTM\Controllers;

if ( ! defined( 'ABSPATH' ) ) exit; 

use WTM\Functions;

class Sortable extends Functions {

    private $sortable_posts = [];

    private $sortable_taxonomies = [];

    public function __construct(){
        $this->set_sortable_posts();
        $this->set_sortable_taxonomies();
        parent::__construct();
    }

    protected function add_actions(){
        add_action( 'pre_get_posts', [$this, 'set_orderby_posts'],99999 );
    }

    protected function add_filters(){
        add_filter( 'terms_clauses', [$this, 'set_orderby_taxonomies'], 10, 3 );
    }


    private function set_sortable_posts()
    {        
        $this->sortable_posts = get_option('sortable_posts', []);
    }

    private function set_sortable_taxonomies()
    {        
        $this->sortable_taxonomies =  get_option('sortable_taxonomies', []);
    }



    public function set_orderby_posts($query){
        global $wp_query, $post;
        //Get the post type
        $post_type = '';
        if(isset($query->query_vars['post_type'])) {
            $post_type = $query->query_vars['post_type'];
        }
        // On the archive page of the taxonomy term the post type on the query is page
        if( is_archive() && $post_type === 'page' ) {
            $post_type = get_post_type();
        }

        if( !$post_type && is_category() ) {
            $post_type = 'post';
        }
        elseif(is_tax()){
            $post_type = get_taxonomy( get_queried_object()->taxonomy )->object_type[0];
        }

        //No Post type? Stop!
        if(empty($post_type)) {
            return;
        }

        if(isset($this->sortable_posts[$post_type]) && !empty($this->sortable_posts[$post_type])){

            $setting = $this->sortable_posts[$post_type];

            $taxonomy = $taxonomy_term = '';
            $queried = get_queried_object();
            if(isset($queried->taxonomy)) {
                $taxonomy = $queried->taxonomy;
                $taxonomy_term = $queried->slug;
            } elseif(isset($query->query['tax_query'])){
                if ( empty( $query->query['tax_query'] ) ) {
                return false;
            }
				  // This check if a custom WP_query
                $taxonomy = $query->query['tax_query'][0]['taxonomy'];
                $taxonomy_term = $query->query['tax_query'][0]['terms'];
            }
            $inside_tax = 0;
				// Check if array need to sorted based the term parent
            if($taxonomy !== '') {
                $filter_inside_posts = apply_filters('sortable_post_inside_tax', []);
                if( is_array( $filter_inside_posts ) ){
                    foreach ( $filter_inside_posts as $combo ) {
                        if($combo['post_type'] === $post_type && $combo['taxonomy'] === $taxonomy){
                            $inside_tax = true;
                            break;
                        }
                    }
                }
            }

            $sortby = 'DESC';
            $sort = 'date';
            if($setting == 'custom'){
                $sort = !$inside_tax ? 'menu_order' : 'meta_value_num';
                $sortby = 'ASC';
            }
            elseif($setting == 'rand'){
                $sort = 'rand';
            }
            elseif(strpos($setting,'|') !== false){
                $sortby = explode('|',$setting)[1];
                $sort = explode('|',$setting)[0];
            }

            if ( !$inside_tax ) {
                
                // if( is_admin() ) {
                //     $query->set( 'orderby', $sort );
                //     $query->set( 'order', $sortby );
                // } else {
                    
                // User submitted orderby takes priority to allow for override in frontend
                if( ! isset($query->query_vars['orderby']) || empty($query->query_vars['orderby'])) {
                    $query->set( 'orderby', $sort );
                }

                // User submitted order takes priority to allow for override in frontend
                if( ! isset($query->query_vars['order']) || empty($query->query_vars['order'])) {
                    $query->set( 'order', $sortby);
                }
                // }
            }
            else {
                if( is_admin() || !is_archive() && isset($query->query['tax_query'])) {

                    if( ! isset($query->query_vars['orderby']) || empty($query->query_vars['orderby'])) {
                        $query->set( 'orderby', $sort );
                    }
    
                    if( ! isset($query->query_vars['order']) || empty($query->query_vars['order'])) {
                        $query->set( 'order', $sortby);
                    }

                    if( $setting == 'custom'){
                        $query->set( 'meta_query', [
                            'relation' => 'OR',
                            [  
                            'key' => '_sortable_posts_order_' . $taxonomy . '_' . $taxonomy_term,
                            'compare' => '!=',
                            'value' => ''
                            ],
                            [  
                            'key' => '_sortable_posts_order_' . $taxonomy . '_' . $taxonomy_term,
                            'compare' => 'NOT EXISTS',
                            'value' => ''
                            ]
                        ]);
                    }
                }
                else{
                    $args = $wp_query->query_vars;
                    if( ! isset($args['orderby']) || empty($args['orderby'])) {
                        $args['orderby'] = $sort;
                    }
    
                    if( ! isset($args['order']) || empty($args['order'])) {
                        $args['order'] = $sortby;
                    }
                    if( $setting == 'custom'){
                        $args[ 'meta_query'] = [ 
						    'relation' => 'OR',
						    [ 
							  'key' => '_sortable_posts_order_' . $taxonomy . '_' . $taxonomy_term,
							  'compare' => '!=',
							  'value' => ''
                            ],
						    [  
							  'key' => '_sortable_posts_order_' . $taxonomy . '_' . $taxonomy_term,
							  'compare' => 'NOT EXISTS',
							  'value' => ''
                        ]];
                    }

                    $wp_query = new WP_Query($args);
                    return $wp_query;
                }

            }
                        
        }
       

        return $query;
        
    }



    public function set_orderby_taxonomies($clauses, $taxonomies, $args){
        global $wpdb;

        if(function_exists('get_current_screen')) {
            $screen = get_current_screen();
                if( !empty( $screen ) ) {

                // if( $screen->base === 'edit-tags' || $screen->base === 'post' || $screen->base === 'edit' ) {
                  // 	return $clauses;
                // }
                if( $screen->base === 'post' || $screen->base === 'edit' ) {
                    return $clauses;
                }
            }
        }

        // Need to rework this. Allows users to override orderby param
        // if ( isset( $args['orderby'] ) && $args['orderby'] !== 'name' ){
            // return $clauses;
        // }


        // taxonomies might come as associative array.
        // make sure $taxonomy_values[0] won't trigger a php warning
        $taxonomy_values = array_values( $taxonomies );

        $taxonomy = $taxonomy_values[0];
        

        // Accept only single taxonomy queries & only if taxonomy is sortable
        if(!isset($this->sortable_taxonomies[$taxonomy]) || empty($this->sortable_taxonomies[$taxonomy])){
                return $clauses;
        }


        $setting = $this->sortable_taxonomies[$taxonomy];

        $sort = isset($args['order']) ? $args['order'] : 'asc';
        $sortby = 'name';


        if( $setting == 'custom' ){
            
            $sortby = "ABS(tm.meta_value)";
        }
        elseif(strpos($setting,'|') !== false){
            $sort = explode('|',$setting)[1];
            $sortby = explode('|',$setting)[0];          

            if ( in_array( $sortby, array( 'term_id', 'name', 'slug', 'term_group' ), true ) ) {
                $sortby = "t.$sortby";
            } elseif ( in_array( $sortby, array( 'count', 'parent', 'taxonomy', 'term_taxonomy_id', 'description' ), true ) ) {
                $sortby = "tt.$sortby";
            } elseif ( 'term_order' === $sortby ) {
                $sortby = 'tr.term_order';
            } elseif ( 'none' == $sortby ) {
                $sortby = '';
            } elseif ( empty( $sortby ) || 'id' == $sortby || 'term_id' === $sortby ) {
                $sortby = 't.term_id';
            } else {
                $sortby = 't.name';
            }  
        }
        else{
            $sortby = 't.name';
        }

        $sort = strtoupper( $sort );

        if ( ! in_array( $sort, array('ASC', 'DESC') ) ) {
            $sort = 'ASC';
        }

        if( $setting == 'custom' ){
            // Join termmeta to terms tables
            $clauses['join'] .= " LEFT JOIN {$wpdb->termmeta} tm ON (t.term_id = tm.term_id AND tm.meta_key = 'term_order')";
            $clauses['orderby'] = "ORDER BY {$sortby} {$sort}, t.name";
        } else{
            $clauses['orderby'] = "ORDER BY {$sortby}";
        }
        
        $clauses['order'] = $sort;          
        
        return $clauses;
    }
}