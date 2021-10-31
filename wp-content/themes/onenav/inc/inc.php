<?php
/*!
 * Theme Name:One Nav
 * Theme URI:https://www.iotheme.cn/
 * Author:iowen
 * Author URI:https://www.iowen.cn/
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * 禁止自动生成 768px 缩略图
 */
function shapeSpace_customize_image_sizes($sizes) {
    unset($sizes['medium_large']);
    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'shapeSpace_customize_image_sizes');
/**
 * wordpress禁用图片属性srcset和sizes
 */
add_filter( 'add_image_size', function(){return 1;} );
add_filter( 'wp_calculate_image_srcset_meta', '__return_false' );
add_filter( 'big_image_size_threshold', '__return_false' );

/**
 * 禁止WordPress自动生成缩略图
 */
function ztmao_remove_image_size($sizes) {
    unset( $sizes['small'] );
    unset( $sizes['medium'] );
    unset( $sizes['large'] );
    return $sizes;
}
add_filter('image_size_names_choose', 'ztmao_remove_image_size');
/**
 * 古腾堡编辑器样式
 */
function block_editor_styles() {
    wp_enqueue_style( 'block-editor-style', get_theme_file_uri( '/css/editor-blocks.css' ), array(), VERSION );
}
function initialization(){
    io_add_db_table();
} 
function io_add_db_table() {
    global $wpdb;
    //if($wpdb->has_cap('collation')) {
    //    if(!empty($wpdb->charset)) {
    //        $table_charset = "DEFAULT CHARACTER SET $wpdb->charset";
    //    }
    //    if(!empty($wpdb->collate)) {
    //        $table_charset .= " COLLATE $wpdb->collate";
    //    }
    //}
    $charset_collate = $wpdb->get_charset_collate();
    // TODO `meta` text DEFAULT NULL,
    if($wpdb->get_var("show tables like '$wpdb->iomessages'") != $wpdb->iomessages) {
        $sql = "CREATE TABLE $wpdb->iomessages (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `user_id` bigint(20) DEFAULT NULL COMMENT '收件人',
            `sender_id` bigint(20) DEFAULT NULL COMMENT '发件人',
            `sender` varchar(50) DEFAULT NULL COMMENT '发件人名称',
            `msg_type` varchar(20) DEFAULT NULL,
            `msg_date` datetime DEFAULT NULL,
            `msg_title` text,
            `msg_content` text,
            `meta` text DEFAULT NULL,
            `msg_read` text DEFAULT NULL,
            `msg_status` varchar(20) DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY uid_index (`user_id`),
            KEY sid_index (`sender_id`)
        )$charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    if($wpdb->get_var("show tables like '$wpdb->iocustomurl'") != $wpdb->iocustomurl) {
        $sql = "CREATE TABLE $wpdb->iocustomurl (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `user_id` bigint(20) DEFAULT NULL,
            `term_id` bigint(20) NOT NULL DEFAULT 0,
            `post_id` bigint(20) DEFAULT NULL,
            `url` text DEFAULT NULL,
            `url_name` varchar(50) DEFAULT NULL,
            `url_ico` text DEFAULT NULL,
            `summary` varchar(255) DEFAULT NULL,
            `date` datetime DEFAULT NULL,
            `order` int(11) NOT NULL DEFAULT 0,
            `status` int(11) NOT NULL DEFAULT 1,
            PRIMARY KEY (`id`),
            KEY `user_id` (`user_id`),
            KEY `term_id` (`term_id`),
            KEY `url_name` (`url_name`)
        )$charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    if($wpdb->get_var("show tables like '$wpdb->iocustomterm'") != $wpdb->iocustomterm) {
        $sql = "CREATE TABLE $wpdb->iocustomterm (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `name` varchar(50) DEFAULT NULL,
            `ico` varchar(255) DEFAULT NULL,
            `user_id` bigint(20) DEFAULT NULL,
            `order` int(11) NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`),
            KEY `user_id` (`user_id`)
        )$charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    if($wpdb->get_var("show tables like '$wpdb->ioviews'") != $wpdb->ioviews) {
        $sql = "CREATE TABLE $wpdb->ioviews (
            `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `time` date NOT NULL,
            `post_id` bigint(20) NOT NULL,
            `type` varchar(50) NOT NULL,
            `desktop` int(11) NOT NULL,
            `mobile` int(11) NOT NULL,
            `download` int(11) NOT NULL,
            `count` int(11) NOT NULL,
            PRIMARY KEY (`id`),
            KEY `post_id` (`post_id`),
            KEY `type` (`type`),
            KEY `time` (`time`)
        )$charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    if(!$wpdb->query("SELECT io_id FROM $wpdb->users")){
        $wpdb->query("ALTER TABLE $wpdb->users ADD io_id varchar(100)");
    }
    update_option('io_add_db_tables', 31009 );
}
if( is_admin() && get_option( 'io_add_db_tables',0 )!=31009){
    io_add_db_table(); 
}
# 激活友情链接模块
# --------------------------------------------------------------------
if(io_get_option('show_friendlink'))add_filter( 'pre_option_link_manager_enabled', '__return_true' );
require_once get_theme_file_path('/inc/post-type.php');
if(io_get_option('save_image')) require_once get_theme_file_path('/inc/save-image.php');
if( io_get_option('post_views') ) require_once get_theme_file_path('/inc/postviews/postviews.php');
global $iodb; $iodb = new IODB();
# 获取CSF框架图片
# --------------------------------------------------------------------
function get_post_meta_img($post_id, $key, $single){
    $metas = get_post_meta($post_id, $key, $single);
    if(is_array($metas)){
        return $metas['url'];
    } else {
        return $metas;
    }
}
function get_search_list(){
    if(io_get_option('custom_search')){
        /**
         * #TODO 次级导航自定义搜索
        if(is_page_template('template-mininav.php') &&  $search_id = get_post_meta( get_the_ID(), '_search_id', true )){
            $search_list = get_option('io_search_list'); 
            foreach( $search_list['custom_search_list'] as $v){ 
                if($v['search_list_id']==$search_id){
                    return isset($v['search_list'])?$v['search_list']:$search_list['search_list']; 
                } 
            }
        }else{
            return get_option('io_search_list')['search_list'];
        }
        */
        return get_option('io_search_list')['search_list'];
    }else{
        include( get_theme_file_path('/inc/search-list.php') ); 
        return $search_list;
    }
}
function get_book_type_name($type){
    switch($type){
        case "books":
            $name = __('图书','i_theme');
            break;
        case "periodical":
            $name = __('期刊','i_theme');
            break;
        case "movie":
            $name = __('电影','i_theme');
            break;
        case "tv":
            $name = __('电视剧','i_theme');
            break;
        case "video":
            $name = __('小视频','i_theme');
            break;
        default:
            $name = __('图书','i_theme');
            break;
    }
    return $name;
}
function get_app_type_name($type){
    switch($type){
        case "app":
            $name = __('软件','i_theme');
            break;
        case "down":
            $name = __('资源','i_theme');
            break;
        default:
            $name = __('资源','i_theme');
            break;
    }
    return $name;
}
# 网站块类型（兼容1.0）
# --------------------------------------------------------------------
function before_class($post_id){
    $metas      = get_post_meta_img($post_id, '_wechat_qr', true);
    $sites_type = get_post_meta($post_id, '_sites_type', true);
    if($metas != '' || $sites_type == "wechat"){
        return 'wechat';
    } elseif($sites_type == "down") {
        return 'down';
    } else {
        return '';
    }
}
# 添加菜单
# --------------------------------------------------------------------
function wp_menu($location){
    if ( function_exists( 'wp_nav_menu' ) && has_nav_menu($location) ) {
        $nav_id = get_nav_menu_locations()[$location];
        $cache_key = 'io_menu_list_'.$nav_id;
        //$nav_menu = wp_cache_get( $cache_key );
        $nav_menu = get_transient( $cache_key );
        if ( false === $nav_menu ) { 
            ob_start();
            wp_nav_menu( array( 'container' => false, 'items_wrap' => '%3$s', 'theme_location' => $location ) );
            $nav_menu = ob_get_contents();
            ob_end_clean();
            //wp_cache_set( $cache_key, $nav_menu ); 
            set_transient( $cache_key, $nav_menu, 24 * HOUR_IN_SECONDS ); 
        }
        echo $nav_menu;
    } else {
        if (current_user_can('manage_options')) { 
            if($location == 'search_menu')
                echo '<li><a href="'.get_option('siteurl').'/wp-admin/nav-menus.php">'.__('请到[后台->外观->菜单]中添加“搜索推荐”菜单。','i_theme').'</a></li>';
            else
                echo '<li><a href="'.get_option('siteurl').'/wp-admin/nav-menus.php">'.__('请到[后台->外观->菜单]中设置菜单。','i_theme').'</a></li>';
        }
    }
}
/**
 * 添加统计数据
 * 
 * @param int $post_id
 * @param string $type
 * @param bool $is_mobile
 * @param int $count 增加的值
 * @param string $action 数据类型 view down
 * @param string $time
 * @return object
 */
function io_add_post_view($post_id,$type,$is_mobile,$count =1,$action='view',$time=''){
    global $iodb;
    if($time==''){
        date_default_timezone_set('Asia/Shanghai');
        $time = date('Y-m-d');
    }
    if($action=='down')
        return $iodb->addViews( $post_id, $type, $time, 0, 0, 1, 0 ); 
    $desktop = 0;
    $mobile  = 0;
    if($is_mobile)
        $mobile  = $count;
    else 
        $desktop = $count;
    return $iodb->addViews( $post_id, $type, $time, $desktop, $mobile, 0, $count ); 
}
function io_get_post_view($post_id,$time=''){
    global $iodb;
    if($time==''){
        date_default_timezone_set('Asia/Shanghai');
        $time = date('Y-m-d');
    }

}
/**
 * 获取排行榜
 * 
 * @param string $time 时间 today yesterday month all
 * @param string $type 类型 sites app book post
 * @param int $count 数量，默认10
 * @return *
 */
function io_get_post_rankings($time, $type, $count=10){
    global $iodb;
    $_is_go = get_post_meta( get_the_ID(), '_url_go', true );
    date_default_timezone_set('Asia/Shanghai');
    switch($time){
        case "today":
            $sql = $iodb->getDayRankings(date('Y-m-d'),$type,$count);
            break;
        case "yesterday":
            $sql = $iodb->getDayRankings(date("Y-m-d",strtotime("-1 day")),$type,$count);
            break;
        case "month":
            $sql = $iodb->getRangeRankings(date('Y-m-d'),$type,'m',$count);
            break;
        case "all":
        default:
            $sql = 'all';
            break;
    }
    if( $sql!='all'){
        $m_post = $sql;
        if($m_post){
            $_post_ids  = array();
            $post_view  = array();
            foreach($m_post as $post){
                $_post_ids[]                = $post->post_id;
                $post_view[$post->post_id]  = $post->count;
            }
            $args = array(   
                'post_type'           => $type,             
                'post__in'            => $_post_ids,
                'orderby'             => 'post__in', 
                'ignore_sticky_posts' => 1,
                //'nopaging'            => true,
                'posts_per_page'      => $count, 
            );
            $myposts = new WP_Query( $args );
            $_post = get_rankings_data($myposts,$type,$_is_go,$post_view);
            wp_reset_postdata();
            return $_post;
        }else{
            return '';
        }
    }else{
        $args = array(   
            'post_type'           => $type,             
            'posts_per_page'      => $count,  
            'ignore_sticky_posts' => 1,
            'meta_key'            => 'views',
            'orderby'             => array( 'meta_value_num' => 'DESC', 'date' => 'DESC' ),
        );
        $myposts = new WP_Query( $args );
        $_post = get_rankings_data($myposts,$type,$_is_go);
        wp_reset_postdata();
        return $_post;
    }
}
function get_rankings_data($myposts,$type,$_is_go,$post_view=0){
    $index = 1;
    $_post = array();
    if($myposts->have_posts()):while ($myposts->have_posts()): $myposts->the_post(); 
        $url = get_permalink();
        $post_id = get_the_ID();
        $is_go = false;
        if($type=='sites'){
            $sites_type = get_post_meta($post_id, '_sites_type', true);
            if($sites_type == 'sites'){
                if(!io_get_option('details_page') || (io_get_option('details_page')&&$_is_go)){
                    $url   = get_post_meta($post_id, '_sites_link', true);
                    $is_go = true;
                }
            }
        }
        if($post_view==0)
            $views = get_post_meta( $post_id, 'views', true );
        else 
            $views = $post_view[$post_id];
        $_post[] = array(
            "index"     => $index,
            "id"        => $post_id,
            "title"     => get_the_title(),
            "url"       => $url,
            "is_go"     => $is_go,
            "views"     => io_number_format($views),
        );
        $index++;
    endwhile;endif;
    return $_post;
}
/**
 * 每天执行的定时任务
 *
 */
function io_setup_common_daily_schedule()
{
    if (io_get_option('leader_board') && !wp_next_scheduled('io_setup_common_daily_event')) {
        // 1620162305是北京2021年5月5日05:05:05时间戳
        wp_schedule_event('1620162305', 'daily', 'io_setup_common_daily_event');
    }
}
add_action('wp', 'io_setup_common_daily_schedule');
/**
 * 自动删除排行榜历史事件.
 * 
 * @return bool
 */
function io_auto_delete_close_order()
{
    global $wpdb;
    $day = io_get_option('how_long');
    $sql = "DELETE FROM {$wpdb->ioviews} where DATEDIFF(curdate(), `time`)>{$day}";
    // `time` < date_sub(curdate(), INTERVAL 30 DAY 
    mysqli_query($link, $sql) or die('删除数据出错：' . mysqli_error($link));
    $delete = $wpdb->query($sql);
    return (bool) $delete;
}
add_action('io_setup_common_daily_event', 'io_auto_delete_close_order');
/**
 * 对于部分链接，拒绝搜索引擎索引.
 * 
 * @param string $output Robots.txt内容
 * @param bool   $public
 * @return string
 */
function io_robots_modification($output, $public)
{
    $site_url = parse_url( home_url() );
    $path     = ( ! empty( $site_url['path'] ) ) ? $site_url['path'] : '';
    $output  .= "Disallow: $path/bookmark/\n";
    $output  .= "Disallow: $path/user\n";
    $output  .= "Disallow: $path/hotnews\n";
    return $output;
}
add_filter('robots_txt', 'io_robots_modification', 10, 2);

/**
 * 过滤公告帖子地址
 * 
 * @description: post_type_link 自定义帖子，post_link post帖子
 * @param * $permalink
 * @param * $post
 * @return *
 */
function io_suppress_post_link( $permalink, $post ) {
    if($post->post_type=='bulletin'){
        if($goto = get_post_meta($post->ID,'_goto',true)){
            if(get_post_meta($post->ID,'_is_go',true)){
                $permalink = go_to($goto);
            }else{
                $permalink = $goto;
            }
        }
    }else{
        global $queried_object_id; 
        if(is_page_template('template-mininav.php') || ($queried_object_id && defined( 'DOING_AJAX' ) && DOING_AJAX) ){
            $post_id = get_queried_object_id()?:$queried_object_id;
            $permalink = $permalink.'?menu-id='.get_post_meta( $post_id, 'nav-id', true ).'&mininav-id='.$post_id;
        }
    }
    return $permalink;
}
add_filter( 'post_type_link', 'io_suppress_post_link', 10, 2 );
add_filter( 'post_link', 'io_suppress_post_link', 10, 2 );

/**
 * 显示置顶标签
 * ******************************************************************************************************
 */
function show_sticky_tag($isSticky){
    $span = '';
    if($isSticky && io_get_option('sticky_tag')['switcher'])
        $span = '<span class="badge badge-danger text-ss mr-1" title="'.__('置顶','i_theme').'">'.io_get_option('sticky_tag')['name'].'</span>';
    echo $span;
}
/**
 * 显示 NEW 标签
 * ******************************************************************************************************
 */
function show_new_tag($post_date){
    $span = '';
    if(io_get_option('new_tag')['switcher']){
        $t2=date("Y-m-d H:i:s");
        $t3=io_get_option('new_tag')['date']*24;
        $diff=(strtotime($t2)-strtotime($post_date))/3600;
        if($diff<$t3){ 
            $span = '<span class="badge badge-danger text-ss mr-1" title="'.__('新','i_theme').'">'.io_get_option('new_tag')['name'].'</span>'; 
        }
    }
    echo $span;
}
/**
 * 编辑器增强
 * ******************************************************************************************************
 */
function enable_more_buttons($buttons) { 
    $buttons[] = 'fontselect';
    $buttons[] = 'fontsizeselect'; 
    $buttons[] = 'styleselect';
    $buttons[] = 'wp_page'; 
    $buttons[] = 'backcolor';
    return $buttons;
}
add_filter( "mce_buttons_2", "enable_more_buttons" );
/**
 * 主题切换
 * ******************************************************************************************************
 */
function theme_mode(){
    $default_c = io_get_option('theme_mode');
    if($default_c == 'io-black-mode')
        $default_c = '';
    if (isset($_COOKIE['io_night_mode']) && $_COOKIE['io_night_mode'] != '') {
        return(trim($_COOKIE['io_night_mode']) == '0' ? 'io-black-mode' : $default_c); 
    } elseif (io_get_option('theme_mode')) {
        return io_get_option('theme_mode');
    } else { 
        return(trim($_COOKIE['io_night_mode']) == '0' ? 'io-black-mode' : $default_c); 
    }
}
/**
 * 在启用WP_CACHE的情况下切换主题状态
 */
function dark_mode_js(){
    if( !defined( 'WP_CACHE' ) || !WP_CACHE )
        return; 
    echo '<script type="text/javascript">
    var default_c = "'.io_get_option('theme_mode').'";
    var night = document.cookie.replace(/(?:(?:^|.*;\s*)io_night_mode\s*\=\s*([^;]*).*$)|^.*$/, "$1"); 
    if(night == "1"){
        document.body.classList.remove("io-black-mode");
        document.body.classList.add(default_c);
    }else if(night == "0"){
        document.body.classList.remove(default_c);
        document.body.classList.add("io-black-mode");
    }
    </script> '; 
}
/**
 * 获取自定义菜单列表
 * ******************************************************************************************************
 */
function get_menu_list( $theme_location ) {
    if ( is_numeric($theme_location) || (has_nav_menu($theme_location) && ($theme_location) && ($locations = get_nav_menu_locations()) && isset($locations[$theme_location])) ) {
        $nav_id = is_numeric($theme_location)?$theme_location:$locations[$theme_location];
        $cache_key = 'io_menu_list_'. $nav_id;
        //$io_menu_list = wp_cache_get( $cache_key );
        $io_menu_list = get_transient( $cache_key );
        if ( false === $io_menu_list ) { 
            $io_menu_list = array();
            $menu_items = wp_get_nav_menu_items($nav_id);
            foreach( $menu_items as $menu_item ) {
                if( $menu_item->menu_item_parent == 0 ) {
                    $parent = $menu_item->ID;
                    $my_parent = array();
                    foreach($menu_item as $k=>$v)
                        $my_parent[$k] = $v ;
                    $menu_array = array();
                    $bool = false;
                    foreach( $menu_items as $submenu ) {
                        if( $submenu->menu_item_parent == $parent ) {
                            $bool = true;
                            $my_submenu = array();
                            foreach($submenu as $k=>$v)
                                $my_submenu[$k] = $v ;
                            $menu_array[] = $my_submenu;
                        }
                    }
                    if( $bool == true && count( $menu_array ) > 0 ) {
                        $my_parent['submenu'] = $menu_array;
                    } else { 
                        $my_parent['submenu'] = array();
                    }
                    $io_menu_list[] = $my_parent;
                } 
            }
            //wp_cache_set( $cache_key, $io_menu_list ); 
            set_transient( $cache_key, $io_menu_list, 24 * HOUR_IN_SECONDS ); 
        }
        return $io_menu_list;
    }else{
        return array();
    }
}
/**
 * 新窗口访问
 * ******************************************************************************************************
 */
function new_window(){
    if(io_get_option('new_window'))
        return 'target="_blank"';
    else
        return '';
}
/**
 * 网址块添加 nofollow
 * noopener external nofollow
 * $details 忽略设置
 * ******************************************************************************************************
 */
function nofollow($url, $details = false, $is_blank = false){
    $ret = '';
    if($details)
        return $ret;

    if(io_get_option('is_nofollow') && !is_go_exclude($url))
        $ret .= 'external nofollow';

    if(io_get_option('new_window') ||  $is_blank)
        $ret .= ' noopener';

    if($ret == '')
        return $ret;
    else
        return 'rel="'.$ret.'"';
}
/**
 * 网址块 go 跳转
 * ******************************************************************************************************
 */
function go_to($url, $omit=false){
    if($omit)
        return $url;
    if(io_get_option('is_go')){
        if(is_go_exclude($url))
            return $url;
        else
            return esc_url(home_url()).'/go/?url='.urlencode(base64_encode($url)) ;
    }
    else
        return $url;
}
/**
 * 添加go跳转，排除白名单
 * ******************************************************************************************************
 */
function is_go_exclude($url){ 
    $exclude_links = array();
    $site = esc_url(home_url());
    if (!$site)
        $site = get_option('siteurl');
    $site = str_replace(array("http://", "https://"), '', $site);
    $p = strpos($site, '/');
    if ($p !== FALSE)
        $site = substr($site, 0, $p);/*网站根目录被排除在屏蔽之外，不仅仅是博客网址*/
    $exclude_links[] = "http://" . $site;
    $exclude_links[] = "https://" . $site;
    $exclude_links[] = 'javascript';
    $exclude_links[] = 'mailto';
    $exclude_links[] = 'skype';
    $exclude_links[] = '/';/* 有关相对链接*/
    $exclude_links[] = '#';/*用于内部链接*/

    $a = explode(PHP_EOL , io_get_option('exclude_links'));
    $exclude_links = array_merge($exclude_links, $a);
    foreach ($exclude_links as $val){
        if (stripos(trim($url), trim($val)) === 0) {
            return true;
        }
    }
    return false;
}
add_filter( 'query_vars',  'wp_link_pages_all_parameter_queryvars'  );
add_action( 'the_post',  'wp_link_pages_all_the_post'  , 0 );
function wp_link_pages_all_parameter_queryvars( $queryvars ) {
    $queryvars[] = 'view';
    return( $queryvars );
}
function wp_link_pages_all_the_post( $post ) {
    global $pages, $multipage, $wp_query;
    if ( isset( $wp_query->query_vars[ 'view' ] ) && ( 'all' === $wp_query->query_vars[ 'view' ] ) ) {
        $multipage = true;
        $post->post_content = str_replace( '<!--nextpage-->', '', $post->post_content );
        $pages = array( $post->post_content );
    }
}
# 后台检测网址状态
# --------------------------------------------------------------------
add_action('admin_bar_menu', 'invalid_prompt_menu', 1000);
function invalid_prompt_menu() {
    if( ! is_admin() ) { return; }
    $n =io_get_option('failure_valve');
    if($n != 0){
        global $wp_admin_bar;
        $menu_id = 'invalid';
        $args = array(
            'post_type' => 'sites',// 文章类型
            'post_status' => 'publish',
            'meta_key' => 'invalid', 
            'meta_type' => 'NUMERIC', 
            'meta_value' => $n,
            'meta_compare' => '>'
        );
        $invalid_items = new WP_Query( $args ); 
        if ($invalid_items->have_posts()) : 
            $wp_admin_bar->add_menu(array(
                'id' => $menu_id,  
                'title' => '<span class="update-plugins count-2" style="display: inline-block;background-color: #d54e21;color: #fff;font-size: 9px;font-weight: 600;border-radius: 10px;z-index: 26;height: 18px;margin-right: 5px;"><span class="update-count" style="display: block;padding: 0 6px;line-height: 17px;">'.$invalid_items->found_posts.'</span></span>个网址可能失效', 
                'href' => get_option('siteurl')."/wp-admin/index.php"
            ));
        endif; 
        wp_reset_postdata();
    }
}
# 网址状态面板
# --------------------------------------------------------------------
if( is_admin() && io_get_option('failure_valve')!=0 ){
    add_action('wp_dashboard_setup', 'example_add_invalid_widgets' ); 
    function example_add_invalid_widgets() {
        wp_add_dashboard_widget('custom_invalid_help', __('网址状态','io_setting'), 'custom_invalid_help');
    }
    function custom_invalid_help() {
        echo '<p><i class="dashicons-before dashicons-admin-site"></i> <span>'.__('网站收录了','io_setting').' '.wp_count_posts('sites')->publish.' '.__('个网址','io_setting').'</span></p>';
        global $post;
        $n =io_get_option('failure_valve');
        $args = array(
            'post_type' => 'sites',// 文章类型
            'post_status' => 'publish',
            'meta_key' => 'invalid', 
            'meta_type' => 'NUMERIC', 
            'meta_value' => $n,
            'meta_compare' => '>'
        );
        $invalid_items = new WP_Query( $args ); 
        if ($invalid_items->have_posts()) : 
            echo '<p><i class="dashicons-before dashicons-admin-site"></i> '.__('有','io_setting').' '.$invalid_items->found_posts.' '.__('个网址可能失效了(死链)','io_setting').'</p>';
            echo '<ul style="padding-left:20px">';
            echo '<span>'.__('失效列表：','io_setting').'</span><br>';
            while ( $invalid_items->have_posts() ) : $invalid_items->the_post();
                echo '<li style="display:inline-block;margin-right:10px"><a href="'.get_edit_post_link().'">'.get_the_title().'</a></li>';
            endwhile;
            echo '<br><span>'.__('请手动检测一下，如果没有问题，请点击对应网址进入编辑，然后修改自定义字段“invalid”的值为0','io_setting').'</span>';
            echo '</ul>';
        else:
            echo '<p><i class="dashicons-before dashicons-admin-site"></i> '.__('所有网址都可以正常访问','io_setting').'</p>';
        endif; 
        wp_reset_postdata();
    }
}
# 后台检测投稿状态
# --------------------------------------------------------------------
add_action('admin_bar_menu', 'pending_prompt_menu', 2000);
function pending_prompt_menu() {
    if( ! is_admin() ) { return; }
    global $wp_admin_bar;
    $menu_id = 'pending';
    $args = array(
        'post_type' => 'sites',// 文章类型
        'post_status' => 'pending',
    );
    $pending_items = new WP_Query( $args ); 
    if ($pending_items->have_posts()) : 
        $wp_admin_bar->add_menu(array(
            'id' => $menu_id,  
            'title' => '<span class="update-plugins count-2" style="display: inline-block;background-color: #d54e21;color: #fff;font-size: 9px;font-weight: 600;border-radius: 10px;z-index: 26;height: 18px;margin-right: 5px;"><span class="update-count" style="display: block;padding: 0 6px;line-height: 17px;">'.$pending_items->found_posts.'</span></span>个网址待审核', 
            'href' => get_option('siteurl')."/wp-admin/edit.php?post_status=pending&post_type=sites"
        ));     
    endif; 
    wp_reset_postdata();
}
# 格式化 url
# --------------------------------------------------------------------
function format_url($url,$is_format=false){
    if($url == '')
    return;
    $url = rtrim($url,"/");
    if(io_get_option('ico-source')['url_format'] || $is_format){
        $pattern = '@^(?:https?://)?([^/]+)@i';
        $result = preg_match($pattern, $url, $matches);
        return $matches[1];
    }
    else{
        return $url;
    }
} 
# 格式化数字 $precision int 精度
# --------------------------------------------------------------------
function format_number($n, $precision = 2)
{
    return $n;
    if ($n < 1e+3) {
        $out = number_format($n);
    } else {
        $out = number_format($n / 1e+3, $precision) . 'k';
    }
    return $out;
}
# 获取点赞数
# --------------------------------------------------------------------
function get_like($post_id ,$post_type = "sites"){
    if(io_get_option('user_center') && function_exists('io_get_post_star_count')){
        $type         = $post_type;
        if($post_type == "sites-down")
            $type     = "sites";
        $like_data    = io_get_post_star_count($post_id,$type);
        $like_count   = $like_data['count'];
    }else{
        if ( !$like_count = get_post_meta( $post_id, '_like_count', true ) ) {
            if(io_get_option('like_n')>0){
                $like_count = mt_rand(0, 10)*io_get_option('like_n');
                update_post_meta( $post_id, '_like_count', $like_count );
            }
            else
                $like_count = 0;
        }
    }
    return format_number($like_count);
} 
/**
 * 查找字符是否存在
 * 
 * @description:
 * @param * $str
 * @param * $array_sou
 * @return {*}
 */
function iostrpos($str , $array_sou){
    $intex = 0;
    foreach($array_sou as $value){
        if(strstr($str , $value)!==false){
            if( $intex == 0)
                $intex = strpos($str , $value);
            if( $intex>strpos($str , $value) )
                $intex = strpos($str , $value);
        }
    }
    return $intex;
}
/**
 * 文章浏览数
 * 
 * @param * $author_id 用户id, all 为所有文章
 * @param * $display
 * @return *
 */
function author_posts_views($author_id = 'all',$display = true){
    global $wpdb;
    if($author_id == 'all')
        $sql = "SELECT sum(meta_value) FROM $wpdb->postmeta WHERE meta_key='views'";
    else
        $sql = "SELECT SUM(meta_value+0) FROM $wpdb->posts left join $wpdb->postmeta on ($wpdb->posts.ID = $wpdb->postmeta.post_id) WHERE meta_key = 'views' AND post_author =$author_id";    
    $comment_views = intval($wpdb->get_var($sql));
    if($display) {
        echo io_number_format($comment_views);
    } else {
        return $comment_views;
    }
}
/**
 * 获取作者所有文章点赞数
 * 
 * @param * $author_id 用户id, all 为所有文章
 * @param * $display
 * @return *
 */
function author_posts_likes($author_id = 'all' ,$display = true) {
    global $wpdb;
    if($author_id == 'all')
        $sql = "SELECT sum(meta_value) FROM $wpdb->postmeta WHERE meta_key='_like_count'";
    else
        $sql = "SELECT SUM(meta_value+0) FROM $wpdb->posts left join $wpdb->postmeta on ($wpdb->posts.ID = $wpdb->postmeta.post_id) WHERE meta_key = '_like_count' AND post_author = $author_id ";
        
    $posts_likes = intval($wpdb->get_var($sql));    
    if($display) {
        echo io_number_format($posts_likes);
    } else {
        return $posts_likes;
    }
}
// 将数字四舍五入为K（千），M（百万）或B（十亿）
function io_number_format( $number, $min_value = 1000, $decimal = 1 ) {
    if( $number < $min_value ) {
        return number_format_i18n( $number );
    }
    $alphabets = array( 1000000000 => 'B', 1000000 => 'M', 1000 => 'K' );
    foreach( $alphabets as $key => $value )
        if( $number >= $key ) {
            return round( $number / $key, $decimal ) . '' . $value;
        }
}
/**
 * 菜单允许的类型
 * 
 * @description:
 * @param *
 * @return *
 */
function get_menu_category_list(){
    $terms = apply_filters( 'io_category_list', array('favorites','apps','category','books',"series","apptag","sitetag","booktag","post_tag") );
    return $terms;
}
# 获取分类下文章数量
# --------------------------------------------------------------------
function io_get_category_count($cat_ID = '',$taxonomy = '') {
    if($cat_ID == '' || $taxonomy == '' ){
        global $wp_query;
        $cat_ID = get_query_var('cat');
        $category = get_category($cat_ID);
    }else{
        $category = get_term( $cat_ID, $taxonomy );
    }
    return $category->count;
}

add_action('publish_sites', 'io_add_post_data_fields');
add_action('publish_book', 'io_add_post_data_fields');
add_action('publish_app', 'io_add_post_data_fields');
add_action('publish_post', 'io_add_post_data_fields');
add_action('publish_page', 'io_add_post_data_fields');
function io_add_post_data_fields($post_ID) {
    global $wpdb;
    if(!wp_is_post_revision($post_ID)) {
        add_post_meta($post_ID, 'views', 0, true);
        add_post_meta($post_ID, '_down_count', 0, true);
        add_post_meta($post_ID, '_like_count', 0, true);
        add_post_meta($post_ID, '_star_count', 0, true);
    }
}
function like_button($post_id,$post_type="sites",$display = true){
    if(io_get_option('user_center') && function_exists('io_get_post_star_count')){
        $type         = $post_type;
        if($post_type == "sites-down")
            $type     = "sites";
        $like_data    = io_get_post_star_count($post_id,$type);
        $like_count   = $like_data['count'];
        $liked        = $like_data['status']; 
        switch($post_type){
            case "sites":
                $button = '
                <a href="javascript:;" data-action="post_star" data-post_type="'.$type.'" data-id="'.$post_id.'" data-ticket="'.wp_create_nonce('post_star_nonce').'" class=" btn btn-like btn-icon btn-light rounded-circle p-2 mx-3 mx-md-2 '. ($liked?'liked':'') .'" data-toggle="tooltip" data-placement="top" title="'. __('收藏','i_theme') .'">
                <span class="flex-column text-height-xs">
                    <i class="star-ico icon-lg iconfont icon-collection'. ($liked?'':'-line') .'"></i>
                    <small class="star-count-'.$post_id.' text-xs mt-1">'. $like_count .'</small>
                </span>
                </a>';
                break;
            case "book":
                $button = '
                <a href="javascript:;" data-action="post_star" data-post_type="'.$type.'" data-id="'.$post_id.'" data-ticket="'.wp_create_nonce('post_star_nonce').'" class=" btn btn-like btn-icon btn-light rounded-circle p-2 mx-3 mx-md-2 '. ($liked?'liked':'') .'" data-toggle="tooltip" data-placement="top" title="'. __('收藏','i_theme') .'">
                <span class="flex-column text-height-xs">
                    <i class="star-ico icon-lg iconfont icon-collection'. ($liked?'':'-line') .'"></i>
                    <small class="star-count-'.$post_id.' text-xs mt-1">'. $like_count .'</small>
                </span>
                </a>';
                break;
            case "app":
            case "sites-down":
                $button = '
                <button type="button" data-action="post_star" data-post_type="'.$type.'" data-id="'.$post_id.'" data-ticket="'.wp_create_nonce('post_star_nonce').'" class="btn btn-lg px-4 text-lg radius-50 btn-outline-danger custom_btn-outline mb-2 btn-like '.($liked?'liked':'').'">
                    <i class="star-ico iconfont icon-collection'. ($liked?'':'-line') .' mr-2"></i> '. __('收藏','i_theme') .' <span class="star-count-'.$post_id.'">'.$like_count.'</span>
                </button>';
                break;
            case "post":
                $button = '
                <span class="mr-3"><a class="btn-like btn-link-like '.($liked?'liked':'').'" href="javascript:;" data-action="post_star" data-post_type="'.$type.'" data-id="'.$post_id.'" data-ticket="'.wp_create_nonce('post_star_nonce').'"><i class="star-ico iconfont icon-collection'. ($liked?'':'-line') .'"></i> <span class="star-count-'.$post_id.'">'.$like_count.'</span></a></span>';
                break;
        }
    }else{
        $like_count    = get_like($post_id);
        $liked        = isset($_COOKIE['liked_' . $post_id]) ? 'liked' : ''; 
        switch($post_type){
            case "sites":
                $button = '
                <a href="javascript:;" data-action="post_like" data-id="'.$post_id.'" class=" btn btn-like btn-icon btn-light rounded-circle p-2 mx-3 mx-md-2 '.$liked.'" data-toggle="tooltip" data-placement="top" title="'. __('点赞','i_theme').'">
                <span class="flex-column text-height-xs">
                    <i class="icon-lg iconfont icon-like"></i>
                    <small class="like-count text-xs mt-1">'.$like_count.'</small>
                </span>
                </a>';
                break;
            case "book":
                $button = '
                <a href="javascript:;" data-action="post_like" data-id="'.$post_id.'" class=" btn btn-like btn-icon btn-light rounded-circle p-2 mx-3 mx-md-2 '.$liked.'" data-toggle="tooltip" data-placement="top" title="'. __('点赞','i_theme').'">
                <span class="flex-column text-height-xs">
                    <i class="icon-lg iconfont icon-like"></i>
                    <small class="like-count text-xs mt-1">'.$like_count.'</small>
                </span>
                </a>';
                break;
            case "app":
            case "sites-down":
                $button = '
                <button type="button" data-action="post_like" data-id="'.$post_id.'" class="btn btn-lg px-4 text-lg radius-50 btn-outline-danger custom_btn-outline mb-2 btn-like '.$liked.'">
                    <i class="iconfont icon-like mr-2"></i> '. __('赞','i_theme') .' <span class="like-count ">'.$like_count.'</span>
                </button>';
                break;
            case "post":
                $button = '
                <span class="mr-3"><a class="btn-like btn-link-like '.$liked.'" href="javascript:;" data-action="post_like" data-id="'.$post_id.'"><i class="iconfont icon-like"></i> <span class="like-count">'.$like_count.'</span></a></span>';
                break;
        }
    }
    if($display)
        echo $button;
    else
        return $button;
}
function like_home_button($post_id,$post_type="sites",$display = true){
    if(io_get_option('user_center') && function_exists('io_get_post_star_count')){
        $like_data    = io_get_post_star_count($post_id,$post_type);
        $like_count   = $like_data['count'];
        $liked        = $like_data['status']; 
        $button = '<span class="btn-like pl-2 '. ($liked?'liked':'') .'" data-action="post_star" data-post_type="'.$post_type.'" data-id="'.$post_id.'" data-ticket="'.wp_create_nonce('post_star_nonce').'" ><i class="star-ico iconfont icon-collection'. ($liked?'':'-line') .'"></i> <span class="star-count-'.$post_id.'">'.$like_count.'</span></span>';
    }else{
        $like_count    = get_like($post_id);
        $liked        = isset($_COOKIE['liked_' . $post_id]) ? 'liked' : ''; 
        $button = '<span class="home-like pl-2 '. $liked .'" data-action="post_like" data-id="'.$post_id.'" ><i class="iconfont icon-heart"></i> <span class="home-like-'.$post_id.'">'.$like_count.'</span></span>';
    }
    if($display)
        echo $button;
    else
        return $button;
}
/**
 * 获取用户权限等级
 * @param int $user_id
 * @return int
 */
function io_get_user_level($user_id = -1)
{
    if($user_id == -1){
        global $current_user;
        $user_id = $current_user->ID;
    }
    if (user_can($user_id, 'manage_options')) {
        return 10;
    }
    if (user_can($user_id, 'edit_others_posts')) {
        return 7;
    }
    if (user_can($user_id, 'publish_posts')) {
        return 2;
    }
    if (user_can($user_id, 'edit_posts')) {
        return 1;
    }
    return 0;
}
/**
 * 加载主页菜单内容对应卡片
 * @param *
 * @return *
 */
function add_menu_content_card(){
    global $menu_categories;
    foreach($menu_categories as $category) {
        if(get_post_meta( $category['ID'], 'purview', true )<=io_get_user_level()):
        if($category['menu_item_parent'] == 0){
            if(empty($category['submenu'])){ 
                $terms = get_menu_category_list();
                if($category['type'] != 'taxonomy') {
                    $url = trim($category['url']);
                    if( strlen($url)>1 ) {
                        if(substr( $url, 0, 1 ) == '#' || substr( $url, 0, 4 ) == 'http' )
                            continue;
                        echo "<div class='card py-3 px-4'><p style='color:#f00'>“{$category['title']}”不是分类，请到菜单重新添加</p></div>";
                    }
                } elseif ( $category['type'] == 'taxonomy' && in_array( $category['object'],$terms ) ){
                    fav_con_a($category);
                } else {
                    echo "<div class='card py-3 px-4'><p style='color:#f00'>“{$category['title']}”不是分类，请到菜单重新添加</p></div>";
                }
            }else{
                $is_null=true; //如果菜单内没有有效的项目，则不显示在正文中。
                foreach($category['submenu'] as $mid){
                    if($mid['type'] != 'taxonomy' ){
                        continue;
                    }
                    $is_null=false;
                }
                if($is_null) continue;
                if(io_get_option("tab_type")) {
                    if(io_get_option("tab_ajax"))
                        fav_con_tab_ajax($category['submenu'],$category);
                    else
                        fav_con_tab($category['submenu'],$category);
                }else{
                    foreach($category['submenu'] as $mid) {
                        if($mid['type'] != 'taxonomy' ){
                            $url = trim($mid['url']);
                            if( strlen($url)>1 ) {
                                if(substr( $url, 0, 1 ) == '#' || substr( $url, 0, 4 ) == 'http' )
                                    continue;
                            }
                        }
                        fav_con_a($mid,$category['title']);
                    }
                }
            }
        }
        endif;
    } 
}
/**
 * 获取bing图片
 * https://cn.bing.com/th?id=OHR.YoshinoyamaSpring_ZH-CN5545606722_UHD.jpg&pid=hp&w=2880&h=1620&rs=1&c=4&r=0
 * https://cn.bing.com/th?id=OHR.YoshinoyamaSpring_ZH-CN5545606722_1920x1080.jpg&rf=LaDigue_1920x1080.jpg&pid=hp"
 * set_url_scheme
 * 
 * @param  Int      $idx 序号
 * @param  String   $size 尺寸 full 1080p uhd 2880x1620 ro 4476x2518
 * 
 * @return String
 */
function get_bing_img_cache($idx=0,$size='uhd'){
    date_default_timezone_set('Asia/Shanghai');
    $today = mktime(0,0,0,date('m'),date('d'),date('Y'));
    $yesterday = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
    if($size=='full'){
        $suffix = '_1920x1080.jpg';
        $url_add = "_1920x1080.jpg";
    }else{
        $suffix = '_UHD.jpg';
        $url_add = "_UHD.jpg&pid=hp&w=2880&h=1620&rs=1&c=4&r=0";
    }
    if(io_get_option('bing_cache')){
        $imgDir = wp_upload_dir();
        $bingDir = $imgDir['basedir'].'/bing';
        if (!file_exists($bingDir)) {
            if(!mkdir($bingDir, 0755)){
                wp_die('创建必应图片缓存文件夹失败，请检测文件夹权限！', '创建文件夹失败', array('response'=>403));
            }
        }
        if (!file_exists($bingDir.'/'.$today.$suffix)) {
            $bing_url = 'http:'.bing_img_url($idx).$url_add;
            //$content = file_get_contents($bing_url, false, stream_context_create(array('http' => array('method' => 'GET', 'timeout' => 5))));

            $response = wp_remote_get($bing_url);
            $content = wp_remote_retrieve_body($response);

            file_put_contents($bingDir.'/'.$today.$suffix, $content); // 写入今天的
            $yesterdayimg=$bingDir.'/'.$yesterday.$suffix;
            if (file_exists($yesterdayimg)) unlink($yesterdayimg); //删除昨天的 
            $src = $imgDir['baseurl'].'/bing/'.$today.$suffix;
        } else {
            $src = $imgDir['baseurl'].'/bing/'.$today.$suffix;
        }
    }else{
        $src = bing_img_url($idx).$url_add;
    }
    return $src;
}
function bing_img_url($idx=0,$n=1){
    //$res = file_get_contents('http://cn.bing.com/HPImageArchive.aspx?format=js&idx='.$idx.'&n='.$n, false, stream_context_create(array('http' => array('method' => 'GET', 'timeout' => 5))));
    $response = wp_remote_get('http://cn.bing.com/HPImageArchive.aspx?format=js&idx='.$idx.'&n='.$n);
    $res = wp_remote_retrieve_body($response);
    $bingArr = json_decode($res, true);
    $bing_url = "//cn.bing.com{$bingArr['images'][0]['urlbase']}";
    return $bing_url;
}
/**
 * 获取简介 
 * @param int $count
 * @param string $meta_key
 * @param string $trimmarker
 * @return string
 */
function io_get_excerpt($count = 90,$meta_key = '_seo_desc', $trimmarker = '...')
{
    global $post;
    $excerpt = '';
    if (!($excerpt = get_post_meta($post->ID, $meta_key, true))) { 
        if (!empty($post->post_excerpt)) {
            $excerpt = $post->post_excerpt;
        } else {
            $excerpt = $post->post_content;
        }
    }
    $excerpt = trim(str_replace(array("\r\n", "\r", "\n", "　", " "), " ", str_replace("\"", "'", strip_tags(strip_shortcodes($excerpt)))));
    $excerpt = mb_strimwidth(strip_tags($excerpt), 0, $count, $trimmarker);
    return $excerpt;
}
/**
 * 保存外链图片到本地
 * @param string $src
 * @return array
 */
function io_save_img($src) { 
    // 本地上传路径信息(数组)，用来构造url
    $wp_upload_dir = wp_upload_dir();

    
    $return_data =  array(
        'status' => false,
        'url'    => '',
        'msg'    => '',
    );

    // 脚本执行不限制时间
    set_time_limit(0);

    if (isset($src) && unexclude_image($src)) {// 如果图片域名是外链

        // 检查src中的url有无扩展名，没有则重新给定文件名
        // 注意：如果url中有扩展名但格式为webp，那么返回的file_info数组为 ['ext' =>'','type' =>'']
        $file_info = wp_check_filetype(basename($src), null);
        if ($file_info['ext'] == false) {
            // 无扩展名和webp格式的图片会被作为无扩展名文件处理
            date_default_timezone_set('PRC');
            $file_name = date('YmdHis-').dechex(mt_rand(100000, 999999)).'.tmp';
        } else {
            // 有扩展名的图片重新给定文件名防止与本地文件名冲突
            $file_name = dechex(mt_rand(100000, 999999)) . '-' . basename($src);
        }
        // 抓取图片, 将图片写入本地文件
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS,20);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_URL, $src);
        $file_path = $wp_upload_dir['path'] . '/' . $file_name;
        $img = fopen($file_path, 'wb');

        // curl写入$img
        curl_setopt($ch, CURLOPT_FILE, $img);
        $img_data  = curl_exec($ch);
        curl_close($ch);
        fclose($img);

        if (file_exists($file_path) && filesize($file_path) > 0) {
            // 将扩展名为tmp和webp的图片转换为jpeg文件并重命名
            $t   = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            $arr = explode('/', $t);
            // 对url地址中没有扩展名或扩展名为webp的图片进行处理
            if (pathinfo($file_path, PATHINFO_EXTENSION) == 'tmp') {
                $file_path = io_handle_ext($file_path, $arr[1], $wp_upload_dir['path'], $file_name, 'tmp');
            } elseif (pathinfo($file_path, PATHINFO_EXTENSION) == 'webp') {
                $file_path = io_handle_ext($file_path, $arr[1], $wp_upload_dir['path'], $file_name, 'webp');
            }

            // 本地src
            $url = $wp_upload_dir['url'] . '/' . basename($file_path);
            // 构造附件post参数并插入媒体库(作为一个post插入到数据库)
            $attachment = io_get_attachment_post(basename($file_path), $url);
            // 生成并更新图片的metadata信息
            $attach_id = wp_insert_attachment($attachment, ltrim($wp_upload_dir['subdir'] . '/' . basename($file_path), '/'), 0);
            $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
            // 直接调用wordpress函数，将metadata信息写入数据库
            $ss = wp_update_attachment_metadata($attach_id, $attach_data);

            $return_data['status'] = true;
            $return_data['url'] = $url;
            $return_data['msg'] = '获取成功！';
            return $return_data;
        }else{
            $return_data['msg'] = '图片获取失败！';
            return $return_data;
        }
    }else{
        $return_data['msg'] = '已经是本地图片了';
        return $return_data;
    }
}
/**
 * 图片白名单
 * @param string $url
 * @return bool
 */
function unexclude_image($url){
    $exclude = explode(PHP_EOL , io_get_option('exclude_image'));
    $exclude[] = $_SERVER['HTTP_HOST']; 
    foreach($exclude as $v){
        if(strpos($url, $v) !== false){
            return false;
        }
    }
    return true;
}
/**
 * 处理没有扩展名的图片:转换格式或更改扩展名
 *
 * @param string $file 图片本地绝对路径
 * @param string $type 图片mimetype
 * @param string $file_dir 图片在本地的文件夹
 * @param string $file_name 图片名称
 * @param string $ext 图片扩展名
 * @return string 处理后的本地图片绝对路径
 */
function io_handle_ext($file, $type, $file_dir, $file_name, $ext) {
    switch ($ext) {
        case 'tmp':
            if (rename($file, str_replace('tmp', $type, $file))) {
                if ('webp' == $type) {
                    // 将webp格式的图片转换为jpeg格式
                    return io_image_convert('webp', 'jpeg', $file_dir . '/' . str_replace('tmp', $type, $file_name));
                }
                return $file_dir . '/' . str_replace('tmp', $type, $file_name);
            }
        case 'webp':
            if ('webp' == $type) {
                // 将webp格式的图片转换为jpeg格式
                return io_image_convert('webp', 'jpeg', $file);
            } else {
                if (rename($file, str_replace('webp', $type, $file))) {
                    return $file_dir . '/' . str_replace('webp', $type, $file_name);
                }
            }
        default:
            return $file;
    }
}
/**
 * 图片格式转换，暂只能从webp转换为jpeg
 *
 * @param string $from
 * @param string $to
 * @param string $image 图片本地绝对路径
 * @return string 转换后的图片绝对路径
 */
function io_image_convert($from='webp', $to='jpeg', $image='') {
    // 加载 WebP 文件
    $im = imagecreatefromwebp($image);
    // 以 100% 的质量转换成 jpeg 格式并将原webp格式文件删除
    if (imagejpeg($im, str_replace('webp', 'jpeg', $image), 100)) {
        try {
            unlink($image);
        } catch (Exception $e) {
            $error_msg = sprintf('Error removing local file %s: %s', $image,
                $e->getMessage());
            error_log($error_msg);
        }
    }
    imagedestroy($im);

    return str_replace('webp', 'jpeg', $image);
}
/**
 * 构造图片post参数
 *
 * @param string $filename
 * @param string $url
 * @return array 图片post参数数组
 */
function io_get_attachment_post($filename, $url) {
    $file_info  = wp_check_filetype($filename, null);
    return array(
        'guid'           => $url,
        'post_type'      => 'attachement',
        'post_mime_type' => $file_info['type'],
        'post_title'     => preg_replace('/\.[^.]+$/', '', $filename),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );
} 




function get_sites_card_meta(){
    global $post;
    $link_url = get_post_meta($post->ID, '_sites_link', true); 
    $default_ico = get_theme_file_uri('/images/favicon.png');

    $summary=htmlspecialchars(get_post_meta($post->ID, '_sites_sescribe', true));
    if( $summary=='' ){
        $summary = io_get_excerpt(30);
        update_post_meta($post->ID, '_sites_sescribe',$summary);
    } 
    $sites_type = get_post_meta($post->ID, '_sites_type', true);
    if($post->post_type != 'sites')
        $link_url = get_permalink($post->ID);
    $title = $link_url;
    $is_html = '';
    $width = 128;
    $tooltip = 'data-toggle="tooltip" data-placement="bottom"';
    if(get_post_meta_img($post->ID, '_wechat_qr', true)){
        $title="<img src='" . get_post_meta_img(get_the_ID(), '_wechat_qr', true) . "' width='{$width}'>";
        $is_html = 'data-html="true"';
    } else {
        switch(io_get_option('po_prompt')) {
            case 'null':  
                $title = get_the_title();
                $tooltip = '';
                break;
            case 'url': 
                if($link_url==""){
                    if($sites_type == "down")
                        $title = __('下载','i_theme').'“'.get_the_title().'”';
                    elseif ($sites_type == "wechat") 
                        $title = __('居然没有添加二维码','i_theme');
                    else
                        $title = __('没有 url','i_theme');
                }
                break;
            case 'summary':
                if($sites_type == "down")
                    $title = __('下载','i_theme').'“'.get_the_title().'”';
                else
                    $title = $summary;
                break;
            case 'qr':
                if($link_url==""){
                    if($sites_type == "down")
                        $title = __('下载','i_theme').'“'.get_the_title().'”';
                    elseif ($sites_type == "wechat") 
                        $title = __('居然没有添加二维码','i_theme');
                    else
                        $title = __('没有 url','i_theme');
                }
                else{
                    $title = "<img src='".str_ireplace(array('$size','$url'),array($width,$link_url),io_get_option('qr_url'))."' width='{$width}' height='{$width}'>";
                    $is_html = 'data-html="true"';
                }
                break;
            default:  
        } 
    } 
    
    $url = '';
    $blank = new_window() ;
    $is_views = '';
    //($sites_meta['sites_type'] == "sites" && get_post_meta($post->ID, '_goto', true))?$sites_meta['link_url']:go_to($sites_meta['link_url'])
    if($sites_type == "sites" && get_post_meta($post->ID, '_goto', true)){
        $is_views = 'is-views';
        $blank = 'target="_blank"' ;
        $url = $link_url;
    }else{
        if(io_get_option('details_page')){
            $url=get_permalink();
        }else{ 
            if($sites_type && $sites_type != "sites"){
                $url=get_permalink();
            }
            elseif($link_url==""){
                $url = 'javascript:';
                $blank = '';
            }else{
                $is_views = 'is-views';
                $blank = 'target="_blank"' ;
                $url = go_to($link_url);
            }
        }
    }
    $ico = '';
    if( !io_get_option('no_ico') ){
        if($post->post_type != 'sites')
            $ico = io_theme_get_thumb();
        else
            $ico = get_post_meta_img($post->ID, '_thumbnail', true);

        if($ico == ''){
            if( $link_url != '' || ($sites_type == "sites" && $link_url != '') )
                $ico = (io_get_option('ico-source')['ico_url'] .format_url($link_url) . io_get_option('ico-source')['ico_png']);
            elseif($sites_type == "wechat")
                $ico = get_theme_file_uri('/images/qr_ico.png');
            elseif($sites_type == "down")
                $ico = get_theme_file_uri('/images/down_ico.png');
            else
                $ico = $default_ico;
        }
    }
    $sites_card_meta = array(
        "ico"           => $ico,
        "url"           => $url,// 详情页
        "is_views"      => $is_views,
        "is_html"       => $is_html,
        "blank"         => $blank,
        "summary"       => $summary,
        "tooltip"       => $tooltip,
        "title"         => $title,
        "sites_type"    => $sites_type,
        "link_url"      => $link_url,// 目标地址
        "default_ico"   => $default_ico,
    );
    return $sites_card_meta;
}
# 网址块样式
# --------------------------------------------------------------------
function get_columns($display = true){
    $class = '';
    switch(io_get_option('columns')) {
        case 2: 
            $class = ' col-sm-6 ';
            break;
        case 3: 
            $class = ' col-sm-6 col-md-4 ';
            break;
        case 4: 
            $class = ' col-sm-6 col-md-4 col-xl-3 ';
            break;
        case 6: 
            $class = ' col-sm-6 col-md-4 col-xl-5a col-xxl-6a '; 
            break;
        case 10: 
            $class = ' col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2 col-xxl-10a ';
            break;
        default: 
            $class = ' col-sm-6 col-md-4 col-lg-3 ';
    } 
    if($display)
        echo $class;
    else
        return $class;
}
# 时间格式转化
# --------------------------------------------------------------------
function timeago( $ptime ) {
    date_default_timezone_set('PRC');
    $ptime = strtotime($ptime);
    $etime = time() - $ptime;
    if($etime < 1) return __('刚刚', 'i_theme');
    $interval = array (
        12 * 30 * 24 * 60 * 60  =>  __('年前', 'i_theme').' ('.date('Y', $ptime).')',
        30 * 24 * 60 * 60       =>  __('个月前', 'i_theme'),
        7 * 24 * 60 * 60        =>  __('周前', 'i_theme'),
        24 * 60 * 60            =>  __('天前', 'i_theme'),
        60 * 60                 =>  __('小时前', 'i_theme'),
        60                      =>  __('分钟前', 'i_theme'),
        1                       =>  __('秒前', 'i_theme')
    );
    foreach ($interval as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . $str;
        }
    };
}
/**
 * 根据WP设置显示日期时间。
 *
 * @param  integer|string   $datetime   DateTime或UNIX时间戳。
 * @param  boolean          $time       如果要显示时间部分，则为True。
 * @return string                       格式化的日期时间。
 * --------------------------------------------------------------------------
 */
function io_date_time( $datetime, $time = true ) {
    if( ! is_numeric($datetime) ) {
        $datetime = strtotime($datetime);
    }
    $date_time_format = get_option( 'date_format' );
    if( $time ) {
        $date_time_format .= ' ';
        $date_time_format .= get_option( 'time_format' );
    }
    return wp_date( $date_time_format, $datetime );
}
# 评论高亮作者
# --------------------------------------------------------------------
function is_master($email = '') {
    if( empty($email) ) return;
    $handsome = array( '1' => ' ', );
    $adminEmail = get_option( 'admin_email' );
    if( $email == $adminEmail ||  in_array( $email, $handsome )  )
    echo '<span class="is-author"  data-toggle="tooltip" data-placement="right" title="'.__('博主','i_theme').'"><i class="iconfont icon-user icon-fw"></i></span>';
}
/**
 * 首页标签图标,菜单图标
 * @description: 
 * @param string $terms   分类法
 * @param array $mid      分类对象
 * @param string $default 默认图标
 * @return string
 */
function get_tag_ico($terms, $mid, $default='iconfont icon-tag'){
    $icon = $default; 
    if(!is_array($mid))
        return $icon; 
    if(!io_get_option('same_ico') && $terms!='' ){
        if($terms == "favorites") { 
            $icon = 'iconfont icon-tag'; 
        } elseif($terms == "apps") { 
            $icon = 'iconfont icon-app'; 
        } elseif($terms == "books") { 
            $icon = 'iconfont icon-book'; 
        } elseif($terms == "category") {
            $icon = 'iconfont icon-publish';
        } else { 
            $icon = $default;
        }
    }else{
        //if(empty($mid['classes']) || ( count($mid['classes'])==1 && empty($mid['classes'][0])) )
        //    $icon = get_cate_ico($mid['post_content']);
        //else{
        if(!$icon = get_post_meta( $mid['ID'], 'menu_ico', true )){
            $classes = preg_grep( '/^(fa[b|s]?|io)(-\S+)?$/i', $mid['classes'] );
            if( !empty( $classes ) ){
                $icon = implode(" ",$mid['classes']);
            }else{
                $icon = $default;
            }
        }
        //}
    }
    return $icon;
}
# 头衔
# --------------------------------------------------------------------
function site_rank( $comment_author_email, $user_id ) {
    $adminEmail = get_option( 'admin_email' );
    if($comment_author_email ==$adminEmail) 
        return;

    if (user_can($user_id, 'manage_options')) {
        $rank =  __('管理员', 'i_theme');
    }
    if (user_can($user_id, 'edit_others_posts')) {
        $rank =  __('编辑', 'i_theme');
    }
    if (user_can($user_id, 'publish_posts')) {
        $rank =  __('作者', 'i_theme');
    }
    if (user_can($user_id, 'edit_posts')) {
        $rank =  __('投稿者', 'i_theme');
    }
    if($user_id == 0) {
        $rank =  __('游客', 'i_theme');
    }
    if(!isset($rank)){
        $rank =  __('读者', 'i_theme');
    }
    return $rank = '<span class="rank" title="'.__('头衔：','i_theme') . $rank .'">'. $rank .'</span>';

    //$v1 = 'Vip1';
    //$v2 = 'Vip2';
    //$v3 = 'Vip3';
    //$v4 = 'Vip4';
    //$v5 = 'Vip5';
    //$v6 = 'Vip6'; 
    //global $wpdb;
    //$num = count( $wpdb->get_results( "SELECT comment_ID as author_count FROM $wpdb->comments WHERE comment_author_email = '$comment_author_email' " ) );
    //
    //if ( $num > 0 && $num < 6 ) {
    //    $rank = $v1;
    //}
    //elseif ( $num > 5 && $num < 11 ) {
    //    $rank = $v2;
    //}
    //elseif ( $num > 10 && $num < 16 ) {
    //    $rank = $v3;
    //}
    //elseif ($num > 15 && $num < 21) {
    //    $rank = $v4;
    //}
    //elseif ( $num > 20 && $num < 26 ) {
    //    $rank = $v5;
    //}
    //elseif ( $num > 25 ) {
    //    $rank = $v6;
    //}

    //if( $comment_author_email != $adminEmail )
    //    return $rank = '<span class="rank" data-toggle="tooltip" data-placement="right" title="'.__('头衔：','i_theme') . $rank .'，'.__('累计评论：','i_theme') . $num .'">'. $rank .'</span>';
}
# 评论格式
# --------------------------------------------------------------------
if(!function_exists('my_comment_format')){
    function my_comment_format($comment, $args, $depth){
        $GLOBALS['comment'] = $comment;
        ?>
        <li <?php comment_class('comment'); ?> id="li-comment-<?php comment_ID() ?>">
            <div id="comment-<?php comment_ID(); ?>" class="comment_body d-flex flex-fill">    
                <div class="profile mr-2 mr-md-3"> 
                    <?php 
                    echo  get_avatar( $comment, 96, '', get_comment_author() );
                    ?>
                </div>                    
                <section class="comment-text d-flex flex-fill flex-column">
                    <div class="comment-info d-flex align-items-center mb-1">
                        <div class="comment-author text-sm w-100"><?php comment_author_link(); ?>
                        <?php is_master( $comment->comment_author_email ); echo site_rank( $comment->comment_author_email, $comment->user_id ); ?>
                        </div>                                        
                    </div>
                    <div class="comment-content d-inline-block text-sm">
                        <?php comment_text(); ?> 
                        <?php
                        if ($comment->comment_approved == '0'){
                            echo '<span class="cl-approved">('.__('您的评论需要审核后才能显示！','i_theme').')</span><br />';
                        } 
                        ?>
                    </div>
                    <div class="d-flex flex-fill text-xs text-muted pt-2">
                        <div class="comment-meta">
                            <div class="info"><time itemprop="datePublished" datetime="<?php echo get_comment_date( 'c' );?>"><?php echo timeago(get_comment_date('Y-m-d G:i:s'));?></time></div>
                        </div>
                        <div class="flex-fill"></div>
                        <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                    </div>
                </section>
            </div>
        <?php
    }
}
/**
 * 禁止冒充管理员评论
 * ******************************************************************************************************
 */
function usercheck($incoming_comment) {
    $isSpam = false;
    if (trim($incoming_comment['comment_author']) == io_get_option('io_administrator')['admin_name'] )
        $isSpam = true;
    if (trim($incoming_comment['comment_author_email']) ==  io_get_option('io_administrator')['admin_email'] )
        $isSpam = true;
    if(!$isSpam)
        return $incoming_comment;
    error('{"status":3,"msg":"'.__('请勿冒充管理员发表评论！' , 'i_theme' ).'"}', true);
}
if (!is_user_logged_in()) { add_filter('preprocess_comment', 'usercheck'); }
/**
 * 过滤纯英文、日文和一些其他内容
 * ******************************************************************************************************
 */
function io_refused_spam_comments($comment_data) {
    $pattern = '/[一-龥]/u';
    $jpattern = '/[ぁ-ん]+|[ァ-ヴ]+/u';
    $links = '/http:\/\/|https:\/\/|www\./u';
    if (io_get_option('io_comment_set')['no_url'] && (preg_match($links, $comment_data['comment_author']) || preg_match($links, $comment_data['comment_content']))) {
        error('{"status":3,"msg":"'.__('别啊，昵称和评论里面添加链接会怀孕的哟！！' , 'i_theme').'"}', true);
    }
    if(io_get_option('io_comment_set')['no_chinese']){
        if (!preg_match($pattern, $comment_data['comment_content'])) {
            error('{"status":3,"msg":"'.__('评论必须含中文！' , 'i_theme' ).'"}', true);
        }
        if (preg_match($jpattern, $comment_data['comment_content'])) {
            error('{"status":3,"msg":"'.__('评论必须含中文！' , 'i_theme' ).'"}', true);
        }
    }
    if (wp_check_comment_disallowed_list($comment_data['comment_author'], $comment_data['comment_author_email'], $comment_data['comment_author_url'], $comment_data['comment_content'], isset($comment_data['comment_author_IP']), isset($comment_data['comment_agent']))) {
        header("Content-type: text/html; charset=utf-8");
        error('{"status":3,"msg":"'.sprintf(__('不好意思，您的评论违反了%s博客评论规则' , 'i_theme'), get_option('blogname')).'"}', true);
    }
    return ($comment_data);
}
if (!is_user_logged_in()) add_filter('preprocess_comment', 'io_refused_spam_comments');
/**
 * 禁止评论自动超链接
 * ******************************************************************************************************
 */
remove_filter('comment_text', 'make_clickable', 9);   
/**
 * 屏蔽长链接转垃圾评论
 * ******************************************************************************************************
 */
function lang_url_spamcheck($approved, $commentdata) {
    return (strlen($commentdata['comment_author_url']) > 50) ?
    'spam' : $approved;
}
add_filter('pre_comment_approved', 'lang_url_spamcheck', 99, 2);
# 获取热门文章
# --------------------------------------------------------------------
function get_timespan_most_viewed($mode = '', $limit = 10, $days = 7, $show_thumbs = false, $newWindow='', $display = true) {
    global $wpdb, $post;
    $limit_date = current_time('timestamp') - ($days*86400);
    $limit_date = date("Y-m-d H:i:s",$limit_date);    
    $where = '';
    $temp = '';
    if(!empty($mode) && $mode != 'both') {
        $where = "post_type = '$mode'";
    } else {
        $where = '1=1';
    }
    $most_viewed = $wpdb->get_results("SELECT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER  BY views DESC LIMIT $limit");
    if($most_viewed) {
        foreach ($most_viewed as $post) {
            $post_title = get_the_title();
            $post_views = intval($post->views);
            $post_views = number_format($post_views);
            $temp .= "<div class='list-item py-2'>";
            if($show_thumbs){
                $temp .= "<div class='media media-3x2 rounded col-4 mr-3'>";
                $thumbnail =  io_theme_get_thumb();
                if(io_get_option('lazyload'))
                    $temp .= '<a class="media-content" href="'.get_permalink().'" '. $newWindow .' title="'.get_the_title().'" data-src="'.$thumbnail.'"></a>';
                else
                    $temp .= '<a class="media-content" href="'.get_permalink().'" '. $newWindow .' title="'.get_the_title().'" style="background-image: url('.$thumbnail.');"></a>';
                $temp .= "</div>"; 
            }
            $temp .= '
                <div class="list-content py-0">
                    <div class="list-body">
                        <a href="'.get_permalink().'" class="list-title overflowClip_2" '. $newWindow .' rel="bookmark">'.get_the_title().'</a>
                    </div>
                    <div class="list-footer">
                        <div class="d-flex flex-fill text-muted text-xs">
                            <time class="d-inline-block">'.timeago(get_the_time('Y-m-d G:i:s')).'</time>
                            <div class="flex-fill"></div>' 
                            .(function_exists( 'the_views' )?the_views( false, '<span class="views"><i class="iconfont icon-chakan"></i> ','</span>' ):'').
                        '</div>
                    </div> 
                </div> 
            </div>'; 
        }
    } else {
        $temp = "<div class='list-item py-2'>".__("暂无文章","i_theme")."</div>";
    }
    if($display) {
        echo $temp;
    } else {
        return $temp;
    }
}
# 获取热门网址
# --------------------------------------------------------------------
function get_sites_most_viewed( $limit = 10, $days = 7, $newWindow='', $display = true) {
    global $wpdb, $post;
    $limit_date = current_time('timestamp') - ($days*86400);
    $limit_date = date("Y-m-d H:i:s",$limit_date);    
    $temp = '';
    $where = "post_type = 'sites'";

    $most_viewed = $wpdb->get_results("SELECT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER  BY views DESC LIMIT $limit");
    if($most_viewed) {
        foreach ($most_viewed as $post) { 
            $sites_type = get_post_meta($post->ID, '_sites_type', true);
            $link_url = get_post_meta($post->ID, '_sites_link', true); 
            $default_ico = get_theme_file_uri('/images/favicon.png');
            $ico = get_post_meta_img($post->ID, '_thumbnail', true);
            if($ico == ''){
                if( $link_url != '' || ($sites_type == "sites" && $link_url != '') )
                    $ico = (io_get_option('ico-source')['ico_url'] .format_url($link_url) . io_get_option('ico-source')['ico_png']);
                elseif($sites_type == "wechat")
                    $ico = get_theme_file_uri('/images/qr_ico.png');
                elseif($sites_type == "down")
                    $ico = get_theme_file_uri('/images/down_ico.png');
                else
                    $ico = $default_ico;
            }

            //if(current_user_can('level_10') || !get_post_meta($post->ID, '_visible', true)){
                $temp .= '<div class="url-card col-6 '. before_class($post->ID) .'">';
                $temp .= '<a href="'.get_permalink().'" '.$newWindow.' class="card post-min mb-2">
                <div class="card-body" style="padding:0.3rem 0.5rem;">
                <div class="url-content d-flex align-items-center">
                    <div class="url-img rounded-circle">';
                        if(io_get_option('lazyload')):
                            $temp .= '<img class="lazy" src="'.$default_ico.'" data-src="'.$ico.'" onerror="javascript:this.src=\''.$default_ico.'\'">';
                        else:
                            $temp .= '<img src="'.$ico.'" onerror="javascript:this.src='.$default_ico.'">';
                        endif;
                        $temp .= '</div>
                    <div class="url-info pl-1 flex-fill">
                    <div class="text-xs overflowClip_1">'.get_the_title().'</div>
                    </div>
                </div>
                </div>
            </a> 
            </div>';
              
            //}

        }
    } else {
        $temp = "<div class='list-item py-2'>".__("暂无文章","i_theme")."</div>";
    }
    if($display) {
        echo $temp;
    } else {
        return $temp;
    }
}
# 归档页显示数量单独设置
# --------------------------------------------------------------------
function filter_pre_get_posts( $query ){
    if ( $query->is_main_query() ){
        $num = '';  
        $meta = '';  
        if ( is_tax('favorites') ){ $num = io_get_option('site_archive_n')?:''; $meta = io_get_option('home_sort')['favorites']; } 
        if ( is_tax('sitetag') ){ $num = io_get_option('site_archive_n')?:''; $meta = io_get_option('home_sort')['favorites']; } 
        if ( is_tax('apps') ){ $num = io_get_option('app_archive_n')?:''; $meta = io_get_option('home_sort')['apps']; } 
        if ( is_tax('apptag') ){ $num = io_get_option('app_archive_n')?:''; $meta = io_get_option('home_sort')['apps']; } 
        if ( is_tax('books') ){ $num = io_get_option('book_archive_n')?:''; $meta = io_get_option('home_sort')['books']; } 
        if ( is_tax('booktag') ){ $num = io_get_option('book_archive_n')?:''; $meta = io_get_option('home_sort')['books']; } 
        if ( is_tax('series') ){ $num = io_get_option('book_archive_n')?:''; $meta = io_get_option('home_sort')['books']; } 
        
        if ( '' != $num ){ $query->set( 'posts_per_page', $num ); }

        if( '' != $meta ){
            if( $meta=="views" || $meta=="_sites_order" || $meta=="_down_count" ){
                if($meta=="_sites_order"&& io_get_option('sites_sortable')){
                    $query->set( 'orderby',  array( 'menu_order' => 'ASC', 'ID' => 'DESC' )  ); 
                }else{
                    $query->set( 'meta_key', $meta );
                    $query->set( 'orderby', array( 'meta_value_num' => 'DESC', 'date' => 'DESC' ) );
                }
            }else{
                $query->set( 'orderby', $meta );
                $query->set( 'order', 'DESC' );
            }
        }
    }
    return $query;
}
/**
 * 首页置顶靠前
 * 
 * @param * $myposts
 * @param * $post_type
 * @param * $taxonomy
 * @param * $terms
 * @return {*}
 */
function sticky_posts_to_top($myposts,$post_type,$taxonomy,$terms){
    $sticky_posts = get_option( 'sticky_posts' );
    if (is_array( $sticky_posts ) && ! empty( $sticky_posts ) ) {
        $num_posts     = count( $myposts->posts );
        $sticky_offset = 0;
        // 循环文章，将置顶文章移到最前面。
        for ( $i = 0; $i < $num_posts; $i++ ) {
            if ( in_array( $myposts->posts[$i]->ID, $sticky_posts, true ) ) {
                $sticky_post = $myposts->posts[$i];
                // 从当前位置移除置顶文章。
                array_splice( $myposts->posts, $i, 1 );
                // 移到前面，在其他置顶文章之后。
                array_splice( $myposts->posts, $sticky_offset, 0, array( $sticky_post ) );
                // 增加置顶文章偏移量。下一个置顶文章将被放置在此偏移处。
                $sticky_offset++;
                // 从置顶文章数组中删除文章。
                $offset = array_search( $sticky_post->ID, $sticky_posts, true );
                unset( $sticky_posts[$offset] );
            }
        }
    }
    // 获取查询结果中没有的置顶文章
    if ( !empty($sticky_posts) ) { 
        $stickies = get_posts( array(
            'post__in' => $sticky_posts, 
            'post_status' => 'publish',
            'post_type' => $post_type,
            'nopaging' => true,
            'tax_query'           => array(
                array(
                    'taxonomy' => $taxonomy,       
                    'field'    => 'id',            
                    'terms'    => $terms,    
                )
            ),
        ) );
        foreach ( $stickies as $sticky_post ) {
            array_splice( $myposts->posts, $sticky_offset, 0, array( $sticky_post ) );
            $sticky_offset++;
        }
    }

    return $myposts;
}
# 归档页置顶靠前
# --------------------------------------------------------------------
if( io_get_option('show_sticky') && io_get_option('category_sticky'))
add_filter('the_posts',  'category_sticky_to_top' );
function category_sticky_to_top( $posts ) {
    if(is_admin() || is_home() || is_front_page() || !is_main_query() || !is_archive() )
        return $posts; 
    global $wp_query;
    
    if($wp_query->post_count>0)
        return $posts; 
    // 获取所有置顶文章
    $sticky_posts = get_option('sticky_posts');
    if ( $wp_query->query_vars['paged'] <= 1 && !empty($sticky_posts) && is_array($sticky_posts) && !get_query_var('ignore_sticky_posts') ) {
        $stickies1 = get_posts( array( 'post__in' => $sticky_posts ) );
        foreach ( $stickies1 as $sticky_post1 ) { 
            // 判断当前是否分类页 
            if($wp_query->is_category == 1 && !has_category($wp_query->query_vars['cat'], $sticky_post1->ID)) {
              // 去除不属于本分类的置顶文章
                $offset1 = array_search($sticky_post1->ID, $sticky_posts);
                unset( $sticky_posts[$offset1] );
            } 
            // 判断当前是否自定义分类页 
            if($wp_query->is_tax == 1 && !has_term($wp_query->query_vars['term'],$wp_query->query_vars['taxonomy'], $sticky_post1->ID)) {
                // 去除不属于本分类的置顶文章
                $offset1 = array_search($sticky_post1->ID, $sticky_posts);
                unset( $sticky_posts[$offset1] );
            }  
            if($wp_query->is_tag == 1 && !has_tag($wp_query->query_vars['tag'], $sticky_post1->ID)) {
                // 去除不属于本标签的文章
                $offset1 = array_search($sticky_post1->ID, $sticky_posts);
                unset( $sticky_posts[$offset1] );
            }
            if($wp_query->is_year == 1 && date_i18n('Y', strtotime($sticky_post1->post_date))!=$wp_query->query['m']) {
                // 去除不属于本年份的文章
                $offset1 = array_search($sticky_post1->ID, $sticky_posts);
                unset( $sticky_posts[$offset1] );
            }
            if($wp_query->is_month == 1 && date_i18n('Ym', strtotime($sticky_post1->post_date))!=$wp_query->query['m']) {
                // 去除不属于本月份的文章
                $offset1 = array_search($sticky_post1->ID, $sticky_posts);
                unset( $sticky_posts[$offset1] );
            }
            if($wp_query->is_day == 1 && date_i18n('Ymd', strtotime($sticky_post1->post_date))!=$wp_query->query['m']) {
                // 去除不属于本日期的文章
                $offset1 = array_search($sticky_post1->ID, $sticky_posts);
                unset( $sticky_posts[$offset1] );
            }
            if($wp_query->is_author == 1 && $sticky_post1->post_author != $wp_query->query_vars['author']) {
                // 去除不属于本作者的文章
                $offset1 = array_search($sticky_post1->ID, $sticky_posts);
                unset( $sticky_posts[$offset1] );
            }
        }
        $num_posts = count($posts);
        $sticky_offset = 0;
        // 循环文章，将置顶文章移到最前面。
        for ( $i = 0; $i < $num_posts; $i++ ) {
            if ( in_array($posts[$i]->ID, $sticky_posts) ) {
                $sticky_post = $posts[$i];
                // 从当前位置移除置顶文章。
                array_splice($posts, $i, 1);
                // 移到前面，在其他置顶文章之后。
                array_splice($posts, $sticky_offset, 0, array($sticky_post));
                // 增加置顶文章偏移量。下一个置顶文章将被放置在此偏移处。
                $sticky_offset++;
                // 从置顶文章数组中删除文章。
                $offset = array_search($sticky_post->ID, $sticky_posts);
                unset( $sticky_posts[$offset] );
            }
        }
        // 删除被排除的文章
        if ( !empty($sticky_posts) && !empty($wp_query->query_vars['post__not_in'] ) )
            $sticky_posts = array_diff($sticky_posts, $wp_query->query_vars['post__not_in']);
        // 获取查询结果中没有的置顶文章
        if ( !empty($sticky_posts) ) {
            if( is_tax() ){
                $stickies = get_posts( array(
                    'post__in' => $sticky_posts, 
                    'post_status' => 'publish',
                    'post_type' => $wp_query->query_vars['post_type'],
                    'nopaging' => true,
                    'tax_query'           => array(
                        array(
                            'taxonomy' => $wp_query->query_vars['taxonomy'],       
                            'field'    => 'slug',            
                            'terms'    => $wp_query->query_vars['term'],    
                        )
                    ),
                ) );
            }else{
            $stickies = get_posts( array(
                'post__in' => $sticky_posts,
                'post_type' => $wp_query->query_vars['post_type'],
                'post_status' => 'publish',
                'nopaging' => true
            ) );
            }
            foreach ( $stickies as $sticky_post ) {
                array_splice( $posts, $sticky_offset, 0, array( $sticky_post ) );
                $sticky_offset++;
            }
        }
    }
    return $posts;
}
# 编辑菜单后删除相应菜单缓存
# --------------------------------------------------------------------
add_action( 'wp_update_nav_menu', 'io_delete_menu_cache', 10, 1 );
function io_delete_menu_cache($menu_id) {  
    if (wp_using_ext_object_cache()){
        //$_menu = wp_get_nav_menu_object( $menu_id );
        wp_cache_delete('io_menu_list_'.$menu_id);
    }
    delete_transient('io_menu_list_'.$menu_id);
}
# 主题设置项变更排序相关选项后删除缓存
# --------------------------------------------------------------------
add_action( 'csf_io_get_option_saved', 'io_delete_home_post_cache', 10, 2 );
function io_delete_home_post_cache($data,$_this) {  
    if( io_get_option('user_center')                != $data['user_center']                 || 
        io_get_option('rewrites_types')             != $data['rewrites_types']              || 
        io_get_option('rewrites_end')               != $data['rewrites_end']                || 
        io_get_option('sites_rewrite')['post']      != $data['sites_rewrite']['post']       || 
        io_get_option('sites_rewrite')['taxonomy']  != $data['sites_rewrite']['taxonomy']   || 
        io_get_option('sites_rewrite')['tag']       != $data['sites_rewrite']['tag']        || 
        io_get_option('app_rewrite')['post']        != $data['app_rewrite']['post']         || 
        io_get_option('app_rewrite')['taxonomy']    != $data['app_rewrite']['taxonomy']     || 
        io_get_option('app_rewrite')['tag']         != $data['app_rewrite']['tag']          || 
        io_get_option('book_rewrite')['post']       != $data['book_rewrite']['post']        || 
        io_get_option('book_rewrite')['taxonomy']   != $data['book_rewrite']['taxonomy']    || 
        io_get_option('book_rewrite')['tag']        != $data['book_rewrite']['tag']         || 
        io_get_option('ioc_category')               != $data['ioc_category'])
    {
        //wp_safe_redirect( admin_url( 'options-permalink.php?settings-updated=true' ) );
        io_refresh_rewrite();
        flush_rewrite_rules();
    }
    if (wp_using_ext_object_cache()){
        //添加判断条件
        if( io_get_option('home_sort')['favorites'] != $data['home_sort']['favorites']  || 
            io_get_option('home_sort')['apps']      != $data['home_sort']['apps']       || 
            io_get_option('home_sort')['books']     != $data['home_sort']['books']      || 
            io_get_option('home_sort')['category']  != $data['home_sort']['category']   || 
            io_get_option('show_sticky')            != $data['show_sticky']             || 
            io_get_option('category_sticky')        != $data['category_sticky']         || 
            io_get_option('sites_sortable')         != $data['sites_sortable'])
        {
            wp_cache_flush();
        }else{
            wp_cache_delete('io_options_cache', 'options');
        }
    }
}
/* 
 * 编辑文章排序后删除对应缓存 id
 * --------------------------------------------------------------------
 */
function io_edit_post_delete_home_cache( $terms, $taxonomy='favorites' )
{
    if (wp_using_ext_object_cache()){
        $site_n= io_get_option('card_n')[$taxonomy];
        $ajax = 'ajax-url';
        //$slug = get_term_by( 'id', $terms, 'favorites')->slug;
        $cache_key      = 'io_home_posts_'.$terms.'_'.$taxonomy.'_'. $site_n.'_';
        $cache_ajax_key = 'io_home_posts_'.$terms.'_'.$taxonomy.'_'. $site_n.'_'.$ajax;
        wp_cache_delete($cache_key,'home-card');
        wp_cache_delete($cache_ajax_key,'home-card');
    }
} 
add_action( "csf_sites_post_meta_save_before", 'io_meta_saved_delete_home_cache_article',10,2 );
function io_meta_saved_delete_home_cache_article( $data, $post_id )
{
    if (wp_using_ext_object_cache()){
        //添加判断条件
        if( get_post_meta($post_id, '_sites_order', true) != $data['_sites_order']){
            // 删除缓存
            $terms = get_the_terms($post_id,'favorites');
            foreach($terms as $term){
                io_edit_post_delete_home_cache($term->term_id,'favorites');
            } 
        }
    }
}
//删除分类后删除对应缓存
add_action("delete_term", "io_delete_term_delete_cache",10,5);
function io_delete_term_delete_cache($term, $tt_id, $taxonomy, $deleted_term, $object_ids){
    io_edit_post_delete_home_cache($tt_id, $taxonomy);
}

# 替换用户链接
# --------------------------------------------------------------------
add_filter('author_link', 'author_link', 10, 2);
function author_link( $link, $author_id) {
    global $wp_rewrite;
    $author_id = (int) $author_id;
    $link = $wp_rewrite->get_author_permastruct();
    if ( empty($link) ) {
        $file = home_url( '/' );
        $link = $file . '?author=' . $author_id;
    } else {
        $link = str_replace('%author%', $author_id, $link);
        $link = home_url( user_trailingslashit( $link ) );
    }
    return $link;
}
add_filter('request', 'author_link_request');
function author_link_request( $query_vars ) {
    if ( array_key_exists( 'author_name', $query_vars ) ) {
        global $wpdb;
        $author_id=$query_vars['author_name'];
        if ( $author_id ) {
            $query_vars['author'] = $author_id;
            unset( $query_vars['author_name'] );    
        }
    }
    return $query_vars;
}
# 屏蔽用户名称类
# --------------------------------------------------------------------
add_filter('comment_class','remove_comment_body_author_class');
add_filter('body_class','remove_comment_body_author_class');
function remove_comment_body_author_class( $classes ) {
    foreach( $classes as $key => $class ) {
    if(strstr($class, "comment-author-")||strstr($class, "author-")) {
            unset( $classes[$key] );
        }
    }
    return $classes;
}
function chack_name($filename){
    $filename     = remove_accents( $filename );
    $special_chars = array( '?', '[', ']', '/', '\\', '=', '<', '>', ':', ';', ',', "'", '"', '$', '#', '*', '(', ')', '~', '`', '!', '{', '}', '%', '+', '’', '«', '»', '”', '“', chr( 0 ) );
    static $utf8_pcre = null;
    if ( ! isset( $utf8_pcre ) ) {
        $utf8_pcre = @preg_match( '/^./u', 'a' );
    }
    if ( !seems_utf8( $filename ) ) {
        $_ext     = pathinfo( $filename, PATHINFO_EXTENSION );
        $_name    = pathinfo( $filename, PATHINFO_FILENAME );
        $filename = sanitize_title_with_dashes( $_name ) . '.' . $_ext;
    }
    if ( $utf8_pcre ) {
        $filename = preg_replace( "#\x{00a0}#siu", ' ', $filename );
    }
    $filename = str_replace( $special_chars, '', $filename );
    $filename = str_replace( array( '%20', '+' ), '', $filename );
    $filename = preg_replace( '/[\r\n\t -]+/', '', $filename );
    return esc_attr($filename);
}
function loading_type(){
    $type = io_get_option('loading_type')?:'rand';
    if($type == 'rand')
        $type = wp_rand(1,7);
    include( get_theme_file_path("/templates/loadfx/loading-{$type}.php") );
}
# 禁止谷歌字体
# --------------------------------------------------------------------
add_action( 'init', 'remove_open_sans' );
function remove_open_sans() {
    wp_deregister_style( 'open-sans' );
    wp_register_style( 'open-sans', false );
    wp_enqueue_style('open-sans','');
}
# 字体增加
# --------------------------------------------------------------------
add_filter('tiny_mce_before_init', 'custum_fontfamily');
function custum_fontfamily($initArray){  
   $initArray['font_formats'] = "微软雅黑='微软雅黑';宋体='宋体';黑体='黑体';仿宋='仿宋';楷体='楷体';隶书='隶书';幼圆='幼圆';";  
   return $initArray;  
} 
/**
 * 关键词加链接
 * ******************************************************************************************************
 */
if (io_get_option('tag_c')['switcher']) {
    add_filter('the_content','tag_link',8);
    function tag_link($content){
        global $post_type;
        $match_num_from = 1;        //配置：一个关键字少于多少不替换  
        $match_num_to = io_get_option('tag_c')['chain_n'];        //配置：一个关键字最多替换，建议不大于2  
        $tax = array('post_tag','apptag','sitetag','booktag');
        $post_tags = get_terms( 
            array(
                'taxonomy'      => $tax, 
                'number'        => 256, 
                'orderby'       => 'count', 
                'order'         => 'DESC', 
                'hide_empty'    => true,
            )
        );
        if ($post_tags) {
            $sort_func = function($a, $b){
                if ( $a->name == $b->name ) return 0;
                return ( strlen($a->name) > strlen($b->name) ) ? -1 : 1;
            };
            usort($post_tags, $sort_func);//重新排序
            $ex_word = '';
            $case = false ? "i" : ""; //配置：忽略大小写 true是开，false是关  
            foreach($post_tags as $tag) {
                $link = get_tag_link($tag->term_id);
                $keyword = $tag->name;
                $cleankeyword = stripslashes($keyword);
                $url = "<a class=\"external\" href=\"$link\" title=\"".str_replace('%s',addcslashes($cleankeyword, '$'),__('查看与 %s 相关的文章', 'i_theme' ))."\"";
                $url .= ' target="_blank"';
                $url .= ">".addcslashes($cleankeyword, '$')."</a>";
                $limit = rand($match_num_from,$match_num_to);
                $ex_word = preg_quote($cleankeyword, '\''); 
                $content = preg_replace( '|(<a[^>]+>)(.*)<pre.*?>('.$ex_word.')(.*)<\/pre>(</a[^>]*>)|U'.$case, '$1$2%&&&&&%$4$5', $content);//a标签，免混淆处理  
                $content = preg_replace( '|(<img)(.*?)('.$ex_word.')(.*?)(>)|U'.$case, '$1$2%&&&&&%$4$5', $content);//img标签
                $content = preg_replace( '|(\[)(.*?)('.$ex_word.')(.*?)(\])|U'.$case, '$1$2%&&&&&%$4$5', $content);//短代码标签
                $cleankeyword = preg_quote($cleankeyword,'\'');
                $regEx = '\'(?!((<.*?)|(<a.*?)))('. $cleankeyword . ')(?!(([^<>]*?)>)|([^>]*?</a>))\'s' . $case;
                $content = preg_replace($regEx,$url,$content,$limit);
                $content = str_replace( '%&&&&&%', stripslashes($ex_word), $content);//免混淆还原处理  
            }
        }
        return $content;
    }
}
# 移除 WordPress 文章标题前的“私密/密码保护”提示文字
# --------------------------------------------------------------------
add_filter('private_title_format', 'remove_title_prefix');//私密
add_filter('protected_title_format', 'remove_title_prefix');//密码保护
function remove_title_prefix($content) {
    return '%s';
}
# FancyBox图片灯箱
# --------------------------------------------------------------------
//add_filter('the_content', 'io_fancybox');
function io_fancybox($content){ 
    global $post;
    $title = $post->post_title;
    $pattern = array("/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png|swf)('|\")(.*?)>(.*?)<\/a>/i","/<img(.*?)src=('|\")([^>]*).(bmp|gif|jpeg|jpg|png|swf)('|\")(.*?)>/i");
    $replacement = array('<a$1href=$2$3.$4$5 data-fancybox="images"$6 data-caption="'.$title.'">$7</a>','<a$1href=$2$3.$4$5 data-fancybox="images" data-caption="'.$title.'"><img$1src=$2$3.$4$5$6></a>');
    $content = preg_replace($pattern, $replacement, $content);
    return $content;
}
/**
 * 去掉正文图片外围标签p、自动添加 a 标签和 data-original
 * ******************************************************************************************************
 */
function lazyload_fancybox($content) {
    global $post;
    $title = $post->post_title;
    $loadimg_url=get_template_directory_uri().'/images/t.png';
      //判断是否为文章页或者页面
    if(!is_single())
        return $content;
    if(!is_feed()||!is_robots()) {
        $content = preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '$1$2$3', $content);
        //添加 fancybox
        $pattern = "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png|swf)('|\")(.*?)>(.*?)<\/a>/i";
        $replacement = '<a$1href=$2$3.$4$5 data-fancybox="images" data-caption="'.$title.'"$6>$7</a>';
        $content = preg_replace($pattern, $replacement, $content);
        //添加懒加载
        $imgpattern   = '/<img(.*?)src=[\'|"]([^\'"]+)[\'|"](.*?)>/i';
        //$imgpattern = "/<img(.*?)src=('|\")([^>]*).(bmp|gif|jpeg|jpg|png|swf)('|\")(.*?)>/i";
        if(io_get_option('lazyload')){
            $imgreplacement = '<img$1data-src="$2" src="'.$loadimg_url.'" alt="'.$title.'"$3>';
        } else {
            $imgreplacement = '<img$1src="$2" alt="'.$title.'"$3>';
        }
        $content = preg_replace($imgpattern,$imgreplacement,$content);
    }
    return $content;
} 
add_filter ('the_content', 'lazyload_fancybox',10);
function find_character($string,$arr){
    preg_match_all('#('.implode('|', $arr).')#', $string, $wordsFound);
    $wordsFound = array_unique($wordsFound[0]);
    if(count($wordsFound) > 0){
        return true;
    }else{
        return false;
    }
}
function getkm($len = 20){
    $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    $strlen = strlen($str);
    $randstr = "";
    for ($i = 0; $i < $len; $i++) {
        $randstr .= $str[mt_rand(0, $strlen - 1)];
    }
    return $randstr;
}
/**
 * 加密内容
 * @param string $input 需加密的内容
 * @return string
 */
function base64_io_encode($input){
    $url = htmlspecialchars_decode($input);
    // = at the end is just padding to make the length of the str divisible by 4
    if(!$key = get_option('iotheme_encode_key')){
        $key = getkm();
        update_option('iotheme_encode_key',$key);
    }
    $url = str_rot_pass($url, $key);
    return rtrim(strtr(base64_encode($url), '+/', '-_'), '=');
}
/**
 * 解密内容 
 * @param string $input 需解密的内容
 * @return string
 */
function base64_io_decode($input){
    $url = base64_decode(str_pad(strtr($input, '-_', '+/'), strlen($input) % 4, '=', STR_PAD_RIGHT));
    $key = get_option('iotheme_encode_key');
    return str_rot_pass($url, $key, true);
}
/**
 * 根据 KEY 相应的ascii值旋转每个字符串 
 * @param string $str
 * @param string $key 
 * @param bool $decrypt 
 * @return string
 */
function str_rot_pass($str, $key, $decrypt = false){
    
    // if key happens to be shorter than the data
    $key_len = strlen($key);
    
    $result = str_repeat(' ', strlen($str));
    
    for($i=0; $i<strlen($str); $i++){

        if($decrypt){
            $ascii = ord($str[$i]) - ord($key[$i % $key_len]);
        } else {
            $ascii = ord($str[$i]) + ord($key[$i % $key_len]);
        }
    
        $result[$i] = chr($ascii);
    }
    
    return $result;
}
# 正文外链跳转和自动nofollow
# --------------------------------------------------------------------
add_filter( 'the_content', 'ioc_seo_wl',10);
function ioc_seo_wl( $content ) {
    //$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>";
    $regexp = "<a(.*?)href=('|\")([^>]*?)('|\")(.*?)>(.*?)<\/a>";
    if(preg_match_all("/$regexp/i", $content, $matches, PREG_SET_ORDER)) { // s 匹配换行
        if( !empty($matches) ) {
            $srcUrl = get_option('siteurl'); 
            for ($i=0; $i < count($matches); $i++)
            { 
                $url = $matches[$i][3];
                $pos = strpos($url,$srcUrl); 
                if ( $pos === false ) {
                    $_url=$matches[$i][3];
                    if(io_get_option('is_go') && is_go_exclude($_url)===false && !preg_match('/\.(jpg|jepg|png|ico|bmp|gif|tiff)/i',$_url) && !preg_match('/(ed2k|thunder|Flashget|flashget|qqdl):\/\//i',$_url)) {
                        $_url= esc_url(home_url()). "/go/?url=" .base64_encode($_url);
                    }
                    $tag = '<a'.$matches[$i][1].'href='.$matches[$i][2].$_url.$matches[$i][4].$matches[$i][5].'>';
                    $tag2 = '<a'.$matches[$i][1].'href='.$matches[$i][2].$url.$matches[$i][4].$matches[$i][5].'>';
                    $noFollow = '';
                    $pattern = '/target\s*=\s*"\s*_blank\s*"/';
                    preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
                    if( count($match) < 1 ){
                        $noFollow .= ' target="_blank" ';
                    }
                    $pattern = '/rel\s*=\s*"\s*[n|d]ofollow\s*"/';
                    preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
                    if( count($match) < 1 ){
                        $noFollow .= ' rel="nofollow noopener" ';
                    }
                    if(strpos($matches[$i][6],'<img') === false){
                        $pattern = '/class\s*=\s*"\s*(.*?)\s*"/';  //追加class的方法-------------------------------------------------------------
                        preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE); 
                        if( count($match) > 0 ){
                            $tag = str_replace($match[1][0],'external '.$match[1][0],$tag); 
                        }else{
                            $noFollow .= ' class="external" ';
                        }
                    }
                    $tag = rtrim ($tag,'>');
                    $tag .= $noFollow.'>';
                    $content = str_replace($tag2,$tag,$content); 
                }
            }
        }
    }
    return $content;
}

# 评论作者链接跳转 or 评论作者链接新窗口打开
# --------------------------------------------------------------------
if (io_get_option('is_go')) {
    add_filter('get_comment_author_link', 'comment_author_link_to');
    function comment_author_link_to() {
        $encodeurl = get_comment_author_url();
        $url = esc_url(home_url()).'/go/?url=' . base64_encode($encodeurl);
        $author = get_comment_author();
        if ( empty( $encodeurl ) || 'http://' == $encodeurl )
            return $author;
        else
            return "<a href='$url' target='_blank' rel='nofollow noopener noreferrer' class='url'>$author</a>";
    }
} else {
    add_filter('get_comment_author_link', 'comment_author_link_blank');
    function comment_author_link_blank() {
        $url    = get_comment_author_url();
        $author = get_comment_author();
        if ( empty( $url ) || 'http://' == $url )
            return $author;
        else
            return "<a target='_blank' href='$url' rel='nofollow noopener noreferrer' class='url'>$author</a>";
    }
}
# 定制CSS
# --------------------------------------------------------------------
function modify_css(){
    $css = '';
    if (io_get_option("custom_css")) {
        $css .= substr(io_get_option("custom_css"), 0);
    }
    if(io_get_option('home_width')){
        $css .= '.content-site{max-width:'.io_get_option('h_width').'px}';
    }
    if($css != '')
        echo "<style>" . $css . "</style>";
}
# 移除系统菜单模块
# -------------------------------------------------------------------- 
if ( is_admin() ) {   
    add_action('admin_init','remove_submenu');  
    function remove_submenu() {   
        remove_submenu_page( 'themes.php', 'theme-editor.php' );   //移除主题编辑页
    }  
}  
add_action( 'current_screen', 'block_theme_editor_access' );
function block_theme_editor_access() {
    if ( is_admin() ) {
        $screen = get_current_screen();
        if ( 'theme-editor' == $screen->id ) {
            wp_redirect( admin_url() );
            exit;
        }
    }
} 
function add_popup(){
    if(is_404() || !io_get_option('enable_popup')) return;
    $popup_set = io_get_option('popup_set');
    if( $popup_set['only_home'] && !(is_home() || is_front_page()) ) return;
    date_default_timezone_set('Asia/Shanghai');
    $update_date = $popup_set['logged_show']?strtotime($popup_set['update_date']):'1';
    if( !$popup_set['show'] || (  $popup_set['show'] && ( !isset($_COOKIE['io_popup_tips'])||( isset($_COOKIE['io_popup_tips']) && $_COOKIE['io_popup_tips'] != $update_date ) )  ) ){ 
        if(!$popup_set['valid'] ||( $popup_set['valid'] && validity_inspection($popup_set['begin_time'],$popup_set['end_time']) ) ){
        ?>
        <div id='io-popup-tips' class="io-bomb" data-date='<?php echo $update_date ?>'>
            <div class="io-bomb-overlay"></div>
            <div class="io-bomb-body" style="max-width:<?php echo $popup_set['width'] ?>px">
                <div class="io-bomb-content io-popup-tips-content rounded m-3 p-4 p-md-5 bg-white">
                    <?php if($title=$popup_set['title']){
                        echo '<h3 class="mb-4 pb-md-2 text-center">'.$title.'</h3>';
                    } ?>
                    <div>
                        <?php echo $popup_set['content'] ?>
                    </div>
                </div>
                <div class="btn-close-bomb mt-2 text-center">
                    <i class="iconfont popup-close icon-close-circle"></i>
                </div>
            </div>
            <script>
                var cookieValue='<?php echo $update_date ?>';
                var exdays = <?php echo is_user_logged_in()&&$popup_set['logged_show']?'30':'1' ?>;
                $(document).ready(function(){
                    <?php echo $popup_set['show']?"if(getCookie('io_popup_tips')!=cookieValue)":"" ?>
                    setTimeout(function(){ 
                        $('#io-popup-tips').addClass('io-bomb-open');
                    },<?php echo $popup_set['delay'].'000' ?>);  
                });
                $(document).on('click','.popup-close',function(ev) {
                    $('#io-popup-tips').removeClass('io-bomb-open').addClass('io-bomb-close');
                    <?php echo ($popup_set['show']?'setCookie("io_popup_tips",cookieValue,exdays);':'') ?>
                    setTimeout(function(){
                        $('#io-popup-tips').remove(); 
                    },600);
                });
            </script>
        </div>
    <?php }
    }
}
function add_remind_bind(){
    $user = wp_get_current_user();
    if(!$user->ID) {
        return; 
    }
    if(is_404() || !io_get_option('remind_bind') || get_user_meta($user->ID, 'email_status', true )) return; 

    if( !io_get_option('remind_only') || (  io_get_option('remind_only') && !isset($_COOKIE['io_remind_only'])) || (isset($_COOKIE['io_remind_only'])&&  $_COOKIE['io_remind_only']!="1") ){ 
        ?>
        <div id='io-remind-bind' class="io-bomb">
            <div class="io-bomb-overlay"></div>
            <div class="io-bomb-body text-center" style="max-width:260px">
                <div class="io-bomb-content rounded bg-white"> 
                <i class="iconfont icon-tishi icon-8x text-success"></i> 
                            <p class="text-md mt-3">你没有绑定邮箱！</p> 
                            <a href="<?php echo home_url('/login/?action=bind&type=bind') ?>" class="btn btn-danger mt-3 popup-bind-close">前往绑定</a>
                </div>
                <div class="btn-close-bomb mt-2 text-center">
                    <i class="iconfont popup-bind-close icon-close-circle"></i>
                </div>
            </div>
            <script>  
                $(document).ready(function(){
                    <?php echo io_get_option('remind_only')?"if(getCookie('io_remind_only')!=1)":"" ?>
                        $('#io-remind-bind').addClass('io-bomb-open');
                });
                $(document).on('click','.popup-bind-close',function() {
                    $('#io-remind-bind').removeClass('io-bomb-open').addClass('io-bomb-close');
                    <?php echo (io_get_option('remind_only')?'setCookie("io_remind_only",1,1);':'') ?>
                    setTimeout(function(){
                        $('#io-remind-bind').remove(); 
                    },600);
                });
            </script>
        </div>
    <?php
    }
}
function io_footer_box() {
    add_popup();
    add_remind_bind();
}
add_action( 'wp_footer', 'io_footer_box' );
/**
 * @description: 
 * @param date $begin_time 开始时间 2021/05/20
 * @param date $end_time 结束时间 2021/06/18
 * @return bool  
 */
function validity_inspection($begin_time,$end_time){
    date_default_timezone_set('Asia/Shanghai');
    $today=date("y-m-d h:i:s");  
    $state = true;
    if( strtotime($today)<strtotime($begin_time." 00:00:00"))
        $state = false;
    elseif( strtotime($today)>strtotime($end_time." 23:59:59"))
        $state = false;
    return $state;
}
# 登陆页添加验证码
# -------------------------------------------------------------------- 
function add_login_head() {
    echo '<script src="https://ssl.captcha.qq.com/TCaptcha.js"></script>';
    echo '<style type="text/css">.login_button {line-height:38px;border-radius:3px;cursor:pointer;color:#fff;background:#f1404b;border:2px solid #f1404b;font-size:14px;margin-bottom:20px;text-align:center;transition:.5s;}.login_button:hover{color:#fff;background:#111;border-color:#111;}</style>'; 
}
function add_captcha_body(){ ?>
    <input type="hidden" id="wp007_tcaptcha" name="tcaptcha_007" value="" />
    <input type="hidden" id="wp007_ticket" name="tencent_ticket" value="" />
    <input type="hidden" id="wp007_randstr" name="tencent_randstr" value="" /> 
    <?php if(io_get_option('user_center')){ ?>
    <script type="text/javascript">
        window.loginTicket = function(res){
            if(res.ret === 0){
                document.getElementById("wp007_ticket").value = res.ticket;
                document.getElementById("wp007_randstr").value = res.randstr;
                document.getElementById("wp007_tcaptcha").value = 1;
                $("#wp_login_form").submit();  
            }
            else if(res.ret === 2) {
                $("div#result").html("");
                $("<div>").html("你没有完成验证！").appendTo("div#result").hide().fadeIn("slow");  
            }
        }
    </script>
    <?php }else{ ?>
    <div id="TencentCaptcha" data-appid="<?php echo io_get_option('io_captcha')['appid_007'] ?>" data-cbfn="callback" class="login_button"><?php _e('验证',"i_theme") ?></div>
    <script>
        window.callback = function(res){
            if(res.ret === 0){
                var but = document.getElementById("TencentCaptcha");
                document.getElementById("wp007_ticket").value = res.ticket;
                document.getElementById("wp007_randstr").value = res.randstr;
                document.getElementById("wp007_tcaptcha").value = 1;
                but.style.cssText = "color:#fff;background:#4fb845;border-color:#4fb845;pointer-events:none";
                but.innerHTML = "<?php  _e('验证成功',"i_theme") ?>";
            }
        }
    </script>
    <?php } ?>
<?php
}
function validate_tcaptcha_login($user) { 
    $slide=$_POST['tcaptcha_007'];
    if($slide == ''){
        return  new WP_Error('broke', __("请先验证！！！","i_theme"));
    }
    else{
        $result = validate_ticket($_POST['tencent_ticket'],$_POST['tencent_randstr']);
        if ($result['result']) {
            return $user;
        } else{
            return  new WP_Error('broke', $result['message']);
        }
    }
    return $user;
}
if( LOGIN_007 && io_get_option('io_captcha')['tcaptcha_007']){
    add_action('login_head', 'add_login_head');
    add_action('login_form','add_captcha_body');
    if(!io_get_option('user_center')){
        add_filter('wp_authenticate_user',  'validate_tcaptcha_login',100,1);
    }else{
        add_action('lostpassword_form','add_captcha_body');
        add_action('register_form','add_captcha_body');
        add_action('io_bind_form','add_captcha_body');
    }
}
function io_ajax_is_robots(){
    $captcha = io_get_option('io_captcha');
    if( LOGIN_007 && $captcha['tcaptcha_007'] && $captcha['appid_007'] ){
        if(isset($_REQUEST['comment']) && !$captcha['comment_007']){
            return true;
        }
        if(isset($_REQUEST['tcaptcha_007'])&&!empty($_REQUEST['tcaptcha_007'])&&($_REQUEST['tcaptcha_007']=='1')){
            $tencent007 = validate_ticket($_REQUEST['tencent_ticket'],$_REQUEST['tencent_randstr']);
            if($tencent007['result']){
                return true;
            }else{
                echo (json_encode(array('status' => 2, 'msg' => $tencent007['message'])));
                exit();
            }
        }else{
            echo (json_encode(array('status' => 2, 'msg' => '人机验证失败!')));
            exit();
        }
    }
    return true;
}
# 重写规则
# --------------------------------------------------------------------
add_action('generate_rewrite_rules', 'io_rewrite_rules' );  
if ( ! function_exists( 'io_rewrite_rules' ) ){ 
    function io_rewrite_rules( $wp_rewrite ){   
        $new_rules = array(    
            'go/?$'          => 'index.php?custom_page=go',
            'hotnews/?$'        => 'index.php?custom_page=hotnews',
            //'login/?$'       => 'index.php?custom_page=login',
        ); //添加翻译规则   
        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;   
        //php数组相加   
    }   
} 
add_action('query_vars', 'io_add_query_vars');  
if ( ! function_exists( 'io_add_query_vars' ) ){ 
    function io_add_query_vars($public_query_vars){     
        $public_query_vars[] = 'custom_page'; //往数组中添加custom_page   
        return $public_query_vars;     
    }  
} 
add_action("template_redirect", 'io_template_redirect');   //模板载入规则  
if ( ! function_exists( 'io_template_redirect' ) ){ 
    function io_template_redirect(){   
        global $wp;   
        global $wp_query, $wp_rewrite;  
        if( !isset($wp_query->query_vars['custom_page']) )   
            return;    
        $reditect_page =  $wp_query->query_vars['custom_page'];   
        $wp_query->is_home = false;
        switch ($reditect_page) {
            case 'go':
                include(get_theme_file_path('/go.php'));
                die();
            case 'hotnews':
                include(get_theme_file_path('/templates/hot/hot-home.php'));
                die();
            //case 'login':
            //    include(get_theme_file_path('/login.php'));
            //    die();
        }
    }
} 
# 激活主题更新重写规则
# --------------------------------------------------------------------
add_action( 'load-themes.php', 'io_flush_rewrite_rules' );   
function io_flush_rewrite_rules() {   
    global $pagenow;   
    if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) )   
        io_refresh_rewrite();   
}
function io_refresh_rewrite()
{
    // 如果启用了memcache等对象缓存，固定链接的重写规则缓存对应清除
    if (wp_using_ext_object_cache()){
        wp_cache_flush();
    }
    // 刷新固定链接
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}
# 搜索只查询文章和网址。
# --------------------------------------------------------------------
//add_filter('pre_get_posts','searchfilter');
function searchfilter($query) {
    //限定对搜索查询和非后台查询设置
    if ($query->is_search && !is_admin() ) {
        $query->set('post_type',array('sites','post','app'));
    }
    return $query;
}
# 修改搜索查询的sql代码，将postmeta表左链接进去。
# --------------------------------------------------------------------
add_filter('posts_join', 'cf_search_join' );
function cf_search_join( $join ) {
    if(is_admin())
        return $join;
    global $wpdb;
    if ( is_search() ) {
        $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }
    return $join;
}
add_filter('posts_where', 'cf_search_where');// 在wordpress查询代码中加入自定义字段值的查询。
function cf_search_where( $where ) {
    if(is_admin())
        return $where; 
    global $pagenow, $wpdb;
    if ( is_search() ) {
        $where = preg_replace("/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
        "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
    }
    return $where;
}
// 去重
function cf_search_distinct( $where ) {
    if( is_admin() ) return $where;
    global $wpdb;
    if ( is_search() )  {
        return 'DISTINCT';
    }
    return $where;
}
add_filter( "posts_distinct", "cf_search_distinct" );

function wp_insert_score($comment_ID,$comment_data) { 
    global $iodb;
    $post_ID = $comment_data->comment_post_ID;
    //文章作者ID
    $post_author_id = get_post($post_ID)->post_author;
    //给评论者站内通知
    if($comment_data->comment_parent != 0) { 
        //父级评论者id
        $user_id = get_comment($comment_data->comment_parent)->user_id;
        if($user_id != 0) {
            $iodb->addMessages($user_id,'comment',sprintf( __('%s在「%s」中回复了你', 'i_theme'), $comment_data->comment_author, get_post($post_ID)->post_title), $comment_data->comment_date, $comment_data->comment_content, $comment_data->user_id, $comment_data->comment_author );
        }
    }
    //给文章作者站内通知
    if($post_author_id != $comment_data->user_id){
        if($comment_data->comment_parent != 0){
            $user_id = get_comment($comment_data->comment_parent)->user_id;
            if($user_id != $post_author_id) {
                $iodb->addMessages($post_author_id,'notification',sprintf( __('%s在你的文章「%s」中发表了评论', 'i_theme'), $comment_data->comment_author, get_post($post_ID)->post_title), $comment_data->comment_date, $comment_data->comment_content );
            }
        }else{
            $iodb->addMessages($post_author_id,'notification',sprintf( __('%s在你的文章「%s」中发表了评论', 'i_theme'), $comment_data->comment_author, get_post($post_ID)->post_title), $comment_data->comment_date, $comment_data->comment_content );
        }
    }
}
add_action('wp_insert_comment','wp_insert_score',10,2);
/**
 * 判断是否在微信APP内 
 */
function io_is_wechat_app()
{
    return strripos($_SERVER['HTTP_USER_AGENT'], 'micromessenger');
}

/**后台生成二维码图片 */
function io_get_qrcode_base64($url)
{
    //引入phpqrcode类库
    require_once get_theme_file_path('/inc/classes/phpqrcode.php');
    $errorCorrectionLevel = 'L'; //容错级别
    $matrixPointSize      = 6; //生成图片大小
    ob_start();
    QRcode::png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);
    $data = ob_get_contents();
    ob_end_clean();

    $imageString = base64_encode($data);
    header("content-type:application/json; charset=utf-8");
    return 'data:image/jpeg;base64,' . $imageString;
}
/**
 * 获取当前页面url.
 * --------------------------------------------------------------------------------------
 */
function io_get_current_url($method = 'php')
{
    if ($method === 'wp') {
        global $wp;
        $url = get_option('permalink_structure') == '' ? add_query_arg($wp->query_string, '', home_url($wp->request) ) : home_url(add_query_arg(array(), $wp->request));
        return $url;
    }

    $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $port_str = ($_SERVER['SERVER_PORT'] == '80' && $scheme == 'http') || ($_SERVER['SERVER_PORT'] == '443' && $scheme == 'https') ? '' : ':' . $_SERVER['SERVER_PORT'];
    $url = $scheme . '://' . $_SERVER['HTTP_HOST'] . $port_str . $_SERVER["REQUEST_URI"];
    return $url;
}

/**
 * 为链接添加重定向链接.
 * --------------------------------------------------------------------------------------
 */
function io_add_redirect($url, $redirect = '')
{
    if ($redirect) {
        return add_query_arg('redirect_to', urlencode($redirect), $url);
    } elseif (isset($_GET['redirect_to'])) {
        return add_query_arg('redirect_to', urlencode(esc_url_raw($_GET['redirect_to'])), $url);
    } elseif (isset($_GET['redirect'])) {
        return add_query_arg('redirect_to', urlencode(esc_url_raw($_GET['redirect'])), $url);
    }

    return add_query_arg('redirect_to', urlencode(home_url()), $url);
}
function is_http($url){
    $preg = "/^http(s)?:\\/\\/.+/";
    if(preg_match($preg,$url)){
        return true;
    }else{
        return false;
    }
}
function custom_login_page() {
    //自定义登录页面风格
    echo'<style type="text/css">#login form {-webkit-box-shadow:0 2px 5px 0 rgba(146,146,146,.1);-moz-box-shadow:0 2px 5px 0 rgba(146,146,146,.1);box-shadow:0 8px 25px 0 rgba(146,146,146,0.21);}#login form .forgetmenot{float:none}
    .login #login_error, .login .message, .login .success {border-left: 4px solid #f1404b;box-shadow: 0 5px 10px 0 rgba(146,146,146,0.21);}
    #login form p.submit{padding: 20px 0 0;}#login form p.submit .button-primary{float:none;background-color: #f1404b;font-weight: bold;color: #fff;width: 100%;height: 40px;border-width: 0;text-shadow: none!important;border-color:none;transition: .5s;}#login form input{box-shadow:none!important;outline:none!important}
    #login form p.submit .button-primary:hover{background-color: #444;}</style>';  

    //自动修改网站登陆页面logo
    if($logo= io_get_option('logo_small_light') ) {
        echo '<style type="text/css">
        .login h1 a { background-image:url('.$logo.');max-width:280px;background-position: center;}
        </style>';      
    }else{
    echo '<style type="text/css">
        .login h1 a { background-image:url('.get_template_directory_uri() .'/images/logo.png);background-position: center;}
        </style>';   
    } 
}
add_action('login_head', 'custom_login_page');
//登录页面的LOGO链接为首页链接
add_filter('login_headerurl',function() {return get_bloginfo('url');});
//登陆界面logo的title为博客副标题
add_filter('login_headertext',function() {return get_bloginfo( 'description' );});

// 用户注册成功后通知
add_action('user_register','io_register_msg'); 
function io_register_msg($user_id){ 
    io_create_message($user_id,0,'System','notification',sprintf( __('欢迎来到%s，请首先在个人设置中完善您的账号信息。', 'i_theme'), get_bloginfo('name') ));
}

/**
 * 创建消息.
 *
 * @param int    $user_id   接收用户ID
 * @param int    $sender_id 发送者ID(可空)
 * @param string $sender    发送者
 * @param string $type      消息类型 cash|货币 comment|评论 credit|积分 notification|通知 star|收藏
 * @param string $title     消息标题
 * @param string $content   消息内容
 * @param string $status    消息状态
 * @param string $date      消息时间
 *
 * @return bool
 */
function io_create_message($user_id = 0,  $sender_id = 0, $sender = 0, $type = '', $title = '',$content = '', $date = '', $status = 'publish')
{
    $user_id = absint($user_id);
    $sender_id = absint($sender_id);
    $title = sanitize_text_field($title);

    if (!$user_id || empty($title)) {
        return false;
    }

    date_default_timezone_set(get_option('timezone_string'));

    $type = $type ? sanitize_text_field($type) : 'notification';
    $date = $date ? $date : date('Y-m-d H:i:s');
    
    $content = htmlspecialchars($content);

    global $iodb; 

    if ($iodb->addMessages($user_id, $type, $title,$date, $content, $sender_id, $sender,$status)) {
        return true;
    }

    return false;
}
/**
 * 添加消息
 * @param  Int $user_id 消息对象
 * @param  String $msg_type 消息类型 cash|货币 comment|评论 credit|积分 notification|通知 star|收藏
 * @param  String $msg_title 消息标题
 * @param  String $msg_date 消息时间
 * @param  String $msg_content 消息内容 
 * @param  Int $sender_id  发送人 ID 默认 0
 * @param  String $sender 发送人 默认 System
 * @param  String $msg_status 消息状态
 */

if( function_exists('erphp_register_extra_fields') ) {//改--
    add_action( 'register_award', 'io_register_award', 10, 2 );
    function io_register_award($user_id,$money) {  
        global $iodb;
        if($money > 0) { 
            $iodb->addMessages($user_id,'cash',sprintf( __('获得注册奖励%s', 'i_theme'), $money.get_option('ice_name_alipay') ));
        }
    }
    add_action( 'promotion_award', 'io_promotion_award', 10, 3 );
    function io_promotion_award($aff_id,$user_id,$money) {
        global $wpdb,$iodb; 
        $sql = "SELECT $wpdb->users.display_name FROM $wpdb->users WHERE ID = $user_id";
        $user_name = $wpdb->get_var($sql);
        if($money > 0) { 
            $iodb->addMessages($aff_id,'cash',sprintf( __('获得注册推广（来自%s的注册）奖励%s', 'i_theme'),$user_name, $money.get_option('ice_name_alipay') ));
        }
    }
}

/**
 * 查询IP地址
 */
function query_ip_addr($ip)
{
    $url = 'http://freeapi.ipip.net/'.$ip;
    $body = wp_remote_retrieve_body(wp_remote_get($url));
    $arr = json_decode($body);
    if ($arr[1] == $arr[2]) {
        array_splice($arr, 2, 1);
    }

    return implode($arr);
}

/**
 * 记录用户登录时间
 */
function user_last_login($user_login)
{
    $user = get_user_by('login', $user_login);
    $time = current_time('mysql');
    update_user_meta($user->ID, 'last_login', $time);
}
add_action('wp_login', 'user_last_login');

/**
 * 判断是否有非法名称
 */
function is_disable_username($name)
{
    $disable_reg_keywords = io_get_option('user_nickname_stint');
    $disable_reg_keywords = preg_split("/,|，|\s|\n/", $disable_reg_keywords);

    if (!$disable_reg_keywords || !$name) {
        return false;
    }
    foreach ($disable_reg_keywords as $keyword) {
        if (stristr($name, $keyword) || $keyword == $name) {
            return true;
        }
    }
    return false;
}
/**
 * 中文文字计数
 */
function _new_strlen($str, $charset = 'utf-8')
{
    //中文算一个，英文算半个
    return (int)((strlen($str) + mb_strlen($str, $charset)) / 4);
}
/**
 * 判断是否是重复昵称
 */
function io_nicename_exists($name)
{
    $db_name = false;
    if ($name) {
        global $wpdb;
        $db_name = $wpdb->get_var("SELECT id FROM $wpdb->users WHERE `user_nicename`='" . $name . "' OR `display_name`='" . $name . "' ");
        // 查询已登录用户
        $current_user_id = get_current_user_id();
        if($db_name && $current_user_id && $db_name == $current_user_id){
            $db_name = false;
        }
    }
    return $db_name;
}
/**
 * 判断用户名合法 
 * @param string $user_name
 * @param string $logn_in 登录流程
 * @return array
 */
function is_username_legal($user_name, $logn_in = false)
{

    if (!$user_name) {
        return array('error' => 1, 'msg' => '请输入用户名');
    }

    if (_new_strlen($user_name) < 2) {
        return array('error' => 1, 'msg' => '用户名太短');
    }
    if (_new_strlen($user_name) > 10) {
        return array('error' => 1, 'msg' => '用户名太长');
    }
    if (!$logn_in) {
        if (is_numeric($user_name)) {
            return array('error' => 1, 'msg' => '用户名不能为纯数字');
        }
        if (filter_var($user_name, FILTER_VALIDATE_EMAIL)) {
            return array('error' => 1, 'msg' => '请勿使用邮箱帐号作为用户名');
        }
        if (is_disable_username($user_name)) {
            return array('error' => 1, 'msg' => '昵称含保留或非法字符');
        }
        //重复昵称判断
        if (io_get_option('nickname_exists', true)) {
            if (io_nicename_exists($user_name)) {
                return array('error' => 1, 'msg' => '昵称已存在，请换一个试试');
            }
        }
    }

    return array('error' => 0);
}

function my_avatar( $avatar, $id_or_email,  $size = 96, $default = '', $alt = '' ,$args=NULL){  
    if ( is_numeric( $id_or_email ) )
        $user_id = (int) $id_or_email;
    elseif ( is_string( $id_or_email ) && ( $user = get_user_by( 'email', $id_or_email ) ) )
        $user_id = $user->ID;
    elseif ( is_object( $id_or_email ) && ! empty( $id_or_email->user_id ) )
        $user_id = (int) $id_or_email->user_id;
        
    if ( empty( $user_id ) )
        return $avatar;
    $type = get_user_meta( $user_id, 'avatar_type', true );
    $author_class = is_author( $user_id ) ? ' current-author' : '' ;
    switch ($type){
        case 'gravatar':
            return $avatar;
            break;
        case 'qq':
            return "<img alt='" . esc_attr( $alt ) . "' src='" . format_http(esc_url( get_user_meta( $user_id, 'qq_avatar', true )) ) . "' class='{$args['class']} avatar avatar-{$size}{$author_class} photo' height='{$size}' width='{$size}' />";
            break;
        case 'sina': 
            return "<img alt='" . esc_attr( $alt ) . "' src='" . format_http(esc_url( get_user_meta( $user_id, 'sina_avatar', true )) ) . "' class='{$args['class']} avatar avatar-{$size}{$author_class} photo' height='{$size}' width='{$size}' />";
            break;
        case 'wechat':
            return "<img alt='" . esc_attr( $alt ) . "' src='" . format_http(esc_url( get_user_meta( $user_id, 'wechat_avatar', true )) ) . "' class='{$args['class']} avatar avatar-{$size}{$author_class} photo' height='{$size}' width='{$size}' />";
            break;
        case 'wechat_gzh':
            return "<img alt='" . esc_attr( $alt ) . "' src='" . format_http(esc_url( get_user_meta( $user_id, 'wechat_gzh_avatar', true )) ) . "' class='{$args['class']} avatar avatar-{$size}{$author_class} photo' height='{$size}' width='{$size}' />";
            break;
        case 'custom':
            return "<img alt='" . esc_attr( $alt ) . "' src='" . format_http(esc_url( get_user_meta( $user_id, 'custom_avatar', true )) ) . "' class='{$args['class']} avatar avatar-{$size}{$author_class} photo' height='{$size}' width='{$size}' />";
            break;
        default:
            return $avatar;
    } 
}

function format_http($url){  
        $pattern = '@^(?:https?:)?(.*)@i';
        $result = preg_match($pattern, $url, $matches);
        return $matches[1];
} 

//主题更新
if(io_get_option('update_theme')){
    require_once get_theme_file_path('/inc/classes/theme.update.checker.class.php');  
    $example_update_checker = new ThemeUpdateChecker(
        'onenav', 
        'https://www.iotheme.cn/update/'
    ); 
}
