<?php
/*!
 * Theme Name:One Nav
 * Theme URI:https://www.iotheme.cn/
 * Author:iowen
 * Author URI:https://www.iowen.cn/
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>


<?php 
get_template_part( 'templates/sidebar','nav' );
?>
<div class="main-content flex-fill">
    
<?php get_template_part( 'templates/tools','header' ); ?>
    
        <div class="row">
            <div class="col-lg-8 col-xl-9">
                <h4 class="text-gray text-lg mb-4">
                    <i class="site-tag iconfont icon-app icon-lg mr-1" id="<?php single_cat_title() ?>"></i><?php single_cat_title() ?>
                </h4>
                <div class="row">  
    	        	<?php 
					if ( !have_posts() ){
						echo '<div class="col-lg-12"><div class="nothing">'.__("没有内容","i_theme").'</div></div>';
					}
					if ( have_posts() ) : while ( have_posts() ) : the_post();
                        if(io_get_option('app_card_mode') == 'card'){
                            echo'<div class="col-12 col-md-6 col-lg-6 col-xl-4">';
                            include( get_theme_file_path('/templates/card-appcard.php') ); 
                            echo'</div>';
                        }else{
                            echo'<div class="col-4 col-md-3 col-lg-5a col-xl-6a col-xxl-7a pb-1">';
                            include( get_theme_file_path('/templates/card-app.php') ); 
                            echo'</div>';
                        }
                    endwhile; endif;?>
                </div>  
    	        <div class="posts-nav mb-4">
    	            <?php echo paginate_links(array(
    	                'prev_next'          => 0,
    	                'before_page_number' => '',
    	                'mid_size'           => 2,
    	            ));?>
    	        </div>
    		</div> 
    		<div class="sidebar sidebar-tools col-lg-4 col-xl-3 pl-xl-4 d-none d-lg-block">
    			<?php get_sidebar(); ?>
    		</div> 
        </div>
    </div>

<?php get_footer(); ?>
