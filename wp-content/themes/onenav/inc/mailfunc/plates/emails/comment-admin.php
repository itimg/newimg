<?php 
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-08-20 10:24:32
 * @LastEditors: iowen
 * @LastEditTime: 2021-08-20 15:12:01
 * @FilePath: \onenav\inc\mailfunc\plates\emails\comment-admin.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$this->layout('base', array('blogName' => get_bloginfo('name'), 'logo' => io_get_option('logo_small_light'), 'home' => home_url())) ?>

<style>
    img{max-width:100%;}
</style>
<p><?=$this->e($commentAuthor)?>在文章《<a href="<?=$this->e($commentLink)?>" target="_blank"><?=$this->e($postTitle)?></a>》发表了评论, 快去看看吧</p>
<p style="border-bottom:#ddd 1px solid;border-left:#ddd 1px solid;padding-bottom:20px;background-color:#eee;margin:15px 0px;padding-left:20px;padding-right:20px;border-top:#ddd 1px solid;border-right:#ddd 1px solid;padding-top:20px"><?=$this->e($commentContent)?></p>
<?php if($this->e($verify)=='1'): ?>
<p>此条评论待审核。</p>
<?php endif; ?>