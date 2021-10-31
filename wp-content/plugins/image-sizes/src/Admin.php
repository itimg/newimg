<?php
/**
 * All admin facing functions
 */
namespace codexpert\Image_Sizes;
use codexpert\plugin\Base;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @package Plugin
 * @subpackage Admin
 * @author codexpert <hello@codexpert.io>
 */
class Admin extends Base {

	public $plugin;

	/**
	 * Constructor function
	 */
	public function __construct( $plugin ) {
		$this->plugin	= $plugin;
		$this->slug		= $this->plugin['TextDomain'];
		$this->name		= $this->plugin['Name'];
		$this->server	= $this->plugin['server'];
		$this->version	= $this->plugin['Version'];
	}

	/**
	 * Internationalization
	 */
	public function i18n() {
		load_plugin_textdomain( 'image-sizes', false, CXIS_DIR . '/languages/' );
	}

	/**
	 * Installer. Runs once when the plugin in activated.
	 *
	 * @since 1.0
	 */
	public function install() {
		/**
		 * Schedule an event to sync help docs
		 */
		if ( !wp_next_scheduled ( 'image-sizes_daily' )) {
		    wp_schedule_event( time(), 'daily', 'image-sizes_daily' );
		}

		if( get_option( 'image-sizes_survey_agreed' ) != 1 ) {
			delete_option( 'image-sizes_survey' );
		}

		if( !get_option( 'image-sizes_version' ) ){
			update_option( 'image-sizes_version', $this->version );
		}
		
		if( !get_option( 'image-sizes_install_time' ) ){
			update_option( 'image-sizes_install_time', time() );
		}
	}

	/**
	 * Uninstaller. Runs once when the plugin in deactivated.
	 *
	 * @since 1.0
	 */
	public function uninstall() {
		/**
		 * Remove scheduled hooks
		 */
		wp_clear_scheduled_hook( 'image-sizes_daily' );
	}
	
	/**
	 * Enqueue JavaScripts and stylesheets
	 */
	public function enqueue_scripts() {
		$min = defined( 'CXIS_DEBUG' ) && CXIS_DEBUG ? '' : '.min';
		
		wp_enqueue_script( $this->slug, plugins_url( "/assets/js/admin{$min}.js", CXIS ), [ 'jquery' ], $this->version, true );
		wp_enqueue_style( $this->slug, plugins_url( "/assets/css/admin{$min}.css", CXIS ), '', $this->version, 'all' );
		
		$localized = array(
			'ajaxurl'	=> admin_url( 'admin-ajax.php' ),
			'nonce'		=> wp_create_nonce( $this->slug ),
			'regen'		=> __( 'Regenerate', 'image-sizes' ),
			'regening'	=> __( 'Regenerating..', 'image-sizes' ),
		);
		wp_localize_script( $this->slug, 'CXIS', apply_filters( "{$this->slug}-localized", $localized ) );
	}

	public function set_init_sizes() {
		update_option( '_image-sizes', Helper::default_image_sizes() );
	}

	/**
	 * sync blog posts for the first time
	 */
	public function init_sync() {

		if( get_option( 'codexpert-blog-json' ) != '' ) return;

		/**
		 * Sync blog posts from https://codexpert.io
		 *
		 * @since 1.0
		 */
	    $_posts = 'https://codexpert.io/wp-json/wp/v2/posts/';
	    if( !is_wp_error( $_posts_data = wp_remote_get( $_posts ) ) ) {
	        update_option( 'codexpert-blog-json', json_decode( $_posts_data['body'], true ) );
	    }
	}

	/**
     * unset image size(s)
     *
     * @since 1.0
     */
    public function image_sizes( $sizes ){
        $disables = Helper::get_option( 'prevent_image_sizes', 'disables', [] );

        if( count( $disables ) ) :
        foreach( $disables as $disable ){
            unset( $sizes[ $disable ] );
        }
        endif;
        
        return $sizes;
    }

	public function action_links( $links ) {
		$this->admin_url = admin_url( 'upload.php' );

		$new_links = [
			'settings'	=> sprintf( '<a href="%1$s">' . __( 'Settings', 'image-sizes' ) . '</a>', add_query_arg( 'page', $this->slug, $this->admin_url ) )
		];
		
		return array_merge( $new_links, $links );
	}

	public function plugin_row_meta( $plugin_meta, $plugin_file ) {
		
		if ( $this->plugin['basename'] === $plugin_file ) {
			$plugin_meta['help'] = '<a href="https://help.codexpert.io/?utm_source=free-plugins&utm_medium=help-link&utm_campaign=image-sizes" target="_blank" class="cx-help">' . __( 'Help', 'image-sizes' ) . '</a>';
		}

		return $plugin_meta;
	}

	public function footer_text( $text ) {
		if( get_current_screen()->parent_base != $this->slug ) return $text;

		return sprintf( __( 'If you like <strong>%1$s</strong>, please <a href="%2$s" target="_blank">leave us a %3$s rating</a> on WordPress.org! It\'d motivate and inspire us to make the plugin even better!', 'image-sizes' ), $this->name, "https://wordpress.org/support/plugin/{$this->slug}/reviews/?filter=5#new-post", '⭐⭐⭐⭐⭐' );
	}
}