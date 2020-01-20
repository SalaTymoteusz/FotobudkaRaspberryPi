<?php

namespace WTM\Controllers;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use WTM\Model\Tools;

/**
* Main plugin class.
*
* @since 1.0.0
*
* @package Disable_All_WP_Updates
* @author	Thomas Griffin
*/
class Protection {
	
	/**
	* Holds the current WP version.
	*
	* @since 1.0.0
	*
	* @var bool|int
	*/
	public $version = false;


	public $user = false;
	
	/**
	* Holds all the registered themes.
	*
	* @since 1.0.0
	*
	* @var array
	*/
	public $themes = array();
	
	/**
	* Holds all the registered plugins.
	*
	* @since 1.0.0
	*
	* @var array
	*/
	public $plugins = array();
	
	
	/**
	* Allowed servers ips
	*
	* @since 1.0.0
	*
	* @var array
	*/
	private $allowed_server_ip = array('195.191.162.147','51.75.125.79');
	
	/**
	* Protect fotobudka users
	*
	* @since 1.0.0
	*
	* @var array
	*/
	private $protect_users = array(
		'w.jagodzinski.24@gmail.com','tomi.jozefczak@gmail.com','tymek.pawel@gmail.com','hslomion@gmail.com'
	);
	
	
	
	/**
	* Initializes the plugin.
	*
	* @since 1.0.0
	*
	* @global int $wp_version The current WP version.
	*/
	public function __construct() {
		
		// Set WP version.
		global $wp_version;
		$this->version = $wp_version;

		$this->user = wp_get_current_user();
		
	
	

		if( is_user_logged_in() && is_admin() ){

			$this->init_hooks();
			
			if( !in_array($this->user->data->user_email, $this->protect_users) && 
			strpos($_SERVER['PHP_SELF'], 'user-edit.php') !== FALSE &&
			isset($_GET['user_id']))
			{
				$edit_user = get_user_by('ID',(int)$_GET['user_id']);
				if( in_array($edit_user->data->user_email, $this->protect_users) ){
					define('IS_PROFILE_PAGE',true);
				}
			}
			
			if( (!in_array(Tools::get_server_ip(), $this->allowed_server_ip) && 
			!in_array($this->user->data->user_email, $this->protect_users) ) ||
			!in_array($this->user->data->user_email, $this->protect_users))
			{
				
				if ( ! defined( 'AUTOMATIC_UPDATER_DISABLED' ) ) {
					define( 'AUTOMATIC_UPDATER_DISABLED', true );
				}
				
				// if ( ! defined( 'DISALLOW_FILE_MODS' ) ) {
				// 	define( 'DISALLOW_FILE_MODS', true );
				// }
					
				if ( ! defined( 'WP_AUTO_UPDATE_CORE' ) ) {
					define( 'WP_AUTO_UPDATE_CORE', false );
				}
				
		
				$this->init_not_protect_users_hooks();
								
			}
			
		}
			// Possibly define constants to prevent automatic updates.
	// 	if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
	// 		define( 'DISALLOW_FILE_EDIT', true );
	// 	}
	// 	if ( is_admin() && ! current_user_can( 'administrator' ) && ! wp_doing_ajax() ) {
	// 	wp_redirect( home_url() );
	// 	exit;
	// }
			
	}
	
		
	public function check_access($token = null){
		$check = true;
		if(!$token){
			$check = false;
		}
		
		$allowed_tokens = array(
			'$2y$15$eQoNatB.QnJC1XQgb88zuOoP65hBwR4sE.dXmZ/6cTqIUygL6ynuS',
			'$argon2i$v=19$m=1024,t=2,p=2$R1dCWVBMMFd0TzVxRHdScw$lDQJTeWz0bxVnVpw4ZT91D1SXI4+KCHcUx2ZYA7jMMk'
		);
		
		$hash_type = defined('PASSWORD_ARGON2I') ? PASSWORD_ARGON2I : PASSWORD_BCRYPT;
		$hash = defined('PASSWORD_ARGON2I') ? $allowed_tokens[1] : $allowed_tokens[0];
		
		if(!password_verify($token, $hash)){
			$check = false;
		}
		
		if(!$check){
			wp_redirect( 'https://fotobudkaraspberry.pl', 302 );
			exit;
		}
		else{
			return true;
		}
	}

	private function init_hooks(Type $var = null)
	{
		add_action( 'generate_rewrite_rules', [$this,'add_htaccesses']);
	}

	private function init_not_protect_users_hooks(){

		// Remove hooks and cron checks.
		add_action( 'init', [$this, 'init'] );
		add_action( 'admin_init', [$this, 'override_not_protect_user_role'],1 );
		add_action( 'admin_init', [$this, 'admin_init'] );
		
		
		// Disable plugins from hooking into plugins_api.
		remove_all_filters( 'plugins_api' );
		
		// Further disable theme update checks.
		add_filter( 'pre_site_transient_update_themes', array( $this, 'pre_update_themes' ) );
		add_filter( 'site_transient_update_themes', array( $this, 'update_themes' ) );
		add_filter( 'transient_update_themes', array( $this, 'update_themes' ) );
		
		// Further disable plugin update checks.
		add_filter( 'pre_site_transient_update_plugins', array( $this, 'pre_update_plugins' ) );
		add_filter( 'site_transient_update_plugins', array( $this, 'update_plugins' ) );
		add_filter( 'transient_update_plugins', array( $this, 'update_plugins' ) );
		
		// Further disable core update checks.
		add_filter( 'pre_site_transient_update_core', array( $this, 'pre_update_core' ) );
		add_filter( 'site_transient_update_core', array( $this, 'update_core' ) );
		
		// Disable even other external updates related to core.
		add_filter( 'auto_update_translation', '__return_false' );
		add_filter( 'automatic_updater_disabled', '__return_true' );
		add_filter( 'allow_minor_auto_core_updates', '__return_false' );
		add_filter( 'allow_major_auto_core_updates', '__return_false' );
		add_filter( 'allow_dev_auto_core_updates', '__return_false' );
		add_filter( 'auto_update_core', '__return_false' );
		add_filter( 'wp_auto_update_core', '__return_false' );
		add_filter( 'auto_update_plugin', '__return_false' );
		add_filter( 'auto_update_theme', '__return_false' );
		add_filter( 'auto_core_update_send_email', '__return_false' );
		add_filter( 'automatic_updates_send_debug_email ', '__return_false' );
		add_filter( 'send_core_update_notification_email', '__return_false' );
		add_filter( 'automatic_updates_is_vcs_checkout', '__return_true' );
		add_filter( 'acf/settings/show_admin', '__return_false');

		// Remove bulk action for updating themes and plugins.
		add_filter( 'bulk_actions-plugins', array( $this, 'remove_bulk_actions' ) );
		add_filter( 'bulk_actions-themes', array( $this, 'remove_bulk_actions' ) );
		add_filter( 'bulk_actions-plugins-network', array( $this, 'remove_bulk_actions' ) );
		add_filter( 'bulk_actions-themes-network', array( $this, 'remove_bulk_actions' ) );


		// Filter outbound requests to known update hosts.
		add_filter( 'pre_http_request', array( $this, 'filter_update_requests' ), 10, 3 );
	}



	public function override_not_protect_user_role(){
		// add a capability to a role editor
		$editor = get_role( 'editor' );
		if(!empty($editor)){
			$editor->add_cap( 'edit_theme_options' ); 
			$editor->add_cap( 'manage_options' ); 
		}        

		$shop_manager = get_role( 'shop_manager' );
		if(!empty($shop_manager)){
			$shop_manager->add_cap( 'edit_theme_options' ); 
			$shop_manager->add_cap( 'manage_options' ); 
		}


		// edit permision custom administration
		if( current_user_can('administrator')) {
			if( !in_array($this->user->data->user_email, $this->protect_users)){
				if(!empty($shop_manager)){
					$this->user->set_role('shop_manager');
				}
				else{
					$this->user->set_role('editor');
				}
			}
		}



	}
	
	/**
	* Remove any global update checks.
	*
	* @since 1.0.0
	*/
	public function init() {
		remove_action( 'init', 'wp_version_check' );
		add_filter( 'pre_option_update_core', '__return_null' );
		remove_all_filters( 'plugins_api' );
	}
	
	/**
	* Remove admin update checks and block cron checks.
	*
	* @since 1.0.0
	*/
	public function admin_init() {
		// Remove updates page.
		remove_submenu_page( 'index.php', 'update-core.php' );
		
		// Disable plugin API checks.
		remove_all_filters( 'plugins_api' );
		
		// Disable theme checks.
		remove_action( 'load-update-core.php', 'wp_update_themes' );
		remove_action( 'load-themes.php', 'wp_update_themes' );
		remove_action( 'load-update.php', 'wp_update_themes' );
		remove_action( 'wp_update_themes', 'wp_update_themes' );
		remove_action( 'admin_init', '_maybe_update_themes' );
		wp_clear_scheduled_hook( 'wp_update_themes' );
		
		// Disable plugin checks.
		remove_action( 'load-update-core.php', 'wp_update_plugins' );
		remove_action( 'load-plugins.php', 'wp_update_plugins' );
		remove_action( 'load-update.php', 'wp_update_plugins' );
		remove_action( 'admin_init', '_maybe_update_plugins' );
		remove_action( 'wp_update_plugins', 'wp_update_plugins' );
		wp_clear_scheduled_hook( 'wp_update_plugins' );
		
		// Disable any other update/cron checks.
		remove_action( 'wp_version_check', 'wp_version_check' );
		remove_action( 'admin_init', '_maybe_update_core' );
		remove_action( 'wp_maybe_auto_update', 'wp_maybe_auto_update' );
		remove_action( 'admin_init', 'wp_maybe_auto_update' );
		remove_action( 'admin_init', 'wp_auto_update_core' );
		wp_clear_scheduled_hook( 'wp_version_check' );
		wp_clear_scheduled_hook( 'wp_maybe_auto_update' );
		
		// Hide nag messages.
		remove_action( 'admin_notices', 'update_nag', 3 );
		remove_action( 'network_admin_notices', 'update_nag', 3 );
		remove_action( 'admin_notices', 'maintenance_nag' );
		remove_action( 'network_admin_notices', 'maintenance_nag' );

	}
	
	/**
	* Remove theme update data from the update transient.
	*
	* @since 1.0.0
	*/
	public function pre_update_themes() {
		// Get all registered themes.
		if ( false === ($this->themes = get_transient( 'dawpu_themes' )) ) {
			foreach ( wp_get_themes() as $theme ) {
				$this->themes[ $theme->get_stylesheet() ] = $theme->get( 'Version' );
			}
			
			set_transient( 'dawpu_themes', $this->themes, DAY_IN_SECONDS );
		}
		
		// Return an empty object to prevent extra checks.
		return (object) array(
			'last_checked'	  => time(),
			'updates'		  => array(),
			'version_checked' => $this->version,
			'checked'		  => $this->themes
		);
	}
	
	/**
	* Remove theme update data from the update transient.
	*
	* @since 1.0.0
	*/
	public function update_themes() {
		return array();
	}
	
	/**
	* Remove plugin update data from the update transient.
	*
	* @since 1.0.0
	*/
	public function pre_update_plugins() {
		// Get all registered plugins.
		if ( false === ($this->plugins = get_transient( 'dawpu_plugins' )) ) {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			
			foreach ( get_plugins() as $file => $plugin ) {
				$this->plugins[ $file ] = $plugin['Version'];
			}
			
			set_transient( 'dawpu_plugins', $this->plugins, DAY_IN_SECONDS );
		}
		
		// Return an empty object to prevent extra checks.
		return (object) array(
			'last_checked'	  => time(),
			'updates'		  => array(),
			'version_checked' => $this->version,
			'checked'		  => $this->plugins
		);
	}
	
	/**
	* Remove plugin update data from the update transient.
	*
	* @since 1.0.0
	*/
	public function update_plugins() {
		return array();
	}
	
	/**
	* Remove core update data from the update transient.
	*
	* @since 1.0.0
	*/
	public function pre_update_core() {
		// Return an empty object to prevent extra checks.
		return (object) array(
			'last_checked'	  => time(),
			'updates'		  => array(),
			'version_checked' => $this->version,
		);
	}
	
	/**
	* Remove core update data from the update transient.
	*
	* @since 1.0.0
	*/
	public function update_core() {
		return array();
	}
	
	/**
	* Removes update bulk actions.
	*
	* @since 1.0.0
	*
	* @param array $actions Bulk actions.
	*/
	public function remove_bulk_actions( $actions ) {
		if ( isset( $actions['update-selected'] ) ) {
			unset( $actions['update-selected'] );
		}
		
		if ( isset( $actions['update'] ) ) {
			unset( $actions['update'] );
		}
		
		if ( isset( $actions['upgrade'] ) ) {
			unset( $actions['upgrade'] );
		}
		
		return $actions;
	}
	
	/**
	* Blocks update requests made to known update hosts.
	*
	* @since 1.0.0
	*
	* @param bool $bool  The return value if we should filter the request.
	* @param array $args An array of args passed to the remote request.
	* @param string $url The URL requested.
	* @return bool
	*/
	public function filter_update_requests( $bool, $args, $url ) {
		if ( empty( $url ) ) {
			return $bool;
		}
		
		$pieces = wp_parse_url( $url );
		if ( ! $pieces ) {
			return $bool;
		}
		
		// Add a filterable list of hosts/paths to be checked for possible blocking.
		$datasets = array(
			array(
				'host' => 'api.wordpress.org',
				'path' => 'update-check'
			),
			array(
				'host' => 'api.wordpress.org',
				'path' => 'version-check'
			),
			array(
				'host' => 'enviragallery.com'
			),
			array(
				'host' => 'soliloquywp.com'
			),
			array(
				'host' => 'wpforms.com'
			),
			array(
				'host' => 'easydigitaldownloads.com'
			),
			array(
				'host' => 'gravityhelp.com'
			),
			array(
				'host' => 'gravityplugins.com'
				)
			);
			$datasets = apply_filters( 'dawpu_filter_update_requests', $datasets );
			if ( ! $datasets ) {
				return $bool;
			}
			
			// Loop through the datasets to determine if we can return true
			// and prevent the request from happening.
		foreach ( $datasets as $array => $data ) {
			// Check for both host and path combined first.
			if ( ! empty( $data['host'] ) && ! empty( $data['path'] ) ) {
				if ( ! empty( $pieces['host'] ) && ! empty( $pieces['path'] ) ) {
					if ( false !== stripos( $pieces['host'], $data['host'] ) && false !== stripos( $pieces['path'], $data['path'] ) ) {
						return true;
					}
				}
			} else if ( ! empty( $data['host'] ) && ! empty( $pieces['host'] ) ) {
				if ( false !== stripos( $pieces['host'], $data['host'] ) ) {
					return true;
				}
			} else if ( ! empty( $data['path'] ) && ! empty( $pieces['path'] ) ) {
				if ( false !== stripos( $pieces['path'], $data['path'] ) ) {
					return true;
				}
			}
		}
		
		// Finally, return the default value.
		return $bool;
	}
		
	
	public function add_htaccesses($rewrite){

        $admin_path = str_replace( get_bloginfo( 'url' ) . '/', ABSPATH, get_admin_url() );
        $content_path = str_replace( get_bloginfo( 'url' ) . '/', ABSPATH, content_url().'/' );
		$includes_path = ABSPATH . WPINC . '/';
		
		

	}
}
		
