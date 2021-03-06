<?php
/**
 * Functions for cookie tatsting
 *
 * @package cookie
 * @since 1.0.0
 */

/**
 * Should secure cookie?
 *
 * @return bool
 */
function cookie_tasting_should_be_secure() {
	return false !== strpos( get_option( 'siteurl' ), 'https://' );
}

/**
 * Update cookie
 *
 * @param int $user_id
 */
function cookie_tasting_record( $user_id ) {
	$data    = cookie_tasting_values( $user_id );
	// 2 years.
	$expires = apply_filters( 'cookie_tasting_period', 60 * 60 * 24 * 365 * 2 );
	$expires += current_time( 'timestamp', true );
	// Check if home is SSL.
	$is_secure = cookie_tasting_should_be_secure();
	foreach ( $data as $key => $value ) {
		$cookie_name = "ctwp_{$key}";
		setcookie( $cookie_name,  $value, $expires, COOKIEPATH, COOKIE_DOMAIN, $is_secure, false );
	}
}

/**
 * The interval period to check cookie is real.
 *
 * @return int
 */
function cookie_tasting_interval() {
	return (int) apply_filters( 'cookie_tasting_limit', 60 * 5 );
}

/**
 * Get user name.
 *
 * @return string
 */
function cookie_tasting_guest_name() {
	return (string) apply_filters( 'cookie_tasting_guest_name', __( 'Guest', 'cookie' ) );
}

/**
 * Get data to save.
 *
 * @param int $user_id
 * @return array
 */
function cookie_tasting_values( $user_id = 0 ) {
	$user   = get_userdata( $user_id );
	$values = [
		'name'         => $user ? $user->display_name : cookie_tasting_guest_name(),
		'last_updated' => $user ? current_time( 'timestamp', true ) : 0,
		'avatar'       => $user ? get_avatar_url( $user_id, apply_filters( 'cookie_tasting_avatar_args', [
			'size' => 60,
		], $user_id ) ) : '',
		'logout'       => $user ? wp_create_nonce( 'log-out' ) : '',
		'api'          => wp_create_nonce( 'wp_rest' ),
	];
	$values = apply_filters( 'cookie_tasting_values', $values, $user_id );
	return $values;
}

/**
 * Clear all cookie.
 */
function cookie_tasting_flush() {
	$secure = cookie_tasting_should_be_secure();
	foreach ( array_keys( cookie_tasting_values() ) as $key ) {
		$cookie_name = "ctwp_{$key}";
		// Clear cookie.
		setcookie( $cookie_name, ' ', time() - YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN, $secure, false );
	}
}
