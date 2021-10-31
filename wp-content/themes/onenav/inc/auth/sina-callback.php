<?php  
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-03-01 10:19:02
 * @LastEditors: iowen
 * @LastEditTime: 2021-09-11 14:10:39
 * @FilePath: \onenav\inc\auth\sina-callback.php
 * @Description: 
 */
include_once('../../../../../wp-config.php');
$appid      = io_get_option('open_weibo_key')['appid'];
$appkey     = io_get_option('open_weibo_key')['appkey'];
$callback   = new ioLoginSina();
if(!session_id()) session_start();
$callback_data = $callback->callback($appid,$appkey,get_template_directory_uri().'/inc/auth/sina-callback.php',$_SESSION['state']);
if(is_array($callback_data)){
    $callback->use_db($callback_data['token'],$callback_data['open_id'],$_SESSION['rurl']);
}
?>