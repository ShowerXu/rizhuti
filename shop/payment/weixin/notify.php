<?php
/**
 * 原生支付（扫码支付）及公众号支付的异步回调通知
 * 说明：需要在native.php或者jsapi.php中的填写回调地址。例如：http://www.xxx.com/wx/notify.php
 *需要在微信支付https://pay.weixin.qq.com/index.php/extend/pay_setting设置扫码回调链接
 * 付款成功后，微信服务器会将付款结果通知到该页面
 */
header('Content-type:text/html; Charset=utf-8');
ob_start();
require_once('../../../../../../wp-load.php');
ob_end_clean();
date_default_timezone_set('Asia/Shanghai');
global $wpdb, $wppay_table_name;


// 更新会员数据
function up_user_vipinfo($user_id,$order_type){
    $this_vip_type=get_user_meta($user_id,'vip_type',true); //当前会员类型 0 31 365 3600
    $this_vip_time=get_user_meta($user_id,'vip_time',true); //当前时间
    $time_stampc = intval($this_vip_time)-time();// 到期时间减去当前时间
    if ($time_stampc > 0) {
        $nwetimes= intval($this_vip_time);
    }else{
        $nwetimes= time();
    }

    if ($order_type==2) {
        # 月费...
        $days= 31;
    }else if ($order_type==3) {
        # 年费...
        $days= 365;
    }else if ($order_type==4) {
        # 终身...
        $days= 3600;
    }else{
        $days= 0;
    }
    // 写入usermeta
    update_user_meta( $user_id, 'vip_type', $days ); //更新等级 
    update_user_meta( $user_id, 'vip_time', $nwetimes+$days*24*3600 );   //更新到期时间

}


$mchid = _hui('weixinpay_mchid');          //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
$appid = _hui('weixinpay_appid');  //公众号APPID 通过微信支付商户资料审核后邮件发送
$apiKey = _hui('weixinpay_apikey');   //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
$wxPay = new WxpayServices($mchid,$appid,$apiKey);
$result = $wxPay->notify();

if($result){
    //完成你的逻辑
    //例如连接数据库，获取付款金额$result['cash_fee']，获取订单号$result['out_trade_no']，修改数据库中的订单状态等;$result['transaction_id']
   
    $transaction_id = $result['transaction_id']; //微信订单号
    $out_trade_no = $result['out_trade_no']; //本地订单号
    $cash_price = $result['cash_fee']; //付款金额

    $order=$wpdb->get_row("select * from $wppay_table_name where order_num='".$out_trade_no."'");

    if($order){
        $user_id = $order->user_id; //该订单用户id
        $order_type = $order->order_type; //订单类型

        if(!$order->status){

            if ($order->order_type!= 1) {
                up_user_vipinfo($user_id,$order_type);// 更新会员信息
            }
    
            
            $update_order = $wpdb->query("UPDATE $wppay_table_name SET pay_num = '".$transaction_id."', pay_time = '".time()."' ,status=1 WHERE order_num = '".$out_trade_no."'");
            // 发送邮件
            if ($update_order && _hui('is_sened_paymail') && $user_id != 0) {
                $user_obj = get_user_by('id', $user_id);
                $email = $user_obj->user_email;
                $title = '恭喜您付款成功';
                $headers = array('Content-Type: text/html; charset=UTF-8');
                $pay_type = ($order->pay_type == 1) ? '支付宝' : '微信支付' ;
                $a_href = '<a href="'.home_url().'">查看或下载</a>';
                // get_permalink($value->post_id)
                if ($order_type == 1) {
                    $order_name = get_the_title($order->post_id);
                    $a_href = '<a href="'.get_permalink($order->post_id).'">查看或下载</a>';
                }elseif ($order_type == 2) {
                    $order_name = '月费会员';
                }elseif ($order_type == 3) {
                    $order_name = '年费会员';
                }elseif ($order_type == 4) {
                    $order_name = '终身会员';
                }
                $message = tpl_emailPay($order->order_num,$order_name,$order->order_price,$pay_type,$a_href);
                _sendMail($email,$title,$message,$headers);
            }
            echo 'success';exit();
        }
    }

}else{
    echo 'pay error';
}
class WxpayServices
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

    public function notify()
    {
        $config = array(
            'mch_id' => $this->mchid,
            'appid' => $this->appid,
            'key' => $this->apiKey,
        );
        $postStr = file_get_contents('php://input');
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);        
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
            return $arr;
        }
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
