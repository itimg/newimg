<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-07-27 14:18:44
 * @LastEditors: iowen
 * @LastEditTime: 2021-09-30 23:28:01
 * @FilePath: \onenav\templates\hot\hot-home.php
 * @Description: 
 */
?>
<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php 
get_header(); 
get_template_part( 'templates/sidebar','nav' );
?>
	<div class="main-content flex-fill">
    
	<?php get_template_part( 'templates/header','banner' ); ?> 
	<div id="content" class="container container-lg customize-site mb-4 mb-md-5">
		<?php get_template_part( 'templates/search/mini' ) ?>
		<div class="row row-sm">
            
                <?php
                $list=io_get_option('hot_home_list');
                foreach ($list as $value) {
                    echo '<div class="col-12 col-md-6 col-lg-4 mb-3">';
                    hot_search($value); 
                    echo '</div> ';
                } 
                ?>
        </div>
	</div> 
<?php
get_footer(); 
