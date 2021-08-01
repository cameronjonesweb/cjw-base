<?php if ( has_post_thumbnail() ) { ?>
	<a href="<?php the_permalink(); ?>" class="hentry__thumbnail"><?php the_post_thumbnail(); ?></a>
<?php } ?>
<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
<p><?php echo esc_html( get_the_date() ); ?> | <?php echo get_the_author(); ?></p>
<?php the_excerpt(); ?>
<p><a href="<?php the_permalink(); ?>">Read more</a></p>
