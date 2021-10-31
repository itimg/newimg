<?php 
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-03-01 10:19:02
 * @LastEditors: iowen
 * @LastEditTime: 2021-09-12 17:46:15
 * @FilePath: \onenav\inc\classes\open.wechat.dyh.class.php
 * @Description: 微信订阅号
 */
class ioLoginWechatDYH {

    protected $appid;
    protected $secret;
    protected $accessToken; 
    public $callback;

    function __construct($appid = null, $appSecret = null, $access_token = null)
    {
        $this->appid       = $appid;
        $this->secret      = $appSecret;

        $accessToken_option = get_option('weixindyh_access_token');
        $new_time = strtotime('+300 Second'); //获取现在时间加5分钟

        if (!empty($accessToken_option['access_token']) && $accessToken_option['expiration_time'] > $new_time) {
            $this->accessToken = $accessToken_option['access_token'];
        } else {
            $this->accessToken = $this->getAccessToken();
        }
    }

    /***
     * 获取access_token
     * token的有效时间为2小时，这里可以做下处理，提高效率不用每次都去获取，
     * 将token存储到缓存中，每2小时更新一下，然后从缓存取即可
     * @return
     **/
    private function getAccessToken()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $this->appid . "&secret=" . $this->secret;
        $res = json_decode($this->get_curl($url), true);

        if (!empty($res['access_token'])) {
            //储存access_token到本地
            $res['expiration_time'] = strtotime('+' . $res['expires_in'] . ' Second');
            update_option('weixindyh_access_token', $res);
            $this->accessToken = $res['access_token'];
            return $res['access_token'];
        } 
        wp_die( __('AccessToken获取失败：', 'i_theme'). json_encode($res), __('获取失败', 'i_theme'), array('response'=>403)); 
        //throw new GZHException('AccessToken获取失败：' . json_encode($res));
    } 

    /***
     * 回调函数
     **/
    public function callback()
    {
        $callbackXml = file_get_contents('php://input'); //获取返回的xml
        //下面是返回的xml
        //<xml><ToUserName><![CDATA[gh_f6b4da984c87]]></ToUserName> //微信公众号的微信号
        //<FromUserName><![CDATA[oJxRO1Y2NgWJ9gMDyE3LwAYUNdAs]]></FromUserName> //openid用于获取用户信息，做登录使用
        //<CreateTime>1531130986</CreateTime> //回调时间
        //<MsgType><![CDATA[event]]></MsgType>
        //<Event><![CDATA[SCAN]]></Event>
        //<EventKey><![CDATA[lrfun1531453236]]></EventKey> //上面自定义的参数（scene_str）
        //<Ticket><![CDATA[gQF57zwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyY2ljbjB3RGtkZWwxbExLY3hyMVMAAgTvM0NbAwSAOgkA]]></Ticket> //换取二维码的ticket
        //</xml>

        $data = json_decode(json_encode(simplexml_load_string($callbackXml, 'SimpleXMLElement', LIBXML_NOCDATA)), true); //将返回的xml转为数组

        if (!empty($data['FromUserName'])) {
            $this->callback = $data;
            return $data;
        }
        return false;
    }
    public function responseMsg() { 

        $postObj = $this->callback; 
        //$scene_id = str_replace("qrscene_", "", $postObj->EventKey);

        date_default_timezone_set('Asia/Shanghai');
        $openid = esc_sql($postObj['FromUserName']); 
        

        if($postObj['MsgType'] == 'text'){
            if($postObj['Content'] == '登录' || $postObj['Content'] == '登陆' || $postObj['Content'] == '绑定'){

                $code = rand(100000,999999); 
                set_transient( $code, $openid, 5 * MINUTE_IN_SECONDS ); 

                $content = "验证码：".$code."，5分钟内（".date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." +5 minutes"))."）有效，过期后请重新发送“登录”二字获取";

            }else{
                //$content = "有点不明白您的意思，可发送“登录”二字获取验证码。"; 
            }

            echo $this->sendMessage($content);
        }else{
            if(( !empty($postObj['Event']) && in_array($postObj['Event'], array('subscribe', 'SCAN'))) || ($postObj['Event'] == 'click' && $postObj['EventKey'] == 'io_ws_login')){

                $code = rand(100000,999999); 
                set_transient( $code, $openid, 5 * MINUTE_IN_SECONDS ); 

                $content = "验证码：".$code."，5分钟内（".date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." +5 minutes"))."）有效，过期后请重新发送“登录”二字获取";

                echo $this->sendMessage($content);
            }
        }  
    }

    /**
     * 通过openId获取用户信息
     * @openId
     * @return
     */
    public function getUserInfo($openid) {
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=". $this->accessToken."&openid=".$openid."&lang=zh_CN";
        $res = $this->get_curl($url);
        return json_decode($res,true);
    } 

    public function get_curl($url) {
        $ch = curl_init();
        $headers[] = 'Accept-Charset:utf-8';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }  

    /***
     * 回复消息
     * @msg 消息内容
     * @return
     **/
    public function sendMessage($msg = "")
    {
        $callback = $this->callback;

        if (empty($callback['FromUserName']) || empty($callback['ToUserName']) || !$msg) return;
        $time = time();    //时间戳
        $msgtype = 'text'; //消息类型：文本
        $textTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            </xml>";

        $fromUsername = $callback['FromUserName']; //请求消息的用户
        $toUsername = $callback['ToUserName'];    //"我"的公众号id
        $resultStrq = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgtype, $msg);
        return $resultStrq;
    }

    public function use_db($open_id,$back_url){
        
        $userInfo = $this->getUserInfo($open_id); 

        if ($open_id) {
            $userInfo['name']   = !isset($userInfo['nickname']) ? $userInfo['nickname'] : '';
            $userInfo['avatar'] = !isset($userInfo['headimgurl']) ? $userInfo['headimgurl'] : '';

            $oauth_data = array(
                'type'          => 'wechat_dyh',
                'openid'        => $open_id,
                'name'          => $userInfo['name'],
                'avatar'        => $userInfo['avatar'],
                'description'   => '',
                'getUserInfo'   => $userInfo,
                'rurl'          => $back_url, 
            );

            $oauth_result = io_oauth_update_user($oauth_data,true);

            //注册完成后需前端js跳转到绑定页面
            if ($oauth_result['error']) { 
                wp_die('处理出错：' . (isset($oauth_result['msg']) ? $oauth_result['msg'] : ''));
                exit;
            } else {
                $rurl = !empty($back_url) ? $back_url : $oauth_result['redirect_url'];
                if(io_get_option('user_center') && $oauth_result['bind'] && io_get_option('bind_email')!='null'){
                    return array(
                        'status'    => true,
                        'rurl'      => home_url('/login/?action=bind').'&redirect_to='.$rurl,
                    );
                }else{
                    return array(
                        'status'    => true,
                        'rurl'      => $rurl,
                    );
                }
                exit;
            }
        }
        return false;
        exit();
    }
}
