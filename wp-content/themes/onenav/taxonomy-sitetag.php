<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:56:01
 * @LastEditors: iowen
 * @LastEditTime: 2021-07-15 01:13:01
 * @FilePath: \onenav\taxonomy-sitetag.php
 * @Description: 
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
                    <i class="site-tag iconfont icon-tag icon-lg mr-1" id="<?php single_cat_title() ?>"></i><?php single_cat_title() ?>
                </h4>
                <div class="row">  
					<?php 
					if ( !have_posts() ){
						echo '<div class="col-lg-12"><div class="nothing">'.__("没有内容","i_theme").'</div></div>';
					}
					if ( have_posts() ) : while ( have_posts() ) : the_post(); 
	            	//if(current_user_can('level_10') || !get_post_meta($post->ID, '_visible', true)) {
						if(io_get_option('site_card_mode') == 'max'){ ?>
            			    <div class="url-card col-sm-6 col-md-4 col-lg-6 col-xl-4  <?php echo before_class($post->ID) ?>">
            			    <?php include( get_theme_file_path('/templates/card-sitemax.php') ); ?>
            			    </div>
            			<?php }else{ ?>
            			    <div class="url-card <?php echo io_get_option('two_columns')?"col-6":"" ?> col-sm-6 col-md-4 col-xl-3 <?php echo before_class($post->ID) ?>">
            			    <?php include( get_theme_file_path('/templates/card-site.php') ); ?>
            			    </div>
						<?php }
					//} 
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
