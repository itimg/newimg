<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-03-01 10:19:00
 * @LastEditors: iowen
 * @LastEditTime: 2021-08-05 23:31:54
 * @FilePath: \onenav\archive.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>


<?php 
get_template_part( 'templates/sidebar','nav' );
?>
<div class="main-content flex-fill">
    
<?php get_template_part( 'templates/tools','header' ); ?>
    <div class="row">
        <div class="col-lg-8">
            <?php get_template_part( 'templates/cat','list' ) ?>
        </div> 
		<div class="sidebar sidebar-tools col-lg-4 pl-xl-4 d-none d-lg-block">
			<?php get_sidebar(); ?>
		</div> 
    </div>
</div>
<?php get_footer(); ?>
