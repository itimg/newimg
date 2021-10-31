<?php  
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-03-01 10:19:02
 * @LastEditors: iowen
 * @LastEditTime: 2021-09-11 14:10:44
 * @FilePath: \onenav\inc\auth\qq-callback.php
 * @Description: 
 */
include_once('../../../../../wp-config.php'); 
$appid      = io_get_option('open_qq_key')['appid'];
$appkey     = io_get_option('open_qq_key')['appkey'];
$callback   = new ioLoginQQ();
if(!session_id()) session_start();
$callback_data = $callback->callback($appid,$appkey,get_template_directory_uri().'/inc/auth/qq-callback.php',$_SESSION['state']);
if(is_array($callback_data)){
    $open_id = $callback->get_openid($callback_data['token']);
    $callback->use_db($callback_data['token'],$open_id,$_SESSION['rurl']);
}
?>