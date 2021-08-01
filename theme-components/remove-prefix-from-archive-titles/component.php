<?php
/**
 * Remove Prefix From Archive Titles
 *
 * @package WP_Theme_Components
 * @subpackage remove-prefix-from-archive-titles
 * @link https://github.com/WP-Theme-Components/remove-prefix-from-archive-titles
 * @version 1.0.0
 * @author Cameron Jones
 */

namespace WP_Theme_Components\Remove_Prefix_From_Archive_Titles;

/**
 * Remove semi colon and every prior from the archive title
 *
 * @link https://stackoverflow.com/a/22997249/1672694
 * @since 1.0.0
 * @param string $title The archive title.
 * @return string
 */
function remove_archive_prefix( $title ) {
	$title = trim( substr( $title, strpos( $title, ':' ) + 1 ) );
	return $title;
}

add_filter( 'get_the_archive_title', __NAMESPACE__ . '\\remove_archive_prefix' );
