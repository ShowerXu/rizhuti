<?php 
global $current_user;
?>
<div class="info-wrap">
	<div class="user-post">
		<div class="row posts-wrapper">
	    <?php 
	    $args = array(
	    	'post_status' => array('publish', 'pending', 'draft', 'future', 'private'),
	    	'author' => get_current_user_id(),
            'posts_per_page' => 8,
            'ignore_sticky_posts' => 1,
            'paged'               => $paged
		);
		query_posts($args);
	    if(have_posts()){
	    	while ( have_posts() ) : the_post();
	        get_template_part( 'excerpt', 'item' );
	        endwhile; 
	        _paging();
	        wp_reset_query();
	    }else{
	        echo '<p style=" text-align: center; padding: 30px; ">您还没有发布文章</p>';
	    } 
	    ?>  
	    </div>
	   
	</div>
</div>