<?php



// theme start
if (!function_exists('rizhuti_setup')):

    function rizhuti_setup()
    {

        add_theme_support('automatic-feed-links');

        add_theme_support('title-tag');

        add_theme_support('post-thumbnails');

        add_image_size('480-384.7', 480, 384, true);

        // 检测是否有qqid字段，没有添加，为qq登陆提供基础
        global $wpdb, $wppay_table_name;
        $var = $wpdb->query("SELECT qqid FROM $wpdb->users");
        if (!$var) {
            $wpdb->query("ALTER TABLE $wpdb->users ADD qqid varchar(100)");
        }
        // 插入订单表
        if ($wpdb->get_var("show tables like '{$wppay_table_name}'") != $wppay_table_name) {
            $wpdb->query("CREATE TABLE `" . $wpdb->prefix . "wppay_order` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `user_id` int(11) DEFAULT NULL COMMENT '用户id',
              `post_id` int(11) DEFAULT NULL COMMENT '关联文章id',
              `order_num` varchar(50) DEFAULT NULL COMMENT '本地订单号',
              `order_price` double(10,2) DEFAULT NULL COMMENT '订单价格',
              `order_type` tinyint(3) DEFAULT '0' COMMENT '订单类型；0为止；1文章；2会员',
              `pay_type` tinyint(3) DEFAULT '0' COMMENT '支付类型；0无；1支付宝；2微信',
              `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
              `pay_time` int(10) DEFAULT NULL COMMENT '支付时间',
              `pay_num` varchar(50) DEFAULT NULL COMMENT '支付订单号',
              `status` tinyint(3) DEFAULT '0' COMMENT '状态；0 未支付；1已支付；2失效',
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=" . DB_CHARSET . " COMMENT='订单数据表';");
        }

        update_option('thumbnail_crop', 1);

        // CREATE PAGES
        $init_pages = array(
            'pages/posts-likes.php' => array('点赞排行', 'likes'),
            'pages/tags.php'        => array('热门标签', 'tags'),
            'pages/sitemap.php'     => array('网站地图', 'sitemap'),
            'pages/login.php'       => array('登录注册', 'login'),
            'pages/user.php'        => array('用户中心', 'user'),
        );

        foreach ($init_pages as $template => $item) {

            $one_page = array(
                'post_title'  => $item[0],
                'post_name'   => $item[1],
                'post_status' => 'publish',
                'post_type'   => 'page',
                'post_author' => 1,
            );

            $one_page_check = get_page_by_title($item[0]);

            if (!isset($one_page_check->ID)) {
                $one_page_id = wp_insert_post($one_page);
                update_post_meta($one_page_id, '_wp_page_template', $template);
            }

        }
    }
endif;
add_action('after_setup_theme', 'rizhuti_setup');


/**
 * Functions which require the theme options.
 */
require get_template_directory() . '/inc/codestar-framework/codestar-framework.php';
require get_template_directory() . '/inc/options.theme.php';



/**
 *
 * Get option
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('_hui')) {
    function _hui($option_name = '', $default = '')
    {

        $options = apply_filters('_hui', get_option(CS_OPTION), $option_name, $default);

        if (!empty($option_name) && !empty($options[$option_name])) {
            return $options[$option_name];
        } else {
            return (!empty($default)) ? $default : null;
        }

    }
}

if (!function_exists('_hui_img')) {
    function _hui_img($option_name = '', $default = '')
    {

        $options = apply_filters('_hui_img', get_option(CS_OPTION), $option_name, $default);

        if (!empty($option_name) && !empty($options[$option_name])) {
            return $options[$option_name]['url'];
        } else {
            return (!empty($default)) ? $default : null;
        }

    }
}

/* 调试模式选项保存为全局变量 */
if (_hui('display_errors')) {
    ini_set("display_errors", "On");
    error_reporting(E_ALL);
} else {
    ini_set("display_errors", "Off");
}

// 禁用更新
if (_hui('display_wp_update')) {
    add_filter('pre_site_transient_update_core',    create_function('$a', "return null;")); // 关闭核心提示

    add_filter('pre_site_transient_update_plugins', create_function('$a', "return null;")); // 关闭插件提示

    add_filter('pre_site_transient_update_themes',  create_function('$a', "return null;")); // 关闭主题提示

    remove_action('admin_init', '_maybe_update_core');    // 禁止 WordPress 检查更新

    remove_action('admin_init', '_maybe_update_plugins'); // 禁止 WordPress 更新插件

    remove_action('admin_init', '_maybe_update_themes');  // 禁止 WordPress 更新主题
}

// 禁用难用的Gutenberg（古腾堡） 编辑器
if (_hui('disabled_block_editor')) {
    add_filter('use_block_editor_for_post', '__return_false');
    remove_action('wp_enqueue_scripts', 'wp_common_block_scripts_and_styles');
}
/**
 * 禁止后台加载谷歌字体
 */
if (_hui('disabled_open_sans')) {

    function wp_remove_open_sans_from_wp_core()
    {
        wp_deregister_style('open-sans');
        wp_register_style('open-sans', false);
        wp_enqueue_style('open-sans', '');
    }
    add_action('init', 'wp_remove_open_sans_from_wp_core');

}
/**
 * 清除wordpress自带的meta标签
 */
if (_hui('disabled_of_theme_meta')) {

    function ashuwp_clean_theme_meta()
    {
        remove_action('wp_head', 'print_emoji_detection_script', 7, 1);
        remove_action('wp_print_styles', 'print_emoji_styles', 10, 1);
        remove_action('wp_head', 'rsd_link', 10, 1);
        remove_action('wp_head', 'wp_generator', 10, 1);
        remove_action('wp_head', 'feed_links', 2, 1);
        remove_action('wp_head', 'feed_links_extra', 3, 1);
        remove_action('wp_head', 'index_rel_link', 10, 1);
        remove_action('wp_head', 'wlwmanifest_link', 10, 1);
        remove_action('wp_head', 'start_post_rel_link', 10, 1);
        remove_action('wp_head', 'parent_post_rel_link', 10, 0);
        remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
        remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
        remove_action('wp_head', 'rest_output_link_wp_head', 10, 0);
        remove_action('wp_head', 'wp_oembed_add_discovery_links', 10, 1);
        remove_action('wp_head', 'rel_canonical', 10, 0);
    }
    add_action('after_setup_theme', 'ashuwp_clean_theme_meta'); //清除wp_head带入的meta标签
}
/**
 * 防pingback攻击
 */
if (_hui('disabled_pingback_ping')) {

    add_filter('xmlrpc_methods', 'remove_xmlrpc_pingback_ping');
    function remove_xmlrpc_pingback_ping($methods)
    {
        unset($methods['pingback.ping']);
        return $methods;
    };

}

/**
 * SSL Gravatar
 */
if (_hui('_get_ssl2_avatar')) {
    function _get_ssl2_avatar($avatar)
    {
        $avatar = preg_replace('/.*\/avatar\/(.*)\?s=([\d]+)&.*/', '<img src="https://secure.gravatar.com/avatar/$1?s=$2&d=mm" class="avatar avatar-$2" height="50" width="50">', $avatar);
        return $avatar;
    }
    add_filter('get_avatar', '_get_ssl2_avatar');
}

/**
 * WordPress Emoji Delete
 */
if (_hui('disabled_emoji')) {

    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

}

// JPEG QUALITY
if (_hui('_jpeg_quality')) {

    function _jpeg_quality($arg)
    {
        return 100;
    }

    add_filter('jpeg_quality', '_jpeg_quality', 10);
}

// EDITOR STYLE
add_editor_style(get_stylesheet_directory_uri() . '/editor-style.css');

if (!function_exists('_add_editor_buttons')):

    function _add_editor_buttons($buttons)
{
        $buttons[] = 'fontselect';
        $buttons[] = 'fontsizeselect';
        $buttons[] = 'cleanup';
        $buttons[] = 'styleselect';
        $buttons[] = 'del';
        $buttons[] = 'sub';
        $buttons[] = 'sup';
        $buttons[] = 'copy';
        $buttons[] = 'paste';
        $buttons[] = 'cut';
        $buttons[] = 'image';
        $buttons[] = 'anchor';
        $buttons[] = 'backcolor';
        $buttons[] = 'wp_page';
        $buttons[] = 'charmap';
        return $buttons;
    }
    add_filter("mce_buttons", "_add_editor_buttons");

endif;


// MD5 上传文件重命名
if (_hui('_new_filename')) {
    function _new_filename($filename)
    {
        $info = pathinfo($filename);
        $ext  = empty($info['extension']) ? '' : '.' . $info['extension'];
        $name = basename($filename, $ext);
        return substr(md5($name), 0, 15) . $ext;
    }
    add_filter('sanitize_file_name', '_new_filename', 10);
}

// Hide Admin Bar
if (_hui('hide_admin_bar')) {
    add_filter('show_admin_bar', 'hide_admin_bar');
    function hide_admin_bar($flag)
    {
        return false;
    }

}

// COMMENT Ctrl+Enter
if (!function_exists('_admin_comment_ctrlenter')):

    function _admin_comment_ctrlenter()
{
        echo '<script type="text/javascript">
                jQuery(document).ready(function($){
                    $("textarea").keypress(function(e){
                        if(e.ctrlKey&&e.which==13||e.which==10){
                            $("#replybtn").click();
                        }
                    });
                });
            </script>';
    };
    add_action('admin_footer', '_admin_comment_ctrlenter');

endif;

// //管理后台添加按钮
// function custom_adminbar_menu($meta = true)
// {
//     global $wp_admin_bar;
//     if (!is_user_logged_in()) {
//         return;
//     }
//     if (!is_super_admin() || !is_admin_bar_showing()) {
//         return;
//     }
//     $wp_admin_bar->add_menu(array(
//         'id'    => 'themeset',
//         'title' => '日主题设置-Pro', /* 设置链接名 */
//         'href' => admin_url("themes.php?page=options-framework"),
//     ));
// }
// add_action('admin_bar_menu', 'custom_adminbar_menu', 100);

// $postmeta_keywords_description = array(
//     array(
//         "name"  => "keywords",
//         "std"   => "",
//         "title" => '关键字：',
//     ),
//     array(
//         "name"  => "description",
//         "std"   => "",
//         "title" => '描述：',
//     ),
// );
// if (_hui('post_keywords_description_s')) {
//     add_action('admin_menu', '_postmeta_keywords_description_create');
//     add_action('save_post', '_postmeta_keywords_description_save');
// }

// function _postmeta_keywords_description()
// {
//     global $post, $postmeta_keywords_description;
//     foreach ($postmeta_keywords_description as $meta_box) {
//         $meta_box_value = get_post_meta($post->ID, $meta_box['name'], true);
//         if ($meta_box_value == "") {
//             $meta_box_value = $meta_box['std'];
//         }

//         echo '<p>' . $meta_box['title'] . '</p>';
//         if ($meta_box['name'] == 'keywords') {
//             echo '<p><input type="text" style="width:98%" value="' . $meta_box_value . '" name="' . $meta_box['name'] . '"></p>';
//         } else {
//             echo '<p><textarea style="width:98%" name="' . $meta_box['name'] . '">' . $meta_box_value . '</textarea></p>';
//         }
//     }

//     echo '<input type="hidden" name="post_newmetaboxes_noncename" id="post_newmetaboxes_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
// }

// function _postmeta_keywords_description_create()
// {
//     global $theme_name;
//     if (function_exists('add_meta_box')) {
//         add_meta_box('postmeta_keywords_description_boxes', __('自定义关键字和描述', 'haoui'), '_postmeta_keywords_description', 'post', 'normal', 'high');
//         add_meta_box('postmeta_keywords_description_boxes', __('自定义关键字和描述', 'haoui'), '_postmeta_keywords_description', 'page', 'normal', 'high');
//     }
// }

// function _postmeta_keywords_description_save($post_id)
// {
//     global $postmeta_keywords_description;

//     if (!wp_verify_nonce(isset($_POST['post_newmetaboxes_noncename']) ? $_POST['post_newmetaboxes_noncename'] : '', plugin_basename(__FILE__))) {
//         return;
//     }

//     if (!current_user_can('edit_posts', $post_id)) {
//         return;
//     }

//     foreach ($postmeta_keywords_description as $meta_box) {
//         $data = $_POST[$meta_box['name']];
//         if (get_post_meta($post_id, $meta_box['name']) == "") {
//             add_post_meta($post_id, $meta_box['name'], $data, true);
//         } elseif ($data != get_post_meta($post_id, $meta_box['name'], true)) {
//             update_post_meta($post_id, $meta_box['name'], $data);
//         } elseif ($data == "") {
//             delete_post_meta($post_id, $meta_box['name'], get_post_meta($post_id, $meta_box['name'], true));
//         }

//     }
// }

// $postmeta_from = array(
//     array(
//         "name"  => "fromname_value",
//         "std"   => "",
//         "title" => __('来源名', 'haoui') . '：',
//     ),
//     array(
//         "name"  => "fromurl_value",
//         "std"   => "",
//         "title" => __('来源网址', 'haoui') . '：',
//     ),
// );

// if (_hui('post_from_s')) {
//     add_action('admin_menu', '_postmeta_from_create');
//     add_action('save_post', '_postmeta_from_save');
// }

// function _postmeta_from()
// {
//     global $post, $postmeta_from;
//     foreach ($postmeta_from as $meta_box) {
//         $meta_box_value = get_post_meta($post->ID, $meta_box['name'], true);
//         if ($meta_box_value == "") {
//             $meta_box_value = $meta_box['std'];
//         }

//         echo '<p>' . $meta_box['title'] . '</p>';

//         echo '<p><input type="text" style="width:98%" value="' . $meta_box_value . '" name="' . $meta_box['name'] . '"></p>';

//     }

//     echo '<input type="hidden" name="post_newmetaboxes_noncename" id="post_newmetaboxes_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
// }

// function _postmeta_from_create()
// {
//     global $theme_name;
//     if (function_exists('add_meta_box')) {
//         add_meta_box('postmeta_from_boxes', __('来源', 'haoui'), '_postmeta_from', 'post', 'normal', 'high');
//     }
// }

// function _postmeta_from_save($post_id)
// {
//     global $postmeta_from;

//     if (!wp_verify_nonce(isset($_POST['post_newmetaboxes_noncename']) ? $_POST['post_newmetaboxes_noncename'] : '', plugin_basename(__FILE__))) {
//         return;
//     }

//     if (!current_user_can('edit_posts', $post_id)) {
//         return;
//     }

//     foreach ($postmeta_from as $meta_box) {
//         $data = $_POST[$meta_box['name']];
//         if (get_post_meta($post_id, $meta_box['name']) == "") {
//             add_post_meta($post_id, $meta_box['name'], $data, true);
//         } elseif ($data != get_post_meta($post_id, $meta_box['name'], true)) {
//             update_post_meta($post_id, $meta_box['name'], $data);
//         } elseif ($data == "") {
//             delete_post_meta($post_id, $meta_box['name'], get_post_meta($post_id, $meta_box['name'], true));
//         }

//     }
// }
