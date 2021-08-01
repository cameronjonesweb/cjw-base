<?php
/**
 * Return or display the excerpt of a post by it's ID
 *
 * @package WP-Theme-Components
 * @subpackage excerpt-by-id
 * @author Cameron Jones
 * @version 1.0.0
 */

namespace WP_Theme_Components\Excerpt_By_ID;

/**
 * Bail if accessed directly
 *
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Get the excerpt of a post by it's ID
 *
 * @since 1.0.0
 * @param int $post_id Post ID.
 * @return string
 */
function get_excerpt_by_id( $post_id ) {
	$excerpt = apply_filters( 'get_the_excerpt', wp_trim_excerpt( '', $post_id ), $post_id );
	return $excerpt;
}

/**
 * Display the excerpt of a post by it's ID
 *
 * @since 1.0.0
 * @param int $post_id Post ID.
 */
function the_excerpt_by_id( $post_id ) {
	$excerpt = get_excerpt_by_id( $post_id );
	echo apply_filters( 'the_excerpt', $excerpt ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
