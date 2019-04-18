<?php if( (is_single() && (_hui('post_share_s') || _hui('post_like_s') || _hui('post_rewards_s'))) || (is_page() && (_hui('page_share_s') || _hui('page_like_s') || _hui('page_rewards_s'))) ){ ?>
    <div class="article-actions clearfix">

        <?php if( ( is_single() && _hui('post_share_s') ) || ( is_page() && _hui('page_share_s') ) ){ ?>
            <?php _the_shares() ?>
        <?php } ?>

        <?php if( ( is_single() && _hui('post_like_s') ) || ( is_page() && _hui('page_like_s') ) ){ ?>
            <?php $like = _get_post_like_data(get_the_ID()); ?>
            <a href="javascript:;" class="action-like<?php echo $like->liked?' actived':'' ?>" data-pid="<?php echo get_the_ID() ?>" etap="like"><i class="iconfont">&#xe63a;</i>赞(<span><?php echo $like->count; ?></span>)</a>
        <?php } ?>

        <?php if( ( is_single() && _hui('post_rewards_s') ) || ( is_page() && _hui('page_rewards_s') ) ){ ?>
            <a href="javascript:;" class="action-rewards" etap="rewards"><i class="iconfont">&#xe628;</i><?php echo _hui('post_rewards_text', '打赏') ?></a>
        <?php } ?>

    </div>
<?php } ?>