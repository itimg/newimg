<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-07-15 00:43:42
 * @LastEditors: iowen
 * @LastEditTime: 2021-07-15 13:43:45
 * @FilePath: \onenav\sidebar-sitestop.php
 * @Description: 
 */ 

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if ( is_active_sidebar( 'sidebar-sites-t' ) ) : ?> 
	<div class="sidebar sidebar-border col-12 col-md-12 col-lg-4 mt-4 mt-lg-0">
		<?php  dynamic_sidebar( 'sidebar-sites-t' ) ?> 
	</div>
<?php elseif(io_get_option('ad_right_s')) : ?>
    <div class="col-12 col-md-12 col-lg-4 mt-4 mt-lg-0">
        <div class="apd apd-right">
            <?php  echo  stripslashes( io_get_option('ad_right') )   ?>
        </div>
    </div>
<?php endif; ?>
