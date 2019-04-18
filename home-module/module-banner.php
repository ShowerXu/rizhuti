<?php  if (!$paged || $paged===1) { ?>
<div class="focusbox" id="focsbox-true" style="background-image: url(<?php echo _hui('search_bgimg')?>);background-attachment: fixed;">
	<div class="focusbox-image-overlay"></div>
	<div class="container">
		<h3 class="focusbox-title"><?php echo _hui('banner_title'); ?></h3>
			<form class="form-inline" id="fh5co-header-subscribe" method="get" action="<?php echo esc_url( home_url( '/' ) ) ?>">
				<div class="form-group">
					<input type="text" class="form-control" id="email" name="s" placeholder="输入要查找关键字">
					<button type="submit" class="btn btn-default"><?php echo _hui('search_btn'); ?></button>
				</div>
			</form>
	</div>
</div>
<?php  } ?>