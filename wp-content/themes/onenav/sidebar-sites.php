<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-07-15 00:43:42
 * @LastEditors: iowen
 * @LastEditTime: 2021-07-15 01:29:58
 * @FilePath: \onenav\sidebar-sites.php
 * @Description: 
 */ 

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if ( is_active_sidebar( 'sidebar-sites-r' ) ) : ?> 
	<div class="sidebar sidebar-tools col-lg-4 pl-xl-4 d-none d-lg-block">
		<?php  dynamic_sidebar( 'sidebar-sites-r' ) ?> 
	</div>
<?php endif; ?>
