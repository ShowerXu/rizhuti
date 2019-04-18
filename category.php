<?php get_header(); ?>

<?php _the_focusbox( '', single_cat_title('', false) ); ?>

<section class="container">
	<?php if (_hui('is_filters_cat') == 1) { ?>
		<div class="filters">
			<?php if (_hui('filters_cat_is')) { ?>
			<div class="filter-item">
			 	<span>所有分类：</span>
			 	<ul class="filter-catnav">
				<?php

				$variable = wp_list_categories( array(
				    'echo' => false,
				    'show_count' => false,
				    'title_li' => '',
				    'hide_empty'           => 0,
				    'child_of'            => 0,
				) );
				echo $variable;
				?>
				</ul>
			</div>
			<?php } ?>
			<?php if (_hui('is_filters_tag_is')) { ?>
			<div class="filter-item">
			 	<span>推荐标签：</span>
				<?php
					$this_cat_arg = array( 'categories' => $cat);
					$tags = _get_category_tags($this_cat_arg);
					$content = '<ul class="filter-tag">';
					if(!empty($tags)) {
					  foreach ($tags as $tag) {
					    $content .= '<li><a href="'.get_tag_link($tag->term_id).'">'.$tag->name.'</a></li>';
					  }
					}else{$content .= '<li>暂无相关标签</li>';}
					$content .= "</ul>";
					echo $content;
				?>
			</div>
			<?php } ?>
			<div class="filter-item">
			 	<span>资源类型：</span>
				<a href="<?php echo add_query_arg("price","all")?>" class="<?php if(_get('price') == 'all') echo 'on';?>">全部</a>
				<a href="<?php echo add_query_arg("price","1")?>" class="<?php if(_get('price') == '1') echo 'on';?>">付费全文</a>
				<a href="<?php echo add_query_arg("price","2")?>" class="<?php if(_get('price') == '2') echo 'on';?>">付费隐藏内容</a>
				<a href="<?php echo add_query_arg("price","3")?>" class="<?php if(_get('price') == '3') echo 'on';?>">付费下载</a>
			 </div>
			 <div class="filter-item">
			 	<span>会员权限：</span>
			 	<a href="<?php echo add_query_arg("vip","all")?>" class="<?php if(_get('vip') == 'all') echo 'on';?>">全部</a>
				<a href="<?php echo add_query_arg("vip","1")?>" class="<?php if(_get('vip') == '1') echo 'on';?>">月费会员</a>
				<a href="<?php echo add_query_arg("vip","2")?>" class="<?php if(_get('vip') == '2') echo 'on';?>">年费会员</a>
				<a href="<?php echo add_query_arg("vip","3")?>" class="<?php if(_get('vip') == '3') echo 'on';?>">终身会员</a>
			 </div>
		</div>
		<?php 
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	 		$metaArray = array(); //自定义字段数组
	 		if(isset($_GET['price'])){
			 	if($_GET['price'] == 'all'){
				 	// array_push($metaArray,array('key' => 'wppay_type', 'value'=>'0', 'compare'=>'>'));
				 }elseif ($_GET['price'] == '1') {
				 	array_push($metaArray,array('key' => 'wppay_type', 'value'=>'1', 'compare'=>'='));
				 }elseif ($_GET['price'] == '2') {
				 	array_push($metaArray,array('key' => 'wppay_type', 'value'=>'2', 'compare'=>'='));
				 }elseif ($_GET['price'] == '3') {
				 	array_push($metaArray,array('key' => 'wppay_type', 'value'=>'3', 'compare'=>'='));
				 }
			 }
			 if(isset($_GET['vip'])){
			 	if($_GET['vip'] == '1'){
				 	array_push($metaArray,array('key' => 'wppay_vip_auth', 'value'=>'1', 'compare'=>'='));
				 }elseif ($_GET['vip'] == '2') {
				 	array_push($metaArray,array('key' => 'wppay_vip_auth', 'value'=>'2', 'compare'=>'<='));
				 }elseif ($_GET['vip'] == '3') {
				 	array_push($metaArray,array('key' => 'wppay_vip_auth', 'value'=>'3', 'compare'=>'<='));
				 }
			 }

			$args = array(
			 'order' => 'DESC',
			 'cat'      => $cat,
			 'ignore_sticky_posts' => 1,
			 'meta_query' => $metaArray,
			 'paged' => $paged
			 );
			query_posts($args);
		} 
	get_template_part( 'excerpt' );
	?>
</section>

<?php get_footer(); ?>