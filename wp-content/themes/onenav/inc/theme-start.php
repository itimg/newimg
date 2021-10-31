<?php 
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-03-01 10:19:06
 * @LastEditors: iowen
 * @LastEditTime: 2021-09-30 23:54:05
 * @FilePath: \onenav\inc\theme-start.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * 注册边栏.
 *
 * @since   2.0.0
 */
function io_register_sidebars()
{
    $sidebars = array(
        array(
            'id'            => 'sidebar-h',
            'name'          => __('网站详情页侧边栏','io_setting' ),
            'description'   => __('显示在网站详情页侧边栏','io_setting' ),
        ),
        array(
            'id'            => 'sidebar-s',
            'name'          => __('正文侧边栏','io_setting' ),
            'description'   => __('显示在文章正文及页面侧边栏','io_setting' ),
        ),
        array(
            'id'            => 'sidebar-a',
            'name'          => __('分类归档侧边栏','io_setting' ),
            'description'   => __('显示在文章归档页、搜索、404页侧边栏','io_setting' ),
        ),
        array(
            'id'            => 'sidebar-bull',
            'name'          => __('公告归档页侧边栏','io_setting' ),
            'description'   => __('显示在公告归档页侧边栏','io_setting' ),
        ),
        array(
            'id'            => 'sidebar-sites-t',
            'name'          => __('网站详情页顶部小工具','io_setting' ),
            'description'   => __('显示在网站详情页顶部，替换“网址、app正文上方广告位”，推荐只添加一个小工具。','io_setting' ),
        ),
        array(
            'id'            => 'sidebar-sites-r',
            'name'          => __('网站详情页侧边栏','io_setting' ),
            'description'   => __('显示在网站详情页侧边栏','io_setting' ),
        ),
    );
    /*
     * HOOK 过滤钩子
     * io_sidebar_list_filters
     * 
     */
    $sidebars = apply_filters('io_sidebar_list_filters', $sidebars); 
    foreach ($sidebars as $value) {
        register_sidebar(
            array(
                'name'          => $value['name'],
                'id'            => $value['id'],
                'description'   => $value['description'],
                'before_widget' => '<div id="%1$s" class="card %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<div class="card-header widget-header"><h3 class="text-md mb-0">',
                'after_title'   => '</h3></div>',
            )
        );
    }
}
add_action('widgets_init', 'io_register_sidebars');

# 注册菜单
# --------------------------------------------------------------------
io_register_menus();
function io_register_menus(){
    $navs=array(
        'nav_menu'    => __( '侧栏主菜单' , 'io_setting' ),
        'nav_main'    => __( '侧栏底部菜单' , 'io_setting' ),
        'main_menu'   => __( '顶部菜单' , 'io_setting' ),
        'search_menu' => __( '搜索推荐' , 'io_setting' ),
    );
    /*
     * HOOK 过滤钩子
     * io_nav_list_filters
     */
    $navs = apply_filters('io_nav_list_filters', $navs);
    register_nav_menus($navs);
}
add_action('after_setup_theme', 'my_theme_setup');
function my_theme_setup(){
    load_theme_textdomain( 'i_theme', get_template_directory() . '/languages' );
    load_theme_textdomain( 'io_setting', get_template_directory() . '/languages' );
}

/**
 * 启用主题后进仪表盘 
 */
add_action('load-themes.php', 'Init_theme');
function Init_theme(){
    //强制启用伪静态
    if (!get_option('permalink_structure')) {
        update_option('permalink_structure', '/%post_id%.html');
    }
    global $pagenow;
    if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) {
        initialization();
        update_option( 'thumbnail_size_w',0 );
        update_option( 'thumbnail_size_h', 0 );
        update_option( 'thumbnail_crop', 0 );
        update_option( 'medium_size_w',0 );
        update_option( 'medium_size_h', 0 );
        update_option( 'large_size_w',0 );
        update_option( 'large_size_h', 0 );
        wp_redirect( admin_url( '/admin.php?page=theme_settings' ) );
        exit;
    }
}
# 支持自定义功能
# ------------------------------------------------------------------------------
if(!get_option('permalink_structure'))
add_action( 'admin_notices', 'webstacks_init_check' );
function webstacks_init_check(){
    $html = '<div id="notice-warning-tgmpa" class="notice notice-warning is-dismissible" style="padding: 20px 12px;background-color: #ffeacf;">
                <p>
                    <b>警告：</b> 站点固定链接没有设置，请前往设置为非第一项的选项，推荐 “/%post_id%.html”。 
                    <a href="'.admin_url( 'options-permalink.php' ).'"> 立即前往设置</a>
                </p>
            </div>';
    echo $html;
}
//add_action( 'after_switch_theme', 'active_webstacks_notice');
function active_webstacks_notice() {
    $notice = '<div id="setting-error-tgmpa" class="notice notice-info is-dismissible"> 
				<p>
					<b>通知：</b> WebStacks PRO 主题已激活，鉴于之前很多用户使用时都遇到了问题，请您先去 
                    <a href="'.admin_url( 'index.php' ).'">仪表盘</a>仔细阅读使用说明，谢谢！ 
                </p> 
            <button type="button" class="notice-dismiss"><span class="screen-reader-text">忽略此通知。</span></button> 
        </div>';
    echo $notice;
}
function get_root_host($url){
    $url = strtolower($url);
    $hosts = parse_url($url);
    $host = isset($hosts['host'])?:$url;
    $data = explode('.', $host);
    $n = count($data);
    $preg = '/[\w].+\.(com|net|org|gov|edu)\.cn$/';
    if(($n > 2) && preg_match($preg,$host)){
        $host = $data[$n-3].'.'.$data[$n-2].'.'.$data[$n-1];
    }else{
        $host = $data[$n-2].'.'.$data[$n-1];
    }
    return $host;
}

global $wpdb;
$wpdb->iomessages   = $wpdb->prefix.'io_messages';
$wpdb->iocustomurl  = $wpdb->prefix.'io_custom_url';
$wpdb->iocustomterm = $wpdb->prefix.'io_custom_term';
$wpdb->ioviews      = $wpdb->prefix.'io_views';

require get_theme_file_path('/inc/primary.php'); 
require get_theme_file_path('/inc/theme-update.php'); 
require get_theme_file_path('/inc/classes/iodb.class.php'); 
require get_theme_file_path('/inc/classes/menuico.class.php'); 
require get_theme_file_path('/inc/inc.php'); 
require get_theme_file_path('/inc/meta-menu.php'); 
require get_theme_file_path('/inc/hot-search-option.php'); 
if(io_get_option('custom_search')) require get_theme_file_path('/inc/search-settings.php'); 
if(io_get_option('site_map'))      require get_theme_file_path('/inc/classes/do.sitemap.class.php'); 

if (io_get_option('disable_gutenberg')) {
    add_editor_style( 'css/editor-style.css' );
}
if (!defined('IO_PRO') || !function_exists('onStart') ){
    wp_die('禁止破解！否则冻结订单，享受完整功能与专属服务请<a href="https://www.iotheme.cn" target="_blank">购买正版</a>！', '禁止破解！', array('response'=>403));
}
getAlibabaIco('ico');
function theme_load_scripts() {

    $_min = WP_DEBUG === true?'':'.min';

    if(!$iointo=io_get_option('ico-source')  ){
        if(!isset($iointo['ico_url'])){
            echo "<h1 style='margin: 100px 0;text-align: center;'>请先保存“主题设置”。。。。。。。。。。<br><span style='font-size:60%'>并且认真查看后台 <b>仪表盘</b> 的使用说明</span></h1>";
            exit;
        }
    }
    
    wp_deregister_script( 'jquery' );

    wp_register_style( 'font-awesome',              '//cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/all.min.css', array(), VERSION, 'all'  );
    wp_register_style( 'font-awesome4',             '//cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/v4-shims.min.css', array(), VERSION, 'all'  );
    //移除本地fa图标
    //wp_register_style( 'font-awesome',            get_theme_file_uri('/css/all.min.css'), array(), VERSION, 'all'  );
    //wp_register_style( 'font-awesome4',           get_theme_file_uri('/css/v4-shims.min.css'), array(), VERSION, 'all'  );

    switch(io_get_option('cdn_resources')){
        case 'jsdelivr':
            wp_register_style( 'iconfont',                   '//cdn.jsdelivr.net/gh/owen0o0/ioStaticResources/onenav/css/iconfont.css', array(), VERSION, 'all'  );
            wp_register_style( 'style',                      '//cdn.jsdelivr.net/gh/owen0o0/ioStaticResources/onenav/css/style.min.css', array(), VERSION );
            wp_register_style( 'bootstrap',                  '//cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css', array(), VERSION, 'all'  );
            wp_register_style( 'lightbox',                   '//cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css', array(), VERSION );
        
            wp_register_script( 'jquery',                    '//cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js', array(), VERSION ,false);
            wp_register_script( 'clipboard-mini',            '//cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js', array(), VERSION, true );
            wp_register_script( 'popper',                    '//cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js', array('jquery'), VERSION, true );
            wp_register_script( 'bootstrap',                 '//cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js', array('jquery'), VERSION, true );
            wp_register_script( 'sidebar',                   '//cdn.jsdelivr.net/npm/theia-sticky-sidebar@1.7.0/dist/theia-sticky-sidebar.min.js', array('jquery'), VERSION, true );
            wp_register_script( 'lightbox-js',               '//cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js', array('jquery'), VERSION, true );  
            wp_register_script( 'comments-ajax',             '//cdn.jsdelivr.net/gh/owen0o0/ioStaticResources/onenav/js/comments-ajax.js', array('jquery'), VERSION, true );
            wp_register_script( 'color-thief',               '//cdn.jsdelivr.net/gh/owen0o0/ioStaticResources/onenav/js/color-thief.umd.js', array(), VERSION, true );
            wp_register_script( 'appjs',                     '//cdn.jsdelivr.net/gh/owen0o0/ioStaticResources/onenav/js/app.min.js', array('jquery'), VERSION, true );
            wp_register_script( 'bookmark',                  '//cdn.jsdelivr.net/gh/owen0o0/ioStaticResources/onenav/js/bookmark.js', array('jquery'), VERSION, true );
            wp_register_script( 'lazyload',                  '//cdn.jsdelivr.net/gh/owen0o0/ioStaticResources/onenav/js/lazyload.min.js', array('jquery'), VERSION, true );
            break;
        //case 'staticfile':
        //    wp_register_style( 'bootstrap',                  '//cdn.staticfile.org/twitter-bootstrap/4.6.0/css/bootstrap.min.css', array(), VERSION, 'all'  );
        //    wp_register_style( 'lightbox',                   '//cdn.staticfile.org/fancybox/3.5.7/jquery.fancybox.min.css', array(), VERSION );
        //
        //    wp_register_script( 'jquery',                    '//cdn.staticfile.org/jquery/3.5.1/jquery.js', array(), VERSION ,false);
        //    wp_register_script( 'clipboard-mini',            '//cdn.staticfile.org/clipboard.js/2.0.8/clipboard.min.js', array(), VERSION, true );
        //    wp_register_script( 'popper',                    '//cdn.staticfile.org/popper.js/1.16.0/umd/popper.min.js', array('jquery'), VERSION, true );
        //    wp_register_script( 'bootstrap',                 '//cdn.staticfile.org/twitter-bootstrap/4.6.0/js/bootstrap.min.js', array('jquery'), VERSION, true );
        //    wp_register_script( 'sidebar',                   get_theme_file_uri('/js/theia-sticky-sidebar.js'), array('jquery'), VERSION, true );
        //    wp_register_script( 'lightbox-js',               '//cdn.staticfile.org/fancybox/3.5.7/jquery.fancybox.min.js', array('jquery'), VERSION, true );
        //    break;
        default:
            wp_register_style( 'iconfont',                   get_theme_file_uri('/css/iconfont.css'), array(), VERSION, 'all'  );
            wp_register_style( 'style',                      get_theme_file_uri('/css/style'.$_min.'.css'), array(), VERSION );
            wp_register_style( 'bootstrap',                  get_theme_file_uri('/css/bootstrap.min.css'), array(), VERSION, 'all'  );                  //cdn
            wp_register_style( 'lightbox',                   get_theme_file_uri('/css/jquery.fancybox.min.css'), array(), VERSION );                    //cdn
            
            wp_register_script( 'jquery',                    get_theme_file_uri('/js/jquery.min.js'), array(), VERSION ,false);
            wp_register_script( 'clipboard-mini',            get_theme_file_uri('/js/clipboard.min.js'), array(), VERSION, true );                      //cdn
            wp_register_script( 'popper',                    get_theme_file_uri('/js/popper.min.js'), array('jquery'), VERSION, true );                 //cdn
            wp_register_script( 'bootstrap',                 get_theme_file_uri('/js/bootstrap.min.js'), array('jquery'), VERSION, true );              //cdn
            wp_register_script( 'sidebar',                   get_theme_file_uri('/js/theia-sticky-sidebar.js'), array('jquery'), VERSION, true );       //cdn
            wp_register_script( 'lightbox-js',               get_theme_file_uri('/js/jquery.fancybox.min.js'), array('jquery'), VERSION, true );        //cdn
            wp_register_script( 'comments-ajax',             get_theme_file_uri('/js/comments-ajax.js'), array('jquery'), VERSION, true );
            wp_register_script( 'color-thief',               get_theme_file_uri('/js/color-thief.umd.js'), array(), VERSION, true );
            wp_register_script( 'appjs',                     get_theme_file_uri('/js/app'.$_min.'.js'), array('jquery'), VERSION, true );
            wp_register_script( 'bookmark',                  get_theme_file_uri('/js/bookmark.js'), array('jquery'), VERSION, true );
            wp_register_script( 'lazyload',                  get_theme_file_uri('/js/lazyload.min.js'), array('jquery'), VERSION, true );
    }
    wp_register_script( 'echarts',                   '//cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js', array(), VERSION, true );
    wp_register_script( 'jquery-ui',                 '//cdn.jsdelivr.net/npm/jquery-ui-dist@1.12.1/jquery-ui.min.js', array('jquery'), VERSION, true );
    wp_register_script( 'jquery-touch',              '//cdn.jsdelivr.net/npm/jquery-ui-touch-punch@0.2.3/jquery.ui.touch-punch.min.js', array('jquery'), VERSION, true );
    if( !is_admin() )
    {
        wp_enqueue_style('iconfont');
        if ( !get_query_var('bookmark_id')) {
            if ( io_get_option('is_iconfont')) {
                $url = io_get_option('iconfont_url');
                $urls = explode(PHP_EOL , $url);
                $index = 1;
                if(!empty($urls)&&is_array($urls)){
                    foreach($urls as $v){
                        wp_enqueue_style( 'iconfont-io-'.$index,  $v, array(), VERSION );
                        $index++;
                    }
                }else{
                    wp_enqueue_style( 'iconfont-io',  $url, array(), VERSION );
                }
            }else{
                wp_enqueue_style('font-awesome');
                wp_enqueue_style('font-awesome4');
            }
        }
        wp_enqueue_style('bootstrap');
        if( ( io_get_option('hot_iframe') && (is_home() || is_front_page() || is_page_template('template-mininav.php') || get_query_var('custom_page')=='hotnews') ) ||  is_single() ) wp_enqueue_style('lightbox'); 
        wp_enqueue_style('style'); 

        wp_enqueue_script('jquery');
        wp_add_inline_script( 'jquery', '/* <![CDATA[ */ 
        function loadFunc(func) {var oldOnload = window.onload;if(typeof window.onload != "function"){window.onload = func;}else{window.onload = function(){oldOnload();func();}}} 
        /* ]]> */');
        if(is_home() ||  is_front_page() || get_query_var('user_child_route')){
            //wp_enqueue_script('jquery-ui-sortable');
            //wp_enqueue_script('jquery-ui-droppable');
            //wp_enqueue_script('jquery-touch-punch');
            wp_enqueue_script('jquery-ui');
            wp_enqueue_script('jquery-touch');
        }

        if(is_single() || get_query_var('bookmark_id')) wp_enqueue_script('clipboard-mini');
        if(is_single() && io_get_option('leader_board') && io_get_option('details_chart')) wp_enqueue_script('echarts');
        wp_enqueue_script('popper');
        wp_enqueue_script('bootstrap');
        wp_enqueue_script('sidebar'); 
        if(io_get_option('lazyload')) wp_enqueue_script('lazyload'); 

        if( ( io_get_option('hot_iframe') && (is_home() || is_front_page() || is_page_template('template-mininav.php') || get_query_var('custom_page')=='hotnews') ) ||  is_single() ) wp_enqueue_script('lightbox-js'); 
        wp_enqueue_script('appjs'); 

        if( get_query_var('bookmark_id')) {
            //wp_enqueue_script('color-thief'); 
            wp_enqueue_script('bookmark'); 
        }

        if( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            if( io_get_option('io_captcha')['tcaptcha_007'] && io_get_option('io_captcha')['comment_007'] ){
                wp_enqueue_script( 'captcha','//ssl.captcha.qq.com/TCaptcha.js',[],VERSION,true );
            }
            wp_enqueue_script( 'comment-reply' );
            wp_enqueue_script( 'comments-ajax' );
        }
    }
    wp_localize_script('appjs', 'theme' , array(
        'ajaxurl'      => admin_url( 'admin-ajax.php' ),
        'addico'       => get_theme_file_uri('/images/add.png'),
        'order'        => get_option('comment_order'),
        'formpostion'  => 'top', 
        'defaultclass' => io_get_option('theme_mode')=="io-black-mode"?'':io_get_option('theme_mode'), 
        'isCustomize'  => io_get_option('customize_card'),
        'icourl'       => io_get_option('ico-source')['ico_url'],
        'icopng'       => io_get_option('ico-source')['ico_png'],
        'urlformat'    => io_get_option('ico-source')['url_format'],
        'customizemax' => io_get_option('customize_n'),
        'newWindow'    => io_get_option('new_window'),
        'lazyload'     => io_get_option('lazyload'),
        'minNav'       => io_get_option('min_nav'),
        'loading'      => io_get_option('loading_fx'),
        'hotWords'     => io_get_option('baidu_hot_words'),
        'classColumns' => get_columns(false),
        'apikey'       => ioThemeKey(),//iowenKey(),
        'isHome'       => (is_home() || is_front_page() || is_page_template('template-mininav.php')),
        'version'      => VERSION,
    )); 
    wp_localize_script('appjs', 'localize' , array(
        'liked'             => __('您已经赞过了!','i_theme'),
        'like'              => __('谢谢点赞!','i_theme'),
        'networkerror'      => __('网络错误 --.','i_theme'),
        'selectCategory'    => __('为什么不选分类。','i_theme'),
        'addSuccess'        => __('添加成功。','i_theme'),
        'timeout'           => __('访问超时，请再试试，或者手动填写。','i_theme'),
        'lightMode'         => __('日间模式','i_theme'),
        'nightMode'         => __('夜间模式','i_theme'),
        'editBtn'           => __('编辑','i_theme'),
        'okBtn'             => __('确定','i_theme'),
        'urlExist'          => __('该网址已经存在了 --.','i_theme'),
        'cancelBtn'         => __('取消','i_theme'),
        'successAlert'      => __('成功','i_theme'),
        'infoAlert'         => __('信息','i_theme'),
        'warningAlert'      => __('警告','i_theme'),
        'errorAlert'        => __('错误','i_theme'),
        'extractionCode'    => __('网盘提取码已复制，点“确定”进入下载页面。','i_theme'),
    ));
}

function io_admin_load_scripts($hook) {
    if( !is_admin() )return;
	if( $hook == 'post.php' || $hook == 'post-new.php' || $hook == 'toplevel_page_theme_settings' ) {
        wp_register_style( 'add-hot',  get_theme_file_uri('/css/add-hot.css'), array(), VERSION );
        wp_register_script( 'add-hot', get_theme_file_uri('/js/add-hot.js'), array('jquery'), VERSION, true );
        wp_enqueue_style('add-hot'); 
        wp_enqueue_script('add-hot');
        wp_localize_script('add-hot', 'io_theme' , array(
            'ajaxurl'      => admin_url( 'admin-ajax.php' ),
            'apikey'       => iowenKey(),
        )); 
    }
    if(io_get_option('is_iconfont')){
        //wp_register_style( 'iconfont-io',  io_get_option('iconfont_url'), array(), '' );
        //wp_enqueue_style('iconfont-io'); 
        $url = io_get_option('iconfont_url');
        $urls = explode(PHP_EOL , $url);
        $index = 1;
        if(!empty($urls)&&is_array($urls)){
            foreach($urls as $v){
                wp_enqueue_style( 'iconfont-io-'.$index,  $v, array(), VERSION );
                $index++;
            }
        }else{
            wp_enqueue_style( 'iconfont-io',  $url, array(), VERSION );
        }
    }
}
add_action('admin_enqueue_scripts', 'io_admin_load_scripts');

function io_login_head() {
	switch(io_get_option('cdn_resources')){
        case 'jsdelivr':
            $iconfont       = '//cdn.jsdelivr.net/gh/owen0o0/ioStaticResources/onenav/css/iconfont.css';
            $bootstrap      = '//cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css';
        
            $jquery         = '//cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js';
            $bootstrap_js   = '//cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js';
            break;
        default:
            $iconfont       = get_theme_file_uri('/css/iconfont.css');
            $bootstrap      = get_theme_file_uri('/css/bootstrap.min.css');                  //cdn
            
            $jquery         = get_theme_file_uri('/js/jquery.min.js');
            $bootstrap_js   = get_theme_file_uri('/js/bootstrap.min.js');
    }
    $login   = get_theme_file_uri('/css/login.css');
    echo '<link rel="stylesheet" href="'. $iconfont .'?ver='. VERSION.'" type="text/css"/>';
    echo '<link rel="stylesheet" href="'. $bootstrap .'?ver='. VERSION.'" type="text/css"/>';
    echo '<link rel="stylesheet" href="'. $login .'?ver='. VERSION.'" type="text/css"/>';
    echo '<script type="text/javascript" src="'. $jquery .'?ver='. VERSION.'"></script>';

    if ( isset($_GET['action']) && $_GET['action'] === "bind" ) { 
        echo '<script type="text/javascript" src="'. $bootstrap_js .'?ver='. VERSION.'"></script>';
    }
}
add_action('login_head', 'io_login_head');
