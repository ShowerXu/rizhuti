<?php get_header(); ?>

<?php _the_focusbox( '', htmlspecialchars($s).' 的搜索结果', '' ); ?>

<section class="container">
	<?php if ( !have_posts() ) : ?>
		<?php _the_404() ?>
	<?php else: ?>
		<?php get_template_part( 'excerpt' ); ?>
	<?php endif; ?>
</section>

<?php get_footer(); ?>