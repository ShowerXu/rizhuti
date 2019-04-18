<?php
if ( !defined('ABSPATH') ) {exit;}
// 添加设置菜单项
add_action('admin_menu', 'wppay_menu');
function wppay_menu() {
	add_menu_page('WPPAY', '商城', 'activate_plugins', 'wppay_orders_page', 'wppay_orders_page','dashicons-shield');
	add_submenu_page('wppay_orders_page', '订单', '订单', 'activate_plugins', 'wppay_orders_page','wppay_orders_page');
	add_submenu_page('wppay_orders_page', '会员', '会员', 'activate_plugins', 'wppay_vip_page','wppay_vip_page');
	add_action( 'admin_init', 'wppay_setting_group');
}

function wppay_setting_group() {
	register_setting( 'wppay_setting_group', 'wppay_setting' );
}	


// 插件订单页面
function wppay_orders_page(){
    @include WPAY_PATH.'/admin/orders.php';
}

// 插件会员页面
function wppay_vip_page(){
    @include WPAY_PATH.'/admin/vip.php';
}

// 卸载删除数据表
function wppay_uninstall(){
	global $wpdb, $wppay_table_name;
	$wpdb->query("DROP TABLE IF EXISTS {$wppay_table_name}");
}

// QRcode
function getQrcode($url){
ob_start();
$errorCorrectionLevel = 'L';//容错级别 
$matrixPointSize = 6;//生成图片大小 
QRcode::png($url, false , $errorCorrectionLevel, $matrixPointSize, 2);
$data =ob_get_contents();
ob_end_clean();
return "data:image/jpeg;base64,".base64_encode($data);
}


// 插件在前台显示的脚本 定义wppay_ajax_url的地址
function wppay_scripts(){
	wp_enqueue_style( 'wppay', wppay_css_url('pay'), array(), WPAY_VERSION );
	wp_enqueue_script('jquery');
	
	if (_hui('weixinpay') || _hui('alpay')) {
		$this_js = 'qy-pay'; 
	}elseif (_hui('is_mianqian_skb') && !_hui('is_mianqian_mzf')) {
		$this_js = 'skb-pay'; 
	}elseif (_hui('is_mianqian_mzf') && !_hui('is_mianqian_skb')) {
		$this_js = 'mzf-pay';
	}else{
		$this_js = 'qy-pay'; 
	}
	wp_enqueue_script( 'wppay',  wppay_js_url($this_js), false, '', true, WPAY_VERSION );
    wp_localize_script( 'wppay', 'wppay_ajax_url', WPAY_ADMIN_URL . "admin-ajax.php");
}
add_action('wp_enqueue_scripts', 'wppay_scripts', 20, 1);





// 获取支付支付宝二维码返回的ajax参数  action 为 alipay
function alipay_callback(){
	date_default_timezone_set('Asia/Shanghai');
	header('Content-type:application/json; Charset=utf-8');
	global $wpdb, $wppay_table_name;
	$post_id = $_POST['post_id'];
	$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
	$code='';$link='';$msg='';$num='';$status=400;
	$out_trade_no = date("ymdhis").mt_rand(100,999).mt_rand(100,999).mt_rand(100,999);
	$order_type = $_POST['order_type'];
	$vip_options = _hui('vip_options');
	if ($order_type == 1) {
		// 文章价格
		$price = get_post_meta($post_id,'wppay_price',true);
	}else if ($order_type == 2) {
		// 月会员价格
		$price = $vip_options['vip_price_31'];
	}else if ($order_type == 3) {
		// 年费会员价格
		$price = $vip_options['vip_price_365'];
	}else if ($order_type == 4) {
		// 终身会员价格
		$price = $vip_options['vip_price_3600'];
	}else{
		$price = 0;
	}
	///////////////////////////////////////////
	$appid = _hui('alipay_appid');  
	$notifyUrl = get_stylesheet_directory_uri() . '/shop/payment/alipay/notify.php';     //付款成功后的异步回调地址
	$outTradeNo = $out_trade_no;     //你自己的商品订单号，不能重复
	$payAmount = $price;          //付款金额，单位:元
	$orderName = get_bloginfo('name').'付费资源';    //订单标题
	$signType = 'RSA2';			//签名算法类型，支持RSA2和RSA，推荐使用RSA2
	$rsaPrivateKey= _hui('alipay_privatekey');	
	/*** 配置结束 ***/

	$aliPay = new AlipayService();
	$aliPay->setAppid($appid);
	$aliPay->setNotifyUrl($notifyUrl);
	$aliPay->setRsaPrivateKey($rsaPrivateKey);
	$aliPay->setTotalFee($payAmount);
	$aliPay->setOutTradeNo($outTradeNo);
	$aliPay->setOrderName($orderName);
	/////////////////////////////////////////////////

	if($appid){

		$wppay = new WPAY($post_id, $user_id, $order_type);
		// 写入订单到本地数据库
		$pay_type = 1; //定义支付方式为支付宝
		if($wppay->add($out_trade_no, $price ,$order_type,$pay_type)){
			
			$result = $aliPay->doPay();

			$result = $result['alipay_trade_precreate_response'];
			if($result['code'] && $result['code']=='10000'){
			    //生成二维码
			    $url = getQrcode($result['qr_code']);
			    $msg =	'二维码内容：'.$result['qr_code'];

			    $result_json = array(
					'status' => '200',
					'price' =>$payAmount,
					'qr' => $url,
					'num' => $outTradeNo,
					'msg' => $msg
				);


			}else{
				$result_json = array(
					'status' => '201',
					'price' =>$payAmount,
					'qr' => '',
					'num' => $outTradeNo,
					'msg' => $result['msg'].' : '.$result['sub_msg']
				);
			}
		}else{
			$result_json = array(
				'status' => '202',
				'price' =>$payAmount,
				'qr' => '',
				'num' => $outTradeNo,
				'msg' => '订单创建失败，请刷新重试！'
			);
		}
	}else{
		$result_json = array(
			'status' => '203',
			'price' =>$payAmount,
			'qr' => '',
			'num' => $outTradeNo,
			'msg' => '支付接口信息未正确配置！'
		);
	}

	echo json_encode($result_json);
	exit;
}
add_action( 'wp_ajax_alipay', 'alipay_callback');
add_action( 'wp_ajax_nopriv_alipay', 'alipay_callback');

// 获取微信支付二维码返回的ajax参数  action 为 weixinpay
function weixinpay_callback(){
	date_default_timezone_set('Asia/Shanghai');
	header('Content-type:application/json; Charset=utf-8');
	global $wpdb, $wppay_table_name;

	$post_id = $_POST['post_id'];
	$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
	$code='';$link='';$msg='';$num='';$status=400;
	$out_trade_no = date("ymdhis").mt_rand(100,999).mt_rand(100,999).mt_rand(100,999);
	$order_type = $_POST['order_type'];
	$vip_options = _hui('vip_options');
	if ($order_type == 1) {
		// 文章价格
		$price = get_post_meta($post_id,'wppay_price',true);
	}else if ($order_type == 2) {
		// 月会员价格
		$price = $vip_options['vip_price_31'];
	}else if ($order_type == 3) {
		// 年费会员价格
		$price = $vip_options['vip_price_365'];
	}else if ($order_type == 4) {
		// 终身会员价格
		$price = $vip_options['vip_price_3600'];
	}else{
		$price = 0;
	}
	///////////////////////////////////////////
	$mchid = _hui('weixinpay_mchid');          //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
	$appid = _hui('weixinpay_appid');  //公众号APPID 通过微信支付商户资料审核后邮件发送
	$apiKey = _hui('weixinpay_apikey');   //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
	$wxPay = new WxpayService($mchid,$appid,$apiKey);
	$outTradeNo = date("ymdhis").mt_rand(100,999).mt_rand(100,999).mt_rand(100,999);     //你自己的商品订单号
	$payAmount = $price;        //付款金额，单位:元
	$orderName = get_bloginfo('name').'付费资源';    //订单标题 
	$notifyUrl = get_stylesheet_directory_uri() . '/shop/payment/weixin/notify.php';     //付款成功后的回调地址(不要有问号)
	$payTime = time();      //付款时间
	/*** 配置结束 ***/
	/////////////////////////////////////////////////

	if($mchid){

		$wppay = new WPAY($post_id, $user_id, $order_type);
		// 写入订单到本地数据库
		$pay_type = 2; //定义支付方式为微信支付
		if($wppay->add($out_trade_no, $price ,$order_type,$pay_type)){
			
			$result = $wxPay->createJsBizPackage($payAmount,$out_trade_no,$orderName,$notifyUrl,$payTime);  //发起微信支付

			if($result['code_url']){
			    //生成二维码
			    $url = getQrcode($result['code_url']); 
			    $msg =	'二维码内容：'.$result['qr_code'];

			    $result_json = array(
					'status' => '200',
					'price' =>$payAmount,
					'qr' => $url,
					'num' => $out_trade_no,
					'msg' => $msg
				);


			}else{
				$result_json = array(
					'status' => '201',
					'price' =>$payAmount,
					'qr' => '',
					'num' => $out_trade_no,
					'msg' => '二维码生成失败，请刷新重试！'
				);
			}
		}else{
			$result_json = array(
				'status' => '202',
				'price' =>$payAmount,
				'qr' => '',
				'num' => $out_trade_no,
				'msg' => '订单创建失败，请刷新重试！'
			);
		}
	}else{
		$result_json = array(
			'status' => '203',
			'price' =>$payAmount,
			'qr' => '',
			'num' => $out_trade_no,
			'msg' => '支付接口信息未正确配置！'
		);
	}

	echo json_encode($result_json);
	exit;
}
add_action( 'wp_ajax_weixinpay', 'weixinpay_callback');
add_action( 'wp_ajax_nopriv_weixinpay', 'weixinpay_callback');





// 免签约函数封装  zlkbcodepay

if (_hui('is_mianqian_skb')) {
	@include WPAY_PATH.'/payment/zlkbcodepay/zlkbcodepay.class.php';
}

// 免签约函数封装 支付宝支付
function skbalipay_callback(){
	date_default_timezone_set('Asia/Shanghai');
	header('Content-type:application/json; Charset=utf-8');
	global $wpdb, $wppay_table_name;
	$pay_type = 3; //收款宝免签支付宝方式
	$post_id = $_POST['post_id'];
	$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
	$code='';$link='';$msg='';$num='';$status=400;
	$out_trade_no = date("ymdhis").mt_rand(100,999).mt_rand(100,999).mt_rand(100,999);
	$order_type = $_POST['order_type'];
	$vip_options = _hui('vip_options');
	if ($order_type == 1) {
		// 文章价格
		$price = get_post_meta($post_id,'wppay_price',true);
	}else if ($order_type == 2) {
		// 月会员价格
		$price = $vip_options['vip_price_31'];
	}else if ($order_type == 3) {
		// 年费会员价格
		$price = $vip_options['vip_price_365'];
	}else if ($order_type == 4) {
		// 终身会员价格
		$price = $vip_options['vip_price_3600'];
	}else{
		$price = 0;
	}
	///////////////////////////////////////////
	$paymethod = 2; //支付宝
	$zlkb_appid  = _hui('zlkb_appid'); //appid
	$zlkb_secret = _hui('zlkb_secret'); //secret
	//构造需要传递的参数
	$params = array(
	     "app_id"         => $zlkb_appid, //app_id
	     "app_secret"         => $zlkb_secret, //secret
	     "overtime"         => 220, //超时时间
	     "orderid"         => $out_trade_no, //订单号
	     "subject"         => get_bloginfo('name').'付费资源',
	     "money"         => $price, //金额,
	     "returnurl"         => get_stylesheet_directory_uri() . '/shop/payment/zlkbcodepay/notify.php',
	     "notifyurl"         => get_stylesheet_directory_uri() . '/shop/payment/zlkbcodepay/notify.php',
	);
	if($zlkb_appid && $zlkb_secret){
		$wppay = new WPAY($post_id, $user_id, $order_type);
		if ($wppay->add($out_trade_no, $price ,$order_type,$pay_type)) {
			$link = new zlkbcodepay_link();
			$goPay  = $link->get_paylink($params,$paymethod); 
			// var_dump($goPay);die;
			// 创建订单成功
			if ($goPay['code'] == 1) {
				$payDeta = $goPay['data']; 
				//生成二维码
			    $url = getQrcode($payDeta['qr_content']); 
			    $msg =	'二维码内容：'.$payDeta['qr_content'];

			    $result_json = array(
					'status' => '200',
					'price' =>$payDeta['money'],
					'qr' => $url,
					'num' => $payDeta['ordersn'],
					'msg' => $msg
				);
			}
		}else{
			$result_json = array(
				'status' => '202',
				'price' =>$price,
				'qr' => '',
				'num' => $out_trade_no,
				'msg' => '订单创建失败，请刷新重试！'
			);
		}
	}else{
		$result_json = array(
			'status' => '203',
			'price' =>$price,
			'qr' => '',
			'num' => $outTradeNo,
			'msg' => '免签约支付接口信息未正确配置！'
		);
	}
	
	echo json_encode($result_json);
	exit;
	
}
add_action( 'wp_ajax_skbalipay', 'skbalipay_callback');
add_action( 'wp_ajax_nopriv_skbalipay', 'skbalipay_callback');

// 免签约函数封装 微信支付
function skbweixinpay_callback(){
	date_default_timezone_set('Asia/Shanghai');
	header('Content-type:application/json; Charset=utf-8');
	global $wpdb, $wppay_table_name;
	$pay_type = 4; //收款宝免签weixin方式
	$post_id = $_POST['post_id'];
	$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
	$code='';$link='';$msg='';$num='';$status=400;
	$out_trade_no = date("ymdhis").mt_rand(100,999).mt_rand(100,999).mt_rand(100,999);
	$order_type = $_POST['order_type'];
	$vip_options = _hui('vip_options');
	if ($order_type == 1) {
		// 文章价格
		$price = get_post_meta($post_id,'wppay_price',true);
	}else if ($order_type == 2) {
		// 月会员价格
		$price = $vip_options['vip_price_31'];
	}else if ($order_type == 3) {
		// 年费会员价格
		$price = $vip_options['vip_price_365'];
	}else if ($order_type == 4) {
		// 终身会员价格
		$price = $vip_options['vip_price_3600'];
	}else{
		$price = 0;
	}
	///////////////////////////////////////////
	$paymethod = 1; //微信
	$zlkb_appid  = _hui('zlkb_appid'); //appid
	$zlkb_secret = _hui('zlkb_secret'); //secret
	//构造需要传递的参数
	$params = array(
	     "app_id"         => $zlkb_appid, //app_id
	     "app_secret"         => $zlkb_secret, //secret
	     "overtime"         => 220, //超时时间
	     "orderid"         => $out_trade_no, //订单号
	     "subject"         => get_bloginfo('name').'付费资源',
	     "money"         => $price, //金额,
	     "returnurl"         => get_stylesheet_directory_uri() . '/shop/payment/zlkbcodepay/notify.php',
	     "notifyurl"         => get_stylesheet_directory_uri() . '/shop/payment/zlkbcodepay/notify.php',
	);

	if($zlkb_appid && $zlkb_secret){
		$wppay = new WPAY($post_id, $user_id, $order_type);
		if ($wppay->add($out_trade_no, $price ,$order_type,$pay_type)) {
			$link = new zlkbcodepay_link();
			$goPay  = $link->get_paylink($params,$paymethod); 
			
			// 创建订单成功
			if ($goPay['code'] == 1) {
				$payDeta = $goPay['data']; 
				//生成二维码
			    $url = getQrcode($payDeta['qr_content']); 
			    $msg =	'二维码内容：'.$payDeta['qr_content'];
			    $result_json = array(
					'status' => '200',
					'price' =>$payDeta['money'],
					'qr' => $url,
					'num' => $payDeta['ordersn'],
					'msg' => $msg
				);
			}else{
				$result_json = array(
					'status' => '203',
					'price' =>$price,
					'qr' => $url,
					'num' => $out_trade_no,
					'msg' => '收款设备不存在/未绑定收款账户'
				);
			}
		}else{
			$result_json = array(
				'status' => '202',
				'price' =>$price,
				'qr' => $url,
				'num' => $out_trade_no,
				'msg' => '订单创建失败，请刷新重试！'
			);
		}
	}else{
		$result_json = array(
			'status' => '203',
			'price' =>$price,
			'qr' => $url,
			'num' => $outTradeNo,
			'msg' => '免签约支付接口信息未正确配置！'
		);
	}
	
	echo json_encode($result_json);
	exit;
	
}
add_action( 'wp_ajax_skbweixinpay', 'skbweixinpay_callback');
add_action( 'wp_ajax_nopriv_skbweixinpay', 'skbweixinpay_callback');


// 免签约函数封装 码支付
function mzfpay_callback(){
	date_default_timezone_set('Asia/Shanghai');
	header('Content-type:application/json; Charset=utf-8');
	global $wpdb, $wppay_table_name;
	$post_id = $_POST['post_id'];
	$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
	$code='';$link='';$msg='';$num='';$status=400;
	$out_trade_no = date("ymdhis").mt_rand(100,999).mt_rand(100,999).mt_rand(100,999);
	$order_type = $_POST['order_type'];
	$vip_options = _hui('vip_options');
	if ($order_type == 1) {
		// 文章价格
		$price = get_post_meta($post_id,'wppay_price',true);
	}else if ($order_type == 2) {
		// 月会员价格
		$price = $vip_options['vip_price_31'];
	}else if ($order_type == 3) {
		// 年费会员价格
		$price = $vip_options['vip_price_365'];
	}else if ($order_type == 4) {
		// 终身会员价格
		$price = $vip_options['vip_price_3600'];
	}else{
		$price = 0;
	}
	///////////////////////////////////////////
	$paymethod = intval($_POST['pay_type']);  //1支付宝支付 3微信支付 2QQ钱包

	$mzf_appid  = _hui('mzf_appid'); //appid
	$mzf_secret = _hui('mzf_secret'); //secret
	$mzf_token = _hui('mzf_token'); //secret

	$params = array(
	    "id" => $mzf_appid,//你的码支付ID
	    "token" => $mzf_token,//你的码支付ID
	    "pay_id" => $out_trade_no, //唯一标识
	    "type" => $paymethod,//1支付宝支付 3微信支付 2QQ钱包
	    "price" => $price,//金额
	    "param" => "rizhuti",//自定义参数
	    "notify_url"=>get_stylesheet_directory_uri() . '/shop/payment/codepay/notify.php',//通知地址
	); //构造需要传递的参数


	if($mzf_appid && $mzf_secret){
		$wppay = new WPAY($post_id, $user_id, $order_type);
		if ($wppay->add($out_trade_no, $price ,$order_type,$paymethod)) {
			// 请求支付数据
			// id=10041&token=888888&price=1&pay_id=admin&type=1&page=4
			$query = 'id='.$params['id'].'&token='.$params['token'].'&price='.$params['price'].'&pay_id='.$params['pay_id'].'&type='.$params['type'].'&notify_url='.$params['notify_url'].'&page=4'; //创建订单所需的参数
			//$urls = 'http://codepay.fateqq.com:52888/creat_order/?'.trim($query); //支付页面
         	 $urls = 'https://codepay.fateqq.com/creat_order/creat_order?'.trim($query); //支付页面
			$result = get_url_contents($urls);
			$resultData = json_decode($result,true);
			// var_dump($resultData);die;

			if ($resultData && $resultData['status'] == 0) {
				$result_json = array(
					'status' => '200',
					'price' =>$resultData['money'],
					'qr' => $resultData['qrcode'],
					'num' => $resultData['pay_id'],
					'msg' => '获取成功！'
				);
			}else{
				$result_json = array(
					'status' => '201',
					'price' =>$price,
					'qr' => '',
					'num' => $out_trade_no,
					'msg' => $resultData['msg']
				);
			}
			
		}else{
			$result_json = array(
				'status' => '202',
				'price' =>$price,
				'qr' => '',
				'num' => $out_trade_no,
				'msg' => '订单创建失败，请刷新重试！'
			);
		}
	}else{
		$result_json = array(
			'status' => '203',
			'price' =>$price,
			'qr' => '',
			'num' => $outTradeNo,
			'msg' => '免签约支付接口信息未正确配置！'
		);
	}
	
	echo json_encode($result_json);
	exit;
	
}
add_action( 'wp_ajax_mzfpay', 'mzfpay_callback');
add_action( 'wp_ajax_nopriv_mzfpay', 'mzfpay_callback');


// 检测是否支付订单  action 为 wppay_pay
function check_pay_callback(){
	$post_id = $_POST['post_id'];
	$order_num = $_POST['order_num'];
	$status = 0;
	$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
	$order_type = $_POST['order_type'];
	$wppay = new WPAY($post_id, $user_id, $order_type);
	if($wppay->check_paid($order_num)){
		$days = intval(_hui('pay_days'));
		$expire = time() + 2*24*60*60;
	    setcookie('wppay_'.$post_id, $wppay->set_key($order_num), $expire, '/', $_SERVER['HTTP_HOST'], false);
	    $status = 1;
	}

	$result = array(
		'status' => $status
	);

	header('Content-type: application/json');
	echo json_encode($result);
	exit;
}
add_action( 'wp_ajax_check_pay', 'check_pay_callback');
add_action( 'wp_ajax_nopriv_check_pay', 'check_pay_callback');


// 根据订单号删除订单 order_num
function del_order_callback(){
	global $wpdb, $wppay_table_name;
	$order_num = $_POST['order_num'];
	$status = 0;
	$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;

	if ($user_id) {
		$order=$wpdb->get_row("SELECT * FROM $wppay_table_name WHERE order_num=$order_num AND status=0 ");
		if (intval($order->user_id) == intval($user_id)) {
			$del = $wpdb->query("DELETE FROM $wppay_table_name WHERE order_num=$order->order_num AND status=0 ");
			$status = 1;
		}
	}

	$result = array(
		'status' => $status
	);

	header('Content-type: application/json');
	echo json_encode($result);
	exit;
}
add_action( 'wp_ajax_del_order', 'del_order_callback');
add_action( 'wp_ajax_nopriv_del_order', 'del_order_callback');



// 付费查看所有内容
add_action('the_content','wppay_content_show');
function wppay_content_show($content){
	global $post;
	$type = get_post_meta($post->ID,'wppay_type',true);
	$price = get_post_meta($post->ID,'wppay_price',true);
	$post_auth = get_post_meta($post->ID,'wppay_vip_auth',true);
	if($price && $type == '1'){
		if (intval($post_auth) == 1) {
        	$vip_infotext= '，月费会员可免费查看';
        }elseif (intval($post_auth) == 2) {
        	$vip_infotext= '，年费会员可免费查看';
        }elseif (intval($post_auth) == 3) {
        	$vip_infotext= '，终身会员可免费查看';
        }else{
        	$vip_infotext= '，请付费后查看';
        }

		$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
		$wppay = new WPAY($post->ID, $user_id);
		if($wppay->is_paid()){
			return $content;
		}else{
			if(is_singular()){
				if (!_hui('no_loginpay') && !is_user_logged_in()) {
					$wpayc = '<div class="erphp-wppay all"><p>此文章为付费文章'.$vip_infotext.'</p><a href="'.home_url('login').'" class="wppay-loader"><i class="iconfont">&#xe66b;</i> 登录购买 ￥'.$price.'</a></div>';
				}else{
					$wpayc = '<div class="erphp-wppay all"><p>此文章为付费文章'.$vip_infotext.'</p><a href="javascript:;" id="pay-loader" class="wppay-loader" data-post="'.$post->ID.'"><i class="iconfont">&#xe70c;</i> 支付'.$price.'元查看</a></div>';
				}
				
				return $wpayc;
			}else{
				return '';
			}
		}
	}
	return $content;
}

// 付费查看部分内容
add_shortcode('wppay','wppay_shortcode');
function wppay_shortcode($atts, $content){ 
	$atts = shortcode_atts( array(
        'id' => 0
    ), $atts, 'wppay' );
	global $post,$wpdb;
	$post_id = $post->ID;
	if($atts['id']){
		$post_id = $atts['id'];
	}

	
	$type = get_post_meta($post_id,'wppay_type',true);
	$price = get_post_meta($post_id,'wppay_price',true);
	$post_auth = get_post_meta($post_id,'wppay_vip_auth',true);
	if($price && $type == '2'){
		if (intval($post_auth) == 1) {
        	$vip_infotext= '，月费会员可免费查看';
        }elseif (intval($post_auth) == 2) {
        	$vip_infotext= '，年费会员可免费查看';
        }elseif (intval($post_auth) == 3) {
        	$vip_infotext= '，终身会员可免费查看';
        }else{
        	$vip_infotext= '，请付费后查看';
        }
		$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
		$wppay = new WPAY($post_id, $user_id);
		if($wppay->is_paid()){
			return '<p><div class="erphp-wppay erphp-wppay-success">'.do_shortcode($content).'</div></p>';
		}else{
			if (!_hui('no_loginpay') && !is_user_logged_in()) {
				$erphp = '<p><div class="erphp-wppay"><p>此处内容需要购买后查看'.$vip_infotext.'</p><a href="'.home_url('login').'"  class="wppay-loader" ><i class="iconfont">&#xe66b;</i> 登录购买 ￥'.$price.'</a></div></p>';
			}else{
				$erphp = '<p><div class="erphp-wppay"><p>此处内容需要购买后查看'.$vip_infotext.'</p><a href="javascript:;" id="pay-loader" class="wppay-loader" data-post="'.$post->ID.'"><i class="iconfont">&#xe70c;</i> 支付'.$price.'元查看</a></div></p>';
			}
			return $erphp;
		}
	}else{
		return '';
	}
	
}  


function wppay_get_setting($key=NULL){
	$setting = get_option('wppay_setting');
	return $key ? $setting[$key] : $setting;
}

function wppay_delete_setting(){
	delete_option('wppay_setting');
}

function wppay_setting_key($key){
	if( $key ){
		return "wppay_setting[$key]";
	}

	return false;
}

function wppay_update_setting($setting){
	update_option('wppay_setting', $setting);
}	

function wppay_css_url($css_url){
	return WPAY_URL . "/static/css/{$css_url}.css";
}

function wppay_js_url($js_url){
	return WPAY_URL . "/static/js/{$js_url}.js";
}

function wppay_init_p()
{
    $body    = array('site' => get_bloginfo('name'), 'version' => _the_theme_version(), 'domain' => get_bloginfo('url'), 'email' => get_bloginfo('admin_email'), 'user_token' => 'no', 'data' => time());
    $url     = _the_theme_aurl() . 'wp-content/plugins/rizhuri-auth/api/v1.php';
    $request = new WP_Http;
    $result  = $request->request($url, array('method' => 'POST', 'body' => $body));
    //return $result['body'];
}
if (isset($_GET['activated'])) {wppay_init_p();}

function c_admin_pagenavi($total_count, $number_per_page=15){

	$current_page = isset($_GET['paged'])?$_GET['paged']:1;

	if(isset($_GET['paged'])){
		unset($_GET['paged']);
	}

	$base_url = add_query_arg($_GET,admin_url('admin.php'));

	$total_pages	= ceil($total_count/$number_per_page);

	$first_page_url	= $base_url.'&amp;paged=1';
	$last_page_url	= $base_url.'&amp;paged='.$total_pages;
	
	if($current_page > 1 && $current_page < $total_pages){
		$prev_page		= $current_page-1;
		$prev_page_url	= $base_url.'&amp;paged='.$prev_page;

		$next_page		= $current_page+1;
		$next_page_url	= $base_url.'&amp;paged='.$next_page;
	}elseif($current_page == 1){
		$prev_page_url	= '#';
		$first_page_url	= '#';
		if($total_pages > 1){
			$next_page		= $current_page+1;
			$next_page_url	= $base_url.'&amp;paged='.$next_page;
		}else{
			$next_page_url	= '#';
		}
	}elseif($current_page == $total_pages){
		$prev_page		= $current_page-1;
		$prev_page_url	= $base_url.'&amp;paged='.$prev_page;
		$next_page_url	= '#';
		$last_page_url	= '#';
	}
	?>
	<div class="tablenav bottom">
		<div class="tablenav-pages">
			<span class="displaying-num">每页 <?php echo $number_per_page;?> 共 <?php echo $total_count;?></span>
			<span class="pagination-links">
				<a class="first-page <?php if($current_page==1) echo 'disabled'; ?>" title="前往第一页" href="<?php echo $first_page_url;?>">«</a>
				<a class="prev-page <?php if($current_page==1) echo 'disabled'; ?>" title="前往上一页" href="<?php echo $prev_page_url;?>">‹</a>
				<span class="paging-input">第 <?php echo $current_page;?> 页，共 <span class="total-pages"><?php echo $total_pages; ?></span> 页</span>
				<a class="next-page <?php if($current_page==$total_pages) echo 'disabled'; ?>" title="前往下一页" href="<?php echo $next_page_url;?>">›</a>
				<a class="last-page <?php if($current_page==$total_pages) echo 'disabled'; ?>" title="前往最后一页" href="<?php echo $last_page_url;?>">»</a>
			</span>
		</div>
		<br class="clear">
	</div>
	<?php
}


// 引入支付宝类库
class AlipayService
{
    protected $appId;
    protected $notifyUrl;
    protected $charset;
    //私钥值
    protected $rsaPrivateKey;
    protected $totalFee;
    protected $outTradeNo;
    protected $orderName;

    public function __construct()
    {
        $this->charset = 'utf8';
    }

    public function setAppid($appid)
    {
        $this->appId = $appid;
    }

    public function setNotifyUrl($notifyUrl)
    {
        $this->notifyUrl = $notifyUrl;
    }

    public function setRsaPrivateKey($saPrivateKey)
    {
        $this->rsaPrivateKey = $saPrivateKey;
    }

    public function setTotalFee($payAmount)
    {
        $this->totalFee = $payAmount;
    }

    public function setOutTradeNo($outTradeNo)
    {
        $this->outTradeNo = $outTradeNo;
    }

    public function setOrderName($orderName)
    {
        $this->orderName = $orderName;
    }

    /**
     * 发起订单
     * @return array
     */
    public function doPay()
    {
        //请求参数
        $requestConfigs = array(
            'out_trade_no'=>$this->outTradeNo,
            'total_amount'=>$this->totalFee, //单位 元
            'subject'=>$this->orderName,  //订单标题
            'timeout_express'=>'2h'       //该笔订单允许的最晚付款时间，逾期将关闭交易。取值范围：1m～15d。m-分钟，h-小时，d-天，1c-当天（1c-当天的情况下，无论交易何时创建，都在0点关闭）。 该参数数值不接受小数点， 如 1.5h，可转换为 90m。
        );
        $commonConfigs = array(
            //公共参数
            'app_id' => $this->appId,
            'method' => 'alipay.trade.precreate',             //接口名称
            'format' => 'JSON',
            'charset'=>$this->charset,
            'sign_type'=>'RSA2',
            'timestamp'=>date('Y-m-d H:i:s'),
            'version'=>'1.0',
            'notify_url' => $this->notifyUrl,
            'biz_content'=>json_encode($requestConfigs),
        );
        $commonConfigs["sign"] = $this->generateSign($commonConfigs, $commonConfigs['sign_type']);
        $result = $this->curlPost('https://openapi.alipay.com/gateway.do',$commonConfigs);
        return json_decode($result,true);
    }
    public function generateSign($params, $signType = "RSA") {
        return $this->sign($this->getSignContent($params), $signType);
    }
    protected function sign($data, $signType = "RSA") {
        $priKey=$this->rsaPrivateKey;
        $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($priKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";
        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');
        if ("RSA2" == $signType) {
            openssl_sign($data, $sign, $res, version_compare(PHP_VERSION,'5.4.0', '<') ? SHA256 : OPENSSL_ALGO_SHA256); //OPENSSL_ALGO_SHA256是php5.4.8以上版本才支持
        } else {
            openssl_sign($data, $sign, $res);
        }
        $sign = base64_encode($sign);
        return $sign;
    }
    /**
     * 校验$value是否非空
     *  if not set ,return true;
     *    if is null , return true;
     **/
    protected function checkEmpty($value) {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;
        return false;
    }
    public function getSignContent($params) {
        ksort($params);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
                // 转换成目标字符集
                $v = $this->characet($v, $this->charset);
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }
        unset ($k, $v);
        return $stringToBeSigned;
    }
    /**
     * 转换字符集编码
     * @param $data
     * @param $targetCharset
     * @return string
     */
    function characet($data, $targetCharset) {
        if (!empty($data)) {
            $fileType = $this->charset;
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
                //$data = iconv($fileType, $targetCharset.'//IGNORE', $data);
            }
        }
        return $data;
    }
    public function curlPost($url = '', $postData = '', $options = array())
    {
        if (is_array($postData)) {
            $postData = http_build_query($postData);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}


//引入微信支付类库
class WxpayService
{
    protected $mchid;
    protected $appid;
    protected $apiKey;
    public function __construct($mchid, $appid, $key)
    {
        $this->mchid = $mchid;
        $this->appid = $appid;
        $this->apiKey = $key;
    }
    /**
     * 发起订单
     * @param float $totalFee 收款总费用 单位元
     * @param string $outTradeNo 唯一的订单号
     * @param string $orderName 订单名称
     * @param string $notifyUrl 支付结果通知url 不要有问号
     * @param string $timestamp 订单发起时间
     * @return array
     */
    public function createJsBizPackage($totalFee, $outTradeNo, $orderName, $notifyUrl, $timestamp)
    {
        $config = array(
            'mch_id' => $this->mchid,
            'appid' => $this->appid,
            'key' => $this->apiKey,
        );
        //$orderName = iconv('GBK','UTF-8',$orderName);
        $unified = array(
            'appid' => $config['appid'],
            'attach' => 'pay',             //商家数据包，原样返回，如果填写中文，请注意转换为utf-8
            'body' => $orderName,
            'mch_id' => $config['mch_id'],
            'nonce_str' => self::createNonceStr(),
            'notify_url' => $notifyUrl,
            'out_trade_no' => $outTradeNo,
            'spbill_create_ip' => '127.0.0.1',
            'total_fee' => intval($totalFee * 100),       //单位 转为分
            'trade_type' => 'NATIVE',
        );
        $unified['sign'] = self::getSign($unified, $config['key']);
        $responseXml = self::curlPost('https://api.mch.weixin.qq.com/pay/unifiedorder', self::arrayToXml($unified));
		//禁止引用外部xml实体
		libxml_disable_entity_loader(true);        
        $unifiedOrder = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($unifiedOrder === false) {
            die('parse xml error');
        }
        if ($unifiedOrder->return_code != 'SUCCESS') {
            die($unifiedOrder->return_msg);
        }
        if ($unifiedOrder->result_code != 'SUCCESS') {
            die($unifiedOrder->err_code);
        }
        $codeUrl = (array)($unifiedOrder->code_url);
        if(!$codeUrl[0]) exit('get code_url error');
        $arr = array(
            "appId" => $config['appid'],
            "timeStamp" => $timestamp,
            "nonceStr" => self::createNonceStr(),
            "package" => "prepay_id=" . $unifiedOrder->prepay_id,
            "signType" => 'MD5',
            "code_url" => $codeUrl[0],
        );
        $arr['paySign'] = self::getSign($arr, $config['key']);
        return $arr;
    }
    public function notify()
    {
        $config = array(
            'mch_id' => $this->mchid,
            'appid' => $this->appid,
            'key' => $this->apiKey,
        );
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($postObj === false) {
            die('parse xml error');
        }
        if ($postObj->return_code != 'SUCCESS') {
            die($postObj->return_msg);
        }
        if ($postObj->result_code != 'SUCCESS') {
            die($postObj->err_code);
        }
        $arr = (array)$postObj;
        unset($arr['sign']);
        if (self::getSign($arr, $config['key']) == $postObj->sign) {
            echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
            return $postObj;
        }
    }
    /**
     * curl get
     *
     * @param string $url
     * @param array $options
     * @return mixed
     */
    public static function curlGet($url = '', $options = array())
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    public static function curlPost($url = '', $postData = '', $options = array())
    {
        if (is_array($postData)) {
            $postData = http_build_query($postData);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    public static function createNonceStr($length = 16)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    public static function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
        }
        $xml .= "</xml>";
        return $xml;
    }
    /**
     * 获取签名
     */
    public static function getSign($params, $key)
    {
        ksort($params, SORT_STRING);
        $unSignParaString = self::formatQueryParaMap($params, false);
        $signStr = strtoupper(md5($unSignParaString . "&key=" . $key));
        return $signStr;
    }
    protected static function formatQueryParaMap($paraMap, $urlEncode = false)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if (null != $v && "null" != $v) {
                if ($urlEncode) {
                    $v = urlencode($v);
                }
                $buff .= $k . "=" . $v . "&";
            }
        }
        $reqPar = '';
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }
}
