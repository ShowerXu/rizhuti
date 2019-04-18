<?php 
/**
 * template name: 热门标签
 */

get_header();
?>

<?php _the_focusbox( '', get_the_title() ); ?>

<section class="container">

	<div class="tagslist">
		<ul>
			<?php 
				$tags_count = _hui('page_tags_count', 50);
				$tagslist = get_tags('orderby=count&order=DESC&number='.$tags_count);
				foreach($tagslist as $tag) {
					echo '<li><a class="name" href="'.get_tag_link($tag).'">'. $tag->name .'</a><small>&times;'. $tag->count .'</small>'; 

					$posts = get_posts( "tag_id=". $tag->term_id ."&numberposts=1" );
					foreach( $posts as $post ) {
						setup_postdata( $post );
						echo '<p><a class="tit" href="'.get_permalink().'">'.get_the_title().'</a></p>';
					}

					echo '</li>';
				} 
		
			?>
		</ul>
	</div>

</section>

<?php get_footer(); ?>