<?php if( ((is_single() && _hui('post_rewards_s')) || (is_page() && _hui('page_rewards_s'))) && ( _hui('post_rewards_alipay') || _hui('post_rewards_wechat') ) ){ ?>
	<div class="rewards-popover-mask" etap="rewards-close"></div>
	<div class="rewards-popover">
		<h3><?php echo _hui('post_rewards_title') ?></h3>
		<?php if( _hui('post_rewards_alipay') ){ ?>
		<div class="rewards-popover-item">
			<h4>支付宝扫一扫打赏</h4>
			<img src="<?php echo _hui_img('post_rewards_alipay') ?>">
		</div>
		<?php } ?>
		<?php if( _hui('post_rewards_wechat') ){ ?>
		<div class="rewards-popover-item">
			<h4>微信扫一扫打赏</h4>
			<img src="<?php echo _hui_img('post_rewards_wechat') ?>">
		</div>
		<?php } ?>
		<span class="rewards-popover-close" etap="rewards-close"><i class="iconfont">&#xe627;</i></span>
	</div>
<?php } ?>