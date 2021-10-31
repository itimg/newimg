<?php
/*!
 * Theme Name:One Nav
 * Theme URI:https://www.iotheme.cn/
 * Author:iowen
 * Author URI:https://www.iowen.cn/
 */
if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php
global $iodb,$customize_terms,$bookmark_id,$bookmark_user,$bookmark_set,$current_user;
if(empty($customize_terms))
   $customize_terms = $iodb->getTerm($bookmark_id);
?>
<header class="navbar navbar-dark fixed-top">
   <?php if(io_get_option('weather')): ?>
   <!-- weather widgets -->
   <div class="weather">
      <div id="he-plugin-simple" style="display: contents;"></div>
      <script>
      WIDGET = {
         "CONFIG": {
            "modules": "20",
            "background": "5",
            "tmpColor": "FFFFFF",
            "tmpSize": "20",
            "cityColor": "FFFFFF",
            "citySize": "16",
            "aqiColor": "FFFFFF",
            "aqiSize": "16",
            "weatherIconSize": "30",
            "alertIconSize": "18",
            "padding": "0px 5px 0px 5px",
            "shadow": "0",
            "language": "auto",
            "fixed": "false",
            "vertical": "top",
            "horizontal": "left",
            "key": "5782734e2f024918b12d38b68635e101"
         }
      }
      </script>
      <script>
      loadFunc(function() {
         let script = document.createElement("script");
         script.setAttribute("async", "");
         script.src = "//widget.qweather.net/simple/static/js/he-simple-common.js?v=2.0";
         document.body.appendChild(script);
      });
      </script>
   </div> 
   <!-- weather widgets end -->
   <?php endif; ?>
   <ul class="nav navbar-menu"> 
      <?php 
      if( io_get_option('nav_login') ){  
         if(!is_user_logged_in()) {
      ?>
			<li class="nav-login ml-3">
            <a href="<?php echo esc_url(home_url('/wp-login.php')) ?>" class="text-light" title="<?php _e('登录','i_theme') ?>"><i class="iconfont icon-user bookmark-ico icon-2x"></i></a>
			</li>
      <?php 
         }else{
            get_template_part( 'templates/widget/header', 'user' );
         }
      } 
      ?>
		<li class="nav-item ml-3">
			<a href="javascript:" class="text-light" id="seting-btn" title="<?php _e('设置','i_theme') ?>"><i class="iconfont icon-seting bookmark-ico icon-2x"></i></a>
		</li>
      <div class="seting-panel py-5 px-3">
         <div class="seting-container">
            <div class="d-flex mt-n4">
               <div class="btn ml-auto seting-close mb-4 text-muted"><i class="iconfont icon-close icon-2x"></i></div>
            </div>
            <?php if(!is_user_logged_in()): ?>
            <div class="text-center">
               <i class="iconfont icon-smiley icon-4x"></i>
               <div class="mt-5"><?php _e('登录后可拥有自定义书签页','i_theme')?></div>
               <a href="<?php echo io_add_redirect(home_url('/login/'), io_get_current_url()) ?>" class="btn mt-2 btn-danger" title="登录"><i class="iconfont icon-user mr-2"></i> <?php _e('登录','i_theme')?></a>
            </div>
            <?php elseif(is_user_logged_in() && $bookmark_id=='default'): 
            $other_user=get_user_by('ID', $current_user->ID); 
            ?>
            <div class="text-center">  
               <div class="author-info text-center">
                  <div class="avatar-body bg-white rounded-circle p-2">
                     <?php echo get_avatar($current_user->ID,76) ?> 	      	    	
                  </div>
                  <div class='mt-3'><?php echo $other_user->display_name ?></div>
                  <a href="<?php echo esc_url(home_url('/bookmark/'.base64_io_encode(sprintf("%08d", $current_user->ID)))) ?>" class='btn btn-danger mt-3'><?php _e('返回我的书签', 'i_theme') ?></a>
               </div>
            </div>
            <?php 
            elseif(is_user_logged_in() && $current_user->ID!=$bookmark_id): 
            $other_user=get_user_by('ID', $bookmark_id);   
            ?>
            <div class="text-center ">    
               <div class="author-info text-center">
                  <div class="avatar-body bg-white rounded-circle p-2">
                     <?php echo get_avatar($bookmark_id,76) ?> 	      	    	
                  </div>
                  <div class='mt-3'><?php echo $other_user->display_name.__('的书签','i_theme') ?></div>
               </div>
            </div>
            <?php else: ?>
            <form class="bookmark-seting-form">
               <div class="overflow-y-auto" style="max-height:calc(100vh - 180px)">
               <?php if(!io_get_option('bookmark_share',true)): ?>
               <div class="form-group">
                  <label for="share-bookmark"><?php _e('共享书签','i_theme') ?></label><a class="ml-2 btn btn-light btn-sm text-xs copy-url" data-clipboard-text="<?php echo esc_url(home_url('/bookmark/'.base64_io_encode(sprintf("%08d", $current_user->ID)))) ?>"><?php _e('复制分享','i_theme') ?></a>
                  <div class="custom-control custom-switch">
                     <span class="custom-switch-before"><?php _e('私有','i_theme') ?></span>
                     <input type="checkbox" class="custom-control-input" name="share-bookmark" <?php echo get_bookmark_seting('share_bookmark',$bookmark_set) ?> id="share-bookmark">
                     <label class="custom-control-label" for="share-bookmark"><?php _e('共享','i_theme') ?><span class="text-xs text-muted">(<?php _e('私有或者共享给所有人使用','i_theme') ?>)</span></label>
                  </div>
               </div>
               <?php endif; ?>
               <?php if(io_get_option('is_go')): ?>
               <div class="form-group">
                  <label for="is-go"><?php _e('内链跳转&直链','i_theme') ?></label>
                  <div class="custom-control custom-switch">
                     <span class="custom-switch-before"><?php _e('内链','i_theme') ?></span>
                     <input type="checkbox" class="custom-control-input" name="is-go" <?php echo get_bookmark_seting('is_go',$bookmark_set) ?> id="is-go">
                     <label class="custom-control-label" for="is-go"><?php _e('直链','i_theme') ?><span class="text-xs text-muted"></span></label>
                  </div>
               </div>
               <?php endif; ?>
               <div class="form-group">
                  <label for="hide-title"><?php _e('隐藏标题','i_theme') ?></label>
                  <div class="custom-control custom-switch">
                     <span class="custom-switch-before"><?php _e('隐藏','i_theme') ?></span>
                     <input type="checkbox" class="custom-control-input" name="hide-title" <?php echo get_bookmark_seting('hide_title',$bookmark_set) ?> id="hide-title">
                     <label class="custom-control-label" for="hide-title"><?php _e('显示','i_theme') ?></label>
                  </div>
               </div>
               <div class="form-group">
                  <label for="sites-title"><?php _e('标题文字','i_theme') ?></label>
                  <input type="text" class="form-control" name="sites-title" id="sites-title" placeholder="<?php _e('我的导航','i_theme') ?>" value="<?php echo get_bookmark_seting('sites_title',$bookmark_set) ?>">
               </div>
               <div class="form-group">
                  <label for="quick-nav"><?php _e('选择快速导航分类','i_theme') ?></label>
                  <select class="form-control form-control-lg" name="quick-nav" id="quick-nav">
                     <?php 
                     if($customize_terms){ 
                        foreach($customize_terms as $c_term){
                           echo '<option value="'.$c_term->id.'" '.(get_bookmark_seting('quick_nav',$bookmark_set)==$c_term->id?'selected':'').'>'.$c_term->name.'</option>';
                        }
                     }
                     ?>
                  </select>
               </div>

               <div class="form-group img-radio">
                  <?php $bgSet = get_bookmark_seting('bg',$bookmark_set) ?>
                  <label><?php _e('选择背景','i_theme') ?><span class="text-xs ml-2"><?php _e('动态背景会影响性能','i_theme') ?></span></label>
                  <input type="text" class="form-control mb-2" name="custom-img" id="custom-img" placeholder="<?php _e('自定义图片(url)','i_theme') ?>" value="<?php echo get_bookmark_seting('custom_img',$bookmark_set) ?>" style="<?php echo ($bgSet == 'custom'?'':'display:none') ?>">
                  <div class="px-2">
                  <div class="row no-gutters">
                  <?php
                     $bgs = get_bookmark_bg();
                     foreach ($bgs as $k=>$v){
                        echo'<div class="col-6 col-sm-4 p-1">
                        <input id="'.$k.'" class="" name="bg" type="radio" '.($bgSet==$k?'checked':'').' value="'.$k.'" />
                        <label class="label-img" id="label_'.$k.'" for="'.$k.'" style="background-image: url('.$v.')"> </label>
                        </div>';
                     }
                  ?>
                  </div>
                  </div>
               </div>
               </div>
               <input type="hidden" name="action" value="save_bookmark_set">
               <input type="hidden" name="key" value="<?php echo base64_io_encode($bookmark_id) ?>">
               <?php wp_nonce_field('bookmark_set'); ?>
               <button type="submit" class="btn submit btn-primary btn-block mt-3"><?php _e('保存','i_theme') ?></button>
            </form>
            <?php endif; ?>
         </div>
      </div>
</nav>
   </ul>
</header>