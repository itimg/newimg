<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:56:02
 * @LastEditors: iowen
 * @LastEditTime: 2021-07-16 21:08:01
 * @FilePath: \onenav\templates\widget\header-user.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $current_user; ?>
<li class="nav-login-user dropdown ml-3 ml-md-4">
    <a class="user-ico" href="#" data-toggle="dropdown">
        <?php echo get_avatar($current_user->ID) ?> 
    </a> 
    
    <div  class="dropdown-menu dropdown-menu-right text-center" style="min-width:auto">
		<?php if(user_can($current_user->ID,'manage_options')): ?>
		<a class="dropdown-item" href="<?php echo admin_url() ?>"><i class="iconfont mr-2 icon-chart-pc"></i><?php _e('后台管理','i_theme') ?></a>
		<?php endif; ?>
		<?php if(io_get_option('user_center')): ?>
		<a class="dropdown-item" href="<?php echo esc_url(home_url('/user/settings')) ?>"><i class="iconfont mr-2 icon-user"></i><?php _e('个人中心','i_theme') ?></a>
		<a class="dropdown-item" href="<?php echo esc_url(home_url('/user/settings')) ?>"><i class="iconfont mr-2 icon-business"></i><?php _e('用户信息','i_theme') ?></a>
		<a class="dropdown-item" href="<?php echo esc_url(home_url('/user/sites')) ?>"><i class="iconfont mr-2 icon-category"></i><?php _e('网址管理','i_theme') ?></a>
		<a class="dropdown-item" href="<?php echo esc_url(home_url('/user/stars')) ?>"><i class="iconfont mr-2 icon-heart"></i><?php _e('我的收藏','i_theme') ?></a>
		<a class="dropdown-item" href="<?php echo esc_url(home_url('/user/security')) ?>"><i class="iconfont mr-2 icon-statement"></i><?php _e('安全设置','i_theme') ?></a>
		<?php endif; ?>
		<div class="dropdown-divider"></div>
		<a class="dropdown-item" href="<?php echo esc_url(home_url('/bookmark/'.base64_io_encode(sprintf("%08d", $current_user->ID)))) ?>"><i class="iconfont mr-2 icon-tags"></i><?php _e('我的书签','i_theme') ?></a>
		<a class="dropdown-item" href="<?php echo wp_logout_url(home_url());?>"><i class="iconfont mr-2 icon-close-circle"></i><?php _e('安全退出','i_theme') ?></a>
	</div>
</li>
