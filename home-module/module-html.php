<?php if (!$paged || $paged===1) { 
$module_home_html = _hui( 'home_mod_html' );
?>
<?php if (!$module_home_html) { ?>
    <h2 style=" text-align: center; margin: 0 auto; padding: 60px; ">请前往后台设置自定义HTML模块！</h2>
<?php }else{ ?>
    <?php echo $module_home_html; ?>
<?php } ?>
<?php } ?>
