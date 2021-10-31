<?php 
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-08-20 10:24:32
 * @LastEditors: iowen
 * @LastEditTime: 2021-08-20 11:19:07
 * @FilePath: \onenav\inc\mailfunc\plates\emails\comment-pass.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$this->layout('base', array('blogName' => get_bloginfo('name'), 'logo' => io_get_option('logo_small_light'), 'home' => home_url())) ?>

<style>
    img{max-width:100%;}
</style>
<p><?=$this->e($parentAuthor)?>, 您好!</p>
<p>您于<?=$this->e($parentCommentDate)?>在文章《<?=$this->e($postTitle)?>》上发表的评论: </p>
<p style="border-bottom:#ddd 1px solid;border-left:#ddd 1px solid;padding-bottom:20px;background-color:#eee;margin:15px 0px;padding-left:20px;padding-right:20px;border-top:#ddd 1px solid;border-right:#ddd 1px solid;padding-top:20px"><?=$this->e($parentCommentContent)?></p>
<p>管理员已通过审核并显示。</p>
<p>您可在此查看您的评论：<a style="color:#00bbff;text-decoration:none" href="<?=$this->e($commentLink)?>" target="_blank">前往查看完整內容</a></p>