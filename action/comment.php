<?php
if ('POST' != $_SERVER['REQUEST_METHOD']) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}
ob_start();
require_once dirname(__FILE__) . "/../../../../wp-load.php";
ob_end_clean();

nocache_headers();
$comment_post_ID = isset($_POST['comment_post_ID']) ? (int) $_POST['comment_post_ID'] : 0;
$post = get_post($comment_post_ID);
if ( empty($post->comment_status) ) {
	do_action('comment_id_not_found', $comment_post_ID);
	err(__('Invalid comment status.'));
}

$status = get_post_status($post);
$status_obj = get_post_status_object($status);

do_action('pre_comment_on_post', $comment_post_ID);

$comment_author       = ( isset($_POST['author']) )  ? trim(strip_tags($_POST['author'])) : null;
$comment_author_email = ( isset($_POST['email']) )   ? trim($_POST['email']) : null;
$comment_author_url   = ( isset($_POST['url']) )     ? trim($_POST['url']) : null;
$comment_content      = ( isset($_POST['comment']) ) ? trim($_POST['comment']) : null;
$edit_id              = ( isset($_POST['edit_id']) ) ? $_POST['edit_id'] : null; 


// If the user is logged in
$user = wp_get_current_user();

if ( $user->ID ) {
	if ( empty( $user->display_name ) ){
		$user->display_name=$user->user_login;
	}

	$comment_author       = $wpdb->_escape($user->display_name);
	$comment_author_email = $wpdb->_escape($user->user_email);
	$comment_author_url   = $wpdb->_escape($user->user_url);

	if ( current_user_can('unfiltered_html') ) {
		if ( wp_create_nonce('unfiltered-html-comment_' . $comment_post_ID) != $_POST['_wp_unfiltered_html_comment'] ) {
			kses_remove_filters(); // start with a clean slate
			kses_init_filters(); // set up the filters
		}
	}
} else {
	if ( get_option('comment_registration') || 'private' == $status ){
		err('Hi，你必须登录才能发表评论！'); 
	}
}



$comment_type = '';
if ( get_option('require_name_email') && !$user->ID ) {
	if ( 6 > strlen($comment_author_email) || '' == $comment_author ){
		err( '请填写昵称和邮箱！' ); 
	}
	elseif ( !is_email($comment_author_email)){
		err( '请填写有效的邮箱地址！' ); 
	}
}

if ( '' == $comment_content ){
	err( '请填写点评论！' ); 
}

function err($ErrMsg) {
    header('HTTP/1.1 405 Method Not Allowed');
    echo $ErrMsg;
    exit;
}

$comment_parent = isset($_POST['comment_parent']) ? absint($_POST['comment_parent']) : 0;

$commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID');


if ( $edit_id ){
	$comment_id = $commentdata['comment_ID'] = $edit_id;
	wp_update_comment( $commentdata );
} else {
	$comment_id = wp_new_comment( $commentdata );
}


$comment = get_comment($comment_id);
if ( !$user->ID ) {
	$comment_cookie_lifetime = apply_filters('comment_cookie_lifetime', 30000000);
	setcookie('comment_author_' . COOKIEHASH, $comment->comment_author, time() + $comment_cookie_lifetime, COOKIEPATH, COOKIE_DOMAIN);
	setcookie('comment_author_email_' . COOKIEHASH, $comment->comment_author_email, time() + $comment_cookie_lifetime, COOKIEPATH, COOKIE_DOMAIN);
	setcookie('comment_author_url_' . COOKIEHASH, esc_url($comment->comment_author_url), time() + $comment_cookie_lifetime, COOKIEPATH, COOKIE_DOMAIN);
}


$comment_depth = 1;
$tmp_c = $comment;
while($tmp_c->comment_parent != 0){
	$comment_depth++;
	$tmp_c = get_comment($tmp_c->comment_parent);
}



echo '<li '; comment_class(); echo ' id="comment-'.get_comment_ID().'">';

	echo '<div class="comt-avatar">';
		echo _get_user_avatar($comment->comment_author_email, true);
	echo '</div>';

	echo '<div class="comt-main" id="div-comment-'.get_comment_ID().'">';
	    echo str_replace(' src=', ' data-original=', convert_smilies(get_comment_text()));
	    
		echo '<div class="comt-meta">';
			if ($comment->comment_approved == '0'){
		    	echo '<span class="comt-approved">待审核</span>';
		    }
			echo '<span class="comt-author">'.get_comment_author_link().'</span>';
	    	echo date('G:i', strtotime($comment->comment_date));
		echo '</div>';
	echo '</div>';

