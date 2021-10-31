<?php
function corepress_load_scripts()
{
    global $set;
    wp_enqueue_script('index_js', THEME_JS_PATH . '/index.js', array(), THEME_VERSION, true);
    $setdata = load_set_data_to_js();
    wp_localize_script('index_js', 'set', $setdata);
    if ($set['module']['highlight'] == 1) {
        if (is_single() || is_page()) {
            wp_enqueue_script('highlight_init', THEME_LIB_PATH . '/highlight/init.js', array(), THEME_VERSION, true);
        }
    }

    wp_enqueue_script('corepress_jquery', THEME_JS_PATH . '/jquery.min.js', array(), THEME_VERSION, false);
    wp_enqueue_script('corepress_jquery_lazyload', THEME_JS_PATH . '/jquery.lazyload.min.js', array('corepress_jquery'), THEME_VERSION, false);
    wp_enqueue_script('corepress_jquery_qrcode', THEME_JS_PATH . '/qrcode.min.js', array('corepress_jquery'), THEME_VERSION, false);


    if (is_single() || is_page()) {
        wp_enqueue_script('corepress_clipboard_js', THEME_JS_PATH . '/clipboard.min.js', array(), THEME_VERSION, false);
        wp_enqueue_script('post_content', THEME_JS_PATH . '/post-content.js', array('corepress_clipboard_js'), THEME_VERSION, true);
    }

    if (!is_page_template('page-corepress.php')) {
        wp_enqueue_script('tools', THEME_JS_PATH . '/tools.js', array(), THEME_VERSION, false);
        wp_localize_script('tools', 'tools', array('index' => is_home(), 'page' => is_page(), 'post' => is_single()));
    }
    if (is_page_template('page-links.php') || is_page_template('page-friends.php')) {
        wp_enqueue_style('page-links.css', THEME_CSS_PATH . '/page-links.css', array(), THEME_VERSION);
    }
    corepress_plugin_compatibility();

    if ($set['theme']['curname'] != 'default') {
        wp_enqueue_style('corepress-cursor', THEME_CSS_PATH . '/cursor.css', array(), THEME_VERSION);
        $custom_inline_style = ':root {--cur-default:url(' . file_get_img_url('cur/' . $set['theme']['curname'] . '/arrow.png)') . ';--cur-pointer:url(' . file_get_img_url('cur/' . $set['theme']['curname'] . '/link.png)') . '}';
        wp_add_inline_style('corepress-cursor', $custom_inline_style);
    }
}


function corepress_load_style_filse_onadmin($hook)
{
    wp_enqueue_style('corepressicon', THEME_LIB_PATH . '/corepressicon/iconfont.css', array(), THEME_VERSION);
    if ($hook == 'post.php' || $hook == 'post-new.php') {
        wp_enqueue_script('corepress_jquery', THEME_JS_PATH . '/jquery.min.js', array(), THEME_VERSION, false);
        wp_enqueue_script('tools', THEME_JS_PATH . '/tools.js', array('corepress_jquery'), THEME_VERSION, false);
        wp_enqueue_script('layer', THEME_LIB_PATH . '/layer/layer.js', array('corepress_jquery'), THEME_VERSION, false);
        wp_enqueue_script('vue', THEME_JS_PATH . '/vue.min.js', array('corepress_jquery'), THEME_VERSION, false);
        wp_localize_script('tools', 'tools', array('index' => is_home(), 'page' => is_page(), 'post' => is_single()));
        wp_enqueue_script('corepress_element_js', THEME_LIB_PATH . '/element/index.js', array(), THEME_VERSION, false);
        wp_enqueue_style('corepress_element_css', THEME_LIB_PATH . '/element/index.css', array(), THEME_VERSION);
        wp_enqueue_style('corepress_admin_css', THEME_CSS_PATH . '/admin.css', array(), THEME_VERSION);
        wp_enqueue_style('editor_window', THEME_CSS_PATH . '/editor-window.css', array(), THEME_VERSION);
    }

}

function corepress_plugin_compatibility()
{
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    if (is_plugin_active('wp-editormd/wp-editormd.php')) {
        wp_deregister_script('jQuery-CDN');
    }
}


add_action('admin_enqueue_scripts', 'corepress_load_style_filse_onadmin');
add_action('wp_enqueue_scripts', 'corepress_load_scripts');

function load_set_data_to_js()
{
    global $set;
    $setdata = array();
    /**
     * 通用模块
     */
    $setdata['is_single'] = is_single();
    $setdata['is_page'] = is_page();
    $setdata['is_home'] = is_home();
    $setdata['ajaxurl'] = AJAX_URL;
    /*
     * 防转载模块
     * */
    $set['module']['reprint']['open'] == 0;
    $setdata['reprint']['msg'] = '';
    $setdata['reprint']['copylenopen'] = 0;
    $setdata['reprint']['copylen'] = 0;
    $setdata['reprint']['addurl'] = 0;
    $setdata['reprint']['siteurl'] = curPageURL();

    if ($set['module']['reprint']['open'] == 1) {
        $setdata['reprint']['open'] = 1;
        $setdata['reprint']['msg'] = $set['module']['reprint']['msg'];
        if ($set['module']['reprint']['copylenopen'] == 1) {
            $setdata['reprint']['copylenopen'] = 1;
            $setdata['reprint']['copylen'] = $set['module']['reprint']['copylen'];
        }
        if ($set['module']['reprint']['addurl'] == 1) {
            $setdata['reprint']['addurl'] = 1;
        }
    }
    /**
     *延迟加载
     */
    $setdata['module']['imglightbox'] = $set['module']['imglightbox'];
    $setdata['module']['imglazyload'] = $set['module']['imglazyload'];

    /**
     * 文章参数
     */

    if (is_single() || is_page()) {
        global $post;
        $corepress_post_meta = get_post_meta($post->ID, 'corepress_post_meta', true);
        if ($corepress_post_meta == false) {
            $setdata['corepress_post_meta']['catalog'] = 0;
        } else {
            $corepress_post_meta = json_decode($corepress_post_meta);

        }
        $setdata['corepress_post_meta'] = $corepress_post_meta;
        $setdata['theme']['sidebar_position'] = $set['theme']['sidebar_position'];
    }
    /**
     * 延迟加载
     */
    $setdata['module']['imglazyload'] = $set['module']['imglazyload'];

    /**
     * 顶部加载
     */
    $setdata['theme']['loadbar'] = $set['theme']['loadbar'];
    $setdata['index']['linksicon'] = $set['index']['linksicon'];
    $setdata['index']['chromeiconurl'] = file_get_img_url("chrome.png");
    /**
     * 页面位置
     */
    $setdata['is_page_template'] = is_page_template();
    return $setdata;

}