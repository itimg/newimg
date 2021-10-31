<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-08-20 13:29:51
 * @LastEditors: iowen
 * @LastEditTime: 2021-08-20 13:45:19
 * @FilePath: \onenav\inc\mailfunc\plates\emails\contribute-post.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$this->layout('base', array('blogName' => get_bloginfo('name'), 'logo' => io_get_option('logo_small_light'), 'home' => home_url())) ?>

<h3>有投稿需要审核。</h3>
<p>需审核的文章《<?=$this->e($postTitle)?>》</p>