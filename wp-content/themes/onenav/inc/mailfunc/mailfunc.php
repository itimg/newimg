<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Include file helper
function io_include_file( $file, $load = true ) {
    $path     = '';
    $file     = ltrim( $file, '/' );
    $dirname        = str_replace( '//', '/', wp_normalize_path( dirname( dirname( __FILE__ ) ) ) );
    $override = apply_filters( 'io_override', 'mailfunc' );
    if ( file_exists( get_parent_theme_file_path( $override .'/'. $file ) ) ) {
        $path = get_parent_theme_file_path( $override .'/'. $file );
    } elseif ( file_exists( get_theme_file_path( $override .'/'. $file ) ) ) {
        $path = get_theme_file_path( $override .'/'. $file );
    } elseif ( file_exists( $dirname .'/'. $override .'/'. $file ) ) {
        $path = $dirname .'/'. $override .'/'. $file;
    } elseif ( file_exists( $dirname .'/'. $file ) ) {
        $path = $dirname .'/'. $file;
    }
    if ( ! empty( $path ) && ! empty( $file ) && $load ) {
        global $wp_query;
        if ( is_object( $wp_query ) && function_exists( 'load_template' ) ) {
            load_template( $path, true );
        } else {
            require_once( $path );
        }
    } else {
        return $dirname .'/'. $file;
    }
}
// Plates模板引擎
//io_include_file('plates/extension/ExtensionInterface.php');
io_include_file('plates/template/Data.php');
io_include_file('plates/template/Directory.php');
io_include_file('plates/template/FileExtension.php');
io_include_file('plates/template/Folder.php');
io_include_file('plates/template/Folders.php');
io_include_file('plates/template/Func.php');
io_include_file('plates/template/Functions.php');
io_include_file('plates/template/Name.php');
io_include_file('plates/template/Template.php');
//io_include_file('plates/extension/Asset.php');
//io_include_file('plates/extension/URI.php');
io_include_file('plates/Engine.php');

/* 实例化异步任务类实现注册异步任务钩子 */
io_include_file('class.Async.Task.php');
io_include_file('class.Async.Email.php');
new AsyncEmail();

/**
 * 根据用户设置选择邮件发送方式
 */
function i_switch_mailer($phpmailer){
    $mailer = io_get_option('i_default_mailer');
    if($mailer === 'smtp'){
        //$phpmailer->Mailer = 'smtp';
        $phpmailer->Host = io_get_option('i_smtp_host');
        $phpmailer->SMTPAuth = true; // 强制它使用用户名和密码进行身份验证
        $phpmailer->Port = io_get_option('i_smtp_port');
        $phpmailer->Username = io_get_option('i_smtp_username');
        $phpmailer->Password = io_get_option('i_smtp_password');

        // Additional settings…
        $phpmailer->SMTPSecure = io_get_option('i_smtp_secure');
        $phpmailer->FromName = io_get_option('i_smtp_name');
        $phpmailer->From = $phpmailer->Username; // io_get_option('i_mail_custom_address'); // 多数SMTP提供商要求发信人与SMTP服务器匹配，自定义发件人地址可能无效
        $phpmailer->Sender = $phpmailer->From; //Return-Path--
        $phpmailer->AddReplyTo($phpmailer->From,$phpmailer->FromName); //Reply-To--
        $phpmailer->IsSMTP();
    }else{
        // when use php mail
        $phpmailer->FromName = io_get_option('i_mail_custom_sender');
        $phpmailer->From = io_get_option('i_mail_custom_address');
    }
}
add_action('phpmailer_init', 'i_switch_mailer');

/**
 * 发送邮件
 *
 * @since 2.0.0
 *
 * @param string    $from   发件人
 * @param string    $to     收件人
 * @param string    $title  主题
 * @param string|array    $args    渲染内容所需的变量对象
 * @param string    $template   模板，例如评论回复邮件模板、新用户、找回密码、订阅信等模板
 * @return  bool
 */
function io_mail($from, $to, $title = '', $args = array(), $template = 'comment') {
    //if(io_get_option('i_smtp_username')=='')return;
    $title = $title ? trim($title) : io_get_mail_title($template);
    $content = io_mail_render($args, $template);
    $blog_name = get_bloginfo('name');
    $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
    $sender_name = io_get_option('i_mail_custom_sender') || io_get_option('i_smtp_name', $blog_name);
    if(empty($from)){
        $from = io_get_option('i_mail_custom_address') . $wp_email; //TODO: case e.g subdomain.domain.com
    }
    $fr = "From: \"" . $sender_name . "\" <$from>";
    $headers = "$fr\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
    
    if(wp_mail( $to, $title, $content, $headers ))
        return true;
    else
        return false;
}
//add_action('io_async_send_mail', 'io_mail', 10, 5);

/**
 * 异步发送邮件
 *
 * @since 2.0.0
 * @param $from
 * @param $to
 * @param string $title
 * @param array $args
 * @param string $template
 */
function io_async_mail($from, $to, $title = '', $args = array(), $template = 'comment'){
    if(is_array($args)) {
        $args = base64_encode(json_encode($args));
    }
    do_action('send_mail', $from, $to, $title, $args, $template);
}


/**
 * 邮件内容的模板选择处理
 *
 * @since   2.0.0
 *
 * @param   string  $content    未处理的邮件内容或者内容必要参数数组
 * @param   string  $template   渲染模板选择(reset_pass|..)
 * @return  string
 */
function io_mail_render($content, $template = 'comment') {
    // 使用Plates模板渲染引擎
    $templates = new League\Plates\Engine(io_include_file('/mailfunc/plates/emails',false));
    if (is_string($content)) {
        return $templates->render('pure', array('content' => $content));
    } elseif (is_array($content)) {
        return $templates->render($template, $content); // TODO confirm template exist
    }
    return '';
}

/**
 * 不同模板的邮件标题
 *
 * @since   2.0.0
 *
 * @param   string  $template   邮件模板
 * @return  string
 */
function io_get_mail_title($template = 'comment') {
    $blog_name = get_bloginfo('name');
    switch ($template){
        case 'comment':
            return sprintf(__('新评论通知 - %s', 'i_theme'), $blog_name);
            break;
        case 'comment-admin':
            return sprintf(__('您的博客有新评论 - %s', 'i_theme'), $blog_name);
            break;
        case 'findpass':
            return sprintf(__('你的登录密码重置链接 - %s', 'i_theme'), $blog_name);
            break;
        case 'login':
            return sprintf(__('登录成功通知 - %s', 'i_theme'), $blog_name);
            break;
        case 'login-fail':
            return sprintf(__('登录失败通知 - %s', 'i_theme'), $blog_name);
            break;
        case 'reply':
            return sprintf(__('新评论回复通知 - %s', 'i_theme'), $blog_name);
            break;
        //TODO more
        default:
            return sprintf(__('站内通知 - %s', 'i_theme'), $blog_name);//站内通知
    }
}


/**
 * 评论回复邮件
 *
 * @since 2.0.0
 * @param $comment_id
 * @param $comment_object
 * @return void 
 */
function io_comment_mail_notify($comment_id, $comment_object) {
    date_default_timezone_set ('Asia/Shanghai');
    $admin_notify = '1'; // admin 要不要收回复通知 ( '1'=要 ; '0'=不要 )
    $admin_email = get_bloginfo ('admin_email'); // $admin_email 可改为你指定的 e-mail.
    $comment = get_comment($comment_id);
    $comment_author = trim($comment->comment_author);
    $comment_date = trim($comment->comment_date);
    $comment_link = htmlspecialchars(get_comment_link($comment_id));
    $comment_content = nl2br($comment->comment_content);
    $comment_author_email = trim($comment->comment_author_email);
    $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
    $parent_comment = !empty($parent_id) ? get_comment($parent_id) : null;
    $parent_email = $parent_comment ? trim($parent_comment->comment_author_email) : '';
    $post = get_post($comment_object->comment_post_ID);
    $post_author_email = get_user_by( 'id' , $post->post_author)->user_email;

    if( $comment_object->comment_approved != 1 ){
        $args = array(
            'postTitle' => $post->post_title,
            'commentAuthor' => $comment_author,
            'commentContent' => $comment_content,
            'commentLink' => $comment_link,
            'verify' => '1'
        );
        io_async_mail('', $admin_email, sprintf( __('%s上的文章有了新的回复', 'i_theme'), get_bloginfo('name') ), $args, 'comment-admin');
        return;
    }

    $notify = 1; // 默认全部提醒
    $spam_confirmed = $comment->comment_approved;
    //给父级评论提醒
    if ($parent_id != '' && $spam_confirmed != 'spam' && $notify == '1' && $parent_email != $comment_author_email) {
        $parent_author = trim($parent_comment->comment_author);
        $parent_comment_date = trim($parent_comment->comment_date);
        $parent_comment_content = nl2br($parent_comment->comment_content);
        $args = array(
            'parentAuthor' => $parent_author,
            'parentCommentDate' => $parent_comment_date,
            'parentCommentContent' => $parent_comment_content,
            'postTitle' => $post->post_title,
            'commentAuthor' => $comment_author,
            'commentDate' => $comment_date,
            'commentContent' => $comment_content,
            'commentLink' => $comment_link
        );
        if(filter_var( $parent_email, FILTER_VALIDATE_EMAIL)){
            io_async_mail('', $parent_email, sprintf( __('%1$s在%2$s中回复你', 'i_theme'), $comment_object->comment_author, $post->post_title ), $args, 'reply');
        }
        if ($parent_comment->user_id) {
            io_create_message($parent_comment->user_id, $comment->user_id, $comment_author, 'comment', sprintf(__('我在%1$s中回复了你', 'i_theme'), $post->post_title), $comment_content);
        }
        
    }

    //给文章作者的通知
    if($post_author_email != $comment_author_email && $post_author_email != $parent_email){
        $args = array(
            'postTitle' => $post->post_title,
            'commentAuthor' => $comment_author,
            'commentContent' => $comment_content,
            'commentLink' => $comment_link
        );
        if(filter_var( $post_author_email, FILTER_VALIDATE_EMAIL)){
            io_async_mail('', $post_author_email, sprintf( __('%1$s在%2$s中回复你', 'i_theme'), $comment_author, $post->post_title ), $args, 'comment');
        }
        io_create_message($post->post_author, 0, 'System', 'notification', sprintf(__('%1$s在%2$s中回复你', 'i_theme'), $comment_author, $post->post_title), $comment_content);
    }

    //给管理员通知
    if($post_author_email != $admin_email && $parent_id != '' && $admin_notify == '1'){
        $args = array(
            'postTitle' => $post->post_title,
            'commentAuthor' => $comment_author,
            'commentContent' => $comment_content,
            'commentLink' => $comment_link,
            'verify' => '0'
        );
        io_async_mail('', $admin_email, sprintf( __('%s上的文章有了新的回复', 'i_theme'), get_bloginfo('name') ), $args, 'comment-admin');
    
    }
}
//add_action('comment_post', 'io_comment_mail_notify');
add_action('wp_insert_comment', 'io_comment_mail_notify' , 99, 2 );


/**
 * WP登录提醒
 *
 * @since 2.0.0
 * @param string $user_login
 * @return void
 */
function io_wp_login_notify($user_login){
    date_default_timezone_set ('Asia/Shanghai');
    $admin_email = get_bloginfo ('admin_email');
    $subject = __('你的博客空间登录提醒', 'i_theme');
    $args = array(
        'loginName' => $user_login,
        'ip' => $_SERVER['REMOTE_ADDR']
    );
    io_async_mail('', $admin_email, $subject, $args, 'login');
    //io_mail('', $admin_email, $subject, $args, 'login');
}
//add_action('wp_login', 'io_wp_login_notify', 10, 1);

/**
 * WP登录错误提醒
 *
 * @since 2.0.0
 * @param string $login_name
 * @return void
 */
function io_wp_login_failure_notify($login_name){
    $admin_email = get_bloginfo ('admin_email');
    $subject = __('你的博客空间登录错误警告', 'i_theme');
    $args = array(
        'loginName' => $login_name,
        'ip' => $_SERVER['REMOTE_ADDR']
    );
    io_async_mail('', $admin_email, $subject, $args, 'login-fail');
}
//add_action('wp_login_failed', 'io_wp_login_failure_notify', 10, 1);


/**
 * 更改找回密码邮件中的内容
 *
 * @since 2.0.0
 * @param $message
 * @param $key
 * @return string
 */
function io_reset_password_message( $message, $key ) {
    io_ajax_is_robots();
    if ( strpos($_POST['user_login'], '@') ) {
        $user_data = get_user_by('email', trim($_POST['user_login']));
    } else {
        $login = trim($_POST['user_login']);
        $user_data = get_user_by('login', $login);
    }
    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;
    $reset_link = network_site_url('wp-login.php?action=rp&key=' . $key . '&login=' . rawurlencode($user_login), 'login') ;
 
    $args = array('home' => home_url(), 'userLogin' => $user_login, 'resetPassLink' => $reset_link);
    //return io_mail_render($args, 'findpass');
    io_mail('', $user_email, sprintf( __('你的登录密码重置链接-%1$s', 'i_theme'), get_bloginfo('name') ),$args , 'findpass');
}
add_filter('retrieve_password_message', 'io_reset_password_message', null, 2);
