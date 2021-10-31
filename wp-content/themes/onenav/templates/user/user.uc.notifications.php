<?php
/*!
 * Theme Name:One Nav
 * Theme URI:https://www.iotheme.cn/
 * Author:iowen
 * Author URI:https://www.iowen.cn/
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
            <?php load_template( get_theme_file_path( '/templates/user/user.menu.php')); ?>
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
                    <?php  
                    global $io_user_vars; 
                    $io_filter_type = get_query_var('user_grandchild_route'); 
                    $io_page        = $io_user_vars['io_paged'];
                    $data           = getNotificationData($current_user->ID, $io_filter_type, $io_page);
                    $notifications  = $data->notifications; 
                    $count          = $data->count; 
                    $total          = $data->total; 
                    $max_pages      = $data->max_pages; 
                    ?>
                    <div class="text-lg pb-3 border-bottom border-light border-2w mb-3 d-flex"><?php _e('站内消息', 'i_theme'); ?><span class="ml-auto text-sm text-muted"><?php printf(__('总共 %d 条消息', 'i_theme'), $total); ?></span></div>  
                    <?php if($count > 0) { ?>
                    <div class="info-group">
                        <ul class="notifications-list">
                            <?php foreach ($notifications as $notification) { ?>
                                <li id="notification-<?php echo $notification->id; ?>" class="text-sm my-4 bg-light p-2">
                                <span class="mr-3 text-muted text-xs notifi-time"><?php echo $notification->msg_date; ?></span>
                                <?php _e('发送者: ', 'i_theme'); ?><span class="mr-3">
                                <?php if($notification->sender_id != 0) { ?>
                                <a href="<?php echo get_author_posts_url($notification->sender_id); ?>" target="_blank"><?php echo $notification->sender; ?></a>
                                <?php }else{ ?>
                                <?php echo $notification->sender; ?>
                                <?php } ?>
                                </span>
                                <span class="mr-3"><?php echo $notification->msg_title; ?></span>
                                <?php if(!empty($notification->msg_content)) { ?>
                                    <div class="notification-content bg-white p-2 mt-2">
                                    <p><?php echo htmlspecialchars_decode($notification->msg_content); ?></p>
                                </div>
                                <?php } ?>
                                </li>
                            <?php } ?>
                        </ul>
                        <?php if($max_pages > 1) { ?>
                            <div class="row">
                                <div class="col-md-3">
                                <?php if($io_page == 1) { ?>
                                    <a href="javascript:;" class="btn btn-dark prev disabled"><?php _e('上一页', 'i_theme'); ?></a>
                                <?php }else{ ?>
                                    <a href="<?php echo $data->prev_page; ?>" class="btn btn-dark prev"><?php _e('上一页', 'i_theme'); ?></a>
                                <?php } ?>
                                </div>
                                <div class="col-md-6 text-xs text-mute text-center align-self-center">
                                    <span class="current-page"><?php printf(__('第 %d 页', 'i_theme'), $io_page); ?></span>
                                    <span class="separator">/</span>
                                    <span class="max-page"><?php printf(__('共 %d 页', 'i_theme'), $max_pages); ?></span>
                                </div>
                                <div class="col-md-3 text-right">
                                <?php if($io_page != $data->max_pages) { ?>
                                    <a href="<?php echo $data->next_page; ?>" class="btn btn-dark next"><?php _e('下一页', 'i_theme'); ?></a>
                                <?php }else{ ?>
                                    <a href="javascript:;" class="btn btn-dark next disabled"><?php _e('下一页', 'i_theme'); ?></a>
                                <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <?php }else{ ?>
                    <div class="empty-content text-center pb-5">
                        <i class="iconfont icon-nothing1"></i>
                    </div>
                    <?php } ?>
                </div>
                </div>
            </div>
        </div>
	</div> 


<?php get_footer(); ?>