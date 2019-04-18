<?php
class QQ_LOGIN
{

    public function __construct()
    {
        session_start();
    }
    // 登录类
    public function login($appid, $scope, $callback)
    {
        $_SESSION['rurl']  = $_REQUEST["rurl"];
        $_SESSION['state'] = md5(uniqid(rand(), true)); //CSRF protection
        $login_url         = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=" . $appid . "&redirect_uri=" . urlencode($callback) . "&state=" . $_SESSION['state'] . "&scope=" . $scope;
        header("Location:$login_url");
    }
    // 返回类
    public function callback($appid, $appkey, $path)
    {
        if ($_REQUEST['state'] == $_SESSION['state']) {
            $token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&" . "client_id=" . $appid . "&redirect_uri=" . urlencode($path) . "&client_secret=" . $appkey . "&code=" . $_REQUEST["code"];

            $response = get_url_contents($token_url);
            if (strpos($response, "callback") !== false) {
                $lpos     = strpos($response, "(");
                $rpos     = strrpos($response, ")");
                $response = substr($response, $lpos + 1, $rpos - $lpos - 1);
                $msg      = json_decode($response);
                if (isset($msg->error)) {
                    echo "<h3>error:</h3>" . $msg->error;
                    echo "<h3>msg  :</h3>" . $msg->error_description;
                    exit();
                }
            }

            $params = array();
            parse_str($response, $params);
            $_SESSION['access_token'] = $params["access_token"];
        } else {
            echo ("The state does not match. You may be a victim of CSRF.");
            exit;
        }
    }
    // 获取用户openid
    public function get_openid()
    {
        $graph_url = "https://graph.qq.com/oauth2.0/me?access_token=" . $_SESSION['access_token'];

        $str = get_url_contents($graph_url);
        if (strpos($str, "callback") !== false) {
            $lpos = strpos($str, "(");
            $rpos = strrpos($str, ")");
            $str  = substr($str, $lpos + 1, $rpos - $lpos - 1);
        }

        $user = json_decode($str);
        if (isset($user->error)) {
            echo "<h3>error:</h3>" . $user->error;
            echo "<h3>msg2  :</h3>" . $user->error_description;
            exit();
        }
        $_SESSION['openid'] = $user->openid;
    }
    // 获取用户信息
    public function get_user_info()
    {
        $appid         = _hui('oauth_qqid');
        $get_user_info = "https://graph.qq.com/user/get_user_info?" . "access_token=" . $_SESSION['access_token'] . "&oauth_consumer_key=" . $appid . "&openid=" . $_SESSION['openid'] . "&format=json";
        return get_url_contents($get_user_info);
    }
    // 创建新账号
    public function qq_cb()
    {
        if (is_user_logged_in()) {
            wp_die('<meta charset="UTF-8" />您已登录，请在个人中心绑定。');
        } else {
            if (isset($_SESSION['openid'])) {
                global $wpdb;

                $user_ID = $wpdb->get_var("SELECT ID FROM $wpdb->users WHERE qqid='" . $wpdb->_escape($_SESSION['openid']) . "'");
                if ($user_ID > 0) {
                    wp_set_auth_cookie($user_ID, true, false);
                    wp_redirect($_SESSION['rurl']);
                    exit();
                } else {

                    $pass       = wp_create_nonce(rand(10, 1000));
                    $uinfo      = json_decode($this->get_user_info());
                    $login_name = "u" . mt_rand(1000, 9999) . mt_rand(1000, 9999) . mt_rand(1000, 9999) . mt_rand(1000, 9999);
                    $username   = $uinfo->nickname;
                    $userdata   = array(
                        'user_login'   => $login_name,
                        'user_email'   => $login_name . '@qq.com',
                        'display_name' => $username,
                        'nickname'     => $username,
                        'user_pass'    => $pass,
                        'role'         => get_option('default_role'),
                        'first_name'   => $username,
                    );
                    $user_id = wp_insert_user($userdata);
                    update_user_meta($user_id, 'vip_type', 0); //更新等级 0 无等级
                    update_user_meta($user_id, 'vip_time', time()); //更新到期时间
                    if (is_wp_error($user_id)) {
                        echo $user_id->get_error_message();
                    } else {
                        $ff = $wpdb->query("UPDATE $wpdb->users SET qqid = '" . $wpdb->_escape($_SESSION['openid']) . "' WHERE ID = '$user_id'");
                        if ($ff) {
                            update_user_meta($user_id, 'photo', $uinfo->figureurl_qq_2);
                            update_user_meta($user_id, 'qq_name', $username);
                            update_user_meta($user_id, 'open_bind', '1');
                            update_user_meta($user_id, 'reg_method', 'qq');
                            wp_set_auth_cookie($user_id, true, false);
                            wp_redirect($_SESSION['rurl']);

                        }
                    }
                    exit();
                }
            }
        }
    }
    // 绑定
    public function qq_bd()
    {
        if (is_user_logged_in()) {
            if (isset($_SESSION['openid'])) {

                global $wpdb;
                $hasuser_ID = $wpdb->get_var("SELECT ID FROM $wpdb->users WHERE qqid='" . $wpdb->_escape($_SESSION['openid']) . "'");
                if ($hasuser_ID) {
                    wp_die('<meta charset="UTF-8" />绑定失败，可能之前已有其他账号绑定，请先登录其他账户解绑。');
                } else {
                    global $current_user;
                    $user_id = $current_user->ID;
                    $dd      = $wpdb->query("UPDATE $wpdb->users SET qqid = '" . $wpdb->_escape($_SESSION['openid']) . "' WHERE ID = $user_id");
                    if ($dd) {
                        $uinfo = json_decode($this->get_user_info());
                        update_user_meta($user_id, 'qq_name', $uinfo->nickname);
                        update_user_meta($user_id, 'photo', $uinfo->figureurl_qq_2);
                        update_user_meta($user_id, 'open_bind', '1');
                        wp_redirect($_SESSION['rurl']);
                        exit();
                    }
                }
            }
        } else {
            wp_die('<meta charset="UTF-8" />绑定失败，请先登录。');
        }
    }

}
