<?php if( (is_single() && _hui('post_wechats_s')) || (is_page() && _hui('page_wechats_s')) ){ ?>
    <div class="article-wechats">
        <div class="article-wechatitem">
            <img src="<?php echo _hui_img('post_wechat_1_image') ?>">
            <div class="article-wechatitem-tit"><?php echo _hui('post_wechat_1_title') ?></div>
            <div class="article-wechatitem-desc"><?php echo _hui('post_wechat_1_desc') ?></div>
            <div class="article-wechatitem-users"><?php echo _hui('post_wechat_1_users') ?></div>
        </div>
    </div>
<?php } ?>