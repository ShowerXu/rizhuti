<?php get_header(); ?>

<?php _the_focusbox( '', single_tag_title('', false), trim(strip_tags(tag_description())) ); ?>

<section class="container">
	<?php get_template_part( 'excerpt' ); ?>
</section>

<?php get_footer(); ?>