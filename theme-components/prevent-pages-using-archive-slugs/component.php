<?php
/**
 * Prevent pages from using the same slug as a post type archive to avoid naming conflicts
 *
 * @package WP-Theme-Components
 * @subpackage prevent-pages-using-archive-slugs
 * @author Cameron Jones
 * @version 1.0.0
 */

namespace WP_Theme_Components\Prevent_Pages_Using_Archive_Slugs;

/**
 * Bail if accessed directly
 *
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Prevent pages from using the same slug as a post type archive to avoid naming conflicts
 *
 * @since 1.0.0
 * @param bool   $is_bad      Whether the post slug would be bad in a hierarchical post context.
 * @param string $slug        The post slug.
 * @param string $post_type   Post type.
 * @param int    $post_parent Post parent ID.
 */
function wp_unique_post_slug_is_bad_hierarchical_slug( $is_bad, $slug, $post_type, $post_parent ) {
	if ( 'page' === $post_type && false === $is_bad && empty( $post_parent ) ) {
		$slugs      = array();
		$post_types = get_post_types(
			array(
				'public'      => true,
				'has_archive' => true,
			),
			'objects'
		);
		foreach ( $post_types as $post_type ) {
			$slugs[] = $post_type->rewrite['slug'];
		}
		if ( in_array( $slug, $slugs, true ) ) {
			$is_bad = true;
		}
	}
	return $is_bad;
}
