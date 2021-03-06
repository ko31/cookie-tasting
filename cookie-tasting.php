<?php
/*
 * Plugin Name: Cookie Tasting
 * Plugin URI: https://github.com/tarosky/cookie-tasting
 * Description: User can
 * Author: Tarosky INC.
 * Version: 1.0.2
 * Author URI: https://tarosky.co.jp
 * License: GPL3 or later
 * Text Domain: cookie
 * Domain Path: /languages/
 *
 * @package cookie
 */


defined( 'ABSPATH' ) || die();

/**
 * Get plugin version.
 *
 * @return string
 */
function cookie_tasting_version() {
	static $info = null;
	if ( is_null( $info ) ) {
		$info = get_file_data( __FILE__, [
			'version' => 'Version',
		] );
	}
	return $info['version'];
}

/**
 * Initialize Cookie setting.
 */
function cookie_tasting_init() {
	// Load text domain.
	load_plugin_textdomain( 'cookie', false, basename( __DIR__ ) . '/languages' );
	// Includes all hooks.
	$include_dir = __DIR__ . '/includes';
	foreach ( scandir( $include_dir ) as $file ) {
		if ( preg_match( '#^[^._].*\.php$#u', $file ) ) {
			require $include_dir . '/' . $file;
		}
	}
}
add_action( 'plugins_loaded', 'cookie_tasting_init' );
