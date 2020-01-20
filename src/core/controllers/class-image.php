<?php

namespace WTM\Controllers;

if ( ! defined( 'ABSPATH' ) ) exit; 

use WTM\Functions;
use WTM\Model\Tools;


class Image extends Functions{

    protected function add_actions(){

        add_action('admin_head', [$this,'admin_fix_svg_thumb_display']);

        add_action( 'after_setup_theme', [$this,'add_image_sizes'] );

        add_action('delete_attachment', [$this,'remove_webp'], 10, 1);
    }

    protected function add_filters(){

        //support svg
        add_filter('upload_mimes', [$this,'add_support_svg']);
        add_filter('wp_check_filetype_and_ext', [$this,'add_support_svg_extension'], 10, 4 );


        // support webp
        add_filter('wp_handle_upload', [$this,'create_webp'], 10, 2 );
        add_filter( 'image_make_intermediate_size', [$this,'create_webp_thumb'] );

        add_filter('wp_get_attachment_image_attributes',[$this,'make_lazy_loading_images'], 99, 2);
        add_filter('wtm_change_attachment_image_html', [$this,'change_attachment_image_html'], 10, 3);

        add_filter('msp_get_the_resized_image_src', [$this,'create_webp_masterslider'], 10, 2 );

        add_filter("post_gallery", [$this, 'clear_gallery_html'], 10, 2);

    }

    public function add_support_svg($mimes) {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }


    public function add_support_svg_extension( $types, $file, $filename, $mimes ) {
        if ( false !== strpos( $filename, '.svg' ) ) {
            $types['ext'] = 'svg';
            $types['type'] = 'image/svg+xml';
        }

        return $types;
    }


    public function admin_fix_svg_thumb_display() {
        echo '
        <style>
          td.media-icon img[src$=".svg"], img[src$=".svg"].attachment-post-thumbnail { 
            width: 100% !important; 
            height: auto !important; 
          }</style>
        ';
    }


    public function add_image_sizes(){
        add_image_size( 'wtm-admin-thumbnail-mini', 50, 50, true );
        add_image_size( 'wtm-top-frontpage-thumbnail', 1770, 930, true );
        add_image_size( 'wtm-top-thumbnail', 1770, 530, true );
        add_image_size( 'wtm-box-row', 466, 291, true );
        add_image_size( 'wtm-box-column', 318, 248, true );
        add_image_size( 'wtm-desc', 1337, 551, true );
        add_image_size( 'wtm-product', 338, 458, true );
        add_image_size( 'wtm-gallery', 680, 350, true );
        add_image_size( 'icon-social', 30, 30, true );
        add_image_size( 'front-cover', 380, 380, true );
        add_image_size( 'front-large', 500, 680, true );
    }


    public function create_webp( $upload, $context ){
        if( !getimagesize($upload['file']) )
            return $upload;
    
        $imgstring = imagecreatefromstring(file_get_contents($upload['file']));
        imagepalettetotruecolor($imgstring);
        
        $file_info = pathinfo($upload['file']);
        $img = $file_info['filename'];
        $dir = $file_info['dirname'];
        try {
            imagewebp($imgstring, $dir.'/'.$file_info['filename'].'.webp', 90);
        } catch (Exception $e) {
            return $image;
        }
        
        return $upload;
    }



    public function create_webp_thumb( $image ) {
        if( !getimagesize($image) )
            return $image;
    
        $imgstring = imagecreatefromstring(file_get_contents($image));
        imagepalettetotruecolor($imgstring);
    
        $file_info = pathinfo($image);
    
        $img = $file_info['filename'];
        $dir = $file_info['dirname'];
    
        try {
            imagewebp($imgstring, $dir.'/'.$file_info['filename'].'.webp', 90);
        } catch (Exception $e) {
            return $image;
        }
    
        
        return $image;
    }


    public function remove_webp( $post_id ) 
    {
        $basedir = wp_upload_dir()['basedir'];
        $images = unserialize(get_post_meta($post_id)['_wp_attachment_metadata'][0]);
        $imgInfo = pathinfo( $basedir.'/'.get_post_meta($post_id)['_wp_attached_file'][0] );
        $title = $imgInfo['filename'];
        $file_title = $images['file'];

        $imgdir = substr($file_title, 0, strpos($file_title,$title));
        $ext = substr($file_title, strpos($file_title,$title)+strlen($title), strlen($file_title));

        unlink( $basedir.'/'.$imgdir.$title.'.webp' );
        foreach( $images['sizes'] as $img ) {
            $fileWebp = str_replace($ext, '.webp', $img['file']);
            unlink( $basedir.'/'.$imgdir.$fileWebp );
        }
        if( $backup = unserialize(get_post_meta($post_id)['_wp_attachment_backup_sizes'][0]) ) {
            foreach( $backup as $img ) {
                $fileWebp = str_replace($ext, '.webp', $img['file']);
                unlink( $basedir.'/'.$imgdir.$fileWebp );
            }
        }
        return $image;
    }




    public function make_lazy_loading_images($attr, $attachment ){
        if(is_admin())
        return $attr;
        
        if(!Tools::is_bot() || wp_doing_ajax()){
            if(!isset($attr['preload']) || $attr['preload'] !== false ){
                if(isset($attr['src'])){
                    $attr['data-src'] = $attr['src'];
                    // $attr['src'] = get_template_directory_uri() . '/images/loading.svg';
                    if(!is_admin()){
                        unset($attr['src']);
                    }
                    
                }
                if(isset($attr['srcset'])){
                    $attr['data-srcset'] = $attr['srcset'];
                    unset($attr['srcset']);
                }
                if(isset($attr['sizes'])){
                    $attr['data-sizes'] = $attr['sizes'];
                    unset($attr['sizes']);
                }
            }
        }

        $attr['title'] = get_the_title($attachment->ID); 
        
        return $attr;
    }






    public function change_attachment_image_html($html = '', $attachment = null, $size = null){

        if(is_admin())
        return $html;

        if(!Tools::is_bot() || wp_doing_ajax()){
            $spacer = '';
            $is_full = true;
            if($size){
                if(is_array($size)){
                    $padding = (int)$size[1] / (int)$size[0] * 100;
                }
                elseif(is_string($size)){
                    // if($size == 'full' || $size == 'post-thumbnail'){
                        $image = wp_get_attachment_image_src( $attachment->ID, $size, false );
                        $size = array(
                            'height' => $image[2],
                            'width' => $image[1]
                        );
                        $set_max_width = true;
                    // }
                    // else{
                    //     $size = wtm_get_image_size($size);
                    // }
    
                    $padding = (int)$size['height'] / (int)$size['width'] * 100;
                }
                $spacer = '<div class="lazy-spacer" style="padding-bottom: '.$padding.'%"></div>';
            }
    
            $html = sprintf('<div class="lazy-container"%s>%s%s</div>',
            $is_full ? ' style="max-width:'.$size['width'].'px"': '',
            $html,
            $spacer);
        }
        return $html;
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