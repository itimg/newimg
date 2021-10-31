<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-03-01 10:19:02
 * @LastEditors: iowen
 * @LastEditTime: 2021-09-11 17:46:30
 * @FilePath: \onenav\inc\auth\gzh-callback.php
 * @Description: 
 */
include_once('../../../../../wp-config.php'); 
//获取后台配置
$wxConfig = io_get_option('open_weixin_gzh_key');
//微信配置接口验证
if (!empty($_REQUEST['echostr']) && !empty($_REQUEST['signature'])) {
    //微信接口校验
    $signature = $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce = $_GET["nonce"];

    $token = $wxConfig['token'];
    $tmpArr = array($token, $timestamp, $nonce);
    sort($tmpArr, SORT_STRING);
    $tmpStr = implode($tmpArr);
    $tmpStr = sha1($tmpStr);

    if ($tmpStr == $signature) {
        echo $_REQUEST['echostr'];
    }
    exit();
}

//微信APP内跳转登录
if (io_is_wechat_app()) {
    // 在微信APP内使用无感登录接口
	$callback = new ioLoginWechat();
    if(!session_id()) session_start();
	$callback_data = $callback->callback($wxConfig['appid'], $wxConfig['appkey'],$_REQUEST['code'],$_SESSION ['weixin_state'] ); 
	if(is_array($callback_data)){
		$callback->use_db($callback_data['token'],$callback_data['open_id'],'wechat_gzh',$_SESSION['rurl']);
	}
    exit();
}

//扫码登录流程 
$wxOAuth = new ioLoginWechatGZH($wxConfig['appid'], $wxConfig['appkey']);

$action = !empty($_REQUEST['action']) ? $_REQUEST['action'] : 'callback';

switch ($action) {
    case 'callback':
        //接受微信发过来的信息
        $callback = $wxOAuth->callback();
        if ($callback) {
            $EventKey = str_replace('qrscene_', '', $callback['EventKey']);
            update_option('weixingzh_event_key_' . $EventKey, $callback);
            //给用户发送消息
            if (!empty($wxConfig['subscribe_msg']) && $callback['Event'] == 'subscribe') {
                $wxOAuth->sendMessage($wxConfig['subscribe_msg']);
                exit();
            } elseif (!empty($wxConfig['scan_msg']) && $callback['Event'] == 'SCAN') {
                $wxOAuth->sendMessage($wxConfig['scan_msg']);
                exit();
            }

            echo 'callback'; //发送字串符，让微信停止发送
            exit();
        }
        break;

    case 'check_callback':
        //前端验证是否回调
        $state = !empty($_REQUEST['state']) ? $_REQUEST['state'] : '';
        if (!$state) { 
            echo (json_encode(array('error' => 1, 'msg' => '参数传入错误'))); 
            exit();
        }
        // 验证 CSRF
        $option = get_option('weixingzh_event_key_' . $state);
        if (!$option) {
            echo (json_encode(array('error' => 1, 'msg' => 'Waiting...'))); 
            exit;
        }
        delete_option('weixingzh_event_key_' . $state);
        // -- CSRF
        $goto_uery_arg = array(
            'action' => 'login',
            'openid' => $option['FromUserName']
        );
        if (!empty($_REQUEST['oauth_rurl'])) $goto_uery_arg['oauth_rurl'] = $_REQUEST['oauth_rurl'];
        echo (json_encode(array('goto' => add_query_arg($goto_uery_arg, get_template_directory_uri().'/inc/auth/gzh-callback.php'), 'option' => $option)));
        exit();

        break;

    case 'login':
        //前台登录或者绑定
        $openId = !empty($_REQUEST['openid']) ? $_REQUEST['openid'] : '';
        if (!$openId) {
            wp_die(__('参数传入错误', 'i_theme'));
        }
        if(!session_id()) session_start();
        $wxOAuth->use_db($openId,$_SESSION['rurl']);
        break;
}
exit;
