<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:56:02
 * @LastEditors: iowen
 * @LastEditTime: 2021-07-28 17:13:40
 * @FilePath: \onenav\templates\tools-hotsearch.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; } 
if(is_page()){
    $hotlist = get_post_meta(get_the_ID(), 'hot_new', true);   
}else{
    $hotlist= io_get_option('hot_new');
}
if(!empty($hotlist) && is_array($hotlist)){
    echo '<div class="overflow-x-auto hot-search-panel mb-3" style="margin:0 -.5rem;padding:0 .5rem"><div class="row row-sm hot-search">';
    
    $list_int=0;
    foreach ($hotlist as $hot) {
        echo'<div class="col col-md col-lg">';
        hot_search($hot);
        echo'</div>';
        $list_int++;
        if($list_int>=5){
            break;
        } 
    } 
	if($list_int>0) echo '<style>@media screen and (max-width: 1199.98px){.hot-search {min-width: '.(300*($list_int)).'px;}}</style>';
    echo '</div></div>';
}
