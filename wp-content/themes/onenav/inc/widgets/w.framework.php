<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-10 21:26:22
 * @LastEditors: iowen
 * @LastEditTime: 2021-09-14 23:20:20
 * @FilePath: \onenav\inc\widgets\w.framework.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
function loadWidget($path, $safe = false)
{    
    require_once get_theme_file_path('/inc/widgets/'.$path.'.php');
}
add_action('admin_head', 'widget_icon');
function widget_icon() {
?>
<style type="text/css">   
[id*="about"] h3:before,       
[id*="advert"] h3:before,      
[id*="new_cat"] h3:before,  
[id*="new_bulletin"] h3:before,  
[id*="hot_comment"] h3:before, 
[id*="hot_post_img"] h3:before, 
[id*="hot_sites"] h3:before, 
[id*="random_sites"] h3:before, 
[id*="cx_tag_cloud"] h3:before,   
[id*="related_post"] h3:before,    
[id*="io_hot_apps"] h3:before,   
[id*="io_hot_books"] h3:before, 
[id*="random_post"] h3:before {
    content:'';
    margin-top: -2px;
    margin-right: 8px;
    width: 15px;
    display: inline-block;
    height: 11px;
    vertical-align: middle;
    background:url(data:image/svg+xml;base64,PHN2ZyBpZD0i5Zu+5bGCXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgdmlld0JveD0iMCAwIDEwMCAxMDAiPjxzdHlsZT4uc3Qwe2ZpbGw6I2Q0MjZlOH08L3N0eWxlPjxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik02Ni44OCAzN2gtMjBjLTcuMTggMC0xMyA1LjgyLTEzIDEzczUuODIgMTMgMTMgMTNoMjBjNy4xOCAwIDEzLTUuODIgMTMtMTNzLTUuODItMTMtMTMtMTN6Ii8+PHBhdGggY2xhc3M9InN0MCIgZD0iTTkwLjMyIDE1LjU2SDkuNjhBNC42OCA0LjY4IDAgMCAwIDUgMjAuMjRWMjVoMTEuODh2NTBINXY0Ljc2YzAgMi41OCAyLjEgNC42OCA0LjY4IDQuNjhoODAuNjRjMi41OCAwIDQuNjgtMi4xIDQuNjgtNC42OFYyMC4yNGMwLTIuNTktMi4xLTQuNjgtNC42OC00LjY4em0xLjU2IDM0LjU2QzkxLjg4IDYzLjg2IDgwLjc0IDc1IDY2Ljk5IDc1SDQ2Ljc2Yy0xMy43NCAwLTI0Ljg4LTExLjE0LTI0Ljg4LTI0Ljg4di0uMjNDMjEuODggMzYuMTQgMzMuMDIgMjUgNDYuNzYgMjVoMjAuMjNjMTMuNzQgMCAyNC44OCAxMS4xNCAyNC44OCAyNC44OHYuMjR6Ii8+PC9zdmc+) no-repeat center;
    background-size:100%;
}
</style>
<?php 
}

/* 载入小工具 */
loadWidget('w.hot.apps');
loadWidget('w.hot.books');
loadWidget('w.hot.sites');
loadWidget('w.hot.post');
loadWidget('w.random.sites');

// TODO 最近更新过的内容