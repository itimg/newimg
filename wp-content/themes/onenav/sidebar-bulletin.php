<?php
/*!
 * Theme Name:One Nav
 * Theme URI:https://www.iotheme.cn/
 * Author:iowen
 * Author URI:https://www.iowen.cn/
 */
if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
 
		<?php if ( ! dynamic_sidebar( 'sidebar-bull' ) ) : ?> 
			<div id="add-widgets" class="card widget_text bk">
				
				<div class="card-header">
					<span><i class="iconfont icon-category mr-2"></i><?php _e('添加小工具','i_theme') ?></span>
				</div>
				<div class="card-body text-sm">
					<a href="<?php echo admin_url(); ?>widgets.php" target="_blank"><?php _e('点此为“公告侧边栏”添加小工具','i_theme') ?></a>
				</div>
			</div>
		<?php endif; ?>
 