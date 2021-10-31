<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:55:58
 * @LastEditors: iowen
 * @LastEditTime: 2021-09-12 00:01:25
 * @FilePath: \onenav\header.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<?php get_template_part( 'templates/title' ) ?>
<link rel="shortcut icon" href="<?php echo io_get_option('favicon') ?>">
<link rel="apple-touch-icon" href="<?php echo io_get_option('apple_icon') ?>">
<?php wp_head(); ?>
<?php custom_color() ?>
<?php echo io_get_option('ad_t'); ?>
<!-- 自定义代码 -->
<?php echo io_get_option('code_2_header');?>
<!-- end 自定义代码 -->
</head> 
<?php if(get_query_var('bookmark_id')): ?>
<body <?php body_class(); ?> >
<?php else: ?>
<body <?php body_class(theme_mode()); ?> >
<?php dark_mode_js() ?>
<?php endif; ?>
<?php if(io_get_option('loading_fx')) { ?><div id="loading"><?=loading_type()?></div><?php } ?>
   <div class="page-container">
