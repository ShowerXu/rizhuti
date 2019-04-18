<?php
// require_once '../../../../../wp-config.php';
// header("Content-Type: text/html;charset=utf-8");
// date_default_timezone_set('Asia/Shanghai');

// if (!is_user_logged_in()) {wp_die('请先登录！');}

// $trade_order_id = date("ymdhis") . mt_rand(100, 999) . mt_rand(100, 999) . mt_rand(100, 999); //本地订单号
// $price          = isset($_GET['ice_money']) && is_numeric($_GET['ice_money']) ? $_GET['ice_money'] : 0;
// $price          = $wpdb->escape($price); //金额

// $subject = get_bloginfo('name').'充值订单['.get_the_author_meta( 'user_login', wp_get_current_user()->ID ).']'; 


// if ($price > 0) {
//     $user_Info = wp_get_current_user();
//     $sql       = "INSERT INTO $wpdb->icemoney (ice_money,ice_num,ice_user_id,ice_time,ice_success,ice_note,ice_success_time,ice_alipay)
//     VALUES ('$price','$trade_order_id','" . $user_Info->ID . "','" . date("Y-m-d H:i:s") . "',0,'0','" . date("Y-m-d H:i:s") . "','')";
//     $a = $wpdb->query($sql);
//     if (!$a) {
//         wp_die('系统发生错误，请稍后重试!');
//     }
// } else {
//     wp_die('请输入您要充值的金额');
// }

// $paymethod = 1; //默认支付宝
// if ($_GET['type']) {
//     $paymethod = intval($_GET['type']);
// }
// //支付方式



// $souqianbao_id  = get_option('ice_souqianbao_appid'); //这里改成appid
// $souqianbao_key = get_option('ice_souqianbao_secret'); //这是您的通讯密钥

// $params = array(
//      "app_id"         => $souqianbao_id, //app_id
//      "app_secret"         => $souqianbao_key, //secret
//      "overtime"         => 220, //超时时间
//      "orderid"         => $trade_order_id, //订单号
//      "subject"         => $subject, //订单说明
//      "money"         => $price, //金额,
//      "returnurl"         => constant("erphpdown") . 'payment/shoukuanbao/notify.php', 
//      "notifyurl"         => constant("erphpdown") . 'payment/shoukuanbao/notify.php'
// ); //构造需要传递的参数

// $link = new zlkbcodepay_link();
// $goPay  = $link->get_paylink($params,$paymethod); 

// $payDeta = $goPay['data']; //
// $url = $payDeta['payurl']; //支付url

// header("Location:{$url}"); //跳转到支付页面


// var_dump($url);die;//////////////////////////////////




//收款宝核心类//////////////////////////////////////
class zlkbcodepay
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

// 获取支付链接
class zlkbcodepay_link 
{
    public function get_paylink($params,$paymethod)
    {
        if (!function_exists("openssl_open"))
        {
            return '<span style="color:red">Fatal Error:管理员未开启openssl组件<br/>正常情况下该组件必须开启<br/>请开启openssl组件解决该问题</span>';
        }
        if (!function_exists("scandir"))
        {
            return '<span style="color:red">Fatal Error:管理员未开启scandir PHP函数<br/>支付宝Sdk 需要使用该函数<br/>请修改php.ini下的disable_function来解决该问题</span>';
        }
        if (empty($params['app_id']))
        {
            return "管理员未配置 应用ID , 无法使用该支付接口";
        } 
        if (empty($params['app_secret']))
        {
            return "管理员未配置 app_secret  , 无法使用该支付接口";
        }   
        if (empty($params['overtime']))
        {
            return "管理员未配置 overtime  , 无法使用该支付接口";
        }   
        return $this->Pay($params,$paymethod);
    }
    
    public function Pay($params,$paymethod)
    {
        
        
        //API请求,创建订单
        $apiparams = array(
            'paymethod'=>$paymethod,
            'orderid'=>$params['orderid'],
            'subject'=>$params['subject'],
            'money'=>$params['money'],
            'returnurl'=>$params['returnurl'],
            'notifyurl'=>$params['notifyurl'],
        );
        //配置
        $payconfig = array(
            'app_id'=>$params['app_id'], //官网应用id
            'app_secret'=>$params['app_secret'],//官网应用secret
            'overtime'=>$params['overtime'], //超时时间
        );
        $zlkbcodepay = new zlkbcodepay();
        $result = $zlkbcodepay->pay($payconfig,$apiparams);
        if(!empty($result)){
            if($result['code']!="1"){
                return $result['msg'];
            }else{
                return $result;
            }
        }else{
            return "创建订单失败";
        }   
    }   
}

