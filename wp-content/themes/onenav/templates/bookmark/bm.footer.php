<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-04-10 16:08:29
 * @LastEditors: iowen
 * @LastEditTime: 2021-09-12 00:16:55
 * @FilePath: \onenav\templates\bookmark\bm.footer.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }?>
<?php global $bookmark_id; ?>
    <?php if(io_get_option('ad_footer_s')) echo '<div class="container apd apd-footer">' . stripslashes( io_get_option('ad_footer') ) . '</div>'; ?> 
    <footer class="main-footer footer-type-1 position-relative text-xs">
        <div id="footer-tools" class="d-flex flex-column">
            <a href="javascript:" id="go-to-up" class="btn rounded-circle go-up m-1" rel="go-top">
                <i class="iconfont icon-to-up"></i>
            </a>
            <?php if(is_user_logged_in() && $bookmark_id=='default'){  global $current_user; ?>
            <a href="<?php echo esc_url(home_url('/bookmark/'.base64_io_encode(sprintf("%08d", $current_user->ID)))) ?>" class="btn rounded-circle m-1" data-toggle="tooltip" data-placement="left" title="<?php _e('我的书签','i_theme') ?>" target="_blank">
                <i class="iconfont icon-tags"></i>
            </a>
            <?php } ?>
            <a href="<?php echo esc_url(home_url()) ?>" class="btn rounded-circle m-1" data-toggle="tooltip" data-placement="left" title="<?php _e('首页','i_theme') ?>" target="_blank">
                <i class="iconfont icon-home"></i>
            </a>
        </div>
        <div class="footer-inner text-center text-light my-3">
            <div class="footer-text">
                <?php if(io_get_option('footer_copyright')) : 
                    echo io_get_option('footer_copyright')."&nbsp;&nbsp;".io_get_option('footer_statistics');
                ?>
                <?php else: ?>
                Copyright © <?php echo date('Y') ?> <?php bloginfo('name'); ?> <?php if(io_get_option('icp')) echo '<a href="https://beian.miit.gov.cn/" target="_blank" rel="link noopener">' . io_get_option('icp') . '</a>'?>
                &nbsp;&nbsp;Design by <a href="https://www.iowen.cn" target="_blank"><strong>一为</strong></a>&nbsp;&nbsp;<?php echo io_get_option('footer_statistics') ?>
                <?php endif; ?>
            </div>
        </div>
    </footer>
</div><!-- page-container end -->
<?php wp_footer(); ?> 
<!-- 自定义代码 -->
<?php echo io_get_option('code_2_footer');?>
<!-- end 自定义代码 -->
</body>
</html>
