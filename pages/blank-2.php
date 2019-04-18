<?php 
/**
 * template name: 空页面(标题栏)
 */

get_header();
?>

<?php _the_focusbox( '', get_the_title() ); ?>

<section class="container">

	<?php while (have_posts()) : the_post(); ?>

		<article class="article-content">
			<?php the_content(); ?>
    		<?php get_template_part( 'module/content', 'module-share' ); ?> 
		</article>

	<?php endwhile; ?>


    <?php comments_template('', true); ?>

</section>

<?php get_footer(); ?>