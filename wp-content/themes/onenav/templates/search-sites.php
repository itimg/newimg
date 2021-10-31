<?php
/*!
 * Theme Name:One Nav
 * Theme URI:https://www.iotheme.cn/
 * Author:iowen
 * Author URI:https://www.iowen.cn/
 */
if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

                <div class="row">
                 
                <?php if ( !have_posts() ) : ?>
                    <div class="col-lg-12">
                        <div class="nothing"><?php _e('没有内容','i_theme') ?></div>
                      </div>
                <?php endif; ?>
                
                <?php if ( have_posts() ) : ?>
                <?php while ( have_posts() ) : the_post();
                //if(current_user_can('level_10') || !get_post_meta($post->ID, '_visible', true)){
                ?>
                    <div class="url-card <?php echo io_get_option('two_columns')?"col-6":"" ?> col-sm-6 col-md-4 <?php echo before_class($post->ID) ?>">
                          
                    <?php include( get_theme_file_path('/templates/card-site.php') ); ?>
                    </div>
                <?php //}
            
                endwhile; endif;?>
                </div> 
