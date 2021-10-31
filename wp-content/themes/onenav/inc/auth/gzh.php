<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-03-01 10:19:02
 * @LastEditors: iowen
 * @LastEditTime: 2021-08-27 13:44:01
 * @FilePath: \onenav\inc\auth\gzh.php
 * @Description: 
 */
include_once('../../../../../wp-config.php');
//启用 session
if(!session_id()) session_start(); 

$wxConfig = io_get_option('open_weixin_gzh_key');
$_SESSION['oauth_rurl']     = !empty($_REQUEST["rurl"]) ? $_REQUEST["rurl"] : ''; // 储存返回页面
$_SESSION['weixin_state']   = md5 ( uniqid ( rand (), true ) ); //CSRF protection
$_SESSION['rurl']           = $_REQUEST ["loginurl"];
$back_url = get_template_directory_uri().'/inc/auth/gzh-callback.php'; 
$scope = 'snsapi_userinfo'; 
$login = new ioLoginWechat();
$login->login2($wxConfig['appid'],$scope,$_SESSION ['weixin_state'],$back_url);
