
<?php if ((!$paged || $paged===1)) { ?>
	<!-- about -->
	<section class="container-white home2" style="background-image: url(<?php echo _hui_img('about_bgimg') ?>)">
		<div class="container">
			<h3><?php echo _hui('about_title') ?></h3>
			<p><?php echo _hui('about_desc') ?></p>
			<a href="<?php echo _hui('about_btn_href') ?>" class="btn btn-wiht"><?php echo _hui('about_btn') ?></a>
		</div>
	</section>
<?php } ?>
