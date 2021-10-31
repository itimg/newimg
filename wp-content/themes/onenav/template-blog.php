<?php
/*
Template Name: 博客页面
*/

get_header();
global $is_blog;
$is_blog = true;
?>
<?php 
get_template_part( 'templates/sidebar','nav' );
?>
<div class="main-content flex-fill">
    
<?php get_template_part( 'templates/header','banner' ); ?>

<div id="content" class="container my-4 my-md-5">
    <div class="slide-blog mb-4">
    <?php get_template_part( 'templates/slide','blog' ); ?>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <?php get_template_part( 'templates/cat','list' ) ?>
        </div> 
		<div class="sidebar sidebar-tools col-lg-4 pl-xl-4 d-none d-lg-block">
			<?php get_sidebar('blog'); ?>
		</div> 
    </div>
</div>

<?php get_footer(); ?>
