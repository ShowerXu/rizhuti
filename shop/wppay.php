<?php

define('WPAY_VERSION', '4.0.0');
define('WPAY_URL', get_stylesheet_directory_uri().'/shop');
define('WPAY_PATH', dirname( __FILE__ ));
define('WPAY_ADMIN_URL', admin_url());

/**
 * 定义数据库wp_wppay_order
 */
global $wpdb, $wppay_table_name;
$wppay_table_name = isset($table_prefix) ? ($table_prefix . 'wppay_order') : ($wpdb->prefix . 'wppay_order');

/**
 * 加载类
 */
require WPAY_PATH . '/include/wppay.class.php';
require WPAY_PATH . '/include/wppay.functions.php';
require WPAY_PATH . '/include/wppay.metabox.php';
require WPAY_PATH . '/include/qr.class.php';

/**
 * if ( isset($_GET['activated']) ){,新建数据库
 */
// if ( isset($_GET['activated']) ){
// 	wppay_install();
// }
