<?php
/*!
 * Theme Name:One Nav
 * Theme URI:https://www.iotheme.cn/
 * Author:iowen
 * Author URI:https://www.iowen.cn/
 */
if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if(io_get_option('customize_card')){ 
    global $iodb;
    $user_id = get_current_user_id();
    $customize_terms = $iodb->getTerm($user_id,io_get_option('customize_count'));
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
    //if(io_get_option("tab_ajax"))
?>
        <div class="d-flex flex-fill customize-menu">
            <div class="d-flex slider-menu-father">
            <div class='overflow-x-auto slider_menu mini_tab' sliderTab="sliderTab" >
                <ul class="nav nav-pills menu ajax-cm-home" role="tablist"> 
                    <?php if(io_get_option('customize_d_n') && io_get_option('customize_show') && $myposts->have_posts()): $i_t = 1;?>
                    <li class="pagenumber nav-item">
                        <a class="nav-link active"  data-toggle="pill" href="#my-c-nav"><?php _e('每日推荐', 'i_theme' ); ?></a>
                    </li><?php endif; ?>
                    <?php if($customize_terms){
                        foreach($customize_terms as $c_term){
                            echo '<li class="pagenumber nav-item">
                            <a class="nav-link'.($i_t == 0?' active':'').' ajax-cm"  data-toggle="pill" href="#ct-'.$c_term->id.'" data-action="load_home_customize_tab" data-id="'.$c_term->id.'">'.$c_term->name.'</a>
                            </li>';
                            $i_t ++;
                        }
                    }else{ ?><li class="pagenumber nav-item">
                        <a class="nav-link<?php echo ($i_t == 0?' active':'') ?>"  data-toggle="pill" href="#ct-1"><?php _e('我的导航', 'i_theme' ); ?></a>
                    </li><?php } ?><li class="pagenumber nav-item">
                        <a class="nav-link"  data-toggle="pill" href="#my-star"><?php _e('我的收藏', 'i_theme' ); ?></a>
                    </li><li class="pagenumber nav-item">
                        <a class="nav-link"  data-toggle="pill" href="#my-click"><?php _e('最近使用', 'i_theme' ); ?></a>
                    </li>
                </ul>
            </div> 
            </div> 
            <div class="flex-fill"></div>
            <a class='btn-edit text-xs ml-2' href="javascript:;"><?php _e('编辑', 'i_theme' ); ?></a>
        </div>
        <div id="cust-sites" class="tab-content mt-4">
            <?php if(io_get_option('customize_d_n') && io_get_option('customize_show') && $myposts->have_posts()):  $i_u = 1;?>
            <div id="my-c-nav" class="tab-pane active">                    
                <div class="row <?php echo io_get_option("hot_card_mini")?"row-sm":"" ?>">
                <?php    
                while ($myposts->have_posts()): $myposts->the_post(); 
                ?>
                    <div class="url-card col-6 <?php get_columns() ?> <?php echo before_class($post->ID) ?> col-xxl-10a">
                    <?php include( get_theme_file_path('/templates/card-sitemini.php') ); ?>
                    </div>
                <?php endwhile; ?>
                </div> 
            </div> 
            <?php endif;  ?> 
            <?php   
            if($customize_terms){
                foreach($customize_terms as $c_term){?>
                    <div id="ct-<?php echo $c_term->id ?>" class="customize-sites tab-pane<?php echo ($i_u == 0?' active':'') ?>">                    
                        <div class="site-list row <?php echo io_get_option("hot_card_mini")?"row-sm":"" ?>" data-term_id="<?php echo $c_term->id ?>" >
                        <?php
                        if(io_get_option("tab_ajaxs")): //TODO 判断始终为 false，考虑是否开放设置---------------------------------------
                        $c_urls = $iodb->getUrlWhereTerm($user_id,$c_term->id);
                        if($c_urls){ 
                            $default_ico = get_theme_file_uri('/images/favicon.png');
                            foreach($c_urls as $c_url){ 
                                $ico = $c_url->url_ico?:(io_get_option('ico-source')['ico_url'] .format_url($c_url->url) . io_get_option('ico-source')['ico_png']);
                            ?> 
                            <div id="url-<?php echo $c_url->id ?>" class="url-card sortable col-6 <?php get_columns() ?> col-xxl-10a">
                                <div class="url-body mini">
                                    <a href="<?php echo go_to($c_url->url) ?>" target="_blank" class="card new-site mb-3 site-<?php echo $c_url->id ?>" data-id="<?php echo $c_url->id ?>" data-url="<?php echo $c_url->url ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $c_url->url_name ?>" <?php echo  nofollow($c_url->url,false,true) ?>>
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
                            <div class="url-card col-6 <?php get_columns() ?> col-xxl-10a add-custom-site" data-term_id="<?php echo $c_term->id ?>" style="display: none">
                                <a class="btn p-0 rounded mb-3" data-toggle="modal" data-target="#addSite" style="background: rgba(136, 136, 136, 0.1);width: 100%;text-align: center;border: 2px dashed rgba(136, 136, 136, 0.5);">
                                    <div class="text-lg"  style="padding:0.22rem 0.5rem;">
                                        +
                                    </div>
                                </a>
                            </div> 
                        <?php 
                        }else{ ?>
                            <div class="col-lg-12 customize_nothing<?php echo ($i_u == 0?' custom-site':'') ?>">
                                <div class="nothing mb-4"><?php _e('没有数据！点右上角编辑添加网址', 'i_theme' ); ?></div>
                            </div>
                            <div class="url-card col-6 <?php get_columns() ?> col-xxl-10a add-custom-site" data-term_id="<?php echo $c_term->id ?>" style="display: none">
                                <a class="btn p-0 rounded mb-3" data-toggle="modal" data-target="#addSite" style="background: rgba(136, 136, 136, 0.1);width: 100%;text-align: center;border: 2px dashed rgba(136, 136, 136, 0.5);">
                                    <div class="text-lg"  style="padding:0.22rem 0.5rem;">
                                        +
                                    </div>
                                </a>
                            </div> 
                        <?php }
                        else:
                            if($i_u == 0){ 
                            $c_urls = $iodb->getUrlWhereTerm($user_id,$c_term->id);
                            if($c_urls){ 
                                $default_ico = get_theme_file_uri('/images/favicon.png');
                                foreach($c_urls as $c_url){ 
                                    $ico = $c_url->url_ico?:(io_get_option('ico-source')['ico_url'] .format_url($c_url->url) . io_get_option('ico-source')['ico_png']);
                                ?> 
                                <div id="url-<?php echo $c_url->id ?>" class="url-card sortable col-6 <?php get_columns() ?> col-xxl-10a">
                                    <div class="url-body mini">
                                        <a href="<?php echo go_to($c_url->url) ?>" target="_blank" class="card new-site mb-3 site-<?php echo $c_url->id ?>" data-id="<?php echo $c_url->id ?>" data-url="<?php echo $c_url->url ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $c_url->url_name ?>" <?php echo  nofollow($c_url->url,false,true) ?>>
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
                                <div class="url-card col-6 <?php get_columns() ?> col-xxl-10a add-custom-site" data-term_id="<?php echo $c_term->id ?>" style="display: none">
                                    <a class="btn p-0 rounded mb-3" data-toggle="modal" data-target="#addSite" style="background: rgba(136, 136, 136, 0.1);width: 100%;text-align: center;border: 2px dashed rgba(136, 136, 136, 0.5);">
                                        <div class="text-lg"  style="padding:0.22rem 0.5rem;">
                                            +
                                        </div>
                                    </a>
                                </div> 
                            <?php 
                            }else{ ?>
                                <div class="col-lg-12 customize_nothing<?php echo ($i_u == 0?' custom-site':'') ?>">
                                    <div class="nothing mb-4"><?php _e('没有数据！点右上角编辑添加网址', 'i_theme' ); ?></div>
                                </div>
                                <div class="url-card col-6 <?php get_columns() ?> col-xxl-10a add-custom-site" data-term_id="<?php echo $c_term->id ?>" style="display: none">
                                    <a class="btn p-0 rounded mb-3" data-toggle="modal" data-target="#addSite" style="background: rgba(136, 136, 136, 0.1);width: 100%;text-align: center;border: 2px dashed rgba(136, 136, 136, 0.5);">
                                        <div class="text-lg"  style="padding:0.22rem 0.5rem;">
                                            +
                                        </div>
                                    </a>
                                </div> 
                            <?php }
                            }else{
                                echo '<div class="col-lg-12 customize_nothing"><div class="nothing mb-4"><i class="iconfont icon-loading icon-spin mr-2"></i>'. __('加载中...', 'i_theme' ).'</div></div>';
                            }
                        endif;
                        $i_u++;
                        ?>
                        </div> 
                    </div> 
                <?php }
            } else { ?>
            <div id="ct-1" class="tab-pane<?php echo ($i_u == 0?' active':'') ?>">                    
                <div class="row <?php echo io_get_option("hot_card_mini")?"row-sm":"" ?>">
                    <div class="col-lg-12 customize_nothing custom-site">
                        <div class="nothing mb-4"><?php _e('没有数据！点右上角编辑添加网址', 'i_theme' ); ?></div>
                    </div>
                    <div class="url-card col-6 <?php get_columns() ?> col-xxl-10a add-custom-site"  data-term_id="-1" style="display: none">
                        <a class="btn p-0 rounded mb-3" data-toggle="modal" data-target="#addSite" style="background: rgba(136, 136, 136, 0.1);width: 100%;text-align: center;border: 2px dashed rgba(136, 136, 136, 0.5);">
                            <div class="text-lg"  style="padding:0.22rem 0.5rem;">
                                +
                            </div>
                        </a>
                    </div> 
                </div> 
            </div> 
            <?php } ?>


            <div id="my-star" class="tab-pane">            
                <div class="row <?php echo io_get_option("hot_card_mini")?"row-sm":"" ?> my-star-list">   
                    <?php 
                    $sites_ids = io_get_user_star_post_ids($user_id,'sites');
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
                                <div class="url-card col-6 <?php get_columns() ?> <?php echo before_class($post->ID) ?> col-xxl-10a">
                                <?php include( get_theme_file_path('/templates/card-sitemini.php') ); ?>
                                </div>
                        <?php endwhile; endif; wp_reset_postdata();
                    }else{ ?>
                    <div class="col-lg-12 customize_nothing_star">
                        <div class="nothing mb-4"><?php _e('您还没用收藏内容！等待你的参与哦 ^_^', 'i_theme' ); ?></div>
                    </div> 
                    <?php }?>
                </div> 
            </div> 
            <div id="my-click" class="tab-pane">            
                <div class="row <?php echo io_get_option("hot_card_mini")?"row-sm":"" ?> my-click-list">   
                    <div class="col-lg-12 customize_nothing_click">
                        <div class="nothing mb-4"><?php _e('没有数据！等待你的参与哦 ^_^', 'i_theme' ); ?></div>
                    </div>  
                </div> 
            </div>  
        </div>
    <!-- 模态框 -->
    <div class="modal fade add_new_sites_modal" id="addSite">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">  
                <div class="modal-body">
                    <div class="modal-title text-center mb-4"><span class="text-lg"><?php _e('添加自定义网址', 'i_theme' ) ?></span><br><a href="<?php echo esc_url(home_url('/user/sites')) ?>" class="text-xs">[<?php _e('前往用户中心管理网址更方便', 'i_theme' ) ?>]</a></div>
                    <form class="add-custom-site-form" action="javascript:;">
                        <div class="form-row">
                            <div class="col-12 mb-2">
                            <p class="mb-2"><?php _e('选择分类：','i_theme') ?></p>
                            <?php if($customize_terms){
                                foreach($customize_terms as $c_term){
                                echo '<div class="form-check form-check-inline my-2">
                                <input class="form-check-input form_custom_term_id" type="radio" name="term_id" id="term_id-'.$c_term->id.'" value="'.$c_term->id.'">
                                <label class="form-check-label" for="term_id-'.$c_term->id.'">'.$c_term->name.'</label>
                                </div>';
                            }}else{
                                echo "";
                            } ?> 
                            </div> 
                            <div class="col-12 mb-2"><input type="text" class="site-add-name form-control" name="term_name" placeholder="<?php _e('或者自定义分类', 'i_theme' ); ?>"></div>
                            <div class="border-bottom my-4"></div> 
                            <div class="col-12 col-md-6 mb-2">
                                <input id="modal-new-url" type="url" class="site-add-url form-control" name="url" value="http://" required="required">
                                <i id="modal-new-url-ico" class="iconfont icon-loading icon-spin position-absolute" style="top:10px;color:red;right:10px;display:none;text-shadow:0 0 6px red"></i>
                            </div>
                            <div class="col-12 col-md-6 mb-2"><input type="text" class="site-add-name form-control" name="url_name" placeholder="<?php _e('网站名称', 'i_theme' ); ?>" required="required"></div>
                            <div class="col-12 mb-2">
                                <textarea id="modal-new-url-summary" class="form-control" name="url_summary" placeholder="<?php _e('输入简介（选填）','i_theme') ?>"></textarea>
                                <div class="invalid-feedback"><i class="iconfont icon-tishi"></i> <?php _e('网址信息获取失败，请再试试，或者手动填写。','i_theme') ?></div>
                            </div>
                            <input type="hidden" name="user_id"  value="<?php echo $user_id ?>">
                            <?php wp_nonce_field('add_custom_site_form'); ?>
                            <div class="col-12 text-center mt-4">
                                <button type="submit" class="btn btn-danger btn-xs mr-3"><?php _e('添加', 'i_theme' ); ?></button>
                                <button type="button" class="btn-close-fm btn btn-dark btn-xs" data-dismiss="modal"><?php _e('取消', 'i_theme' ); ?></button>
                            </div>
                            <div class="col-12 text-center mt-2">
                                <a href="<?php echo esc_url(home_url('/user/sites')) ?>" class="text-xs text-muted"><i class="iconfont icon-upload mr-1"></i><?php _e('导入 chrome 书签', 'i_theme' ); ?></a>
                            </div>
                        </div>
                    </form>
                </div>  
            </div>
        </div>
    </div>
<?php } ?>
