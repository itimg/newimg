<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 * Version: 2.0.3
 * Text Domain: csf
 * Domain Path: /languages
 */
require_once plugin_dir_path( __FILE__ ) .'classes/setup.class.php';
require_once plugin_dir_path( __FILE__ ) .'customize/options-function.php';
require_once plugin_dir_path( __FILE__ ) .'customize/iosf.class.php';

function io_get_option($option, $default = null){ 
    $cache_key = 'io_options_cache';
    $options = wp_cache_get( $cache_key, 'options' );
    if ( false === $options ) { 
        $options = get_option('io_get_option');
        wp_cache_set( $cache_key, $options ,'options', 24 * HOUR_IN_SECONDS); 
    }
    return ( isset( $options[$option] ) ) ? $options[$option] : $default;
}
