<?php
global $set;
echo '<script>console.log("\n %c CorePress主题v ' . THEME_VERSIONNAME . ' %c by applek | www.lovestu.com", "color:#fff;background:#409EFF;padding:5px 0;", "color:#eee;background:#444;padding:5px 10px;");
</script>';
/*吃水不忘挖井人，请勿删除版权，让更多人使用，作者才有动力更新下去
删版权可能会导致网站运行bug，视为放弃一切技术支持
*/

if ($set['code']['footcode'] != null) {
    echo base64_decode($set['code']['footcode']);
}
if ($set['code']['alifront'] != null) {
    echo '<script src="' . $set['code']['alifront'] . '"></script>';
}
?>
<div class="go-top-plane" title="返回顶部">
    <i class="fa fa-arrow-up" aria-hidden="true"></i>
</div>


<div class="footer-plane">
    <div class="footer-container">
        <div class="footer-left">
            <div>
                <?php dynamic_sidebar('footer_widget'); ?>
                <?php
                get_template_part('component/nav-footer');
                //吃水不忘挖井人，如果要免费使用，禁止删除CorePress Theme版权
                //删版权可能会导致网站运行bug，视为放弃一切技术支持
                ?>
                <div class="footer-info">
                    Copyright © 2021 <?php bloginfo('name'); ?>
                    <span class="theme-copyright"><a href="https://www.lovestu.com/corepress.html" target="_blank">CorePress</a>
                </span>
                    Powered by WordPress
                </div>
                <div class="footer-info">
                    <?php
                    if ($set['routine']['icp'] != null) {
                        echo '<span class="footer-icp"><img class="ipc-icon" src="' . file_get_img_url('icp.svg') . '" alt=""><a href="https://beian.miit.gov.cn/" target="_blank">' . $set['routine']['icp'] . '</a></span>';
                    }
                    if ($set['routine']['police'] != null) {
                        echo '<span class="footer-icp"><img class="ipc-icon" src="' . file_get_img_url('police.svg') . '" alt=""><a href="http://www.beian.gov.cn/portal/registerSystemInfo/" target="_blank">' . $set['routine']['police'] . '</a></span>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="footer-details footer-right">
            <div>
                <?php
                dynamic_sidebar('footer_widget_right');
                ?>
            </div>

        </div>
        <div>
            <?php wp_footer(); ?>
        </div>

    </div>
</div>

