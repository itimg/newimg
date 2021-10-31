<?php
/*!
 * Theme Name:One Nav
 * Theme URI:https://www.iotheme.cn/
 * Author:iowen
 * Author URI:https://www.iowen.cn/
 */
if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php 
global $iodb, $customize_terms, $bookmark_id, $bookmark_set;
if($bookmark_id == 'default')
    return;
if(empty($customize_terms))
    $customize_terms = $iodb->getTerm($bookmark_id);
$is_go = ((io_get_option('is_go') && get_bookmark_seting('is_go',$bookmark_set)=='')?false:true);
if(io_get_option('customize_show') && io_get_option('customize_d_n')){
    global $post; 
        $args = array(
            'post_type'           => 'sites',  
            'ignore_sticky_posts' => 1,     
            'posts_per_page'      => -1,     
            'post__in'            => explode(',', io_get_option('customize_d_n')),    
            'orderby'             => 'post__in',     
        );
    $myposts = new WP_Query( $args );
    wp_reset_postdata();
}
$i_t = 0; 
$i_u = 0; 
?>
    <div class="container-lg bookmark-content position-relative"> 
        <section class="customize-menu sites-tabs">
            <div class="sites-tabs-container d-flex align-items-end">
                <div class="d-flex flex-fill container-lg overflow-hidden text-nowrap">
                    <div class="d-flex sites-tabs-btn">
                    <?php if(io_get_option('customize_d_n') && io_get_option('customize_show') && $myposts->have_posts()): $i_t = 1;?>
                        <a class="nav-link sites-tab-btn sites-tab active" href="#my-c-nav"><?php _e('每日推荐', 'i_theme' ); ?></a>
                    <?php endif; ?>
                    <?php if($customize_terms){
                        foreach($customize_terms as $c_term){
                            echo '<a class="nav-link'.($i_t == 0?' active':'').' sites-tab-btn sites-tab" href="#ct-'.$c_term->id.'" data-id="'.$c_term->id.'">'.$c_term->name.'</a>';
                            $i_t ++;
                        }
                    }else{ ?>
                        <a class="nav-link sites-tab-btn sites-tab<?php echo ($i_t == 0?' active':'') ?>" href="#ct-1"><?php _e('我的导航', 'i_theme' ); ?></a><?php } ?> 
                        <a class="nav-link sites-tab-btn sites-tab" href="#my-star"><?php _e('我的收藏', 'i_theme' ); ?></a> 
                        <span class="sites-tab-slider d-none d-md-block"></span>
                    </div> 
                    <div id="sites-btn-dropdown" class="ml-auto">
                        <a class="nav-link" href="#" data-toggle="dropdown"><i class="iconfont icon-dian"></i></a>
                        <div class="dropdown-menu dropdown-menu-right bg-dark text-center sites-btn-dropdown-list" style="min-width:auto">
                            <div class="dropdown-divider sites-btn-dropdown-divider"></div>
                            <a class="dropdown-item" href="<?php echo esc_url(home_url("/user/stars")) ?>" target="_blank"><?php _e('管理','i_theme') ?></a>
                        </div>
                    </div>
                </div> 
            </div> 
        </section>
        <main id="cust-sites" class="tab-content customize-sites-list mt-4">
            <?php if(io_get_option('customize_d_n') && io_get_option('customize_show') && $myposts->have_posts()):  $i_u = 1;?>
            <div id="my-c-nav" class="sites-pane mb-4 active">                    
                <div class="row <?php echo io_get_option("hot_card_mini")?"row-sm":"" ?>">
                <?php    
                while ($myposts->have_posts()): $myposts->the_post(); 
                ?>
                    <div class="url-card col-6 <?php get_columns() ?> <?php echo before_class($post->ID) ?> ">
                    <?php include( get_theme_file_path('/templates/card-sitemini.php') ); ?>
                    </div>
                <?php endwhile; ?>
                </div> 
            </div> 
            <?php endif;  ?> 
            <?php   
            if($customize_terms){
                foreach($customize_terms as $c_term){?>
                    <div id="ct-<?php echo $c_term->id ?>" class="customize-sites sites-pane mb-4<?php echo ($i_u == 0?' active':'') ?>">   
                        <div class="ct-name"><i class="iconfont icon-category mr-2"></i><?php echo $c_term->name ?></div>                 
                        <div class="site-list row <?php echo io_get_option("hot_card_mini")?"row-sm":"" ?>" data-term_id="<?php echo $c_term->id ?>" >
                        <?php
                        $c_urls = $iodb->getUrlWhereTerm($bookmark_id,$c_term->id);
                        if($c_urls){ 
                            $default_ico = get_theme_file_uri('/images/favicon.png');
                            foreach($c_urls as $c_url){ 
                                $ico = $c_url->url_ico?:(io_get_option('ico-source')['ico_url'] .format_url($c_url->url) . io_get_option('ico-source')['ico_png']);
                            ?> 
                            <div id="url-<?php echo $c_url->id ?>" class="url-card sortable col-6 <?php get_columns() ?> ">
                                <div class="url-body mini">
                                    <a href="<?php echo go_to($c_url->url,$is_go) ?>" target="_blank" class="card new-site mb-3 site-<?php echo $c_url->id ?>" data-id="<?php echo $c_url->id ?>" data-url="<?php echo $c_url->url ?>" title="<?php echo $c_url->url_name ?>" <?php echo  nofollow($c_url->url,false,true) ?>>
                                        <div class="card-body" style="padding:0.4rem 0.5rem;">
                                            <div class="url-content d-flex align-items-center">
                                                <div class="url-img rounded-circle mr-2 d-flex align-items-center justify-content-center">
                                                    <?php if(io_get_option('lazyload')): ?>
                                                    <img class="lazy" src="<?php echo $default_ico; ?>" data-src="<?php echo $ico ?>" onerror="javascript:this.src='<?php echo $default_ico; ?>'" alt="<?php echo $c_url->url_name ?>">
                                                    <?php else: ?>
                                                    <img class="" src="<?php echo $ico ?>" onerror="javascript:this.src='<?php echo $default_ico; ?>'" alt="<?php echo $c_url->url_name ?>">
                                                    <?php endif ?>
                                                </div>
                                                <div class="url-info flex-fill">
                                                    <div class="text-sm overflowClip_1">
                                                        <strong><?php echo $c_url->url_name ?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <a href="javascript:;" class="text-center remove-cm-site" data-action="delete_custom_url" data-id="<?php echo $c_url->id ?>" data-name="<?php echo $c_url->url_name ?>" style="display: none"><i class="iconfont icon-close-circle"></i></a>
                            </div> 
                            <?php } ?>
                        <?php 
                        }else{ ?>
                            <div class="col-lg-12 customize_nothing<?php echo ($i_u == 0?' custom-site':'') ?>">
                                <div class="nothing mb-3"><?php _e('没有数据！', 'i_theme' ); ?></div>
                            </div>
                        <?php }
                        $i_u++;
                        ?>
                        </div> 
                    </div> 
                <?php }
            } else { ?>
            <div id="ct-1" class="sites-pane mb-4<?php echo ($i_u == 0?' active':'') ?>">                    
                <div class="row <?php echo io_get_option("hot_card_mini")?"row-sm":"" ?>">
                    <div class="col-lg-12 customize_nothing custom-site">
                        <div class="nothing mb-3"><?php _e('没有数据！', 'i_theme' ); ?></div>
                    </div>
                </div> 
            </div> 
            <?php } ?>


            <div id="my-star" class="sites-pane mb-4">            
                <div class="row <?php echo io_get_option("hot_card_mini")?"row-sm":"" ?> my-star-list">   
                    <?php 
                    $sites_ids = io_get_user_star_post_ids($bookmark_id,'sites');
                    if($sites_ids){
                        global $post; 
                        $args = array(
                            'post_type'           => 'sites',  
                            'ignore_sticky_posts' => 1,     
                            'posts_per_page'      => -1,     
                            'post__in'            => $sites_ids,    
                            'orderby'             => 'post__in',     
                        );
                        $mystars = new WP_Query( $args );
                        ?>
                        <?php if($mystars->have_posts()): while ($mystars->have_posts()):  $mystars->the_post();  ?>
                                <div class="url-card col-6 <?php get_columns() ?> <?php echo before_class($post->ID) ?> ">
                                <?php include( get_theme_file_path('/templates/card-sitemini.php') ); ?>
                                </div>
                        <?php endwhile; endif; wp_reset_postdata();
                    }else{ ?>
                    <div class="col-lg-12 customize_nothing_star">
                        <div class="nothing mb-3"><?php _e('您还没用收藏内容！等待你的参与哦 ^_^', 'i_theme' ); ?></div>
                    </div> 
                    <?php }?>
                </div> 
            </div>  
        </main>
    </div>
