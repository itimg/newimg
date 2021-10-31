<?php
/*
Template Name: 次级导航
*/
?>
<?php get_header();?>


<?php 
get_template_part( 'templates/sidebar','nav' );
?>
<div class="main-content flex-fill">
<?php 
    get_template_part( 'templates/tools','header' ); 
 
    // 加载文章轮播模块
    //get_template_part( 'templates/article','list' ); 

    // 加载热搜模块
    get_template_part( 'templates/tools','hotsearch' ); 

    // 加载文章模块
    //get_template_part( 'templates/tools','post' ); 

    // 加载自定义模块
    //if(is_user_logged_in() && io_get_option('user_center')){
    //    get_template_part( 'templates/tools','customizeforuser' ); 
    //}else{
    //    get_template_part( 'templates/tools','customize' ); 
    //}
    // 加载热门模块
    if (is_page_template('template-mininav.php') && !get_post_meta( get_the_ID(), 'hot_box', true )){
        goto miniNav;
    }
    get_template_part( 'templates/tools','hotcontent' ); 

    miniNav:
    // 加载广告模块second
    get_template_part( 'templates/ads','homesecond' );

    // 加载菜单内容卡片
    add_menu_content_card();
    
    // 加载广告模块link
    get_template_part( 'templates/ads','homelink' );
    // 加载友链模块
    //get_template_part( 'templates/friendlink' ); ?>   
    </div> 
<?php
get_footer();
