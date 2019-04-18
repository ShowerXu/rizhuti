<?php
include_once '../../../../../wp-config.php';
$appid    = _hui('oauth_qqid');
$appkey   = _hui('oauth_qqkey');
$callback = new QQ_LOGIN();

$callback->callback($appid, $appkey, get_stylesheet_directory_uri() . '/oauth/qq/callback.php');

$callback->get_openid();

if (is_user_logged_in()) {
    $callback->qq_bd();
} else {
    $callback->qq_cb();
}
