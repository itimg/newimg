<?php
/*!
 * Theme Name:One Nav
 * Theme URI:https://www.iotheme.cn/
 * Author:iowen
 * Author URI:https://www.iowen.cn/
 */

 // https://s0.wp.com/mshots/v1/www.google.com?w=383&h=328  网址截图
 
if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php while( have_posts() ): the_post();?>
            <div class="row site-content py-4 py-md-5 mb-xl-5 mb-0 mx-xxl-n5">
                <?php get_template_part( 'templates/fx' ); ?>
                <!-- 网址信息 -->
                            <div class="col-12 col-sm-5 col-md-4 col-lg-3">
                                <?php 
                                $m_link_url  = get_post_meta(get_the_ID(), '_sites_link', true);  
                                $imgurl = get_post_meta_img(get_the_ID(), '_thumbnail', true);
                                $is_preview = false;
                                if($imgurl == '' || io_get_option('sites_preview') ){
                                    if( $m_link_url != '' || ($sites_type == "sites" && $m_link_url != '') ){
                                        if(!io_get_option('sites_preview')){
                                            $imgurl = (io_get_option('ico-source')['ico_url'] .format_url($m_link_url) . io_get_option('ico-source')['ico_png']);
                                        }else{
                                            $imgurl = '//s0.wp.com/mshots/v1/'. format_url($m_link_url,true) .'?w=383&h=328';
                                            $is_preview = true;
                                        }
                                    }
                                    elseif($sites_type == "wechat")
                                        $imgurl = get_theme_file_uri('/images/qr_ico.png');
                                    else
                                        $imgurl = get_theme_file_uri('/images/favicon.png');
                                }
                                $sitetitle = get_the_title();
                                $views = function_exists('the_views')? the_views(false) :  '0' ;
                                ?>
                                <div class="siteico">
                                    <?php if(!$is_preview){ ?>
                                    <div class="blur blur-layer" style="background: transparent url(<?php echo $imgurl ?>) no-repeat center center;-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover;animation: rotate 30s linear infinite;"></div>
                                    <img class="img-cover" src="<?php echo $imgurl ?>" alt="<?php echo $sitetitle ?>" title="<?php echo $sitetitle ?>">
                                    <?php } ?>
                                    <?php if($is_preview){ 
                                        if(io_get_option('lazyload')){
                                            echo '<img class="img-cover lazy" src="'.get_theme_file_uri('/images/t.png').'" alt="'. $sitetitle .'" data-src="'. $imgurl .'" title="'. $sitetitle .'">';
                                        }else{
                                            echo '<img class="img-cover" src="'.$imgurl.'" alt="'. $sitetitle .'" title="'. $sitetitle .'">';
                                        }
                                    } ?>
                                    <?php if($country = get_post_meta(get_the_ID(),'_sites_country', true)) {
                                        echo '<div id="country" class="text-xs custom-piece_c_b country-piece loadcountry"><i class="iconfont icon-globe mr-1"></i>'.$country.'</div>';
                                    }else{
                                        echo '<div id="country" class="text-xs custom-piece_c_b country-piece" style="display:none;"><i class="iconfont icon-loading icon-spin"></i></div>';
                                    }
                                    ?>
                                    <div class="tool-actions text-center mt-md-4">
                                        <?php like_button(get_the_ID()) ?>
                                        <a href="javascript:;" class="btn-share-toggler btn btn-icon btn-light rounded-circle p-2 mx-3 mx-md-2" data-toggle="tooltip" data-placement="top" title="<?php _e('浏览','i_theme') ?>">
                                            <span class="flex-column text-height-xs">
                                                <i class="icon-lg iconfont icon-chakan"></i>
                                                <small class="share-count text-xs mt-1"><?php echo $views ?></small>
                                            </span>
                                        </a> 
                                    </div>
                                </div>
                            </div>
                            <div class="col mt-4 mt-sm-0">
                                <div class="site-body text-sm">
                                    <?php 
                                    $terms = get_the_terms( get_the_ID(), 'favorites' ); 
                                    if( !empty( $terms ) ){
                                        foreach( $terms as $term ){
                                            if($term->parent != 0){
                                                $parent_category = get_term( $term->parent );
                                                echo '<a class="btn-cat custom_btn-d mb-2" href="' . esc_url( get_category_link($parent_category->term_id)) . '">' . esc_html($parent_category->name) . '</a>';
                                                echo '<i class="iconfont icon-arrow-r-m mr-n1 custom-piece_c" style="font-size:50%;color:#f1404b;vertical-align:0.075rem"></i>';
                                                break;
                                            }
                                        } 
                                        foreach( $terms as $term ){
                                            $name = $term->name;
                                            $link = esc_url( get_term_link( $term, 'favorites' ) );
                                            echo " <a class='btn-cat custom_btn-d mb-2' href='$link'>".$name."</a>";
                                        }
                                    }  
                                    ?>
                                    <h1 class="site-name h3 my-3"><?php echo $sitetitle ?><?php edit_post_link(__('编辑','i_theme'), '<span class="edit-link text-xs text-muted ml-2">', '</span>' ); ?>
                                    <?php $language = get_post_meta(get_the_ID(), '_sites_language', true); if($m_link_url!="" && $language && !find_character($language,['中文','汉语','zh','cn','简体']) ){ ?>
                                        <a class="text-xs" href="//fanyi.baidu.com/transpage?query=<?php echo format_url($m_link_url,true) ?>&from=auto&to=zh&source=url&render=1" target="_blank" rel="nofollow noopener noreferrer"><?php _e('翻译站点','i_theme') ?><i class="iconfont icon-wailian text-ss"></i></a>
                                    <?php } ?>
                                    </h1>
                                    <div class="mt-2">
                                        <?php 
                                        $width = 150;
                                        $m_post_link_url = $m_link_url ?: get_permalink(get_the_ID());
                                        $qrurl = "<img src='".str_ireplace(array('$size','$url'),array($width,$m_post_link_url),io_get_option('qr_url'))."' width='{$width}'>";
                                        $qrname = __("手机查看",'i_theme');
                                        if(get_post_meta_img(get_the_ID(), '_wechat_qr', true) || $sites_type == 'wechat'){
                                            $m_qrurl = get_post_meta_img(get_the_ID(), '_wechat_qr', true);
                                            if($m_qrurl == "")
                                                $qrurl = '<p>'.__('居然没有添加二维码','i_theme').'</p>';
                                            else 
                                                $qrurl = "<img src='".$m_qrurl."' width='{$width}'>";
                                            $qrname = get_post_meta(get_the_ID(),'_is_min_app', true) ? __("小程序",'i_theme') : __("公众号",'i_theme');
                                        }
                                        ?>
                                        <p class="mb-2"><?php echo io_get_excerpt(170,'_sites_sescribe') ?></p> 
                                        <?php the_terms( get_the_ID(), 'sitetag',__('标签：','i_theme').'<span class="mr-1">', '<i class="iconfont icon-wailian text-ss"></i></span> <span class="mr-1">', '<i class="iconfont icon-wailian text-ss"></i></span>' ); ?>
                                        <?php 
                                        if($sites_type == "sites" && io_get_option('url_rank')){
                                            $aizhan = home_url().'/go/?url='.base64_encode('https://baidurank.aizhan.com/baidu/'.format_url($m_link_url,true));
                                            echo '<div class="mt-2">爱站权重：';
                                            echo '<span class="mr-2">PC <a href="'. $aizhan .'" title="百度权重" target="_blank"><img class="" src="//baidurank.aizhan.com/api/br?domain='.format_url($m_link_url,true).'&style=images" alt="百度权重" title="百度权重" style="height:18px"></a></span>';
                                            echo '<span class="mr-2">移动 <a href="'. $aizhan .'" title="百度移动权重" target="_blank"><img class="" src="//baidurank.aizhan.com/api/mbr?domain='.format_url($m_link_url,true).'&style=images" alt="百度移动权重" title="百度移动权重" style="height:18px"></a></span>';
                                            echo '</div>';
                                        }
                                        ?>
                                        
                                        <div class="site-go mt-3">
                                            <?php if($m_link_url!=""): ?>
                                                <div id="security_check_img"></div>
                                                <?php //theUrlStatus($m_link_url); ?>
                                                <span class="site-go-url">
                                                <a style="margin-right: 10px;" href="<?php echo go_to($m_link_url) ?>" title="<?php echo $sitetitle ?>" target="_blank" class="btn btn-arrow"><span><?php _e('链接直达','i_theme') ?><i class="iconfont icon-arrow-r-m"></i></span></a>
                                                </span>
                                            <?php endif; ?>
                                            <a href="javascript:" class="btn btn-arrow qr-img"  data-toggle="tooltip" data-placement="bottom" title="" data-html="true" data-original-title="<?php echo $qrurl ?>"><span><?php echo $qrname ?><i class="iconfont icon-qr-sweep"></i></span></a>
                                        </div>
                                        <?php if($spare_link =get_post_meta(get_the_ID(),'_spare_sites_link', true)) { ?>
                                            <div class="spare-site mb-3"> 
                                            <i class="iconfont icon-url"></i><span class="mr-3"><?php _e('其他站点:','i_theme') ?></span>
                                            <?php for ($i=0;$i<count($spare_link);$i++) { ?>
                                            <a class="mb-2 mr-3" href="<?php echo go_to($spare_link[$i]['spare_url']) ?>" title="<?php echo $spare_link[$i]['spare_note'] ?>" target="_blank" style="white-space:nowrap"><span><?php echo $spare_link[$i]['spare_name'] ?><i class="iconfont icon-wailian"></i></span></a>
                                            <?php } ?> 
                                            </div>
                                        <?php } ?>
                                                
                                        <p id="check_s" class="text-sm" style="display:none"><i class="iconfont icon-loading icon-spin"></i></p> 
                                    </div>

                                </div>
                            </div>
                <!-- 网址信息 end -->
                            <?php get_sidebar('sitestop') ?> 
            </div>
    <div class="row">
		<div class="<?php echo (is_active_sidebar( 'sidebar-sites-r' )?'col-lg-8':'col-12') ?>">
            <div class="panel site-content card transparent"> 
                <div class="card-body p-0">
                    <div class="apd-bg">
                        <?php if(io_get_option('ad_app_s')) echo '<div class="apd apd-right">' . stripslashes( io_get_option('ad_app') ) . '</div>'; ?>
                    </div> 
                    <div class="panel-body single my-4 ">
                            <?php  
                            $contentinfo = get_the_content();
                            if( $contentinfo ){
                                the_content();
                                thePostPage();
                            }else{
                                echo htmlspecialchars(get_post_meta(get_the_ID(), '_sites_sescribe', true));
                            }
                            ?>
                    </div>

                        
                </div>
            </div>
            <?php if( io_get_option('leader_board') && io_get_option('details_chart')){ //图表统计?>
            <h2 class="text-gray text-lg my-4"><i class="iconfont icon-zouxiang mr-1"></i><?php _e('数据统计','i_theme') ?></h2>
            <div class="card io-chart">
                <div class=" mt-4">
                    <div id="chart-container" class="" style="height:300px" data-type="<?php echo $sites_type ?>" data-post_id="<?php echo get_the_ID() ?>" data-nonce="<?php echo wp_create_nonce( 'post_ranking_data' ) ?>"></div>
                </div>
            </div> 
            <?php } ?>
            <?php if(io_get_option('sites_default_content')): // 网址详情页默认内容 ?>
            <h2 class="text-gray  text-lg my-4"><i class="iconfont icon-tubiaopeizhi mr-1"></i><?php _e('数据评估','i_theme') ?></h2>
            <div class="panel site-content sites-default-content card"> 
                <div class="card-body">
                    <p class="viewport">
                    <?php echo $sitetitle ?>浏览人数已经达到<?php echo $views ?>，如你需要查询该站的相关权重信息，可以点击"<a class="external" href="//www.aizhan.com/seo/<?php echo format_url($m_link_url,true) ?>" rel="nofollow" target="_blank">爱站数据</a>""<a class="external" href="//seo.chinaz.com/?q=<?php echo format_url($m_link_url,true) ?>" rel="nofollow" target="_blank">Chinaz数据</a>"进入；以目前的网站数据参考，建议大家请以爱站数据为准，更多网站价值评估因素如：<?php echo $sitetitle ?>的访问速度、搜索引擎收录以及索引量、用户体验等；当然要评估一个站的价值，最主要还是需要根据您自身的需求以及需要，一些确切的数据则需要找<?php echo $sitetitle ?>的站长进行洽谈提供。如该站的IP、PV、跳出率等！</p>
                    <div class="text-center my-2"><span class=" content-title"><span class="d-none">关于<?php echo $sitetitle ?></span>特别声明</span></div>
                    <p class="text-muted text-sm m-0">
                    本站<?php bloginfo('name'); ?>提供的<?php echo $sitetitle ?>都来源于网络，不保证外部链接的准确性和完整性，同时，对于该外部链接的指向，不由<?php bloginfo('name'); ?>实际控制，在<?php echo io_date_time($post->post_date) ?>收录时，该网页上的内容，都属于合规合法，后期网页的内容如出现违规，可以直接联系网站管理员进行删除，<?php bloginfo('name'); ?>不承担任何责任。</p>
                </div>
                <div class="card-footer text-muted text-xs">
                    <div class="d-flex"><span><?php bloginfo('name'); ?>致力于优质、实用的网络站点资源收集与分享！</span><span class="ml-auto d-none d-md-block">本文地址<?php the_permalink() ?>转载请注明</span></div>
                </div>
            </div>
            <?php endif; ?>
<?php endwhile; ?>
