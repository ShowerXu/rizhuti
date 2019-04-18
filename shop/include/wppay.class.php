<?php
date_default_timezone_set('Asia/Shanghai');
class WPAY
{
	private $ip;
	public $post_id;
	public $user_id;
	public $order_type;

	public function __construct($postid, $userid)
	{
		$this->ip = $_SERVER['REMOTE_ADDR'];
		$this->post_id = $postid;
		$this->user_id = $userid ? $userid : 0;
	}
	// 检测订单查询状态 返回int 0 OR 1
	public function check_paid($order_num)
	{
		global $wpdb, $wppay_table_name;
		$sql_ispay = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$wppay_table_name} WHERE post_id = %d AND status = 1  AND order_num = %s", $this->post_id, $order_num));
		$sql_ispay = intval($sql_ispay);
		return $sql_ispay && $sql_ispay > 0;
	}

	


	// 判断当前用户是否购买
	public function is_paid()
	{
		global $wpdb, $wppay_table_name;
		$sql_ispay= 0;
		if (isset($_COOKIE['wppay_' . $this->post_id])) {
			$this_key_id = $this->get_key($_COOKIE['wppay_' . $this->post_id]);
			$sql_ispay = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$wppay_table_name} WHERE post_id = %d AND status = 1  AND order_num = %s", $this->post_id, $this_key_id));
			$sql_ispay = intval($sql_ispay);
			return $sql_ispay && $sql_ispay > 0;
		}
	
		//会员权限
		if ($this->user_id) {

			$sql_ispay = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$wppay_table_name} WHERE   post_id = %d AND status = 1  AND user_id = %d", $this->post_id, $this->user_id));
			if ($sql_ispay) {
				$sql_ispay= 1;
			}else{
				// 获取文章会员权限设置
				$post_auth = get_post_meta($this->post_id,'wppay_vip_auth',true);
				
				// 获取会员等级
				$vip_type=get_user_meta($this->user_id,'vip_type',true);
			    $vip_time=get_user_meta($this->user_id,'vip_time',true);
			    $timestamp = intval($vip_time)-time();
			    if ($timestamp > 0 ) {
			        if (intval($post_auth) == 1 && intval($vip_type) >= 31) {
			        	$sql_ispay= 1;
			        }elseif (intval($post_auth) == 2 && intval($vip_type) >= 365) {
			        	$sql_ispay= 1;
			        }elseif (intval($post_auth) == 3 && intval($vip_type) >= 3600) {
			        	$sql_ispay= 1;
			        }else{
			        	$sql_ispay= 0;
			        }
			    }
			}
			
		}

		$sql_ispay = intval($sql_ispay);
		return $sql_ispay && $sql_ispay > 0;

	}
	// 添加订单到数据
	public function add($out_trade_no, $price ,$order_type,$pay_type)
	{
	
		global $wpdb, $wppay_table_name;
		$sql = $wpdb->insert($wppay_table_name, array('order_num' => $out_trade_no, 'order_type' => $order_type, 'pay_type' => $pay_type, 'post_id' => $this->post_id, 'order_price' => $price,'user_id' => $this->user_id, 'create_time' => time()), array('%s', '%d', '%d','%d', '%s', '%d', '%s'));
		if ($sql) {
			return true;
		}
		return false;
	}
	
	// http请求封装
	public function curl_post($url = '', $data = '')
	{
		if (function_exists('curl_init')) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		} else {
			wp_die('网站未开启curl组件，正常情况下该组件必须开启，请开启curl组件解决该问题');
		}
	}
	

	// 获取后台设置的关键词key识别码
	public function get_key($getkey)
	{
		return str_replace(md5(_hui('pay_key')), '', base64_decode($getkey));
	}
	// 生成key
	public function set_key($setkey)
	{
		return base64_encode($setkey . md5(_hui('pay_key')));
	}

}