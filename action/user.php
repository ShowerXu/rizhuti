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

if (isset($_POST['action2'])) {
    global $wpdb, $wppay_table_name, $current_user;
    $uid     = $current_user->ID;
    $action2 = @$_POST['action2'];

    $msg = '';
    if ($action2 == '1') {

        $error                    = 0;
        $msg                      = '';
        $userdata                 = array();
        $userdata['ID']           = $uid;
        $userdata['nickname']     = str_replace(array('<', '>', '&', '"', '\'', '#', '^', '*', '_', '+', '$', '?', '!'), '', @$_POST['nickname']);
        $userdata['display_name'] = @$userdata['nickname'];
        $email                    = _post('user_email');
        $preg_email               = '/^[a-zA-Z0-9]+([-_.][a-zA-Z0-9]+)*@([a-zA-Z0-9]+[-.])+([a-z]{2,5})$/ims';
        if (preg_match($preg_email, $email)) {
            $userdata['user_email'] = esc_sql($email);
        }
        if (wp_update_user($userdata)) {
            $msg = '用户资料修改成功';
        }
        $error = 0;

    } elseif ($action2 == '2') {
        $error     = 0;
        $msg       = '';
        $password  = esc_sql(@$_POST['password']);
        $password2 = esc_sql(@$_POST['password2']);
        if (strlen($password) < 6) {
            $error = 1;
            $msg   = '密码长度至少6位';
        } elseif ($password != $password2) {
            $error = 1;
            $msg   = '两次输入密码不一致';
        } else {
            $userdata              = array();
            $userdata['ID']        = $uid;
            $userdata['user_pass'] = $password;
            wp_update_user($userdata);
            $error = 0;
            $msg   = '用户密码修改成功';
        }
    } elseif ($action2 == '3') {
        // 绑定QQ操作
        $reg_method = get_user_meta($uid, 'reg_method', true);
        if ($reg_method == 'qq') {
            $msg = '默认QQ注册，无法解绑！';
        } else {
            $sql = $wpdb->query("UPDATE $wpdb->users SET qqid='' WHERE ID = $uid");
            if ($sql) {
                update_user_meta($uid, 'open_bind', '0');
                $msg = '解绑QQ成功';
            } else {
                update_user_meta($uid, 'open_bind', '0');
                $msg = '解绑失败';
            }
        }
    } else {
        $msg = '请求错误！';
    }
    // 输出相应结果
    echo $msg;

} else {
    echo "非法请求！";
}
