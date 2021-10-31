<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:56:01
 * @LastEditors: iowen
 * @LastEditTime: 2021-07-21 17:28:33
 * @FilePath: \onenav\templates\bulletin.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if( io_get_option('show_bulletin') && io_get_option('bulletin')) : ?>
<div id="bulletin_box" class="card my-2" >
    <div class="card-body py-1 px-2 px-md-3 d-flex flex-fill text-xs text-muted">
		<div><i class="iconfont icon-bulletin" style="line-height:25px"></i></div>
        <div class="bulletin mx-1 mx-md-2 carousel-vertical">
			<div class="carousel slide" data-ride="carousel" data-interval="3000">
				<div class="carousel-inner" role="listbox">
				<?php 
				$args = array(
					'post_type' => 'bulletin', 
					'posts_per_page' => io_get_option('bulletin_n')
				);
				$i = 0;
				$the_query = new WP_Query($args); 
				while ( $the_query->have_posts() ) : $the_query->the_post();
				?>
				<?php 
                    if(get_post_meta(get_the_ID(),'_goto',true)){
						the_title( sprintf( '<div class="carousel-item %s"><a class="overflowClip_1" href="%s" target="_blank" rel="bulletin noreferrer noopener%s">',$i==0?'active':'',  esc_url( get_permalink() ),get_post_meta(get_the_ID(),'_nofollow',true)?' external nofollow':'' ), ' ('. get_the_time('m/d').')</a></div>' ); 
                    }else{
                        the_title( sprintf( '<div class="carousel-item %s"><a class="overflowClip_1" href="%s" rel="bulletin">',$i==0?'active':'',  esc_url( get_permalink() ) ), ' ('. get_the_time('m/d').')</a></div>' ); 
                    }
				?>
				<?php $i ++; endwhile; ?>
				<?php wp_reset_postdata(); ?>
            
				</div>
			</div>
		</div> 
		<div class="flex-fill"></div>
        <a title="<?php _e('关闭','i_theme') ?>" href="javascript:;" rel="external nofollow" class="bulletin-close" onClick="$('#bulletin_box').slideUp('slow');"><i class="iconfont icon-close" style="line-height:25px"></i></a>
    </div>
</div>
<?php endif; ?> 