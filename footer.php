<?php 
$footer_mod_1 = _hui('footer_mod_1');
$footer_mod_2 = _hui('footer_mod_2');
$footer_mod_3 = _hui('footer_mod_3');
$footer_mod_4 = _hui('footer_mod_4');
$footer_moble_is = (_hui('footer_moble_is')) ? 'cs-moble-false' : 'cs-moble-true' ;
?>
<footer class="footer">

	<div class="container <?php echo $footer_moble_is;?>">
        <div class="row">
            <div class="col l4 m6 s12">
                <div class="cs-footer-logo"><img alt="rizhuti" src="<?php echo _hui_img('header_logo'); ?>"></div>
                <div class="cs-footer-text">
                    <p><?php echo $footer_mod_1['_desc'] ?></p>
                </div>
            </div>
            <div class="col l2 m6 s6 m-t-l">
                <h4 class="footer-head"><?php echo $footer_mod_2['_title'] ?></h4>
                <ul class="cs-footer-links">
                    <li><a href="<?php echo $footer_mod_2['_href_1'] ?>"><?php echo $footer_mod_2['_hrefneme_1'] ?></a></li>
                    <li><a href="<?php echo $footer_mod_2['_href_2'] ?>"><?php echo $footer_mod_2['_hrefneme_2'] ?></a></li>
                    <li><a href="<?php echo $footer_mod_2['_href_3'] ?>"><?php echo $footer_mod_2['_hrefneme_3'] ?></a></li>
                </ul>
            </div>
            <div class="col l2 m6 s6 m-t-l">
                <h4 class="footer-head"><?php echo $footer_mod_3['_title'] ?></h4>
                <ul class="cs-footer-links">
                    <li><a href="<?php echo $footer_mod_3['_href_1'] ?>"><?php echo $footer_mod_3['_hrefneme_1'] ?></a></li>
                    <li><a href="<?php echo $footer_mod_3['_href_2'] ?>"><?php echo $footer_mod_3['_hrefneme_2'] ?></a></li>
                    <li><a href="<?php echo $footer_mod_3['_href_3'] ?>"><?php echo $footer_mod_3['_hrefneme_3'] ?></a></li>
                </ul>
            </div>
             <div class="col l2 m6 s6 m-t-l">
                <h4 class="footer-head"><?php echo $footer_mod_4['_wx_title'] ?></h4>
                <ul class="cs-footer-links">
					<img class="footer-qrimg" alt="" src="<?php echo $footer_mod_4['wx_img']['url'] ?>">
	            </ul>
            </div>
             <div class="col l2 m6 s6 m-t-l">
                <h4 class="footer-head"><?php echo $footer_mod_4['_ali_title'] ?></h4>
                <ul class="cs-footer-links">
					<img class="footer-qrimg" alt="" src="<?php echo $footer_mod_4['ali_img']['url'] ?>">
	            </ul>
            </div>
        </div>
    </div>
	<div class="footer-copyright">
        <div class="container">
            <div class="row">
                <div class="col l6 m6 s12">
                    <div class="copy-text">
                    &copy; <?php echo date('Y'); ?> <a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a> &nbsp; 
					    <?php echo get_option('zh_cn_l10n_icp_num') ? get_option('zh_cn_l10n_icp_num').' &nbsp; ' : ''; ?>
					    <?php echo $retVal = (_hui('footer_by_info')) ? '<a href="https://rizhuti.com/" target="_blank">theme by rizhuti </a>' : '' ; ?>
                      	&nbsp;本次查询请求：<?php echo get_num_queries();?>
                        &nbsp;页面生成耗时： <?php echo timer_stop(0,5);?>
					</div>
                </div>
                <div class="col l6 m6 s12">
                    <ul class="copyright-links">
                        <li><a href="<?php echo esc_url(home_url('/')); ?>">首页</a></li>
                        <li><a href="<?php echo esc_url(home_url('privacy-policy')); ?>">隐私政策</a></li>
                     </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php get_template_part( 'module/content', 'module-rewards' ); ?> 

<script>
	<?php  
		$ajaxpager = '0';
		if( ((!wp_is_mobile() &&_hui('ajaxpager_s')) || (wp_is_mobile() && _hui('ajaxpager_s_m'))) && _hui('ajaxpager') ){
			$ajaxpager = _hui('ajaxpager');
		}

		$shareimage = _hui('share_base_image') ? _hui_img('share_base_image') : '';
		if( is_single() || is_page() ){
			$thumburl = timthumb(_get_post_thumbnail_url(), array('w' => '200', 'h' => '200'));
			if( $thumburl ){
				$shareimage = $thumburl; 
			}
		}
		$shareimagethumb = _hui('share_post_image_thumb') ? 1 : 0;
        $is_alpay = _hui('alpay') ? 1 : 0;
        $is_weixinpay = _hui('weixinpay') ? 1 : 0;
        $shareimagethumb = _hui('share_post_image_thumb') ? 1 : 0;
		$is_login_popup = _hui('is_login_popup') ? 1 : 0 ;
		$is_oauth_qq = _hui('is_oauth_qq') ? 1 : 0 ;
        $is_email_reg = _hui('is_email_reg') ? 1 : 0 ;
		$is_header_fixed = _hui('is_header_fixed') ? 1 : 0 ;

	?>
  		
	window.TBUI = {
		siteurl         : '<?php echo esc_url( home_url( '/' ) ); ?>',
		uri             : '<?php echo get_stylesheet_directory_uri() ?>',
		ajaxpager       : '<?php echo $ajaxpager ?>',
		pagenum         : '<?php echo get_option('posts_per_page', 20) ?>',
		shareimage      : '<?php echo $shareimage ?>',
		shareimagethumb : '<?php echo $shareimagethumb ?>',
		is_login_popup 	: '<?php echo $is_login_popup ?>',
		is_oauth_qq 	: '<?php echo $is_oauth_qq ?>',
        is_alpay     : '<?php echo $is_alpay ?>',
        is_weixinpay     : '<?php echo $is_weixinpay ?>',
        is_header_fixed     : '<?php echo $is_header_fixed ?>',
		is_email_reg 	: '<?php echo $is_email_reg ?>'
	}
	
	console.log("version：<?php echo _the_theme_name().'_V'._the_theme_version();?>");
	console.log("SQL 请求数：<?php echo get_num_queries();?>");
	console.log("页面生成耗时： <?php echo timer_stop(0,5);?>");

</script>
<?php wp_footer(); ?>

<?php if (_hui('web_js')) { ?>
<script>
<?php echo _hui('web_js') ?>
</script>
<?php } ?>

</body>
</html>