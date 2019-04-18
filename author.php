<?php get_header(); ?>

<?php _the_focusbox( '', get_the_author_meta('display_name').' 的文章', get_the_author_meta('user_description') ); ?>

<section class="container">
	<?php get_template_part( 'excerpt' ); ?>
</section>

<?php get_footer(); ?>