<?php
/*!
 * Theme Name:One Nav
 * Theme URI:https://www.iotheme.cn/
 * Author:iowen
 * Author URI:https://www.iowen.cn/
 */
if ( ! defined( 'ABSPATH' ) ) { exit; } 
// 普通用户禁止进入后台
add_action("init","io_restrict_admin");
function io_restrict_admin(){
    if ( is_admin() && ( !defined( 'DOING_AJAX' ) || !DOING_AJAX )) { 
        if(!current_user_can( 'manage_options' ) && !current_user_can( 'publish_pages' ) && !current_user_can( 'edit_others_posts' ) && !current_user_can('publish_posts') && !current_user_can( 'edit_posts' ) ) { 
            wp_redirect( home_url('/user/') );
        }
    }
}
require get_theme_file_path('/inc/user-callback.php'); 
/**
 * 获取用户权限描述字符.
 * --------------------------------------------------------------------------------------
 */
function io_get_user_cap_string($user_id)
{
    if (user_can($user_id, 'manage_options')) {
        return __('管理员', 'i_theme');
    }
    if (user_can($user_id, 'edit_others_posts')) {
        return __('编辑', 'i_theme');
    }
    if (user_can($user_id, 'publish_posts')) {
        return __('作者', 'i_theme');
    }
    if (user_can($user_id, 'edit_posts')) {
        return __('投稿者', 'i_theme');
    }

    return __('读者', 'i_theme');
}
/**
 * 获取用户收藏的所有文章ID.
 * --------------------------------------------------------------------------------------
 */
function io_get_user_star_post_ids($user_id, $post_type="sites")
{
    global $wpdb;
    $sql = $wpdb->prepare("SELECT `post_id` FROM $wpdb->postmeta  WHERE `meta_key`='%s' AND `meta_value`=%d", "io_{$post_type}_star_users", $user_id);
    $results = $wpdb->get_results($sql);
    $ids = array();
    foreach ($results as $result) {
        $ids[] = intval($result->post_id);
    }
    $ids = array_unique($ids);
    rsort($ids); //从大到小排序
    return $ids;
}

/**
 * 获取文章收藏数量.
 * --------------------------------------------------------------------------------------
 */
function io_get_post_star_count($post_id, $post_type="sites")
{
    $star_post = array_unique(get_post_meta( $post_id, "io_{$post_type}_star_users", false)); 
    $status = 0;
    if(is_user_logged_in()){
        if(in_array(get_current_user_id(),$star_post)){
            $status = 1;
        }
    }
    return array('count' => count($star_post),'status' => $status);
}

function io_add_star_post($post_type,$post_ids){
    if($post_ids){
        echo '<div class="row row-sm">';
        global $post;
        $args = array(
            'post_type'           => $post_type,  
            'ignore_sticky_posts' => 1,     
            'posts_per_page'      => -1,     
            'post__in'            => $post_ids,    
            'orderby'             => 'post__in',     
        );
        $myposts = new WP_Query( $args );
        if ($myposts->have_posts()): while ($myposts->have_posts()): $myposts->the_post(); 
        switch($post_type){
            case "sites":
                echo '<div class="url-card col-6 col-md-4">';
                include( get_theme_file_path('/templates/card-site.php')  );
                echo '</div>';
                break;
            case "app":
                echo'<div class="col-4 col-md-3 col-lg-2">';
                include( get_theme_file_path('/templates/card-app.php') ); 
                echo'</div>';
                break;
            case "book":
                echo'<div class="col-6 col-sm-4 col-md-3">';
                include( get_theme_file_path('/templates/card-book.php') ); 
                echo'</div>'; 
                break;
            case "post":
                echo '<div class="col-6 col-md-4 col-xl-3">';
                get_template_part( 'templates/card','post' );
                echo '</div>';
                break;
            default:
                echo '<div class="url-card col-6 col-md-4 col-lg-3 ">';
                include( get_theme_file_path('/templates/card-sitemini.php')  );
                echo '</div>';
        }

        endwhile; endif; wp_reset_postdata();
        echo '</div>';
    }else{
        echo '<div class="empty-content text-center pb-5">
            <i class="iconfont icon-nothing1"></i>
        </div>';
    }
}
/**
 * 统计用户点赞(收藏)的文章数.
 * --------------------------------------------------------------------------------------
 */
function io_count_user_star_posts($user_id, $post_type="sites")
{
    return count(io_get_user_star_post_ids($user_id,$post_type));
}

/* 默认登录页的主重定向 */ //lostpassword
function redirect_login_page() {
    $login_page  = home_url('/login/');
    $page_viewed = basename($_SERVER['REQUEST_URI']);
	$action = !empty($_REQUEST['action']) ? $_REQUEST['action'] : '';
    $page = array('login','register','lostpassword', 'retrievepassword');//, 'resetpass', 'rp');
    if( get_option('permalink_structure') && in_array($action, $page)){
        if(!(in_array($action, array('lostpassword', 'retrievepassword')) && $_SERVER['REQUEST_METHOD'] == 'POST')){
            $redirect_to = !empty($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : '';
            $url = add_query_arg('action',$action,$login_page);
            if($redirect_to)
                wp_safe_redirect(add_query_arg('redirect_to', urlencode($redirect_to),$url));
            else
                wp_safe_redirect($url);
            exit();
        }
    }
}
add_action('login_init','redirect_login_page');
/* 登录失败该去哪里 */
function custom_login_failed() {
    $login_page  = home_url('/login/');
    wp_safe_redirect($login_page . '?login=failed');
    exit;
}
//add_action('wp_login_failed', 'custom_login_failed');
/* 如果任何字段为空，该去哪里 */
function verify_user_pass($user, $username, $password) {
    $login_page  = home_url('/login/');
    if($username == "" || $password == "") {
        wp_safe_redirect($login_page);// . "?login=empty");
        exit;
    }
}
add_filter('authenticate', 'verify_user_pass', 1, 3);
/* 注销时该怎么办 */
function logout_redirect() {
    $login_page  = home_url('/login/');
    wp_safe_redirect($login_page . "?login=false");
    exit;
}
//add_action('wp_logout','logout_redirect');


/**
 * 登录/注册/注销等动作页路由().
 * --------------------------------------------------------------------------------------
 */
function io_handle_action_page_rewrite_rules($wp_rewrite)
{
    if (get_option('permalink_structure')) {
        $new_rules['login/?$'] = 'index.php?custom_action=login';
        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
    }   
}
add_action('generate_rewrite_rules', 'io_handle_action_page_rewrite_rules');

/**
 * 为自定义的Action页添加query_var白名单.
 * --------------------------------------------------------------------------------------
 */
function io_add_action_page_query_vars($public_query_vars)
{
    if (!is_admin()) {
        $public_query_vars[] = 'custom_action'; // 添加参数白名单action，代表是各种动作页
    }

    return $public_query_vars;
}
add_filter('query_vars', 'io_add_action_page_query_vars');

/**
 * 登录/注册/注销等动作页模板
 * --------------------------------------------------------------------------------------
 */
function io_handle_action_page_template()
{ 
    //global $wp_query, $wp_rewrite;  
    //if( !isset($wp_query->query_vars['custom_action']) )   
    //    return;    
    //$reditect_page =  $wp_query->query_vars['custom_action'];   
    //switch ($reditect_page) {
    //    case 'login':
    //        include(TEMPLATEPATH.'/login.php');
    //        die();
    //}

    $action = strtolower(get_query_var('custom_action'));
    $allowed_actions = array(
        'login' => 'login',
    );
    if ($action && in_array($action, array_keys($allowed_actions))) {
        global $wp_query;
        $wp_query->is_home = false;
        $template = get_theme_file_path('/login.php');
        include($template);
        exit;
    } elseif ($action && !in_array($action, array_keys($allowed_actions))) {
        // 非法路由处理
        set404();
        return;
    }
}
add_action('template_redirect', 'io_handle_action_page_template', 5);


/**
 * /书签页主路由处理.
 * --------------------------------------------------------------------------------------
 */
function io_redirect_user_bookmark_route()
{
    if (preg_match('/^\/bookmark$/i', $_SERVER['REQUEST_URI'])) {
        if ($user_id = get_current_user_id()) {
            //$nickname = get_user_meta(get_current_user_id(), 'nickname', true);
            wp_redirect(get_author_posts_url($user_id), 302);
        } else {
            wp_redirect(io_add_redirect(home_url('/login/'), io_get_current_url()), 302);
        }
        exit;
    }
}
add_action('init', 'io_redirect_user_bookmark_route');
/**
 * 书签页路由.
 *
 * @since   2.0.0
 *
 * @param object $wp_rewrite WP_Rewrite
 */
function io_set_user_bookmark_rewrite_rules($wp_rewrite)
{
    if ($ps = get_option('permalink_structure')) {
        $new_rules['bookmark/([^/]+)$'] = 'index.php?bookmark_id=$matches[1]';
        $new_rules['bookmark/?$'] = 'index.php?bookmark_id=default';
        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
    }

    return $wp_rewrite;
}
add_action('generate_rewrite_rules', 'io_set_user_bookmark_rewrite_rules');  
 
/**
 * 为书签页添加query_var白名单
 * 
 * @param object $public_query_vars 公共全局query_vars
 * @return object
 */
function io_add_user_bookmark_query_vars($public_query_vars)
{
    if (!is_admin()) {
        $public_query_vars[] = 'bookmark_id';
    }

    return $public_query_vars;
}
add_filter('query_vars', 'io_add_user_bookmark_query_vars');

/**
 * 书签页模板
 * 
 * @param {*}
 * @return {*}
 */
function io_add_user_bookmark_page_template()
{  

    global $wp_query;
    if ($wp_query->is_404()) {
        return;
    }
    $action = get_query_var('bookmark_id');
    if(is_numeric($action)){
        set404();
        return;
    }
    if($action && $action == 'default'){
        global $wp_query;
        $wp_query->is_home = false;
        $template = get_theme_file_path('/templates/bookmark/bm.index.php');
        include($template);
        exit;
    }
    $action = base64_io_decode($action); 
    if ($action && preg_match('/([0-9]{1,})/', $action) ) {
        if(!get_user_by('ID', $action)){
            set404();
            return;
        }
        $bookmark_set = maybe_unserialize(get_user_meta( $action, 'bookmark_set', true ));
        if( (io_get_option('bookmark_share',true)&&wp_get_current_user()->ID!=$action) || (wp_get_current_user()->ID!=$action && isset($bookmark_set['share_bookmark']) && !$bookmark_set['share_bookmark']) ){
            set404();
            return;
        }
        global $wp_query;
        $wp_query->is_home = false;
        $template = get_theme_file_path('/templates/bookmark/bm.index.php');
        include($template);
        exit;
    } elseif ($action && !preg_match('/([0-9]{1,})/', $action)) {
        // 非法路由处理
        set404();
        return;
    }
}
add_action('template_redirect', 'io_add_user_bookmark_page_template', 5);


/**
 * /user主路由处理.
 * --------------------------------------------------------------------------------------
 */
function io_redirect_user_main_route()
{
    if (preg_match('/^\/user$/i', $_SERVER['REQUEST_URI'])) {
        if ($user_id = get_current_user_id()) {
            //$nickname = get_user_meta(get_current_user_id(), 'nickname', true);
            wp_redirect(get_author_posts_url($user_id), 302);
        } else {
            wp_redirect(io_add_redirect(home_url('/login/'), io_get_current_url()), 302);
        }
        exit;
    }
}
add_action('init', 'io_redirect_user_main_route');

/**
 * /user子路由处理 - Rewrite.
 * --------------------------------------------------------------------------------------
 */
function io_handle_user_child_routes_rewrite($wp_rewrite)
{
    if (get_option('permalink_structure')) {
        // user子路由与孙路由必须字母组成，不区分大小写
        $new_rules['user/([a-zA-Z]+)$'] = 'index.php?user_child_route=$matches[1]&is_user_route=1';
        $new_rules['user/([a-zA-Z]+)/([a-zA-Z]+)$'] = 'index.php?user_child_route=$matches[1]&user_grandchild_route=$matches[2]&is_user_route=1';
        // 分页
        $new_rules['user/([a-zA-Z]+)/page/([0-9]{1,})$'] = 'index.php?user_child_route=$matches[1]&is_user_route=1&paged=$matches[2]';
        $new_rules['user/([a-zA-Z]+)/([a-zA-Z]+)/page/([0-9]{1,})$'] = 'index.php?user_child_route=$matches[1]&user_grandchild_route=$matches[2]&is_user_route=1&paged=$matches[3]';
        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
    }

    return $wp_rewrite;
}
add_filter('generate_rewrite_rules', 'io_handle_user_child_routes_rewrite');

/**
 * 为自定义的当前用户页(user)添加query_var白名单.
 * --------------------------------------------------------------------------------------
 */
function io_add_user_page_query_vars($public_query_vars)
{
    if (!is_admin()) {
        $public_query_vars[] = 'is_user_route';
        $public_query_vars[] = 'user_child_route';
        $public_query_vars[] = 'user_grandchild_route';
    }

    return $public_query_vars;
}
add_filter('query_vars', 'io_add_user_page_query_vars');

function allowed_user_routes(){
    $user_allow_routes = array(
        'settings' => 'settings',  
        'bind' => 'bind',
        'sites' => 'sites',
        'stars' => 'stars',
        'security' => 'security',
        'notifications' => array(
            'all',
            'comment',
            'star',
            'update',
            'cash'
        ),
    );
    return $user_allow_routes;
}
/**
 * /user子路由处理 - Template.
 * --------------------------------------------------------------------------------------
 */
function io_handle_user_child_routes_template()
{
    $is_user_route = strtolower(get_query_var('is_user_route'));
    $user_child_route = strtolower(get_query_var('user_child_route'));
    $user_grandchild_route = strtolower(get_query_var('user_grandchild_route'));
    if ($is_user_route && $user_child_route) {
        global $wp_query;
        if ($wp_query->is_404()) {
            return;
        }

        //非Home
        $wp_query->is_home = false;

        //未登录的跳转到登录页
        if(!is_user_logged_in()) {
            wp_redirect(io_add_redirect(home_url('/login/'), io_get_current_url()), 302);
            exit;
        }

        if($user_child_route == '')
            wp_redirect(get_author_posts_url($user_id), 302);
            
        $allow_routes =  allowed_user_routes();
        $allow_child = array_keys($allow_routes);
        // 非法的子路由处理
        if (!in_array($user_child_route, $allow_child)) {
            set404();
            return;
        } 

        $allow_grandchild = $allow_routes[$user_child_route];
        // 对于可以有孙路由的一般不允许直接子路由，必须访问孙路由，比如/user/notifications 必须跳转至/user/notifications/all
        if (empty($user_grandchild_route) && is_array($allow_grandchild)) {
            wp_redirect(home_url('/user/'.$user_child_route.'/'.$allow_grandchild[0]), 302);
            exit;
        }
        // 非法孙路由处理
        if (is_array($allow_grandchild) && !in_array($user_grandchild_route, $allow_grandchild)) {
            set404();
            return;
        }
        $template = get_theme_file_path('/templates/user/user.uc.'.$user_child_route.'.php');
        load_template($template);
        exit;
    }
}
add_action('template_redirect', 'io_handle_user_child_routes_template', 5);
function set404(){
    global $wp_query;
    $wp_query->is_home = false;
    $wp_query->is_404 = true;
    $wp_query->query = array('error'=>'404');
    $wp_query->query_vars['error'] = '404';
}

/**
 * 条件判断类名.
 * --------------------------------------------------------------------------------------
 */
function io_conditional_class($base_class, $condition, $active_class = 'active')
{
    if ($condition) {
        return $base_class.' '.$active_class;
    }

    return $base_class;
}
/**
 * 给上传的图片生成独一无二的图片名.
 * 
 * @param string $filename 名称
 * @param string $type 文件类型
 * @return string
 */
function io_uniioque_img_name($filename, $type)
{
    $tmp_name = mt_rand(10, 25).time().$filename;
    $ext = str_replace('image/', '', $type);

    return md5($tmp_name).'.'.$ext;
}

/**
 * 裁剪图片并转换为JPG.
 * --------------------------------------------------------------------------------------
 */
function io_resize_img($ori, $dst = '', $dst_width = 100, $dst_height = 100, $delete_ori = false)
{ //绝对路径, 带文件名

    $original_ratio = $dst_width / $dst_height;
    $info = io_get_img_info($ori);

    if ($info) {
        if ($info['type'] == 'jpg' || $info['type'] == 'jpeg') {
            $im = imagecreatefromjpeg($ori);
        }
        if ($info['type'] == 'gif') {
            $im = imagecreatefromgif($ori);
        }
        if ($info['type'] == 'png') {
            $im = imagecreatefrompng($ori);
        }
        if ($info['type'] == 'bmp') {
            $im = imagecreatefromwbmp($ori);
        }
        if ($info['width'] / $info['height'] > $original_ratio) {
            $height = intval($info['height']);
            $width = $height * $original_ratio;
            $x = ($info['width'] - $width) / 2;
            $y = 0;
        } else {
            $width = intval($info['width']);
            $height = $width / $original_ratio;
            $x = 0;
            $y = ($info['height'] - $height) / 2;
        }
        $new_img = imagecreatetruecolor($width, $height);
        imagecopy($new_img, $im, 0, 0, $x, $y, $info['width'], $info['height']);
        $scale = $dst_width / $width;
        $target = imagecreatetruecolor($dst_width, $dst_height);
        $final_w = intval($width * $scale);
        $final_h = intval($height * $scale);
        imagecopyresampled($target, $new_img, 0, 0, 0, 0, $final_w, $final_h, $width, $height);
        imagejpeg($target, $dst ?: $ori);
        imagedestroy($im);
        imagedestroy($new_img);
        imagedestroy($target);

        if ($delete_ori) {
            unlink($ori);
        }
    }

    return;
}


/**
 * 根据上传配置用户头像类型.
 * --------------------------------------------------------------------------------------
 */
function io_update_user_avatar_by_upload($user_id = 0)
{
    $user_id = $user_id ?: get_current_user_id();
    update_user_meta($user_id, 'avatar_type', 'custom');
}
/**
 * 根据上传配置用户背景图类型.
 * --------------------------------------------------------------------------------------
 */
function io_update_user_cover_by_upload($user_id , $meta)
{
    $user_id = $user_id ?: get_current_user_id();
    update_user_meta($user_id, 'io_user_cover', $meta);
}

/**
 * 获取图片信息.
 * --------------------------------------------------------------------------------------
 */
function io_get_img_info($img)
{
    $imageInfo = getimagesize($img);
    if ($imageInfo !== false) {
        $imageType = strtolower(substr(image_type_to_extension($imageInfo[2]), 1));
        $info = array(
            'width' => $imageInfo[0],
            'height' => $imageInfo[1],
            'type' => $imageType,
            'mime' => $imageInfo['mime'],
        );

        return $info;
    } else {
        return false;
    }
}
if(!function_exists('io_get_user_cover')):
/**
 * 获取用户的封面.
 * --------------------------------------------------------------------------------------
 */
function io_get_user_cover($user_id, $size = 'full', $default = '')
{
    if (!in_array($size, array('full', 'mini'))) {
        $size = 'full';
    }
    if ($cover = get_user_meta($user_id, 'io_user_cover', true)) {
        return $cover . $size . '.jpg';
    }

    return $default ? $default : get_theme_file_uri('/images/user-default-cover-'.$size.'.jpg');
}
endif;

/**
 * 标记消息阅读状态
 * --------------------------------------------------------------------------------------
 */
function io_mark_message($id, $read = 1)
{
    $id = absint($id);
    $user_id = get_current_user_id(); //确保只能标记自己的消息

    if ((!$id || !$user_id)) {
        return false;
    }

    $read = $read == 0 ?: 1;

    global $wpdb;
    $table_name = $wpdb->iomessages;

    if ($wpdb->query($wpdb->prepare("UPDATE $table_name SET `msg_read` = %d WHERE `id` = %d AND `user_id` = %d", $read, $id, $user_id))) {
        return true;
    }

    return false;
}

/**
 * 标记所有未读消息已读.
 * --------------------------------------------------------------------------------------
 */
function io_mark_all_message_read($sender_id) {
    $user_id = get_current_user_id();
    if(!$user_id) return false;

    global $wpdb;
    $table_name = $wpdb->iomessages;

    if($wpdb->query( $wpdb->prepare("UPDATE $table_name SET `msg_read` = 1 WHERE `user_id` = %d AND `msg_read` = 0 AND `sender_id` = %d", $user_id, $sender_id) )) {
        return true;
    }
    return false;
}

/**
 * 获取单条消息.
 * --------------------------------------------------------------------------------------
 */
function io_get_message($msg_id)
{
    $user_id = get_current_user_id();
    if (!$user_id) {
        return false;
    } // 用于防止获取其他用户的消息

    global $wpdb;
    $table_name = $wpdb->iomessages;

    $row = $wpdb->get_row(sprintf("SELECT * FROM $table_name WHERE `id`=%d AND `user_id`=%d OR `sender_id`=%d", $msg_id, $user_id, $user_id));
    if ($row) {
        return $row;
    }

    return false;
}

/**
 * 查询消息.
 * --------------------------------------------------------------------------------------
 */
function io_get_messages($type = 'chat', $limit = 20, $offset = 0, $read = 0, $msg_status = 'publish', $sender_id = 0, $count = false)
{
    $user_id = get_current_user_id();

    if (!$user_id) {
        return false;
    }

    if (is_array($type)) {
        $type = implode("','", $type); //NOTE  IN('comment','star','update','notification') IN表达式的引号
    }
    if (!in_array($read, array(1, 0,9))) {
        $read = 0;
    }
    if (!in_array($msg_status, array('publish', 'trash', 'all'))) {
        $msg_status = 'publish';
    }

    global $wpdb;
    $table_name = $wpdb->iomessages;

    $sql = sprintf("SELECT %s FROM $table_name WHERE `user_id`=%d%s AND `msg_type` IN('$type')%s%s ORDER BY (CASE WHEN `msg_read`='all' THEN 1 ELSE 0 END) DESC, `id` DESC%s", $count ? 'COUNT(*)' : '*', $user_id, $sender_id ? " AND `sender_id`=$sender_id" : '', $read != 9 ? " AND `msg_read`=$read" : '', $msg_status != 'all' ? " AND `msg_status`='$msg_status'" : '', $count ? '' : " LIMIT $offset, $limit");

    $results = $count ? $wpdb->get_var($sql) : $wpdb->get_results($sql);
    if ($results) {
        return $results;
    }

    return 0;
}

/**
 * 指定类型消息计数.
 * --------------------------------------------------------------------------------------
 */
function io_count_messages($type = 'chat', $read = 0, $msg_status = 'publish', $sender_id = 0)
{
    return io_get_messages($type, 0, 0, $read, $msg_status, $sender_id, true);
}

/**
 * 获取未读消息.
 * --------------------------------------------------------------------------------------
 */
function io_get_unread_messages($type = 'chat', $limit = 20, $offset = 0, $msg_status = 'publish')
{
    return io_get_messages($type, $limit, $offset, 0, $msg_status);
}

/**
 * 未读消息计数.
 * --------------------------------------------------------------------------------------
 */
function io_count_unread_messages($type = 'chat', $msg_status = 'publish')
{
    return io_count_messages($type, 0, $msg_status);
}


/**
 * 获取消息
 *
 * @param   int    $user_id   用户ID
 * @param   string $type      通知类型
 * @param   int    $page      分页
 * @param   int    $limit     每页最多显示数量
 * @return  static
 * --------------------------------------------------------------------------------------
 */
function getNotificationData($user_id = 0, $type = 'all', $page = 1, $limit = 20) {
    $type = in_array($type, array('comment', 'star', 'credit', 'cash', 'update')) ? $type : array('comment', 'star', 'update', 'notification', 'credit', 'cash'); //TODO add more
    $notifications = io_get_messages($type, $limit, ($page - 1) * $limit, 9);
    $count = $notifications ? count($notifications) : 0;
    $total = io_count_messages( $type, 9);
    $max_pages = ceil($total / $limit);
    $pagination_base = is_array($type) ?   '/user/notifications/all/page/%#%' : '/user/notifications/' . $type . '/page/%#%';
    return (object)array(
        'count' => $count,
        'notifications' => $notifications,
        'total' => $total,
        'max_pages' => $max_pages,
        'pagination_base' => $pagination_base,
        'prev_page' => str_replace('%#%', max(1, $page - 1), $pagination_base),
        'next_page' => str_replace('%#%', min($max_pages, $page + 1), $pagination_base)
    );
}

/**
 * 给自定义页面Body添加额外的class.
 *
 * @param array $classes
 * @return array
 * --------------------------------------------------------------------------------------
 */
function ct_modify_body_classes($classes)
{
    if ($query_var = get_query_var('user')) {
        $classes[] = 'user-body-'.$query_var;
    } elseif ($query_var = get_query_var('custom_action')) {
        $classes[] = 'login-body-'.$query_var;
    } elseif ($query_var = get_query_var('user_child_route')) {
        $classes[] = 'user-body user-body-'.$query_var;
    } elseif ($query_var = get_query_var('bookmark_id')) {
        $classes[] = 'user-bookmark-body bookmark-'.$query_var;
    }

    return $classes;
}
add_filter('body_class', 'ct_modify_body_classes');

function get_bookmark_bg(){
    $bg=array(
        'bing'      => get_theme_file_uri('/images/bg/bing.jpg'),
        'custom'    => get_theme_file_uri('/images/bg/custom.png'),
        '07'        => get_theme_file_uri('/images/bg/07.png'),
        '08'        => get_theme_file_uri('/images/bg/08.png'),
        '09'        => get_theme_file_uri('/images/bg/09.png'),
        '10'        => get_theme_file_uri('/images/bg/10.png'),
        '11'        => get_theme_file_uri('/images/bg/11.png'),
        '12'        => get_theme_file_uri('/images/bg/12.png'),
        '13'        => get_theme_file_uri('/images/bg/13.png'),
        '14'        => get_theme_file_uri('/images/bg/14.png'),
        '15'        => get_theme_file_uri('/images/bg/15.png'),
        '16'        => get_theme_file_uri('/images/bg/16.png'),
        '17'        => get_theme_file_uri('/images/bg/17.png'),
    );
    return $bg;
}
/**
 * 个人书签设置
 * --------------------------------------------------------------------------------------
 */
function get_bookmark_seting( $type, $seting='')
{
    if($seting==''){
        global $bookmark_set;
        $seting == $bookmark_set;
    }
    $value='';
    switch ($type){
		case 'share_bookmark':
            if($seting){
                $value=$seting['share_bookmark']?'checked':'';
            }else{
                $value='checked';
            }
			break;
        case 'hide_title': 
            if($seting){
                $value=$seting['hide_title']?'checked':'';
            }else{
                $value='checked';
            }
			break;  
        case 'is_go': 
            if($seting){
                $value=(isset($seting['is_go']) && $seting['is_go'])?'checked':'';
            }else{
                $value='';
            }
			break; 
        case 'sites_title':
            if($seting){
                $value=$seting['sites_title'];
            }else{
                $value=io_get_option('search_skin')['big_title']?:get_bloginfo('name');
            }
            break;
        case 'quick_nav':  
            if($seting){
                $value=$seting['quick_nav'];
            }else{
                $value='';
            }
            break;
        case 'bg': 
            if($seting){
                $value=$seting['bg'];
            }else{
                $value='bing';
            }
            break;
        case 'custom_img': 
            if($seting){
                $value=$seting['custom_img'];
            }else{
                $value='';
            }
            break;
    }
    return $value;
}
