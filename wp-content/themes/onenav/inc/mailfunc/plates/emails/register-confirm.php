<?php 
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-08-20 10:24:32
 * @LastEditors: iowen
 * @LastEditTime: 2021-08-20 11:18:19
 * @FilePath: \onenav\inc\mailfunc\plates\emails\register-confirm.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$this->layout('base', array('blogName' => get_bloginfo('name'), 'logo' => io_get_option('logo_small_light'), 'home' => home_url())) ?>

<h2 style="color: #333;font-size: 30px;font-weight: 400;line-height: 34px;margin-top: 0;text-align: center;">欢迎, <?=$this->e($name)?></h2>
<p style="color: #444;font-size: 17px;line-height: 24px;margin-bottom: 0;text-align: center;">要完成注册, 请点击下面的激活按钮确认你的账户</p>
<div id="cta" style="border: 1px solid #e14329; border-radius: 3px; display: block; margin: 20px auto; padding: 12px 24px;max-width: 120px;text-align: center;">
    <a href="<?=$this->e($link)?>" style="color: #e14329; display: inline-block; text-decoration: none" target="_blank">确认账户</a>
</div>