<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:55:57
 * @LastEditors: iowen
 * @LastEditTime: 2021-09-12 00:16:50
 * @FilePath: \onenav\footer.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }?>
<?php
if($bm_route = get_query_var('bookmark_id')){
	get_template_part( 'templates/bookmark/bm.footer' ); 
    exit;
}
?>
            <?php 
            if(io_get_option('ad_footer_s')) 
                echo '<div class="container apd apd-footer">' . stripslashes( io_get_option('ad_footer') ) . '</div>'; 
            /**
             * -----------------------------------------------------------------------
             * HOOK : ACTION HOOK
             * io_before_footer
             * 
             * 在<footer>前挂载其他菜单。
             * @since  3.xxx
             * -----------------------------------------------------------------------
             */
            do_action( 'io_before_footer' );
            ?> 
            <footer class="main-footer footer-type-1 text-xs">
                <div id="footer-tools" class="d-flex flex-column">
                    <a href="javascript:" id="go-to-up" class="btn rounded-circle go-up m-1" rel="go-top">
                        <i class="iconfont icon-to-up"></i>
                    </a>
                    <?php if( io_get_option('search_position') && in_array("tool",io_get_option('search_position')) ){ ?>
                    <a href="javascript:" data-toggle="modal" data-target="#search-modal" class="btn rounded-circle m-1" rel="search">
                        <i class="iconfont icon-search"></i>
                    </a>
                    <?php } ?>
                    <?php if(io_get_option('weather') && io_get_option('weather_location')=='footer'){ ?>
                    <!-- 天气  -->
                    <div class="btn rounded-circle weather m-1">
                        <div id="he-plugin-simple" style="display: contents;"></div>
                        <script>WIDGET = {CONFIG: {"modules": "02","background": "5","tmpColor": "888","tmpSize": "14","cityColor": "888","citySize": "14","aqiSize": "14","weatherIconSize": "24","alertIconSize": "18","padding": "7px 2px 7px 2px","shadow": "1","language": "auto","fixed": "false","vertical": "middle","horizontal": "left","key": "a922adf8928b4ac1ae7a31ae7375e191"}}</script>
                        <script>
                        loadFunc(function() {
                            let script = document.createElement("script");
                            script.setAttribute("async", "");
                            script.src = "//widget.qweather.net/simple/static/js/he-simple-common.js?v=2.0";
                            document.body.appendChild(script);
                        });
                        </script>
                    </div>
                    <!-- 天气 end -->
                    <?php } ?>
                    <?php if(io_get_option('user_center')){
                        if(is_user_logged_in()){  global $current_user; ?>
                    <a href="<?php echo esc_url(home_url('/bookmark/'.base64_io_encode(sprintf("%08d", $current_user->ID)))) ?>" class="btn rounded-circle m-1" data-toggle="tooltip" data-placement="left" title="<?php _e('我的书签','i_theme') ?>" target="_blank">
                        <i class="iconfont icon-tags"></i>
                    </a>
                    <?php } ?>
                    <?php  ?>
                    <a href="<?php echo esc_url(home_url('/bookmark/')) ?>" class="btn rounded-circle m-1" data-toggle="tooltip" data-placement="left" title="<?php _e('mini 书签','i_theme') ?>">
                        <i class="iconfont icon-minipanel"></i>
                    </a>
                    <?php } ?>
                    <a href="javascript:" id="switch-mode" class="btn rounded-circle switch-dark-mode m-1" data-toggle="tooltip" data-placement="left" title="<?php _e('夜间模式','i_theme') ?>">
                        <i class="mode-ico iconfont icon-light"></i>
                    </a>
                </div>
                <div class="footer-inner">
                    <div class="footer-text">
                        <?php if(io_get_option('footer_copyright')) : 
                            echo io_get_option('footer_copyright')."&nbsp;&nbsp;".io_get_option('footer_statistics');
                        ?>
                        <?php else: ?>
                        Copyright © <?php echo date('Y') ?> <?php bloginfo('name'); ?> <?php if(io_get_option('icp')) echo '<a href="https://beian.miit.gov.cn/" target="_blank" rel="link noopener">' . io_get_option('icp') . '</a>'?> 
                        <?php if($police_icp = io_get_option('police_icp')){ if(preg_match('/\d+/',$police_icp,$arr)){echo ' <a href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode='.$arr[0].'" target="_blank" rel="noopener"><img style="margin-bottom: 3px;" src="'.get_theme_file_uri('/images/gaba.png').'"> ' . $police_icp . '</a>'; }} ?>
                        &nbsp;&nbsp;Designed by <a href="https://www.iotheme.cn" target="_blank"><strong>一为</strong></a>&nbsp;&nbsp;<?php echo io_get_option('footer_statistics') ?>
                        <?php endif; ?>
                    </div>
                </div>
            </footer>
            <?php 
            /**
             * -----------------------------------------------------------------------
             * HOOK : ACTION HOOK
             * io_after_footer
             * 
             * 在</footer>后挂载其他菜单。
             * @since  3.xxx
             * -----------------------------------------------------------------------
             */
            do_action( 'io_after_footer' );
            ?>
        </div><!-- main-content end -->
    </div><!-- page-container end -->
<?php if(io_get_option('search_position') && ( in_array("top",io_get_option('search_position')) || in_array("tool",io_get_option('search_position')) ) ){ ?>  
<div class="modal fade search-modal" id="search-modal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">  
            <div class="modal-body">
                <?php get_template_part( 'templates/search/modal' ); ?>  
                <div class="px-1 mb-3"><i class="text-xl iconfont icon-hot mr-1" style="color:#f1404b;"></i><span class="h6"><?php _e('热门推荐：','i_theme') ?> </span></div>
                <div class="mb-3">
                    <?php wp_menu("search_menu") ?>
                </div>
            </div>  
            <div style="position: absolute;bottom: -40px;width: 100%;text-align: center;"><a href="javascript:" data-dismiss="modal"><i class="iconfont icon-close-circle icon-2x" style="color: #fff;"></i></a></div>
        </div>
    </div>  
</div>
<?php } ?>
<?php wp_footer(); ?> 
<!-- 自定义代码 -->
<?php echo io_get_option('code_2_footer');?>
<!-- end 自定义代码 -->
</body>
</html>
