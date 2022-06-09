<?php
/**
 * A system for registering and rendering ACF blocks. A fork of Render ACF Blocks With Template Part.
 *
 * @package WP-Theme-Components
 * @subpackage calculate-color-contrast
 * @author Cameron Jones
 * @version 1.0.0
 */

namespace WP_Theme_Components\Calculate_Color_Contrast;

/**
 * Bail if accessed directly
 *
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Convert a color hex code to a YIQ value
 *
 * @link 24ways.org/2010/calculating-color-contrast/
 * @since 1.0.0
 * @param string $hex Color hex code.
 * @return string
 */
function get_contrast_yiq( $hex ) {
	$hex = ltrim( $hex, '#' );
	$r   = hexdec( substr( $hex, 0, 2 ) );
	$g   = hexdec( substr( $hex, 2, 2 ) );
	$b   = hexdec( substr( $hex, 4, 2 ) );
	$yiq = ( ( $r * 299 ) + ( $g * 587 ) + ( $b * 114 ) ) / 1000;
	return $yiq;
}

/**
 * Generate a contrast color from a YIQ value
 *
 * @since 1.0.0
 * @param string $yiq Color YIQ code.
 * @return string `black` or `white`
 */
function get_bw_from_yiq( $yiq ) {
	return ( $yiq >= 128 ) ? 'black' : 'white';
}

/**
 * Generate a contrast color from a hex value
 *
 * @since 1.0.0
 * @param string $hex Color hex code.
 * @return string `black` or `white`
 */
function get_bw_from_hex( $hex ) {
	return get_bw_from_yiq( get_contrast_yiq( $hex ) );
}
