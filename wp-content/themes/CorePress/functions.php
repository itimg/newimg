<?php
define('THEME_NAME', 'CorePress');
define('THEME_VERSION', 60);
define('THEME_DOWNURL', 'https://www.lovestu.com/corepress.html');
define('THEME_VERSIONNAME', '5.7');
define('THEME_PATH', get_template_directory());
define('THEME_STATIC_PATH', get_template_directory_uri() . '/static');
define('THEME_CSS_PATH', THEME_STATIC_PATH . '/css');
define('THEME_JS_PATH', THEME_STATIC_PATH . '/js');
define('THEME_LIB_PATH', THEME_STATIC_PATH . '/lib');
define('THEME_IMG_PATH', THEME_STATIC_PATH . '/img');
define('FRAMEWORK_PATH', THEME_PATH . '/geekframe');
$upload = wp_upload_dir();
$upload_dir = $upload['basedir'];
define('AVATAR_DIR', $upload_dir . '/corepress_avatar/');
define('FRAMEWORK_URI', get_template_directory_uri() . '/geekframe');
define('AJAX_URL', admin_url('admin-ajax.php'));
require_once(FRAMEWORK_PATH . '/options.php');
$set = options::getInstance()->getdata();
require_once(FRAMEWORK_PATH . '/utils.php');
require_once(FRAMEWORK_PATH . '/support.php');
require_once(FRAMEWORK_PATH . '/users.php');
require_once(FRAMEWORK_PATH . '/ajax.php');
require_once(FRAMEWORK_PATH . '/loadfiles.php');
require_once(FRAMEWORK_PATH . '/seo/category.php');
require_once(FRAMEWORK_PATH . '/comment-pro.php');
require_once(THEME_PATH . '/widgets/comments.php');
require_once(THEME_PATH . '/widgets/author.php');
require_once(THEME_PATH . '/widgets/hot-post.php');
require_once(THEME_PATH . '/widgets/tag-cloud.php');
require_once(THEME_PATH . '/widgets/sentence.php');

require_once(FRAMEWORK_PATH . '/shortcode.php');
add_editor_style('static/css/editor-style.css');
error_reporting(0);
require_once(ABSPATH . 'wp-admin/includes/file.php');
WP_Filesystem();


add_filter('automatic_updater_disabled', '__return_true');	// 彻底关闭自动更新
remove_action('init', 'wp_schedule_update_checks');	// 关闭更新检查定时作业
wp_clear_scheduled_hook('wp_version_check');			// 移除已有的版本检查定时作业
wp_clear_scheduled_hook('wp_update_plugins');		// 移除已有的插件更新定时作业
wp_clear_scheduled_hook('wp_update_themes');			// 移除已有的主题更新定时作业
wp_clear_scheduled_hook('wp_maybe_auto_update');		// 移除已有的自动更新定时作业
remove_action( 'admin_init', '_maybe_update_core' );		// 移除后台内核更新检查

remove_action( 'load-plugins.php', 'wp_update_plugins' );	// 移除后台插件更新检查

