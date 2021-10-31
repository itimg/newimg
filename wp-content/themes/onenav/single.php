<?php
/*!
 * Theme Name:One Nav
 * Theme URI:https://www.iotheme.cn/
 * Author:iowen
 * Author URI:https://www.iowen.cn/
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>


<?php 
get_template_part( 'templates/sidebar','nav' );
?>
<div class="main-content flex-fill page">
<?php get_template_part( 'templates/header','banner' ); ?>
<div id="content" class="container my-3 my-md-4">
    <?php get_template_part( 'templates/breadcrumb' ) ?>
    <div class="row">
        <div class="col-lg-8">
            <div class="panel card">
            <div class="card-body">
                <div class="panel-header mb-4">
                    <?php while( have_posts() ): the_post(); ?>
                    <h1 class="h3 mb-3"><?php echo get_the_title() ?></h1>
                    <div class="d-flex flex-fill text-muted text-sm pb-4 border-bottom border-t">
                    <?php 
                    $category = get_the_category();
                    if(!empty($category) && $category[0]){
                    echo '<span class="mr-3 d-none d-sm-block"><a href="'.get_category_link($category[0]->term_id ).'"><i class="iconfont icon-classification"></i> '.$category[0]->cat_name.'</a></span>';
                    }
                    ?>
                    <span class="mr-3 d-none d-sm-block"><i class="iconfont icon-time"></i> <?php echo timeago(get_the_time('Y-m-d G:i:s')); ?></span>
                    <span class="mr-3"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ) ?>" title="<?php the_author_meta('nickname') ?>"><i class="iconfont icon-user-circle"></i> <?php the_author_meta('nickname') ?></a></span>
                    <div class="flex-fill"></div>
                    <?php 
                    if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views mr-3"><i class="iconfont icon-chakan"></i> ','</span>' ); }
                    ?>
                    <span class="mr-3"><a class="smooth-n" href="#comments"> <i class="iconfont icon-comment"></i> <?php echo get_post(get_the_ID())->comment_count; ?></a></span>
                    <?php like_button(get_the_ID(),'post') ?>
                    </div>
                </div>
                <?php if(io_get_option('ad_s_title')) { ?>
                    <div class="post-apd"><?php echo stripslashes( io_get_option('ad_s_title_c') ); ?></div>
                <?php } ?>
                <div class="panel-body single mt-2"> 
                    <?php the_content() ?>
                    <?php thePostPage() ?>
                </div>
            
                <div class="tags my-2">
                    <?php
                        $post_tags = get_the_tags();
                        if ($post_tags) {
                            echo '<i class="iconfont icon-tags"></i>';
                            foreach($post_tags as $tag) {
                                echo '<a href="'.get_tag_link($tag->term_id).'" rel="tag" class="tag-' . $tag->slug . ' color-'.mt_rand(0, 8).'">' . $tag->name . '</a>';
                            }
                        }
                    ?>
                </div>
                <?php edit_post_link(__('编辑','i_theme'), '<span class="edit-link">', '</span>' ); ?>
                <?php endwhile; ?> 
                <?php if(io_get_option('ad_s_b')) { ?>
                <div class="post-apd"><?php echo stripslashes( io_get_option('ad_s_b_c') ); ?></div>
                <?php } ?>
            </div>
            </div>
            <div class="single-top-area text-sm card mt-4">
                    
                <div class="card-body"> 
                    <strong><?php _e('版权声明：','i_theme') ?></strong><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ) ?>" title="<?php bloginfo( 'name' ); ?>"><?php the_author_meta('nickname'); ?></a> <?php _e('发表于','i_theme') ?> <?php echo io_date_time($post->post_date) ?>。<br/>
                    <strong><?php _e('转载请注明：','i_theme') ?></strong><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('本文固定链接','i_theme') ?> <?php the_permalink() ?>"><?php the_title(); ?> | <?php bloginfo('name');?></a>
                </div>
                    
            </div>
            <div class="near-navigation rounded mt-4 py-2">
                <?php
                $current_category = get_the_category();//获取当前文章所属分类ID
                $prev_post = get_previous_post($current_category,'');//与当前文章同分类的上一篇文章
                $next_post = get_next_post($current_category,'');//与当前文章同分类的下一篇文章
                ?>
                <?php if (!empty( $prev_post )) { ?>
                <div class="nav previous border-right border-color">
                    <a class="near-permalink" href="<?php echo get_permalink( $prev_post->ID ); ?>">
                    <span><?php _e('上一篇','i_theme') ?></span>
                    <h4 class="near-title"><?php echo $prev_post->post_title; ?></h4>
                    </a>
                </div>
                <?php } else { ?>
                <div class="nav none border-right border-color">
                    <span><?php _e('上一篇','i_theme') ?></span>
                    <h4 class="near-title"><?php _e('没有更多了...','i_theme') ?></h4>
                </div>
                <?php } ?>
                <?php if (!empty( $next_post )) { ?>
                <div class="nav next border-left border-color">
                    <a class="near-permalink" href="<?php echo get_permalink( $next_post->ID ); ?>">
                    <span><?php _e('下一篇','i_theme') ?></span>
                    <h4 class="near-title"><?php echo $next_post->post_title; ?></h4>
                </a>
                </div>
                <?php } else { ?>
                <div class="nav none border-left border-color" style="text-align: right;">
                    <span><?php _e('下一篇','i_theme') ?></span>
                    <h4 class="near-title"><?php _e('没有更多了...','i_theme') ?></h4>    
                </div>
                <?php } ?>
            </div>
            <h4 class="text-gray text-lg my-4"><i class="site-tag iconfont icon-book icon-lg mr-1" ></i><?php _e('相关文章','i_theme') ?></h4>
            <div class="row mb-n4 customize-site"> 
                <?php get_template_part( 'templates/related','post' ); ?>
            </div>
            <?php 
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif; 
            ?>
        </div> 
        <div class="sidebar sidebar-tools col-lg-4 pl-xl-4 d-none d-lg-block">
            <?php get_sidebar(); ?>
        </div>
    </div> 
</div>
<?php get_footer(); ?>