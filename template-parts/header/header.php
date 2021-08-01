<?php
$theme = Cameronjonesweb_Base_Theme::get_instance();
?>
<header class="site-header">
	<div class="wrap-items">
		<div class="columns site-header__masthead is-mobile alignwide">
			<div class="column is-half-mobile is-narrow-tablet site-header__logo">
				<?php the_custom_logo(); ?>
			</div>
			<div class="column site-header__menu">
				<?php
				$theme->hamburger_menu();
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'depth'          => 3,
						'container'      => false,
						'fallback_cb'    => false,
					)
				);
				?>
			</div>
		</div>

	</div>
</header>
<div class="site-naviation">
	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'mobile',
			'depth'          => 3,
			'container'      => false,
			'fallback_cb'    => false,
		)
	);
	?>
</div>
