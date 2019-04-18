<?php
/* *
 * 配置文件
 * 日期：2018-11-04
 * 说明：新版本支付宝支付，不依赖账户等，更加安全方便，配置简单
 */
 
//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
//蚂蚁金服开放平台APPID //https://open.alipay.com 账户中心->密钥管理->开放平台密钥，填写添加了电脑网站支付的应用的APPID
// $alipay_config['appid'] = get_option('alipay_appid');
$alipay_config['appid'] = _hui('alipay_appid');

//商户私钥，填写对应签名算法类型的私钥，如何生成密钥参考：https://docs.open.alipay.com/291/105971和https://docs.open.alipay.com/200/105310
// $alipay_config['rsaprivateKkey'] = get_option('alipay_rsaprivateKkey');



$alipay_config['rsaprivateKkey'] = _hui('alipay_privatekey');

//支付宝公钥，账户中心->密钥管理->开放平台密钥，找到添加了支付功能的应用，根据你的加密类型，查看支付宝公钥
// $alipay_config['publickey'] = get_option('alipay_publickey');
$alipay_config['publickey'] = _hui('alipay_publickey');

//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

?>