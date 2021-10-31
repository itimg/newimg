<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:56:02
 * @LastEditors: iowen
 * @LastEditTime: 2021-09-28 17:20:50
 * @FilePath: \onenav\templates\cat-list.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<div class="site-title mb-4">
<?php if ( is_category() ) { ?> 
    <h1 class="text-gray text-lg mb-2">
        <i class="site-tag iconfont icon-tag icon-lg mr-1" id="<?php single_cat_title() ?>"></i><?php single_cat_title() ?>
    </h1>
    <p class="text-secondary text-sm">
        <?php echo strip_tags(trim(category_description())); ?>
    </p>
<?php } ?>
<?php if ( is_tag() ) { ?> 
    <h1 class="text-gray text-lg mb-2">
        <i class="site-tag iconfont icon-tag icon-lg mr-1" id="<?php single_cat_title() ?>"></i><?php _e('标签：','i_theme') ?><?php single_cat_title() ?>
    </h1>
    <p class="text-secondary text-sm">
        <?php echo strip_tags(trim(tag_description())); ?>
    </p>
<?php } ?> 
<?php if ( is_author() ) { ?>
    <h1 class="text-gray text-lg mb-2">
        <i class="site-tag iconfont icon-tag icon-lg mr-1"></i><?php _e('作者：','i_theme') ?><?php echo get_the_author() ?>
    </h1>
    <p class="text-secondary text-sm">
        <?php if(get_the_author_meta('description')){ echo the_author_meta( 'description' );}else{echo __('我还没有学会写个人说明！','i_theme'); }?>
    </p>
<?php } ?>
<?php 
global $is_blog;
if(isset($is_blog)) {
    $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
    $args = array(
        'ignore_sticky_posts' => 1,
        'paged' => $paged,
    );
    query_posts( $args ); ?> 
    <h1 class="text-gray text-lg mb-2">
        <i class="site-tag iconfont icon-tag icon-lg mr-1"></i><?php _e('最新文章','i_theme') ?>
    </h1>
<?php 
}
?>

</div>
<div class="cat_list">
    <?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post();?>
    <div class="list-grid list-grid-padding">
        <div class="list-item card">
            <div class="media media-3x2 rounded col-4 col-md-4">
                <?php if(io_get_option('lazyload')): ?>
                <a class="media-content" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" data-src="<?php echo io_theme_get_thumb() ?>"></a>
                <?php else: ?>
                <a class="media-content" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" style="background-image: url(<?php echo io_theme_get_thumb() ?>);"></a>
                <?php endif ?>
            </div>
            <div class="list-content">
                <div class="list-body">
                    <h2>
                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="list-title text-lg overflowClip_2"><?php show_sticky_tag( is_sticky() ) . show_new_tag(get_the_time('Y-m-d H:i:s')) ?><?php the_title(); ?></a>
                    </h2>
                    <div class="list-desc d-none d-md-block text-sm text-secondary my-3">
                        <div class="overflowClip_2 "><?php echo io_get_excerpt(150) ?></div>
                    </div>
                </div>
                <div class="list-footer">
                    <div class="d-flex flex-fill align-items-center text-muted text-xs">
                        <?php
                        $category = get_the_category();
                        if($category[0]){   ?>
                        <span><i class="iconfont icon-classification"></i>
                            <a href="<?php echo get_category_link($category[0]->term_id ) ?>"><?php echo $category[0]->cat_name ?></a>
                        </span>
                        <?php } ?>
                    
                        <div class="flex-fill"></div>
                        <div>
                            <time class="mx-1"><?php echo timeago( get_the_time('Y-m-d G:i:s') ) ?></time>
                        </div>
                    </div>        
                </div>
            </div>
        </div>                            
    </div> 
    <?php endwhile; endif;?>
</div>
<div class="posts-nav">
    <?php echo paginate_links(array(
        'prev_next'          => 0,
        'before_page_number' => '',
        'mid_size'           => 2,
    ));
    wp_reset_query();
    ?>
</div>
