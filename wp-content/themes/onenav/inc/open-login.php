<?php 
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:55:58
 * @LastEditors: iowen
 * @LastEditTime: 2021-10-02 15:19:39
 * @FilePath: \onenav\inc\open-login.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }


require_once get_theme_file_path('/inc/classes/open.qq.class.php');
require_once get_theme_file_path('/inc/classes/open.sina.class.php');
require_once get_theme_file_path('/inc/classes/open.wechat.class.php');
require_once get_theme_file_path('/inc/classes/open.wechat.gzh.class.php');
require_once get_theme_file_path('/inc/classes/open.wechat.dyh.class.php');

//session_write_close()

//检测IO_ID是否重复
function userioid_exists( $ioid ) {
    global $wpdb;
    $user = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM $wpdb->users WHERE io_id = %s LIMIT 1",
            $ioid
        )
    );
    if ( ! $user ) {
        return false;
    }else{
        return $user->ID;
    }
} 
//添加 io_id
add_action('user_register', 'io_register_extra_fields');
function io_register_extra_fields($user_id) {
	global $wpdb;

    $prename = 'io';
    $extname = rand(100000,999998);
    $ioid = $prename.$extname;
    if(userioid_exists($ioid)){
        while(userioid_exists($ioid)){ $extname++; }
        $ioid = $prename.$extname;
    }
    $wpdb->query("UPDATE $wpdb->users SET io_id = '$ioid' WHERE ID = '$user_id'"); 
} 
function openloginFormButton(){
    if(!io_get_option('open_qq') && !io_get_option('open_weibo') && !io_get_option('open_wechat') && !io_get_option('open_weixin_dyh') && !io_get_option('open_weixin_gzh') ) return;
    echo '<p id="openlogin-box" class="openlogin-box text-center">'; 
    echo '<span class="social-separator separator text-muted text-xs mb-3">'.__('社交帐号登录','i_theme').'</span>';
    if(io_get_option('open_qq')) echo '<a href="'.get_theme_file_uri('/inc/auth/qq.php').'?loginurl='.(isset($_REQUEST["redirect_to"])?$_REQUEST["redirect_to"]:io_get_option('open_login_url')).'" title="QQ快速登录" rel="nofollow" class="openlogin-qq-a"><i class="iconfont icon-qq"></i></a>';
    if(io_get_option('open_weibo')) echo '<a href="'.get_theme_file_uri('/inc/auth/sina.php').'?loginurl='.(isset($_REQUEST["redirect_to"])?$_REQUEST["redirect_to"]:io_get_option('open_login_url')).'" title="微博快速登录" rel="nofollow" class="openlogin-weibo-a"><i class="iconfont icon-weibo"></i></a>';
    if(io_get_option('open_wechat')) echo '<a href="'.get_theme_file_uri('/inc/auth/wechat.php').'?loginurl='.(isset($_REQUEST["redirect_to"])?$_REQUEST["redirect_to"]:io_get_option('open_login_url')).'" title="微信快速登录" rel="nofollow" class="openlogin-wechat-a"><i class="iconfont icon-wechat"></i></a>';
    if(io_get_option('open_weixin_dyh')) {
        echo '<a href="javascript:" title="微信快速登录" rel="nofollow" data-action="get_weixin_dyh_qr"  class="openlogin-wechat-dyh-a"><i class="iconfont icon-wechat"></i></a>';
        $loginurl = isset($_REQUEST["redirect_to"])?$_REQUEST["redirect_to"]:io_get_option('open_login_url');
        echo'
<script type="text/javascript"> 
$(document).on("click","a.openlogin-wechat-dyh-a",function(){    
    $.ajax({
        url: "'.admin_url( 'admin-ajax.php' ).'",
        type: "POST", 
        dataType: "json",
        data : $(this).data(),
    })
    .done(function(response) { ';
        if(!io_get_option('user_center'))
            echo '$("#loginform").html("<div class=\'sign-header h4 mb-3 mb-md-5\'>扫码登陆</div>"+response.html); ';
        else
            echo '$("#wp_login_form").parent().html("<div class=\'sign-header h4 mb-3 mb-md-5\'>扫码登陆</div>"+response.html+response.but); ';
    echo '})
    .fail(function() { 
    })
});
$(document).on("click",".io-wx-btn",function(){
    var that = $(this),
        code = that.prev().val();
    if(code){
        if(!that.hasClass("disabled")){
            that.text("验证中...");
            that.addClass("disabled");
            $.post("'.admin_url( 'admin-ajax.php' ).'", {
                action: "io_dyh_login",
                code: code,
                rurl: "'.$loginurl.'"
            }, function(data) {
                if(data.status == "1"){
                    window.location.href = data.goto;
                    window.location.reload; 
                }else{
                    that.removeClass("disabled");
                    that.text("验证登录");
                    alert("登录失败！请检查是否验证码已过期～");
                }
            }, "Json");
        }
    }else{
        alert("请输入验证码～");
    }
    return false;
});
</script>';
    }
    if(io_get_option('open_weixin_gzh')) {
        if (io_is_wechat_app()) echo '<a href="'.get_theme_file_uri('/inc/auth/gzh.php').'?loginurl='.(isset($_REQUEST["redirect_to"])?$_REQUEST["redirect_to"]:io_get_option('open_login_url')).'" title="微信快速登录" rel="nofollow" class="openlogin-wechat-gzh-a"><i class="iconfont icon-wechat"></i></a>';
        else {
            echo '<a href="javascript:" title="微信快速登录" rel="nofollow" data-loginurl="'.(isset($_REQUEST["redirect_to"])?$_REQUEST["redirect_to"]:io_get_option('open_login_url')).'" data-action="get_weixin_gzh_qr"  class="openlogin-wechat-gzh-a"><i class="iconfont icon-wechat"></i></a>';
            $back_url = get_template_directory_uri().'/inc/auth/gzh-callback.php';
echo'
<script type="text/javascript">
var _state="";
$(document).on("click","a.openlogin-wechat-gzh-a",function(){    
    $.ajax({
        url: "'.admin_url( 'admin-ajax.php' ).'",
        type: "POST", 
        dataType: "json",
        data : $(this).data(),
    })
    .done(function(response) { ';
        if(!io_get_option('user_center'))
            echo '$("#loginform").html("<div class=\'sign-header h4 mb-3 mb-md-5\'>扫码登陆</div>"+response.html); ';
        else
            echo '$("#wp_login_form").parent().html("<div class=\'sign-header h4 mb-3 mb-md-5\'>扫码登陆</div>"+response.html+response.but); ';
    echo '_state = response.state;
        checkLogin();
    })
    .fail(function() { 
    })
});
function checkLogin() {
    $.post("'.$back_url.'", {
        state: _state,
        action: "check_callback"
    }, function(n) {
        if (n.goto) {
            window.location.href = n.goto;
            window.location.reload;
        } else {
            setTimeout(function() {
                checkLogin();
            }, 2000);
        }
    }, "Json");
}
</script>';

        }
    }
    echo '</p>';
?>
<?php
}
add_filter('io_login_form', 'openloginFormButton');

/**
 * 判断用户是否已经绑定了开放平台账户.
 *
 * @param string $type
 * @param int    $user_id
 *
 * @return bool|string
 */
function io_has_connect($type = 'qq', $user_id = 0){
    $user_id = $user_id ?: get_current_user_id();
    if($type){
        return get_user_meta($user_id, $type.'_openid', true);
    }

    return false;
} 

/**
 * 获取公众号二维码
 */   
function get_weixin_gzh_qr(){
    if(!session_id()) session_start(); 
    $wxConfig         = io_get_option('open_weixin_gzh_key');
    $WeChat           = new ioLoginWechatGZH($wxConfig['appid'], $wxConfig['appkey']);
    $qrcode_array     = $WeChat->getQrcode();                 //生成二维码
    $qrcode           = io_get_qrcode_base64($qrcode_array['url']);
    $_SESSION['rurl'] = $_REQUEST["loginurl"];
    
    $text = '微信扫码' . (!empty($_REQUEST["bind"]) ? '绑定' : '登录');
    $html = '<div class="text-center mb-2"><i class="text-success iconfont icon-qr-sweep mr-2"></i>' . $text . '</div>';
    $html .= '<div class="text-center"><img class="signin-qrcode-img" src="' . $qrcode . '" alt="' . $text . '"></div>';
    //$but  = '<div class="text-center mt-2"><a href="'. esc_url(home_url('/login/')) .'" class="btn btn-outline-danger px-4 px-lg-5 ml-auto">返回</a></div>';
    $but  = '<div class="text-muted"><small>使用其他方式 <a href="'. esc_url(home_url('/login/')) .'" class="signup">'.__('登录','i_theme').'</a> / <a href="'. esc_url(home_url('/login/?action=register')) .'" class="signup">'.__('注册','i_theme').'</a></small></div>';
    echo (json_encode(array('html' => $html, 'but'=>$but, 'url' => get_template_directory_uri().'/inc/auth/gzh-callback.php', 'state' => $WeChat->state)));
    die();
}
add_action( 'wp_ajax_get_weixin_gzh_qr' , 'get_weixin_gzh_qr' );
add_action( 'wp_ajax_nopriv_get_weixin_gzh_qr' , 'get_weixin_gzh_qr' );

/**
 * 获取订阅号二维码
 */   
function get_weixin_dyh_qr(){
    $wxConfig         = io_get_option('open_weixin_dyh_key'); 
    $qrcode           = $wxConfig['qr_code'];
    
    $action = !empty($_REQUEST["bind"]) ? '绑定' : '登录';
    $text = '微信扫码' . $action;
    $html = '<div class="text-center mb-2"><i class="text-success iconfont icon-qr-sweep mr-2"></i>' . $text . '</div>';
    $html .= '<div class="text-center"><img class="signin-qrcode-img" src="' . $qrcode . '" alt="' . $text . '"></div>';
    $html .= '<div class="text-center text-sm text-muted mb-3"> 如已关注，请回复“'.$action.'”二字获取验证码 </div>';
    $html .= '<div class="io-wx-box"><input type="text" id="io_ws_code" class="io-wx-input form-control" placeholder="验证码"/><button type="button" class="btn btn-success mt-2 io-wx-btn ml-2">验证'.$action.'</button></div>'; 
    //$but  = '<div class="text-center mt-2"><a href="'. esc_url(home_url('/login/')) .'" class="btn btn-outline-danger px-4 px-lg-5 ml-auto">返回</a></div>';
    $but  = '<div class="text-muted mt-3"><small>使用其他方式 <a href="'. esc_url(home_url('/login/')) .'" class="signup">'.__('登录','i_theme').'</a> / <a href="'. esc_url(home_url('/login/?action=register')) .'" class="signup">'.__('注册','i_theme').'</a></small></div>';
    echo (json_encode(array('html' => $html, 'but'=>$but, 'url' => get_template_directory_uri().'/inc/auth/gzh-callback.php')));
    die();
}
add_action( 'wp_ajax_get_weixin_dyh_qr' , 'get_weixin_dyh_qr' );
add_action( 'wp_ajax_nopriv_get_weixin_dyh_qr' , 'get_weixin_dyh_qr' );

function io_dyh_login_callback(){
    $code       = $_POST['code'];
    $back_url   = $_POST['rurl'];
    $status     = 0; 

    $openid     = get_transient( $code ); 
    if($openid){
        $wxConfig   = io_get_option('open_weixin_dyh_key');
        $wxCallback = new ioLoginWechatDYH($wxConfig['appid'], $wxConfig['appkey']); 
        $callback   = $wxCallback->use_db($openid,$back_url);
        if($callback['status']){
            $status     = 1;
            $back_url   = $callback['rurl'];
            delete_transient($code);
        }
    }
    $result = array(
        'status'    => $status,
        'goto'      => $back_url
    );

    header('Content-type: application/json');
    echo json_encode($result);
    exit;
}
add_action( 'wp_ajax_io_dyh_login', 'io_dyh_login_callback');
add_action( 'wp_ajax_nopriv_io_dyh_login', 'io_dyh_login_callback');


function io_filter_nickname($nickname){
    $nickname = preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $nickname);
    $nickname = preg_replace('/[\x{1F300}-\x{1F5FF}]/u', '', $nickname);
    $nickname = preg_replace('/[\x{1F680}-\x{1F6FF}]/u', '', $nickname);
    $nickname = preg_replace('/[\x{2600}-\x{26FF}]/u', '', $nickname);
    $nickname = preg_replace('/[\x{2700}-\x{27BF}]/u', '', $nickname);
    $nickname = str_replace(array('"','\''), '', $nickname);
    $nickname = preg_replace_callback( '/./u', function (array $match) {
        return strlen($match[0]) >= 4 ? '' : $match[0];
    }, $nickname);
    return addslashes(trim($nickname));
}



/**
 * 处理返回数据，更新用户资料
 */
function io_oauth_update_user($args,$is_weixin_dyh = false)
{
    /** 需求数据明细 */
    $defaults = array(
        'type'   => '',
        'openid' => '',
        'name' => '',
        'avatar' => '',
        'description' => '', 
        'getUserInfo' => array(),
        'rurl' => '', 
    );

    $args = wp_parse_args((array) $args, $defaults);

    // 初始化信息
    $openid_meta_key =  $args['type'] . '_openid';
    $openid = $args['openid'];
    $return_data = array(
        'redirect_url' => '',
        'msg' => '',
        'bind' => false,
        'error' => true,
    );

    global $wpdb, $current_user;

    // 查询该openid是否已存在
    $user_exist = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key=%s AND meta_value=%s", $openid_meta_key, $openid));

    // 查询已登录用户
    $current_user_id = get_current_user_id();

    //如果已经登录，且该openid已经存在
    if ($current_user_id && isset($user_exist) && $current_user_id != $user_exist) {
        $return_data['msg'] = '绑定失败，可能之前已有其他账号绑定，请先登录并解绑。';
        return $return_data;
    }

    if (isset($user_exist) && (int) $user_exist > 0) {
        // 该开放平台账号已连接过WP系统，再次使用它直接登录
        $user_exist = (int) $user_exist;

        //登录
        $user = get_user_by('id', $user_exist);
        wp_set_current_user($user_exist);
        wp_set_auth_cookie($user_exist, true);
        do_action('wp_login', $user->user_login, $user);

        //绑定尝试社交登录的账号
        io_update_oauth_data($user_exist);

        $return_data['redirect_url'] = get_author_posts_url($user_id);  //重定向链接到用户中心
        $return_data['error'] = false;
    } elseif ($current_user_id) {
        // 已经登录，但openid未占用，则绑定，更新用户字段
        // 更新用户mate
        $args['user_id'] = $current_user_id;

        //绑定用户不更新以下数据
        $args['name'] = '';
        $args['description'] = '';

        io_oauth_update_user_meta($args);
        // 准备返回数据
        $return_data['redirect_url'] = get_author_posts_url($user_id);  //重定向链接到用户中心
        $return_data['error'] = false;
    } else {
        // 既未登录且openid未占用，则新建用户并绑定
        if(io_get_option('user_center') && io_get_option('bind_email')=='must'){
            //添加到临时数据
            if(!session_id()) session_start();
            $_SESSION['temp_oauth'] = maybe_serialize($args);
            if($is_weixin_dyh){
                $return_data['redirect_url'] = get_author_posts_url($user_id);  //重定向链接到用户中心
                $return_data['bind'] = true;
                $return_data['error'] = false;
                return $return_data;
            }else{
                wp_safe_redirect(home_url('/login/?action=bind').'&redirect_to='.$args['rurl']);
                exit();
            }
        }
        $prename = 'io';
        $extname = rand(1000000,9999998);
        $login_name = $prename.$extname;
        if(username_exists($login_name)){
            while(username_exists($login_name)){ $extname++; }
            $login_name = $prename.$extname;
        }
        $user_pass = wp_generate_password();  

        $user_id = wp_create_user($login_name, $user_pass);
        if (is_wp_error($user_id)) {
            //新建用户出错
            $return_data['msg'] = $user_id->get_error_message();
        } else {
            //新建用户成功
            update_user_meta($user_id, 'oauth_new', $args['type']);
            /**标记为系统新建用户 */
            //更新用户mate
            $args['user_id'] = $user_id;
            $args['login_name'] = $login_name;
            io_oauth_update_user_meta($args, true);

            //登录
            $user = get_user_by('id', $user_id);
            wp_set_current_user($user_id, $user->user_login);
            wp_set_auth_cookie($user_id, true);
            do_action('wp_login', $user->user_login, $user);
            // 准备返回数据
            $return_data['redirect_url'] = get_author_posts_url($user_id);  //重定向链接到用户中心
            $return_data['bind'] = true;
            $return_data['error'] = false;
        }
    }
    return $return_data;
}


function io_oauth_update_user_meta($args, $is_new = false)
{
    /** 需求数据明细 */
    $defaults = array(
        'user_id' => '',
        'type'   => '',
        'openid' => '',
        'name' => '',
        'login_name' => '',
        'avatar' => '',
        'description' => '',
        'getUserInfo' => array(),
    );
    $args = wp_parse_args((array) $args, $defaults);

    update_user_meta($args['user_id'],  $args['type'] . '_openid', $args['openid']);
    update_user_meta($args['user_id'],  $args['type'] . '_getUserInfo', $args['getUserInfo']);
    update_user_meta($args['user_id'], 'name_change', 1); 

    //自定义头像，无则添加
    $custom_avatar = get_user_meta($args['user_id'], 'custom_avatar', true);
    if ($args['avatar'] && !$custom_avatar) {
        update_user_meta($args['user_id'], 'custom_avatar', $args['avatar']);
    }
    if($args['avatar']){
        update_user_meta($args['user_id'], $args['type'] . '_avatar', $args['avatar']);
    }

    //自定义简介，无则添加
    $description = get_user_meta($args['user_id'], 'description', true);
    if ($args['description'] && !$description) {
        update_user_meta($args['user_id'], 'description', $args['description']);
    }

    if ($is_new) {
        if($args['avatar'])
            update_user_meta($args['user_id'], 'avatar_type', $args['type']);
        else
            update_user_meta($args['user_id'], 'avatar_type', 'letter');
        //新建用户，更新display_name
        $nickname = trim($args['name']);
        if (is_username_legal($nickname)['error']) {
            //判断用户名是否合法
            $nickname = $args['login_name'] ? str_replace('io', '用户', $args['login_name'])  : "用户" .  rand(1000000,9999998);
        }

        $user_datas = array(
            'ID' => $args['user_id'],
            'display_name' => $nickname,
            'nickname'     => $nickname,
        );
        wp_update_user($user_datas);
    }
}
/**
 * 社交登录后执行
 * @description: 
 * @param array $oauth_result
 * @param string $back_url
 * @return null
 */
function io_oauth_login_after_execute($oauth_result,$back_url,$is_redirect=true){
    if ($oauth_result['error']) {
        wp_die('<meta charset="UTF-8" />' . ($oauth_result['msg'] ? $oauth_result['msg'] : '处理失败'));
        exit;
    } else {
        $rurl = !empty($back_url) ? $back_url : $oauth_result['redirect_url'];
        if(io_get_option('user_center') && $oauth_result['bind'] && io_get_option('bind_email')=='bind'){
            wp_safe_redirect(home_url('/login/?action=bind').'&redirect_to='.$rurl);
        }else{
            if($is_redirect) wp_safe_redirect($rurl);
        }
        exit;
    }
}
/**
 * 第一次社交登录时绑定到已有账号
 * @param int $user_id 旧账号 ID
 * @return null
 */
function io_update_oauth_data($user_id){

    if(!session_id()) session_start();
    if( isset($_SESSION['temp_oauth']) && !empty($_SESSION['temp_oauth'])){
        $args =  maybe_unserialize($_SESSION['temp_oauth']);
        $args['user_id'] = $user_id;
        //绑定用户不更新以下数据
        $args['name'] = '';
        $args['description'] = '';
        io_oauth_update_user_meta($args); 

        unset($_SESSION['temp_oauth']);
    }
}
