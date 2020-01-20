<?php

namespace WTM;

 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);

if (!defined('ABSPATH')) exit;

define('WTM_VERSION', '1.0');
define('WTM_PATH', get_template_directory());
define('WTM_URL', get_template_directory_uri());



class Functions
{
    protected static $instance;

    private static $instance_autoload = [
        'WTM\Controllers\Admin\Admin',
        'WTM\Controllers\Categories',
        'WTM\Controllers\Mod_Rewrite',

        //'WTM\Controllers\Branch',
        'WTM\Controllers\Product',
        // 'WTM\Core\Realization',
        'WTM\Controllers\Seo',
        'WTM\Controllers\Page',
        'WTM\Controllers\Comment',
        'WTM\Controllers\Template',
        'WTM\Controllers\Image',
        'WTM\Controllers\Shortcode',
        'WTM\Controllers\Personalization',
        'WTM\Controllers\Navbar',
        'WTM\Controllers\Editor',
        'WTM\Controllers\Contact_Form7',
        'WTM\Controllers\Sortable'
    ];


    protected static $instances = [];

    protected function __construct()
    {
        $this->remove_actions();
        $this->remove_filters();
        $this->add_actions();
        $this->add_filters();
    }


    public static function init()
    {

        spl_autoload_register(['WTM\Functions', '__autoload']);

        if (self::check_access()) {
            self::load_core();
        }
    }


    public static function get_instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }




    private static function check_access()
    {

        $protection = new \WTM\Controllers\Protection;

        if (!is_object($protection) || !method_exists($protection, 'check_access')) {
            wp_redirect('https://fotobudka.pl', 301);
            exit;
        }

        if (!$protection->check_access('Vr@zV27<q}gcc[R^')) {
            wp_redirect('https://fotobudka.pl', 301);
            exit;
        }

        return true;
    }


    private static function load_core()
    {   

        if (!isset(self::$instance_autoload) || empty(self::$instance_autoload)) {
            return;
        }

        foreach (self::$instance_autoload as $instance) {
            // $instance = "WTM\\Core\\$instance";
            if(!isset(self::$instances[$instance]) || empty(self::$instances[$instance]))
                self::$instances[$instance] = new $instance();
        }

    }


    protected function add_actions()
    { }

    protected function remove_actions()
    { }

    protected function add_filters()
    {
       // add_filter('retrieve_password_message', 'remove_angle_brackets_around_url', 99, 1); 
    }

    protected function remove_filters()
    { }



    private static function __autoload($className)
    {

        $className = explode("\\", $className);
        $className = array_filter(array_map(function ($path) {
            $path = strtolower($path);
            $path = str_replace('_', '-', $path);


            if (strtolower(__NAMESPACE__) == $path) {
                return false;
            }
            return $path;
        }, $className));


        $className2 = $className;
        end($className2);
        $key = key($className2);
        $className2[$key] = 'class-' . $className2[$key];
        $className2 = join('/', $className2);
        $className2 = strtolower($className2);

        $className = join('/', $className);
        $className = strtolower($className);

        $paths = [
            WTM_PATH . "/core/%class_name%",
            WTM_PATH . "/%class_name%",
        ];

        foreach ([$className2, $className] as $class) {
            foreach ($paths as $path) {

                $path = str_replace('%class_name%', $class, $path);
       
                if (file_exists("$path.php")) {
                    require_once "$path.php";
                    break;
                }   
            }
        }

        // if(file_exists( WTM_PATH."/core/classes/$className.php")){
        //     require_once WTM_PATH."/core/classes/$className.php";
        // }
        // if(file_exists( WTM_PATH."/class-$className.php")){
        //     require_once WTM_PATH."/class-$className.php";
        // }
        // if(file_exists( WTM_PATH."/$className.php")){
        //     require_once WTM_PATH."/$className.php";
        // }

        // if(file_exists( WTM_PATH."/inc/$className2.php")){
        //     require_once WTM_PATH."/inc/$className2.php";
        // }
        // if(file_exists( WTM_PATH."/class-$className2.php")){
        //     require_once WTM_PATH."/class-$className2.php";
        // }
        // if(file_exists( WTM_PATH."/$className2.php")){
        //     require_once WTM_PATH."/$className2.php";
        // }

    }
}


Functions::init();