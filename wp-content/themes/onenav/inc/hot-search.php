<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:55:58
 * @LastEditors: iowen
 * @LastEditTime: 2021-10-01 16:31:31
 * @FilePath: \onenav\inc\hot-search.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }  
function hot_search($hot_data){
    $t= mt_rand();
    $type = isset($hot_data['hot_type'])?$hot_data['hot_type']:'api';
    switch ($type){
        case "api":
            $api        = "//ionews.top/api/get.php";
            $key        = iowenKey();
            $title      = $hot_data['name'];
            $ico        = $hot_data['ico'];
            $iframe     = $hot_data['is_iframe'];
            $rule_id    = $hot_data['rule_id'];
            include( get_theme_file_path('/templates/hot/hot-api.php') ); 
            break;
        case "rss":
        case "json":
            $custom_api = get_option( 'io_hot_search_list' )[$type.'_list'];
            $rule_id    = $hot_data['rule_id'];
            $custom_data= $custom_api[$rule_id-1];
            $api        = $custom_data['url'];
            $title      = $custom_data['name'];
            $subtitle   = $custom_data['subtitle'];
            $ico        = $hot_data['ico'];
            $iframe     = $hot_data['is_iframe'];

            $datas_node = $custom_data['datas'];
            $title_node = $custom_data['title'];
            $link_node  = $custom_data['link'];
            $hot_node   = $custom_data['hot'];

            $link_regular = isset($custom_data['link_regular'])?$custom_data['link_regular']:'';
            //$request_type = $custom_data['request_type'];
            //$request_data = isset($custom_data['request_data'])?$custom_data['request_data']:'';
            include( get_theme_file_path('/templates/hot/hot-json.php') ); 
            break;
        default:
            include( get_theme_file_path('/templates/hot/hot-api.php') ); 
    }
}

add_action('wp_ajax_nopriv_get_hot_data', 'io_get_hot_search_data');  
add_action('wp_ajax_get_hot_data', 'io_get_hot_search_data');
function io_get_hot_search_data(){ 
    $_data = wp_remote_get($_REQUEST['url'],array('sslverify' => FALSE));
    if ($_data && ! is_wp_error( $_data )) {
        $_data = wp_remote_retrieve_body($_data) ;
        if($_REQUEST['type']=='json')
            $_data = json_decode($_data, true);
        else
            $_data = json_decode(json_encode(simplexml_load_string($_data, 'SimpleXMLElement', LIBXML_NOCDATA)), true); //将返回的xml转为数组
        
        if(empty($_data)||$_data==''||$_data==null) {
            error(array( "status"=>0,"code"=>202,"data"=> __("没有获取到内容。",'i_theme')));
        }
        error(array( "status"=>1,"data"=> $_data),false,10);
    }elseif(is_wp_error($_data)){
        error(array( "status"=>0,"code"=>$_data->get_error_code(),"data"=> $_data->get_error_message()));
    } else {
        error(array( "status"=>0,"code"=>404,"data"=> __("网络拥堵，请稍后再试。",'i_theme')));
    }
}
// 热搜列表
if(!function_exists('all_topnew_list')){
	function all_topnew_list(){  
        $topsearch = array(
            array(
                'rule_id'       => '100000',
                'name'          => '百度热点',
                'description'   => '实时热点排行榜 https://top.baidu.com/buzz.php?p=top10',
                'ico'           => get_hot_ico('baidu'),
                'is_iframe'     => false,
                'hot_type'      => 'api'
            ),
            array(
                'rule_id'       => '100001',
                'name'          => '36氪人气榜',
                'description'   => '24小时人气阅读 https://www.36kr.com/hot-list/catalog',
                'ico'           => get_hot_ico('36kr'),
                'is_iframe'     => true,
                'hot_type'      => 'api'
            ),
            array(
                'rule_id'       => '100002',
                'name'          => '吾爱破解热度排行榜',
                'description'   => '吾爱破解帖子今日热度排行榜',
                'ico'           => get_hot_ico('wuaipojie'),
                'is_iframe'     => false,
                'hot_type'      => 'api'
            ),
            array(
                'rule_id'       => '100003',
                'name'          => '哔哩哔哩全站排行榜',
                'description'   => '哔哩哔哩全站排行榜 https://www.bilibili.com/v/popular/rank/all',
                'ico'           => get_hot_ico('bilibili'),
                'is_iframe'     => true,
                'hot_type'      => 'api'
            ),
            array(
                'rule_id'       => '100004',
                'name'          => '豆瓣小组',
                'description'   => '豆瓣小组讨论精选',
                'ico'           => get_hot_ico('douban'),
                'is_iframe'     => true,
                'hot_type'      => 'api'
            ),
            array(
                'rule_id'       => '100005',
                'name'          => '历史上的今天',
                'description'   => 'https://hao.360.com/histoday/',
                'ico'           => get_hot_ico('lssdjt'),
                'is_iframe'     => false,
                'hot_type'      => 'api'
            ),
            array(
                'rule_id'       => '100006',
                'name'          => '少数派热门文章',
                'description'   => 'https://sspai.com/tag/热门文章',
                'ico'           => get_hot_ico('sspai'),
                'is_iframe'     => true,
                'hot_type'      => 'api'
            ),
            array(
                'rule_id'       => '100007',
                'name'          => '微博热搜榜',
                'description'   => 'http://s.weibo.com/top/summary',
                'ico'           => get_hot_ico('weibo'),
                'is_iframe'     => true,
                'hot_type'      => 'api'
            ),
            array(
                'rule_id'       => '100008',
                'name'          => '微信搞笑',
                'description'   => '微信搞笑 https://weixin.sogou.com',
                'ico'           => get_hot_ico('wechat'),
                'is_iframe'     => false,
                'hot_type'      => 'api'
            ),
            array(
                'rule_id'       => '100009',
                'name'          => '微信财经迷',
                'description'   => '微信财经迷 https://weixin.sogou.com/pcindex/pc/pc_6/pc_6.html',
                'ico'           => get_hot_ico('wechat'),
                'is_iframe'     => false,
                'hot_type'      => 'api'
            ),
            array(
                'rule_id'       => '100010',
                'name'          => '微信八卦精',
                'description'   => '微信八卦精 https://weixin.sogou.com/pcindex/pc/pc_4/pc_4.html',
                'ico'           => get_hot_ico('wechat'),
                'is_iframe'     => false,
                'hot_type'      => 'api'
            ),
            array(
                'rule_id'       => '100011',
                'name'          => '微信热搜词',
                'description'   => '微信热搜词 https://weixin.sogou.com/',
                'ico'           => get_hot_ico('wechat'),
                'is_iframe'     => false,
                'hot_type'      => 'api'
            ),
            array(
                'rule_id'       => '100012',
                'name'          => '微信热门',
                'description'   => '微信热门 https://weixin.sogou.com/',
                'ico'           => get_hot_ico('wechat'),
                'is_iframe'     => false,
                'hot_type'      => 'api'
            ),
            array(
                'rule_id'       => '100013',
                'name'          => '微信读书新书榜',
                'description'   => '微信读书新书榜 https://weread.qq.com/web/category/newbook',
                'ico'           => get_hot_ico('weread'),
                'is_iframe'     => false,
                'hot_type'      => 'api'
            ),
            array(
                'rule_id'       => '100014',
                'name'          => '微信读书更新榜',
                'description'   => '更新榜 https://weread.qq.com/web/category/novel_male_series',
                'ico'           => get_hot_ico('weread'),
                'is_iframe'     => false,
                'hot_type'      => 'api'
            ),
            array(
                'rule_id'       => '100015',
                'name'          => '知乎热度',
                'description'   => '知乎热度 https://www.zhihu.com/hot',
                'ico'           => get_hot_ico('zhihu'),
                'is_iframe'     => false,
                'hot_type'      => 'api'
            ),
            array(
                'rule_id'       => '100016',
                'name'          => '电商报7X24h快讯',
                'description'   => '7X24h快讯 https://www.dsb.cn/news',
                'ico'           => get_hot_ico('dsb'),
                'is_iframe'     => true,
                'hot_type'      => 'api'
            ),
            array(
                'rule_id'       => '100017',
                'name'          => '什么值得买',
                'description'   => '什么值得买精选好价 https://www.smzdm.com/jingxuan/',
                'ico'           => get_hot_ico('smzdm'),
                'is_iframe'     => true,
                'hot_type'      => 'api'
            ),
            array(
                'rule_id'       => '100018',
                'name'          => '豆瓣电影排行榜',
                'description'   => '豆瓣电影排行榜，豆瓣新片榜',
                'ico'           => get_hot_ico('douban'),
                'is_iframe'     => true,
                'hot_type'      => 'api'
            ),
            array(
                'rule_id'       => '100020',
                'name'          => '抖音热点榜',
                'description'   => '抖音热点榜 https://www.iesdouyin.com/share/billboard/',
                'ico'           => get_hot_ico('douyin'),
                'is_iframe'     => true,
                'hot_type'      => 'api'
            ),
            array(
                'rule_id'       => '100038',
                'name'          => '抖音今日热门视频',
                'description'   => '抖音今日热门视频 https://www.iesdouyin.com/share/billboard/',
                'ico'           => get_hot_ico('douyin'),
                'is_iframe'     => true,
                'hot_type'      => 'api'
            ),
            array(
                'rule_id'       => '100027',
                'name'          => 'IT之家资讯热榜',
                'description'   => 'IT之家资讯热榜 https://www.ithome.com',
                'ico'           => get_hot_ico('ithome'),
                'is_iframe'     => true,
                'hot_type'      => 'api'
            ),
            array(
                'rule_id'       => '100028',
                'name'          => 'IT之家最新资讯',
                'description'   => 'IT之家IT资讯最新 https://it.ithome.com/',
                'ico'           => get_hot_ico('ithome'),
                'is_iframe'     => true,
                'hot_type'      => 'api'
            ),
            array(
                'rule_id'       => '100029',
                'name'          => '百度贴吧热议榜',
                'description'   => '百度贴吧热议榜 http://tieba.baidu.com/hottopic/browse/topicList?res_type=1',
                'ico'           => get_hot_ico('baidu'),
                'is_iframe'     => false,
                'hot_type'      => 'api'
            ),
            array(
                'rule_id'       => '100030',
                'name'          => '虎扑步行街热帖',
                'description'   => '虎扑步行街热帖 https://bbs.hupu.com/all-gambia',
                'ico'           => get_hot_ico('hupu'),
                'is_iframe'     => true,
                'hot_type'      => 'api'
            ),
            array(
                'rule_id'       => '100036',
                'name'          => '哔哩哔哩综合热门',
                'description'   => '综合热门 https://www.bilibili.com/v/popular/all',
                'ico'           => get_hot_ico('bilibili'),
                'is_iframe'     => true,
                'hot_type'      => 'api'
            ),
            array(
                'rule_id'       => '100037',
                'name'          => '哔哩哔哩入站必刷',
                'description'   => '入站必刷 https://www.bilibili.com/v/popular/history',
                'ico'           => get_hot_ico('bilibili'),
                'is_iframe'     => true,
                'hot_type'      => 'api'
            ),
        );
        $topsearch = apply_filters('io_topnew_list_filters', $topsearch);
        return $topsearch;
    }
}

function get_hot_ico($ico_name){
    return get_theme_file_uri('/images/hotico/'.$ico_name.'.png');
}
//https://feed.mix.sina.com.cn/api/roll/get?pageid=153&lid=2509&k=&num=50&page=1&r=0.466137586907422&callback=jQuery11120153213739791773_1633014950125&_=1633014950127
//http://zhibo.sina.com.cn/api/zhibo/feed?callback=jQuery1112042151262348278307_1583126404217&page=1&page_size=20&zhibo_id=152&tag_id=0&dire=f&dpc=1&pagesize=20&id=1638768&type=0&_=1583126404220
//http://zhibo.sina.com.cn/api/zhibo/feed?callback=jQuery1112042151262348278307_1583126404217&page=1&page_size=20&zhibo_id=152&tag_id=0&dire=f&dpc=1&pagesize=20&id=1638768&type=0&_=1583126404221
//http://zhibo.sina.com.cn/api/zhibo/feed?page=1&page_size=20&zhibo_id=152&tag_id=0&dire=f&dpc=1&pagesize=20&_=1583119028651
//
