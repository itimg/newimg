<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-07-20 20:48:09
 * @LastEditors: iowen
 * @LastEditTime: 2021-07-20 21:14:58
 * @FilePath: \onenav\templates\breadcrumb.php
 * @Description: 面包屑导航
 */
echo'<nav class="text-xs mb-3 mb-md-4"  aria-label="breadcrumb">';
	if (is_home()) { 
			echo sprintf(__( '现在位置', 'i_theme' )) . '<i class="text-danger px-1">•</i>' . sprintf(__( '首页', 'i_theme' )) ;
	}

	if ( !is_home() && !is_front_page() ) {
		echo '<i class="iconfont icon-home"></i> ';
		echo '<a class="crumbs" href="'.home_url('/').'">';
		echo sprintf(__( '首页', 'i_theme' ));
		echo "</a>";
	}

	if ( !is_search() && is_category() ) {
		echo '<i class="text-danger px-1">•</i>';
		echo get_category_parents( get_query_var('cat') , true , '<i class="text-danger px-1">•</i>' ); 
	}

	if (is_single()) {
		echo '<i class="text-danger px-1">•</i>';
		echo the_category('<i class="text-danger px-1">•</i>', 'multiple');
		if ( 'post' == get_post_type() ) {
			echo '<i class="text-danger px-1">•</i>';
			echo '<span aria-current="page">';
			if (wp_is_mobile()) {
				echo sprintf(__( '正文', 'i_theme' ));
			} else {
				echo the_title();
			}
			echo '</span>';
		}
		if (is_attachment() ) {echo sprintf(__( '附件', 'i_theme' )) ; }
	}

	if ( is_page() && !is_front_page() ) {
		echo '<i class="text-danger px-1">•</i>';
		echo the_title();
	}

	if ( is_page() && is_front_page() ) { 
			echo sprintf(__( '现在位置', 'i_theme' )) . '<i class="text-danger px-1">•</i>' . sprintf(__( '首页', 'i_theme' )) ;
	}

	elseif ( is_tag() ) {echo '<i class="text-danger px-1">•</i>';single_tag_title();echo '';}
	elseif ( is_day() ) {echo '<i class="text-danger px-1">•</i>';echo"发表于"; the_time('Y年m月d日'); echo'的文章';}
	elseif ( is_month() ) {echo '<i class="text-danger px-1">•</i>';echo"发表于"; the_time('Y年m月'); echo'的文章';}
	elseif ( is_year() ) {echo '<i class="text-danger px-1">•</i>';echo"发表于"; the_time('Y年'); echo'的文章';}
	elseif ( is_author() ) {echo '<i class="text-danger px-1">•</i>';echo wp_title( ''); echo'发表的文章';}
	elseif ( is_404() ) {echo '<i class="text-danger px-1">•</i>';echo sprintf(__( '亲，你迷路了！', 'i_theme' )) ; echo'';}
	elseif ( is_search()) {
		echo '<i class="text-danger px-1">•</i>' . sprintf(__( '搜索', 'i_theme' )) . ' ';
		echo '<i class="text-danger px-1">•</i>';
		echo search_results();
	}
echo'</nav>';