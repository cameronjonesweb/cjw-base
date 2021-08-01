<?php
/**
 * Efficient script and stylesheet registration
 *
 * @package WP-Theme-Components
 * @subpackage Register-Local-Assets
 * @author Cameron Jones
 * @version 1.0.1
 * @link https://github.com/WP-Theme-Components/register-local-assets
 */

namespace WP_Theme_Components\Register_Local_Assets;

/**
 * Bail if accessed directly
 *
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Register scripts and stylesheets relative to the theme directory
 *
 * @since 1.0.0
 * @param string      $handle Asset handle.
 * @param string      $path Asset path relative to the theme directory.
 * @param array       $deps Asset dependancies.
 * @param string|null $ver Asset version. Defaults to the file modified time.
 */
function register_local_asset( $handle, $path, $deps = array(), $ver = null ) {
	$path = ltrim( $path, '/' );
	if ( empty( $ver ) ) {
		$ver = filemtime( trailingslashit( get_template_directory() ) . $path );
	}
	if ( preg_match( '/css$/', $path ) ) {
		wp_register_style( $handle, trailingslashit( get_template_directory_uri() ) . $path, $deps, $ver );
	} elseif ( preg_match( '/js$/', $path ) ) {
		wp_register_script( $handle, trailingslashit( get_template_directory_uri() ) . $path, $deps, $ver, true );
	}
}
