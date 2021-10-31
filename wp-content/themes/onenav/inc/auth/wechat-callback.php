<?php   
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-03-01 10:19:02
 * @LastEditors: iowen
 * @LastEditTime: 2021-09-11 14:10:35
 * @FilePath: \onenav\inc\auth\wechat-callback.php
 * @Description: 
 */
include_once('../../../../../wp-config.php'); 
$appid 	= io_get_option('open_wechat_key')['appid'];
$appkey	= io_get_option('open_wechat_key')['appkey'];
if(isset($_REQUEST['code']) && isset($_REQUEST['state'])){
	$callback = new ioLoginWechat();
	if(!session_id()) session_start();
	$callback_data = $callback->callback($appid,$appkey,$_REQUEST['code'],$_SESSION ['state'] );
	if(is_array($callback_data)){
		$callback->use_db($callback_data['token'],$callback_data['open_id'],'wechat',$_SESSION['rurl']);
	}
}