<?php  
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-03-01 10:19:02
 * @LastEditors: iowen
 * @LastEditTime: 2021-08-27 13:46:03
 * @FilePath: \onenav\inc\auth\qq.php
 * @Description: 
 */
include_once('../../../../../wp-config.php');
if(!session_id()) session_start();
$scope = 'get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo';
$appid = io_get_option('open_qq_key')['appid'];
$login = new ioLoginQQ();
$_SESSION['state']  = md5 ( uniqid ( rand (), true ) ); //CSRF protection
$_SESSION['rurl']   = $_REQUEST ["loginurl"];
$login->login($appid,$scope,$_SESSION ['state'],get_template_directory_uri().'/inc/auth/qq-callback.php');
?>