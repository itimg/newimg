<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-03-01 10:19:07
 * @LastEditors: iowen
 * @LastEditTime: 2021-05-12 01:02:03
 * @FilePath: \onenav\templates\card-book.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }  ?>
<?php 
$ico_info = get_post_meta(get_the_ID(), 'app_ico_o', true);
$bg = '';$size='';
$target = isset($new_window)&&$new_window?' target="_blank"':'';
if($ico_info && $ico_info['ico_a']){
    $bg ='style="background-image: linear-gradient(130deg, '.$ico_info['ico_color']['color-1'].', '.$ico_info['ico_color']['color-2'].');"';
    $size = 'background-size: '.$ico_info["ico_size"].'%';
}
?>
	<div class="card-book list-item">
        <div class="media media-5x7 p-0 rounded">
            <?php if(io_get_option('lazyload')): ?>
            <a class="media-content" href="<?php the_permalink(); ?>" <?php echo ($target!=''?$target:new_window()) ?> data-bg="url(<?php echo get_post_meta_img(get_the_ID(), '_thumbnail', true) ?>)"></a>
            <?php else: ?>
            <a class="media-content" href="<?php the_permalink(); ?>" <?php echo ($target!=''?$target:new_window()) ?>  style="background-image: url(<?php echo get_post_meta_img(get_the_ID(), '_thumbnail', true) ?>)"></a>
            <?php endif ?>
        </div>
        <div class="list-content">
            <div class="list-body">
                <a href="<?php the_permalink(); ?>" <?php echo ($target!=''?$target:new_window()) ?> class=" list-title text-md overflowClip_1">
                <?php show_sticky_tag( is_sticky() ) . show_new_tag(get_the_time('Y-m-d H:i:s')) ?><?php the_title(); ?>         
                </a>
                <div class="mt-1">
                    <div class="list-subtitle text-muted text-xs overflowClip_1">
                    <?php echo get_post_meta(get_the_ID(), '_summary', true) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>	 
