<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-03-01 10:19:02
 * @LastEditors: iowen
 * @LastEditTime: 2021-09-11 13:34:07
 * @FilePath: \onenav\inc\classes\open.wechat.gzh.class.php
 * @Description: 微信公众号
 */
class GZHException extends \Exception
{
}

class ioLoginWechatGZH
{
    protected $appid;
    protected $secret;
    protected $accessToken;
    public $state;
    public $ticket;
    public $callback;
    public static $getQrcode_count;

    function __construct($appid = null, $appSecret = null, $access_token = null)
    {
        $this->appid       = $appid;
        $this->secret      = $appSecret;

        $accessToken_option = get_option('weixingzh_access_token');
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
        $res = json_decode($this->httpRequest($url), true);

        if (!empty($res['access_token'])) {
            //储存access_token到本地
            $res['expiration_time'] = strtotime('+' . $res['expires_in'] . ' Second');
            update_option('weixingzh_access_token', $res);
            $this->accessToken = $res['access_token'];
            return $res['access_token'];
        } 
        wp_die( __('AccessToken获取失败：', 'i_theme'). json_encode($res), __('获取失败', 'i_theme'), array('response'=>403)); 
        //throw new GZHException('AccessToken获取失败：' . json_encode($res));
    }

    /***
     * POST或GET请求
     * @url 请求url
     * @data POST数据
     * @return
     **/
    private function httpRequest($url, $data = "")
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)) {  //判断是否为POST请求
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    /***
     * 获取openID和unionId
     * @code 微信授权登录返回的code
     * @return
     **/
    public function getOpenIdOrUnionId($code)
    {
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $this->appid . "&secret=" . $this->secret . "&code=" . $code . "&grant_type=authorization_code";
        $data = $this->httpRequest($url);
        return $data;
    }

    /***
     * 发送模板短信
     * @data 请求数据
     * @return
     **/
    public function sendTemplateMessage($data = "")
    {
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $this->accessToken;
        $result = $this->httpRequest($url, $data);
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
        echo $resultStrq;
    }

    /***
     * 生成带参数的二维码|此方式暂未使用
     * 使用scene_id的方式，QR_SCENE为临时的整型参数值
     * @scene_id 自定义参数（整型）
     * @return
     **/
    public function getQrcodeById($repeat = true)
    {
        $state = time() . mt_rand(11, 99);
        $this->state = (int)$state;

        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=" . $this->accessToken;
        $data = array(
            "expire_seconds" => 3600, //二维码的有效时间（1小时）
            "action_name" => "QR_SCENE",
            "action_info" => array("scene" => array("scene_id" => $this->state))
        );
        $result = $this->httpRequest($url, json_encode($data));
        $result = json_decode($result, true);

        if (!empty($result['ticket'])) {
            $this->ticket = $result['ticket'];
            return $result;
        }

        //如果access_token错误则在执行一次
        if (!empty($result['errmsg']) && stristr($result['errmsg'], 'access_token') && $repeat) {
            $this->getAccessToken();
            return $this->getQrcodeById(false);
        }

        wp_die( __('二维码获取失败：', 'i_theme'). json_encode($result), __('获取失败', 'i_theme'), array('response'=>403));
        //throw new GZHException('二维码获取失败：' . json_encode($result));
    }

    /***
     * 生成带参数的二维码
     * 使用 scene_str 方式，QR_STR_SCENE为临时的字符串参数值
     * @scene_str 自定义参数（字符串）
     * @return
     **/
    public function getQrcode($repeat = true)
    {
        $state = time() . mt_rand(11, 99);
        $this->state = $state;
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=" . $this->accessToken;
        $data = array(
            "expire_seconds" => 3600 * 24, //二维码的有效时间（1天）
            "action_name" => "QR_STR_SCENE",
            "action_info" => array("scene" => array("scene_str" => $this->state))
        );
        $result = $this->httpRequest($url, json_encode($data));
        $result = json_decode($result, true);
        if (!empty($result['ticket'])) {
            $this->ticket = $result['ticket'];
            return $result;
        }

        //如果access_token错误则在执行一次
        if (!empty($result['errmsg']) && stristr($result['errmsg'], 'access_token') && $repeat) {
            $this->getAccessToken();
            return $this->getQrcode(false);
        }

        wp_die( __('二维码获取失败：', 'i_theme'). json_encode($result), __('获取失败', 'i_theme'), array('response'=>403));
        //throw new GZHException('二维码获取失败：' . json_encode($result));
    }

    /**
     * 换取二维码
     * @ticket
     * @return
     */
    public function generateQrcode()
    {

        $this->getQrcode();

        return "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=" . $this->ticket;
    }

    /***
     * 通过openId获取用户信息
     * @openId
     * @return
     **/
    public function getUserInfo($openId)
    {

        $url  = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $this->accessToken . "&openid=" . $openId . "&lang=zh_CN";
        $data = json_decode($this->httpRequest($url),true);

        if (!empty($data['openid'])) {
            return $data;
        }

        wp_die( __('用户信息获取失败：', 'i_theme'). json_encode($data), __('获取失败', 'i_theme'), array('response'=>403));
        //throw new GZHException('用户信息获取失败：' . json_encode($data));
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

        if (!empty($data['FromUserName']) && !empty($data['EventKey']) && !empty($data['Event']) && in_array($data['Event'], array('subscribe', 'SCAN'))) {
            $this->callback = $data;
            return $data;
        }
        return false;
    }
    public function use_db($open_id, $back_url){
        $userInfo = $this->getUserInfo($open_id); 

        if (!empty($userInfo['openid'])) {
            $userInfo['name'] = !empty($userInfo['nickname']) ? $userInfo['nickname'] : '';
            $userInfo['avatar'] = !empty($userInfo['headimgurl']) ? $userInfo['headimgurl'] : '';

            $oauth_data = array(
                'type'   => 'wechat_gzh',
                'openid' => $userInfo['openid'],
                'name' => $userInfo['name'],
                'avatar' => $userInfo['avatar'],
                'description' => '',
                'getUserInfo' => $userInfo,
                'rurl'          => $back_url, 
            );

            $oauth_result = io_oauth_update_user($oauth_data);

            io_oauth_login_after_execute($oauth_result,$back_url);
            
        }
    }  
}
