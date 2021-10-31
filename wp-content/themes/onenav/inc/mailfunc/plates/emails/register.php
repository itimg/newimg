<?php 
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-08-20 10:24:32
 * @LastEditors: iowen
 * @LastEditTime: 2021-08-20 11:18:21
 * @FilePath: \onenav\inc\mailfunc\plates\emails\register.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$this->layout('base', array('blogName' => get_bloginfo('name'), 'logo' => io_get_option('logo_small_light'), 'home' => home_url())) ?>

<p>您的注册用户名和密码信息如下:</p>
<div style="background-color:#fefcc9; padding:10px 15px; border:1px solid #f7dfa4; font-size: 12px;line-height:160%;">
    用户名: <?=$this->e($loginName)?>
    <br>登录密码: <?=$this->e($password)?>
    <br>登录链接: <a href="<?=$this->e($loginLink)?>"><?=$this->e($loginLink)?></a>
</div>