<?php 
/**
 * template name: 点赞排行
 */

get_header();
?>

<?php _the_focusbox( '', get_the_title() ); ?>

<section class="container">

	<?php 

		$args = array(
			'ignore_sticky_posts' => 1,
			'meta_key'            => 'like',
			'orderby'             => 'meta_value_num',
			'showposts'           => _hui('page_likes_count', 50)
		);

		query_posts($args);

		get_template_part( 'excerpt' );

	?>

</section>

<?php get_footer(); ?>