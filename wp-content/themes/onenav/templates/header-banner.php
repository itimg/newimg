<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:56:02
 * @LastEditors: iowen
 * @LastEditTime: 2021-07-20 21:21:01
 * @FilePath: \onenav\templates\header-banner.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
		<div id="header" class="page-header sticky">
			<div class="navbar navbar-expand-md">
                <div class="container-fluid p-0">

                    <a href="<?php echo esc_url( home_url() ) ?>" class="navbar-brand d-md-none" title="<?php bloginfo('name') ?>">
						<img src="<?php echo io_get_option('logo_small_light') ?>" class="logo-light" alt="<?php bloginfo('name') ?>">
						<img src="<?php echo io_get_option('logo_small') ?>" class="logo-dark d-none" alt="<?php bloginfo('name') ?>">
                    </a>
                    
                    <div class="collapse navbar-collapse order-2 order-md-1">
                        <div class="header-mini-btn">
                            <label>
                                <input id="mini-button" type="checkbox" <?php echo io_get_option('min_nav')?'':'checked="checked"'?>>
                                <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"> 
                                    <path class="line--1" d="M0 40h62c18 0 18-20-17 5L31 55"></path>
                                    <path class="line--2" d="M0 50h80"></path>
                                    <path class="line--3" d="M0 60h62c18 0 18 20-17-5L31 45"></path>
                                </svg>
                            </label>
                        
                        </div>
                        <?php if(io_get_option('weather') && io_get_option('weather_location')=='header'){ ?>
                        <!-- 天气 -->
                        <div class="weather">
                            <div id="he-plugin-simple" style="display: contents;"></div>
                            <script>WIDGET = {CONFIG: {"modules": "12034","background": "5","tmpColor": "888","tmpSize": "14","cityColor": "888","citySize": "14","aqiSize": "14","weatherIconSize": "24","alertIconSize": "18","padding": "10px 10px 10px 10px","shadow": "1","language": "auto","fixed": "false","vertical": "middle","horizontal": "left","key": "a922adf8928b4ac1ae7a31ae7375e191"}}</script>
                            <script>
                            loadFunc(function() {
                                let script = document.createElement("script");
                                script.setAttribute("async", "");
                                script.src = "//widget.qweather.net/simple/static/js/he-simple-common.js?v=2.0";
                                document.body.appendChild(script);
                            });
                            </script>
                        </div>
                        <!-- 天气 end -->
                        <?php } ?>
						<ul class="navbar-nav site-menu mr-4">
                            <?php wp_menu('main_menu'); ?> 
						</ul>
                    </div>
                    
                    <ul class="nav navbar-menu text-xs order-1 order-md-2">
                        <?php if(io_get_option('hitokoto')){ ?>
                        <!-- 一言 -->
						<li class="nav-item mr-3 mr-lg-0 d-none d-lg-block">
							<div class="text-sm overflowClip_1">
                                <script src="//v1.hitokoto.cn/?encode=js&select=%23hitokoto" defer></script> 
                                <span id="hitokoto"></span> 
							</div>
						</li>
                        <!-- 一言 end -->
                        <?php } ?>
                        <?php if( io_get_option('nav_login') ){ 
                            global $user_ID; 
                            if(!$user_ID) {?>
						    <li class="nav-login ml-3 ml-md-4">
						    	<a href="<?php echo esc_url(home_url('/wp-login.php')) ?>" title="登录"><i class="iconfont icon-user icon-2x"></i></a>
						    </li>
                        <?php }else{
                                get_template_part( 'templates/widget/header', 'user' );
                            }
                        } ?>
                        <?php if( io_get_option('search_position') && in_array("top",io_get_option('search_position')) ){ ?>
						<li class="nav-search ml-3 ml-md-4">
							<a href="javascript:" data-toggle="modal" data-target="#search-modal"><i class="iconfont icon-search icon-2x"></i></a>
						</li>
                        <?php } ?>
						<li class="nav-item d-md-none mobile-menu ml-3 ml-md-4">
							<a href="javascript:" id="sidebar-switch" data-toggle="modal" data-target="#sidebar"><i class="iconfont icon-classification icon-2x"></i></a>
						</li>
                    </ul>
				</div>
            </div>
        </div>
        <div class="placeholder"></div>
