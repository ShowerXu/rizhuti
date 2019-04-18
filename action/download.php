<?php
header("Content-type:text/html;character=utf-8");
ob_start();
require_once dirname(__FILE__) . "/../../../../wp-load.php";
ob_end_clean();
ini_set('display_errors', 'Off');
ini_set('error_reporting', E_ALL);
$postid = isset($_GET['postid']) ? $_GET['postid'] : false;
$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
$type = get_post_meta($postid,'wppay_type',true);
if (!$postid) {
    wp_die('下载参数信息错误！');exit();
}
session_start();

$wppay = new WPAY($postid, $user_id);

if(!$wppay->is_paid() && $type != 4){
   wp_die('非法下载，当前没有下载权限！');exit(); 
}
// 下载地址处理rzt_down_downkey url
$down        = get_post_meta($postid, 'wppay_down', true);

$lock_down   = rizhuti_lock_url($down, _hui('rzt_down_downkey'));
$unlock_down = rizhuti_unlock_url($lock_down, _hui('rzt_down_downkey'));

$post_auth = get_post_meta($postid, 'wppay_vip_auth', true);
if (intval($post_auth) == 1) {
    $is_limit = true;
} elseif (intval($post_auth) == 2) {
    $is_limit = true;
} elseif (intval($post_auth) == 3) {
    $is_limit = true;
} else {
    $is_limit = false;
}
// 用户已经登录 并且是会员 满足下载次数限制要求
if (is_user_logged_in()) {
    $user_id  = get_current_user_id();
    $vip_type = vip_type($user_id);
    if ($vip_type > 0 && $is_limit) {
        // 满足下载限制
        $this_vip_downum = this_vip_downum($user_id);
        if ($this_vip_downum['is_down']) {

            $then_session = $_SESSION['arr_pid'];
            if ($then_session) {
                $arr_postid = explode(',', $_SESSION['arr_pid']);
                // 如果不存在
                if (in_array($postid, $arr_postid)) {
                    $_SESSION['arr_pid'] = $then_session;
                } else {
                    update_user_meta($user_id, 'this_vip_downum', $this_vip_downum['today_down_num'] + 1); //更新+1
                    $_SESSION['arr_pid'] = $then_session . ',' . $postid;
                }

            } else {
                $_SESSION['arr_pid'] = $postid;
                update_user_meta($user_id, 'this_vip_downum', $this_vip_downum['today_down_num'] + 1); //更新+1
            }

            $info = rizhuti_download_file($unlock_down);
            exit();
        } else {
            $arr_postid = explode(',', $_SESSION['arr_pid']);
            // 如果不存在
            if (in_array($postid, $arr_postid)) {
                $info = rizhuti_download_file($unlock_down);
                exit();
            } else {
                wp_die('今日下载次数已经用完！');exit();
            }
        }
    }
}
if (_hui('is_down_rasmd5')) {
    header("Location:".trim($down));exit();
}else{
    $info = rizhuti_download_file($unlock_down);
}
exit();
