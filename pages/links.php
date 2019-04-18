<?php 
/**
 * Template name: 友情链接
 * Description:   A links page
 */

get_header();
?>

<?php _the_focusbox( '', get_the_title() ); ?>

<section class="container">

    <ul class="plinks">
		<?php 
			$links_cat = _hui('page_links_cat');
			$links = array();
			if( $links_cat ){
				foreach ($links_cat as $key => $value) {
					if( $value ) $links[] = $key;
				}
			}

			$links = implode(',', $links);

			if( !empty($links) ){
				wp_list_bookmarks(array(
					'category'         => $links,
					'category_orderby' => 'slug',
					'category_order'   => 'ASC',
					'orderby'          => 'rating',
					'title_before'     => '<h2><i class="fa">&#xe65c;</i>',
					'title_after'     => '</h2>',
					'order'            => 'DESC'
				)); 
			}
		?>
	</ul>
	
	<?php get_template_part( 'module/content', 'module-share' ); ?> 
	
    <?php comments_template('', true); ?>

</section>

<?php

get_footer();