<?php 
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-03-01 10:19:02
 * @LastEditors: iowen
 * @LastEditTime: 2021-09-11 17:47:03
 * @FilePath: \onenav\inc\classes\open.qq.class.php
 * @Description:  
 */
class ioLoginQQ
{ 

    public function __construct(){ 

    }

    function login($appid, $scope,$state, $callback) {
        $params=array(
            'response_type'=>'code',
            'client_id'=>$appid,
            'state'=>$state,
            'scope'=>$scope,
            'redirect_uri'=>$callback
        );
        $login_url = 'https://graph.qq.com/oauth2.0/authorize?'.http_build_query($params);
        wp_redirect($login_url, 302);
        exit;
    }
    function callback($appid,$appkey,$path,$state) {
        if ($_REQUEST ['state'] == $state) {
            $params=array(
                'grant_type'=>'authorization_code',
                'code'=>$_REQUEST ["code"],
                'client_id'=>$appid,
                'client_secret'=>$appkey,
                'redirect_uri'=>$path
            );
            $token_url = 'https://graph.qq.com/oauth2.0/token?'.http_build_query($params);
            
            $response = wp_remote_get( $token_url );
            $body = wp_remote_retrieve_body($response);
            if(preg_match('/callback\((.*)\)/', $body, $matches)){
                $msg = json_decode(trim($matches[1]));
                if(isset($msg->error)){
                    wp_die($msg->error.': '.$msg->error_description, __('获取 QQ Access Token 失败', 'i_theme'), array('response'=>403));
                }
            }
            
            $params = array ();
            parse_str ( $body, $params );
            //$_SESSION ['qq_access_token'] = $params ["access_token"];
            //$_SESSION ['qq_refresh_token'] = $params ["refresh_token"];
            //$_SESSION ['qq_token_expiration'] = $params ["expires_in"];
            return array(
                'token'    => $params ["access_token"],
                'refresh'  => $params ["refresh_token"],
                'expires'  => $params ["expires_in"],
            ); 
        } else {
            echo ("The state does not match. You may be a victim of CSRF.");
            exit;
        }
    }
    function get_openid($access_token) {
        $graph_url = "https://graph.qq.com/oauth2.0/me?access_token=" . $access_token;
        
        $response = wp_remote_get( $graph_url );
        $body = wp_remote_retrieve_body($response);
        $msg = null;
        if(preg_match('/callback\((.*)\)/', $body, $matches)){
            $msg = json_decode(trim($matches[1]));
            if(isset($msg->error)){
                wp_die($msg->error.': '.$msg->error_description, __('获取 QQ OpenID 失败', 'i_theme'), array('response'=>403));
            }
        }
        if(!$msg){
            parse_str($body, $params);
            $msg = (object)$params;
        }
        //$_SESSION ['qq_openid'] = $msg->openid;
        return $msg->openid;
    }
    function get_user_info($access_token, $openid) {
        $params=array(
            'access_token'=>$access_token,
            'oauth_consumer_key'=>io_get_option('open_qq_key')['appid'],
            'openid'=>$openid,
            'format'=>'json'
        );
        $get_user_info = 'https://graph.qq.com/user/get_user_info?' . http_build_query($params);        
        $info = json_decode(wp_remote_retrieve_body(wp_remote_get($get_user_info)),true);
        if ($info['ret']){
            wp_die($info['ret'].': '.$info['msg'], __('获取 QQ 用户信息失败', 'i_theme'), array('response'=>403));
        }
        return $info;
    }
    function use_db($access_token, $openid,$back_url){ 
        $userInfo = $this->get_user_info($access_token, $openid);
        if ($openid) {
            $userInfo['name'] = !empty($userInfo['nickname']) ? $userInfo['nickname'] : '';
        
            $oauth_data = array(
                'type'   => 'qq',
                'openid' => $openid,
                'name' => $userInfo['name'],
                'avatar' => !empty($userInfo['figureurl_qq_2']) ? $userInfo['figureurl_qq_2'] : '',
                'description' => '',
                'getUserInfo' => $userInfo,
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