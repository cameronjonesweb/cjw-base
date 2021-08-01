<?php

$theme = Cameronjonesweb_Base_Theme::get_instance();
$image = $theme->get_page_image();
?>
<div class="site-title <?php echo ! empty( $image ) ? 'site-title--has-image' : ''; ?>" style="background-image: url( <?php echo esc_url( $image ); ?> );">
	<div class="wrap">
		<h1><?php echo esc_html( $theme->get_page_title() ); ?></h1>
	</div>
</div>
