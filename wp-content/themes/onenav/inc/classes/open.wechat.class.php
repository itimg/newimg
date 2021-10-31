<?php 
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-03-01 10:19:02
 * @LastEditors: iowen
 * @LastEditTime: 2021-09-11 13:27:37
 * @FilePath: \onenav\inc\classes\open.wechat.class.php
 * @Description: 微信开放平台
 */
class ioLoginWechat{

    public function __construct(){
    }

    function login($appid, $scope,$state, $callback){  
        $params=array(
            'response_type' =>'code',
            'appid'         =>$appid,
            'state'         =>$state,
            'scope'         =>$scope,
            'redirect_uri'  =>$callback
        );
        $login_url = 'https://open.weixin.qq.com/connect/qrconnect?'.http_build_query($params);
        wp_redirect($login_url, 302);
        exit;
    } 
	function login2($appid, $scope,$state, $callback) { 
		$params = array(
			'response_type'	=> 'code',
			'appid'			=> $appid,
			'scope'			=> $scope,
			'state'			=> $state,
            'redirect_uri'  => $callback
		);
        $login_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?'.http_build_query($params); 
        wp_redirect($login_url, 302);
        exit;
	}
    function callback($appid,$appkey,$code,$state){
        if($_REQUEST ['state'] == $state){ 
            $params=array(
                'grant_type'=>'authorization_code',
                'code'=>$code,
                'appid'=>$appid,
                'secret'=>$appkey
            );
            $token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?'.http_build_query($params);
            $response = wp_remote_get($token_url);
            $body = wp_remote_retrieve_body($response);
            
            if(preg_match('/\{(.*)\}/', $body, $matches)){
                $msg = json_decode(trim($matches[0]));
                if(isset($msg->errcode)){
                    wp_die($msg->errcode.': '.$msg->errmsg, __('获取微信 Access Token 失败', 'i_theme'), array('response'=>403));
                }
            }else{
                wp_die('微信服务器返回了不正确的响应', __('获取微信 Access Token 失败', 'i_theme'), array('response'=>403));
            }
            return array(
                'token'    => $msg->access_token,
                'open_id'  => $msg->openid,
            ); 
        }else{
            echo ("The state does not match. You may be a victim of CSRF.");
            exit;
        }
    }
    function wx_oauth2_get_user_info($access_token, $openid){
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
        
        $response = wp_remote_get($url);

        $body = wp_remote_retrieve_body($response);

        $msg = null;
        if(preg_match('/\{(.*)\}/', $body, $matches)){
            $msg = json_decode(trim($matches[0]),true);
            if(isset($msg['errcode'])){
                wp_die($msg['errcode'].': '.$msg['errmsg'], __('获取微信用户信息失败', 'i_theme'), array('response'=>403));
            }
        }else{
            wp_die('微信服务器返回了不正确的响应', __('获取微信用户信息失败', 'i_theme'), array('response'=>403));
        }

        return  $msg ;
    }
    function use_db($access_token, $openid, $type, $back_url){ 
        $userInfo = $this->wx_oauth2_get_user_info($access_token,$openid); 
        
        if ($openid && $userInfo) {
            $userInfo['name'] = !empty($userInfo['nickname']) ? $userInfo['nickname'] : '';
        
            $oauth_data = array(
                'type'          => $type,
                'openid'        => $openid,
                'name'          => $userInfo['name'],
                'avatar'        => !empty($userInfo['headimgurl']) ? $userInfo['headimgurl'] : '',
                'description'   => '',
                'getUserInfo'   => $userInfo,
                'rurl'          => $back_url, 
            );
        
            $oauth_result = io_oauth_update_user($oauth_data);
        
            io_oauth_login_after_execute($oauth_result,$back_url);

        } else {
            wp_die(
                '<h1>' . __('处理错误') . '</h1>' .
                    '<p>' . json_encode($userInfo) . '</p>' .
                    '<p>openid:' . $openid . '</p>',
                403
            );
            exit;
        }
        wp_safe_redirect(home_url());
        exit;
    }  
} 
?>
