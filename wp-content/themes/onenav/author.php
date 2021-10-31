<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:55:57
 * @LastEditors: iowen
 * @LastEditTime: 2021-07-14 21:30:17
 * @FilePath: \onenav\author.php
 * @Description: 
 */
?>
<?php if ( ! defined( 'ABSPATH' ) ) { exit; }?>
<?php 
if(!io_get_option('user_center')){
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
    nocache_headers();
    echo '<meta http-equiv="refresh" content="0;url='.esc_url(home_url()).'">';
    exit;
}
?>
<?php get_header(); ?>


<?php 
get_template_part( 'templates/sidebar','nav' );
$author = get_user_by('ID', $author);
$user_bg = function_exists('io_get_user_cover')?io_get_user_cover($author->ID ,"full"):get_theme_file_uri('/images/user-default-cover-full.jpg');
?>
<div class="main-content flex-fill page">
<div class="big-header-banner">
<?php get_template_part( 'templates/header','banner' ); ?>
</div>
<div class="user-bg d-flex" style="background-image: url(<?php echo $user_bg ?>)">
    <div class="container d-flex align-items-end position-relative mb-4"> 
        <?php echo get_avatar($author->ID,70) ?> 	
        <div class="author-meta-r ml-0 ml-md-3">
            <div class="h2 text-white mb-2"><?php echo $author->display_name; ?>
                <small class="text-xs"><span class="badge badge-outline-primary mt-2">
                    <?php echo io_get_user_cap_string($author->ID) ?>
                </span></small>
            </div>
            <div class="text-white text-sm"><?php echo ($author->description?:'帅气的我简直无法用语言描述！'); ?></div>
        </div> 
    </div> 
</div>
    <div id="content" class="container user-area my-4"> 
            <div class="card">
                <div class="card-body">
                    <div class="text-lg pb-3 border-bottom border-light border-2w mb-3">基本信息</div>  
                    
                    <div class="empty-content text-center pb-5">
                        <i class="iconfont icon-nothing1"></i>
                    </div>
                </div>
            </div>
	</div> 
<?php get_footer(); ?>