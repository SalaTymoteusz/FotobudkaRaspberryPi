<?php

namespace WTM\Model;

if ( ! defined( 'ABSPATH' ) ) exit; 


class Template{


    public static function set_assets_version($src, $handle){
        $home_url = get_home_url();
        $home_path = Tools::get_home_path();

        if( strpos($src, $home_url) ===  false)
        return $src;   

        $src_url = explode('?', $src)[0];
        $src_path = str_replace($home_url, $home_path, $src_url);
        if (!file_exists($src_path)) {
            return $src;   
        }
        
        $version = md5(filemtime($src_path));
        $parsed = parse_url($src);

        if(isset($parsed['query']) && !empty($parsed['query'])){
            parse_str($parsed['query'], $params);
            $params['ver'] = $version;
            $params = http_build_query($params);

            $src = "{$parsed['scheme']}://{$parsed['host']}{$parsed['path']}?{$params}";

        }else{
            $src = add_query_arg( 'ver', $version, $src_url );
        }
        return $src;
    }



    public static function get_template( $template_name, $args = array(), $template_path = '' ) {
        $cache_key = sanitize_key( implode( '-', array( 'template', $template_name, $template_path ) ) );
        $template  = (string) wp_cache_get( $cache_key, 'fotobudka' );
    
        if ( ! $template ) {
            $template = self::locate_template( $template_name, $template_path );
            wp_cache_set( $cache_key, $template, 'fotobudka' );
        }
    
        // Allow 3rd party plugin filter template file from their plugin.
        $filter_template = apply_filters( 'wtm_get_template', $template, $template_name, $args, $template_path );
    
        if ( $filter_template !== $template ) {
            if ( ! file_exists( $filter_template ) ) {
                /* translators: %s template */
                echo '<pre>';
                print_r(sprintf( __( '%s nie istnieje.', 'fotobudka' ), '<code>' . $template . '</code>' ));
                echo '</pre>';
                return;
            }
            $template = $filter_template;
        }
    
        $action_args = array(
            'template_name' => $template_name,
            'template_path' => $template_path,
            'located'       => $template,
            'args'          => $args,
        );
    
        if ( ! empty( $args ) && is_array( $args ) ) {
            if ( isset( $args['action_args'] ) ) {
                echo '<pre>';
                print_r(__( 'action_args nie powinny być nadpisywane podczas wywoływania wtm_get_template.', 'fotobudka' ));
                echo '</pre>';
                unset( $args['action_args'] );
            }
            extract( $args ); // @codingStandardsIgnoreLine
        }
    
        do_action( 'wtm_before_template_part', $action_args['template_name'], $action_args['template_path'], $action_args['located'], $action_args['args'] );
        if(isset($args['return']) && $args['return'] == true){
            ob_start();
        }
        include $action_args['located'];
    
        if(isset($args['return']) && $args['return'] == true){
            return ob_get_clean();
        }   
    
        do_action( 'wtm_after_template_part', $action_args['template_name'], $action_args['template_path'], $action_args['located'], $action_args['args'] );
    }





    public static function locate_template( $template_name, $template_path = '' ) {
    
        if ( ! $template_path ) {
            $template_path = get_template_directory();
        }
    
        // Look within passed path within the theme - this is priority.
        $template = locate_template(
            array(
                trailingslashit( $template_path ) . $template_name . '.php',
                $template_name,
            )
        );
    
        if ( ! $template && file_exists(trailingslashit( $template_path ) . $template_name . '.php') ) {
            $template = trailingslashit( $template_path ) . $template_name . '.php';
        }
    
        // Return what we found.
        return apply_filters( 'wtm_locate_template', $template, $template_name, $template_path );
    }







    public static function get_template_part( $slug, $name = '' ) {
        $cache_key = sanitize_key( implode( '-', array( 'template-part', $slug, $name ) ) );
        $template  = (string) wp_cache_get( $cache_key, 'fotobudka' );


        if ( ! $template ) {
            if ( $name ) {
                $template = locate_template(
                    array(
                        "{$slug}-{$name}.php",
                        "/_partials/{$slug}-{$name}.php",
                    )
                );
            }


            if ( ! $template ) {
                $template = locate_template(
                    array(
                        "{$slug}.php",
                        "/_partials/{$slug}.php",
                    )
                );

            }

            wp_cache_set( $cache_key, $template, 'fotobudka' );
        }

        // Allow 3rd party plugins to filter template file from their plugin.
        $template = apply_filters( 'wtm_get_template_part', $template, $slug, $name );


        if ( $template ) {
            load_template( $template, false );
        }
    }

}