<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:56:00
 * @LastEditors: iowen
 * @LastEditTime: 2021-10-02 16:26:23
 * @FilePath: \onenav\templates\login\lost.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title><?php _e( '找回密码', 'i_theme' ); ?> - <?php bloginfo('name') ?></title>
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
                                <div class="sign-header h4 mb-3 mb-md-4"><?php _e('找回密码','i_theme') ?></div>
				                <div id="result" class="mb-4 text-danger"></div>
                                <div class="mb-4"><small><i class="iconfont icon-tishi"></i> <?php _e( '输入用户名或电子邮箱地址，您会收到一封新密码链接的电子邮件。', 'i_theme' ); ?></small></div>
						        <form method="post" action="<?php echo esc_url(home_url('wp-login.php?action=lostpassword')) ?>" class="wp-user-form" id="wp_login_form">

                                    <div class="form-group mb-4">
                                      <input type="text" name="user_login" size="20" id="user_log" placeholder="用户名或电子邮件地址" class="input-material" required="required" >
                                    </div>
                                    <div class="login-form mb-3"><?php do_action('lostpassword_form'); ?></div>
						        	<div class="login_fields mb-3">
                                        <?php  if( LOGIN_007 && io_get_option('io_captcha')['tcaptcha_007'] && io_get_option('io_captcha')['appid_007'] ) { ?>
                                        <input class="btn btn-danger btn-block" type="button" id="TencentCaptcha" value="<?php _e('获取新密码','i_theme') ?>" data-appid="<?php echo io_get_option('io_captcha')['appid_007'] ?>" data-cbfn="loginTicket"/>
                                        <?php } else { ?>
						        		<input type="submit" name="user-submit" value="<?php _e( '获取新密码', 'i_theme' ); ?>" class="btn btn-danger btn-block" />
                                        <?php } ?>
						        		<?php $reset = isset($_GET['reset'])?$_GET['reset']:''; if($reset == true) { echo '<p></p>'; } ?>
						        		<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>?reset=true" />
						        		<input type="hidden" name="user-cookie" value="1" />
						        	</div>
                                    <div class=" text-muted">
                                        <small><a href="<?php echo esc_url(io_add_redirect(home_url('/login/').'?action=register')) ?>" class="signup"><?php _e('注册','i_theme') ?></a> | <a href="<?php echo esc_url(io_add_redirect(home_url('/login/'))) ?>" class="signup"><?php _e('登陆','i_theme') ?></a></small> 
                                    </div>
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
    <?php do_action( 'login_footer' ); ?>
</body >
</html>