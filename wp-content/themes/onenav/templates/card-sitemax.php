<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:56:02
 * @LastEditors: iowen
 * @LastEditTime: 2021-09-28 17:22:13
 * @FilePath: \onenav\templates\card-sitemax.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }  ?>

<?php $sites_meta=get_sites_card_meta(); 
$is_goto   = $sites_meta["sites_type"] == "sites" && get_post_meta($post->ID, "_goto", true) ? true : false;
$max_url   = 'href="'.$sites_meta["url"].'" '                                              .$sites_meta["blank"]. ' ' .($is_goto ? "" : nofollow($sites_meta["link_url"], io_get_option("details_page")));
$goto_url  = 'href="'.($is_goto?$sites_meta['link_url']:go_to($sites_meta['link_url'])). '" target="_blank" '         .($is_goto ? "" : nofollow($sites_meta["link_url"]))                               .' title="'. __("直达","i_theme") .'"';
$max_views =$sites_meta['is_views'];
$goto_views='is-views';
if( $sites_meta['link_url']!="" && !get_post_meta($post->ID, '_goto', true) && io_get_option("details_page") && io_get_option("url_reverse") ){
    $goto_url  = 'href="'.$sites_meta["url"].'" '                                              .$sites_meta["blank"]. ' ' .($is_goto ? "" : nofollow($sites_meta["link_url"], io_get_option("details_page"))).' title="'. __("详情","i_theme") .'"';
    $max_url   = 'href="'.($is_goto?$sites_meta['link_url']:go_to($sites_meta['link_url'])). '" target="_blank" '         .($is_goto ? "" : nofollow($sites_meta["link_url"]));
    $max_views ='is-views';
    $goto_views='';
}
?>
        <div class="url-body max">    
            <a <?php echo $max_url ?> data-id="<?php echo $post->ID ?>" data-url="<?php echo rtrim($sites_meta['link_url'],"/") ?>" class="card <?php echo $max_views ?> mb-4 site-<?php echo $post->ID ?>" <?php echo $sites_meta['tooltip'] . ' ' . $sites_meta['is_html'] ?> title="<?php echo $sites_meta['title'] ?>">
                <div class="card-body py-2 px-3">
                    <div class="url-content align-items-center d-flex flex-fill">
                        <?php if(!io_get_option('no_ico')) : ?>
                        <div class="url-img rounded-circle mr-2 d-flex align-items-center justify-content-center">
                            <?php if(io_get_option('lazyload')): ?>
                            <img class="lazy" src="<?php echo $sites_meta['default_ico']; ?>" data-src="<?php echo $sites_meta['ico'] ?>" onerror="javascript:this.src='<?php echo $sites_meta['default_ico']; ?>'" alt="<?php the_title() ?>">
                            <?php else: ?>
                            <img class="" src="<?php echo $sites_meta['ico'] ?>" onerror="javascript:this.src='<?php echo $sites_meta['default_ico']; ?>'" alt="<?php the_title() ?>">
                            <?php endif ?>
                        </div>
                        <?php endif; ?>
                        <div class="url-info flex-fill">
                            <div class="text-sm overflowClip_1">
                            <?php show_sticky_tag( is_sticky() ) . show_new_tag(get_the_time('Y-m-d H:i:s')) ?><strong><?php the_title() ?></strong>
                            </div>
                            <p class="overflowClip_1 text-muted text-xs"><?php echo $sites_meta['summary'] ?></p>
                        </div>
                    </div>
                    <div class="url-like"> 
                        <div class="text-muted text-xs text-center mr-1"> 
                            <?php 
                            if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="iconfont icon-chakan"></i>','</span>' ); }
                            like_home_button($post->ID); 
                            ?>
                            
                        </div>
                    </div>
                    <div class="url-goto-after mt-2"> 
                    </div>
                </div>
            </a> 
            <div class="url-goto px-3 pb-1">
                <div class="d-flex align-items-center" style="white-space:nowrap">
                    <div class="tga text-xs py-1">
                    <?php 
                    $post_tags = get_the_terms(get_the_ID(),'sitetag');
                    if(!$post_tags) $post_tags = get_the_terms(get_the_ID(),'favorites');
                    if ($post_tags) {
                        $c = count($post_tags)>4 ? 4 : count($post_tags);
                        for( $i = 0; $i < $c; $i++ ) {
                            echo '<span class="mr-1"><a href="'.get_tag_link($post_tags[$i]->term_id).'" rel="tag">' . $post_tags[$i]->name . '</a></span>';
                        }
                    } else {
                        echo '<span class="mr-1"><a class="no-tag">没添加标签</a></span>';
                    }
                    
                    ?>
                    </div>
                    <?php if($sites_meta['link_url'] != '') { ?>
                    <a <?php echo $goto_url ?> class="togo text-center text-muted <?php echo $goto_views ?>" data-id="<?php echo $post->ID ?>" data-toggle="tooltip" data-placement="right"><i class="iconfont icon-goto"></i></a>
                    <?php } ?>
                </div>
            </div>
        </div>
