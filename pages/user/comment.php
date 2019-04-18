<?php 
global $current_user;
?>
<div class="info-wrap">
	<?php function rizhuti_strip_tags($content){
		if($content){
			$content = preg_replace("/\[.*?\].*?\[\/.*?\]/is", "", $content);
		}
		return strip_tags($content);
	} ?>
	<div class="comment-user user-usermeta-form">
        <?php 
			$counts = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE user_id=" . get_current_user_id());
			$perpage = 10;
			$pages = ceil($counts / $perpage);
			$paged = (get_query_var('paged')) ? $wpdb->escape(get_query_var('paged')) : 1;
			
			$args = array('user_id' => get_current_user_id(), 'number' => 10, 'offset' => ($paged - 1) * 10);
			$lists = get_comments($args);
			
		?>
        
          <?php
          	if($lists) {
		  ?>
            <div class="list-group-item" style=" z-index: 1; ">
              <?php foreach($lists as $value){ ?>
				<li>
					<a class="comment" href="<?php echo get_permalink($value->comment_post_ID);?>#comments"><?php echo mb_strimwidth( rizhuti_strip_tags( $value->comment_content ), 0, 50,"...");?></a>
					<div class="plp2"><span><?php echo $value->comment_date; ?>　</span><span>评论文章：<a style="color: #bababa;" target="_blank" href="<?php echo get_permalink($value->comment_post_ID);?>#comments"><?php echo get_post($value->comment_post_ID)->post_title;?></a></span></div>
				</li>
              <?php }?>
            </div>
		  <?php }?>
	</div>
</div>