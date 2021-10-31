<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-03-01 10:19:07
 * @LastEditors: iowen
 * @LastEditTime: 2021-09-29 17:34:33
 * @FilePath: \onenav\templates\tools-header.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; } 
$search_big = io_get_option('search_skin') ? io_get_option('search_skin') : false;
// content 内容
if(is_home() || is_front_page() || is_page_template('template-mininav.php')) {
    $content = '<div id="content" class="content-site customize-site">';
} elseif(is_category() || is_tag() || is_author() ) {
    $content = '<div id="content" class="container is_category">';
} elseif(is_tax("sitetag") || is_tax("favorites")) {
    if(get_term_children(get_queried_object_id(), 'favorites')){
        $content = '<div id="content" class="content-site customize-site">';
    } else {
        $content = '<div id="content" class="container container-lg customize-site">';
    }
} else {
    $content = '<div id="content" class="container container-lg">';
} 

if( io_get_option('search_position') && in_array("home",io_get_option('search_position')) && $search_big && $search_big['search_big'] ){
    if (is_page_template('template-mininav.php') && !get_post_meta( get_the_ID(), 'search_box', true )){
        get_template_part( 'templates/header','banner' );
        echo '<div class="my-2"></div>';
        goto BIG_END;
    }
    //goto miniNav;
    echo '<div class="'.($search_big['big_skin']!="no-bg"?'big-header-banner':'no-big-header').'">';
    get_template_part( 'templates/header','banner' );
    echo '</div>';

    // padding-bottom
    $padding = '';
    if( (is_home() || is_front_page()) && io_get_option('article_module') && $search_big['post_top'] )
        $padding .= 'post-top ';
        
    // gradual
    $gradual = '';
    if($search_big['big_skin']!="no-bg" && $search_big['bg_gradual'])
        $gradual = 'bg-gradual ';

    $style='';
    $gradient = '';
    if($search_big['big_skin']=="css-color"){
        $style = 'style="background-image: linear-gradient(45deg, '.$search_big['search_color']['color-1'].' 0%, '.$search_big['search_color']['color-2'].' 50%, '.$search_big['search_color']['color-3'].' 100%);"';
    }
    if($search_big['big_skin']=="css-img"){
        $style = 'style="background-image: url('.$search_big['search_img'].')"';
    }
    if($search_big['big_skin']=="css-bing")
    {
        $style = 'style="background-image: url('.get_bing_img_cache(rand(0,5),'full').')"';
        if(!$search_big['bg_gradual']) $gradient = '<div class="gradient-linear" style="top:0"></div>';
    }
    

    echo '<div class="header-big '.($search_big['changed_bg']?'':'unchanged').' '. $padding . $gradual . $search_big['big_skin'] .' mb-4" '. $style .'>';
    echo $gradient;

    if($search_big['big_skin']=="canvas-fx"){
        if($search_big['canvas_id']=='custom')
            echo '<iframe class="canvas-bg" scrolling="no" sandbox="allow-scripts allow-same-origin" src="'.$search_big['custom_canvas'].'"></iframe>';
        else
            echo '<iframe class="canvas-bg" scrolling="no" sandbox="allow-scripts allow-same-origin" src="'.get_theme_file_uri('/fx/io-fx'.sprintf("%02d",($search_big['canvas_id']==0?rand(1,17):$search_big['canvas_id'])).'.html').'"></iframe>';
    }
        // 加载搜索模块 
    if(io_get_option('search_position') && in_array("home",io_get_option('search_position')) ){
        get_template_part( 'templates/search/big' );
    } else {
        echo '<div class="no-search my-2 p-1"></div>';
    }
    // 加载公告模块
    if(is_home() || is_front_page()){
        echo '<div class="bulletin-big mx-3 mx-md-0">';
        get_template_part( 'templates/bulletin' );  
        echo '</div>';
    }

    
    echo '</div>';
    BIG_END:
    echo $content;
} else { 
    get_template_part( 'templates/header','banner' );

    echo $content;

    if (is_page_template('template-mininav.php') && !get_post_meta( get_the_ID(), 'search_box', true )){
        echo '<div class="my-2"></div>';
        goto DEF_END;
    }
    // 加载公告模块
    if(is_home() || is_front_page())
        get_template_part( 'templates/bulletin' );  

    // 加载搜索模块 
    if(io_get_option('search_position') && in_array("home",io_get_option('search_position')) ){
        get_template_part( 'templates/search/default' );
    } else {
        echo '<div class="no-search my-2 p-1"></div>';
    }

    DEF_END:
    // 加载广告模块
    get_template_part( 'templates/ads','hometop' );
}
