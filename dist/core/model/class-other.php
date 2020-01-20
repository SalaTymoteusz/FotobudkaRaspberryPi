<?php

namespace WTM\Model;

if ( ! defined( 'ABSPATH' ) ) exit; 


class Other {
    
    
    public function breadcrumbs() {
        global $post;
        
        $options = [
            'show_on_home' => false,
            'home_label' => __('Home','fotobudka'),
            'return' => false
        ];
        
        $breadcrumbs = [];
        
        if (is_home() || is_front_page()) {
            
            if ($options['show_on_home']){
                $breadcrumbs[] = [
                    'active' => true,
                    'title' => $options['home_label'],
                    
                ];
            }
            
        } else {

            $homeLink = get_bloginfo('url');
            $breadcrumbs[] = [
                'title' => $options['home_label'],
                'link' => $homeLink,
            ];
            
            if ( is_category() ) {
                $cat = get_category(get_query_var('cat'), false);
                
                if ($cat->parent != 0) {
                    $parent_id = $cat->parent;
                    $breadcrumbs_temp = [];
                    
                    while ($parent_id) {
                        $cat_parent = get_category($parent_id, false);
                        $breadcrumbs_temp[] = array(
                            'title' => get_cat_name( $parent_id ),
                            'link' => get_category_link( $parent_id ),
                        );
                        $parent_id  = $cat_parent->post_parent;
                    }
                    
                    $breadcrumbs_temp = array_reverse($breadcrumbs_temp);
                    $breadcrumbs = array_merge($breadcrumbs,$breadcrumbs_temp);
                }
                
                $breadcrumbs[] = [
                    'title' => single_cat_title('', false),
                    'active' => true,
                ];
                
            } elseif ( is_search() ) {
                $breadcrumbs[] = [
                    'title' => __('Wyniki wyszukiwania: ','fotobudka') . get_search_query(),
                    'active' => true,
                ];
                
            } elseif ( is_day() ) {
                $breadcrumbs[] = [
                    'title' => get_the_time('Y'),
                    'link' => get_year_link(get_the_time('Y')),
                ];
                $breadcrumbs[] = [
                    'title' => get_the_time('F'),
                    'link' => get_month_link(get_the_time('Y'),get_the_time('m')),
                ];
                $breadcrumbs[] = [
                    'title' => get_the_time('d'),
                    'active' => true,
                ];
                
            } elseif ( is_month() ) {
                $breadcrumbs[] = [
                    'title' => get_the_time('Y'),
                    'link' => get_year_link(get_the_time('Y')),
                ];
                $breadcrumbs[] = [
                    'title' => get_the_time('F'),
                    'active' => true,
                ];
            } elseif ( is_year() ) {
                $breadcrumbs[] = [
                    'title' => get_the_time('Y'),
                    'active' => true,
                ];
                
                
            } elseif ( is_single() && !is_attachment() ) {
                
                
                if ( get_post_type() != 'post' ) {
                    $post_type = get_post_type_object(get_post_type());
                    
                    $breadcrumbs[] = [
                        'title' => $post_type->labels->name,
                        'link' => get_post_type_archive_link( get_post_type() ),
                    ];
                    $breadcrumbs[] = [
                        'title' => get_the_title(),
                        'active' => true,
                    ];
                } else {
                    $cat = get_the_category(); 
                    $cat = $cat[0];
                    $cats = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
                    if ($showCurrent == 0) $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
                    echo $cats;
                    
                    
                    $breadcrumbs[] = [
                        'title' => get_the_title(),
                        'active' => true,
                    ];
                }
                
            } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
                $post_type = get_post_type_object(get_post_type());
                $breadcrumbs[] = [
                    'title' => $post_type->labels->name,
                    'active' => true,
                ];
                
            } elseif ( is_attachment() ) {
                $parent = get_post($post->post_parent);
                $cat = get_the_category($parent->ID);
                $cat = $cat[0];
                echo get_category_parents($cat, TRUE, '');
                echo '<li><a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a></li>';
                
                
                $breadcrumbs[] = [
                    'title' => get_the_title(),
                    'active' => true,
                ];
                
            } elseif ( is_page() && !$post->post_parent ) {
                
                $breadcrumbs[] = [
                    'title' => get_the_title(),
                    'active' => true,
                ];
                
            } elseif ( is_page() && $post->post_parent ) {
                $parent_id  = $post->post_parent;
                $breadcrumbs_temp = [];
                while ($parent_id) {
                    $page = get_page($parent_id);
                    $breadcrumbs_temp[] = array(
                        'title' => get_the_title($page->ID),
                        'link' => get_permalink($page->ID)
                    );
                    $parent_id  = $page->post_parent;
                }
                $breadcrumbs_temp = array_reverse($breadcrumbs_temp);
                $breadcrumbs = array_merge($breadcrumbs,$breadcrumbs_temp);
                
                $breadcrumbs[] = [
                    'title' => get_the_title(),
                    'active' => true,
                ];
                
                
            } elseif ( is_tag() ) {
                
                $breadcrumbs[] = [
                    'title' => single_tag_title('', false),
                    'active' => true,
                ];
                
            } elseif ( is_author() ) {
                global $author;
                $userdata = get_userdata($author);
                
                $breadcrumbs[] = [
                    'title' => __('Articles posted by ','fotobudka') . $userdata->display_name,
                    'active' => true,
                ];
                
            } 
            
            elseif ( is_404() ) {
                $breadcrumbs[] = [
                    'title' => __('Error 404','fotobudka'),
                    'active' => true,
                ];
            }
        }  
        
        if(!empty($breadcrumbs)){
            
            Template::get_template('_partials/global/breadcrumbs',[
                'breadcrumbs' => $breadcrumbs,
                'return' => (int)$options['return']
                ]
            );
        }
    }
    
    public function pagination($display = true, $pages = '', $range = 2)
    {  
        $showitems = ($range * 2)+1;  
        
        global $paged;
        if(empty($paged)) $paged = 1;
        
        if($pages == '')
        {   
            global $wp_query;
            $pages = $wp_query->max_num_pages;
            
            if(!$pages)
            {
                $pages = 1;
            }
        }       
        
        $html = "<nav id='pagination'><ul class='pagination'>";
        
        // if($paged > 2 && $paged > $range+1 && $showitems < $pages){
            $html .= sprintf("<li%s><%s%s>&laquo;</%s></li>",
            ((int)$paged == 1) ? ' class="disabled bg-gray-light"' : ' class="bg-gray-light"',
            ((int)$paged == 1) ? 'span' : 'a',
            ((int)$paged != 1) ? ' href="'.get_pagenum_link(1).'"' : '',
            ((int)$paged == 1) ? 'span' : 'a'
        );
        
        $html .= sprintf("<li%s><%s%s>&lsaquo;</%s></li>",
        ((int)$paged == 1) ? ' class="disabled text-white bg-gray-dark"' : ' class="text-white bg-gray-dark"',
        ((int)$paged == 1) ? 'span' : 'a',
        ((int)$paged != 1) ? ' href="'.get_pagenum_link($paged - 1).'"' : '',
        ((int)$paged == 1) ? 'span' : 'a'
    );
    // }
    
    
    for ($i=1; $i <= $pages; $i++)
    {
        if (( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
        {
            $html .= ($paged == $i) ? '<li class="active"><span>'.$i."</span></li>":"<li><a href='".get_pagenum_link($i)."' >".$i."</a></li>";
        }
    }
    
    // if ($paged < $pages && $showitems < $pages){
        
        
        $html .= sprintf("<li%s><%s%s>&rsaquo;</%s></li>",
        ((int)$paged == $pages) ? ' class="disabled text-white bg-gray-dark"' : ' class="text-white bg-gray-dark"',
        ((int)$paged == $pages) ? 'span' : 'a',
        ((int)$paged != $pages) ? ' href="'.get_pagenum_link($paged + 1).'"' : '',
        ((int)$paged == $pages) ? 'span' : 'a'
    );
    // }
    // if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages){
        
        $html .= sprintf("<li%s><%s%s>&raquo;</%s></li>",
        ((int)$paged == $pages) ? ' class="disabled bg-gray-light"' : ' class="bg-gray-light"',
        ((int)$paged == $pages) ? 'span' : 'a',
        ((int)$paged != $pages) ? ' href="'.get_pagenum_link($pages).'"' : '',
        ((int)$paged == $pages) ? 'span' : 'a'
    );
    // }
    $html .= "</ul></nav>\n";
    
    if ($display) {
        echo $html;
    }
    else{
        return $html;
    }        
}
}