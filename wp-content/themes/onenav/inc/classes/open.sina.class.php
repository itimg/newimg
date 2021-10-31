<?php 
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-03-01 10:19:02
 * @LastEditors: iowen
 * @LastEditTime: 2021-09-11 13:31:48
 * @FilePath: \onenav\inc\classes\open.sina.class.php
 * @Description:  
 */
class ioLoginSina
{

    public function __construct(){
    }
    function login($appid, $state, $callback) {
        $params=array(
            'response_type' =>'code',
            'client_id'     =>$appid,
            'state'         =>$state,
            'redirect_uri'  =>$callback
        );
        $login_url = 'https://api.weibo.com/oauth2/authorize?'.http_build_query($params);
        wp_redirect($login_url, 302);
        exit;
    }
    function callback($appid,$appkey,$path,$state){
        if ($_REQUEST ['state'] == $state) {
            $params=array(
                'grant_type'    =>'authorization_code',
                'code'          =>$_REQUEST ["code"],
                'client_id'     =>$appid,
                'client_secret' =>$appkey,
                'redirect_uri'  =>$path
            ); 
            $url = 'https://api.weibo.com/oauth2/access_token';
            $response = wp_remote_post($url, $params);
    
            $body = wp_remote_retrieve_body($response);

            if(preg_match('/\{(.*)\}/', $body, $matches)){
                $msg = json_decode(trim($matches[0]));
                if(isset($msg->error)){
                    wp_die($msg->error_code.': '.$msg->error, __('获取微博 Access Token 失败', 'i_theme'), array('response'=>403));
                }
            }else{
                wp_die('微博服务器返回了不正确的响应', __('获取微博 Access Token 失败', 'i_theme'), array('response'=>403));
            }
            //$_SESSION['sina_access_token'] = $msg->access_token;
            //$openid = $msg->uid;
            return array(
                'token'    => $msg->access_token,
                'open_id'  => $msg->uid, 
            ); 
        }else{
            echo ("The state does not match. You may be a victim of CSRF.");
            exit;
        }
    }
    function get_user_info($access_token, $openid) { 
        $get_user_info = "https://api.weibo.com/2/users/show.json?uid=".$openid."&access_token=".$access_token;
        $response = wp_remote_get($get_user_info);
        $body = wp_remote_retrieve_body($response);

        if(preg_match('/\{(.*)\}/', $body, $matches)){
            $msg = json_decode(trim($matches[0]),true);
            if(isset($msg['error'])){
                wp_die($msg['error_code'].': '.$msg['error'], __('获取微博用户信息失败', 'i_theme'), array('response'=>403));
            }
        }else{
            wp_die('微博服务器返回了不正确的响应', __('获取微博用户信息失败', 'i_theme'), array('response'=>403));
        }

        return $msg;
    }
    function get_user_id($access_token) {
        $get_user_id = "https://api.weibo.com/2/account/get_uid.json?access_token=".$access_token;
        $response = wp_remote_get($get_user_id);
        $body = wp_remote_retrieve_body($response);

        if(preg_match('/\{(.*)\}/', $body, $matches)){
            $msg = json_decode(trim($matches[0]));
            if(isset($msg->error)){
                wp_die($msg->error_code.': '.$msg->error, __('获取微博用户 UID 失败', 'i_theme'), array('response'=>403));
            }
        }else{
            wp_die('微博服务器返回了不正确的响应', __('获取微博用户 UID 失败', 'i_theme'), array('response'=>403));
        }
        return $msg;
    }
    function use_db($access_token, $openid, $back_url){
        $userInfo = $this->get_user_info($access_token, $openid);

        if ($openid && $userInfo) {
            $userInfo['name'] = !empty($userInfo['screen_name']) ? $userInfo['screen_name'] : '';
        
            $oauth_data = array(
                'type'   => 'sina',
                'openid' => $openid,
                'name' => $userInfo['name'],
                'avatar' => !empty($userInfo['avatar_large']) ? $userInfo['avatar_large'] : '',
                'description' => '',
                'getUserInfo' => $userInfo,
                'rurl'          => $back_url, 
            );
        
            $oauth_result = io_oauth_update_user($oauth_data);

            io_oauth_login_after_execute($oauth_result,$back_url);
            
        }else{
            wp_die(
                '<h1>' . __( '处理错误' ) . '</h1>' .
                '<p>' . json_encode($userInfo) . '</p>' .
                '<p>openid:' .$openid. '</p>',
                403
            );
            exit;
        }
        wp_safe_redirect(home_url());
        exit;
    } 
}
?>