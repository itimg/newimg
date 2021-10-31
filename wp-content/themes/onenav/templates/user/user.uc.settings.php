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
<script type="text/javascript" src="https://cdn.staticfile.org/webuploader/0.1.5/webuploader.html5only.min.js"></script>
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
                    <div class="text-lg pb-3 border-bottom border-light border-2w mb-3"><?php _e('个人资料','i_theme') ?></div>  
                    <form id="io-change-profile" method="post">
                        <input type="hidden" name="action" value="change_profile">
                        <div class="form-group row">
                            <label for="mm_name" class="col-sm-3 col-md-2 col-form-label"><?php _e('昵称','i_theme') ?></label>
                            <div class="col-sm-9 col-md-10">
                                <input type="text" class="form-control" id="mm_name" name="mm_name" value="<?php echo esc_attr( $current_user->nickname ) ?>">
                            </div>
                        </div>
                        <?php if(!get_user_meta($current_user->ID, 'name_change', true)): ?>
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-2 col-form-label"><?php _e('用户名','i_theme') ?></label>
                            <div class="col-sm-9 col-md-10 col-form-label">
                                <?php echo esc_attr( $current_user->user_login ); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-2 col-form-label"><?php _e('账号ID','i_theme') ?></label>
                            <div class="col-sm-9 col-md-10 col-form-label">
                                <?php echo esc_attr( $current_user->ID ); ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-2 col-form-label"><?php _e('头像', 'i_theme'); ?></label>
                            <div class="col-sm-9 col-md-10"> 
                                <div class="radio avatar-radio">
                                    <label class="local-avatar-label position-relative" title="<?php _e('上传头像', 'i_theme'); ?>">
                                        <img src="<?php echo ($current_user->custom_avatar?:get_theme_file_uri('/images/t.png')); ?>" class="io-avatar-custom avatar rounded-circle mr-2" data-filename="<?php echo $current_user->ID . '.jpg'; ?>" width="38" height="38">
                                        <span id="io-avatar-picker" class="avatar-picker img-picker "></span>
                                        <svg class="svgIcon-use" width="38" height="38" viewBox="-8 -8 80 80"><g fill-rule="evenodd"><path d="M10.61 44.486V23.418c0-2.798 2.198-4.757 5.052-4.757h6.405c1.142-1.915 2.123-5.161 3.055-5.138L40.28 13.5c.79 0 1.971 3.4 3.073 5.14 0 .2 6.51 0 6.51 0 2.856 0 5.136 1.965 5.136 4.757V44.47c-.006 2.803-2.28 4.997-5.137 4.997h-34.2c-2.854.018-5.052-2.184-5.052-4.981zm5.674-23.261c-1.635 0-3.063 1.406-3.063 3.016v19.764c0 1.607 1.428 2.947 3.063 2.947H49.4c1.632 0 2.987-1.355 2.987-2.957v-19.76c0-1.609-1.357-3.016-2.987-3.016h-7.898c-.627-1.625-1.909-4.937-2.28-5.148 0 0-13.19.018-13.055 0-.554.276-2.272 5.143-2.272 5.143l-7.611.01z"></path><path d="M32.653 41.727c-5.06 0-9.108-3.986-9.108-8.975 0-4.98 4.047-8.966 9.108-8.966 5.057 0 9.107 3.985 9.107 8.969 0 4.988-4.047 8.974-9.107 8.974v-.002zm0-15.635c-3.674 0-6.763 3.042-6.763 6.66 0 3.62 3.089 6.668 6.763 6.668 3.673 0 6.762-3.047 6.762-6.665 0-3.616-3.088-6.665-6.762-6.665v.002z"></path></g></svg>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="avatar-custom" name="avatar" class="custom-control-input" value="custom" <?php if($current_user->avatar_type=='custom') echo 'checked'; ?> >
                                            <label class="custom-control-label" for="avatar-custom"><?php _e('自定义', 'i_theme'); ?></label>
                                        </div>
                                    </label>
                                    <label  class="letter-avatar-label">
                                        <img src="<?php echo get_avatar_url( $current_user->user_email, array('size'=>80)) ?>" class="avatar rounded-circle mr-2" width="38" height="38"> 
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="avatar-letter" name="avatar" class="custom-control-input" value="letter" <?php if($current_user->avatar_type=='letter') echo 'checked'; ?> >
                                            <label class="custom-control-label" for="avatar-letter"><?php _e('默认', 'i_theme'); ?></label>
                                        </div>
                                    </label>
                                    <?php if(isset($current_user->qq_avatar)) { ?>
                                    <label  class="qq-avatar-label">
                                        <img src="<?php echo $current_user->qq_avatar; ?>" class="avatar rounded-circle mr-2" width="38" height="38">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="avatar-qq" name="avatar" class="custom-control-input" value="qq" <?php if($current_user->avatar_type=='qq') echo 'checked'; ?> >
                                            <label class="custom-control-label" for="avatar-qq"><?php _e('QQ 头像', 'i_theme'); ?></label>
                                        </div>
                                    </label>
                                    <?php } ?>
                                    <?php if(isset($current_user->sina_avatar)) { ?>
                                    <label  class="sina-avatar-label">
                                        <img src="<?php echo $current_user->sina_avatar; ?>" class="avatar rounded-circle mr-2" width="38" height="38">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="avatar-sina" name="avatar" class="custom-control-input" value="sina" <?php if($current_user->avatar_type=='sina') echo 'checked'; ?> >
                                            <label class="custom-control-label" for="avatar-sina"><?php _e('微博头像', 'i_theme'); ?></label>
                                        </div>
                                    </label>
                                    <?php } ?>
                                    <?php if(isset($current_user->wechat_avatar)) { ?>
                                    <label  class="wechat-avatar-label">
                                        <img src="<?php echo $current_user->wechat_avatar; ?>" class="avatar rounded-circle mr-2" width="38" height="38">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="avatar-wechat" name="avatar" class="custom-control-input" value="wechat" <?php if($current_user->avatar_type=='wechat') echo 'checked'; ?> >
                                            <label class="custom-control-label" for="avatar-wechat"><?php _e('微信头像', 'i_theme'); ?></label>
                                        </div>
                                    </label>
                                    <?php } ?>
                                    <?php if(isset($current_user->wechat_gzh_avatar)) { ?>
                                    <label  class="wechat-avatar-label">
                                        <img src="<?php echo $current_user->wechat_gzh_avatar; ?>" class="avatar rounded-circle mr-2" width="38" height="38">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="avatar-wechat" name="avatar" class="custom-control-input" value="wechat_gzh" <?php if($current_user->avatar_type=='wechat_gzh') echo 'checked'; ?> >
                                            <label class="custom-control-label" for="avatar-wechat"><?php _e('微信头像', 'i_theme'); ?></label>
                                        </div>
                                    </label>
                                    <?php } ?>
                                </div>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label for="mm_url" class="col-sm-3 col-md-2 col-form-label"><?php _e('网址','i_theme') ?></label>
                            <div class="col-sm-9 col-md-10">
                                <input type="text" class="form-control" id="mm_url" name="mm_url" value="<?php echo esc_attr( $current_user->user_url ) ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="mm_desc" class="col-sm-3 col-md-2 col-form-label"><?php _e('个人描述','i_theme') ?></label>
                            <div class="col-sm-9 col-md-10">
                                <textarea type="text" class="form-control" id="mm_desc" name="mm_desc" placeholder="<?php _e('帅气的我简直无法用语言描述！', 'i_theme') ?>"><?php echo esc_attr( $current_user->description ) ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                                <label class="col-sm-3 col-md-2 col-form-label"><?php _e('个人封面', 'i_theme'); ?></label>
                                <div class="col-sm-9 col-md-10">
                                    <div class="user-cover"><img src="<?php echo io_get_user_cover($current_user->ID ,"full") ?>" class="io-cover-custom" />
                                    <label class="io-cover-picker cover-picker" title="<?php _e('更换封面', 'i_theme'); ?>"></label>
                                    </div>
                                </div>
                        </div>
                        <?php wp_nonce_field('change_profile'); ?>
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-2 col-form-label"></label> 
                            <div class="col-sm-9 col-md-10">
                                <button type="submit" class="submit btn btn-primary"><?php _e('保存资料','i_theme') ?></button>
                            </div>
                        </div>
                    </form> 
                </div>
                </div>
            </div>
        </div>
	</div> 


<script type="text/javascript">
    (function($){ 
        $('#io-change-profile').on('submit',function(){  
            var t = $(this);
            t.find('.submit').text("<?php _e('保存中...','i_theme') ?>").attr("disabled",true);
            $.ajax({
                url: theme.ajaxurl, 
                data : $(this).serialize(),
                type: 'POST',
                dataType: 'json',
            })
            .done(function(response) {  
                t.find('.submit').text("<?php _e('保存资料','i_theme') ?>").removeAttr("disabled");
                showAlert(response); 
            })
            .fail(function() {  
                t.find('.submit').text("<?php _e('保存资料','i_theme') ?>").removeAttr("disabled");
                showAlert(JSON.parse('{"status":4,"msg":"<?php _e('网络错误！','i_theme') ?>"}'));
            });
            return false;
        });
        var uploaderAvatar = WebUploader.create({
            auto: true,
            server: "<?php echo get_theme_file_uri('/upload.php') ?>",
            pick: {
                id: '#io-avatar-picker',
                innerHTML: "",
                multiple: false
            }, 
            accept: {
                title: "Images",
                extensions: "jpg,jpeg,bmp,png",
                mimeTypes: "image/*"
            },
            compress: {
                width: 100,
                height: 100,
                quality: 90,
                allowMagnify: false,
                crop: true,
                preserveHeaders: true,
                noCompressIfLarger: false,
                compressSize: 0
            },
            formData: {
                imgFor: "avatar"
            }
        });
        uploaderAvatar.on( 'uploadSuccess', function( file , response) { 
            $(".io-avatar-custom").attr("src",response.data.avatar);
                showAlert(JSON.parse('{"status":1,"msg":"<?php _e('头像设置成功！','i_theme') ?>"}'));
        });
        uploaderAvatar.on( 'uploadError', function( file ) {
            showAlert(JSON.parse('{"status":4,"msg":"<?php _e('头像设置失败！','i_theme') ?>"}'));
        });

        var uploaderCover = WebUploader.create({
            auto: true,
            server: "<?php echo get_theme_file_uri('/upload.php') ?>",
            pick: {
                id: '.io-cover-picker',
                innerHTML: "",
                multiple: false
            }, 
            accept: {
                title: "Images",
                extensions: "jpg,jpeg,bmp,png",
                mimeTypes: "image/*"
            },
            compress: {
                width: 1400,
                height: 300,
                quality: 90,
                allowMagnify: false,
                crop: true,
                preserveHeaders: true,
                noCompressIfLarger: false,
                compressSize: 0
            },
            formData: {
                imgFor: "cover"
            }
        });
        uploaderCover.on( 'uploadSuccess', function( file , response) { 
            $(".io-cover-custom").attr("src",response.data.cover);
                showAlert(JSON.parse('{"status":1,"msg":"<?php _e('封面设置成功！','i_theme') ?>"}'));
        });
        uploaderCover.on( 'uploadError', function( file ) {
            showAlert(JSON.parse('{"status":4,"msg":"<?php _e('封面设置失败！','i_theme') ?>"}'));
        });
    })(jQuery);
</script> 

<?php get_footer(); ?>