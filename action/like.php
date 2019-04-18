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

$meta_key = 'like';
$post_id  = isset($_POST['id']) ? trim(htmlspecialchars($_POST['id'], ENT_QUOTES)) : '';

$post_link = get_post_permalink($post_id);

if (!$post_link) {
    print_r(json_encode(array('error' => 1)));
    exit;
}

$cookie_string = '';

if (empty($_COOKIE['likes'])) {
    $cookie_string = $post_id;
    setcookie('likes', $cookie_string, time() + 8640000, COOKIEPATH, COOKIE_DOMAIN);
} else {
    $ids = $_COOKIE['likes'];
    $ids = explode('.', $ids);

    if (!in_array($post_id, $ids)) {

        if (count($ids) > 50) {
            array_shift($ids);
        }

        $ids[]         = $post_id;
        $cookie_string = implode('.', $ids);
        setcookie('likes', $cookie_string, time() + 8640000, COOKIEPATH, COOKIE_DOMAIN);
    }

}

// update
if (empty($_COOKIE['likes']) || !in_array($post_id, explode('.', $_COOKIE['likes']))) {

    $p_meta = (int) get_post_meta($post_id, $meta_key, true);

    if (!$p_meta) {
        $p_meta = 0;
    }

    update_post_meta($post_id, $meta_key, $p_meta + 1);

    print_r(json_encode(array('error' => 0, 'response' => $p_meta + 1)));
    exit;

}

print_r(json_encode(array('error' => 6)));
exit;
