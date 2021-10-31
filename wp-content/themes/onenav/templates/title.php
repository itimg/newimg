<?php
/*!
 * Theme Name:One Nav
 * Theme URI:https://www.iotheme.cn/
 * Author:iowen
 * Author URI:https://www.iowen.cn/
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$linker = io_get_option('seo_linker');
$title = "";
$title_after = $linker . get_bloginfo('name');
$keywords="";
$description="";
$type = 'article';
$url = home_url();
$img = io_get_option("og_img")?:get_theme_file_uri('/screenshot.jpg');
if( (is_home() || is_front_page()) ){
	$title = get_bloginfo('name') . $linker . get_bloginfo( 'description');
	$title_after = '';
	$keywords=io_get_option('seo_home_keywords');
	$description=io_get_option('seo_home_desc');
	$type = 'website';
}
if( is_search() ) {
	$title = sprintf( __('%s的搜索结果', 'i_theme'), '&#8220;'. htmlspecialchars($s) .'&#8221;' );
	$keywords= $s.','.get_bloginfo('name');
	$description= io_get_option('seo_home_desc');
} 
if( is_single() || is_page() ) {
	$id=get_the_ID();
	$title = get_post_meta($id, '_seo_title', true)?:get_the_title();
	$tag = '';
	$tags=get_post_meta($id, '_seo_metakey', true)?:get_the_tags();
	
	$excerpt = get_post_meta($id, '_seo_desc', true);
	
	$url = get_permalink();
	$img = io_theme_get_thumb();
	if( get_post($id)->post_type == 'sites' ){
		$tags=get_post_meta($id, '_seo_metakey', true)?:get_the_terms($id, 'sitetag');
		$img = get_post_meta_img($id, '_thumbnail', true);
		if($img == ''){
			$link_url = get_post_meta($id, '_sites_link', true); 
			$ico_source = io_get_option('ico-source');
			if($link_url != '')
				$img = ($ico_source['ico_url'] .format_url($link_url) . $ico_source['ico_png']);
			else 
				$img = ($ico_source['ico_url'] .format_url(get_permalink()) . $ico_source['ico_png']);
		}
		if(empty($tags)) $tags = get_the_title().','.get_bloginfo('name');
		if(empty($excerpt)) $excerpt = get_post_meta($id, '_sites_sescribe', true);
	}
	if(get_post($id)->post_type =='app'){
		$tags=get_post_meta($id, '_seo_metakey', true)?:get_the_terms($id, 'apptag');
		$appinfo =  get_post_meta($id, 'app_down_list', true)[0]; 
		$title = get_post_meta($id, '_seo_title', true)?:(get_post_meta($id, '_app_name', true).$appinfo['app_version'].($appinfo['app_ad'] ? __('有广告','i_theme') : __('无广告','i_theme')).($appinfo['app_status']=="official"?__('官方版','i_theme'):__('开心版','i_theme')).'-'.$appinfo['app_date']);
		$img = get_post_meta_img($id, '_app_ico', true);
		if(empty($tags)) $tags = get_the_title().','.get_bloginfo('name');
		if(empty($excerpt)) $excerpt = get_post_meta($id, '_app_sescribe', true);
	}
	if(get_post($id)->post_type =='book'){
		$tags=get_post_meta($id, '_seo_metakey', true)?:get_the_terms($id, 'booktag');
		$img = get_post_meta_img($id, '_thumbnail', true);
		if(empty($tags)) $tags = get_the_title().','.get_bloginfo('name');
		if(empty($excerpt)) $excerpt = get_post_meta($id, '_summary', true);
	}
	if( $tags && is_array($tags)){
		foreach($tags as $val){
			$tag.=','.$val->name;
		}
		$tag=ltrim($tag,',');
	}else{
		$tag = $tags;
	}

	if( empty($excerpt)) $excerpt = io_get_excerpt(200); 

	if( $tag!="" ) $keywords=$tag;
	elseif(io_get_option('seo_home_keywords')) $keywords=io_get_option('seo_home_keywords');
	if( !empty($excerpt)) $description=$excerpt;
	elseif(io_get_option('seo_home_desc')) $description=io_get_option('seo_home_desc');
}
if(is_category()){ 
	$cat_id = get_query_var('cat');
	$url = get_category_link($cat_id);
	$meta = get_term_meta( $cat_id, 'category_meta', true );
	if($meta && $meta['seo_title'])
		$title = $meta['seo_title'];
	else
		$title = single_cat_title("", false);
	if($meta && $meta['seo_metakey'])
		$keywords=$meta['seo_metakey'];
	else
		$keywords=single_cat_title('', false).','.get_bloginfo('name');
	if($meta && $meta['seo_desc'])
		$description=$meta['seo_desc'];
	else
		$description = category_description()?:io_get_option('seo_home_desc'); 
}
if(is_tag() ){ 
	$tag_id=get_query_var('tag_id');
	$url = get_tag_link($tag_id);	
	$meta = get_term_meta( $tag_id, 'post_tag_meta', true );  
	if($meta && $meta['seo_title'])
		$title = $meta['seo_title'];
	else
		$title = single_tag_title("", false);
	if($meta && $meta['seo_metakey'])
		$keywords=$meta['seo_metakey'];
	else
		$keywords=single_tag_title('', false).','.get_bloginfo('name');
	if($meta && $meta['seo_desc'])
		$description=$meta['seo_desc'];
	else
		$description=strip_tags(trim(tag_description()))?:io_get_option('seo_home_desc'); 
}

if ( is_year() ) {  $title = sprintf( __('“%s”年所有文章', 'i_theme'), get_the_time('Y') ) ; } 
if ( is_month() ) {   $title =  sprintf( __('“%s”份所有文章', 'i_theme'), get_the_time('F') )  ; }
if ( is_day() ) {   $title = sprintf( __('“%s”所有文章', 'i_theme'), get_the_time(__('Y年n月j日', 'i_theme')) ) ;}
if ( is_author() ) {  $title =   sprintf( __('“%s”发表的所有文章', 'i_theme'), get_the_author() ) ;}
if ( is_404() ) {  $title = __('没有你要找的内容', 'i_theme'); }  

if($custom_page = get_query_var('custom_page')){ 
	switch ($custom_page) {
		case 'hotnews':
			$title = __('今日热榜', 'i_theme');
			break;
	}
}

if ($me_route = get_query_var('user_child_route')) {
	switch ($me_route) {
		case 'settings':
			$title = __('用户信息', 'i_theme');
			break;
		case 'notifications':
			$title = __('站内消息', 'i_theme');
			break;
		case 'stars':
			$title = __('我的收藏', 'i_theme');
			break;
		case 'sites':
			$title = __('网址管理', 'i_theme');
			break;
		case 'security':
			$title = __('账户安全', 'i_theme');
			break;
		default:
			$title = __('用户中心', 'i_theme');
	}
}
if($bm_route = get_query_var('bookmark_id')){
	if($bm_route == 'default'){
		$title = __('新标签页', 'i_theme');
	}else{
		$user_id=base64_io_decode($bm_route);
		if($user = get_user_by('ID', $user_id)){
			$title = $user->display_name;
		}
	}
}
if(is_tax("favorites")){
	$cat_id = get_queried_object_id();
	$url = get_category_link($cat_id); 
	$tit = get_term_meta( $cat_id, 'seo_title', true ); 
	$key = get_term_meta( $cat_id, 'seo_metakey', true ); 
	$des = get_term_meta( $cat_id, 'seo_desc', true ); 
	if($tit!="")
		$title = $tit;
	else
		$title = single_cat_title("", false);
	if($key!="")
		$keywords=$key;
	else
		$keywords=single_cat_title('', false).','.get_bloginfo('name');
	if($des!="")
		$description=$des;
	else
		$description=io_get_option('seo_home_desc');
}
if(is_tax("sitetag")){
	$tag_id = get_queried_object_id();
	$url = get_category_link($tag_id); 
	$meta = get_term_meta( $tag_id, 'sitetag_meta', true );
	if($meta && $meta['seo_title'])
		$title = $meta['seo_title'];
	else
		$title = single_tag_title("", false);
	if($meta && $meta['seo_metakey'])
		$keywords=$meta['seo_metakey'];
	else
		$keywords=single_tag_title('', false).','.get_bloginfo('name');
	if($meta && $meta['seo_desc'])
		$description=$meta['seo_desc'];
	else
		$description=io_get_option('seo_home_desc'); 
} 
if(is_tax("apps")){
	$cat_id = get_queried_object_id();
	$url = get_category_link($cat_id); 
	$tit = get_term_meta( $cat_id, 'seo_title', true ); 
	$key = get_term_meta( $cat_id, 'seo_metakey', true ); 
	$des = get_term_meta( $cat_id, 'seo_desc', true ); 
	if($tit!="")
		$title = $tit;
	else
		$title = single_cat_title("", false);
	if($key!="")
		$keywords=$key;
	else
		$keywords=single_cat_title('', false).','.get_bloginfo('name');
	if($des!="")
		$description=$des;
	else
		$description=io_get_option('seo_home_desc');
}
if(is_tax("apptag")){
	$tag_id = get_queried_object_id();
	$url = get_category_link($tag_id); 
	$meta = get_term_meta( $tag_id, 'apptag_meta', true );
	if($meta && $meta['seo_title'])
		$title = $meta['seo_title'];
	else
		$title = single_tag_title("", false);
	if($meta && $meta['seo_metakey'])
		$keywords=$meta['seo_metakey'];
	else
		$keywords=single_tag_title('', false).','.get_bloginfo('name');
	if($meta && $meta['seo_desc'])
		$description=$meta['seo_desc'];
	else
		$description=io_get_option('seo_home_desc'); 
} 
if(is_tax("books")){
	$cat_id = get_queried_object_id();
	$url = get_category_link($cat_id); 
	$tit = get_term_meta( $cat_id, 'seo_title', true ); 
	$key = get_term_meta( $cat_id, 'seo_metakey', true ); 
	$des = get_term_meta( $cat_id, 'seo_desc', true ); 
	if($tit!="")
		$title = $tit;
	else
		$title = single_cat_title("", false);
	if($key!="")
		$keywords=$key;
	else
		$keywords=single_cat_title('', false).','.get_bloginfo('name');
	if($des!="")
		$description=$des;
	else
		$description=io_get_option('seo_home_desc');
}
if(is_tax("booktag")){
	$tag_id = get_queried_object_id();
	$url = get_category_link($tag_id); 
	$meta = get_term_meta( $tag_id, 'booktag_meta', true );
	if($meta && $meta['seo_title'])
		$title = $meta['seo_title'];
	else
		$title = single_tag_title("", false);
	if($meta && $meta['seo_metakey'])
		$keywords=$meta['seo_metakey'];
	else
		$keywords=single_tag_title('', false).','.get_bloginfo('name');
	if($meta && $meta['seo_desc'])
		$description=$meta['seo_desc'];
	else
		$description=io_get_option('seo_home_desc'); 
} 
if(is_tax("series")){
	$tag_id = get_queried_object_id();
	$url = get_category_link($tag_id); 
	$meta = get_term_meta( $tag_id, 'series_meta', true );
	if($meta && $meta['seo_title'])
		$title = $meta['seo_title'];
	else
		$title = single_tag_title("", false);
	if($meta && $meta['seo_metakey'])
		$keywords=$meta['seo_metakey'];
	else
		$keywords=single_tag_title('', false).','.get_bloginfo('name');
	if($meta && $meta['seo_desc'])
		$description=$meta['seo_desc'];
	else
		$description=io_get_option('seo_home_desc'); 
} 
global $paged, $page;
	if (  $paged >= 2 || $page >= 2  )
		$title = $title.$linker.sprintf( '第 %s 页', max( $paged, $page ) );
?>
<title><?php echo $title . $title_after  ?></title>
<meta name="theme-color" content="<?php echo (theme_mode()=="io-black-mode"?'#2C2E2F':'#f9f9f9') ?>" />
<meta name="keywords" content="<?php echo $keywords ?>" />
<meta name="description" content="<?php echo esc_attr(stripslashes($description)) ?>" />
<meta property="og:type" content="<?php echo $type ?>">
<meta property="og:url" content="<?php echo $url ?>"/> 
<meta property="og:title" content="<?php echo $title . $title_after ?>">
<meta property="og:description" content="<?php echo $description ?>">
<meta property="og:image" content="<?php echo $img ?>">
<meta property="og:site_name" content="<?php bloginfo('name') ?>">
