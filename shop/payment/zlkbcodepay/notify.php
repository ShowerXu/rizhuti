<?php
header('Content-type:text/html; Charset=utf-8');
date_default_timezone_set('Asia/Shanghai');
ob_start();
require_once('../../../../../../wp-load.php');
ob_end_clean();
global $wpdb, $wppay_table_name;
$souqianbao_id  = _hui('mzf_appid'); //这里改成appid
$souqianbao_key = _hui('zlkb_secret'); //这是您的通讯密钥


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


// 验证
if(!empty($_GET) AND isset($_GET['paytime'])){ //同步回调通知
    $zlkbcodepays = new zlkbcodepays();
    //验证签名
    $sign = $zlkbcodepays->signParams($_GET,$souqianbao_key);
    if($sign == $_GET['sign']){
       // 
    }
}
if(!empty($_POST) AND $_POST['paytime']){//异步回调通知
    $zlkbcodepays = new zlkbcodepays();
    //验证签名
    $sign = $zlkbcodepays->signParams($_POST,$souqianbao_key);
    if($sign == $_POST['sign']){
        //业务处理
        $transaction_id = $_POST['orderid']; //接口流水号
        $out_trade_no = $_POST['ordersn']; //本地订单号
        $price = (float)$_POST['price']; //订单的原价
        $cash_price = (float)$_POST['money']; ////实际付款金额

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
                    $pay_type = ($_POST['paymethod'] == 1) ? '微信支付' : '支付宝' ;
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
        //业务处理
        exit("success");
    }else{
        exit("Verify Signature Failure");
    }
}
exit("error");



//收款宝核心类//////////////////////////////////////
class zlkbcodepays
{
    private $apiHost="https://codepay.zlkb.net/api/order";
    
    //处理请求
    public function pay($payconfig,$params)
    {
        try{
            $config =array(
                'version'=>1,
                'paymethod'=>$params['paymethod'],
                'appid'=>$payconfig['app_id'],
                'ordersn'=>$params['orderid'],
                'subject'=>$params['subject'],
                'money'=>(float)$params['money'],
                'overtime'=>$payconfig['overtime'],
                'return_url' => $params['returnurl'],
                'notify_url' => $params['notifyurl'],
            );
            $config['sign'] = $this->signParams($config,$payconfig['app_secret']);
            $curl_data =  $this->_curlPost($this->apiHost,$config);
            $curl_data = json_decode($curl_data,true);
            if(is_array($curl_data)){
                if($curl_data['code']<1){
                    return array('code'=>1002,'msg'=>$curl_data['msg'],'data'=>'');
                }else{
                    return array('code'=>1,'msg'=>'success','data'=>$curl_data['data']);
                }
            }else{
                return array('code'=>1001,'msg'=>"支付接口请求失败",'data'=>'');
            }
        } catch (\Exception $e) {
            return array('code'=>1000,'msg'=>$e->getMessage(),'data'=>'');
        }
    }
    
    
    //异步处理返回
    public function notify($payconfig)
    {
        if(!empty($_POST)){
            $params = $_POST;
            $newsign = $this->signParams($params,$payconfig['app_secret']);
            
            if ($newsign != $params['sign']) { //不合法的数据 KEY密钥为你的密钥
                return 'error|Notify: auth fail';
            } else { //合法的数据
                //业务处理
                $config = array('tradeid'=>$params['orderid'],'paymoney'=>$params['money'],'orderid'=>$params['ordersn']);
                //开始处理
                
                
                
                //处理完成
                return "success";
            }
        }else{
            return 'error|Notify: empty';
        }
    }
    
    
    private function _curlPost($url,$params){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT,300); //设置超时
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result; 
    }
    
    public function signParams($params,$secret){
        $sign = $signstr = "";
        if(!empty($params)){
            ksort($params);
            reset($params);
            
            foreach ($params AS $key => $val) {
                if ($key == 'sign') continue;
                if ($signstr != '') {
                    $signstr .= "&";
                }
                $signstr .= "$key=$val";
            }
            $sign = md5($signstr.$secret);
        }
        return $sign;
    }   
    
}
