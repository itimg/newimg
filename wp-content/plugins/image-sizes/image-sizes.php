<?php
/**
 * Plugin Name: Stop Generating Unnecessary Thumbnails
 * Description: So, it creates multiple thumbnails while uploading an image? Here is the solution!
 * Plugin URI: https://wordpress.org/plugins/image-sizes/
 * Author: Codexpert
 * Author URI: https://codexpert.io/?utm_source=free-plugins&utm_medium=author-uri&utm_campaign=image-sizes
 * Version: 3.3.1.1
 * Text Domain: image-sizes
 * Domain Path: /languages
 *
 * Stop Generating Unnecessary Thumbnails is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Stop Generating Unnecessary Thumbnails is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

namespace codexpert\Image_Sizes;
use codexpert\plugin\Widget;
use codexpert\plugin\Survey;
use codexpert\plugin\Notice;
use codexpert\plugin\Deactivator;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main class for the plugin
 * @package Plugin
 * @author codexpert <hello@codexpert.io>
 */
final class Plugin {
	
	public static $_instance;

	public function __construct() {
		$this->include();
		$this->define();
		$this->hook();
	}

	/**
	 * Includes files
	 */
	public function include() {
		require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );
	}

	/**
	 * Define variables and constants
	 */
	public function define() {
		// constants
		define( 'CXIS', __FILE__ );
		define( 'CXIS_DIR', dirname( CXIS ) );
		define( 'CXIS_DEBUG', apply_filters( 'image-sizes_debug', true ) );

		// plugin data
		$this->plugin				= get_plugin_data( CXIS );
		$this->plugin['basename']	= plugin_basename( CXIS );
		$this->plugin['file']		= CXIS;
		$this->plugin['server']		= apply_filters( 'image-sizes_server', 'https://codexpert.io/dashboard' );
		$this->plugin['min_php']	= '5.6';
		$this->plugin['min_wp']		= '4.0';
		$this->plugin['doc_id']		= 1375;
	}

	/**
	 * Hooks
	 */
	public function hook() {

		if( is_admin() ) :

			/**
			 * Admin facing hooks
			 *
			 * To add an action, use $admin->action()
			 * To apply a filter, use $admin->filter()
			 */
			$admin = new Admin( $this->plugin );
			$admin->activate( 'install' );
			$admin->deactivate( 'uninstall' );
			$admin->action( 'plugins_loaded', 'i18n' );
			$admin->action( 'admin_enqueue_scripts', 'enqueue_scripts' );
			$admin->action( 'admin_head', 'set_init_sizes' );
			$admin->action( 'admin_head', 'init_sync' );
			$admin->filter( 'intermediate_image_sizes_advanced', 'image_sizes' );
			$admin->filter( "plugin_action_links_{$this->plugin['basename']}", 'action_links' );
			$admin->filter( 'plugin_row_meta', 'plugin_row_meta', 10, 2 );
			$admin->action( 'admin_footer_text', 'footer_text' );

			/**
			 * Settings related hooks
			 *
			 * To add an action, use $settings->action()
			 * To apply a filter, use $settings->filter()
			 */
			$settings = new Settings( $this->plugin );
			$settings->action( 'plugins_loaded', 'init_menu' );
			$settings->action( 'admin_notices', 'admin_notices' );

			// Product related classes
			$widget			= new Widget( $this->plugin );
			$survey			= new Survey( $this->plugin );
			$notice			= new Notice( $this->plugin );
			$deactivator	= new Deactivator( $this->plugin );

		else : // !is_admin() ?

		endif;

		/**
		 * AJAX facing hooks
		 *
		 * To add a hook for logged in users, use $ajax->priv()
		 * To add a hook for non-logged in users, use $ajax->nopriv()
		 */
		$ajax = new AJAX( $this->plugin );
		$ajax->priv( 'cxis-regen-thumbs', 'regen_thumbs' );
		$ajax->priv( 'cxis-dismiss', 'dismiss_notice' );
	}

	/**
	 * Cloning is forbidden.
	 */
	public function __clone() { }

	/**
	 * Unserializing instances of this class is forbidden.
	 */
	public function __wakeup() { }

	/**
	 * Instantiate the plugin
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}

Plugin::instance();