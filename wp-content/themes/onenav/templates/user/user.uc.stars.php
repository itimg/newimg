<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:56:02
 * @LastEditors: iowen
 * @LastEditTime: 2021-09-01 22:39:19
 * @FilePath: \onenav\templates\user\user.uc.stars.php
 * @Description: 
 */
?>
<?php get_header(); ?>


<?php 
get_template_part( 'templates/sidebar','nav' );
global $current_user; 
?>
<div class="main-content flex-fill page">
<div class="big-header-banner">
<?php get_template_part( 'templates/header','banner' ); ?>
</div>
<div class="user-bg" style="background-image: url(<?php echo io_get_user_cover($current_user->ID ,"full") ?>)">
</div>
    <div id="content" class="container user-area my-4">
        <div class="row">
            <div class="sidebar col-md-3 user-menu">
            <?php load_template( get_theme_file_path('/templates/user/user.menu.php')); ?>
            </div>
            <div id="user" class="col-md-9">
                <div class="author-meta-r d-none mb-5 d-md-block">
                    <div class="h2 text-white mb-3"><?php echo $current_user->display_name; ?>
                        <small class="text-xs"><span class="badge badge-outline-primary mt-2">
                            <?php echo io_get_user_cap_string($current_user->ID) ?>
                        </span></small>
                    </div>
                    <div class="text-white text-sm"><?php echo ($current_user->description?:__('帅气的我简直无法用语言描述！', 'i_theme')); ?></div>
                </div> 
                <div class="card">
                <div class="card-body">
                    <div class="text-lg pb-3 border-bottom border-light border-2w mb-3"><?php _e('我的收藏','i_theme') ?></div>
                    <?php   
                        $sites_ids = io_get_user_star_post_ids($current_user->ID,'sites');
                        $app_ids = io_get_user_star_post_ids($current_user->ID,'app');
                        $book_ids = io_get_user_star_post_ids($current_user->ID,'book');
                        $post_ids = io_get_user_star_post_ids($current_user->ID,'post');
                    
                    ?>
                    <ul class="nav nav-pills sites-nav mb-3  justify-content-center" id="pills-tab" role="tablist">
                        <li class="nav-item mx-2 mb-2">
                            <a class="nav-link active" id="post-sites-tab" data-toggle="pill" href="#post-sites" role="tab" aria-controls="post-sites" aria-selected="true"><?php _e('网址','i_theme') ?></a>
                        </li>
                        <li class="nav-item mx-2 mb-2">
                            <a class="nav-link" id="post-app-tab" data-toggle="pill" href="#post-app" role="tab" aria-controls="post-app" aria-selected="false"><?php _e('APP&资源','i_theme') ?></a>
                        </li>
                        <li class="nav-item mx-2 mb-2">
                            <a class="nav-link" id="post-book-tab" data-toggle="pill" href="#post-book" role="tab" aria-controls="post-book" aria-selected="false"><?php _e('书籍&影视','i_theme') ?></a>
                        </li>
                        <li class="nav-item mx-2 mb-2">
                            <a class="nav-link" id="post-post-tab" data-toggle="pill" href="#post-post" role="tab" aria-controls="post-post" aria-selected="false"><?php _e('文章资讯','i_theme') ?></a>
                        </li>
                    </ul>
                    <div class="tab-content admin-sites" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="post-sites" role="tabpanel" aria-labelledby="post-sites-tab">
                        <?php io_add_star_post('sites',$sites_ids) ?>
                        </div>
                        <div class="tab-pane fade" id="post-app" role="tabpanel" aria-labelledby="post-app-tab">
                        <?php io_add_star_post('app',$app_ids) ?>
                        </div>
                        <div class="tab-pane fade" id="post-book" role="tabpanel" aria-labelledby="post-book-tab">
                        <?php io_add_star_post('book',$book_ids) ?>
                        </div>
                        <div class="tab-pane fade" id="post-post" role="tabpanel" aria-labelledby="post-post-tab">
                        <?php io_add_star_post('post',$post_ids) ?>
                        </div>
                    </div>  
                </div>
                </div>
            </div>
        </div>
	</div> 
<?php get_footer(); ?>