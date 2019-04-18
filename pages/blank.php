<?php 
/**
 * template name: 空页面
 */

get_header();
?>

<section class="container">

	<?php while (have_posts()) : the_post(); ?>
		<article class="article-content" style=" margin-top: 20px; ">
			<header class="article-header">
			<h1 class="article-title"><?php the_title(); ?></h1>
			</header>
			<?php the_content(); ?>
   			<?php get_template_part( 'module/content', 'module-share' ); ?> 
		</article>

	<?php endwhile; ?>


    <?php comments_template('', true); ?>

</section>

<?php get_footer(); ?>