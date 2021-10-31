<?php  
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-03-01 10:19:02
 * @LastEditors: iowen
 * @LastEditTime: 2021-08-27 13:46:00
 * @FilePath: \onenav\inc\auth\sina.php
 * @Description: 
 */
include_once('../../../../../wp-config.php');
if(!session_id()) session_start();
$appid =io_get_option('open_weibo_key')['appid'];
$login = new ioLoginSina();
$_SESSION['state']  = md5 ( uniqid ( rand (), true ) ); //CSRF protection
$_SESSION['rurl']   = $_REQUEST ["loginurl"];
$login->login($appid,$_SESSION['state'],get_template_directory_uri().'/inc/auth/sina-callback.php');
?>