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
 * @subpackage License
 * @author Codexpert <hi@codexpert.io>
 */
class License {
	
	public $slug;
	
	public $plugin;
	
	public $name;
	
	public $license_page;

	/**
	 * Is it in the validating state?
	 */
	public $validating = false;

	public function __construct( $plugin ) {

		$this->plugin 	= $plugin;

		$this->server 		= untrailingslashit( $this->plugin['server'] );
		$this->slug 		= $this->plugin['TextDomain'];
		$this->name 		= $this->plugin['Name'];
		$this->license_page = isset( $this->plugin['license_page'] ) ? $this->plugin['license_page'] : admin_url( "admin.php?page={$this->slug}" );
		
		if( isset( $this->plugin['updatable'] ) && $this->plugin['updatable'] ) {
			$this->plugin['license'] = $this;
			$update	= new Update( $this->plugin );
		}

		self::hooks();
	}

	public function hooks() {
		register_activation_hook( $this->plugin['file'], [ $this, 'activate' ] );
		register_deactivation_hook( $this->plugin['file'], [ $this, 'deactivate' ] );
		add_action( 'codexpert-daily', [ $this, 'validate' ] );
		add_action( 'admin_init', [ $this, 'init' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ], 99 );
		add_action( 'admin_notices', [ $this, 'admin_notices' ] );
		add_action( 'rest_api_init', [ $this, 'register_endpoints' ] );
	}

	public function activate() {
		if ( ! wp_next_scheduled ( 'codexpert-daily' ) ) {
		    wp_schedule_event( time(), 'daily', 'codexpert-daily' );
		}
	}

	public function deactivate() {
		wp_clear_scheduled_hook( 'codexpert-daily' );
	}

	public function validate() {
		if( $this->_is_activated() ) {

			/**
			 * It's in the validating state
			 */
			$this->validating = true;

			$validation = $this->do( 'check', $this->get_license_key(), $this->name ) ;
			if( $validation['status'] != true ) {
				update_option( $this->get_license_status_name(), 'invalid' );
			}
			else {
				update_option( $this->get_license_status_name(), 'valid' );
			}
		}
	}

	public function init() {
		if( !isset( $_GET['pb-license'] ) ) return;

		if( $_GET['pb-license'] == 'deactivate' ) {
			if( ! wp_verify_nonce( $_GET['pb-nonce'], 'codexpert' ) ) {
				// print an error message. maybe store in a temporary session and print later?
			}
			else {
				$this->do( 'deactivate', $this->get_license_key(), $this->name );
			}
		}

		elseif( $_GET['pb-license'] == 'activate' ) {
			if( ! wp_verify_nonce( $_GET['pb-nonce'], 'codexpert' ) || $_GET['key'] == '' ) {
				// print an error message. maybe store in a temporary session and print later?
			}
			else {
				$this->do( 'activate', $_GET['key'], $this->name );
			}
		}

		$query = isset( $_GET ) ? $_GET : [];
		unset( $query['pb-license'] );
		unset( $query['pb-nonce'] );
		unset( $query['key'] );
		wp_redirect( $this->license_page );
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'codexpert-product-license', plugins_url( 'assets/css/license.css', __FILE__ ), [], $this->plugin['Version'] );
		// wp_enqueue_script( 'codexpert-product-license', plugins_url( 'assets/js/license.js', __FILE__ ), [ 'jquery' ], $this->plugin['Version'], true );
	}

	public function admin_notices() {

		if( did_action( "_license_{$this->slug}_notice" ) ) return;
		do_action( "_license_{$this->slug}_notice" );

		if( ! $this->_is_activated() ) {
			echo '
			<div class="pl-notice notice notice-error">
				<p class="pl-desc">' . sprintf( __( '<strong>Notice:</strong> Please activate your license for <strong><i>%s</i></strong>. The plugin won\'t work without activation!', 'codexpert' ), $this->name ) . '<a href="' . $this->license_page . '" class="button button-primary" style="margin-left:50px;">Click Here</a>' . '</p>
			</div>';
		}
		elseif( $this->_is_activated() && ( $this->_is_invalid() || $this->_is_expired() ) && apply_filters( 'codexpert-show_validation_notice', true, $this->plugin ) ) {
			echo '
			<div class="pl-notice notice notice-error">
				<p class="pl-desc">' . sprintf( __( '<strong>Attention:</strong> Did you change your site URL? It looks like <strong>%1$s</strong> cannot connect to our server and is unable to receive updates! 😢', 'codexpert' ), $this->name ) . '<a href="' . $this->get_deactivation_url() . '" class="button button-primary" style="margin-left:50px;">Reconnect Now</a>' . '</p>
			</div>';
		}
	}

	public function activation_form() {
		$html = '';

		if( ! $this->_is_activated() ) {
			$activation_url = $this->get_activation_url();
			$activate_label	= apply_filters( "{$this->slug}_activate_label", __( 'Activate', 'codexpert' ), $this->plugin );

			$html .= '<p class="pl-desc">' . sprintf( __( 'Thanks for installing <strong>%1$s</strong> 👋', 'codexpert' ), $this->name ) . '</p>';
			$html .= '<p class="pl-desc">' . __( 'In order to make the plugin work, you need to activate the license by clicking the button below. Please reach out to us if you need any help.', 'codexpert' ) . '</p>';
			$html .= "<a id='pl-activate' class='pl-button button button-primary' href='{$activation_url}'>" . $activate_label . "</a>";
		}

		else {
			$deactivation_url	= $this->get_deactivation_url();
			$deactivate_label	= apply_filters( "{$this->slug}_deactivate_label", __( 'Deactivate', 'codexpert' ), $this->plugin );
			
			$html .= '<p class="pl-desc">' . __( 'Congratulations! 🎉', 'codexpert' ) . '</p>';
			$html .= '<p class="pl-desc">' . sprintf( __( 'The license for <strong>%s</strong> is activated. You can deactivate the license by clicking the button below.', 'codexpert' ), $this->name ) . '</p>';
			$html .= "<a id='pl-deactivate' class='pl-button button button-secondary' href='{$deactivation_url}'>" . $deactivate_label . "</a>";
		}

		return apply_filters( "{$this->slug}_activation_form", $html, $this->plugin );
	}

	// backward compatibility
	public function activator_form() {
		return $this->activation_form();
	}

	public function register_endpoints() {
		register_rest_route( 'codexpert', 'license', [
			'methods'				=> 'GET',
			'callback'				=> [ $this, 'callback_action' ],
			'permission_callback'	=> '__return_true'
		] );
	}

	public function callback_action( $request ) {
		
		add_filter( 'codexpert-is_forced', '__return_true' );
		
		$parameters = $request->get_params();
		return $this->do( $parameters['action'], $parameters['license_key'], $parameters['item_name'] );
	}

	/**
	 * Perform an action
	 *
	 * @param string $action activate|deactivate|check
	 * @param string $item_name the plugin name
	 */
	public function do( $action, $license, $item_name ) {

		if( did_action( "_{$this->slug}_did_license_action" ) && $this->validating !== true ) return;
		do_action( "_{$this->slug}_did_license_action" );

		$_response = [
			'status'	=> false,
			'message'	=> __( 'Something is wrong', 'codexpert' ),
			'data'		=> []
		];

		// data to send in our API request
		$api_params = [
			'edd_action'	=> "{$action}_license",
			'license'		=> $license,
			'item_name'		=> urlencode( $item_name ),
			'url'			=> home_url()
		];

		$response		= wp_remote_post( $this->server, [ 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ] );
		$license_data	= json_decode( wp_remote_retrieve_body( $response ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			$_response['message'] = is_wp_error( $response ) ? $response->get_error_message() : __( 'An error occurred, please try again.', 'codexpert' );
		}

		// it's an activation request
		elseif( $action == 'activate' ) {

			// license key is not OK?
			if ( false === $license_data->success ) {
				switch( $license_data->error ) {
					case 'expired' :

						$_response['message'] = sprintf(
							__( 'Your license key expired on %s.', 'codexpert' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;

					case 'disabled' :
					case 'revoked' :

						$_response['message'] = __( 'Your license key has been disabled.', 'codexpert' );
						break;

					case 'missing' :

						$_response['message'] = __( 'Invalid license.', 'codexpert' );
						break;

					case 'invalid' :
					case 'site_inactive' :

						$_response['message'] = __( 'Your license is not active for this URL.', 'codexpert' );
						break;

					case 'item_name_mismatch' :

						$_response['message'] = sprintf( __( 'This appears to be an invalid license key for %s.', 'codexpert' ), $item_name );
						break;

					case 'no_activations_left':

						$_response['message'] = __( 'Your license key has reached its activation limit.', 'codexpert' );
						break;

					default :

						$_response['message'] = __( 'An error occurred, please try again.', 'codexpert' );
						break;
				}

			}

			// license key is OK
			else {
				update_option( $this->get_license_key_name(), $license );
				update_option( $this->get_license_status_name(), $license_data->license );
				update_option( $this->get_license_expiry_name(), ( $license_data->expires == 'lifetime' ? 4765132799 : strtotime( $license_data->expires ) ) );

				$_response['status']	= $license_data;
				$_response['message']	= __( 'License activated', 'codexpert' );
			} 

		}

		// it's a deactivation request
		elseif( $action == 'deactivate' ) {
			if( ( isset( $license_data->license ) && $license_data->license == 'deactivated' ) || $this->_is_forced() ) { // "deactivated" or "failed"
				delete_option( $this->get_license_key_name() );
				delete_option( $this->get_license_status_name() );
				delete_option( $this->get_license_expiry_name() );

				$_response['status']	= true;
				$_response['message'] = __( 'License deactivated', 'codexpert' );
			}
		}

		// it's a verification request
		elseif( $action == 'check' ) {
			if( isset( $license_data->license ) && $license_data->license == 'valid' ) {
				$_response['status']	= true;
				$_response['message']	= __( 'License valid', 'codexpert' );
			} else {
				$_response['status']	= false;
				$_response['message']	= __( 'License invalid', 'codexpert' );
			}
		}

		return $_response;
	}

	public function get_activation_url() {
		$query					= isset( $_GET ) ? $_GET : [];
		$query['pb-nonce']		= wp_create_nonce( 'codexpert' );

		$activation_url = add_query_arg( [
			'item_id'	=> $this->plugin['item_id'],
			'pb-nonce'	=> wp_create_nonce( 'codexpert' ),
			'track'		=> base64_encode( $this->license_page )
		], trailingslashit( $this->get_activation_page() ) );

		return apply_filters( 'codexpert-activation_url', $activation_url, $this->plugin );
	}

	public function get_deactivation_url() {
		$query					= isset( $_GET ) ? $_GET : [];
		$query['pb-nonce']		= wp_create_nonce( 'codexpert' );
		$query['pb-license']	= 'deactivate';

		$deactivation_url = add_query_arg( $query, $this->license_page );

		return apply_filters( 'codexpert-deactivation_url', $deactivation_url, $this->plugin );
	}

	public function get_activation_page() {
		return apply_filters( 'codexpert-activation_page', "{$this->server}/connect", $this->plugin );
	}

	// option_key in the wp_options table
	public function get_license_key_name() {
		return "_license_{$this->slug}_key";
	}

	// option_key in the wp_options table
	public function get_license_status_name() {
		return "_license_{$this->slug}_status";
	}

	// option_key in the wp_options table
	public function get_license_expiry_name() {
		return "_license_{$this->slug}_expiry";
	}

	public function get_license_key() {
		return get_option( $this->get_license_key_name() );
	}

	public function get_license_status() {
		return get_option( $this->get_license_status_name() );
	}

	public function _is_activated() {
		return $this->get_license_key() != '';
	}

	// backward compatibility
	public function _is_active() {
		return $this->_is_activated();
	}

	public function _is_invalid() {
		return $this->get_license_status() != 'valid';
	}

	public function _is_expired() {
		return time() >= get_option( $this->get_license_expiry_name() );
	}

	public function _is_forced() {
		return apply_filters( 'codexpert-is_forced', ( $this->_is_invalid() || $this->_is_expired() ), $this->plugin );
	}
}