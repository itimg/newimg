<?php 
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-08-20 10:24:32
 * @LastEditors: iowen
 * @LastEditTime: 2021-08-20 11:17:39
 * @FilePath: \onenav\inc\mailfunc\plates\emails\findpass.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$this->layout('base', array('blogName' => get_bloginfo('name'), 'logo' => io_get_option('logo_small_light'), 'home' => home_url())) ?>

<p>有人要求重设以下帐号的密码：</p>
<br>
<p>网站: <?=$this->e($home)?></p>
<p>用户名: <?=$this->e($userLogin)?></p>
<p>若这不是您本人要求的，请忽略本邮件，一切如常</p>
<p>要重置您的密码，请打开下面的链接:<br><a href="<?=$this->e($resetPassLink)?>" style="word-break: break-all;"><?=$this->e($resetPassLink)?></a></p>