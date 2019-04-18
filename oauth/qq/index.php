<?php
include_once('../../../../../wp-config.php');
$scope = 'get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo';
$appid = _hui('oauth_qqid');
$login = new QQ_LOGIN();
$login->login($appid,$scope,get_stylesheet_directory_uri() . '/oauth/qq/callback.php');
?>