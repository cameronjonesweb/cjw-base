<?php

use function WP_Theme_Components\Register_Local_Assets\register_local_asset;

class Cameronjonesweb_Base_Theme {

	private $menu_locations = array(
		'primary' => 'Primary (Desktop)',
		'mobile'  => 'Primary (Mobile)',
	);

	private $widget_areas = array(
		array(
			'name'          => 'Footer',
			'id'            => 'footer',
			'before_widget' => '<div>',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widgettitle">',
			'after_title'   => '</h4>',
		)
	);

	/**
	 * Creates a singleton instance of the theme class.
	 *
	 * @since 1.0.0
	 */
	public static function get_instance() {
		static $inst = null;
		if ( null === $inst ) {
			$inst = new self();
		}
		return $inst;
	}

	public function __construct() {
		$this->files();
		$this->bootstrap();
	}

	public function hooks() {
		// Actions.
		add_action( 'after_setup_theme', array( $this, 'register_menus' ) );
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_action( 'init', array( $this, 'register_assets' ) );
		add_action( 'template_redirect', array( $this, 'is_404_fix' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'cjw_before_loop', array( $this, 'content_wrapper_open' ), -20 );
		add_action( 'cjw_after_loop', array( $this, 'content_wrapper_close' ), 20 );
		add_action( 'cjw_after_loop', array( $this, 'archive_pagination' ), 15 );
		add_action( 'cjw_end_post', array( $this, 'post_navigation' ) );
		add_action( 'cjw_end_post', array( $this, 'internal_post_pagination' ), 0 );
		add_action( 'widgets_init', array( $this, 'register_widget_areas' ) );
		// Filters.
		add_filter( 'post_class', array( $this, 'post_class' ) );
		add_filter( 'wp_nav_menu_args', array( $this, 'nav_menu_args' ) );
	}

	public function files() {
		require_once $this->get_template_directory( 'inc/components.php' );
		require_once $this->get_template_directory( 'inc/class-updater.php' );
	}

	public function bootstrap() {
		$updater = array(
			'name' => 'Base Theme', // Theme Name.
			'repo' => 'cameronjonesweb/cjw-base', // Theme repository.
			'slug' => 'cjw-base', // Theme Slug.
			'ver'  => 0.2, // Theme Version.
		);
		new Updater( $updater );
		$this->hooks();
	}

	public function get_template_directory( $path = '' ) {
		return trailingslashit( get_template_directory() ) . $path;
	}

	public function get_template_directory_uri( $path = '' ) {
		return trailingslashit( get_template_directory_uri() ) . $path;
	}

	public function render( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'footer'  => true,
				'header'  => true,
				'sidebar' => false,
				'title'   => is_front_page() && ! is_blog() ? false : true,
			)
		);
		$args = apply_filters( 'cjw_render_args', $args );
		get_header();
		if ( $args['header'] ) {
			$this->get_template_part( 'header/header', is_bool( $args['header'] ) ? '' : $args['header'] );
		}
		?>
		<main class="site-content" id="content">
			<?php
			if ( $args['title'] ) {
				$this->get_template_part( 'title/title', is_bool( $args['title'] ) ? '' : $args['title'] );
			}
			do_action( 'cjw_before_loop' );
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();
					do_action( 'cjw_before_post', get_the_ID() );
					?>
					<article <?php post_class(); ?>>
						<?php
						do_action( 'cjw_start_post' );
						if ( is_search() ) {
							$this->get_template_part( 'misc/search', '', array( 'post_id' => get_the_ID() ) );
						} elseif ( is_archive() || is_home() ) {
							$this->get_template_part( 'content/archive', get_post_type(), array( 'post_id' => get_the_ID() ) );
						} elseif ( is_singular() ) {
							$template = get_page_template_slug();
							if ( ! empty( $template ) ) {
								$template = substr( $template, strrpos( $template, '/' ) + 1 ); // Remove path.
								$template = substr( $template, 0, -4 ); // Remove file extension.
								$this->get_template_part( 'content/template', $template, array( 'post_id' => get_the_ID() ) );
							} else {
								$this->get_template_part( 'content/single', get_post_type(), array( 'post_id' => get_the_ID() ) );
							}
						}
						do_action( 'cjw_end_post' );
						?>
					</article>
					<?php
					do_action( 'cjw_after_post', get_the_ID() );
				}
			} else {
				if ( is_404() ) {
					$this->get_template_part( 'misc/404' );
				} else {
					// Archive with no posts.
					$this->get_template_part( 'misc/no-posts' );
				}
			}
			do_action( 'cjw_after_loop' );
			?>
		</main>
		<?php
		if ( $args['footer'] ) {
			get_template_part( 'template-parts/footer/footer', is_bool( $args['footer'] ) ? '' : $args['footer'] );
		}
		get_footer();
	}

	public function theme_supports() {
		add_theme_support( 'align-wide' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'custom-background' );
		add_theme_support( 'custom-logo' );
		add_theme_support( 'custom-header', array( 'default-text-color' => 'fff' ) );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'html5' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'wp-block-styles' );
	}

	public function register_assets() {
		// Styles.
		register_local_asset( 'parent-theme-style', 'assets/css/style.min.css' );
		register_local_asset( 'parent-admin-style', 'assets/css/admin.min.css' );
		register_local_asset( 'font-awesome', 'assets/css/font-awesome.min.css', array(), '5.15.3' );
		// Scripts.
		register_local_asset( 'parent-theme-scripts', 'assets/js/scripts.js', array( 'jquery' ) );
	}

	public function enqueue_assets() {
		// Styles.
		wp_enqueue_style( 'parent-theme-style' );
		// Scripts.
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'parent-theme-scripts' );
	}

	public function enqueue_admin_assets() {
		// Styles.
		wp_enqueue_style( 'parent-admin-style' );
	}

	public function register_menus() {
		register_nav_menus( $this->get_menu_locations() );
	}

	public function get_menu_locations() {
		return apply_filters( 'cjw_menu_locations', $this->menu_locations );
	}

	public function get_template_part( $path = '', $slug = '', $args = array() ) {
		$path = apply_filters( 'cjw_get_template_part', 'template-parts/' . ltrim( $path, '/' ) );
		get_template_part( $path, $slug, $args );
	}

	public function is_404_fix() {
		global $wp_query;
		if ( $wp_query->is_404 ) {
			$wp_query->posts        = array();
			$wp_query->current_post = 0;
			$wp_query->post         = null;
		}
	}

	public function content_wrapper_open() {
		if ( is_archive() || is_home() || is_search() ) {
			?>
			<div class="main-content">
			<?php
		} else {
			?>
			<div>
			<?php
		}
	}

	public function content_wrapper_close() {
		?>
		</div>
		<?php
	}

	public function post_class( $classes ) {
		if ( is_singular() ) {
			$classes[] = 'wrap-items';
			$classes[] = 'main-content';
		} elseif ( is_archive() || is_home() || is_search() ) {
			$classes[] = 'wrap-items';
		}
		return $classes;
	}

	public function nav_menu_args( $args ) {
		if ( ! empty( $args['theme_location'] ) ) {
			$args['items_wrap'] = str_replace( '%2$s', '%2$s menu--' . sanitize_html_class( $args['theme_location'] ), $args['items_wrap'] );
		}
		return $args;
	}

	public function archive_pagination() {
		if ( $this->is_listing() ) {
			$this->get_template_part( 'pagination/archive' );
		}
	}

	public function post_navigation() {
		if ( is_singular( $this->display_navigation_for_posts() ) ) {
			$this->get_template_part( 'pagination/single' );
		}
	}

	public function internal_post_pagination() {
		if ( is_singular() ) {
			$this->get_template_part( 'pagination/post' );
		}
	}

	public function display_navigation_for_posts() {
		return apply_filters( 'cjw_display_pagination_for_posts', array( 'post' ) );
	}

	public function is_listing() {
		$return = false;
		if ( is_archive() || is_home() || is_search() ) {
			$return = true;
		}
		return $return;
	}

	public function register_widget_areas() {
		$widget_areas = $this->get_widget_areas();
		if ( ! empty( $widget_areas ) ) {
			foreach ( $widget_areas as $widget_area ) {
				register_sidebar( $widget_area );
			}
		}
	}

	public function get_widget_areas() {
		return apply_filters( 'cjw_widget_areas', $this->widget_areas );
	}

	public function hamburger_menu( $slug = null ) {
		$this->get_template_part( 'hamburger/hamburger', $slug );
	}

	public function get_page_title() {
		$return = '';
		if ( is_home() ) {
			if ( is_front_page() ) {
				$return = get_bloginfo( 'name' );
			} else {
				$return = get_the_title( get_option( 'page_for_posts' ) );
			}
		} elseif ( is_archive() ) {
			$return = get_the_archive_title();
		} elseif ( is_singular() ) {
			$return = get_the_title();
		} elseif ( is_search() ) {
			$return = 'Search';
		} elseif ( is_404() ) {
			$return = 'Page Not Found';
		}
		return apply_filters( 'cjw_page_title', $return );
	}

	public function get_page_image() {
		$return = get_header_image();
		if ( is_singular() && has_post_thumbnail() ) {
			$return = get_post_thumbnail_url( 'full' );
		}
		return $return;
	}

}
