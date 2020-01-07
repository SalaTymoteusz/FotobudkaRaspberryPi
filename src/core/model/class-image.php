<?php

namespace WTM\Model;

if ( ! defined( 'ABSPATH' ) ) exit; 


class Image{




    public static function get_attachment_image( $attachment_id, $size = 'thumbnail', $attr = '', $icon = false  ) {
        $html  = '';
        $image = wp_get_attachment_image_src( $attachment_id, $size, $icon );

        if ( $image ) {
            list($src, $width, $height) = $image;
            $hwstring                   = image_hwstring( $width, $height );
            $size_class                 = $size;
            if ( is_array( $size_class ) ) {
                $size_class = $size_class[0] > $size_class[1] ? 'landscape' : 'portrait';
            }
            $attachment   = get_post( $attachment_id );
            $default_attr = array(
                'src'   => $src,
                'class' => "img-$size_class",
                'alt'   => trim( strip_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) ),
            );
     
            $attr = wp_parse_args( $attr, $default_attr );

            // Generate 'srcset' and 'sizes' if not already present.
            if ( empty( $attr['srcset'] ) ) {
                $image_meta = wp_get_attachment_metadata( $attachment_id );
     
                if ( is_array( $image_meta ) ) {
                    $size_array = array( absint( $width ), absint( $height ) );
                    $srcset     = wp_calculate_image_srcset( $size_array, $src, $image_meta, $attachment_id );
                    $sizes      = wp_calculate_image_sizes( $size_array, $src, $image_meta, $attachment_id );
     
                    if ( $srcset && ( $sizes || ! empty( $attr['sizes'] ) ) ) {
                        $attr['srcset'] = $srcset;
     
                        if ( empty( $attr['sizes'] ) ) {
                            $attr['sizes'] = $sizes;
                        }
                    }
                }
            }
     
            /**
             * Filters the list of attachment image attributes.
             *
             * @since 2.8.0
             *
             * @param array        $attr       Attributes for the image markup.
             * @param WP_Post      $attachment Image attachment post.
             * @param string|array $size       Requested size. Image size or array of width and height values
             *                                 (in that order). Default 'thumbnail'.
             */
            $attr = apply_filters( 'wp_get_attachment_image_attributes', $attr, $attachment, $size );
            $attr = array_map( 'esc_attr', $attr );
            $html = rtrim( "<img $hwstring" );
            foreach ( $attr as $name => $value ) {
                $html .= " $name=" . '"' . $value . '"';
            }
            $html .= ' />';
        }

        if(!isset($attr['preload']) || $attr['preload'] == true ){
            $html = apply_filters( 'wtm_change_attachment_image_html', $html, $attachment, $size );
        }
        return $html;
    }





    public static function get_image_sizes() {
        global $_wp_additional_image_sizes;

        $sizes = array();

        foreach ( get_intermediate_image_sizes() as $_size ) {
            if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
                $sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
                $sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
                $sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
            } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
                $sizes[ $_size ] = array(
                    'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
                    'height' => $_wp_additional_image_sizes[ $_size ]['height'],
                    'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
                );
            }
        }

        return $sizes;
    }





    /**
     * get_image_size function
     * 
     * get size information for a specific image size.
     *
     * @uses   get_image_sizes()
     * @param  string $size The image size for which to retrieve data.
     * @return bool|array $size Size data about an image size or false if the size doesn't exist.
     */
    public static function get_image_size( $size ) {
        $sizes = self::get_image_sizes();

        if ( isset( $sizes[ $size ] ) ) {
            return $sizes[ $size ];
        }

        return false;
    }





    /**
     * wtm_get_image_width function
     * 
     * get the width of a specific image size.
     *
     * @param  string $size The image size for which to retrieve data.
     * @return bool|string $size Width of an image size or false if the size doesn't exist.
     */
    public static function get_image_width( $size ) {
        if ( ! $size = self::get_image_size( $size ) ) {
            return false;
        }

        if ( isset( $size['width'] ) ) {
            return $size['width'];
        }

        return false;
    }






    /**
     * wtm_get_image_height function
     * 
     * get the height of a specific image size.
     *
     * @param  string $size The image size for which to retrieve data.
     * @return bool|string $size Height of an image size or false if the size doesn't exist.
     */
    public static function get_image_height( $size ) {
        if ( ! $size = self::get_image_size( $size ) ) {
            return false;
        }

        if ( isset( $size['height'] ) ) {
            return $size['height'];
        }

        return false;
    }




    public function clear_gallery_html($output, $attr) {
        global $post;
    
        static $instance = 0;
        $instance++;
    
        if ( isset( $attr['orderby'] ) ) {
            $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
            if ( !$attr['orderby'] )
                unset( $attr['orderby'] );
        }
    
        extract(shortcode_atts(array(
            'order'      => 'ASC',
            'orderby'    => 'menu_order ID',
            'id'         => $post->ID,
            'itemtag'    => '',
            'icontag'    => '',
            'captiontag' => 'dd',
            'columns'    => 3,
            'size'       => 'thumbnail',
            'include'    => '',
            'exclude'    => ''
            ), $attr));
    
        $id = intval($id);
        if ( 'RAND' == $order )
            $orderby = 'none';
    
        if ( !empty($include) ) {
            $include = preg_replace( '/[^0-9,]+/', '', $include );
            $_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    
            $attachments = array();
            foreach ( $_attachments as $key => $val ) {
                $attachments[$val->ID] = $_attachments[$key];
            }
        } elseif ( !empty($exclude) ) {
            $exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
            $attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
        } else {
            $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
        }
    
        if ( empty($attachments) )
            return '';
    
        if ( is_feed() ) {
            $output = "";
            foreach ( $attachments as $att_id => $attachment )
                $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
            return $output;
        }
    
        $itemtag = tag_escape($itemtag);
        $captiontag = tag_escape($captiontag);
        $columns = intval($columns);
        $itemwidth = $columns > 0 ? floor(100/$columns) : 100;
        $float = is_rtl() ? 'right' : 'left';
    
        $selector = "gallery-{$instance}";
    
        $gallery_style = $gallery_div = '';
        if ( apply_filters( 'use_default_gallery_style', true ) )
    
            $size_class = sanitize_html_class( $size );
        $gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
        $output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );
    
        $i = 0;
        foreach ( $attachments as $id => $attachment ) {
            $link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);
    
            $output .= "$link";
    
            if ( $columns > 0 && ++$i % $columns == 0 )
                $output .= '<span class="clearfix"></span>';
        }
    
        $output .= "</div>";
        return $output;
    }
}