<?php
/*!
 * Theme Name:One Nav
 * Theme URI:https://www.iotheme.cn/
 * Author:iowen
 * Author URI:https://www.iowen.cn/
 */
?>

<?php
global $io_user_vars;
$io_user_vars['io_user_id'] = get_current_user_id();  
$io_user_vars['io_user'] = get_user_by('ID', $io_user_vars['io_user_id']); 
$io_user_vars['io_paged'] = get_query_var('paged') ? : 1; 

global $current_user,$wp_query; 
$query_vars=$wp_query->query_vars;
$user_tab = isset($query_vars['user_child_route']) && in_array($query_vars['user_child_route'], array_keys(allowed_user_routes())) ? $query_vars['user_child_route'] : 'settings';
$mobile=wp_is_mobile()?'#user':'';
?> 
    <div class="card card-menu">
        <div class="card-body">
            <div class="author-avatar text-center">
                <div class="avatar-body bg-white rounded-circle p-2">
                    <?php echo get_avatar($current_user->ID) ?> 	      	    	
                </div>
            </div>
            <div class="author-meta text-center my-3 d-block d-md-none">
                <div class="h6 mb-3"><?php echo $current_user->display_name; ?>
                    <small class="d-block text-xs"><span class="badge badge-outline-primary mt-2">
                        <?php echo io_get_user_cap_string($current_user->ID) ?>
                    </span></small>
                </div>
                <div class="desc text-xs h-2x "><?php echo ($current_user->description?:__('帅气的我简直无法用语言描述！', 'i_theme')); ?></div>
            </div>
            <div class="user-nav mt-5">
                <nav class="nav"> 
                    <ul class="user-tabs text-center">
                        <li><a class="<?php echo io_conditional_class('user-tab settings',          $user_tab == 'settings'     ); ?>" href="/user/settings<?=$mobile?>"><?php _e('个人资料', 'i_theme'); ?></a></li>
                        <li><a class="<?php echo io_conditional_class('user-tab notifications',     $user_tab == 'notifications'); ?>" href="/user/notifications/all<?=$mobile?>"><?php _e('站内消息', 'i_theme'); ?></a></li>
                        <li><a class="<?php echo io_conditional_class('user-tab sites',             $user_tab == 'sites'        ); ?>" href="/user/sites<?=$mobile?>"><?php _e('网址管理', 'i_theme'); ?></a></li>
                        <li><a class="<?php echo io_conditional_class('user-tab stars',             $user_tab == 'stars'        ); ?>" href="/user/stars<?=$mobile?>"><?php _e('我的收藏', 'i_theme'); ?></a></li>
                        <li><a class="<?php echo io_conditional_class('user-tab security',          $user_tab == 'security'     ); ?>" href="/user/security<?=$mobile?>"><?php _e('账户安全', 'i_theme'); ?></a></li>
                        <li><a class="btn btn-light btn-block mt-4" href="<?php echo wp_logout_url(home_url());?>"><?php _e('安全退出', 'i_theme'); ?></a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
<script type="text/javascript">
    $(document).ready(function(){
        setTimeout(function () { 
            if(window.location.hash != ''){
                $("html, body").animate({
                    scrollTop: $(window.location.hash).offset().top - 90
                }, {
                    duration: 300,
                    easing: "swing"
                });
            }
        }, 100);
    });
</script>