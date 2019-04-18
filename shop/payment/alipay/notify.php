<?php
header('Content-type:text/html; Charset=utf-8');
ob_start();
require_once('../../../../../../wp-load.php');
ob_end_clean();
date_default_timezone_set('Asia/Shanghai');
require_once("alipay.config.php");
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


//支付宝公钥，账户中心->密钥管理->开放平台密钥，找到添加了支付功能的应用，根据你的加密类型，查看支付宝公钥
$alipayPublicKey=_hui('alipay_publickey');

$aliPay = new AlipayServiceCheck($alipayPublicKey);

if (!isset($_POST['sign_type'])) {
    echo '非法请求';exit();
}


//验证签名
$result = $aliPay->rsaCheck($_POST,$_POST['sign_type']);


wpay_debug_log($_POST['trade_status'].'...');

wpay_debug_log($_POST['sign_type'].'...');

wpay_debug_log($_POST['$result'].'..check签名.');


if($result===true){
wpay_debug_log($_POST['$result'].'..通过签名.');
    if ($_POST['trade_status'] == 'WAIT_BUYER_PAY') {
        // 该状态表示订单已经生成，用户没有付款
        //wpay_debug_log('订单号：'.$_POST['out_trade_no'].'正在扫码...');
        echo "success";exit();
    }elseif ($_POST['trade_status'] == 'TRADE_SUCCESS') {
       // 该状态表示订单已经付款成功
       //处理你的逻辑，例如获取订单号$_POST['out_trade_no']，订单金额$_POST['total_amount']等
       
        //商户订单号
        $out_trade_no = $_POST['out_trade_no'];
        //支付宝交易号
        $trade_no = $_POST['trade_no'];

        $order=$wpdb->get_row("select * from $wppay_table_name where order_num='".$out_trade_no."'");

        if($order){
            $user_id = $order->user_id; //该订单用户id
            $order_type = $order->order_type; //订单类型

            if(!$order->status){

                if ($order->order_type!=1) {
                    up_user_vipinfo($user_id,$order_type);// 更新会员信息
                }

                $update_order = $wpdb->query("UPDATE $wppay_table_name SET pay_num = '".$trade_no."', pay_time = '".time()."' ,status=1 WHERE order_num = '".$out_trade_no."'");
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

    }


    //程序执行完后必须打印输出“success”（不包含引号）。如果商户反馈给支付宝的字符不是success这7个字符，支付宝服务器会不断重发通知，直到超过24小时22分钟。一般情况下，25小时以内完成8次通知（通知的间隔频率一般是：4m,10m,10m,1h,2h,6h,15h）；
    
}

echo 'sign error';exit();

class AlipayServiceCheck
{
    //支付宝公钥
    protected $alipayPublicKey;
    protected $charset;

    public function __construct($alipayPublicKey)
    {
        $this->charset = 'utf8';
        $this->alipayPublicKey=$alipayPublicKey;
    }

    /**
     *  验证签名
     **/
    public function rsaCheck($params) {
        $sign = $params['sign'];
        $signType = $params['sign_type'];
        unset($params['sign_type']);
        unset($params['sign']);
        return $this->verify($this->getSignContent($params), $sign, $signType);
    }

    function verify($data, $sign, $signType = 'RSA') {
        $pubKey= $this->alipayPublicKey;
        $res = "-----BEGIN PUBLIC KEY-----\n" .
            wordwrap($pubKey, 64, "\n", true) .
            "\n-----END PUBLIC KEY-----";
        ($res) or die('支付宝RSA公钥错误。请检查公钥文件格式是否正确');

        //调用openssl内置方法验签，返回bool值
        if ("RSA2" == $signType) {
            $result = (bool)openssl_verify($data, base64_decode($sign), $res, version_compare(PHP_VERSION,'5.4.0', '<') ? SHA256 : OPENSSL_ALGO_SHA256);
        } else {
            $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        }
//        if(!$this->checkEmpty($this->alipayPublicKey)) {
//            //释放资源
//            openssl_free_key($res);
//        }
        return $result;
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
                

                // 修复转义导致签名失败
                $v = stripslashes($v);

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
       
        // var_dump($stringToBeSigned);

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
}