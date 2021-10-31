<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:56:00
 * @LastEditors: iowen
 * @LastEditTime: 2021-10-02 16:26:13
 * @FilePath: \onenav\templates\login\login.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; } 
$redirect_to = '';
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
<title><?php _e( '登陆', 'i_theme' ); ?> - <?php bloginfo('name') ?></title>
<link rel="shortcut icon" href="<?php echo io_get_option('favicon') ?>">
<link rel="apple-touch-icon" href="<?php echo io_get_option('apple_icon') ?>">
<meta name='robots' content='noindex,nofollow' /> 
<?php do_action('login_head'); ?>
<?php $login_color = io_get_option('login_color'); ?>
<style> 
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
                            <div class="content">
                                <div class="sign-header h4 mb-3 mb-md-4"><?php _e('欢迎回来','i_theme') ?></div>
                                <div id="result" style="color:#f1404b;height:30px"></div>
                                <form method="post" action="" class="form-validate" id="wp_login_form">
                                    <input type="hidden" name="action" value="user_login" />
                                    <div class="form-group mb-4">
                                        <input type="text" name="username" placeholder="<?php _e('用户名或邮箱','i_theme') ?>" class="input-material">
                                    </div>
                                    <div class="form-group mb-4">
                                        <input type="password" name="password" placeholder="<?php _e('密码','i_theme') ?>" class="input-material">
                                    </div> 
                                    <div class="custom-control custom-checkbox mb-4">
                                        <input type="checkbox" class="custom-control-input" checked="checked" name="rememberme" id="check1" value="forever">
                                        <label class="custom-control-label" for="check1"><?php _e('记住我的登录信息','i_theme') ?></label>
                                    </div> 
                                    <input type="hidden" name="redirect_to" value="<?php echo $redirect_to ?>" /> 
                                    <div class="login-form mb-4"><?php do_action('login_form'); ?></div>
                                    <div class="d-flex mb-3">
                                        <?php  if( LOGIN_007 && io_get_option('io_captcha')['tcaptcha_007'] && io_get_option('io_captcha')['appid_007'] ) { ?>
                                        <input class="btn btn-danger btn-block" type="button" id="TencentCaptcha" value="<?php _e('登录','i_theme') ?>" data-appid="<?php echo io_get_option('io_captcha')['appid_007'] ?>" data-cbfn="loginTicket"/>
                                        <?php } else { ?>
                                        <button id="submitbtn" type="submit" class="btn btn-danger btn-block"><?php _e('登录','i_theme') ?></button>
                                        <?php } ?> 
                                        <a href="<?php echo esc_url(home_url()) ?>" class="btn btn-outline-danger btn-block mt-0 ml-4"><?php _e('首页','i_theme') ?></a>
                                    </div> 
                                    <div class=" text-muted">
                                        <small><?php _e('没有账号?','i_theme') ?><a href="<?php echo esc_url(io_add_redirect(home_url('/login/?action=register'))) ?>" class="signup"><?php _e('注册','i_theme') ?></a> / <a href="<?php echo esc_url(io_add_redirect(home_url('/login/').'?action=lostpassword')) ?>" class="signup"><?php _e('找回密码','i_theme') ?></a></small> 
                                    </div>
                                    <div class="login-form mt-4"><?php do_action('io_login_form'); ?></div>
                                </form> 
                            </div>
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
        $("#wp_login_form").submit(function() { 
            $('#result').html('<i class="loader iconfont icon-loading icon-spin icon-2x"></i>').fadeIn();
            var input_data = $('#wp_login_form').serialize(); 
            $.ajax({
                type: "POST",
                url: "<?php echo admin_url( 'admin-ajax.php' ) ?>",
                data: input_data,
                dataType: "json",
                success: function(m){
                    if(m.status == 1){
                        $('div#result').html('').css('color','#2ac12a');
                        $('<div>').html("<i class='iconfont icon-smiley mr-2'></i>"+m.msg).appendTo('div#result').hide().fadeIn('slow');
                        window.location.href = m.goto; 
                        window.location.reload;
                    }else{
                        $('div#result').html('');
                        $('<div>').html("<i class='iconfont icon-crying mr-2'></i>"+m.msg).appendTo('div#result').hide().fadeIn('slow');
                    }
                },
                error: function () {
                    $('div#result').html('');
                    $('<div>').html("网络错误").appendTo('div#result').hide().fadeIn('slow');
                }
            });
            return false;
        });  
    </script>
    <?php do_action( 'login_footer' ); ?>

</body>
</html>