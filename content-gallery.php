<?php while (have_posts()) : the_post(); ?>
<section class="article-focusbox" style="background-image: url(<?php echo _get_post_thumbnail_url() ?>);">
    <header class="article-header">
        <h1 class="article-title"><?php the_title(); ?></h1>
        <div class="article-meta">
            <span class="item item-1"><?php echo get_the_date().' '.get_the_time(); ?></span>
            <?php if( _hui('post_from_s') ){ ?>
                <span class="item item-6"><?php echo _get_post_from() ?></span>
            <?php } ?>
            <span class="item item-2">作者：<?php echo get_the_author() ?></span>
            <span class="item item-3"><?php echo '分类：';the_category(' / '); ?></span>
            <?php if( _hui('post_post_views') ){ ?>
                <span class="item item-4"><?php echo _get_post_views() ?></span>
            <?php } ?>
            <span class="item item-5"><?php edit_post_link('[编辑]'); ?></span>
        </div>
    </header>
</section>

<section class="container">

	<article class="article-content">
		<?php the_content(); ?>

	<?php wp_link_pages('link_before=<span>&link_after=</span>&before=<div class="article-paging">&after=</div>&next_or_number=number'); ?>

	<?php endwhile; ?>
    
    <?php get_template_part( 'module/content', 'module-share' ); ?> 

	</article>
	<?php comments_template('', true); ?>

</section>