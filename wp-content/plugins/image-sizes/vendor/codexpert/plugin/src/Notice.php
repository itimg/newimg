<?php
namespace codexpert\plugin;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * @package Plugin
 * @subpackage Notice
 * @author Codexpert <hi@codexpert.io>
 */
class Notice extends Base {
	
	public $slug;
	public $name;

	public function __construct( $plugin ) {

		$this->plugin 	= $plugin;

		$this->server 	= $this->plugin['server'];
		$this->slug 	= $this->plugin['TextDomain'];
		$this->name 	= $this->plugin['Name'];
		
		self::hooks();
	}

	public function hooks(){
		$this->action( 'admin_enqueue_scripts', 'enqueue_scripts', 99 );
		$this->action( 'admin_notices', 'admin_notices' );
		$this->priv( 'cx-dismiss', 'dismiss_notices' );
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'codexpert-product-notice', plugins_url( 'assets/css/notice.css', __FILE__ ), [], $this->plugin['Version'] );
		wp_enqueue_script( 'codexpert-product-notice', plugins_url( 'assets/js/notice.js', __FILE__ ), [ 'jquery' ], $this->plugin['Version'], true );
	}

	public function admin_notices() {
		
		if( version_compare( get_bloginfo( 'version' ), $this->plugin['min_wp'], '<' ) ) {
			echo "
				<div class='notice notice-error'>
					<p>" . sprintf( __( '<strong>%s</strong> requires <i>WordPress version %s</i> or higher. You have <i>version %s</i> installed.', 'cx-plugin' ), $this->name, $this->plugin['min_wp'], get_bloginfo( 'version' ) ) . "</p>
				</div>
			";
		}

		if( version_compare( PHP_VERSION, $this->plugin['min_php'], '<' ) ) {
			echo "
				<div class='notice notice-error'>
					<p>" . sprintf( __( '<strong>%s</strong> requires <i>PHP version %s</i> or higher. You have <i>version %s</i> installed.', 'cx-plugin' ), $this->name, $this->plugin['min_php'], PHP_VERSION ) . "</p>
				</div>
			";
		}

		/**
		 * Dependencies
		 *
		 * @since 1.0
		 */
		$installed_plugins = get_plugins();
		$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
		if( isset( $this->plugin['depends'] ) && is_array( $this->plugin['depends'] ) ) :
		foreach ( $this->plugin['depends'] as $plugin => $plugin_name ) {
			if( !in_array( $plugin, $active_plugins ) ) {

				$action_links = $this->action_links( $plugin );
				$button_text = array_key_exists( $plugin, $installed_plugins ) ? __( 'Activate', 'cx-plugin' ) : __( 'Install', 'cx-plugin' );
				$action_link = array_key_exists( $plugin, $installed_plugins ) ? $action_links['activate'] : $action_links['install'];
			
				echo "
					<div class='notice notice-error'>
						<p class='pl-desc'>" . sprintf( __( 'In order to <strong>%1$s</strong> run properly, <strong>%2$s</strong> needs to be activated.<a href="%3$s" class="button button-primary" style="margin-left:50px;">%4$s %2$s Now</a>', 'cx-plugin' ), $this->name, $plugin_name, $action_link, $button_text ) . "</p>
					</div>
				";
			}
		}
		endif;

		/**
		 * Remote notices
		 */
		if( apply_filters( "{$this->slug}_show_notice", true ) ) :

			$synced = get_option( "_{$this->slug}_notices_pulled" );
			if( $synced == false || $synced <= time() - HOUR_IN_SECONDS ) {
				
				$plugins = []; // array of plugins' slugs
				foreach ( array_keys( $installed_plugins ) as $plugin ) {
					$plugins[] = explode( '/', $plugin )[0];
				}

				$_url = add_query_arg( [
					'rest_route'	=> '/notices/latest',
					'plugin'		=> $this->slug,
					'plugins'		=> $plugins,
				], $this->server );

				$response = wp_remote_get( $_url );
				
				$notices = [];
				if( wp_remote_retrieve_response_code( $response ) == 200 ) :
					$notices = json_decode( wp_remote_retrieve_body( $response ) );
				endif;

				update_option( "_{$this->slug}_notices", $notices );
				update_option( "_{$this->slug}_notices_pulled", time(), false );
			}
			else {
				$notices = get_option( "_{$this->slug}_notices" );
			}

			if( is_array( $notices ) && count( $notices ) > 0 ) :
			$ajax_url = admin_url( 'admin-ajax.php' );
			foreach ( $notices as $notice ) {
				if( false == get_option( "_notice-{$this->slug}-{$notice->id}" ) ) :
				$dismiss_url = add_query_arg( [ 'action' => 'cx-dismiss', 'plugin' => $this->slug, 'notice' => $notice->id ], $ajax_url );
				echo "
				<div id='notice-{$this->slug}-{$notice->id}' class='notice cx-notice cx-plugin-notice is-dismissible' data-id='{$notice->id}' data-dismiss-url='{$dismiss_url}'>
					<a href='{$notice->link}' target='_blank'><img src='{$notice->image}' /></a>
				</div>";
				endif;
			}
			endif;

		endif; //if( apply_filters( "cx-plugin_show_notice" ) ) :
	}

	public function dismiss_notices() {
		update_option( "_notice-{$_GET['plugin']}-{$_GET['notice']}", time(), false );
		wp_die( __( 'Dismissed' ) );
	}

	public function action_links( $plugin, $action = '' ) {

		$exploded	= explode( '/', $plugin );
		$slug		= $exploded[0];

		$links = [
			'install'		=> wp_nonce_url( admin_url( "update.php?action=install-plugin&plugin={$slug}" ), "install-plugin_{$slug}" ),
			'update'		=> wp_nonce_url( admin_url( "update.php?action=upgrade-plugin&plugin={$plugin}" ), "upgrade-plugin_{$plugin}" ),
			'activate'		=> wp_nonce_url( admin_url( "plugins.php?action=activate&plugin={$plugin}&plugin_status=all&paged=1&s" ), "activate-plugin_{$plugin}" ),
			'deactivate'	=> wp_nonce_url( admin_url( "plugins.php?action=deactivate&plugin={$plugin}&plugin_status=all&paged=1&s" ), "deactivate-plugin_{$plugin}" ),
		];

		if( $action != '' && array_key_exists( $action, $links ) ) return $links[ $action ];

		return $links;
	}
}