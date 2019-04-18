<?php get_header(); ?>

<?php if( get_post_format() ){ ?>

	<?php get_template_part( 'content', get_post_format() ); ?>

<?php }else{ ?>


	<section class="container">
	    <div class="content-wrap">
	    	<div class="content">
	    		<?php while (have_posts()) : the_post(); ?>

	    		

	            <?php _the_ads('ad_page_header', 'page-header') ?>

	    		<article class="article-content">
	    			<header class="article-header">
	    			<h1 class="article-title"><?php the_title(); ?></h1>
	    			<div class="article-meta">
	    				<span class="item item-1"><?php echo get_the_date().' '.get_the_time(); ?></span>
	    				<span class="item item-5"><?php edit_post_link('[编辑]'); ?></span>
	    			</div>
	    		</header>
	    		
	    			<?php the_content(); ?>
	    			<?php get_template_part( 'module/content', 'module-share' ); ?> 
	    		</article>

	    		<?php wp_link_pages('link_before=<span>&link_after=</span>&before=<div class="article-paging">&after=</div>&next_or_number=number'); ?>

	    		<?php endwhile; ?>

	            
	            
	            <?php _the_ads('ad_page_footer', 'page-footer') ?>

	            <?php comments_template('', true); ?>

	    	</div>
	    </div>
		<?php get_sidebar(); ?>
	</section>

<?php } ?>

<?php get_footer(); ?>