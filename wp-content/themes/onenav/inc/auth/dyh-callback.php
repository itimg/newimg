<?php   
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-03-01 10:19:02
 * @LastEditors: iowen
 * @LastEditTime: 2021-08-31 17:31:55
 * @FilePath: \onenav\inc\auth\dyh-callback.php
 * @Description: 
 */
include_once('../../../../../wp-config.php'); 
header("Content-type:text/html;charset=utf-8");
$wxConfig = io_get_option('open_weixin_dyh_key');
//微信配置接口验证
if (!empty($_REQUEST['echostr']) && !empty($_REQUEST['signature'])) {
    //微信接口校验
    $signature = $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce = $_GET["nonce"];

    $token = $wxConfig['token'];
    $tmpArr = array($token, $timestamp, $nonce);
    sort($tmpArr, SORT_STRING);
    $tmpStr = implode($tmpArr);
    $tmpStr = sha1($tmpStr);

    if ($tmpStr == $signature) {
        echo $_REQUEST['echostr'];
    }
    exit();
}
$callback = new ioLoginWechatDYH($wxConfig['appid'], $wxConfig['appkey']);
if($callback->callback()){
    $callback->responseMsg();
}