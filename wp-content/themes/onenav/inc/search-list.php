<?php
/*!
 * Theme Name:One Nav
 * Theme URI:https://www.iotheme.cn/
 * Author:iowen
 * Author URI:https://www.iowen.cn/
 */
if ( ! defined( 'ABSPATH' ) ) { exit; } 
/**
 * 搜索工具搜索引擎列表
 * 修改后如果前台不显示选单，请清空浏览器缓存
 * 如果还是不显示，请检测对应 default 对应的 id 是否存在
 * id 必须唯一
 * 修改默认项请设置“常用”里 default 的值
 * 排序请调整先后顺序
 * 暂只支持添加“keyname=key”结构的搜索引擎，如：https://www.baidu.com/s?wd=
 */
$search_list =apply_filters('io_search_list_filters', array(
    array(
        'id'      => 'group-a',
        'name'    => __('常用','i_theme'),
        'default' => 'type-baidu',
        'list'    => array(
            array(
                'name'        => __('百度','i_theme'),
                'placeholder' => __('百度一下','i_theme'),
                'id'          => 'type-baidu',
                'url'         => 'https://www.baidu.com/s?wd=%s%',
            ),
            array(
                'name'        => 'Google',
                'placeholder' => __('谷歌两下','i_theme'),
                'id'          => 'type-google',
                'url'         => 'https://www.google.com/search?q=%s%',
            ),
            array(
                'name'        => __('站内','i_theme'),
                'placeholder' => __('站内搜索','i_theme'),
                'id'          => 'type-zhannei',
                'url'         => esc_url(home_url()) .'/?post_type=sites&s=%s%',
            ),
            array(
                'name'        => __('淘宝','i_theme'),
                'placeholder' => __('淘宝','i_theme'),
                'id'          => 'type-taobao',
                'url'         => 'https://s.taobao.com/search?q=%s%',
            ),
            array(
                'name'        => 'Bing',
                'placeholder' => __('微软Bing搜索','i_theme'),
                'id'          => 'type-bing',
                'url'         => 'https://cn.bing.com/search?q=%s%',
            ),
        )
    ),
    array(
        'id'      => 'group-b',
        'name'    => __('搜索','i_theme'),
        'default' => 'type-baidu1',
        'list'    => array(
            array(
                'name'        => __('百度','i_theme'),
                'placeholder' => __('百度一下','i_theme'),
                'id'          => 'type-baidu1',
                'url'         => 'https://www.baidu.com/s?wd=%s%',
            ),
            array(
                'name'        => 'Google',
                'placeholder' => __('谷歌两下','i_theme'),
                'id'          => 'type-google1',
                'url'         => 'https://www.google.com/search?q=%s%',
            ),
            array(
                'name'        => '360',
                'placeholder' => __('360好搜','i_theme'),
                'id'          => 'type-360',
                'url'         => 'https://www.so.com/s?q=%s%',
            ),
            array(
                'name'        => __('搜狗','i_theme'),
                'placeholder' => __('搜狗搜索','i_theme'),
                'id'          => 'type-sogo',
                'url'         => 'https://www.sogou.com/web?query=%s%',
            ),
            array(
                'name'        => 'Bing',
                'placeholder' => __('微软Bing搜索','i_theme'),
                'id'          => 'type-bing1',
                'url'         => 'https://cn.bing.com/search?q=%s%',
            ),
            array(
                'name'        => __('神马','i_theme'),
                'placeholder' => __('UC移动端搜索','i_theme'),
                'id'          => 'type-sm',
                'url'         => 'https://yz.m.sm.cn/s?q=%s%',
            ),
        )
    ),
    array(
        'id'      => 'group-c',
        'name'    => __('工具','i_theme'),
        'default' => 'type-br',
        'list'    => array(
            array(
                'name'        => __('权重查询','i_theme'),
                'placeholder' => __('请输入网址(不带https://)','i_theme'),
                'id'          => 'type-br',
                'url'         => 'https://rank.chinaz.com/all/%s%',
            ),
            array(
                'name'        => __('友链检测','i_theme'),
                'placeholder' => __('请输入网址(不带https://)','i_theme'),
                'id'          => 'type-links',
                'url'         => 'https://link.chinaz.com/%s%',
            ),
            array(
                'name'        => __('备案查询','i_theme'),
                'placeholder' => __('请输入网址(不带https://)','i_theme'),
                'id'          => 'type-icp',
                'url'         => 'https://icp.aizhan.com/%s%',
            ),
            array(
                'name'        => __('PING检测','i_theme'),
                'placeholder' => __('请输入网址(不带https://)','i_theme'),
                'id'          => 'type-ping',
                'url'         => 'https://ping.chinaz.com/%s%',
            ),
            array(
                'name'        => __('死链检测','i_theme'),
                'placeholder' => __('请输入网址(不带https://)','i_theme'),
                'id'          => 'type-404',
                'url'         => 'https://tool.chinaz.com/Links/?DAddress=%s%',
            ),
            array(
                'name'        => __('关键词挖掘','i_theme'),
                'placeholder' => __('请输入关键词','i_theme'),
                'id'          => 'type-ciku',
                'url'         => 'https://www.ciku5.com/s?wd=%s%',
            ),
        )
    ),
    array(
        'id'      => 'group-d',
        'name'    => __('社区','i_theme'),
        'default' => 'type-zhihu',
        'list'    => array(
            array(
                'name'        => __('知乎','i_theme'),
                'placeholder' => __('知乎','i_theme'),
                'id'          => 'type-zhihu',
                'url'         => 'https://www.zhihu.com/search?type=content&q=%s%',
            ),
            array(
                'name'        => __('微信','i_theme'),
                'placeholder' => __('微信','i_theme'),
                'id'          => 'type-wechat',
                'url'         => 'https://weixin.sogou.com/weixin?type=2&query=%s%',
            ),
            array(
                'name'        => __('微博','i_theme'),
                'placeholder' => __('微博','i_theme'),
                'id'          => 'type-weibo',
                'url'         => 'https://s.weibo.com/weibo/%s%',
            ),
            array(
                'name'        => __('豆瓣','i_theme'),
                'placeholder' => __('豆瓣','i_theme'),
                'id'          => 'type-douban',
                'url'         => 'https://www.douban.com/search?q=%s%',
            ),
            array(
                'name'        => __('搜外问答','i_theme'),
                'placeholder' => __('SEO问答社区','i_theme'),
                'id'          => 'type-why',
                'url'         => 'https://ask.seowhy.com/search/?q=%s%',
            ),
        )
    ),
    array(
        'id'      => 'group-e',
        'name'    => __('生活','i_theme'),
        'default' => 'type-taobao1',
        'list'    => array(
            array(
                'name'        => __('淘宝','i_theme'),
                'placeholder' => __('淘宝','i_theme'),
                'id'          => 'type-taobao1',
                'url'         => 'https://s.taobao.com/search?q=%s%',
            ),
            array(
                'name'        => __('京东','i_theme'),
                'placeholder' => __('京东','i_theme'),
                'id'          => 'type-jd',
                'url'         => 'https://search.jd.com/Search?keyword=%s%',
            ),
            array(
                'name'        => __('下厨房','i_theme'),
                'placeholder' => __('下厨房','i_theme'),
                'id'          => 'type-xiachufang',
                'url'         => 'https://www.xiachufang.com/search/?keyword=%s%',
            ),
            array(
                'name'        => __('香哈菜谱','i_theme'),
                'placeholder' => __('香哈菜谱','i_theme'),
                'id'          => 'type-xiangha',
                'url'         => 'https://www.xiangha.com/so/?q=caipu&s=%s%',
            ),
            array(
                'name'        => '12306',
                'placeholder' => '12306',
                'id'          => 'type-12306',
                'url'         => 'https://www.12306.cn/?%s%',
            ),
            array(
                'name'        => __('快递100','i_theme'),
                'placeholder' => __('快递100','i_theme'),
                'id'          => 'type-kd100',
                'url'         => 'https://www.kuaidi100.com/?%s%',
            ),
            array(
                'name'        => __('去哪儿','i_theme'),
                'placeholder' => __('去哪儿','i_theme'),
                'id'          => 'type-qunar',
                'url'         => 'https://www.qunar.com/?%s%',
            ),
        )
    ),
    array(
        'id'      => 'group-f',
        'name'    => __('求职','i_theme'),
        'default' => 'type-zhaopin',
        'list'    => array(
            array(
                'name'        => __('智联招聘','i_theme'),
                'placeholder' => __('智联招聘','i_theme'),
                'id'          => 'type-zhaopin',
                'url'         => 'https://sou.zhaopin.com/jobs/searchresult.ashx?kw=%s%',
            ),
            array(
                'name'        => __('前程无忧','i_theme'),
                'placeholder' => __('前程无忧','i_theme'),
                'id'          => 'type-51job',
                'url'         => 'https://search.51job.com/?%s%',
            ),
            array(
                'name'        => __('拉勾网','i_theme'),
                'placeholder' => __('拉勾网','i_theme'),
                'id'          => 'type-lagou',
                'url'         => 'https://www.lagou.com/jobs/list_%s%',
            ),
            array(
                'name'        => __('猎聘网','i_theme'),
                'placeholder' => __('猎聘网','i_theme'),
                'id'          => 'type-liepin',
                'url'         => 'https://www.liepin.com/zhaopin/?key=%s%',
            ),
        )
    ),

));
