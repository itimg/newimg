<?php 
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-08-20 10:24:32
 * @LastEditors: iowen
 * @LastEditTime: 2021-08-20 11:17:51
 * @FilePath: \onenav\inc\mailfunc\plates\emails\register-admin.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$this->layout('base', array('blogName' => get_bloginfo('name'), 'logo' => io_get_option('logo_small_light'), 'home' => home_url())) ?>

<p>您的站点「<?php echo get_bloginfo('name'); ?>」有新用户注册:</p>
<div style="background-color:#fefcc9; padding:10px 15px; border:1px solid #f7dfa4; font-size: 12px;line-height:160%;">
    用户名: <?=$this->e($loginName)?>
    <br>注册邮箱: <?=$this->e($email)?>
    <br>注册时间: <?php echo date("Y-m-d H:i:s"); ?>
    <br>注册IP: <?=$this->e($ip)?><?php echo ' [' . query_ip_addr($ip) . ']'; ?>
</div>