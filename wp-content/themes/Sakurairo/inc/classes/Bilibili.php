<?php

namespace Sakura\API;

class Bilibili
{
    private $uid;
    private $cookies;

    public function __construct()
    {
        $this->uid = iro_opt('bilibili_id');
        $this->cookies = iro_opt('bilibili_cookie');
    }
    /**
     * 获取Bilibili用户追番列表
     * @param integer $type 
     * @param integer $page 页数
     * @author siroi <mrgaopw@hotmail.com>
     * @author KotoriK
     */
    function fetch_api(int $type, int $page = 1)
    {
        $uid = $this->uid;
        $cookies = $this->cookies;
        $url = "https://api.bilibili.com/x/space/bangumi/follow/list?type=$type&pn=$page&ps=15&follow_status=0&vmid=$uid";
        $args = array(
            'headers' => array(
                'Cookie' => $cookies,
                'Host' => 'api.bilibili.com',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.97'
            )
        );
        $response = wp_remote_get($url, $args);
        return json_decode($response["body"], true);
    }

    public function get_bgm_items($page = 1)
    {
        $resp = $this->fetch_api(1, $page);
        $code = $resp["code"];
        switch ($code) {
            case 0: {
                    $bgm = $resp['data'];
                    $totalpage = $bgm["total"] / 15;
                    if ($totalpage - $page < 0) {
                        $next = '<span>共追番' . $bgm["total"] . '部，继续加油吧！٩(ˊᗜˋ*)و</span>';
                    } else {
                        $next = Bilibili::anchor_pagination_next(rest_url('sakura/v1/bangumi/bilibili') . '?page=' . ++$page);
                    }
                    $lists = $bgm["list"];
                    $html = "";
                    foreach ((array)$lists as $item) {
                        $percent = Bilibili::get_percent($item);
                            $html .= Bilibili::bangumi_item($item, $percent);
                    }
                    $html .= '</div><br><div id="bangumi-pagination">' . $next . '</div>';
                    return $html;
                }
            case 53013: //用户隐私设置未公开
                //TODO:制作错误页面
                return '<div>博主似乎隐藏了追番列表。</div>';
        }
    }

    public function get_bfv_items($page = 1)
    {
        $resp = $this->fetch_api(2, $page);
        $code = $resp["code"];
        switch ($code) {
            case 0: {
                    $bgm = $resp['data'];
                    $totalpage = $bgm["total"] / 15;
                    if ($totalpage - $page < 0) {
                        $next = '<span>共追剧' . $bgm["total"] . '部，继续加油吧！٩(ˊᗜˋ*)و</span>';
                    } else {
                        $next = Bilibili::anchor_pagination_next(rest_url('sakura/v1/bangumi/bilibili-ctp') . '?page=' . ++$page);
                    }
                    $lists = $bgm["list"];
                    $html = "";
                    foreach ((array)$lists as $item) {
                        $percent = Bilibili::get_percent($item);
                        $html .= Bilibili::bangumi_item($item, $percent);
                    }
                    $html .= '</div><br><div id="bangumi-pagination">' . $next . '</div>';
                    return $html;
                }
        }
    }
    private static function anchor_pagination_next(string $href)
    {
        return '<a class="bangumi-next" data-href="' . $href . '"><i class="fa fa-bolt" aria-hidden="true"></i> NEXT </a>';
    }
    private static function bangumi_item(array $item, $percent)
    {
        //in_array('index_show','new_ep')
        return '<div class="column">' .
            '<a class="bangumi-item" href="https://bangumi.bilibili.com/anime/' . $item['season_id'] . '/" target="_blank" rel="nofollow">'
            . '<img class="bangumi-image" src="' . str_replace('http://', 'https://', $item['cover']) . '"/>' .
            '<div class="bangumi-info">' .
            '<h3 class="bangumi-title" title="' . $item['title'] . '">' . $item['title'] . '</h2>'
            . '<div class="bangumi-summary"> ' . $item['evaluate'] . ' </div>' .
            '<div class="bangumi-status">'
            . '<div class="bangumi-status-bar" style="width: ' . $percent . '%"></div>'
            . '<p>' . ($item['new_ep']['index_show'] ?? '').  '</p>'
            . '</div>'
            . '</div>'
            . '</a>'
            . '</div>';
    }
    private static function get_percent(array $item)
    {
        $percent = 0;
        if (preg_match('/看完/m', $item["progress"], $matches_finish)) {
            $percent = 100;
        } else {
            preg_match('/第(\d+)./m', $item['progress'], $matches_progress);
            preg_match('/第(\d+)./m', $item["new_ep"]['index_show'] ?? null, $matches_new);
            if (isset($matches_progress[1])) {
                $progress = is_numeric($matches_progress[1]) ? $matches_progress[1] : 0;
            } else {
                $progress = 0;
            }
            $total = (isset($matches_new[1]) && is_numeric($matches_new[1])) ? $matches_new[1] : $item['total_count'];
            if ($total < 0) {
                //电影类剧集$total可能得到0
                $percent = 0;
            } else {
                $percent = $progress / $total * 100;
            }
        }
        return $percent;
    }
}
