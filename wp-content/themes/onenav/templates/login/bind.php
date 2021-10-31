<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-09-10 16:47:20
 * @LastEditors: iowen
 * @LastEditTime: 2021-10-02 16:25:10
 * @FilePath: \onenav\templates\login\bind.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; } 
$bind_email = io_get_option('bind_email');

$action_1   = "register_after_bind_email";

$redirect_to = esc_url(home_url());
if ( isset( $_REQUEST['redirect'] ) || isset( $_REQUEST['redirect_to'] ) ){
    $redirect_to = isset($_REQUEST['redirect']) ?  $_REQUEST['redirect'] : $_REQUEST['redirect_to']; 
}
if($bind_email!='must'&&!is_user_logged_in()){
    wp_safe_redirect($redirect_to);
}elseif($bind_email!='must'&&is_user_logged_in()){
    $user_id = wp_get_current_user()->ID;
    if(get_user_meta($user_id, 'email_status',true))
        wp_safe_redirect($redirect_to);
}
if($bind_email=='null'&&!isset($_GET['type'])){
    wp_safe_redirect($redirect_to);
}
if($bind_email=='must'&&!isset($_GET['type'])){//执行绑定
    if(!session_id()) session_start();
    if(!isset($_SESSION['temp_oauth']) || (isset($_SESSION['temp_oauth']) && empty($_SESSION['temp_oauth'])))
        wp_safe_redirect($redirect_to);
    switch(maybe_unserialize($_SESSION['temp_oauth'])['type']){
        case 'qq':
            $type = 'qq';
            break;
        case 'sina':
            $type = 'weibo';
            break;
        case 'wechat_gzh':
            $type = 'wechat-gzh';
            break;
        case 'wechat_dyh':
            $type = 'wechat-dyh';
            break;
        case 'wechat':
            $type = 'wechat';
            break;
    }
    $action_1 = "register_and_bind_email";
}elseif($bind_email=='must'&&isset($_GET['type'])&&!is_user_logged_in()){
    wp_safe_redirect($redirect_to);
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title><?php _e( '绑定邮箱', 'i_theme' ); ?> - <?php bloginfo('name') ?></title>
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
.openlogin-<?php echo $type  ?>-a{pointer-events:none;filter:grayscale(100%);-webkit-filter:grayscale(100%);-moz-filter:grayscale(100%);-ms-filter:grayscale(100%);-o-filter:grayscale(100%);filter:url("data:image/svg+xml;utf8,#grayscale");filter:progid:DXImageTransform.Microsoft.BasicImage(grayscale=1);-webkit-filter:grayscale(1)}
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
                                <div class="sign-header h4 mb-3 mb-md-4"><?php echo (isset($_GET['type'])?__('绑定邮箱','i_theme'):__('绑定邮箱完成注册','i_theme')) ?></div>
                                <div id="result" style="color:#f1404b;height:30px"></div>
                                <?php if($bind_email=="must"&&!isset($_GET['type'])): ?>
                                <ul class="nav nav-justified mb-4" id="pills-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="pills-new-tab" data-toggle="pill" data-btn="<?php _e('确定','i_theme') ?>" data-action="<?php echo $action_1 ?>" href="#pills-new" role="tab" aria-controls="pills-new" aria-selected="true"><?php _e('设置邮箱','i_theme') ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-old-tab" data-toggle="pill" data-btn="<?php _e('登录并绑定','i_theme') ?>" data-action="user_login" href="#pills-old" role="tab" aria-controls="pills-old" aria-selected="false"><?php _e('绑定现有账号','i_theme') ?></a>
                                    </li>
                                </ul>
                                <?php endif; ?>
                                <form method="post" action="" class="form-validate mb-3" id="wp_login_form">
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="pills-new" role="tabpanel" aria-labelledby="pills-new-tab">
                                            <input type="hidden" name="action" value="<?php echo $action_1 ?>" />
                                            <input type="hidden" name="old_bind" value="1" />
                                            <div class="form-group mb-0">
                                                <input type="text" name="user_email" tabindex="2" id="user_email" placeholder="<?php _e('输入邮箱','i_theme') ?>" value="<?php if(!empty($user_email)) echo $user_email; ?>"  size="30" class="input-material"/> 
                                                <div style="color:#f1404b;height:25px"><small><?php if(!empty($email_error))  echo '<i class="iconfont icon-crying mr-2"></i>'.$email_error; ?></small></div>
                                            </div> 
                                            <div class="form-group mb-0 verification" style="display:none">
                                                <input type="text" name="verification_code" tabindex="3" id="verification_code" placeholder="验证码"  size="6" class="input-material"/> 
                                                <a href="javascript:;" class="btn-token col-form-label text-sm"><?php _e('发送验证码','i_theme') ?></a>
                                                <div style="color:#f1404b;height:25px"><small class="code-error"><?php if(!empty($code_error))  echo '<i class="iconfont icon-crying mr-2"></i>'.$code_error; ?></small></div>
                                            </div> 
                                            <div class="form-group mb-0">
                                                <input type="password" name="user_pass" tabindex="4" id="user_pwd" placeholder="<?php _e('设置密码','i_theme') ?>" size="30" class="input-material"/> 
                                                <div style="color:#f1404b;height:25px"><small><?php if(!empty($pass_error))  echo '<i class="iconfont icon-crying mr-2"></i>'.$pass_error; ?></small></div>
                                            </div> 
                                        </div>
                                        <div class="tab-pane fade" id="pills-old" role="tabpanel" aria-labelledby="pills-old-tab">
                                            <div class="form-group mb-4">
                                                <input type="text" name="username" placeholder="<?php _e('用户名或邮箱','i_theme') ?>" class="input-material">
                                            </div>
                                            <div class="form-group mb-4">
                                                <input type="password" name="password" placeholder="<?php _e('密码','i_theme') ?>" class="input-material">
                                            </div> 
                                            <input type="hidden" name="redirect_to" value="<?php echo $redirect_to ?>" /> 
                                        </div>
                                        <div class="mb-4"><?php do_action('io_bind_form'); ?></div>
                                    </div>
                                    <?php  if( LOGIN_007 && io_get_option('io_captcha')['tcaptcha_007'] && io_get_option('io_captcha')['appid_007'] ) { ?>
                                    <input class="btn btn-danger btn-block submit-btn" type="button" id="TencentCaptcha" value="<?php _e('确定','i_theme') ?>" data-appid="<?php echo io_get_option('io_captcha')['appid_007'] ?>" data-cbfn="loginTicket"/>
                                    <?php } else { ?>
                                    <button id="submitbtn" type="submit" class="btn btn-danger btn-block submit-btn"><?php _e('确定','i_theme') ?></button>
                                    <?php } ?> 
                                    <div class="login-form mt-4 mb-n4 d-none"><?php do_action('io_login_form'); ?></div>
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
        $("#user_email").on("input propertychange",function(){
            if($(this).val().length > 2)
                $(".verification").slideDown(); 
        });
        $("#pills-new-tab").click(function() {
            $('input[name="action"]').val($(this).data("action"));
            $('input.submit-btn').val($(this).data("btn"));
            $('button.submit-btn').text($(this).data("btn"));
            $('.login-form').addClass('d-none');
        });
        $("#pills-old-tab").click(function() {
            $('input[name="action"]').val($(this).data("action"));
            $('input.submit-btn').val($(this).data("btn"));
            $('button.submit-btn').text($(this).data("btn"));
            $('.login-form').removeClass('d-none');
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
        $("#wp_login_form").submit(function() {
            $('#result').html('<i class="loader iconfont icon-loading icon-spin icon-2x"></i>').fadeIn();
            var input_data = $('#wp_login_form').serialize(); 
            $.ajax({
                type: "POST",
                url: "<?php echo admin_url( 'admin-ajax.php' ) ?>",
                data: input_data,
                dataType: "json",
                success: function(msg){
                    if(msg.status==1){
                        $('div#result').html('').css('color','#2ac12a');
                        $('<div>').html("<i class='iconfont icon-smiley mr-2'></i>"+msg.msg).appendTo('div#result').hide().fadeIn('slow');
                        setTimeout(location.href="<?php echo $redirect_to ?>",3000);
                    }else{
                        $('div#result').html('');
                        $('<div>').html("<i class='iconfont icon-crying mr-2'></i>"+msg.msg).appendTo('div#result').hide().fadeIn('slow');
                    }
                }
            });
            return false;
        }); 
    </script>
    <?php do_action( 'login_footer' ); ?>
</body>
</html>
