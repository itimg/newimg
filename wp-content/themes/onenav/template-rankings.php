<?php
/*
Template Name: 排行榜
*/

get_header(); ?>


<?php 
get_template_part( 'templates/sidebar','nav' );
?>
<div class="main-content flex-fill">
    
<?php get_template_part( 'templates/header','banner' ); ?>

<div id="content" class="container container-lg my-3 my-md-4">
    <?php get_template_part( 'templates/ads','homesecond' ); ?>
    <?php 
    $content_list=array(
        'sites' => array(
            'title' => __('网址热度排行榜','i_theme'),
            'type'  => 'sites',
            'list'  => array(
                array(
                    'title' => __('网址今日点击榜','i_theme'),
                    'time'  => 'today'
                ),
                array(
                    'title' => __('网址昨日点击榜','i_theme'),
                    'time'  => 'yesterday'
                ),
                array(
                    'title' => __('网址本月点击榜','i_theme'),
                    'time'  => 'month'
                ),
                array(
                    'title' => __('网址点击总榜','i_theme'),
                    'time'  => 'all'
                )
            )
        ),
        'app' => array(
            'title' => __('软件热度排行榜','i_theme'),
            'type'  => 'app',
            'list'  => array(
                array(
                    'title' => __('软件今日点击榜','i_theme'),
                    'time'  => 'today'
                ),
                array(
                    'title' => __('软件昨日点击榜','i_theme'),
                    'time'  => 'yesterday'
                ),
                array(
                    'title' => __('软件本月点击榜','i_theme'),
                    'time'  => 'month'
                ),
                array(
                    'title' => __('软件点击总榜','i_theme'),
                    'time'  => 'all'
                )
            )
        ),
        'book' => array(
            'title' => __('书籍热度排行榜','i_theme'),
            'type'  => 'book',
            'list'  => array(
                array(
                    'title' => __('书籍今日点击榜','i_theme'),
                    'time'  => 'today'
                ),
                array(
                    'title' => __('书籍昨日点击榜','i_theme'),
                    'time'  => 'yesterday'
                ),
                array(
                    'title' => __('书籍本月点击榜','i_theme'),
                    'time'  => 'month'
                ),
                array(
                    'title' => __('书籍点击总榜','i_theme'),
                    'time'  => 'all'
                )
            )
        ),
        'post' => array(
            'title' => __('资讯热度排行榜','i_theme'),
            'type'  => 'post',
            'list'  => array(
                array(
                    'title' => __('资讯今日点击榜','i_theme'),
                    'time'  => 'today'
                ),
                array(
                    'title' => __('资讯昨日点击榜','i_theme'),
                    'time'  => 'yesterday'
                ),
                array(
                    'title' => __('资讯本月点击榜','i_theme'),
                    'time'  => 'month'
                ),
                array(
                    'title' => __('资讯点击总榜','i_theme'),
                    'time'  => 'all'
                )
            )
        ),
    );
    $show_list = array();
    if(io_get_option('leader_board'))
        $show_list  = get_post_meta( get_the_ID(), '_show-list', true )['enabled'];//array('book','sites','post');
    $count      = get_post_meta( get_the_ID(), '_show-count', true );
    foreach($show_list as $k=>$v){
        $c = $content_list[$k];
        echo '<div class="card d-none d-md-block"><div class="card-body py-2 text-center">'.$c['title'].'</div></div>';
        echo '<div class="ranking position-relative"><div id="'.$c['type'].'-tab" class="row mx-n2 ranking-tab-body">';
        $index = 1;
        foreach($c['list'] as $list){
            echo'<div id="'.$c['type'].'-'.$index.'" class="px-2 col-12 col-md-6 col-lg-3 d-none d-md-flex '.($index == 1?'d-block':'').'">
            <div class="card flex-fill">
                <div class="card-header">
                    <div class="text-center tab-title">'.$list['title'].'</div>
                </div>
                <div class="card-body">';
                    $my_post=(io_get_post_rankings($list['time'],$c['type'],$count));
                    if($my_post){
                        echo'<ul class="m-0 p-0">';
                        foreach($my_post as $_post){
                            $is_go = $_post['is_go']?' is-views':'';
                            echo '<li class="d-flex mb-3">
                                <div class="overflowClip_1 mr-1">
                                    <span class="hot-rank hot-rank-'.$_post['index'].' text-xs text-center">'.$_post['index'].'</span>
                                    <a href="'.($_post['is_go']?go_to($_post['url']):$_post['url']).'" target="_blank" '.($_post['is_go']?nofollow($_post['url']):'').' class="ml-1 text-sm'.$is_go.'" data-id="'.$_post['id'].'">'.$_post['title'].'</a>
                                </div>
                                <span class="ml-auto text-sm">'.$_post['views'].'</span>
                            </li>';
                        }
                        echo'</ul>';
                    }else{
                        echo'<div class="d-flex h-100"><div class="empty-list">
                        <i class="iconfont icon-nothing1"></i>
                        </div></div>';
                    }
            echo'</div></div></div>';
            $index ++;
        }
        echo '</div>
        <div class="ranking-tab-button position-absolute w-100 t-0 d-block d-md-none">
            <div class="d-flex">
                <div class="ml-3 ranking-title">'.$c['title'].'</div>
                <div class="ml-auto d-flex">
                    <a href="javascript:" class="tab-button text-sm active" data-id="#'.$c['type'].'-1" data-tab="#'.$c['type'].'-tab">'.__('今','i_theme').'</a>
                    <a href="javascript:" class="tab-button text-sm" data-id="#'.$c['type'].'-2" data-tab="#'.$c['type'].'-tab">'.__('昨','i_theme').'</a>
                    <a href="javascript:" class="tab-button text-sm" data-id="#'.$c['type'].'-3" data-tab="#'.$c['type'].'-tab">'.__('月','i_theme').'</a>
                    <a href="javascript:" class="tab-button text-sm" data-id="#'.$c['type'].'-4" data-tab="#'.$c['type'].'-tab">'.__('总','i_theme').'</a>
                </div>
            </div>
        </div></div>';
    }
    if(empty($show_list)){
        echo'<div class=""><div class="nothing mb-4">请开启统计:主题设置->统计浏览->按天记录统计数据</div></div>';
    }
    ?> 
</div>
<script>
    $('a.tab-button').click(function() {
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
        $($(this).data('id')).siblings().removeClass('d-block');
        $($(this).data('id')).addClass('d-block');
    });
</script>
<?php get_footer(); ?>
