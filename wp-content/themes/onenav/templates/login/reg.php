<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:56:00
 * @LastEditors: iowen
 * @LastEditTime: 2021-10-02 16:12:42
 * @FilePath: \onenav\templates\login\reg.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php
if( !empty($_POST['user_reg']) ) {
    $error = $user_error = $email_error = $code_error = $pass_error = $pass_error2 = '';
    $sanitized_user_login = sanitize_user( $_POST['user_login'] );
    $user_email = apply_filters( 'user_registration_email', $_POST['user_email'] );

      // 检查名称
    if ( $sanitized_user_login == '' ) {
        $user_error .= (__( '请输入用户名！', 'i_theme' ));
    } elseif ( strlen($sanitized_user_login) < 5 ) {
        $user_error .= (__( '用户名长度至少5位!', 'i_theme' ));
        $sanitized_user_login = '';
    } elseif ( ! validate_username( $sanitized_user_login ) || is_disable_username($sanitized_user_login) ) {
        $user_error .= (__( '此用户名包含无效字符，只能使用字母数字下划线！', 'i_theme' ));
        $sanitized_user_login = '';
    } elseif ( username_exists( $sanitized_user_login ) ) {
        $user_error .= (__( '该用户名已被注册，请再选择一个！', 'i_theme' ));
    }

      // 检查邮件
    if ( $user_email == '' ) {
        $email_error .= (__( '请填写电子邮件地址！', 'i_theme' ));
    } elseif ( ! is_email( $user_email ) ) {
        $email_error .= (__( '电子邮件地址不正确！', 'i_theme' ));
        $user_email = '';
    } elseif ( email_exists( $user_email ) ) {
        $email_error .= (__( '该电子邮件地址已经被注册，请换一个！', 'i_theme' ));
    }
    if(io_get_option('reg_verification')){
        if(!session_id()) session_start();
        // 验证邮箱验证码
        if(!isset($_SESSION['new_mail']) || $_SESSION['new_mail'] != $user_email)
            $email_error .= __('邮箱怎么变了！', 'i_theme');
        if(!$_POST['verification_code']){
            $code_error .= __('请输入验证码！', 'i_theme');
        }elseif(!isset($_SESSION['reg_mail_token']) || $_POST['verification_code'] != $_SESSION['reg_mail_token'] ){
            $code_error .= __('验证码不正确！', 'i_theme');
        }
        session_write_close();
    }
    // 检查密码
    if(strlen($_POST['user_pass']) < 6)
        $pass_error .= (__( '密码长度至少6位!', 'i_theme' ));
    elseif($_POST['user_pass'] != $_POST['user_pass2'])
        $pass_error2 .= (__( '密码不一致!', 'i_theme' ));

    if($user_error == '' && $email_error == '' && $code_error == '' && $pass_error == '' && $pass_error2 == '') {

        io_ajax_is_robots();
        $user_id = wp_create_user( $sanitized_user_login, $_POST['user_pass'], $user_email );
        if ( ! $user_id ) {
            $error .= sprintf( '无法完成您的注册请求... 请联系<a href="mailto:%s">管理员</a>！', get_option( 'admin_email' ) );
        } elseif (!is_user_logged_in()) {
            $user = get_user_by('id', $user_id);
            $user_id = $user->ID;

            update_user_meta($user_id, 'avatar_type', 'letter');

            if(io_get_option('reg_verification'))
                update_user_meta($user_id, 'email_status', 1);

            // 发送激活成功与注册欢迎信
            // io_async_mail('', get_option('admin_email'), sprintf(__('您的站点「%s」有新用户注册 :', 'i_theme'), get_bloginfo('name')), array('loginName' => $sanitized_user_login, 'email' => $user_email, 'ip' => $_SERVER['REMOTE_ADDR']), 'register-admin');

            // 自动登录 
            wp_set_current_user($user_id, $user->user_login);
            wp_set_auth_cookie($user_id, true);
            do_action('wp_login', $user->user_login, $user);
        }
    }
}
$redirect_to = esc_url(home_url());
if ( isset( $_REQUEST['redirect'] ) || isset( $_REQUEST['redirect_to'] ) ){
    $redirect_to = isset($_REQUEST['redirect']) ?  $_REQUEST['redirect'] : $_REQUEST['redirect_to']; 
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title><?php _e( '注册', 'i_theme' ); ?> - <?php bloginfo('name') ?></title>
<link rel="shortcut icon" href="<?php echo io_get_option('favicon') ?>">
<link rel="apple-touch-icon" href="<?php echo io_get_option('apple_icon') ?>">
<meta name='robots' content='noindex,nofollow' />
<?php do_action('login_head'); ?>
<?php $login_color = io_get_option('login_color'); ?>
<style type="text/css">
:root {
--bg-color-l: <?php echo $login_color['color-l'] ?>;
--bg-color-r: <?php echo $login_color['color-r'] ?>;
}
</style>
</head>
<body <?php body_class(theme_mode()); ?> >
<?php dark_mode_js() ?>
    <div class="page login-page">
        <div class="container d-flex align-items-center">
            <div class="form-holder has-shadow">
                <div class="row no-gutters">
                    <!-- Logo & Information Panel-->
                    <div class="col-md-6 col-lg-7 col-xl-8 my-n5 d-none d-md-block">
                        <div class="info d-flex p-5 mr-n5 position-relative login-img rounded-xl shadow-lg" style="background-image: url(<?php echo io_get_option('login_ico') ?>);">
                            <div class="content position-absolute mr-5 pr-5">
                                <div class="logo">
                                    <h1><?php bloginfo('name') ?></h1>
                                </div>
                                <p><?php bloginfo('description') ?></p>
                            </div>
                        </div>
                    </div>
                    <!-- Form Panel    -->
                    <div class="col-12 col-md-6 col-lg-5 col-xl-4 bg-white rounded-xl shadow-lg">
                        <div class="form d-flex align-items-center p-4 p-md-5">
                            <?php if ( !get_option('users_can_register') )  { ?>
                            <div class="content">
                                <div class="sign-header h4 mb-2 mb-md-5"><?php _e( '禁止注册', 'i_theme' ); ?></div>
                                <p class="reg-error"><i class="iconfont icon-tishi"></i> <?php echo sprintf( '请联系<a href="mailto:%s">管理员</a>！', get_option( 'admin_email' ) ) ?></p>
                                <div class=" text-muted">
                                    <small><?php _e( '已有账号?', 'i_theme' ); ?> <a href="<?php echo esc_url(io_add_redirect(home_url('/login/'))) ?>" class="signup"><?php _e( '登陆', 'i_theme' ); ?></a></small> 
                                </div>
                            </div>
                            <?php } else { ?>
                            <div class="content">
                                <?php if (!is_user_logged_in()) { ?>
                                <div class="sign-header h4 mb-2 mb-md-4"><?php _e('注册','i_theme') ?></div>
                                <div id="result" class="mb-4 text-danger"></div>
                                <div style="color:#f1404b;height:30px">
                                <?php if(!empty($error)) {
                                    echo '<i class="iconfont icon-crying mr-2"></i>'.$error;
                                }?>
                                </div>
                                <form name="registerform" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" class="form-validate" id="wp_login_form">
                                    <div class="form-group mb-0">
                                        <input type="text" name="user_login" tabindex="1" id="user_login" placeholder="<?php _e('用户名','i_theme') ?>" value="<?php if(!empty($sanitized_user_login)) echo $sanitized_user_login; ?>"  size="30" class="input-material"/> 
                                        <div style="color:#f1404b;height:25px"><small><?php if(!empty($user_error))  echo '<i class="iconfont icon-crying mr-2"></i>'.$user_error; ?></small></div>
                                    </div> 
                                    <div class="form-group mb-0">
                                        <input type="text" name="user_email" tabindex="2" id="user_email" placeholder="<?php _e('邮箱','i_theme') ?>" value="<?php if(!empty($user_email)) echo $user_email; ?>"  size="30" class="input-material"/> 
                                        <div style="color:#f1404b;height:25px"><small><?php if(!empty($email_error))  echo '<i class="iconfont icon-crying mr-2"></i>'.$email_error; ?></small></div>
                                    </div> 
                                    <?php if(io_get_option('reg_verification')){ ?>
                                    <div class="form-group mb-0 verification" style="display:none">
                                        <input type="text" name="verification_code" tabindex="3" id="verification_code" placeholder="验证码"  size="6" class="input-material"/> 
                                        <a href="javascript:;" class="btn-token col-form-label text-sm"><?php _e('发送验证码','i_theme') ?></a>
                                        <div style="color:#f1404b;height:25px"><small class="code-error"><?php if(!empty($code_error))  echo '<i class="iconfont icon-crying mr-2"></i>'.$code_error; ?></small></div>
                                    </div> 
                                    <?php } ?>
                                    <div class="form-group mb-0">
                                        <input type="password" name="user_pass" tabindex="4" id="user_pwd1" placeholder="<?php _e('密码','i_theme') ?>" size="30" class="input-material"/> 
                                        <div style="color:#f1404b;height:25px"><small><?php if(!empty($pass_error))  echo '<i class="iconfont icon-crying mr-2"></i>'.$pass_error; ?></small></div>
                                    </div> 
                                    <div class="form-group mb-0">
                                        <input type="password" name="user_pass2" tabindex="5" id="user_pwd2" placeholder="<?php _e('确认密码','i_theme') ?>" size="30" class="input-material"/> 
                                        <div style="color:#f1404b;height:25px"><small><?php if(!empty($pass_error2))  echo '<i class="iconfont icon-crying mr-2"></i>'.$pass_error2; ?></small></div>
                                    </div> 
                                    <div class="login-form mb-4"><?php do_action('register_form'); ?></div>
                                    <div class="d-flex my-3">
                                        <input type="hidden" name="user_reg" value="ok" />
                                        <?php  if( LOGIN_007 && io_get_option('io_captcha')['tcaptcha_007'] && io_get_option('io_captcha')['appid_007'] ) { ?>
                                        <input class="btn btn-danger btn-block" type="button" id="TencentCaptcha" value="<?php _e('注册','i_theme') ?>" data-appid="<?php echo io_get_option('io_captcha')['appid_007'] ?>" data-cbfn="loginTicket"/>
                                        <?php } else { ?>
                                        <button id="submit" type="submit" name="submit" class="btn btn-danger btn-block"><?php _e( '注册', 'i_theme' ); ?></button>
                                        <?php } ?>
                                        <a href="<?php echo esc_url(home_url()) ?>" class="btn btn-outline-danger btn-block mt-0 ml-4"><?php _e( '首页', 'i_theme' ); ?></a>
                                    </div> 
                                    <div class=" text-muted">
                                        <small><?php _e( '已有账号?', 'i_theme' ); ?> <a href="<?php echo esc_url(io_add_redirect(home_url('/login/'))) ?>" class="signup"><?php _e( '登陆', 'i_theme' ); ?></a></small> 
                                    </div>
                                    <div class="login-form mt-4"><?php do_action('io_login_form'); ?></div>
                                </form> 
                                <?php } else { ?>
                                <div class="sign-header h4 mb-2 mb-md-5"><?php _e( '注册成功', 'i_theme' ); ?></div> 
                                <div class="d-flex mt-3">
                                    <a href="<?php echo wp_logout_url( home_url() ); ?>" class="btn btn-danger btn-block"><?php _e( '退出登录', 'i_theme' ); ?></a>
                                    <?php
                                    if (current_user_can('manage_options')) {
                                        echo '&nbsp;&nbsp;<a href="' . admin_url() . '" class="btn btn-outline-danger btn-block mt-0 ml-4">' . sprintf(__( '管理站点', 'i_theme' )) . '</a>';
                                    } else {
                                        echo '&nbsp;&nbsp;<a href="/user/settings" class="btn btn-outline-danger btn-block mt-0 ml-4">' . sprintf(__( '用户中心', 'i_theme' )) . '</a>';
                                    }
                                    ?>
                                </div>
                                <p class="text-xs text-muted mt-3"><i class="iconfont icon-tishi"></i><?php _e( '3秒后自动跳转', 'i_theme' ); ?></p>
                                <script type="text/javascript">setTimeout(location.href="<?php echo $redirect_to ?>",3000);</script>
                                <?php } ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-copyright my-4">
            <div class="text-white-50 text-center">
                <small>Copyright © <a href="<?php echo esc_url(home_url()) ?>" class="text-white-50" title="<?php bloginfo('name') ?>" rel="home"><?php bloginfo('name') ?></a></small> 
            </div>
        </div>
    </div> 

    <script type="text/javascript">//ajax 提交数据 
        $("#user_email").on("input propertychange",function(){
            if($(this).val().length > 4)
                $(".verification").slideDown();
        });
        $(".btn-token").click(function() {
            var t = $(this);
            var email = $('#user_email');
            if(!email.val()){
                $('.code-error').text("<?php _e('请填写邮箱！','i_theme') ?>");
                return;
            }
            t.text("<?php _e('稍等...','i_theme') ?>").addClass("disabled");
            email.attr("readonly","readonly");
            $.ajax({
                url: "<?php echo admin_url( 'admin-ajax.php' ) ?>", 
                data : "action=reg_email_token&mm_mail="+email.val(),
                type: 'POST',
                dataType: 'json',
            })
            .done(function(response) {  
                if(response.status == 1){
                    settime();
                    $('.code-error').html('<span style="color:#2ac12a">'+response.msg+'</span>'); 
                }else{
                    email.removeAttr("readonly");
                    t.text("<?php _e('发送验证码','i_theme') ?>").removeClass("disabled");
                    $('.code-error').text(response.msg); 
                }
            })
            .fail(function() { 
                email.removeAttr("readonly"); 
                t.text("<?php _e('发送验证码','i_theme') ?>").removeClass("disabled");
                $('.code-error').text("<?php _e('网络错误！','i_theme') ?>");
            });
        });  
        var timer;
        var countdown=60;
        function settime() {
            if (countdown == 0) {
                $(".btn-token").html("<?php _e('重新发送','i_theme') ?>").removeClass("disabled"); 
                countdown = 60;
                clearTimeout(timer);
                $('#user_email').removeAttr("readonly");
                return;
            } else {
                $(".btn-token").html(countdown+"<?php _e('秒后重新发送','i_theme') ?>");
                countdown--; 
            };
            timer=setTimeout(function() { 
                settime() 
            },1000) 
        }
    </script>
    <?php do_action( 'login_footer' ); ?>
</body >
</html>
