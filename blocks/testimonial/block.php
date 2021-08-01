<?php

add_action(
	'acf/init',
	function() {
		if ( function_exists( 'acf_register_block' ) ) {
			acf_register_block(
				array(
					'name'        => 'testimonial',
					'title'       => __( 'Testimonial' ),
					'description' => __( 'A custom testimonial block.' ),
					'category'    => 'formatting',
					'icon'        => 'admin-comments',
					'keywords'    => array( 'testimonial', 'quote' ),
				)
			);
		}
	}
);
