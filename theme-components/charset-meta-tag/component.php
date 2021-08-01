<?php
/**
 * Add a charset meta tag to the head of the document
 *
 * @package WP-Theme-Components
 * @subpackage charset-meta-tag
 * @author Cameron Jones
 * @version 1.0.0
 * @link https://github.com/WP-Theme-Components/charset-meta-tag
 */

namespace WP_Theme_Components\Charset_Meta_Tag;

/**
 * Bail if accessed directly
 *
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Add the meta tag to the head
 *
 * @since 1.0.0
 */
function add_charset_meta_tag() {
	?>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php
}

add_action( 'wp_head', __NAMESPACE__ . '\\add_charset_meta_tag', -10 );
