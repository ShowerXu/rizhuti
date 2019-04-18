<?php if (!$paged || $paged===1) { 
$module_home_vip = _hui( 'mo_home_vip' );
?>

<?php if (!$module_home_vip) { ?>
    <h2 style=" text-align: center; margin: 0 auto; padding: 60px; ">请前往后台设置VIP介绍模块！</h2>
<?php }else{ ?>
	<section class="container home3">
		<div class="container">
			<div class="row block-wrapper" style="padding-bottom: 0; padding-top: 60px; margin-bottom: 0; ">
			<?php foreach ($module_home_vip as $key => $value) { ?>
				<?php if ($value['_title']) { 
					echo'<div class="block-item"><div class="icon"><img src="' .$value['_img']['url'].' " width="100%"></div><h3 class="content0-title">'.$value['_title'].'</h3><p>'.$value['_desc'].'</p></div>';
				} ?>
			<?php } ?>
			</div>
		</div>
	</section>
<?php } ?>

<?php } ?>
