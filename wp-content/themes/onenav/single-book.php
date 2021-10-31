<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:56:00
 * @LastEditors: iowen
 * @LastEditTime: 2021-08-30 18:53:22
 * @FilePath: \onenav\single-book.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>


<?php 
get_template_part( 'templates/sidebar','nav' );
?>
<div class="main-content flex-fill single">
<?php get_template_part( 'templates/header','banner' ); ?>
<div id="content" class="container my-4 my-md-5">
                <?php 
                $book_type = get_post_meta(get_the_ID(), '_book_type', true);
                if($book_type == "down") include( get_theme_file_path('/templates/content-down.php') );
                else include( get_theme_file_path('/templates/content-book.php') );
                ?>

                <h2 class="text-gray text-lg my-4"><i class="site-tag iconfont icon-book icon-lg mr-1" ></i><?php echo sprintf(__('相关%s','i_theme'),get_book_type_name($book_type)) ?></h2>
                <div class="row mb-n4 customize-site"> 
                    <?php get_template_part( 'templates/related','book' ); ?>
                </div>
                <?php 
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif; 
                ?>
</div>
<?php get_footer(); ?>