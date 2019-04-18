
<!-- 最新文章 -->
<section class="container">
	<?php if ((!$paged || $paged===1)) { ?>
	<div class="section-info"> 
		<h2 class="postmodettitle"><?php echo _hui('mo_postlist_title') ?></h2> 
		<div class="postmode-description"><?php echo _hui('mo_postlist_desc') ?></div> 
	</div>
	<?php } ?>

	<?php 
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 0;

		$args = array(
            'ignore_sticky_posts' => 1,
            'paged'               => $paged
		);
		$mo_postlist_no_cat = _hui('mo_postlist_no_cat');
		// var_dump($mo_postlist_no_cat);
		if($mo_postlist_no_cat){
			$pool = array();
			foreach ($mo_postlist_no_cat as $key => $value) {
				if( $value ) $pool[] = $key;
			}
			$args['cat'] = '-'.implode($pool, ',-');
		}

		query_posts($args);

		get_template_part( 'excerpt', 'home' );
	?>
</section>
<!-- 最新文章end -->
