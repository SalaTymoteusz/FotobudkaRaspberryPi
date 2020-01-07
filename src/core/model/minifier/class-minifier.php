<?php

namespace WTM\Model\Minifier;

if ( ! defined( 'ABSPATH' ) ) exit; 

use WTM\Functions;
use WTM\Model\Tools;
use WTM\Model\Template;

class Minifier extends Functions{

    private $enable = false;
    private $external_js = false;
    private $external_css = false;

    private static $cache = [];

    private $cache_dir_path;
    private $cache_dir_url;

    public function __construct(array $args = []){

        if($args){
           foreach( $args as $param => $value){
               if(isset( $this->{$param} ))
                $this->{$param} = $value;
           } 
        }
        $this->cache_dir_path = get_template_directory().'/cache';
        $this->cache_dir_url = get_template_directory_uri().'/cache';

        self::$cache = get_option('cache_files') ?? [];

        parent::__construct();

    }

    public function is_enable(){
        return $this->check_cache_directory() && (bool)$this->enable;
    }

    protected function add_actions()
    {
        if(!$this->is_enable())
        return;

        if(is_admin())
        return;


        global $wp_version;


        if ( version_compare( $wp_version, '2.8.0', '>' ) ) {          
            // add_action('wp_print_scripts', [$this, 'print_scripts_action'], 0);
            add_action('wp_print_footer_scripts', [$this, 'print_scripts_action'], 0);
            
            add_action('wp_print_styles',         [$this,  'print_styles_action'], -10000);
            // add_action('wp_print_footer_scripts', [$this, 'print_styles_action'], 0);
        } 
        else {
            add_action('wp_print_scripts', [$this, 'print_scripts_action'], 200);
            add_action('wp_print_styles',  [$this, 'print_styles_action'], 200);
        }

        add_action('wp_footer', [$this, 'footer'], 20000000);
        
    }


    public function footer(){
        $this->print_scripts();
        $this->print_styles();
    }


    public function print_styles_action(){

        if (is_admin()) return;
		
        global $wp_styles, $auto_compress_styles;
		if (! is_object($wp_styles)) return;
			
        if (! is_array($auto_compress_styles))
            $auto_compress_styles = [];
		
        $queue = $wp_styles->queue;
        $wp_styles->all_deps($queue);
        
		foreach( $wp_styles->to_do as $key => $handle ) {

			if ( !in_array($handle, $wp_styles->done, true) && isset($wp_styles->registered[$handle]) ) {

				if ( ! $wp_styles->registered[$handle]->src ) { // Defines a group.
					$wp_styles->done[] = $handle;
					continue;
				}

                $src      = Tools::normalize_url($wp_styles->registered[$handle]->src);
                $media    = ($wp_styles->registered[$handle]->args ? $wp_styles->registered[$handle]->args : 'all');
                $external = Tools::is_external_url($src);

                if ( $this->external_css || ! $external) {
                    unset($wp_styles->to_do[$key]);
                    
                    $conditional = 'no-conditional';
                    if (isset($wp_styles->registered[$handle]->extra) 
                        && isset($wp_styles->registered[$handle]->extra['conditional'])) {
                        $conditional = $wp_styles->registered[$handle]->extra['conditional'];
                    }
                        
                    $auto_compress_styles[$media][$conditional][$handle] = [
                        'src'      => $src, 
                        'media'    => $media, 
                        'external' => $external,
                        'ver'      => $wp_styles->registered[$handle]->ver,
                        'args'     => $wp_styles->registered[$handle]->args,
                        'extra'    => $wp_styles->registered[$handle]->extra,
                    ];

                    ob_start();
                    if ( $wp_styles->do_item( $handle ) ) {
                        $wp_styles->done[] = $handle;
                    }
                    ob_end_clean();
                }
                else {
                    if ( $wp_styles->do_item( $handle ) ) {
                        $wp_styles->done[] = $handle;
                    }
                }

				unset( $wp_styles->to_do[$key] );
			}
		}
        
		$this->print_styles();
		
    }

    public function print_scripts_action(){
            /* if ( did_action('wp_print_footer_scripts') ) */
            
        if (is_admin()) return;
    
        global $wp_scripts, $auto_compress_scripts;
        if (! is_a($wp_scripts, 'WP_Scripts')) return;
            
        if (! is_array($auto_compress_scripts))
            $auto_compress_scripts = [];
    
        $queue = $wp_scripts->queue;
        $wp_scripts->all_deps($queue);
            
    
        foreach( $wp_scripts->to_do as $key => $handle ) {
            if ( !in_array($handle, $wp_scripts->done, true) && isset($wp_scripts->registered[$handle]) ) {
    
                if ( ! $wp_scripts->registered[$handle]->src ) { // Defines a group.
                    $wp_scripts->done[] = $handle;
                    continue;
                }
    
                $src = Tools::normalize_url($wp_scripts->registered[$handle]->src);

                $external = Tools::is_external_url($src);


                if ($this->external_js || !$external) {
                        
                    $_conditional = $wp_scripts->registered[$handle]->extra['conditional'] ?? '';
                        
                    // Print scripts those added before
                    if ( $_conditional ) {
                        $this->print_scripts();
                    } 
                    
                    $auto_compress_scripts[$handle] = [
                        'src'      => $src, 
                        'external' => $external,
                        'ver'      => $wp_scripts->registered[$handle]->ver,
                        'args'     => $wp_scripts->registered[$handle]->args,
                        'extra'    => $wp_scripts->registered[$handle]->extra,
                        'localize' => $wp_scripts->registered[$handle]->extra['data'] ?? ''
                    
                    ];
                                                        
                    // Print script with "conditional"
                    if ( $_conditional ) {
                        $this->print_scripts( $_conditional );
                    } 
                                                        

                        
                    ob_start();
                    if ( $wp_scripts->do_item( $handle ) ) {
                        $wp_scripts->done[] = $handle;
                    }
                    ob_end_clean();
                }
                else {
                    // Print scripts those added before
                    $this->print_scripts();
                    
                    // Standard way
                    if ( $wp_scripts->do_item( $handle ) ) {
                        $wp_scripts->done[] = $handle;
                    }
                }

                unset( $wp_scripts->to_do[$key] );
            }
        }
    
        // printing scripts hear or move to the bottom
        // if ( self::$options['combine-js'] == 'combine' || self::$js_printed) {
        //     $this->print_scripts();
        // }        
    }






    

    

    


    private function check_cache_directory() {
              
    
        if (is_writable(Tools::get_full_path($this->cache_dir_path))) {
            return true;
        }
        else {
            if (@mkdir(Tools::get_full_path($this->cache_dir_path), 0777, true) 
                && @chmod(Tools::get_full_path($this->cache_dir_path), 0777)) {
                return true;
            }
        }
        return false;
    }


    


    private function print_styles(){
        global $auto_compress_styles;     

        // TODO: Check ordering
        foreach ($auto_compress_styles as $media => $conditionals) {
            foreach ($conditionals as $conditional => $scripts) {
                if ($conditional == 'no-conditional') {
                    $conditional = false;
                }
                $this->print_styles_by_media($scripts, $media, $conditional);
            }
        }
        //self::$css_printed = true;
        $auto_compress_styles = [];
    }

    
    private function print_styles_by_media($scripts, $media, $conditional) {
        global $auto_compress_styles;
        if (! is_array($scripts) || ! count($scripts))
            return false;

        $home = get_option('siteurl').'/';
        if (! is_array(self::$cache))
            self::$cache = [];

        $handles = array_keys($scripts);
        $handles = implode(', ', $handles);
        
        // Calc "modified tag"
        $fileId = '';
        foreach ($scripts as $handle => $script) {
            if (! $script['external']) {
                $path = Tools::get_path_by_url($script['src'], $home);
                $fileId .= @filemtime($path);               
            }
            else {
                $fileId .= '-'.$script['ver'];
            }
        }
        if (empty($fileId)) 
            $fileId = 'nover';
            
            
        
        
        $cache_name = md5(md5($handles).$fileId);
        $cache_file_path = Tools::get_full_path($this->cache_dir_path) . "/$cache_name.css";
        $cache_file_url = $this->cache_dir_url . "/$cache_name.css";      

        // Find a cache
        if ($this->get_cache($cache_name, $cache_file_path, 'css')) {
            
            // Include script 
            $this->print_css_link_tag($cache_file_url, $media, $conditional, true);

            $scripts = [];
            return true;
        }

        // Build cache
        $scripts_text = '';
        foreach ($scripts as $handle => $script) {
            $src = html_entity_decode($script['src']);
            $scripts_text .= "/* $handle: ($src) */\n";

                // Get script contents
            $_remote_get = wp_remote_get(Template::set_assets_version($src, $handle), 
                array(
                    'timeout' => 10
                )
            );

            if (! is_wp_error($_remote_get) && $_remote_get['response']['code'] == 200) {
                $content = $_remote_get['body'];
                
                $content = Minifier_CSS::minify($content);

                $scripts_text .= $content . "\n";                    
            }
            else {
                if (! is_wp_error($_remote_get)) {
                    $error_message = "/* Error loading script content: $src; HTTP Code: {$_remote_get['response']['code']} ({$_remote_get['response']['message']}) */";
                }
                else
                    $error_message = "/* Error loading script content: $src */";
                    
                $scripts_text .= "$error_message\n";
                $scripts_text .= "@import url('" . $src . "'); \n\n";
            }
        }
        $scripts_text = "/*\nCache: ".$handles."\n*/\n" . $scripts_text;
       
        // Save cache
        $this->save_cache($cache_file_path, $scripts_text, $cache_name, $fileId, 'css');
            
        // Include script 
        $this->print_css_link_tag($cache_file_url, $media, $conditional, false);        
        
        return true;
    }


    private function print_scripts( $conditional = false ) {
        global $auto_compress_scripts;
        if (! is_array($auto_compress_scripts) || ! count($auto_compress_scripts))
            return;

        $home = get_option('siteurl').'/';
        if (! is_array(self::$cache))
            self::$cache = [];

        $handles = array_keys($auto_compress_scripts);
        $handles = implode(', ', $handles);
        $localize_js = '';
            
            // Calc "modified tag"
        $fileId = '';
        foreach ($auto_compress_scripts as $handle => $script) {
            if (! $script['external']) {
                $path = Tools::get_path_by_url($script['src'], $home);
                $fileId .= @filemtime($path);
            }
            else {                  
                $fileId .= $script['ver'].$script['src'];
            }
            
            if (! empty($script['localize'])) {
                $localize_js .= "/* $handle */\n" . $script['localize'] . "\n";
            }
        }			
        
        $cache_name = md5(md5($handles).$fileId);
        $cache_file_path = Tools::get_full_path($this->cache_dir_path) . "/$cache_name.js";
        $cache_file_url = $this->cache_dir_url ."/$cache_name.js";
        
        // Find a cache
        if ($this->get_cache($cache_name, $cache_file_path, 'js')) {
            
            // Include script 
            $this->print_js_script_tag($cache_file_url, $conditional, true, $localize_js);
            
            $auto_compress_scripts = array();
            return;
        }

        // Build cache
        $scripts_text = '';
        foreach ($auto_compress_scripts as $handle => $script) {
            $src = html_entity_decode($script['src']);
            $scripts_text .= "/* $handle: ($src) */\n";
            
            // Get script contents
            $_remote_get = wp_remote_get(Template::set_assets_version($src, $handle), 
                array(
                    'timeout' => 10
                )
            );
            
            if (! is_wp_error($_remote_get) && $_remote_get['response']['code'] == 200) {
                $contents = $_remote_get['body'];
                        
                if ( (strpos($src, '.pack.') === false) 
                    && (strpos($src, '.min.') === false)) {

                    $contents = Minifier_JS::minify( $contents ) . ";\n";
                }
                else {
                    $contents = $contents.";\n\n";
                }
                $scripts_text .= $contents;
            }
            else {
                $scripts_text .= "/*\nError loading script content: $src\n";
                if (! is_wp_error($_remote_get)) 
                    $scripts_text .= "HTTP Code: {$_remote_get['response']['code']} ({$_remote_get['response']['message']})\n*/\n\n"; ///************************
            }
        }
        $scripts_text = "/*\nCache: ".$handles."\n*/\n" . $scripts_text;
        
        // Save cache
        $this->save_cache($cache_file_path, $scripts_text, $cache_name, $fileId, 'js');

        // Include script 
        $this->print_js_script_tag($cache_file_url, $conditional, false, $localize_js);

        $auto_compress_scripts = array();

    }


    private function print_js_script_tag($url, $conditional, $is_cache, $localize = '', $error_message = '') {
        
        if ($localize) {
            echo "<script type='text/javascript'>\n/* <![CDATA[ */\n$localize\n/* ]]> */\n</script>\n";
        }
            
        if ($conditional) {
            echo "<!--[if " . $conditional . "]>\n";        
        }
        
        echo '<script async defer type="text/javascript" src="' . $url . '">' . ($is_cache ? ' <!-- Cache! -->' : '') . $error_message . '</script>' . "\n";

        if ($conditional) {
            echo "<![endif]-->" . "\n";
        }
    }








    private function print_css_link_tag($url, $media, $conditional, $is_cache) {
        if ($conditional)
            echo "<!--[if " . $conditional . "]>\n";
              
        echo '<link rel="stylesheet" href="' . $url . '" type="text/css" media="' . $media . '" />' . (($is_cache && ! $conditional) ? ' <!-- Cache! -->' : '') . "\n";
        
        if ($conditional) 
            echo "<![endif]-->" . (($is_cache && $conditional) ? ' <!-- Cache! -->' : '') . "\n";
    }


    private function save_cache($cache_file_path, $cache, $cache_name, $fileId, $type) {
        $this->save_script($cache_file_path, $cache);
        self::$cache["{$cache_name}_{$type}"] = $fileId;
        update_option('cache_files',self::$cache,true);      
    }   
    
    private function get_cache($cache_name, $cache_file_path, $type) {
        return (!empty(self::$cache["{$cache_name}_{$type}"]) && is_readable($cache_file_path));
    }




    private function save_script($filename, $content) {
        if (is_writable(Tools::get_full_path($this->cache_dir_path))) {
            $fhandle = @fopen($filename, 'w+');
            if ($fhandle) fwrite($fhandle, $content, strlen($content));
        }
        return false;
    }
}