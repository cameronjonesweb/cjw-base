<?php
/**
 * A system for registering and rendering ACF blocks. A fork of Render ACF Blocks With Template Part.
 *
 * @package WP-Theme-Components
 * @subpackage acf-blocks
 * @author Cameron Jones
 * @version 1.0.0
 */

namespace WP_Theme_Components\ACF_Blocks;

/**
 * Bail if accessed directly
 *
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Detect any block registration files and include them
 *
 * @since 1.0.0
 */
function register_acf_blocks() {
	$files = glob( get_template_directory() . '/blocks/{**/block.php}', GLOB_BRACE );
	if ( get_template_directory() !== get_stylesheet_directory() ) {
		$files = array_merge( $files, glob( get_stylesheet_directory() . '/blocks/{**/block.php}', GLOB_BRACE ) );
	}
	if ( ! empty( $files ) ) {
		foreach ( $files as $f ) {
			require_once $f;
		}
	}
}

add_action( 'acf/init', __NAMESPACE__ . '\\register_acf_blocks', 0 );

/**
 * Block Callback Function.
 *
 * @since 1.0.0
 * @param array  $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool   $is_preview True during AJAX preview.
 * @param int    $post_id The post ID this block is saved to.
 */
function render_acf_block( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$path = get_template_path();
	$tag  = get_block_tag();
	$name = get_block_name( $block['name'] );
	$attr = array(
		'class' => array(
			'wp-block-' . $name,
		),
	);

	if ( isset( $block['anchor'] ) && ! empty( $block['anchor'] ) ) {
		$attr['id'] = $block['anchor'];
	}

	if ( isset( $block['className'] ) && ! empty( $block['className'] ) ) {
		$attr['class'][] = $block['className'];
	}

	if ( isset( $block['align'] ) && ! empty( $block['align'] ) ) {
		$attr['class'][] = 'align' . $block['align'];
	}

	$atts = get_attributes( $attr, $block );

	$attr['class'] = implode( ' ', $attr['class'] );

	printf(
		'<%1$s',
		esc_attr( $tag )
	);

	foreach ( $attr as $att => $val ) {
		printf(
			' %1$s="%2$s"',
			esc_attr( $att ),
			esc_attr( $val )
		);
	}

	echo '>';

	if ( locate_template( $path . $name . '/template.php' ) ) {
		get_template_part(
			$path . $name . '/template',
			$is_preview ? 'preview' : '',
			array(
				'block'      => $block,
				'content'    => $content,
				'is_preview' => $is_preview,
				'post_id'    => $post_id,
			)
		);
	} else {
		printf(
			'Template for %1$s block not found',
			esc_html( $block['title'] )
		);
	}

	printf(
		'</%1$s>',
		esc_attr( $tag )
	);
}

/**
 * Set the default render callback for all blocks
 *
 * @since 1.0.0
 * @param array $args Block attributes.
 * @return array
 */
function set_defaults( $args ) {
	if ( empty( $args['render_callback'] ) ) {
		$args['render_callback'] = __NAMESPACE__ . '\\render_acf_block';
	}
	if ( empty( $args['enqueue_assets'] ) ) {
		$args['enqueue_assets'] = __NAMESPACE__ . '\\enqueue_acf_block_assets';
	}
	return $args;
}

add_filter( 'acf/register_block_type_args', __NAMESPACE__ . '\\set_defaults' );

/**
 * Enqueue the block stylesheet and script files if they exist
 *
 * @param array $block Block object.
 */
function enqueue_acf_block_assets( $block ) {
	$path       = get_template_path();
	$name       = get_block_name( $block['name'] );
	$stylesheet = locate_template( $path . $name . '/block.css' );
	$script     = locate_template( $path . $name . '/block.js' );
	if ( ! empty( $stylesheet ) ) {
		wp_enqueue_style( $name . '-block-style', str_replace( untrailingslashit( ABSPATH ), untrailingslashit( site_url() ), $stylesheet ), array(), filemtime( $stylesheet ) );
	}
	if ( ! empty( $script ) ) {
		wp_enqueue_script( $name . '-block-script', str_replace( untrailingslashit( ABSPATH ), untrailingslashit( site_url() ), $script ), array(), filemtime( $script ), true );
	}
}

/**
 * Get the path the block templates are located
 *
 * @since 1.0.0
 * @return string
 */
function get_template_path() {
	return apply_filters( 'wp_theme_components/render_acf_blocks_with_template_part/template_path', 'blocks/' );
}

/**
 * Get the tag to wrap blocks with
 *
 * @since 1.0.0
 * @return string
 */
function get_block_tag() {
	return apply_filters( 'wp_theme_components/render_acf_blocks_with_template_part/block_tag', 'div' );
}

/**
 * Filter the block's attributes
 *
 * @since 1.0.0
 * @param array $attributes Array of attributes.
 * @param array $block The block settings and attributes.
 * @return array
 */
function get_attributes( $attributes, $block ) {
	return apply_filters( 'wp_theme_components/render_acf_blocks_with_template_part/block_attributes', $attributes, $block );
}

/**
 * Get the name (slug) of the block, dropping off the leading `acf/`
 *
 * @since 1.0.0
 * @param string $block_name Block name.
 * @return string
 */
function get_block_name( $block_name ) {
	return substr( $block_name, 4 );
}
