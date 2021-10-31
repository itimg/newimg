<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:56:01
 * @LastEditors: iowen
 * @LastEditTime: 2021-09-28 17:22:24
 * @FilePath: \onenav\templates\bookmark\bm.quick.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $iodb, $customize_terms, $bookmark_id, $bookmark_set;
$quick_begin = '<section class="quick-sites position-relative"><div class="container text-center px-5"><div class="row">';
$quick_after = '</div></div></section>';
if($bookmark_id == 'default'){
    if( io_get_option('customize_d_n') ){
        global $post; 
        $args = array(
            'post_type'           => 'sites',  
            'ignore_sticky_posts' => 1,     
            'posts_per_page'      => 12,     
            'post__in'            => explode(',', io_get_option('customize_d_n')),    
            'orderby'             => 'post__in',     
        );
        $myposts = new WP_Query( $args );
        if ($myposts->have_posts()):
        echo $quick_begin;
        while ($myposts->have_posts()): $myposts->the_post();  
            $sites_meta=get_sites_card_meta(); 
            $url = $sites_meta['link_url']?:get_permalink();
            ?>
            <a class="sites-btn col-3 col-md-2 text-center mb-4" target="_blank" href="<?php echo go_to($url) ?>" title="<?php the_title() ?>" <?php echo  nofollow($url,false,true) ?>>
                <div class="d-flex mb-2">
                <div class="sites-icon mx-auto ub-blur-bg">
                    <?php if(io_get_option('lazyload')): ?>
                    <img class="lazy" src="<?php echo $sites_meta['default_ico'] ?>" data-src="<?php echo $sites_meta['ico'] ?>" onerror="javascript:this.src='<?php echo $sites_meta['default_ico'] ?>'" alt="<?php the_title() ?>">
                    <?php else: ?>
                    <img class="" src="<?php echo $sites_meta['ico'] ?>" onerror="javascript:this.src='<?php echo $sites_meta['default_ico'] ?>'" alt="<?php the_title() ?>">
                    <?php endif ?>
                </div>
                </div>
                <div class="sites-title ub-blur-bg px-2 text-xs overflowClip_1"><span><?php the_title() ?></span></div>
            </a>
        <?php endwhile; 
        echo $quick_after;
        endif;
        wp_reset_postdata();
    }
}else{ // 添加自定义方法
    $terms_id=get_bookmark_seting('quick_nav',$bookmark_set);
    if($terms_id=='' && empty($customize_terms))
        $customize_terms = $iodb->getTerm($bookmark_id); 
    if($terms_id=='' && $customize_terms)
        $terms_id = $customize_terms[0]->id;
    if($terms_id!=''){ 
        $c_urls = $iodb->getUrlWhereTerm($bookmark_id,$terms_id,12);
        if($c_urls){ 
            echo $quick_begin;
            $default_ico = get_theme_file_uri('/images/favicon.png');
            foreach($c_urls as $c_url){
                $ico = $c_url->url_ico?:(io_get_option('ico-source')['ico_url'] .format_url($c_url->url) . io_get_option('ico-source')['ico_png']);
    ?>
        <a class="sites-btn col-3 col-md-2 text-center mb-4" target="_blank" href="<?php echo go_to($c_url->url) ?>" title="<?php echo $c_url->url_name ?>" <?php echo  nofollow($c_url->url,false,true) ?>>
            <div class="d-flex mb-2">
            <div class="sites-icon mx-auto ub-blur-bg">
                <?php if(io_get_option('lazyload')): ?>
                <img class="lazy" src="<?php echo $default_ico; ?>" data-src="<?php echo $ico ?>" onerror="javascript:this.src='<?php echo $default_ico; ?>'" alt="<?php echo $c_url->url_name ?>">
                <?php else: ?>
                <img class="" src="<?php echo $ico ?>" onerror="javascript:this.src='<?php echo $default_ico; ?>'" alt="<?php echo $c_url->url_name ?>">
                <?php endif ?>
            </div>
            </div>
            <div class="sites-title ub-blur-bg px-2 text-xs overflowClip_1"><span><?php echo $c_url->url_name ?></span></div>
        </a>
    <?php 
            }
            echo $quick_after;
        }
    } 
}
if(!is_user_logged_in() && $bookmark_id == 'default'){ ?> 
<div class="container mt-3">
    <div class="row">
        <div class="col-12 col-lg-8 customize_nothing_star mx-auto">
            <div class="nothing ub-blur-bg">
                <i class="iconfont icon-smiley icon-3x"></i>
                <div class="mt-2"><?php _e('登录后可拥有自定义书签页','i_theme')?></div>
                <a href="<?php echo io_add_redirect(home_url('/login/'), io_get_current_url()) ?>" class="btn mt-2 btn-light" title="登录"><i class="iconfont icon-user mr-2"></i> <?php _e('登录','i_theme')?></a>
            </div>
        </div>  
    </div>  
</div>  
<?php }?> 
