<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:56:01
 * @LastEditors: iowen
 * @LastEditTime: 2021-09-12 20:45:05
 * @FilePath: \onenav\templates\bookmark\bm.index.php
 * @Description: 
 */
?>
<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<?php
global $bookmark_id,$bookmark_user,$bookmark_set;
$bookmark_id = (get_query_var('bookmark_id'));
if($bookmark_id != 'default'){
    $bookmark_id=base64_io_decode($bookmark_id);   
    $bookmark_user=get_user_by('ID', $action);
    $bookmark_set = maybe_unserialize(get_user_meta( $bookmark_id, 'bookmark_set', true ));
}
?> 

<?php get_header();?>
<!-- background -->
<div class="bookmark-bg"> 
    <?php
    $bg = get_bookmark_seting('bg',$bookmark_set);
    if($bg == 'custom'){
        $bg_img = get_bookmark_seting('custom_img',$bookmark_set);
        echo'<div class="img-bg" style="background-image: url('.$bg_img.')"> </div>';
    }elseif($bg == 'bing'){
        $bg_img = get_bing_img_cache();
        echo'<div class="img-bg" style="background-image: url('.$bg_img.')"> </div>';
        //<img class="img-bg-img d-none" src="'.$bg_img.'" alt="'. get_bloginfo('name').'" crossorigin="anonymous" />';
    }elseif(is_numeric($bg)){
        echo '<iframe class="canvas-bg" scrolling="no" sandbox="allow-scripts allow-same-origin" src="'.get_theme_file_uri('/fx/io-fx'.$bg.'.html').'"></iframe>';
    }
    ?>
    <div class="gradient-linear"></div>
</div>
<!-- background end -->
<?php  

// header
get_template_part( 'templates/bookmark/bm.header' ); 

// 搜索模块
echo '<div class="header-big  mb-4">';
get_template_part('templates/search/big');
echo '</div>';

// 快速导航
get_template_part( 'templates/bookmark/bm.quick' ); 

// 书签
get_template_part( 'templates/bookmark/bm.sites' );

?>
<?php get_footer();?>
