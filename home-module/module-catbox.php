<?php if (!$paged || $paged===1) { 
$module_catbox = _hui( 'catbox' );
?>
<section class="container-white home1">
	<div class="container">
		<div class="row block-wrapper">
			<?php if (!$module_catbox) { ?>
		        <h2 style=" text-align: center; margin: 0 auto; padding: 60px; ">请前往后台新分类推荐模块！</h2>
		    <?php }else{ ?>
				<?php foreach ($module_catbox as $key => $value) { ?>
				<?php if ($value['cat_id']) { ?>
				<div class="cms-category">
					<div class="category-tile"><div class="category-tile__wrap">
						<div class="background-img" style="background-image:url(<?php echo $value['cat_bgimg']['url'] ?>)"></div>
				            <div class="category-tile__inner">
			                	<div class="category-tile__text inverse-text">
			                    	<?php 
									 	$home_special_catid= $value['cat_id'];
					   					$home_special__name = get_category($home_special_catid)->name;
					   					$home_special__link = get_category_link( $home_special_catid );
					   					$home_special__num = get_category($home_special_catid)->count;
									    echo '<a class="category-tile__name cat-theme-bg" href="'.$home_special__link.'" title="查看全部文章" style="background: #1290de;">'.$home_special__name.'</a>';
									    echo '<div class="category-tile__description">'.$home_special__num.'篇文章</div>';
									?>
			                    </div>
			                </div>
					    </div>
					</div>
				</div>
				<?php } ?>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
</section>
<?php } ?>