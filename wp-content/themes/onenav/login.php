<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:56:00
 * @LastEditors: iowen
 * @LastEditTime: 2021-09-11 22:32:22
 * @FilePath: \onenav\login.php
 * @Description: 
 */
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header('Access-Control-Allow-Headers:x-requested-with,content-type');

global $wpdb,$user_ID;

$action  = (isset($_GET['action']) ) ? $_GET['action'] : 0; 

if ( $action === "bind" ) { 
    get_template_part( 'templates/login/bind' );
} elseif ( $action === "register" ) { 
    get_template_part( 'templates/login/reg' );
} elseif ( $action === "lostpassword" ) {
    get_template_part( 'templates/login/lost' );
} else {
    if (!$user_ID) {  
        get_template_part( 'templates/login/login' );
    } else { 
        echo "<script type='text/javascript'>window.location='". esc_url(home_url()) ."'</script>";
    }

}
