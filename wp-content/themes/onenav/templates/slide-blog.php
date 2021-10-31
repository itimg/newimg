<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:56:02
 * @LastEditors: iowen
 * @LastEditTime: 2021-07-31 17:21:56
 * @FilePath: \onenav\templates\slide-blog.php
 * @Description: 文章轮播模块
 */
if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
        <div id="carousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner rounded">
                <?php 
                $count = 0;
                $img_ad=io_get_option('carousel_img');
                if($img_ad){
                    foreach($img_ad as $ad){
                        ?>
                        <div class="carousel-item home-item <?php echo( $count==0? "active":"" ) ?>">
                            <?php if(io_get_option('lazyload')): ?>
                            <a class="media-content media-title-bg" href="<?php echo $ad['url'] ?>" target="_blank" <?php echo ($ad['is_ad']?'rel="noopener"':'rel="external noopener nofollow"') ?> data-bg="url(<?php echo $ad['img'] ?>)">
                            <?php else: ?>
                            <a class="media-content media-title-bg" href="<?php echo $ad['url'] ?>" target="_blank" <?php echo ($ad['is_ad']?'rel="noopener"':'rel="external noopener nofollow"') ?>  style="background-image: url(<?php echo $ad['img'] ?>);">
                            <?php endif ?>
                                <span class="carousel-caption d-none d-md-block"><?php echo $ad['title'] ?></span>
                            </a>
                        </div>
                        <?php
                        $count++;
                    }
                }
                $query_post = array(
                    'post_type' => 'post',
                    'posts_per_page' => io_get_option('article_n'),
                    'post__in'       => get_option('sticky_posts'),
                    'ignore_sticky_posts' => 1,
                );
                $the_query = new WP_Query($query_post);
                if(!$the_query->have_posts()){
                    wp_reset_postdata(); 
                    $query_post = array(
                        'post_type' => 'post',
                        'posts_per_page' => io_get_option('article_n'),
                        'ignore_sticky_posts' => 1,
                    );
                    $the_query = new WP_Query($query_post);
                }
                ?> 
                <?php while($the_query->have_posts()):$the_query->the_post(); ?>
                    <div class="carousel-item home-item <?php echo( $count==0? "active":"" ) ?>">
                        <?php if(io_get_option('lazyload')): ?>
                        <a class="media-content media-title-bg" href="<?php the_permalink(); ?>" <?php echo new_window() ?> data-bg="url(<?php echo io_theme_get_thumb() ?>)">
                        <?php else: ?>
                        <a class="media-content media-title-bg" href="<?php the_permalink(); ?>" <?php echo new_window() ?>  style="background-image: url(<?php echo io_theme_get_thumb() ?>);">
                        <?php endif ?>
                            <span class="carousel-caption d-none d-md-block"><?php the_title(); ?></span>
                        </a>
                    </div>
                <?php $count++ ?>
                <?php endwhile; wp_reset_postdata(); ?>
            </div> 
            <ol class="carousel-indicators carousel-blog">
            <?php for ($i=0; $i<($count); $i++) { ?>
                <li data-target="#carousel" data-slide-to="<?php echo $i ?>" class="<?php echo( $i==0? "active":"" ) ?>"></li>
            <?php } ?>
            </ol>
        </div>
