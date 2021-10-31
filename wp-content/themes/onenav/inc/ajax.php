<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:55:58
 * @LastEditors: iowen
 * @LastEditTime: 2021-10-09 20:59:54
 * @FilePath: \onenav\inc\ajax.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action('wp_ajax_nopriv_user_login', 'user_login_callback'); 
function user_login_callback(){ 
    $username = esc_sql($_POST['username']);
    $password = $_POST['password'];
    $remember = esc_sql($_POST['rememberme']);
    if($remember){
        $remember = true;
    } else {
        $remember = false;
    }
    if($username=='' || $password==''){ 
        error('{"status":2,"msg":"'.__('请认真填写表单！','i_theme').'"}');
        exit();
    }
    //执行人机验证
    io_ajax_is_robots();

    if(is_email($username)){
        $user = get_user_by( 'email', $username );
        if ( isset( $user, $user->user_login, $user->user_status ) && 0 == (int) $user->user_status )
            $username = $user->user_login;
    }
    $login_data = array(
        'user_login' =>$username,
        'user_password' =>$password,
        'remember' =>$remember,
    ); 

    $user_verify = wp_signon($login_data);
    //wp_signon 是wordpress自带的函数，通过用户信息来授权用户(登陆)，可记住用户名
    if(is_wp_error($user_verify)){
        error('{"status":3,"msg":"'.__('用户名或密码错误，请重试!','i_theme').'"}'); 
        exit();
    } else {  
        
        //绑定尝试社交登录的账号
        if(io_get_option('user_center')&&isset($_POST['old_bind'])) io_update_oauth_data($user_verify->ID);

        if ( isset( $_REQUEST['redirect'] ) || isset( $_REQUEST['redirect_to'] ) ){
            $redirect_to = isset($_REQUEST['redirect']) ?  $_REQUEST['redirect'] : $_REQUEST['redirect_to'];  
        } elseif (user_can($user_verify->ID,'manage_options')) {
            $redirect_to = admin_url();  
        } else {
            $redirect_to = home_url();  
        }
        error('{"status":1,"msg":"'.__('登录成功，跳转中！','i_theme').'","goto":"'.urldecode($redirect_to).'"}'); 
        exit();
    }
    
}
//提交文章
add_action('wp_ajax_nopriv_contribute_post', 'io_contribute_callback');  
add_action('wp_ajax_contribute_post', 'io_contribute_callback');
function io_contribute_callback(){  
    if (!wp_verify_nonce($_POST['_ajax_nonce'],"tougao_robot")){
        error('{"status":4,"msg":"'.__('对不起!您没有通过安全检查','i_theme').'"}');
    }
    $delay = 60; 
    if( isset($_COOKIE["tougao"]) && ( time() - $_COOKIE["tougao"] ) < $delay ){
        error('{"status":2,"msg":"'.sprintf( __('您投稿也太勤快了吧，“%s”秒后再试！', 'i_theme'), $delay - ( time() - $_COOKIE["tougao"] ) ).'"}');
    } 
    $_tougao_ico = isset($_FILES['tougao_ico'])?$_FILES['tougao_ico']:[];
    $_wechat_qr = isset($_FILES['wechat_qr'])?$_FILES['wechat_qr']:[];
    //表单变量初始化
    $sites_type         = isset( $_POST['tougao_type'] ) ? trim(htmlspecialchars($_POST['tougao_type'])) : '';
    $sites_link         = isset( $_POST['tougao_sites_link'] ) ? trim(htmlspecialchars($_POST['tougao_sites_link'])) : '';
    $sites_sescribe     = isset( $_POST['tougao_sites_sescribe'] ) ? trim(htmlspecialchars($_POST['tougao_sites_sescribe'])) : '';
    $title              = isset( $_POST['tougao_title'] ) ? trim(htmlspecialchars($_POST['tougao_title'])) : '';
    $category           = isset( $_POST['tougao_cat'] ) ? sanitize_key($_POST['tougao_cat']) : '0';
    $sites_ico          = isset( $_POST['tougao_sites_ico'] ) ? trim(htmlspecialchars($_POST['tougao_sites_ico'])) : '';
    $wechat_qr          = isset( $_POST['tougao_wechat_qr'] ) ? trim(htmlspecialchars($_POST['tougao_wechat_qr'])) : '';
    $content            = isset( $_POST['tougao_content'] ) ? trim(htmlspecialchars($_POST['tougao_content'])) : '';
    $keywords           = isset( $_POST['tougao_sites_keywords'] ) ? trim(htmlspecialchars($_POST['tougao_sites_keywords'])) : '';
    $publish            = isset( $_POST['is_publish'] ) ? $_POST['is_publish'] : '0';

    
    $down_version       = isset( $_POST['tougao_down_version'] ) ? trim(htmlspecialchars($_POST['tougao_down_version'])) : '';//资源版本
    $down_formal        = isset( $_POST['tougao_down_formal'] ) ? trim(htmlspecialchars($_POST['tougao_down_formal'])) : '';//官网链接
    $sites_down         = isset( $_POST['tougao_sites_down'] ) ? trim(htmlspecialchars($_POST['tougao_sites_down'])) : '';//网盘链接
    $down_preview       = isset( $_POST['tougao_down_preview'] ) ? trim(htmlspecialchars($_POST['tougao_down_preview'])) : '';//演示链接
    $sites_password     = isset( $_POST['tougao_sites_password'] ) ? trim(htmlspecialchars($_POST['tougao_sites_password'])) : '';//网盘密码
    $down_decompression = isset( $_POST['tougao_down_decompression'] ) ? trim(htmlspecialchars($_POST['tougao_down_decompression'])) : '';//解压密码

    $typename = __('网站','i_theme');
    if( $sites_type == 'down' )
    $typename = __('资源','i_theme');
    if( $sites_type == 'wechat' )
    $typename = __('公众号','i_theme');

    $post_status = 'pending';
    if(io_get_option('is_publish') && $publish != '0'){
        if(io_get_option('tougao_category'))
            $category = io_get_option('tougao_category');
        $post_status = 'publish';
    }

    // 表单项数据验证
    if ( empty($title) || mb_strlen($title) > 30 ) {
        error('{"status":4,"msg":"'.$typename.__('名称必须填写，且长度不得超过30字。','i_theme').'"}');
    }
    global $wpdb; 
    $titles = "SELECT post_title FROM $wpdb->posts WHERE post_status IN ('pending','publish') AND post_type = 'sites' AND post_title = '{$title}'";
    if($wpdb->get_row($titles)) {
        error('{"status":4,"msg":"'.__('存在相同的名称，请不要重复提交哦！','i_theme').'"}');
    }

    if ( $sites_type=='sites' && empty($sites_link) || (!empty($sites_link) && !preg_match("/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,8}(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/", $sites_link))){
        error('{"status":3,"msg":"'.$typename.__('链接必须填写，且必须符合URL格式。','i_theme').'"}');
    }
    $meta_value = "SELECT meta_value FROM $wpdb->postmeta WHERE meta_value = '{$sites_link}' AND meta_key='_sites_link'";
    if( $sites_type=='sites' && $wpdb->get_row($meta_value)) {
        error('{"status":4,"msg":"'.__('存在相同的链接地址，请不要重复提交哦！','i_theme').'"}');
    }

    if ( empty($sites_sescribe) || mb_strlen($sites_sescribe) > 50 ) {
        error('{"status":4,"msg":"'.$typename.__('描叙必须填写，且长度不得超过50字。','i_theme').'"}');
    }
    if ( $category == "0" ){
        error('{"status":4,"msg":"'.__('请选择分类。','i_theme').'"}');
    }
    if ( !empty(get_term_children($category, 'favorites'))){
        error('{"status":4,"msg":"'.__('不能选用父级分类目录。','i_theme').'"}');
    }
    //if ( empty($content) || mb_strlen($content) > 10000 || mb_strlen($content) < 6) {
    //    error('{"status":4,"msg":"内容必须填写，且长度不得超过10000字，不得少于6字。"}');
    //}
    if( $sites_type == 'down'){
        if ( empty($down_formal) && empty($sites_down) ) {
            error('{"status":4,"msg":"'.__('“官网地址”和“网盘地址”怎么地也待填一个把。','i_theme').'"}');
        }
    }
    //if(!empty($sites_ico)){
    //    $sites_ico = array(
    //        'url'       => $sites_ico,  
    //        'thumbnail' => $sites_ico, 
    //    );
    //}
    //if(!empty($wechat_qr)){
    //    $wechat_qr = array(
    //        'url'       => $wechat_qr,  
    //        'thumbnail' => $wechat_qr, 
    //    );
    //}

    //执行人机验证
    io_ajax_is_robots();
    
    $oldimg_id = 0;
    if(!empty($_tougao_ico) && $_tougao_ico['error']=="0"){
        $_img = addImg($_tougao_ico,'tougao_ico');
        $sites_ico = $_img["src"];
        $oldimg_id = $_img["id"];
    }
    if(!empty($_wechat_qr) && $_wechat_qr['error']==0){
        $_img = addImg($_wechat_qr,'wechat_qr',$oldimg_id);
        $wechat_qr = $_img["src"];
    }
    if ( $sites_type=='wechat' && empty($wechat_qr)) {
        error('{"status":4,"msg":"'.__('必须添加二维码。','i_theme').'"}');
    }

    $down_list = array();
    if(!empty($sites_down)){ 
            $down_list['down_btn_name'] = '网盘下载';
            $down_list['down_btn_url'] = $sites_down;
            $down_list['down_btn_tqm'] = $sites_password;
            $down_list['down_btn_info'] = '';
    }
    if(!empty($keywords) && $publish == '0') {
        $content = '<span style="color:red">&lt;删除&gt;</span><h1>剪切下方关键字到标签：</h1>'.PHP_EOL. $keywords.PHP_EOL.'<h1>正文：</h1><span style="color:red">&lt;/删除&gt;</span>'.PHP_EOL . $content;
    }
    $tougao = array(
        'comment_status'   => 'closed',
        'ping_status'      => 'closed',
        //'post_author'      => 1,//用于投稿的用户ID
        'post_title'       => $title,
        'comment_status'   => 'open',
        'post_content'     => $content,
        'post_status'      => $post_status,
        'post_type'        => 'sites',
        //'tax_input'        => array( 'favorites' => array($category) ) //游客不可用
    );
    
    // 将文章插入数据库
    $status = wp_insert_post( $tougao );
    if ($status != 0){
        global $wpdb;
        add_post_meta($status, '_sites_type', $sites_type);
        add_post_meta($status, '_sites_sescribe', $sites_sescribe);
        add_post_meta($status, '_sites_link', $sites_link);
        add_post_meta($status, '_down_version', $down_version);
        add_post_meta($status, '_down_formal', $down_formal);
        //add_post_meta($status, '_sites_down', $sites_down);
        add_post_meta($status, '_down_preview', $down_preview);
        //add_post_meta($status, '_sites_password', $sites_password);
        add_post_meta($status, '_down_url_list', array($down_list));//----
        add_post_meta($status, '_dec_password', $down_decompression);
        add_post_meta($status, '_sites_order', '0');
        if( !empty($sites_ico))
            add_post_meta($status, '_thumbnail', $sites_ico); 
        if( !empty($wechat_qr))
            add_post_meta($status, '_wechat_qr', $wechat_qr); 
        wp_set_post_terms( $status, array($category), 'favorites'); //设置文章分类
        if(!empty($keywords) && $publish != '0') wp_set_post_terms( $status, explode(',', $keywords), 'sitetag'); //设置文章tag
        setcookie("tougao", time(), time()+$delay+10);// 如果是直接发布的
        if ($post_status != 'publish') {
            do_action('io_contribute_to_publish', $title);
        }
        error('{"status":1,"msg":"'.__('投稿成功！','i_theme').'"}');
    }else{
        error('{"status":4,"msg":"'.__('投稿失败！','i_theme').'"}');
    }
}
 
//提交评论
add_action('wp_ajax_nopriv_ajax_comment', 'fa_ajax_comment_callback');
add_action('wp_ajax_ajax_comment', 'fa_ajax_comment_callback');
if(!function_exists('fa_ajax_comment_callback')){
function fa_ajax_comment_callback(){
    if (!wp_verify_nonce($_POST['_wpnonce'],"comment_ticket")){
        error('{"status":4,"msg":"'.__('对不起!您没有通过安全检查','i_theme').'"}',true);
    }
	io_ajax_is_robots();
    $comment = wp_handle_comment_submission( wp_unslash( $_POST ) );
    if ( is_wp_error( $comment ) ) {
        $data = $comment->get_error_data();
        if ( ! empty( $data ) ) {
            error('{"status":4,"msg":"'.$comment->get_error_message().'"}', true);
        } else {
            exit;
        }
    }
    $user = wp_get_current_user();
    do_action('set_comment_cookies', $comment, $user);
    $GLOBALS['comment'] = $comment; //根据你的评论结构自行修改，如使用默认主题则无需修改
    ?> 
    <li <?php comment_class('comment'); ?> id="li-comment-<?php comment_ID() ?>" style="position: relative;">
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
                </div>
            </section>
        </div>
        <div class="new-comment" style="background: #4bbbff;position: absolute;top: -1rem;bottom: 1rem;left: -1.25rem;right: -1.25rem;opacity: .2;"></div>
        </li>    
    <?php die();
}
}
;  
add_action('wp_ajax_save_links_img', 'io_save_links_img_callback');
function io_save_links_img_callback(){ 
    global $wpdb;  

    $img_link = isset( $_POST['img_link'] ) ? trim(htmlspecialchars($_POST['img_link'])) : '';
    if(!empty($img_link)){
        $ico_source = io_get_option('ico-source');
        $img = ($ico_source['ico_url'] .format_url($img_link) . $ico_source['ico_png']);
        echo json_encode(io_save_img($img));
    } else {
        error('{"status":0,"msg":"请填写地址！"}', true); 
    }
    exit;  
}

// 查重
add_action('wp_ajax_nopriv_check_duplicate', 'io_check_duplicate');  
add_action('wp_ajax_check_duplicate', 'io_check_duplicate');
function io_check_duplicate(){ 
    global $wpdb;  

    $sites_link = isset( $_POST['sites_link'] ) ? trim(htmlspecialchars($_POST['sites_link'])) : '';
    $sites_link = rtrim($sites_link, '/');
    if(!empty($sites_link)){
        
        $meta_value = "SELECT meta_value FROM $wpdb->postmeta WHERE ( meta_value = '{$sites_link}' OR meta_value = '{$sites_link}/' ) AND meta_key='_sites_link'";
        if($wpdb->get_row($meta_value)) {
            echo __('存在相同的链接地址，请不要重复提交哦！','i_theme') ;
        }
        else{
            echo __('没有重复地址，可以提交！','i_theme') ;
        }  
    } else {
        echo __('请填写地址！','i_theme') ;
    }
    exit;  
}

//点赞
add_action('wp_ajax_nopriv_post_like', 'io_like_ajax_handler');  
add_action('wp_ajax_post_like', 'io_like_ajax_handler');
function io_like_ajax_handler(){
    global $wpdb, $post;  
    if($post_id = sanitize_key($_POST["post_id"])){
        
        if($post_id <= 0)
            return;

        $like_count = get_post_meta($post_id, '_like_count', true);  

        $expire = time() + 99999999;  
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false; // make cookies work with localhost  

        setcookie('liked_' . $post_id, $post_id, $expire, '/', $domain, false);  
        if (!$like_count || !is_numeric($like_count)){
            update_post_meta($post_id, '_like_count', 1);
        }else{
            update_post_meta($post_id, '_like_count', ($like_count + 1));
        }

        echo get_post_meta($post_id, '_like_count', true); 
    }
    exit;  
}
//设置链接失败
add_action('wp_ajax_nopriv_link_failed', 'io_link_failed');  
add_action('wp_ajax_link_failed', 'io_link_failed');
function io_link_failed(){  
    global $wpdb, $post;  
    if($post_id = (int) sanitize_key( $_POST["post_id"]) ){
        $is_inv = $_POST["is_inv"];
        if( $post_id > 0 ){
            $invalid_count = get_post_meta($post_id, 'invalid', true);  
            if( $is_inv=="false" ){
                if ( !$invalid_count || !is_numeric($invalid_count) ){
                    update_post_meta($post_id, 'invalid', 1);
                }else{
                    update_post_meta($post_id, 'invalid', ($invalid_count + 1));
                }
            } else {
                if ( ($invalid_count || is_numeric($invalid_count)) && $invalid_count > 0){ 
                    update_post_meta($post_id, 'invalid', ($invalid_count - 1));
                }
            }
            echo "反馈成功 ".$is_inv; 
        }
    }
    exit;  
}

// 增加文章浏览统计
add_action( 'wp_ajax_io_postviews', 'io_n_increment_views' );
add_action( 'wp_ajax_nopriv_io_postviews', 'io_n_increment_views' );
function io_n_increment_views() {
    if( empty( $_GET['postviews_id'] ) )
        return;

    $post_id =  (int) sanitize_key( $_GET['postviews_id'] );
    if( $post_id > 0 && is_views_execution(io_get_option( 'views_options' )) ) {
        $views_count = get_post_meta($post_id, 'views', true);  
        if (!$views_count || !is_numeric($views_count)){
            $views_count = 0;
        }
        update_post_meta($post_id, 'views', ($views_count + 1));
        if (io_get_option('leader_board')&&!is_page()) io_add_post_view($post_id,get_post_type( $post_id ),wp_is_mobile());
        echo $views_count+1;
        exit();
    }
}


// 获取文章浏览数据
add_action( 'wp_ajax_get_post_ranking_data', 'io_get_post_ranking_data' );
add_action( 'wp_ajax_nopriv_get_post_ranking_data', 'io_get_post_ranking_data' );
function io_get_post_ranking_data() {
    if (!wp_verify_nonce($_POST['data']['nonce'],"post_ranking_data")){
        error('{"status":4,"msg":"'.__('对不起!您没有通过安全检查','i_theme').'"}');
    }
    if( empty( $_POST['data']['post_id'] ) )
        error('{"status":0,"msg":"'.__('错误！','i_theme').'"}');
    $post_id =  (int) sanitize_key( $_POST['data']['post_id'] );
    $type = trim(htmlspecialchars($_POST['data']['type']));
    if( $post_id > 0 ) {
        global $iodb;
        $views_data = $iodb->getPostViewsData($post_id);
        if($views_data){
            $post_data=[];
            foreach($views_data as $v){
                $post_data[$v->time] =  get_object_vars($v );
            }
            date_default_timezone_set('Asia/Shanghai');
            $desktop    = [];
            $mobile     = [];
            $download   = [];
            $count      = [];
            $x_axis     = [];
            if($type == "down")
            $series     = ['pc', __('移动端','i_theme'), __('合计','i_theme'),__('下载量','i_theme')];
            else
            $series     = ['pc', __('移动端','i_theme'), __('合计','i_theme')];
            $day = (int)io_get_option('how_long')-1;
            if($day>29)$day=29;
            for($i=$day;$i>=0;$i--){
                $time = date("Y-m-d", strtotime('-'. $i . 'day'));
                $x_axis[] = $time;
                if(array_key_exists($time,$post_data)){
                    $desktop[]  = (int)$post_data[$time]['desktop'];
                    $mobile[]   = (int)$post_data[$time]['mobile'];
                    $download[] = (int)$post_data[$time]['download'];
                    $count[]    = (int)$post_data[$time]['count'];
                }else{
                    $desktop[]  = 0;
                    $mobile[]   = 0;
                    $download[] = 0;
                    $count[]    = 0;
                }
            }
            $_data = array(
                'series'    => $series,
                'x_axis'    => $x_axis,
                'desktop'   => $desktop,
                'mobile'    => $mobile,
                'download'  => $download,
                'count'     => $count,
            );
            unset($post_data,$series, $x_axis,$desktop,$mobile,$download,$count);
            error(json_encode(array(
                'status' => 1,
                'msg'    => '成功',
                'type'   => $type,
                'data'   => $_data,
            )));
        }
    }
    error('{"status":0,"msg":"错误！"}');
}

// 解除绑定
add_action( 'wp_ajax_unbound_open_id', 'unbound_open_id' );
function unbound_open_id() {
    $user = wp_get_current_user();
    if(!$user->ID) {
        error('{"status":2,"msg":"'.__('请先登录!','i_theme').'"}'); 
    }  
    if (empty($_POST['user_id']) || empty($_POST['type'])) {
        error('{"status":3,"msg":"'.__('参数错误!','i_theme').'"}');  
    }
    $cuid = get_current_user_id();
    if ($user->ID != $_POST['user_id']) {
        error('{"status":3,"msg":"'.__('权限不足!','i_theme').'"}');   
    }
    if(!get_user_meta($_POST['user_id'], 'email_status')){
        error('{"status":4,"msg":"'.__('请先验证邮箱！','i_theme').'"}');   
    }

    delete_user_meta($_POST['user_id'],  $_POST['type'] . '_openid');  
    delete_user_meta($_POST['user_id'],  $_POST['type'] . '_getUserInfo'); 
    delete_user_meta($_POST['user_id'],  $_POST['type'] . '_avatar'); 
    if(get_user_meta( $_POST['user_id'], 'avatar_type', true )==$_POST['type']){
        update_user_meta( $_POST['user_id'], 'avatar_type', 'letter');
    }
    error('{"status":1,"msg":"'.__('已解除绑定','i_theme').'"}'); 

    exit();
}
// 增加国家数据，临时方法
add_action( 'wp_ajax_io_set_country', 'io_set_country' );
add_action( 'wp_ajax_nopriv_io_set_country', 'io_set_country' );
function io_set_country() {
    if( empty( $_POST['id'] ) )
        return;
    $country = $_POST['country'];
    $post_id =  (int) sanitize_key( $_POST['id'] );
    if( $post_id > 0 ) { 
        update_post_meta($post_id, '_sites_country', $country); 
        exit();
    }
}
//显示模式切换
add_action('wp_ajax_nopriv_switch_dark_mode', 'io_switch_dark_mode');  
add_action('wp_ajax_switch_dark_mode', 'io_switch_dark_mode');
function io_switch_dark_mode(){    
    $mode = $_POST["mode_toggle"];
    $expire = time() + 99999999;  
    $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false; // make cookies work with localhost  
    setcookie('io_night_mode', $mode, $expire, '/', $domain, false);  
    exit; 
}

// 增加app下载量
add_action( 'wp_ajax_down_count', 'io_add_down_count' );
add_action( 'wp_ajax_nopriv_down_count', 'io_add_down_count' );
function io_add_down_count() {
    if( empty( $_POST['id'] ) )
        return;

    $post_id =  (int) sanitize_key( $_POST['id'] );
    if( $post_id > 0 ) {
        if (io_get_option('leader_board')&&!is_page()) io_add_post_view($post_id,get_post_type( $post_id ),0,1,'down');
        $down_count = get_post_meta($post_id, '_down_count', true);  
        if (!$down_count || !is_numeric($down_count)){
            $down_count = 0;
        }
        update_post_meta($post_id, '_down_count', ($down_count + 1));
        echo $down_count+1;
        exit();
    }
}

// 加载热门网址   
add_action( 'wp_ajax_load_hot_sites' , 'load_hot_sites' );
add_action( 'wp_ajax_nopriv_load_hot_sites' , 'load_hot_sites' );
function load_hot_sites(){

    $meta_key = sanitize_text_field($_POST['type']); 

    global $post;
    $site_n = io_get_option('hot_n');
    $args = array(
        'post_type'           => 'sites',  
        'post_status'         => array( 'publish', 'private' ),//'publish',
        'perm'                => 'readable',
        'ignore_sticky_posts' => 1,   
        'posts_per_page'      => $site_n,       
    );
    if($meta_key == 'date'){
        $args['orderby'] = 'date';
    }else{
        $args['meta_key'] = $meta_key;
        $args['orderby'] = array( 'meta_value_num' => 'DESC', 'date' => 'DESC' );
    }
    $myposts = new WP_Query( $args );
    if(!$myposts->have_posts()): ?>
        <div class="col-lg-12">
            <div class="nothing mb-4"><?php _e('没有数据！请开启统计并等待产生数据','i_theme') ?></div>
        </div>
    <?php
    elseif ($myposts->have_posts()): while ($myposts->have_posts()): $myposts->the_post(); 
        //if(current_user_can('level_10') || !get_post_meta($post->ID, '_visible', true)):
    ?>
        <?php if(io_get_option("hot_card_mini")) {?>
            <div class="url-card ajax-url col-6 <?php get_columns() ?> col-xxl-10a <?php echo before_class($post->ID) ?>">
            <?php include( get_theme_file_path('/templates/card-sitemini.php')  ); ?>
        <?php }else{?>
            <div class="url-card <?php echo io_get_option('two_columns')?"col-6":"" ?> ajax-url <?php get_columns() ?> <?php echo before_class($post->ID) ?>">
            <?php include( get_theme_file_path('/templates/card-site.php')  );?>
        <?php }?>
        </div>
    <?php //endif; 
    endwhile; endif; wp_reset_postdata();  
    
    die();
}
// 加载热门app
add_action( 'wp_ajax_load_hot_app' , 'load_hot_app' );
add_action( 'wp_ajax_nopriv_load_hot_app' , 'load_hot_app' );
function load_hot_app(){

    $meta_key = sanitize_text_field($_POST['type']); 

    global $post;
    $site_n = io_get_option('hot_n');
    $args = array(
        'post_type'           => 'app', 
        'post_status'         => array( 'publish', 'private' ),//'publish',
        'perm'                => 'readable',
        'ignore_sticky_posts' => 1,              
        'posts_per_page'      => $site_n,       
    );
    if($meta_key == 'date'){
        $args['orderby'] = 'date';
    }else{
        $args['meta_key'] = $meta_key;
        $args['orderby'] = array( 'meta_value_num' => 'DESC', 'date' => 'DESC' );
    }
    $myposts = new WP_Query( $args );
    if(!$myposts->have_posts()): ?>
        <div class="col-lg-12">
            <div class="nothing mb-4"><?php _e('没有数据！请开启统计并等待产生数据','i_theme') ?></div>
        </div>
    <?php
    elseif ($myposts->have_posts()): while ($myposts->have_posts()): $myposts->the_post();   
    ?> 
        <div class="col-12 col-md-6 col-lg-4 col-xxl-5a ajax-url">
        <?php
            include( get_theme_file_path('/templates/card-appcard.php') ); 
            ?>
        </div>
    <?php  endwhile; endif; wp_reset_postdata();  
    
    die();
}
// 加载热门书籍
add_action( 'wp_ajax_load_hot_book' , 'load_hot_book' );
add_action( 'wp_ajax_nopriv_load_hot_book' , 'load_hot_book' );
function load_hot_book(){

    $meta_key = sanitize_text_field($_POST['type']); 

    global $post;
    $site_n = io_get_option('hot_n');
    $args = array(
        'post_type'           => 'book', 
        'post_status'         => array( 'publish', 'private' ),//'publish',  
        'perm'                => 'readable',
        'ignore_sticky_posts' => 1,              
        'posts_per_page'      => $site_n,       
    );
    if($meta_key == 'date'){
        $args['orderby'] = 'date';
    }else{
        $args['meta_key'] = $meta_key;
        $args['orderby'] = array( 'meta_value_num' => 'DESC', 'date' => 'DESC' );
    }
    $myposts = new WP_Query( $args );
    if(!$myposts->have_posts()): ?>
        <div class="col-lg-12">
            <div class="nothing mb-4"><?php _e('没有数据！请开启统计并等待产生数据','i_theme') ?></div>
        </div>
    <?php
    elseif ($myposts->have_posts()): while ($myposts->have_posts()): $myposts->the_post();   
    ?> 
        <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xxl-8a ajax-url">
        <?php
            include( get_theme_file_path('/templates/card-book.php') ); 
            ?>
        </div>
    <?php  endwhile; endif; wp_reset_postdata();  
    
    die();
}

// 加载hot列表
add_action( 'wp_ajax_load_hot_list' , 'load_hot_list_callback' );
add_action( 'wp_ajax_nopriv_load_hot_list' , 'load_hot_list_callback' );
function load_hot_list_callback(){
    echo(json_encode(array(
        'state' =>1,
        'data'=>all_topnew_list()
    )));
    die();
}

// 首页TAB模式ajax加载内容     
add_action( 'wp_ajax_load_home_tab' , 'load_home_tab_post' );
add_action( 'wp_ajax_nopriv_load_home_tab' , 'load_home_tab_post' );
function load_home_tab_post(){

    $meta_id   = sanitize_key($_POST['id']); 
    $taxonomy  = sanitize_text_field($_POST['taxonomy']);
    $post_id   = sanitize_key($_POST['post_id']); 

    $quantity = io_get_option('card_n'); 
    global $post, $queried_object_id;
    $queried_object_id = $post_id;
    $link = "";
    $site_n           = $quantity[get_type_name($taxonomy)];
    $category_count   = get_term_by( 'id', $meta_id, $taxonomy )->count;//get_category( (int)$meta_id )->count;
    $count            = $site_n;
    if($site_n == 0)  $count = min(get_option('posts_per_page'),$category_count);
    if($site_n >= 0 && $count < $category_count){
        $link = get_category_link( $meta_id );
        //$link = esc_url( get_term_link( $_mid, 'res_category' ) );
        if($queried_object_id)
            $link .= '?menu-id='.get_post_meta( $queried_object_id, 'nav-id', true ).'&mininav-id='.$queried_object_id;
    }
    show_card($site_n,$meta_id,$taxonomy,'ajax-url');
    if($link != "") {?>
        <div id="ajax-cat-url" data-url="<?php echo $link ?>"></div>
    <?php } 
    die();
}
// 网址管理ajax加载站点内容     
add_action( 'wp_ajax_load_sites_manager' , 'load_sites_manager_post' );
add_action( 'wp_ajax_nopriv_load_sites_manager' , 'load_sites_manager_post' );
function load_sites_manager_post(){
    if(!is_user_logged_in())
        error('{"status":3,"msg":"请登录！"}');
    $term_id   = sanitize_key($_POST['term_id']); 

    global $post;
    $args = array(   
        'post_type'           => 'sites',
        //'ignore_sticky_posts' => 1,              
        'posts_per_page'      => -1,    
        'post_status'         => array( 'publish' ),
        'orderby'             => 'menu_order',
        'order'               => 'ASC',
        'tax_query'           => array(
            array(
                'taxonomy' => 'favorites',       
                'field'    => 'id',            
                'terms'    => $term_id,    
            )
        ),
    );
    $myposts = new WP_Query( $args );
    if ($myposts->have_posts()): while ($myposts->have_posts()): $myposts->the_post(); 
    if(get_post_meta($post->ID, '_sites_type', true)=='sites'):
        $isAdd = in_array(get_current_user_id(), array_unique(get_post_meta($post->ID, 'io_post_add_custom_users',false)));
    ?>
    <div id="url-<?php echo $post->ID ?>" class="col-12 col-md-4 col-lg-3 mb-2 sites-li admin-li" data-sites_id="<?php echo $post->ID ?>" title="<?php the_title() ?>">
        <div class="rounded sites-card position-relative">
            <div class="d-flex align-items-center">
                <div class=" rounded-circle mr-2 d-flex align-items-center justify-content-center">
                    <i class="iconfont icon-globe"></i>
                </div>
                <div class="flex-fill overflow-hidden">
                    <span class="sites-name overflowClip_1"><?php the_title() ?></span>
                    <span class="sites-name overflowClip_1 text-xs text-muted"><?php echo get_post_meta($post->ID, '_sites_sescribe', true)?:"..." ?></span>
                </div>
            </div>
            <div class="sites-setting">
                <a href="javascript:;" id="admin-sites-id-<?php echo $post->ID ?>" class="text-center add-admin-site <?php echo $isAdd?'add':'' ?>" data-action="add_custom_url" data-_wpnonce="<?=wp_create_nonce('add_custom_site_form') ?>" data-post_id="<?=$post->ID ?>" data-url_name="<?php the_title() ?>" data-url="<?php echo get_post_meta($post->ID, '_sites_link', true) ?>" data-url_summary="<?php echo get_post_meta($post->ID, '_sites_sescribe', true)?:"" ?>" data-url_ico="<?php echo get_post_meta($post->ID, '_thumbnail', true)?:"" ?>" style="" title="<?php echo $isAdd?__('已添加','i_theme'):__('添加','i_theme') ?>"><i class="iconfont <?php echo $isAdd?'text-danger icon-subtract':'icon-add' ?>"></i></a>
            </div>
        </div>
    </div>
    <?php
    endif; endwhile; endif;
    wp_reset_postdata();
    die();
}
// 加载随机网址
add_action( 'wp_ajax_load_random_sites' , 'load_random_sites_callback' );
add_action( 'wp_ajax_nopriv_load_random_sites' , 'load_random_sites_callback' );
function load_random_sites_callback(){
    $instance = $_POST['data'];
    if (!is_array($instance) || count($instance) < 1)
        error('{"status":3,"msg":"数据错误！"}'); 

    $i          = 0;
    $html       = '';
    $post_num   = $instance['number'];
    if($instance['post_id']!=''){
        $post_id        = $instance['post_id'];
        $post_type      = 'sites';
        $taxonomy_tag   = 'sitetag';
        $taxonomy_cat   = 'favorites';
        $exclude        = array ($instance['post_id']);
        $posttags = get_the_terms($post_id, $taxonomy_tag);  
        if($posttags){
            $tags = []; foreach ( $posttags as $tag ) $tags[]= $tag->term_id ;
            $args = array(
                'post_type'         => $post_type, 
                'post_status'       => 'publish',
                'posts_per_page'    => $post_num, 
                'orderby'           => 'rand', 
                'tax_query'         => array(
                    array(
                        'taxonomy'  => $taxonomy_tag, 
                        'field'     => 'id',
                        'terms'     => $tags
                    )
                ),
                'post__not_in'      => $exclude, 
            );
            $myposts = new WP_Query( $args ); 
            if($myposts->have_posts()){
                $data = load_widgets_min_sites_html($myposts,$instance,$i);
                $html .= $data['html'];
                $i    = $data['index'];
            }
            wp_reset_postdata();
        }
        if($i < $post_num){
            $custom_taxterms = get_the_terms( $post_id,$taxonomy_cat);
            if(is_array($custom_taxterms)){
                $terms = []; 
                foreach ( $custom_taxterms as $term ) $terms[]= $term->term_id ;
                $args = array(
                    'post_type'         => $post_type, 
                    'post_status'       => 'publish',
                    'posts_per_page'    => $post_num-$i, 
                    'orderby'           => 'rand', 
                    'tax_query'         => array(
                        array(
                            'taxonomy'  => $taxonomy_cat, 
                            'field'     => 'id',
                            'terms'     => $terms
                        )
                    ),
                    'post__not_in'      => $exclude, 
                );
                $myposts = new WP_Query( $args ); 
                if($myposts->have_posts()){
                    $data = load_widgets_min_sites_html($myposts,$instance,$i);
                    $html .= $data['html'];
                    $i    = $data['index'];
                }
                wp_reset_postdata();
            }
        }
    }
    if($i < $post_num){
        $p_args = array(
            'post_type'           => 'sites', 
            'post_status'         => array( 'publish', 'private' ),//'publish',
            'perm'                => 'readable',
            'ignore_sticky_posts' => 1,              
            'posts_per_page'      => $post_num-$i,    
            'orderby'             => 'rand',          
        ); 
        $myposts = new WP_Query( $p_args );
        $data = load_widgets_min_sites_html($myposts,$instance,$i);
        $html .= $data['html'];
        wp_reset_postdata();
    }
    exit($html);
}
function load_widgets_min_sites_html($myposts,$instance,$index=0){
    $newWindow = '';
    if ($instance['window']) $newWindow = "target='_blank'";
    $temp = '';  
    if(!$myposts->have_posts()): 
        $temp .= '<div class="col-lg-12"><div class="nothing mb-4">'.__('没有数据！','i_theme').'</div></div>';
    elseif ($myposts->have_posts()): 
        while ($myposts->have_posts()): $myposts->the_post();  
            $sites_type     = get_post_meta(get_the_ID(), '_sites_type', true);
            $link_url       = get_post_meta(get_the_ID(), '_sites_link', true); 
            $default_ico    = get_theme_file_uri('/images/favicon.png');
            $ico            = get_post_meta_img(get_the_ID(), '_thumbnail', true);
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
            $url        = get_permalink();
            $nofollow   = '';
            $is_views   = '';
            if(($instance['go']&&$sites_type == "sites"&&$link_url != '') || ($sites_type == "sites"&&$link_url != ''&&!io_get_option('details_page'))){
                $url        = $instance['nofollow'] ? $link_url : go_to($link_url);
                $nofollow   = $instance['nofollow'] ? '' : nofollow($link_url);
                $is_views   = 'is-views ';
            }
            $temp .= '<div class="url-card col-6 '. before_class(get_the_ID()) .' my-1">';
            $temp .= '<a href="'.$url.'" '.$newWindow.' '.$nofollow.' class="'.$is_views.'card post-min m-0" data-url="'.($link_url?:get_permalink()).'" data-id="'.get_the_ID().'">';
            $temp .= '<div class="card-body" style="padding:0.3rem 0.5rem;"><div class="url-content d-flex align-items-center"><div class="url-img rounded-circle">';
            if(io_get_option('lazyload')):
                $temp .= '<img class="lazy" src="'.$default_ico.'" data-src="'.$ico.'" onerror="javascript:this.src=\''.$default_ico.'\'">';
            else:
                $temp .= '<img src="'.$ico.'" onerror="javascript:this.src='.$default_ico.'">';
            endif;
            $temp .= '</div><div class="url-info pl-1 flex-fill"><div class="text-xs overflowClip_1">'.get_the_title().'</div></div></div></div>';
            $temp .= '</a></div>';
            $index++; 
        endwhile; 
    endif;
    return array(
        'html'  =>$temp,
        'index' =>$index,
    );
}
// load_home_customize_tab  
add_action( 'wp_ajax_load_home_customize_tab' , 'load_home_customize_tab' );
function load_home_customize_tab(){
    if(!is_user_logged_in())
        error('{"status":3,"msg":"'.__('请登录！','i_theme').'"}');

    $user_id   = isset($_POST['user_id'])?sanitize_key($_POST['user_id']):get_current_user_id(); 
    $term_id   = sanitize_key($_POST['term_id']); 

    global $iodb;
    $i_u = 0;
    $c_urls = $iodb->getUrlWhereTerm($user_id,$term_id);
    if($c_urls){ 
        $default_ico = get_theme_file_uri('/images/favicon.png');
        foreach($c_urls as $c_url){ 
            $ico = $c_url->url_ico?:(io_get_option('ico-source')['ico_url'] .format_url($c_url->url) . io_get_option('ico-source')['ico_png']);
        ?> 
        <div id="url-<?php echo $c_url->id ?>" class="url-card sortable col-6 <?php get_columns() ?> col-xxl-10a">
            <div class="url-body mini">
                <a href="<?php echo go_to($c_url->url) ?>" target="_blank" class="card new-site mb-3 site-<?php echo $c_url->id ?>" data-id="<?php echo $c_url->id ?>" data-url="<?php echo $c_url->url ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $c_url->url_name ?>" <?php echo  nofollow($c_url->url,false,true) ?>>
                    <div class="card-body" style="padding:0.4rem 0.5rem;">
                        <div class="url-content d-flex align-items-center">
                            <div class="url-img rounded-circle mr-2 d-flex align-items-center justify-content-center">
                                <?php if(io_get_option('lazyload')): ?>
                                <img class="lazy" src="<?php echo $default_ico; ?>" data-src="<?php echo $ico ?>" onerror="javascript:this.src='<?php echo $default_ico; ?>'" alt="<?php echo $c_url->url_name ?>">
                                <?php else: ?>
                                <img class="" src="<?php echo $ico ?>" onerror="javascript:this.src='<?php echo $default_ico; ?>'" alt="<?php echo $c_url->url_name ?>">
                                <?php endif ?>
                            </div>
                            <div class="url-info flex-fill">
                                <div class="text-sm overflowClip_1">
                                    <strong><?php echo $c_url->url_name ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <a href="javascript:;" class="text-center remove-cm-site" data-action="delete_custom_url" data-id="<?php echo $c_url->id ?>" data-name="<?php echo $c_url->url_name ?>" style="display: none"><i class="iconfont icon-close-circle"></i></a>
        </div> 
        <?php } ?>
        <div class="url-card col-6 <?php get_columns() ?> col-xxl-10a add-custom-site" data-term_id="<?php echo $term_id ?>" style="display: none">
            <a class="btn p-0 rounded mb-3" data-toggle="modal" data-target="#addSite" style="background: rgba(136, 136, 136, 0.1);width: 100%;text-align: center;border: 2px dashed rgba(136, 136, 136, 0.5);">
                <div class="text-lg"  style="padding:0.22rem 0.5rem;">
                    +
                </div>
            </a>
        </div> 
    <?php 
    }else{ ?>
        <div class="col-lg-12 customize_nothing">
            <div class="nothing mb-4"><?php _e('没有数据！点右上角编辑添加网址', 'i_theme' ); ?></div>
        </div>
        <div class="url-card col-6 <?php get_columns() ?> col-xxl-10a add-custom-site" data-term_id="<?php echo $term_id ?>" style="display: none">
            <a class="btn p-0 rounded mb-3" data-toggle="modal" data-target="#addSite" style="background: rgba(136, 136, 136, 0.1);width: 100%;text-align: center;border: 2px dashed rgba(136, 136, 136, 0.5);">
                <div class="text-lg"  style="padding:0.22rem 0.5rem;">
                    +
                </div>
            </a>
        </div> 
    <?php }
    die();
} 
add_action('wp_ajax_add_custom_url', 'add_custom_url_callback'); 
function add_custom_url_callback(){ 
    if (!wp_verify_nonce($_POST['_wpnonce'],"add_custom_site_form")){
        error('{"status":4,"msg":"'.__('对不起!您没有通过安全检查','i_theme').'"}');
    }
    if(!is_user_logged_in())
        error('{"status":3,"msg":"'.__('请登录！','i_theme').'"}');
    $user_id        = isset($_POST['user_id'])?sanitize_key($_POST['user_id']):get_current_user_id(); 
    $term_id        = isset($_POST['term_id'])?sanitize_key($_POST['term_id']):-1; 
    $term_name      = isset($_POST['term_name'])?trim(chack_name($_POST['term_name'])):''; 
    $url            = trim(esc_url_raw($_POST['url'])); 
    $url_name       = trim(esc_attr($_POST['url_name'])); 
    $summary        = isset($_POST['url_summary'])?trim(esc_attr($_POST['url_summary'])):'';
    $post_id        = isset($_POST['post_id'])?sanitize_key($_POST['post_id']):''; 
    $url_ico        = isset($_POST['url_ico'])?trim(htmlspecialchars($_POST['url_ico'])):'';

    if ($term_id == -1 &&  $term_name=='' )
        error('{"status":2,"msg":"'.__('内容错误！','i_theme').'"}'); 
    if ($url == '' ||  $url_name=='')
        error('{"status":4,"msg":"'.__('网址内容不能为空！','i_theme').'"}');

    global $iodb,$wpdb;
    date_default_timezone_set('Asia/Shanghai');
    if($term_id == -1 &&  $term_name!=""){
        if($iodb->term_exists($user_id,$term_name))
            error('{"status":3,"msg":"'.__('分类名称已经存在，不能再新建！','i_theme').'"}'); 
        $order = $iodb->getTermOrder($user_id)->order+1; 
        $term_id = $iodb->addTerm($user_id,$term_name,$order,true);
    }
    if($term_id == -1)
        error('{"status":3,"msg":"'.__('内容错误！','i_theme').'"}'); 

    if($iodb->url_exists($user_id,$term_id,$url)) 
        error('{"status":3,"msg":"'.__('当前分类下已经存在同样的 URL 地址！','i_theme').'"}'); 

    if($iodb->urlname_exists($user_id,$term_id,$url_name)) 
        error('{"status":3,"msg":"'.__('当前分类下已经存在同样名称的网址！','i_theme').'"}'); 

    $order = $iodb->getUrlTermOrder($user_id,$term_id)->order+1 ;
    if($iodb->addUrl($user_id,$url,$url_name,$term_id,$order,$summary,$url_ico,$post_id)){
        $url_id = $wpdb->insert_id;
        if($post_id!=''){
            add_post_meta($post_id, 'io_post_add_custom_users', $user_id);
        }
        error('{"status":1,"id":'.$url_id.',"msg":"'.__('添加成功！','i_theme').'"}');
    }
    else
        error('{"status":4,"msg":"'.__('添加失败！','i_theme').'"}');
}

add_action('wp_ajax_add_custom_urls', 'add_custom_urls_callback'); 
function add_custom_urls_callback(){
    if(!is_user_logged_in())
        error('{"status":3,"msg":"'.__('请登录！','i_theme').'"}');
    $user_id        = isset($_POST['user_id'])? sanitize_key($_POST['user_id']):get_current_user_id(); 
    $term_id        = isset($_POST['term_id'])? sanitize_key($_POST['term_id']):-1; 
    $term_name      = trim(chack_name($_POST['term_name']));   

    $urlDatas = json_decode(base64_decode($_POST['urls']),true); 
    if (!is_array($urlDatas) || count($urlDatas)<1)
        error('{"status":2,"msg":"'.__('内容错误！','i_theme').'"}'); 
    global $iodb;
    if($term_id == -1){
        if($iodb->term_exists($user_id,$term_name))
            error('{"status":3,"msg":"'.__('分类名称已经存在，不能再新建！','i_theme').'"}'); 
        $order = $iodb->getTermOrder($user_id)->order+1; 
        $term_id = $iodb->addTerm($user_id,$term_name,$order,true);
    }
    if($term_id == -1)
        error('{"status":3,"msg":"'.__('内容错误！','i_theme').'"}'); 
        
    date_default_timezone_set('Asia/Shanghai');
	$date = date('Y-m-d H:i:s');
    $arr_key = array('user_id','url','url_name','term_id','date');
    $data_urls=[]; 
    $url_i = 0;
    foreach($urlDatas as $urlData){ 
        $url = array( $user_id, esc_url_raw($urlData["url"]), esc_attr($urlData["name"]), $term_id, $date );
        $data_urls[] = $url;
        $url_i++;
    }
    if(count($data_urls)!=0){
        $iodb->addUrls($user_id,$data_urls,$arr_key);
        error('{"status":1,"msg":"'.sprintf(__('成功添加 %s 个网址。','i_theme'), $url_i).'"}');
    }else{
        error('{"status":4,"msg":"'.__('添加失败！','i_theme').'"}');
    }
}

//删除自定义网址
add_action('wp_ajax_delete_custom_url', 'delete_custom_url_callback'); 
function delete_custom_url_callback(){
    if(!is_user_logged_in())
        error('{"status":3,"msg":"'.__('请登录！','i_theme').'"}');
    $user_id        = isset($_POST['user_id'])? sanitize_key($_POST['user_id']):get_current_user_id(); 
    $id             = isset($_POST['id'])? sanitize_key($_POST['id']):-1; 
    $name           = isset($_POST['name'])? trim(esc_attr($_POST['name'])):'';
    if($id<=0){
        error('{"status":2,"msg":"'.__('数据错误！','i_theme').'"}');
    }
    global $iodb;  
    $res = $iodb->getUrlWhereID($user_id, $id);
    if($res && $res->post_id>0){
        delete_post_meta($res->post_id, 'io_post_add_custom_users', $user_id);
    }
    if($iodb->deleteUrl($user_id, $id))
        error('{"status":1,"msg":"'.__('删除成功！','i_theme').'"}');
    else
        error('{"status":4,"msg":"'.__('操作失败，稍后再试！','i_theme').'"}');
}

//搜索自定义网址
add_action('wp_ajax_nopriv_search_custom_url', 'search_custom_url_callback');  
add_action('wp_ajax_search_custom_url', 'search_custom_url_callback'); 
function search_custom_url_callback(){
    if(!is_user_logged_in())
        error('{"status":3,"msg":"'.__('请登录！','i_theme').'"}');
    $user_id        = isset($_POST['user_id'])? sanitize_key($_POST['user_id']):get_current_user_id(); 
    $key_word       = isset($_POST['key_word'])? trim(esc_attr($_POST['key_word'])):'';
    if($key_word==''){
        error('{"status":2,"msg":"'.__('数据错误！','i_theme').'"}');
    }
    global $iodb;  
    $res = $iodb->getUrlByKeyWord($user_id, $key_word,0,10);
    if($res){
        error(($res));
    }
    else
        error('{"status":4,"msg":"'.__('操作失败，稍后再试！','i_theme').'"}');
}
//edit_custom_url 
add_action('wp_ajax_edit_custom_url', 'edit_custom_url_callback'); 
function edit_custom_url_callback(){
    if(!is_user_logged_in())
        error('{"status":3,"msg":"'.__('请登录！','i_theme').'"}');
    $user_id        = isset($_POST['user_id'])? sanitize_key($_POST['user_id']):get_current_user_id(); 
    $id             = isset($_POST['url_id'])? sanitize_key($_POST['url_id']):-1; 
    $term_id        = isset($_POST['term_id'])? sanitize_key($_POST['term_id']):-1; 
    $name           = isset($_POST['url_name'])? trim(esc_attr($_POST['url_name'])):'';
    $summary        = isset($_POST['url_summary'])? trim(esc_attr($_POST['url_summary'])):'';
    $url            = isset($_POST['url'])? trim(esc_url_raw($_POST['url'])):'';
    if($name == '' || $url == '' || $id <0 || $term_id <0){
        error('{"status":2,"msg":"'.__('数据错误！','i_theme').'"}');
    }
    global $iodb; 
    $results = $iodb->getUrlWhereID($user_id,$id);
    if(!$results){
        error('{"status":3,"msg":"'.__('数据错误！','i_theme').'"}');
    }else if( $results->url_name == $name && $results->url == $url && $results->summary == $summary ){
        error('{"status":4,"msg":"'.__('网址没有变化！','i_theme').'"}');
    }
    
    if($results->url != $url && $iodb->url_exists($user_id,$term_id,$url)) 
        error('{"status":3,"msg":"'.__('当前分类下已经存在同样的 URL 地址！','i_theme').'"}'); 

    if($results->url_name != $name && $iodb->urlname_exists($user_id,$term_id,$name)) 
        error('{"status":3,"msg":"'.__('当前分类下已经存在同样名称的网址！','i_theme').'"}'); 

    if($iodb->updateUrl($user_id, $id, $url, $name, $summary))
        error('{"status":1,"msg":"'.__('修改成功！','i_theme').'"}');
    else
        error('{"status":4,"msg":"'.__('操作失败，稍后再试！','i_theme').'"}');
}
//upload_bookmark 
add_action('wp_ajax_upload_bookmark', 'upload_bookmark_callback'); 
function upload_bookmark_callback(){
    if (!wp_verify_nonce($_POST['ubnonce'],"upload_bookmark_cb")){
        error('{"status":4,"msg":"'.__('对不起!您没有通过安全检查','i_theme').'"}');
    }
    if(!is_user_logged_in())
        error('{"status":3,"msg":"'.__('请登录！','i_theme').'"}');
    $user_id        = isset($_POST['user_id'])? sanitize_key($_POST['user_id']):get_current_user_id(); 
    $folders = json_decode(base64_decode($_POST['bookmark']),true);
    
    date_default_timezone_set('Asia/Shanghai');
	$date = date('Y-m-d H:i:s');
    $i = 0;
    $data_urls=[]; 

    if($folders=="")
        error('{"status":4,"msg":"'.__('数据错误！','i_theme').'"}');
    
    global $iodb;
    $order = $iodb->getTermOrder($user_id)->order+1; 
    $arr_key = array('user_id','term_id','url','url_name','summary','date');
    $items_i = 0;
    $url_i = 0;
	foreach($folders['folders'] as $folder)
    {
        if(count($folder['items'])!=0){
            $term_id = $iodb->addTerm($user_id,trim(chack_name($folder['title'])),$order,true);
            foreach($folder['items'] as $link){
                $intex = iostrpos( $link['title'], array('-','_','|',',','－','–') );
                $_title = trim($link['title']);
                $summary = $_title;
                if( $intex > 0 ){
                    $_title = trim(substr($_title, 0, $intex));
                }
                $url = array( $user_id,$term_id,esc_url_raw($link['href']),esc_attr($_title),esc_attr($summary),$date );
                $data_urls[] = $url;
                $url_i++;
            }
            if($i>10){
                $i=0;
                $iodb->addUrls($user_id,$data_urls,$arr_key);
                $data_urls=[]; 
            }else{
                $i++;
            }
            $order++;
            $items_i++;
        }
    }
    if(count($data_urls)!=0)
        $iodb->addUrls($user_id,$data_urls,$arr_key);
    if($items_i > 0)
        error('{"status":1,"msg":"'.sprintf(__('成功添加 %s 个分类，%s 个网址。','i_theme'),$items_i,$url_i).'"}');
    else
        error('{"status":4,"msg":"'.__('添加失败！','i_theme').'"}');
}
//add_custom_terms 
add_action('wp_ajax_add_custom_terms', 'add_custom_terms_callback'); 
function add_custom_terms_callback(){
    if(!is_user_logged_in())
        error('{"status":3,"msg":"'.__('请登录！','i_theme').'"}');
    $user_id        = isset($_POST['user_id'])? sanitize_key($_POST['user_id']):get_current_user_id(); 
    $name           = isset($_POST['name'])? trim(chack_name($_POST['name'])):'';
    if($name == ''){
        error('{"status":2,"msg":"'.__('数据错误！','i_theme').'"}');
    }
    global $iodb; 
    if($iodb->term_exists($user_id,$name))
        error('{"status":3,"msg":"'.__('分类名称已经存在，不能再新建！','i_theme').'"}'); 
    $order = $iodb->getTermOrder($user_id)->order+1; 
    if($iodb->addTerm($user_id, $name, $order))
        error('{"status":1,"msg":"'.__('添加成功！','i_theme').'"}');
    else
        error('{"status":4,"msg":"'.__('操作失败，稍后再试！','i_theme').'"}');
}
//edit_custom_terms 
add_action('wp_ajax_edit_custom_terms', 'edit_custom_terms_callback'); 
function edit_custom_terms_callback(){
    if(!is_user_logged_in())
        error('{"status":3,"msg":"'.__('请登录！','i_theme').'"}');
    $user_id        = isset($_POST['user_id'])? sanitize_key($_POST['user_id']):get_current_user_id(); 
    $id             = isset($_POST['id'])? sanitize_key($_POST['id']):-1; 
    $name           = isset($_POST['name'])? trim(chack_name($_POST['name'])):'';
    $old_name       = isset($_POST['old_name'])? trim(chack_name($_POST['old_name'])):'';
    if($name == '' || $old_name == '' || $id < 0 ){
        error('{"status":2,"msg":"'.__('数据错误！','i_theme').'"}');
    }
    global $iodb;  
    if($iodb->term_exists($user_id,$name))
        error('{"status":3,"msg":"'.__('分类名称已经存在！','i_theme').'"}'); 
    if($iodb->updateTerm($user_id,$id,'',$name,''))
        error('{"status":1,"msg":"'.__('修改成功！','i_theme').'"}');
    else
        error('{"status":4,"msg":"'.__('操作失败，稍后再试！','i_theme').'"}');
}
//delete_custom_terms 
add_action('wp_ajax_delete_custom_terms', 'delete_custom_terms_callback'); 
function delete_custom_terms_callback(){
    if(!is_user_logged_in())
        error('{"status":3,"msg":"'.__('请登录！','i_theme').'"}');
    $user_id        = isset($_POST['user_id'])? sanitize_key($_POST['user_id']):get_current_user_id(); 
    $id             = isset($_POST['id'])? sanitize_key($_POST['id']):-1; 
    $name           = isset($_POST['name'])? trim(chack_name($_POST['name'])):'';
    $clean          = isset($_POST['clean'])? sanitize_key($_POST['clean']):0;
    if($id<=0 || $name==''){
        error('{"status":2,"msg":"'.__('数据错误！','i_theme').'"}');
    }
    global $iodb;
    if($urls = $iodb->getUrlWhereTerm($user_id,$id)){
        if($clean){
            $data_urls = [];
            foreach($urls as $url){
                $data_urls[] = $url->id;
            }
            $iodb->deleteUrls($user_id,$data_urls,'id');
        }else{
            error('{"status":4,"msg":"'.__('此分类内包含网址，无法删除！','i_theme').'"}');
        }
    }
    if($iodb->deleteTerm($user_id, $id))
        error('{"status":1,"msg":"'.__('删除成功！','i_theme').'"}');
    else
        error('{"status":4,"msg":"'.__('操作失败，稍后再试！','i_theme').'"}');
}
//move_sites_to_terms 
add_action('wp_ajax_sites_to_terms', 'sites_to_terms_callback'); 
function sites_to_terms_callback(){
    if(!is_user_logged_in())
        error('{"status":3,"msg":"'.__('请登录！','i_theme').'"}');
    $user_id        = isset($_POST['user_id'])? sanitize_key($_POST['user_id']):get_current_user_id(); 
    $id             = isset($_POST['sites_id'])? sanitize_key($_POST['sites_id']):-1; 
    $terms_id           = isset($_POST['terms_id'])? sanitize_key($_POST['terms_id']):-1;
    if($id<=0 || $terms_id<=0){
        error('{"status":2,"msg":"'.__('数据错误！','i_theme').'"}');
    }
    
    //error('{"status":2,"msg":"'.$user_id.'删除成功！'.$id.'"}');
    global $iodb,$wpdb; 
    
    $results = $iodb->getUrlWhereID($user_id,$id);
    if(!$results){
        error('{"status":3,"msg":"'.__('数据错误！','i_theme').'"}');
    }
    if($iodb->setUrlTerm($user_id, $id, $terms_id))
        error('{"status":1,"msg":"'.__('移动成功','i_theme').'"}');
    else
        error('{"status":4,"msg":"'.__('操作失败，稍后再试！','i_theme').'"}');
}

//update_custom_terms_order 
add_action('wp_ajax_update_custom_terms_order', 'update_custom_terms_order_callback'); 
function update_custom_terms_order_callback(){
    if(!is_user_logged_in())
        error('{"status":3,"msg":"'.__('请登录！','i_theme').'"}');
    $user_id        = isset($_POST['user_id'])? sanitize_key($_POST['user_id']):get_current_user_id();   

    if($user_id<=0){
        error('{"status":2,"msg":"'.__('数据错误！','i_theme').'"}');
    }
    parse_str($_POST['order'], $data);
                    
    if (!is_array($data)    ||  count($data)    <   1)
        error('{"status":3,"msg":"'.__('数据错误！','i_theme').'"}'); 

    global $iodb,$wpdb; 
    $results = $iodb->getTerm($user_id);

    if (!is_array($results)    ||  count($results)    <   1)
        error('{"status":4,"msg":"'.__('数据错误！','i_theme').'"}'); 
    //创建ID列表
    $objects_ids    =   array();
    foreach($results    as  $result)
    {
        $objects_ids[]  =   (int)$result->id;   
    }
    $index = 0;
    for($i = 0; $i  < count($results); $i++){
        if(!isset($objects_ids[$i]))
            break;
            
        $objects_ids[$i]    =   (int)$data['termsli'][$index];//替换列表id为排序id
        $index++;
    }

    //更新数据库中的菜单顺序
    foreach( $objects_ids as $order   =>  $id ) 
    {
        $data = array(
            'order' => $order
        );
        $wpdb->update( $wpdb->iocustomterm, $data, array('id' => $id) );
    } 
    error('{"status":1,"msg":"'.__('排序成功！','i_theme').'"}');   
}


//update_custom_url_order 
add_action('wp_ajax_update_custom_url_order', 'update_custom_url_order_callback'); 
function update_custom_url_order_callback(){
    if(!is_user_logged_in())
        error('{"status":3,"msg":"'.__('请登录！','i_theme').'"}');
    $user_id        = isset($_POST['user_id'])? sanitize_key($_POST['user_id']):get_current_user_id(); 
    $term_id        = isset($_POST['term_id'])? sanitize_key($_POST['term_id']):get_current_user_id();  

    if($term_id<=0){
        error('{"status":2,"msg":"'.__('数据错误！','i_theme').'"}');
    }
    parse_str($_POST['order'], $data);
                    
    if (!is_array($data)    ||  count($data)    <   1)
        error('{"status":3,"msg":"'.__('数据错误！','i_theme').'"}'); 

    global $iodb,$wpdb; 
    $results = $iodb->getUrlWhereTerm($user_id,$term_id);

    if (!is_array($results)    ||  count($results)    <   1)
        error('{"status":4,"msg":"'.__('数据错误！','i_theme').'"}'); 
    //创建ID列表
    $objects_ids    =   array();
    foreach($results    as  $result)
    {
        $objects_ids[]  =   (int)$result->id;   
    }
    $index = 0;
    for($i = 0; $i  < count($results); $i++){
        if(!isset($objects_ids[$i]))
            break;
            
        $objects_ids[$i]    =   (int)$data['url'][$index];//替换列表id为排序id
        $index++;
    }

    //更新数据库中的菜单顺序
    foreach( $objects_ids as $order   =>  $id ) 
    {
        $data = array(
            'order' => $order
        );
        $wpdb->update( $wpdb->iocustomurl, $data, array('id' => $id) ); 
    } 
    error('{"status":1,"msg":"'.__('排序成功！','i_theme').'"}');   
}


//update_sites_order
add_action('wp_ajax_update_sites_order', 'update_sites_order_callback');  
function update_sites_order_callback()
{
    set_time_limit(600);
    
    global $wpdb, $userdata;
    
    $post_type  =   filter_var ( $_POST['post_type'], FILTER_SANITIZE_STRING);
    $term_id    =   filter_var ( $_POST['term_id'], FILTER_SANITIZE_STRING);
    $paged      =   filter_var ( $_POST['paged'], FILTER_SANITIZE_NUMBER_INT);
    $nonce      =   $_POST['_nonce'];
    
    //安全验证
    if (! wp_verify_nonce( $nonce, 'sortable_nonce_' . $userdata->ID ) )
        die();
    if(!is_numeric($term_id)){
        $term_id = get_term_by( 'slug', $term_id, 'favorites')->term_id;
    }
    if(!is_numeric($term_id) || $term_id==0){
        error('{"status":2,"msg":"'.__('请先筛选到分类再排序！','i_theme').'"}'); 
    }
    parse_str($_POST['order'], $data);
    
    if (!is_array($data)    ||  count($data)    <   1)
        error('{"status":3,"msg":"'.__('数据错误！','i_theme').'"}'); 
    
    //检索所有对象的列表
    $mysql_query    =   $wpdb->prepare("SELECT ID FROM $wpdb->posts 
                                            WHERE post_type = %s and post_status IN ('publish', 'pending', 'draft', 'private', 'future', 'inherit') 
                                            and ($wpdb->posts.ID IN (SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id IN (%d) ) ) 
                                            ORDER BY menu_order, post_date DESC", $post_type, $term_id);
    $results        =   $wpdb->get_results($mysql_query);
    
    if (!is_array($results)    ||  count($results)    <   1)
        error('{"status":4,"msg":"'.__('数据错误！','i_theme').'"}'); 
    
    //创建ID列表
    $objects_ids = array();
    foreach($results    as  $result)
    {
        $objects_ids[] = (int)$result->ID;   
    }
    $obj_index = min($objects_ids); //初始序号
    global $userdata;
    $objects_per_page   =   get_user_meta($userdata->ID ,'edit_' .  $post_type  .'_per_page', TRUE);//查询设置每页显示多少
    if(empty($objects_per_page))
        $objects_per_page   =   20;//默认20
    
    $edit_start_at      =   $paged  *   $objects_per_page   -   $objects_per_page;//获取开始id
    $index              =   0;
    for($i = $edit_start_at; $i < ($edit_start_at + $objects_per_page); $i++)
    {
        if(!isset($objects_ids[$i]))
            break;
        $objects_ids[$i]    =   (int)$data['post'][$index];//替换列表id为排序id
        $index++;
    }
    
    //更新数据库中的菜单顺序
    foreach( $objects_ids as $menu_order   =>  $id ) 
    {
        $data = array(
            'menu_order' => $menu_order+$obj_index
        );
        $wpdb->update( $wpdb->posts, $data, array('ID' => $id) );
        clean_post_cache( $id ); 
    }
    io_edit_post_delete_home_cache($term_id,'favorites');
    error('{"status":1,"msg":"排序成功！'.$objects_per_page.'"}');                
}

/**
 * 输出提示
 * @description: 
 * @param array|string $errMsg 1 success 2 info 3 warning 4 danger
 * @param bool $err
 * @param int $cache 缓存时间，分钟
 * @return null
 */
function error($errMsg, $err=false, $cache = 0) {
    if($err){
        header('HTTP/1.0 500 Internal Server Error');
    }
    header('Content-Type: text/json;charset=UTF-8');
    if($cache>0){
        header("Cache-Control: public"); 
        header("Pragma: cache"); 
        $offset = 60*$cache;  
        $ExpStr = "Expires: ".gmdate("D, d M Y H:i:s", time() + $offset)." GMT"; 
        header($ExpStr); 
    }
    if(is_array($errMsg))
        echo json_encode($errMsg);
    else
        echo $errMsg;
    exit;
} 

add_action( 'wp_ajax_io_007_validate', 'io_007_validate_callback');
add_action( 'wp_ajax_nopriv_io_007_validate', 'io_007_validate_callback');
function io_007_validate_callback(){ 
    //error(json_encode(array(
    //    'result'=>1,
    //    'message'  => ''
    //)));
    $Ticket = $_POST['ticket'];
    $Randstr = $_POST['randstr'];
    error(json_encode(validate_ticket($Ticket,$Randstr)));
}
/**
 * 请求服务器验证
 */
function validate_ticket($Ticket,$Randstr){
    $AppSecretKey = io_get_option('io_captcha')['appsecretkey_007'];  
    $appid = io_get_option('io_captcha')['appid_007'];  
    $UserIP = $_SERVER["REMOTE_ADDR"]; 

    $url = "https://ssl.captcha.qq.com/ticket/verify";
    $params = array(
        "aid" => $appid,
        "AppSecretKey" => $AppSecretKey,
        "Ticket" => $Ticket,
        "Randstr" => $Randstr,
        "UserIP" => $UserIP
    );
    $paramstring = http_build_query($params);
    $content = txcurl($url,$paramstring);
    $result = json_decode($content,true);
    if($result){
        if($result['response'] == 1){
            
            return array(
                'result'=>1,
                'message'  => ''
            );
        }else{
            return array(
                'result'=>0,
                'message'  => $result['err_msg']
            );
        }
    }else{
        return array(
            'result'=>0,
            'message'  => __('请求失败,请再试一次！','i_theme')
        );
    }
}

/**
 * 请求接口返回内容
 * @param  string $url [请求的URL地址]
 * @param  string $params [请求的参数]
 * @param  int $ipost [是否采用POST形式]
 * @return  string
*/
function txcurl($url,$params=false,$ispost=0){
    $httpInfo = array();
    $ch = curl_init();

    curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
    curl_setopt( $ch, CURLOPT_USERAGENT , 'ioTheme' );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
    curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true);
    if( $ispost )
    {
        curl_setopt( $ch , CURLOPT_POST , true );
        curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
        curl_setopt( $ch , CURLOPT_URL , $url );
    }
    else
    {
        if($params){
            curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
        }else{
            curl_setopt( $ch , CURLOPT_URL , $url);
        }
    }
    $response = curl_exec( $ch );
    if ($response === FALSE) {
        //echo "cURL Error: " . curl_error($ch);
        return false;
    }
    $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
    $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
    curl_close( $ch );
    return $response;
}
