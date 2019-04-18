<?php

/**
 * Functions for admin
 */

require_once get_stylesheet_directory() . '/inc/functions-admin.php';


//custom login paga css
function custom_login()
{
    echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('template_directory') . '/inc/css/login.css" />';
}
add_action('login_head', 'custom_login');

//custom login img
function custom_login_img()
{
    echo '<style type="text/css">
h1 a {background-image: url(' . _hui('logo_src') . ') !important; }
</style>';
}
add_action('login_head', 'custom_login_img');

//custom login url
function custom_loginlogo_url($url)
{
    return get_bloginfo('url');
}
add_filter('login_headerurl', 'custom_loginlogo_url');

//自定义登录页面的LOGO提示为网站名称
add_filter('login_headertitle', create_function(false, "return get_bloginfo('name');"));

//在登录框添加额外的信息
function custom_login_message()
{
    echo '<p>登陆后可永久保留购买记录哦！</p><br />';
}
add_action('login_form', 'custom_login_message');

/**
 * 修复WordPress找回密码提示“抱歉，该key似乎无效”问题
 */
// 解决找回密码链接无效问题
function reset_password_message($message, $key)
{
    if (strpos($_POST['user_login'], '@')) {
        $user_data = get_user_by('email', trim($_POST['user_login']));
    } else {
        $login     = trim($_POST['user_login']);
        $user_data = get_user_by('login', $login);
    }
    $user_login = $user_data->user_login;
    $msg        = __('有人要求重设如下帐号的密码：') . "\r\n\r\n";
    $msg .= network_site_url() . "\r\n\r\n";
    $msg .= sprintf(__('用户名：%s'), $user_login) . "\r\n\r\n";
    $msg .= __('若这不是您本人要求的，请忽略本邮件，一切如常。') . "\r\n\r\n";
    $msg .= __('要重置您的密码，请打开下面的链接：') . "\r\n\r\n";
    $msg .= network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login');
    return $msg;
}
add_filter('retrieve_password_message', 'reset_password_message', null, 2);

/*
Gravatar 自定义头像 Hook
 */
function custom_avatar_hook($avatar, $id_or_email, $size, $default, $alt)
{
    $user = false;
    if (is_numeric($id_or_email)) {
        $id   = (int) $id_or_email;
        $user = get_user_by('id', $id);
    } elseif (is_object($id_or_email)) {
        if (!empty($id_or_email->user_id)) {
            $id   = (int) $id_or_email->user_id;
            $user = get_user_by('id', $id);
        }
    } else {
        $user = get_user_by('email', $id_or_email);
    }
    if ($user && is_object($user)) {
        if (get_user_meta($user->data->ID, 'photo', true)) {
            $avatar = get_user_meta($user->data->ID, 'photo', true);
            // 修复头像在ssl站点无法显示等问题
            if (is_ssl()) {
                if (strpos($avatar, 'http://thirdqq.qlogo.cn') !== false) {
                    $avatar = str_replace('http://thirdqq.qlogo.cn', 'https://thirdqq.qlogo.cn', $avatar);
                }
            }
            $avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
        } else if (get_user_meta($user->data->ID, 'photo', true)) {
            $avatar = get_user_meta($user->data->ID, 'photo', true);
            $avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
        }
    }
    return $avatar;
}
add_filter('get_avatar', 'custom_avatar_hook', 1, 5);

/**
 * Mail smtp setting
 */


if (_hui('search_no_page')) {
    add_filter('pre_get_posts','ri_exclude_page_from_search');
    function ri_exclude_page_from_search($query) {
    if ($query->is_search) {
        $query->set('post_type', 'post');
    }
    return $query;
    }
}


function mail_smtp($phpmailer)
{
    if (_hui('mail_smtps')) {
        $phpmailer->IsSMTP();
        $mail_name             = _hui('mail_name');
        $mail_host             = _hui('mail_host');
        $mail_port             = _hui('mail_port');
        $mail_username         = _hui('mail_name');
        $mail_passwd           = _hui('mail_passwd');
        $mail_smtpsecure       = _hui('mail_smtpsecure');
        $phpmailer->FromName   = $mail_name ? $mail_name : 'idowns';
        $phpmailer->Host       = $mail_host ? $mail_host : 'smtp.qq.com';
        $phpmailer->Port       = $mail_port ? $mail_port : '465';
        $phpmailer->Username   = $mail_username ? $mail_username : '88888888@qq.com';
        $phpmailer->Password   = $mail_passwd ? $mail_passwd : '123456789';
        $phpmailer->From       = $mail_username ? $mail_username : '88888888@qq.com';
        $phpmailer->SMTPAuth   = _hui('mail_smtpauth') == 1 ? true : false;
        $phpmailer->SMTPSecure = $mail_smtpsecure ? $mail_smtpsecure : 'ssl';

    }
}
add_action('phpmailer_init', 'mail_smtp');


/**
 * post formats
 */
add_theme_support('post-formats', array('gallery', 'image', 'video'));
add_post_type_support('page', 'post-formats');

// add link manager
add_filter('pre_option_link_manager_enabled', '__return_true');


/**
 * register menus
 */
if (function_exists('register_nav_menus')) {
    register_nav_menus(array(
        'nav'    => __('导航')
    ));
}

/**
 * register sidebar
 */
if (function_exists('register_sidebar')) {
    $sidebars = array(
        'single' => '文章页侧栏',
        'page'   => '页面侧栏',
    );
    foreach ($sidebars as $key => $value) {
        register_sidebar(array(
            'name'          => $value,
            'id'            => $key,
            'before_widget' => '<div class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3>',
            'after_title'   => '</h3>',
        ));
    }
    ;
}

/**
 * the theme
 */

$current_theme = wp_get_theme();

function _the_theme_name()
{
    global $current_theme;
    return $current_theme->get('Name');
}

function _the_theme_version()
{
    global $current_theme;
    return $current_theme->get('Version');
}

function _the_theme_aurl()
{
    global $current_theme;
    return $current_theme->get('ThemeURI');
}

function _the_theme_thumb()
{
    return _hui_img('post_default_thumb') ? _hui_img('post_default_thumb') : get_stylesheet_directory_uri() . '/img/thumb.png';
}

function _the_theme_avatar()
{
    return get_stylesheet_directory_uri() . '/img/avatar.png';
}

function _get_description_max_length()
{
    return 200;
}

function _get_delimiter()
{
    return _hui('connector') ? _hui('connector') : '-';
}
remove_action( 'wp_head', '_wp_render_title_tag', 1 );

/**
 * Widgets
 */
require_once get_stylesheet_directory() . '/inc/functions-widgets.php';


/**
 * Functions for wppay Plugin
 */
require_once get_stylesheet_directory() . '/shop/wppay.php';

/**
 * Functions Class QQ oauth
 */
if (_hui('is_oauth_qq', false)) {
    require_once get_stylesheet_directory() . '/oauth/qq/qq-class.php';
}

/**
 * target blank
 */
function _target_blank()
{
    return _hui('target_blank') ? ' target="_blank"' : '';
}


/**
 * title
 */
function _title()
{

    global $paged;

    $html = '';
    $t    = trim(wp_title('', false));

    if ($t) {
        $html .= $t . _get_delimiter();
    }

    if (get_query_var('page')) {
        $html .= '第' . get_query_var('page') . '页' . _get_delimiter();
    }

    $html .= get_bloginfo('name');

    if (is_home()) {
        if ($paged > 1) {
            $html .= _get_delimiter() . '最新发布';
        } elseif (get_option('blogdescription')) {
            $html .= _get_delimiter() . get_option('blogdescription');
        }
    }

    if (is_category()) {
        global $wp_query;
        $cat_ID  = get_query_var('cat');
        $cat_tit = _get_tax_meta($cat_ID, 'title');
        if ($cat_tit) {
            $html = $cat_tit;
        }
    }

    if ($paged > 1) {
        $html .= _get_delimiter() . '第' . $paged . '页';
    }

    return $html;
}

/**
 * Header_Menu_Walker类
 */
class Header_Menu_Walker extends Walker_Nav_Menu
{

    public function start_lvl(&$output, $depth = 0, $args = array())
    {
        $indent      = ($depth > 0 ? str_repeat("\t", $depth) : ''); // 缩进
        $classes     = array('sub-menu');
        $class_names = implode(' ', $classes); //用空格分割多个样式名
        $output .= "\n" . $indent . '<div class="' . $class_names . '"><ul>' . "\n"; //
    }
}

/**
 * 移除菜单的多余CSS选择器
 * From https://www.wpdaxue.com/remove-wordpress-nav-classes.html
 */
add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
function my_css_attributes_filter($var)
{
    return is_array($var) ? array_intersect($var, array('current_page_item', 'menu-item-has-children')) : '';
}

/**
 * menu
 */
function _the_menu($location = 'nav')
{
    echo wp_nav_menu(array('theme_location' => $location, 'container' => 'ul', 'echo' => false, 'walker' => new Header_Menu_Walker()));
}

/**
 * logo
 */
function _the_logo()
{
    $tag = is_home() ? 'div' : 'div';
    $src = _hui('logo_src');
    if (wp_is_mobile() && _hui('logo_src_m')) {
        $src = _hui('logo_src_m');
    }
    echo '<' . $tag . ' class="logo"><a href="' . get_bloginfo('url') . '" title="' . get_bloginfo('name') . (get_bloginfo('description') ? _get_delimiter() . get_bloginfo('description') : '') . '"><img src="' . $src . '"></a></' . $tag . '>';
}

/**
 * heard img
 */
function _single_header_img()
{   global $post;
    $meta_single_header_img = get_post_meta($post->ID,'single_header_img',true);
      // var_dump($meta_single_header_img);
    if ($meta_single_header_img) {
        $src = $meta_single_header_img['url'];
    }else {
        $src = timthumb(_get_post_thumbnail_url(), array('w' => '1920', 'h' => '500'));
    }
    echo 'style="background-image: url(' . $src . ')"';
}

/**
 * ads
 */
function _the_ads($name = '', $class = '')
{
    if (!_hui($name . '_s')) {
        return;
    }

    if (wp_is_mobile()) {
        echo '<div class="asst asst-m asst-' . $class . '">' . _hui($name . '_m') . '</div>';
    } else {
        echo '<div class="asst asst-' . $class . '">' . _hui($name) . '</div>';
    }
}

/**
 * leadpager
 * @return [type] [description]
 */
function _the_leadpager()
{
    global $paged;
    if ($paged && $paged > 1) {
        echo '<div class="leadpager">第 ' . $paged . ' 页</div>';
    }
}

/**
 * focusbox
 */
function _the_focusbox($title_tag = 'h1', $title = '', $text = '')
{
    if ($title) {
        if (!$title_tag) {
            $title_tag = 'h1';
        }
        $title = '<' . $title_tag . ' class="focusbox-title">' . $title . '</' . $title_tag . '>';
    }

    if ($text) {
        $text = '<div class="focusbox-text">' . $text . '</div>';
    }
    echo '<div class="focusbox"><div class="container">' . $title . $text . '</div></div>';
}

/**
 * bodyclass
 */
function _bodyclass()
{
    $class = '';

    if ((is_single() || is_page()) && comments_open()) {
        $class .= ' comment-open';
    }

    if ((is_single() || is_page()) && get_post_format()) {
        $class .= ' postformat-' . get_post_format();
    }

    if (is_super_admin()) {
        $class .= ' logged-admin';
    }

    if (_hui('list_thumb_hover_action')) {
        $class .= ' list-thumb-hover-action';
    }

    if (_hui('phone_list_news')) {
        $class .= ' list-news';
    }

    return trim($class);
}

/**
 * head
 */
function _the_head()
{
    _head_css();
    _keywords();
    _description();
    _post_views_record();
    $css_str = _hui('web_css');
    if ($css_str) {
        echo '<style>'.$css_str.'</style>';
    }

}
add_action('wp_head', '_the_head');

/**
 * foot
 */
function _the_footer()
{

}
// add_action('wp_footer', '_the_footer');

function _the_404()
{
    echo '<div class="f404"><img src="' . get_stylesheet_directory_uri() . '/img/404.png"><h2>404 . Not Found</h2><h3>沒有找到你要的内容！</h3><p><a class="btn btn-primary" href="' . get_bloginfo('url') . '">返回首页</a></p></div>';
}

function _str_cut($str, $start, $width, $trimmarker)
{
    $output = preg_replace('/^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,' . $start . '}((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,' . $width . '}).*/s', '\1', $str);
    return $output . $trimmarker;
}

function _get_excerpt($limit = 200, $after = '')
{
    $excerpt = get_the_excerpt();
    if (mb_strlen($excerpt) > $limit) {
        return _str_cut(strip_tags($excerpt), 0, $limit, $after);
    } else {
        return $excerpt;
    }
}

function _excerpt_length($length)
{
    return 200;
}
add_filter('excerpt_length', '_excerpt_length');




//输出缩略图地址
function _get_post_thumbnail_url( $post = null ){
    if( $post === null ){
        global $post;
    }

    if( has_post_thumbnail( $post ) ){    //如果有特色缩略图，则输出缩略图地址
        $post_thumbnail_src = get_post_thumbnail_id($post->ID);
    } else {
        $post_thumbnail_src = '';
        @$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
        if(!empty($matches[1][0])){

            global $wpdb;
            $att = $wpdb->get_row( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid LIKE '%s'", $matches[1][0] ) );

            if( $att ){
                $post_thumbnail_src = $att->ID; 
            }else{
                $post_thumbnail_src = $matches[1][0]; 
            }
            
        }else{

            $post_thumbnail_src = _the_theme_thumb();

        }
    }
    return $post_thumbnail_src;
}


/**
 * 图像裁切
 */


function timthumb( $src, $size = null, $set = null ){

    $modular = _hui('thumbnail_handle');

    if( is_numeric( $src ) ){
        if( $modular == 'timthumb_mi' ){
            // $src = image_downsize( $src, $size['w'].'-'.$size['h'] );
            $src = image_downsize( $src, 'thumbnail' );
        }else{
            $src = image_downsize( $src, 'full' );
        }
        $src = $src[0];
    }

    if( $set == 'original' ){
        return $src;
    }

    if( $modular == 'timthumb_php' || empty($modular) || $set == 'tim' ){

        return get_stylesheet_directory_uri().'/timthumb.php?src='.$src.'&h='.$size["h"].'&w='.$size['w'].'&zc=1&a=c&q=100&s=1';

    }else{
        return $src;
    }   

} 


function _get_post_thumbnail()
{

    $src = timthumb(_get_post_thumbnail_url(), array('w' => '280', 'h' => '210'));
    return ('video' == get_post_format() ? '<span class="thumb-video"><i class="fa">&#xe62e;</i></span>' : '') . '<img src="' . _the_theme_thumb() . '" data-src="' . $src . '" class="thumb" alt="' . get_the_title() . '">';
}

function _get_filetype($filename)
{
    $exten = explode('.', $filename);
    return end($exten);
}

function _get_user_avatar($user_email = '', $src = false, $size = 50)
{

    $avatar = get_avatar($user_email, $size, _the_theme_avatar());
    if ($src) {
        return $avatar;
    } else {
        return str_replace(' src=', ' data-src=', $avatar);
    }

}

/**
 * set postthumbnail
 */
if (_hui('set_postthumbnail') && !function_exists('_set_postthumbnail')) {
    function _set_postthumbnail()
    {
        global $post;
        if (empty($post)) {
            return;
        }

        $already_has_thumb = has_post_thumbnail($post->ID);
        if (!$already_has_thumb) {
            $attached_image = get_children("post_parent=$post->ID&post_type=attachment&post_mime_type=image&numberposts=1");
            if ($attached_image) {
                foreach ($attached_image as $attachment_id => $attachment) {
                    set_post_thumbnail($post->ID, $attachment_id);
                }
            }
        }
    }

    // add_action('the_post', '_set_postthumbnail');
    add_action('save_post', '_set_postthumbnail');
    add_action('draft_to_publish', '_set_postthumbnail');
    add_action('new_to_publish', '_set_postthumbnail');
    add_action('pending_to_publish', '_set_postthumbnail');
    add_action('future_to_publish', '_set_postthumbnail');
}

/*
 * keywords
 * ====================================================
 */
function _keywords()
{
    global $s, $post;

    $keywords = '';
    if (is_singular()) {
        if (get_the_tags($post->ID)) {
            foreach (get_the_tags($post->ID) as $tag) {
                $keywords .= $tag->name . ', ';
            }

        }
        foreach (get_the_category($post->ID) as $category) {
            $keywords .= $category->cat_name . ', ';
        }

        if (_hui('post_keywords_description_s')) {
            $the = trim(get_post_meta($post->ID, 'keywords', true));
            if ($the) {
                $keywords = $the;
            }

        } else {
            $keywords = substr_replace($keywords, '', -2);
        }

    } elseif (is_home()) {
        $seo_opt = _hui('seo');
        $keywords = $seo_opt['web_keywords'];
    } elseif (is_tag()) {
        $keywords = single_tag_title('', false);
    } elseif (is_search()) {
        $keywords = esc_html($s, 1);
    } else {
        $keywords = trim(wp_title('', false));
    }
    if ($keywords) {
        echo "<meta name=\"keywords\" content=\"$keywords\">\n";
    }
}

/*
 * description
 * ====================================================
 */
function _description()
{
    global $s, $post;
    $description = '';
    $blog_name   = get_bloginfo('name');
    if (is_singular()) {
        if (!empty($post->post_excerpt)) {
            $text = $post->post_excerpt;
        } else {
            $text = $post->post_content;
        }
        $description = trim(str_replace(array("\r\n", "\r", "\n", "　", " "), " ", str_replace("\"", "'", strip_tags($text))));
        if (!($description)) {
            $description = $blog_name . "-" . trim(wp_title('', false));
        }

        if (_hui('post_keywords_description_s')) {
            $the = trim(get_post_meta($post->ID, 'description', true));
            if ($the) {
                $description = $the;
            }

        }
    } elseif (is_home()) {
        $seo_opt = _hui('seo');
        $description = $seo_opt['web_description'];
    } elseif (is_tag()) {
        $description = trim(strip_tags(tag_description()));
    } elseif (is_archive()) {
        $description = $blog_name . "-" . trim(wp_title('', false));
    } elseif (is_search()) {
        $description = $blog_name . ": '" . esc_html($s, 1) . "' " . __('的搜索結果', 'haoui');
    } else {
        $description = $blog_name . "'" . trim(wp_title('', false)) . "'";
    }
    $description = mb_substr($description, 0, _get_description_max_length(), 'utf-8');
    echo "<meta name=\"description\" content=\"$description\">\n";
}

function _smilies_src($img_src, $img, $siteurl)
{
    return get_stylesheet_directory_uri() . '/img/smilies/' . $img;
}
add_filter('smilies_src', '_smilies_src', 1, 10);

function _noself_ping(&$links)
{
    $home = get_option('home');
    foreach ($links as $l => $link) {
        if (0 === strpos($link, $home)) {
            unset($links[$l]);
        }
    }

}
add_action('pre_ping', '_noself_ping');


function _res_from_email($email)
{
    $wp_from_email = get_option('admin_email');
    return $wp_from_email;
}
add_filter('wp_mail_from', '_res_from_email');

function _res_from_name($email)
{
    $wp_from_name = get_option('blogname');
    return $wp_from_name;
}
add_filter('wp_mail_from_name', '_res_from_name');

function _comment_mail_notify($comment_id)
{
    $admin_notify         = '1';
    $admin_email          = get_bloginfo('admin_email');
    $comment              = get_comment($comment_id);
    $comment_author_email = trim($comment->comment_author_email);
    $parent_id            = $comment->comment_parent ? $comment->comment_parent : '';
    global $wpdb;
    if ($wpdb->query("Describe {$wpdb->comments} comment_mail_notify") == '') {
        $wpdb->query("ALTER TABLE {$wpdb->comments} ADD COLUMN comment_mail_notify TINYINT NOT NULL DEFAULT 0;");
    }

    if (($comment_author_email != $admin_email && isset($_POST['comment_mail_notify'])) || ($comment_author_email == $admin_email && $admin_notify == '1')) {
        $wpdb->query("UPDATE {$wpdb->comments} SET comment_mail_notify='1' WHERE comment_ID='$comment_id'");
    }

    $notify         = $parent_id ? get_comment($parent_id)->comment_mail_notify : '0';
    $spam_confirmed = $comment->comment_approved;
    if ($parent_id != '' && $spam_confirmed != 'spam' && $notify == '1') {
        $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
        $to       = trim(get_comment($parent_id)->comment_author_email);
        $subject  = 'Hi，您在 [' . get_option("blogname") . '] 的留言有人回复啦！';
        $message  = '
    <div style="color:#333;font:100 14px/24px microsoft yahei;">
      <p>' . trim(get_comment($parent_id)->comment_author) . ', 您好!</p>
      <p>您曾在《' . get_the_title($comment->comment_post_ID) . '》的留言:<br /> &nbsp;&nbsp;&nbsp;&nbsp; '
        . trim(get_comment($parent_id)->comment_content) . '</p>
      <p>' . trim($comment->comment_author) . ' 给您的回应:<br /> &nbsp;&nbsp;&nbsp;&nbsp; '
        . trim($comment->comment_content) . '<br /></p>
      <p>点击 <a href="' . htmlspecialchars(get_comment_link($parent_id)) . '">查看回应完整內容</a></p>
      <p>欢迎再次光临 <a href="' . get_option('home') . '">' . get_option('blogname') . '</a></p>
      <p style="color:#999">(此邮件由系统自动发出，请勿回复.)</p>
    </div>';
        $from    = "From: \"" . get_option('blogname') . "\" <$wp_email>";
        $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
        wp_mail($to, $subject, $message, $headers);
    }
}
add_action('comment_post', '_comment_mail_notify');

function _comment_mail_add_checkbox()
{
    echo '<label for="comment_mail_notify" class="hide" style="padding-top:0"><input type="checkbox" name="comment_mail_notify" id="comment_mail_notify" value="comment_mail_notify" checked="checked"/>' . __('有人回复时邮件通知我', 'haoui') . '</label>';
}
add_action('comment_form', '_comment_mail_add_checkbox');

function _the_shares()
{
    $htm  = '';
    $arrs = array(
        1  => '<a href="javascript:;" data-url="' . get_the_permalink() . '" class="share-weixin" title="分享到微信"><i class="fa">&#xe602;</i></a>',
        2  => '<a etap="share" data-share="weibo" class="share-tsina" title="分享到微博"><i class="fa">&#xe61f;</i></a>',
        3  => '<a etap="share" data-share="tqq" class="share-tqq" title="分享到腾讯微博"><i class="fa">&#xe60c;</i></a>',
        4  => '<a etap="share" data-share="qq" class="share-sqq" title="分享到QQ好友"><i class="fa">&#xe81f;</i></a>',
        5  => '<a etap="share" data-share="qzone" class="share-qzone" title="分享到QQ空间"><i class="fa">&#xe65e;</i></a>',
        6  => '<a etap="share" data-share="renren" class="share-renren" title="分享到人人网"><i class="fa">&#xe603;</i></a>',
        7  => '<a etap="share" data-share="douban" class="share-douban" title="分享到豆瓣网"><i class="fa">&#xe60b;</i></a>',
        8  => '<a etap="share" data-share="line" class="share-line" title="分享到Line"><i class="fa">&#xe69d;</i></a>',
        9  => '<a etap="share" data-share="twitter" class="share-twitter" title="分享到Twitter"><i class="fa">&#xe902;</i></a>',
        10 => '<a etap="share" data-share="facebook" class="share-facebook" title="分享到Facebook"><i class="fa">&#xe725;</i></a>',
    );
    $lists = '1 2 5';
    if ($lists) {
        $lists = trim($lists);
        $lists = explode(' ', $lists);
        foreach ($lists as $key => $index) {
            $htm .= $arrs[$index];
        }
    }
    if ($htm) {
        echo '<div class="shares"><strong>分享到：</strong>' . $htm . '</div>';
    }
}

function _get_post_time()
{
    return (time() - strtotime(get_the_time('Y-m-d'))) > 86400 ? get_the_date() : get_the_time();
}

//投稿
add_action('wp_ajax_publish_post', function () {
    header('Content-type:application/json; Charset=utf-8');
    global $wpdb;
    $user_id     = get_current_user_id();
    $post_id     = sanitize_text_field($_POST['post_id']);
    $post_status = sanitize_text_field($_POST['post_status']);
    $thumbnail   = sanitize_text_field($_POST['thumbnail']);

    if ($post_id) {

        $old_post = get_post($post_id);

        if ($old_post->post_author != $user_id) {
            $msg = array(
                'state' => 201,
                'tips'  => '你不能编辑别人的文章。',
            );
        } else {
            $post_arr = [
                'ID'            => $post_id,
                'post_title'    => wp_strip_all_tags($_POST['post_title']),
                'post_content'  => $_POST['editor'],
                'post_status'   => $post_status,
                'post_author'   => $user_id,
                'post_category' => $_POST['cats'],
            ];

            wp_update_post($post_arr);
            set_post_thumbnail($post_id, $thumbnail);

            if ($post_id && $thumbnail) {
                set_post_thumbnail($post_id, $thumbnail);
            }

            $msg = array(
                'state' => 200,
                'tips'  => '文章更新成功！',
                'url'   => home_url(user_trailingslashit('/user')),
            );
        }
    } else {
        $post_arr = [
            'post_title'    => wp_strip_all_tags($_POST['post_title']),
            'post_content'  => $_POST['editor'],
            'post_status'   => $post_status,
            'post_author'   => $user_id,
            'post_category' => $_POST['cats'],
        ];

        $post_id = wp_insert_post($post_arr);

        if ($post_id && $thumbnail) {
            set_post_thumbnail($post_id, $thumbnail);
        }

        if ($post_id) {
            $msg = array(
                'state' => 200,
                'tips'  => '文章提交成功',
                'url'   => home_url(user_trailingslashit('/user')),
            );
            add_post_meta($post_id, 'tg', $user_id);
        } else {
            $msg = array(
                'state' => 201,
                'tips'  => '提交失败，请稍候再试',
            );
        }
    }
    echo json_encode($msg); wp_die();
});

function _load_scripts()
{
    if (!is_admin()) {
        wp_enqueue_style('main', get_stylesheet_directory_uri() . '/style.css', array(), _the_theme_version(), 'all');

        wp_deregister_script('jquery');
        wp_deregister_script('l10n');

        $jquery_js = (_hui('enabled_cdn_assets')) ? 'https://apps.bdimg.com/libs/jquery/2.0.0/jquery.min.js' : get_stylesheet_directory_uri() . '/js/jquery.js' ;
        
        wp_register_script('jquery', $jquery_js, false, _the_theme_version(), false);
        // slide插件
        if (is_home() && _hui('home_header_style', 'style_0') == "style_0") {
            wp_enqueue_style('slides', get_stylesheet_directory_uri() . '/css/swiper.min.css', array(), _the_theme_version(), 'all');
            // wp_enqueue_script( 'slides', get_stylesheet_directory_uri() . '/js/swiper.min.js', array('jquery'), _the_theme_version(), true );
        }
        wp_enqueue_script('sticky', get_stylesheet_directory_uri() . '/js/theia-sticky-sidebar.min.js', array('jquery'), _the_theme_version(), false);
        // 弹窗js插件
        wp_enqueue_script('popup', get_stylesheet_directory_uri() . '/js/popup.min.js', array('jquery'), _the_theme_version(), false);
        // 文章图片box
        if (is_single()) {
            wp_enqueue_style('fancybox', get_stylesheet_directory_uri() . '/css/jquery.fancybox.min.css', array(), _the_theme_version(), 'all');
            wp_enqueue_script('fancybox', get_stylesheet_directory_uri() . '/js/jquery.fancybox.min.js', array('jquery'), _the_theme_version(), true);
        }
        wp_enqueue_script('popup', get_stylesheet_directory_uri() . '/js/popup.min.js', array('jquery'), _the_theme_version(), true);

        wp_enqueue_script('main', get_stylesheet_directory_uri() . '/js/main.js', array('jquery'), _the_theme_version(), true);

        // 自定义登录页面样式
        if (is_page_template('pages/user.php')) {
            wp_enqueue_script('user', get_stylesheet_directory_uri() . '/js/user.js', array('jquery'), _the_theme_version(), true);
        }

    }
}
add_action('wp_enqueue_scripts', '_load_scripts');

if (_hui('post_alt_title_s')) {
    add_filter('the_content', '_image_alt');
}
function _image_alt($content)
{
    global $post;
    $title = $post->post_title;
    $rules = array(
        '/<img(.*?) alt="(.*?)"/i' => '<img$1',
        '/<img(.*?) src="(.*?)"/i' => '<img$1 src="$2" alt="' . $title . '" title="' . $title . _get_delimiter() . get_option('blogname') . '"',
    );
    foreach ($rules as $p => $r) {
        $content = preg_replace($p, $r, $content);
    }
    return $content;
}

function _head_css()
{

    $styles = '';

    $styles .= _hui('csscode');

    if ($styles) {
        echo '<style>' . $styles . '</style>' . "\n";
    }

}

/**
 * post like
 */
function _get_post_like_data($post_id = 0)
{
    $count = get_post_meta($post_id, 'like', true);

    return (object) array(
        'liked' => _is_user_has_like($post_id),
        'count' => $count ? $count : 0,
    );
}

function _is_user_has_like($post_id = 0)
{
    if (empty($_COOKIE['likes']) || !in_array($post_id, explode('.', $_COOKIE['likes']))) {
        return false;
    }

    return true;
}

/**
 * post views
 */
function _post_views_record()
{
    if (is_singular()) {
        global $post;
        $post_ID = $post->ID;
        if ($post_ID) {
            $post_views = (int) get_post_meta($post_ID, 'views', true);
            if (!update_post_meta($post_ID, 'views', ($post_views + 1))) {
                add_post_meta($post_ID, 'views', 1, true);
            }
        }
    }
}

function _get_post_views($before = '', $after = '')
{
    global $post;
    $post_ID = $post->ID;
    $views   = (int) get_post_meta($post_ID, 'views', true);
    if ($views >= 1000) {
        $views = round($views / 1000, 2) . 'K';
    }
    return $before . $views . $after;
}

/**
 * post commemnts
 */
function _get_post_comments($before = '评论(', $after = ')')
{
    return $before . get_comments_number('0', '1', '%') . $after;
}

function _get_category_tags($args)
{
    global $wpdb;
    $tags = $wpdb->get_results
        ("
        SELECT DISTINCT terms2.term_id as tag_id, terms2.name as tag_name
        FROM
            $wpdb->posts as p1
            LEFT JOIN $wpdb->term_relationships as r1 ON p1.ID = r1.object_ID
            LEFT JOIN $wpdb->term_taxonomy as t1 ON r1.term_taxonomy_id = t1.term_taxonomy_id
            LEFT JOIN $wpdb->terms as terms1 ON t1.term_id = terms1.term_id,

            $wpdb->posts as p2
            LEFT JOIN $wpdb->term_relationships as r2 ON p2.ID = r2.object_ID
            LEFT JOIN $wpdb->term_taxonomy as t2 ON r2.term_taxonomy_id = t2.term_taxonomy_id
            LEFT JOIN $wpdb->terms as terms2 ON t2.term_id = terms2.term_id
        WHERE
            t1.taxonomy = 'category' AND p1.post_status = 'publish' AND terms1.term_id IN (" . $args['categories'] . ") AND
            t2.taxonomy = 'post_tag' AND p2.post_status = 'publish'
            AND p1.ID = p2.ID
        ORDER by tag_name
    ");
    $count = 0;

    if ($tags) {
        foreach ($tags as $tag) {
            $mytag[$count] = get_term_by('id', $tag->tag_id, 'post_tag');
            $count++;
        }
    } else {
        $mytag = null;
    }

    return $mytag;
}

/**
 * no category
 */
if (_hui('no_categoty') && !function_exists('no_category_base_refresh_rules')) {

    /*
    Plugin Name: No Category Base (WPML)
    Version: 1.2
    Plugin URI: http://infolific.com/technology/software-worth-using/no-category-base-for-wordpress/
    Description: Removes '/category' from your category permalinks. WPML compatible.
    Author: Marios Alexandrou
    Author URI: http://infolific.com/technology/
    License: GPLv2 or later
    Text Domain: no-category-base-wpml
     */

    /*
    Copyright 2015 Marios Alexandrou
    Copyright 2011 Mines (email: hi@mines.io)
    Copyright 2008 Saurabh Gupta (email: saurabh0@gmail.com)

    Based on the work by Saurabh Gupta (email : saurabh0@gmail.com)

    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
     */

    /* hooks */
    register_activation_hook(__FILE__, 'no_category_base_refresh_rules');
    register_deactivation_hook(__FILE__, 'no_category_base_deactivate');

    /* actions */
    add_action('created_category', 'no_category_base_refresh_rules');
    add_action('delete_category', 'no_category_base_refresh_rules');
    add_action('edited_category', 'no_category_base_refresh_rules');
    add_action('init', 'no_category_base_permastruct');

    /* filters */
    add_filter('category_rewrite_rules', 'no_category_base_rewrite_rules');
    add_filter('query_vars', 'no_category_base_query_vars'); // Adds 'category_redirect' query variable
    add_filter('request', 'no_category_base_request'); // Redirects if 'category_redirect' is set

    function no_category_base_refresh_rules()
    {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }

    function no_category_base_deactivate()
    {
        remove_filter('category_rewrite_rules', 'no_category_base_rewrite_rules'); // We don't want to insert our custom rules again
        no_category_base_refresh_rules();
    }

    /**
     * Removes category base.
     *
     * @return void
     */
    function no_category_base_permastruct()
    {
        global $wp_rewrite;
        global $wp_version;

        if ($wp_version >= 3.4) {
            $wp_rewrite->extra_permastructs['category']['struct'] = '%category%';
        } else {
            $wp_rewrite->extra_permastructs['category'][0] = '%category%';
        }
    }

    /**
     * Adds our custom category rewrite rules.
     *
     * @param  array $category_rewrite Category rewrite rules.
     *
     * @return array
     */
    function no_category_base_rewrite_rules($category_rewrite)
    {
        global $wp_rewrite;
        $category_rewrite = array();

        /* WPML is present: temporary disable terms_clauses filter to get all categories for rewrite */
        if (class_exists('Sitepress')) {
            global $sitepress;

            remove_filter('terms_clauses', array($sitepress, 'terms_clauses'));
            $categories = get_categories(array('hide_empty' => false));
            add_filter('terms_clauses', array($sitepress, 'terms_clauses'));
        } else {
            $categories = get_categories(array('hide_empty' => false));
        }

        foreach ($categories as $category) {
            $category_nicename = $category->slug;

            if ($category->parent == $category->cat_ID) {
                $category->parent = 0;
            } elseif ($category->parent != 0) {
                $category_nicename = get_category_parents($category->parent, false, '/', true) . $category_nicename;
            }

            $category_rewrite['(' . $category_nicename . ')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$']    = 'index.php?category_name=$matches[1]&feed=$matches[2]';
            $category_rewrite["({$category_nicename})/{$wp_rewrite->pagination_base}/?([0-9]{1,})/?$"] = 'index.php?category_name=$matches[1]&paged=$matches[2]';
            $category_rewrite['(' . $category_nicename . ')/?$']                                       = 'index.php?category_name=$matches[1]';
        }

        // Redirect support from Old Category Base
        $old_category_base                               = get_option('category_base') ? get_option('category_base') : 'category';
        $old_category_base                               = trim($old_category_base, '/');
        $category_rewrite[$old_category_base . '/(.*)$'] = 'index.php?category_redirect=$matches[1]';

        return $category_rewrite;
    }

    function no_category_base_query_vars($public_query_vars)
    {
        $public_query_vars[] = 'category_redirect';
        return $public_query_vars;
    }

    /**
     * Handles category redirects.
     *
     * @param $query_vars Current query vars.
     *
     * @return array $query_vars, or void if category_redirect is present.
     */
    function no_category_base_request($query_vars)
    {
        if (isset($query_vars['category_redirect'])) {
            $catlink = trailingslashit(get_option('home')) . user_trailingslashit($query_vars['category_redirect'], 'category');
            status_header(301);
            header("Location: $catlink");
            exit();
        }

        return $query_vars;
    }

}

// 用户订单导航分页
//

/**
 * get post mostviews
 */
function _posts_mostviews($mode = 'post', $limit = 10, $days = 15, $display = true)
{
    global $wpdb, $post;
    $limit_date = current_time('timestamp') - ($days * 86400);
    $limit_date = date("Y-m-d H:i:s", $limit_date);
    $where      = '';
    $temp       = '';

    if (!empty($mode) && $mode != 'both') {
        $where = "post_type = '$mode'";
    } else {
        $where = '1=1';
    }

    $most_viewed = $wpdb->get_results("SELECT DISTINCT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '" . current_time('mysql') . "' AND post_date > '" . $limit_date . "' AND $where AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER  BY views DESC LIMIT $limit");

    if ($most_viewed) {
        $i = 1;
        foreach ($most_viewed as $post) {
            $post_title = get_the_title();
            $post_views = intval($post->views);
            // $post_views = number_format($post_views);

            // $temp .= "<li><a href=\"".get_permalink()."\">$post_title</a> - $post_views ".__('views', 'wp-postviews')."</li>";
            $temp .= '<li class="item-' . $i . '"><a href="' . get_permalink($postid) . '"><b>' . $i . '</b><span class="thumbnail">' . _get_post_thumbnail() . '</span><h2>' . $post_title . '</h2><p>' . timeago(get_the_time('Y-m-d H:i:s')) . '<span class="post-views">阅读(' . $post_views . ')</span></p></a></li>';
            $i++;
        }
    } else {
        $temp = '<li>' . __('N/A', 'wp-postviews') . '</li>' . "\n";
    }

    if ($display) {
        echo $temp;
    } else {
        return $temp;
    }
}

function _posts_orderby_views($days = 30, $limit = 12, $display = true, $mode = 'post')
{
    global $wpdb, $post;
    $limit_date = current_time('timestamp') - ($days * 86400);
    $limit_date = date("Y-m-d H:i:s", $limit_date);
    $where      = '';
    $temp       = '';

    if (!empty($mode) && $mode != 'both') {
        $where = "post_type = '$mode'";
    } else {
        $where = '1=1';
    }

    $most_viewed = $wpdb->get_results("SELECT DISTINCT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '" . current_time('mysql') . "' AND post_date > '" . $limit_date . "' AND $where AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER  BY views DESC LIMIT $limit");

    if ($most_viewed) {
        foreach ($most_viewed as $post) {
            $temp .= '<li><a class="thumbnail" href="' . get_permalink() . '">' . _get_post_thumbnail(array()) . '<h2>' . get_the_title() . '</h2></a></li>';
        }
    } else {
        $temp = '<li>暂无内容！</li>' . "\n";
    }

    if ($display) {
        echo $temp;
    } else {
        return $temp;
    }
}

// Posts Related
function _posts_related($limit = 8)
{
    global $post;

    $exclude_id = $post->ID;
    $posttags   = get_the_tags();
    $i          = 0;

    if ($posttags) {
        $tags = '';foreach ($posttags as $tag) {
            $tags .= $tag->name . ',';
        }

        $args = array(
            'post_status'         => 'publish',
            'tag_slug__in'        => explode(',', $tags),
            'post__not_in'        => explode(',', $exclude_id),
            'ignore_sticky_posts' => 1,
            // 'orderby'             => 'comment_date',
            'posts_per_page'      => $limit,
        );
        query_posts($args);
        while (have_posts()) {
            the_post();
            if (_hui('post_related_style', 'style_0') == 'style_0') {
                echo '<li class="isthumb"><a' . _target_blank() . ' class="thumbnail" href="' . get_permalink() . '">' . _get_post_thumbnail() . '</a><h4><a' . _target_blank() . ' href="' . get_permalink() . '">' . get_the_title() . '</a></h4></li>';
            } else {
                echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
            }

            $exclude_id .= ',' . $post->ID;
            $i++;
        }
        ;
        wp_reset_query();
    }
    if ($i < $limit) {
        $cats = '';foreach (get_the_category() as $cat) {
            $cats .= $cat->cat_ID . ',';
        }

        $args = array(
            'category__in'        => explode(',', $cats),
            'post__not_in'        => explode(',', $exclude_id),
            'ignore_sticky_posts' => 1,
            // 'orderby'             => 'comment_date',
            'posts_per_page'      => $limit - $i,
        );
        query_posts($args);
        while (have_posts()) {
            the_post();
            if ($i >= $limit) {
                break;
            }

            if (_hui('post_related_style', 'style_0') == 'style_0') {
                echo '<li class="isthumb"><a' . _target_blank() . ' class="thumbnail" href="' . get_permalink() . '">' . _get_post_thumbnail() . '</a><h4><a' . _target_blank() . ' href="' . get_permalink() . '">' . get_the_title() . '</a></h4></li>';
            } else {
                echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
            }
            $i++;
        }
        ;
        wp_reset_query();
    }
    if ($i == 0) {
        return false;
    }

}

// PAGING
if (!function_exists('_paging')):

    function _paging()
{
        $p = 3;
        if (is_singular()) {
            return;
        }

        global $wp_query, $paged;
        $max_page = $wp_query->max_num_pages;
        if ($max_page == 1) {
            return;
        }

        echo '<div class="pagination' . (_hui('paging_type') == 'multi' ? ' pagination-multi' : '') . '"><ul>';
        if (empty($paged)) {
            $paged = 1;
        }

        if (_hui('paging_type') == 'multi' && $paged !== 1) {
            _paging_link(0);
        }

        // echo '<span class="pages">Page: ' . $paged . ' of ' . $max_page . ' </span> ';
        echo '<li class="prev-page">';
        previous_posts_link(__('上一页', 'haoui'));
        echo '</li>';

        if (_hui('paging_type') == 'multi') {
            if ($paged > $p + 1) {
                _paging_link(1, '<li>' . __('第一页', 'haoui') . '</li>');
            }

            if ($paged > $p + 2) {
                echo "<li><span>···</span></li>";
            }

            for ($i = $paged - $p; $i <= $paged + $p; $i++) {
                if ($i > 0 && $i <= $max_page) {
                    $i == $paged ? print "<li class=\"active\"><span>{$i}</span></li>" : _paging_link($i);
                }

            }
            if ($paged < $max_page - $p - 1) {
                echo "<li><span> ... </span></li>";
            }

        }
        //if ( $paged < $max_page - $p ) _paging_link( $max_page, '&raquo;' );
        echo '<li class="next-page">';
        next_posts_link(__('下一页', 'haoui'));
        echo '</li>';
        if (_hui('paging_type') == 'multi' && $paged < $max_page) {
            _paging_link($max_page, '', 1);
        }

        if (_hui('paging_type') == 'multi') {
            echo '<li><span>' . __('共', 'haoui') . ' ' . $max_page . ' ' . __('页', 'haoui') . '</span></li>';
        }

        echo '</ul></div>';
    }

    function _paging_link($i, $title = '', $w = '')
{
        if ($title == '') {
            $title = __('页', 'haoui') . " {$i}";
        }

        $itext = $i;
        if ($i == 0) {
            $itext = __('首页', 'haoui');
        }
        if ($w) {
            $itext = __('尾页', 'haoui');
        }
        echo "<li><a href='", esc_html(get_pagenum_link($i)), "'>{$itext}</a></li>";
    }

endif;

function _get_post_from($pid = '', $prevtext = '来源：')
{
    if (!_hui('post_from_s')) {
        return;
    }

    if (!$pid) {
        $pid = get_the_ID();
    }

    $fromname = trim(get_post_meta($pid, "fromname_value", true));
    $fromurl  = trim(get_post_meta($pid, "fromurl_value", true));
    $from     = '';

    if ($fromname) {
        if ($fromurl && _hui('post_from_link_s')) {
            $from = '<a href="' . $fromurl . '" target="_blank" rel="external nofollow">' . $fromname . '</a>';
        } else {
            $from = $fromname;
        }
        $from = (_hui('post_from_h1') ? _hui('post_from_h1') : $prevtext) . $from;
    }

    return $from;
}

function _get_tax_meta($id = 0, $field = '')
{
    $ops = get_option("_taxonomy_meta_$id");

    if (empty($ops)) {
        return '';
    }

    if (empty($field)) {
        return $ops;
    }

    return isset($ops[$field]) ? $ops[$field] : '';
}


// GET URL
function get_url_contents($url)
{   
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT,60);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    $result = curl_exec($ch);
    if($result === false)
    {
        echo 'Curl error: ' . curl_error($ch);
    }
    curl_close($ch);
    return $result;
}

// Debug // wpay_debug_log('ping....');
function wpay_debug_log($text)
{
    $file = get_template_directory() . '/shop/payment/alipay/alipay.log';
    file_put_contents($file, $text . PHP_EOL, FILE_APPEND);
}

/**
 * 对象 转 数组
 *
 * @param object $obj 对象
 * @return array
 */
function object_to_array($obj)
{
    $obj = (array) $obj;
    foreach ($obj as $k => $v) {
        if (gettype($v) == 'resource') {
            return;
        }
        if (gettype($v) == 'object' || gettype($v) == 'array') {
            $obj[$k] = (array) object_to_array($v);
        }
    }

    return $obj;
}

function get_user_page_id()
{
    global $wpdb;
    // 多个页面使用同一个模板无效
    $page_id = $wpdb->get_var($wpdb->prepare("SELECT `post_id`
               FROM `$wpdb->postmeta`, `$wpdb->posts`
               WHERE `post_id` = `ID`
                  AND `post_status` = 'publish'
                  AND `meta_key` = '_wp_page_template'
                  AND `meta_value` = %s
                  LIMIT 1;", "pages/user.php"));
    return $page_id;
}

// GET获取参数
function _post($str)
{
    $val = !empty($_POST[$str]) ? $_POST[$str] : null;
    return $val;
}
//POST获取参数
function _get($str)
{
    $val = !empty($_GET[$str]) ? $_GET[$str] : null;
    return $val;
}

//WPemail send
function _sendMail($email, $title, $message, $headers)
{
    $title      = $title . '-' . get_bloginfo('name');
    $send_email = wp_mail($email, $title, $message, $headers);
    if ($send_email) {
        return true;
    }
    return false;
}

//邮件模板 返回html字符串
function tpl_emailPay($order_num, $order_name, $order_price, $pay_type, $a_href)
{
    $html = '<div style="background-color:#eef2fa;border:1px solid #d8e3e8;color: #111;padding:0 15px;-moz-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius:5px;">';
    $html .= '<p style="font-weight: bold;color: #2196F3;">您的订单信息：</p>';
    $html .= sprintf("<p>订单号: 190218110152153372872</p>", $order_num);
    $html .= sprintf("<p>商品名称: %s</p>", $order_name);
    $html .= sprintf("<p>付款金额: %s</p>", $order_price);
    $html .= sprintf("<p>支付方式: %s</p>", $pay_type);
    $html .= sprintf("<p>付款时间: %s</p>", date("Y-m-d H:i:s"));
    $html .= sprintf("<p>查看或下载地址： %s</p>", $a_href);
    $html .= '</div>';
    return $html;
}

// 当前会员类型
// return 0 31 365 3600
function vip_type($users_id = '')
{
    global $current_user;
    if (!is_user_logged_in()) {
        return 0;
    }
    $uid       = (!$users_id) ? $current_user->ID : $users_id;
    $vip_type  = get_user_meta($uid, 'vip_type', true);
    $vip_time  = get_user_meta($uid, 'vip_time', true);
    $timestamp = intval($vip_time) - time();
    if ($timestamp > 0) {
        return intval($vip_type);
    } else {
        return 0;
    }

}

// 当前会员名称
// return 0 31 365 3600
function vip_type_name($users_id = '')
{
    global $current_user;
    if (!is_user_logged_in()) {
        return 0;
    }
    $uid      = (!$users_id) ? $current_user->ID : $users_id;
    $vip_type = get_user_meta($uid, 'vip_type', true);
    if (!$vip_type) {
        return '普通用户';
    }
    $vip_time  = get_user_meta($uid, 'vip_time', true);
    $timestamp = intval($vip_time) - time();
    if ($timestamp > 0) {
        if (intval($vip_type) == 31) {
            $name = '月费会员';
        } elseif (intval($vip_type) == 365) {
            $name = '年费会员';
        } elseif (intval($vip_type) == 3600) {
            $name = '终身会员';
        }
    } else {
        $name = '普通用户';
    }
    return $name;
}

//当前会员到期时间
//return 时间戳
function vip_time($users_id = '')
{
    global $current_user;
    if (!is_user_logged_in()) {
        return date('Y-m-d H:i:s', time());
    }
    $uid      = (!$users_id) ? $current_user->ID : $users_id;
    $vip_time = get_user_meta($uid, 'vip_time', true);
    if ($vip_time > time()) {
        return date('Y-m-d H:i:s', intval($vip_time));
    } else {
        return date('Y-m-d H:i:s', time());
    }

}

/**
 * 获取今天的开始和结束时间
 * @return mixed
 */
function getTime()
{
    $str          = date("Y-m-d", time()) . "0:0:0";
    $data["star"] = strtotime($str);
    $str          = date("Y-m-d", time()) . "24:00:00";
    $data["end"]  = strtotime($str);
    return $data;
}

//当前会员下载次数限制
//return nambers vip_price_31_downum
function this_vip_downum($users_id = '')
{
    global $current_user;
    if (!is_user_logged_in()) {
        return 0;
    }
    $uid = (!$users_id) ? $current_user->ID : $users_id;
    // 会员当前下载结束时间
    $this_vip_downend_time = (get_user_meta($uid, 'this_vip_downend_time', true) > 0) ? get_user_meta($uid, 'this_vip_downend_time', true) : 0;
    // 会员当前下载次数
    $this_vip_downum = (get_user_meta($uid, 'this_vip_downum', true) > 0) ? get_user_meta($uid, 'this_vip_downum', true) : 0;
    // 自动更新下载时间
    $getTime  = getTime();
    $thenTime = time();
    // 获取用户结束时间
    
    // 当用时间为0 时候 初始化时间为今天开始时间 OR 当前时间大于结束时间 刷新新时间
    if ($this_vip_downend_time = 0 || intval($thenTime) > intval($this_vip_downend_time)) {
        update_user_meta($uid, 'this_vip_downend_time', $getTime['end']); //更新用户本次到期时间
        update_user_meta($uid, 'this_vip_downum', 0); //更新用户本次到期时间
    }

    $this_vip_type = vip_type($uid);
    $vip_options = _hui('vip_options');
    if (intval($this_vip_type) == 31) {
        $over_down_num = intval($vip_options['vip_price_31_downum']) - intval($this_vip_downum);
    } elseif (intval($this_vip_type) == 365) {
        $over_down_num = intval($vip_options['vip_price_365_downum']) - intval($this_vip_downum);
    } elseif (intval($this_vip_type) == 3600) {
        $over_down_num = intval($vip_options['vip_price_3600_downum']) - intval($this_vip_downum);
    } else {
        $over_down_num = 0;
    }

    $is_down = ($over_down_num > 0) ? true : false;

    $data = array(
        'is_down'           => $is_down, //是否可以下载
        'today_down_num'    => $this_vip_downum, //当前已下载次数
        'over_down_num'     => $over_down_num, //剩余下载次数
        'over_down_endtime' => $getTime['end'], // 下次下载次数更新时间
    );

    return $data;
    // var_dump(this_vip_downum());
}

// 下载地址加密
function downursad()
{

}

function rizhuti_lock_url($txt, $key)
{
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
    //$nh = rand(0,64);
    $nh    = 23;
    $ch    = $chars[$nh];
    $mdKey = md5($key . $ch);
    $mdKey = substr($mdKey, $nh % 8, $nh % 8 + 7);
    $txt   = base64_encode($txt);
    $tmp   = '';
    $i     = 0;
    $j     = 0;
    $k     = 0;
    for ($i = 0; $i < strlen($txt); $i++) {
        $k = $k == strlen($mdKey) ? 0 : $k;
        $j = ($nh + strpos($chars, $txt[$i]) + ord($mdKey[$k++])) % 64;
        $tmp .= $chars[$j];
    }
    return urlencode($ch . $tmp);
}

function rizhuti_unlock_url($txt, $key)
{
    $txt   = urldecode($txt);
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
    $ch    = $txt[0];
    $nh    = strpos($chars, $ch);
    $mdKey = md5($key . $ch);
    $mdKey = substr($mdKey, $nh % 8, $nh % 8 + 7);
    $txt   = substr($txt, 1);
    $tmp   = '';
    $i     = 0;
    $j     = 0;
    $k     = 0;
    for ($i = 0; $i < strlen($txt); $i++) {
        $k = $k == strlen($mdKey) ? 0 : $k;
        $j = strpos($chars, $txt[$i]) - $nh - ord($mdKey[$k++]);
        while ($j < 0) {
            $j += 64;
        }

        $tmp .= $chars[$j];
    }
    return base64_decode($tmp);
}

class enstr
{
    public function enstrhex($str, $key)
    {
        if (version_compare(phpversion(), '7.1.0') >= 0) {
            $data = openssl_encrypt($str, 'AES-256-ECB', $key);
            return $data;
        } else {
            $td     = mcrypt_module_open('twofish', '', 'ecb', '');
            $iv     = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
            $ks     = mcrypt_enc_get_key_size($td);
            $keystr = substr(md5($key), 0, $ks);
            mcrypt_generic_init($td, $keystr, $iv);
            $encrypted = mcrypt_generic($td, $str);
            mcrypt_module_close($td);
            $hexdata = bin2hex($encrypted);
            return $hexdata;
        }
        return $str;
    }

    public function destrhex($str, $key)
    {
        if (version_compare(phpversion(), '7.1.0') >= 0) {
            $decrypted = openssl_decrypt($str, 'AES-256-ECB', $key);
            return $decrypted;
        } else {
            $td     = mcrypt_module_open('twofish', '', 'ecb', '');
            $iv     = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
            $ks     = mcrypt_enc_get_key_size($td);
            $keystr = substr(md5($key), 0, $ks);
            mcrypt_generic_init($td, $keystr, $iv);
            $encrypted = pack("H*", $str);
            $decrypted = mdecrypt_generic($td, $encrypted);
            mcrypt_generic_deinit($td);
            mcrypt_module_close($td);
            return $decrypted;
        }
        return $str;
    }
}

function rizhuti_download_file($file_dir)
{
    if (substr($file_dir, 0, 7) == 'http://' || substr($file_dir, 0, 8) == 'https://' || substr($file_dir, 0, 10) == 'thunder://' || substr($file_dir, 0, 7) == 'magnet:' || substr($file_dir, 0, 5) == 'ed2k:') {
        $file_path = chop($file_dir);
        echo "<script type='text/javascript'>window.location='$file_path';</script>";
        exit;
    }
    $file_dir = chop($file_dir);
    if (!file_exists($file_dir)) {
        return false;
    }
    $temp = explode("/", $file_dir);

    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"" . end($temp) . "\"");
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: " . filesize($file_dir));
    ob_end_flush();
    @readfile($file_dir);
}
