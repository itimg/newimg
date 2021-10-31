<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-03-01 10:19:07
 * @LastEditors: iowen
 * @LastEditTime: 2021-05-12 00:54:31
 * @FilePath: \onenav\templates\card-appmin.php
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
	<div class="card-app card">
	    <div class="card-body align-items-center d-flex flex-fill p-2">
            <div class="media size-50 p-0 app-rounded" <?php echo $bg ?>>
                <?php if(io_get_option('lazyload')): ?>
                <a class="media-content" href="<?php the_permalink(); ?>" <?php echo ($target!=''?$target:new_window()) ?> data-bg="url(<?php echo get_post_meta_img(get_the_ID(), '_app_ico', true) ?>)" style="<?php echo $size ?>"></a>
                <?php else: ?>
                <a class="media-content" href="<?php the_permalink(); ?>" <?php echo ($target!=''?$target:new_window()) ?>  style="background-image: url(<?php echo get_post_meta_img(get_the_ID(), '_app_ico', true) ?>);<?php echo $size ?>"></a>
                <?php endif ?>
            </div>
            <div class="app-content flex-fill pl-2 pr-1">
                <div class="mb-2"><a href="<?php the_permalink(); ?>" <?php echo ($target!=''?$target:new_window()) ?> class="text-md no-c overflowClip_1"><?php the_title(); ?><?php echo  '<span class="text-xs"> - '.get_post_meta(get_the_ID(), 'app_down_list', true)[0]['app_version'].'</span>' ?></a></div>
                <div class="text-muted text-xs overflowClip_1"><?php echo get_post_meta(get_the_ID(), '_app_sescribe', true) ?></div>
     
            </div>
        </div>
    </div>
