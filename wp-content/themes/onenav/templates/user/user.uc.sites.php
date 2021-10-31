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
global $current_user, $iodb;
$customize_terms = $iodb->getTerm($current_user->ID); 
$element = '';
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
                <div class="card drop-add-url-over">
                <div class="card-body">
                    <div class="text-lg pb-3 border-bottom border-light border-2w mb-3 d-flex">
                        <span><?php _e('网址管理','i_theme') ?></span>
                        <a class="ml-2 btn btn-sm btn-light" href="<?php echo esc_url(home_url('/bookmark/'.base64_io_encode(sprintf("%08d", $current_user->ID)))) ?>"><?php _e('我的书签', 'i_theme'); ?></a>
                        <div class="ml-auto">
                            <div class="position-relative" data-toggle="tooltip" data-placement="top" title="<?php _e('导入 chrome 书签','i_theme') ?>" style="overflow: hidden">
                                <i class="iconfont icon-upload text-xl"></i>
                                <input type="file" id="upload-bookmark" name="file" class="position-absolute" style="top: 0;left: 0;opacity: 0;">
                                <span class="text-xs"><?php _e('导入 chrome 书签','i_theme') ?></span>
                            </div>
                        </div>
                    </div>  
                    <?php if($customize_terms){ ?>
                    <div id="sites-tabs" class="manage-sites">
                        <?php wp_nonce_field('sites-manage'); ?>
                        <div class="row py-4">
                            <div class="col-6 col-md-3">
                                <ul id="terms-list" class="nav flex-column nav-pills sites-nav" role="tablist" aria-orientation="vertical">
                                <?php   
                                $i_t = 0;
                                foreach($customize_terms as $c_term){
                                    echo '
                                    <li id="termsli-'.$c_term->id.'" class="mb-2 sites-li text-break-all" data-term_id="'.$c_term->id.'">
                                        <a class="nav-link'.($i_t == 0?' active':'').'" id="terms-'.$c_term->id.'-tab" data-toggle="pill" href="#terms-'.$c_term->id.'" role="tab" aria-controls="terms-'.$c_term->id.'" aria-selected="'.($i_t == 0?'true':'false').'">'.stripslashes($c_term->name).'</a>
                                        <input type="text" class="nav-link change-terms-name" name="change-terms" placeholder="'.stripslashes($c_term->name).'" style="display: none;">
                                        <div class="sites-setting">
                                            <a href="javascript:;" class="text-center edit-terms" data-action="edit_custom_terms" data-id="'.$c_term->id.'" data-name="'.stripslashes($c_term->name).'" style="" title="'.__('修改','i_theme').'"><i class="iconfont icon-modify"></i></a>
                                            <a href="javascript:;" class="text-center remove-terms" data-action="delete_custom_terms" data-id="'.$c_term->id.'" data-name="'.stripslashes($c_term->name).'" style="" title="'.__('删除','i_theme').'"><i class="iconfont icon-close-circle"></i></a>
                                        </div>
                                    </li>';
                                    $element .= '#terms-'.$c_term->id.' .row,';
                                    $i_t ++;
                                }
                                $element = rtrim($element, ",");
                                ?>
                                    <li class="mb-2 sites-li sortable-disabled">
                                        <input id="new-terms-name" type="text" class="nav-link" name="new-terms" placeholder="<?php _e('添加分类','i_theme') ?>">
                                        <div class="sites-setting">
                                            <a href="javascript:;" class="text-center add-terms" data-action="add_custom_terms" style=""><i class="iconfont icon-adopt"></i></a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="sidebar col-6 col-md-9">
                                <div class="tab-content">
                                <?php
                                $i_u = 0;
                                foreach($customize_terms as $c_term){
                                ?>
                                <ul class="drop-add-url position-relative tab-pane sites-lists fade<?php echo ($i_u == 0?' active show':'') ?>" id="terms-<?php echo $c_term->id ?>" role="tabpanel" aria-labelledby="terms-<?php echo $c_term->id ?>-tab">
                                    <div class="row row-sm" data-terms_id="<?php echo $c_term->id ?>">
                                        <?php 
                                        $c_urls = $iodb->getUrlWhereTerm($current_user->ID,$c_term->id);
                                        if($c_urls){ 
                                            $default_ico = get_theme_file_uri('/images/favicon.png');
                                            foreach($c_urls as $c_url){ 
                                                $ico = (io_get_option('ico-source')['ico_url'] .format_url($c_url->url) . io_get_option('ico-source')['ico_png']);
                                        ?>
                                        <li id="url-<?php echo $c_url->id ?>" class="col-12 col-md-6 col-lg-4 mb-2 sites-li" data-sites_id="<?php echo $c_url->id ?>" title="<?php echo $c_url->url_name ?>">
                                            <div class="rounded sites-card position-relative">
                                                <div class="overflowClip_1">
                                                <i class="iconfont icon-globe"></i>
                                                <span class="sites-name"><?php echo $c_url->url_name ?></span>
                                                </div>
                                                <div class="sites-setting">
                                                    <?php if(!$c_url->post_id): ?>
                                                    <a href="javascript:;" class="text-center edit-site"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-action="edit_custom_url" title="<?php _e('修改','i_theme') ?>"><i class="iconfont icon-modify"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <form class="px-4 py-3 edit-site-form">
                                                            <input type="hidden" name="action" value="edit_custom_url">
                                                            <input type="hidden" name="url_id" value="<?php echo $c_url->id ?>">
                                                            <input type="text" class="form-control mb-2" name="url_name" placeholder="<?php echo $c_url->url_name ?>" value="<?php echo $c_url->url_name ?>" autocomplete="off" required >
                                                            <input type="url" class="form-control mb-2" name="url" placeholder="<?php echo $c_url->url ?>" value="<?php echo $c_url->url ?>" autocomplete="off" required >
                                                            <textarea class="form-control mb-2" name="url_summary" placeholder="<?php _e('输入简介（选填）','i_theme') ?>"><?php echo $c_url->summary ?></textarea>
                                                            <button type="submit" class="btn btn-danger btn-block"><?php _e('提交','i_theme') ?></button>
                                                        </form>
                                                    </div>
                                                    <?php endif; ?>
                                                    <a href="javascript:;" class="text-center remove-cm-site" data-action="delete_custom_url" data-id="<?=$c_url->id ?>" data-name="<?=$c_url->url_name ?>" style="" title="<?php _e('删除','i_theme') ?>"><i class="iconfont icon-close-circle"></i></a>
                                                </div>
                                            </div>
                                        </li>
                                        <?php 
                                            }
                                        }else{
                                        
                                        } 
                                        ?>
                                        <li id="add-sites-terms-id-<?php echo $c_term->id ?>" class="sortable-disabled col-12 col-md-6 col-lg-4 mb-2 sites-li">
                                            <a href="javascript:;" class="rounded terms-id-<?php echo $c_term->id ?> sites-card position-relative add text-center d-block" data-terms_id="<?php echo $c_term->id ?>" data-new_url="" data-toggle="modal" data-target="#add-new-sites-modal">+</a>
                                        </li>
                                    </div>
                                    <div class="drag-add-bookmarks rounded position-absolute w-100 h-100 bg-light text-center" style="top:0;display:none" data-terms_id="<?php echo $c_term->id ?>"><div class="h-100 d-flex align-content-center flex-wrap"><span class="mx-auto"><i class="iconfont icon-shangchuan icon-4x d-block text-muted"></i><?php echo sprintf(__('添加书签到“%s”','i_theme'),stripslashes($c_term->name)) ?></span></div></div>
                                </ul>
                                <?php 
                                    $i_u ++;
                                }
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }else{ ?>
                    <div class="row py-4">
                        <div class="col-6 col-md-3">
                            <ul id="terms-list" class="nav flex-column nav-pills sites-nav" role="tablist" aria-orientation="vertical">
                                <li class="mb-2 sites-li sortable-disabled">
                                    <input id="new-terms-name" type="text" class="nav-link" name="new-terms" placeholder="<?php _e('添加分类','i_theme') ?>">
                                    <div class="sites-setting">
                                        <a href="javascript:;" class="text-center add-terms" data-action="add_custom_terms" style=""><i class="iconfont icon-adopt"></i></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-6 col-md-9">
                            <div class="empty-content text-center pb-5">
                                <i class="iconfont icon-nothing1"></i>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                </div>
            </div>
        </div>
	</div> 
<?php
$categories= get_categories(array(
    'taxonomy'     => 'favorites',
    'meta_key'     => '_term_order',
    'orderby'      => 'meta_value_num',
    'order'        => 'desc',
    'hide_empty'   => 1,
    )
);
$parent_terms=[];
foreach($categories as $category) {
    if($category->category_parent != 0){
        if(!in_array($category->category_parent,$parent_terms))
            $parent_terms[] = $category->category_parent;
    }
} 
?>
    <div class="modal fade add_new_sites_modal" id="add-new-sites-modal" tabindex="-1" role="dialog" aria-labelledby="add-new-sites-title" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add-new-sites-title"><?php _e('添加网址','i_theme') ?></h5>
                    <button type="button" id="close-sites-modal" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="iconfont icon-close-circle text-xl"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form class="col-12 col-lg-8 mx-auto uc-add-custom-site-form">
                            <div class="form-row">
                                <input type="hidden" name="action" value="add_custom_url">
                                <input type="hidden" name="term_id" class="add-to-term-id" value="">
                                <?php wp_nonce_field('add_custom_site_form') ?>
                                <div class="col-12 col-md-6">
                                    <input type="url" id="modal-new-url" class="form-control mb-2 pr-4" name="url" placeholder="<?php _e('URL 地址（http://）','i_theme') ?>" autocomplete="off" required >
                                    <i id="modal-new-url-ico" class="iconfont icon-loading icon-spin position-absolute" style="top:10px;color:red;right:10px;display:none;text-shadow:0 0 6px red"></i>
                                </div>
                                <div class="col-12 col-md-6"><input type="text" class="form-control mb-2" name="url_name" placeholder="<?php _e('名称','i_theme') ?>" autocomplete="off" required ></div>
                                <div class="col-12">
                                    <textarea id="modal-new-url-summary" class="form-control mb-2" name="url_summary" placeholder="<?php _e('输入简介（选填）','i_theme') ?>" style="resize:none;padding-right:65px"></textarea>
                                    <button type="submit" class="btn btn-danger position-absolute" style="top:5px;right:10px;height:45px"><?php _e('提交','i_theme') ?></button>
                                    <div class="invalid-feedback refre_msg"><i class="iconfont icon-tishi"></i> <?php _e('网址信息获取失败，请再试试，或者手动填写。','i_theme') ?></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="admin-sites-list" class="row mt-5 admin-sites">
                        <p class="col-12 text-lg mb-4"><?php _e('添加官方收录网址到书签','i_theme') ?></p>
                        <div class="col-5 col-md-3 col-lg-2 overflow-auto">
                            <div class="nav flex-column nav-pills sites-nav" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <?php 
                                $the_first = 0;
                                foreach($categories as $category) { 
                                    if(!in_array($category->term_id,$parent_terms)){
                                        echo '<a href="javascript:;" class="nav-link load-sites-manager-post mb-2'.($the_first==0?' active':'').'" data-toggle="pill" data-action="load_sites_manager" data-term_id="'.$category->term_id.'" role="tab" aria-selected="'.($the_first==0?'true':'').'">'.$category->name.'</a>';
                                        if($the_first == 0)
                                            $the_first = $category->term_id;
                                    } 
                                }  
                                ?> 
                            </div>
                        </div>
                        <div class="col-7 col-md-9 col-lg-10 overflow-auto">
                            <div id="admin-url-list" class="row row-sm">
                            <?php 
                            if($the_first != 0){
                                $args = array(   
                                    'post_type'           => 'sites',
                                    //'ignore_sticky_posts' => 1,              
                                    'posts_per_page'      => -1,    
                                    'post_status'         => array( 'publish' ),
                                    'orderby'             => 'menu_order',
                                    'order'               => 'ASC',
                                    'tax_query'           => array(
                                        array(
                                            'taxonomy' => 'favorites',       
                                            'field'    => 'id',            
                                            'terms'    => $the_first,    
                                        )
                                    ),
                                );
                                $myposts = new WP_Query( $args );
                                if ($myposts->have_posts()): while ($myposts->have_posts()): $myposts->the_post(); 
                                if(get_post_meta($post->ID, '_sites_type', true)=='sites'):
                                    $isAdd = in_array($current_user->ID, array_unique(get_post_meta($post->ID, 'io_post_add_custom_users',false)));
                                ?>
                                <div id="url-<?php echo $post->ID ?>" class="col-12 col-md-4 col-lg-3 mb-2 sites-li admin-li" data-sites_id="<?php echo $post->ID ?>" title="<?php the_title() ?>">
                                    <div class="rounded sites-card position-relative">
                                        <div class="d-flex align-items-center">
                                            <div class=" rounded-circle mr-2 d-flex align-items-center justify-content-center">
                                                <i class="iconfont icon-globe"></i>
                                            </div>
                                            <div class="flex-fill overflow-hidden">
                                                <span class="sites-name overflowClip_1"><?php the_title() ?></span>
                                                <span class="sites-name overflowClip_1 text-xs text-muted"><?php echo get_post_meta($post->ID, '_sites_sescribe', true)?:"..." ?></span>
                                            </div>
                                        </div>
                                        <div class="sites-setting">
                                            <a href="javascript:;" id="admin-sites-id-<?php echo $post->ID ?>" class="text-center add-admin-site <?php echo $isAdd?'add':'' ?>" data-action="add_custom_url" data-_wpnonce="<?=wp_create_nonce('add_custom_site_form') ?>" data-post_id="<?=$post->ID ?>" data-url_name="<?php the_title() ?>" data-url="<?php echo get_post_meta($post->ID, '_sites_link', true) ?>" data-url_summary="<?php echo get_post_meta($post->ID, '_sites_sescribe', true)?:"" ?>" data-url_ico="<?php echo get_post_meta($post->ID, '_thumbnail', true)?:"" ?>" style="" title="<?php echo $isAdd?__('已添加','i_theme'):__('添加','i_theme') ?>"><i class="iconfont <?php echo $isAdd?'text-danger icon-subtract':'icon-add' ?>"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                endif; endwhile; endif;
                                wp_reset_postdata();
                            }
                            ?>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>

<script>
$(function() {
    $('#upload-bookmark').on('change', function (event) {
        event.preventDefault();
        var t = $(this);
        var selectedFile = t[0].files[0];
        if(selectedFile){
            if ( selectedFile.type != "text/html")
            {
                showAlert(JSON.parse('{"status":4,"msg":"<?php _e('选取的文件必须是HTML文件！','i_theme') ?>"}'));
                return;
            }
            ioConfirm("<div><p class='text-xl text-center mb-3'><?php _e('你选取了文件： ','i_theme') ?></p>"+selectedFile.name+"<br><?php _e('是否继续导入？','i_theme') ?></div>",function(result){
                if(result){
                    var load = loadingShow();
                    runConverterSelectedFile(function(result){
                        $.ajax({
                            url: theme.ajaxurl,
                            type: 'POST', 
                            dataType: 'json',
                            data : {
                                action: 'upload_bookmark',
                                ubnonce: '<?=wp_create_nonce('upload_bookmark_cb') ?>',
                                bookmark: Base64.encode(result),
                            },
                        })
                        .done(function(response) {  
                            loadingHid(load); 
                            if(response.status == 1){
                                ioPopupTips(response.status, response.msg, function(){
                                    location.reload();
                                });
                                return;
                            }
                            t.val('');
                            ioPopupTips(response.status, response.msg);
                        })
                        .fail(function() { 
                            t.val('');
                            loadingHid(load);
                            ioPopupTips(4, "<?php _e('网络错误 --.','i_theme') ?>"); 
                        }) 
                    });
                }else{
                    t.val('');
                }
            });
        }
    });
    function runConverterSelectedFile(callback)
    {
        var chromBookmarkConverter = new ChromBookmarkConverter();
        var selectedFile = document.getElementById('upload-bookmark').files[0];
        if (selectedFile) 
        { 
            if (selectedFile.type != "text/html")
            {
                showAlert(JSON.parse('{"status":4,"msg":"<?php _e('选取的文件必须是HTML文件！','i_theme') ?>"}'));
                return;
            }
            var reader = new FileReader();
            reader.onload = function (evt) 
            { 
                var fileContents = evt.target.result; 
                chromBookmarkConverter.processChromeBookmarksContent(fileContents);
                var bookmarksJson = JSON.stringify(chromBookmarkConverter.bookmarks);
                callback(bookmarksJson);
            };
            reader.onerror = function (evt) 
            {
                showAlert(JSON.parse('{"status":4,"msg":"<?php _e('读取文件时发生错误！','i_theme') ?>"}'));
            };
            reader.readAsText(selectedFile, "UTF-8");
        }
    }
    var timer;
    $('.drop-add-url').on('dragover', function (event) {
        event.preventDefault();
        $(this).find('.drag-add-bookmarks').show();
        clearTimeout(timer);
    });
    $('#add-new-sites-modal').on('dragover', function (event) {
        event.preventDefault();
        $('#close-sites-modal').click();
    });
    $('.drop-add-url').on('dragleave', function (event) {
        event.preventDefault();
        timer = setTimeout(function() {
            $('.drag-add-bookmarks').hide();
        }, 200);
    });
    $('.drop-add-url').on('drop', function (event) {
        
        timer = setTimeout(function() {
            $('.drag-add-bookmarks').hide(200);
        }, 200);
        if(Array.isArray(event.originalEvent.dataTransfer.types) && event.originalEvent.dataTransfer.types.indexOf('text/uri-list')!=-1){
            event.preventDefault();
            let url = event.originalEvent.dataTransfer.getData("URL");
            if(isURL(url))
                $('.terms-id-'+$(this).find('.drag-add-bookmarks').data('terms_id')).data('new_url',url).click();
            else
                showAlert(JSON.parse('{"status":4,"msg":"<?php _e('URL 无效！','i_theme') ?>"}'));
        }else{
            return true;
        }
    });
    $(document).on("click",'.load-sites-manager-post', function(event){  
        event.preventDefault();
        var load = loadingShow('#admin-sites-list');
        var t = $(this); 
        $.ajax({
            url: theme.ajaxurl,
            type: 'POST', 
            dataType: 'html',
            data : {
                action : 'load_sites_manager',
                term_id: t.data('term_id'),
            },
        })
        .done(function(response) {   
            $('#admin-url-list').html(response);
            loadingHid(load);
        })
        .fail(function() { 
            showAlert(JSON.parse('{"status":4,"msg":"<?php _e('网络错误 --.','i_theme') ?>"}'));
            loadingHid(load);
        }) 
    });
    $(document).on("click",'.add-admin-site', function(event){ 
        if($(this).hasClass('add')) return;
        var t = $(this); 
        t.addClass('disabled');
        var dataParam = t.data();
        dataParam["term_id"]= $('.add-to-term-id').val();
        $.ajax({
            url: theme.ajaxurl,
            type: 'POST', 
            dataType: 'json',
            data : dataParam,
        })
        .done(function(response) {   
            if(response.status == 1){
                var url = t.data('url');
                var name = t.data('url_name');
                var term_id = $('.add-to-term-id').val();
                var summary = t.data('url_summary'); 
                var html = addNewSites(response.id,name,url,summary,true);
                $('#add-sites-terms-id-'+term_id).before(html);
                $('#admin-sites-id-'+t.data('post_id')).addClass('add').find('i').removeClass('icon-add').addClass('text-danger icon-subtract');
            }
            t.removeClass('disabled');
            showAlert(response);
        })
        .fail(function() { 
            t.removeClass('disabled');
            showAlert(JSON.parse('{"status":4,"msg":"<?php _e('网络错误 --.','i_theme') ?>"}'));
        }) 
    });
    $(document).on("submit",'.uc-add-custom-site-form', function(){
        var t = $(this); 
        if(!t.find("[name='url_name']").val() || !t.find("[name='url']").val()){
            showAlert(JSON.parse('{"status":4,"msg":"<?php _e('请输入内容！','i_theme') ?>"}'));
            return false;
        }; 
        $.ajax({
            url: theme.ajaxurl,
            type: 'POST', 
            dataType: 'json',
            cache: false,
            data: t.serialize()
        })
        .done(function(response) {   
            if(response.status == 1){
                var url = t.find("[name='url']").val();
                var name = t.find("[name='url_name']").val();
                var term_id = t.find("[name='term_id']").val();
                var summary = t.find("[name='url_summary']").val(); 
                var html = addNewSites(response.id,name,url,summary);
                $('#add-sites-terms-id-'+term_id).before(html);
                t.find('[name="url"]').val('');
                t.find('[name="url_name"]').val('');
                t.find('[name="url_summary"]').val('');
            } 
            showAlert(response);
        })
        .fail(function() {  
            showAlert(JSON.parse('{"status":4,"msg":"<?php _e('网络错误 --.','i_theme') ?>"}'));
        });
        return false;
    });
    function addNewSites(id,name,url,summary,admin=false){
        var sites = '<li id="url-'+id+'" class="col-12 col-md-6 col-lg-4 mb-2 sites-li" data-sites_id="'+id+'" title="'+name+'">'+
            '<div class="rounded sites-card position-relative">'+
                '<div class="overflowClip_1">'+
                '<i class="iconfont icon-globe"></i> '+
                '<span class="sites-name">'+name+'</span>'+
                '</div>'+
                '<div class="sites-setting">';
        if(!admin){
            sites += '<a href="javascript:;" class="text-center edit-site"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-action="edit_custom_url" title="<?php _e('修改','i_theme') ?>"><i class="iconfont icon-modify"></i></a> '+
                    '<div class="dropdown-menu dropdown-menu-right">'+
                        '<form class="px-4 py-3 edit-site-form">'+
                            '<input type="hidden" name="action" value="edit_custom_url">'+
                            '<input type="hidden" name="url_id" value="'+id+'">'+
                            '<input type="text" class="form-control mb-2" name="url_name" placeholder="'+name+'" value="'+name+'" autocomplete="off" required >'+
                            '<input type="url" class="form-control mb-2" name="url" placeholder="'+url+'" value="'+url+'" autocomplete="off" required >'+
                            '<textarea class="form-control mb-2" name="url_summary" placeholder="<?php _e('输入简介（选填）','i_theme') ?>">'+summary+'</textarea>'+
                            '<button type="submit" class="btn btn-primary btn-block"><?php _e('提交','i_theme') ?></button>'+
                        '</form>'+
                    '</div>';
        }
            sites += '<a href="javascript:;" class="text-center remove-cm-site" data-action="delete_custom_url" data-id="'+id+'" data-name="'+name+'" style="" title="<?php _e('删除','i_theme') ?>"><i class="iconfont icon-close-circle"></i></a>'+
                '</div>'+
            '</div>'+
        '</li>';
        return sites;
    }
    $(document).on("submit",'.edit-site-form', function(event){
        event.preventDefault(); 
        var t = $(this); 
        if(!t.find("[name='url_name']").val() || !t.find("[name='url']").val()){
            showAlert(JSON.parse('{"status":4,"msg":"<?php _e('请输入内容！','i_theme') ?>"}'));
            return true;
        };
        $.ajax({
            url: theme.ajaxurl,
            type: 'POST', 
            dataType: 'json',
            data: t.serialize() + "&term_id="+t.parents('.row.row-sm').data("terms_id"),
        })
        .done(function(response) {   
            if(response.status == 1){
                var name = t.find("[name='url_name']").val();
                t.parents('li.sites-li').find('.sites-name').text(name);
                t.parents('li.sites-li').find('a.remove-cm-site').data('name',name);
            } 
            showAlert(response);
        })
        .fail(function() {  
            showAlert(JSON.parse('{"status":4,"msg":"<?php _e('网络错误 --.','i_theme') ?>"}'));
        }); 
        return false;
    });
    $("#terms-list").sortable({
        items: "li:not(.sortable-disabled)",
        axis: "y",
        distance: 15,
        cursor: "move",
        containment: ".manage-sites",
        placeholder: "terms-placeholder rounded mb-2",
        update: function(event, ui) {
            var load = loadingShow('#sites-tabs');
            var t = $(this); 
            var order = t.sortable('serialize');
            $("#terms-list").sortable('disable');
            $.ajax({
                url: theme.ajaxurl,
                type: 'POST',
                cache: false,
                dataType: "json",
                data: {
                    action: 'update_custom_terms_order',
                    order: order,
                }
            })
            .done(function(response) {   
                if(response.status != 1){
                    showAlert(response);
                }
                $("#terms-list").sortable('enable');
                loadingHid(load);
            })
            .fail(function() {
                $("#terms-list").sortable('enable');
                showAlert(JSON.parse('{"status":4,"msg":"<?php _e('网络错误 --.','i_theme') ?>"}'));
                loadingHid(load);
            })

        }
    }).disableSelection();
    var isTerms = false; 
    $("<?php echo $element ?>").sortable({
        items: "li:not(.sortable-disabled)",
        tolerance: "pointer",
        containment: ".manage-sites",
        start: function( event, ui ) {
            isTerms = false; 
        },
        update: function(event, ui) {
            if(!isTerms){
                var t = $(this);
                var order = t.sortable('serialize');
                sitesSorting(t.sortable('serialize'),t.data('terms_id'));
            }
        }
    }).disableSelection();

    var $tab_items = $("ul:first li:not(.sortable-disabled)", $("#sites-tabs")).droppable({
        accept: ".sites-lists li",
        hoverClass: "sites-hover",
        drop: function(event, ui) {
            isTerms = true;
            if(ui.draggable.parents('ul').attr('id') == $(this).find("a.nav-link").attr("aria-controls"))
                return;
            var $item = $(this);
            var $list = $($item.find("a.nav-link").attr("href")).find(".row");
            ui.draggable.hide(function() {
                var sites_id = $(this).data('sites_id');
                var terms_id = $item.data('term_id');
                sitesToTerms(sites_id,terms_id);
                $(this).insertBefore($list.find('.sortable-disabled')).show("slow"); 
                
                $item.find("a.nav-link").click();
                /* 执行排序 */
                if($list.find('li').length>2){
                    sitesSorting($list.sortable('serialize'),terms_id);
                }
            });
        }
    });
    function sitesSorting(order,terms_id){
        var load = loadingShow('#sites-tabs');
        $("<?php echo $element ?>").sortable('disable');
        $.ajax({
            url: theme.ajaxurl,
            type: 'POST',
            cache: false,
            dataType: "json",
            data: {
				action: 'update_custom_url_order',
                order: order,
                term_id: terms_id,
            }
        })
        .done(function(response) {   
            if(response.status != 1){
                showAlert(response);
            }
            $("<?php echo $element ?>").sortable('enable');
            loadingHid(load);
        })
        .fail(function() {
            $("<?php echo $element ?>").sortable('enable');
            showAlert(JSON.parse('{"status":4,"msg":"<?php _e('网络错误 --.','i_theme') ?>"}'));
            loadingHid(load);
        })
    }; 
    
    function sitesToTerms(sites_id,terms_id){
        var load = loadingShow('#sites-tabs');
        $("<?php echo $element ?>").sortable('disable');
        $.ajax({
            url: theme.ajaxurl,
            type: 'POST', 
            dataType: 'json',
            cache: false,
            data: {
				action: 'sites_to_terms',
                sites_id: sites_id,
                terms_id: terms_id,
            },
        })
        .done(function(response) {   
            if(response.status != 1){
                showAlert(response);
            }
            $("<?php echo $element ?>").sortable('enable');
            loadingHid(load);
        })
        .fail(function() {
            $("<?php echo $element ?>").sortable('enable');
            showAlert(JSON.parse('{"status":4,"msg":"<?php _e('网络错误 --.','i_theme') ?>"}'));
            loadingHid(load);
        })
    }; 
    $(document).on("click",'.sites-card .remove-cm-site', function(event){ 
        var t = $(this); 
        t.addClass('disabled');
        $.ajax({
            url: theme.ajaxurl,
            type: 'POST', 
            dataType: 'json',
            data : t.data(),
        })
        .done(function(response) {   
            if(response.status == 1){
                t.parents('.sites-li').fadeOut("slow",function(){
                    $(this).remove();
                });
            }
            t.removeClass('disabled');
            showAlert(response);
        })
        .fail(function() { 
            t.removeClass('disabled');
            showAlert(JSON.parse('{"status":4,"msg":"<?php _e('网络错误 --.','i_theme') ?>"}'));
        }) 
    });
    $(document).on("click",'.sites-nav .remove-terms', function(event){ 
        var t = $(this); 
        var p = t.parents('li.sites-li');
        if(isTermsEdit && current[0]){
            if( current[0]==p.find('.change-terms-name')[0] ){
                isTermsEdit = false;
                cancelEdit(current.parents('li.sites-li'));
                return;
            }else{
                isTermsEdit = false;
                cancelEdit(current.parents('li.sites-li'));
            }
        }
        var url_i = $("#terms-"+t.data('id')+"").find('li').length-1;
        if( url_i>0 ){
            ioConfirm("<div><?php _e('此分类内包含多个网址，是否继续删除分类并清空网址？','i_theme') ?></div>",function(result){
                if(result){
                    removeTerms(t,1);
                }
            });
            return true;
        }
        removeTerms(t,0);
    });
    function removeTerms(doc,clean){
        var dataParam = doc.data();
        dataParam['clean'] = clean;
        doc.addClass('disabled');
        $.ajax({
            url: theme.ajaxurl,
            type: 'POST', 
            dataType: 'json',
            data : dataParam,
        })
        .done(function(response) {   
            if(response.status == 1){
                doc.parents('.sites-li').fadeOut("slow",function(){
                    $(this).remove();
                });
                $('#terms-'+doc.data('id')).remove();
            }
            doc.removeClass('disabled');
            showAlert(response);
        })
        .fail(function() { 
            doc.removeClass('disabled');
            showAlert(JSON.parse('{"status":4,"msg":"<?php _e('网络错误 --.','i_theme') ?>"}'));
        })
    }
    $(document).on("click",'.sites-nav .add-terms', function(event){ 
        var t = $(this); 
        if(!$("#new-terms-name").val()){
            showAlert(JSON.parse('{"status":4,"msg":"<?php _e('请输入分类名称！','i_theme') ?>"}'));
            return true;
        }
        t.addClass('disabled');
        $.ajax({
            url: theme.ajaxurl,
            type: 'POST', 
            dataType: 'json',
            data: {
				action: 'add_custom_terms',
                name: $("#new-terms-name").val(),
            },
        })
        .done(function(response) {   
            if(response.status == 1){
                location.reload();
                return;
            }
            t.removeClass('disabled');
            showAlert(response);
        })
        .fail(function() { 
            t.removeClass('disabled');
            showAlert(JSON.parse('{"status":4,"msg":"<?php _e('网络错误 --.','i_theme') ?>"}'));
        }) 
    });
    var isTermsEdit = false;
    var current ='';
    $(document).on("click",'.sites-nav .edit-terms', function(event){ 
        var t = $(this); 
        var p = t.parents('li.sites-li');
        if(current[0] && current[0]!=p.find('.change-terms-name')[0]){
            isTermsEdit = false;
        }
        if(!isTermsEdit){
            p.find('a.nav-link').removeClass('d-block').addClass('d-none');
            p.find('.change-terms-name').removeClass('d-none').addClass('d-block').focus().on("blur",termsBlur);
            t.attr("title","<?php _e('提交','i_theme') ?>").html('<i class="iconfont icon-adopt"></i>');
            isTermsEdit = true;
            current = p.find('.change-terms-name');
        }else{
            var new_name = p.find('.change-terms-name').val();
            if(!new_name){
                showAlert(JSON.parse('{"status":4,"msg":"<?php _e('请输入分类名称！','i_theme') ?>"}'));
                return true;
            }
            if(chack_name(new_name)){
                ioPopupTips(4,"<?php _e('分类名称不能使用特殊字符和空格！','i_theme') ?>");
                return true;
            }
            if(new_name == t.data('name')){
                showAlert(JSON.parse('{"status":4,"msg":"<?php _e('名称没有变化！','i_theme') ?>"}'));
                return true;
            }
            t.addClass('disabled');
            $.ajax({
                url: theme.ajaxurl,
                type: 'POST', 
                dataType: 'json',
                data: {
                    action: 'edit_custom_terms',
                    id: t.data('id'),
                    name: new_name,
                    old_name: t.data('name'),
                },
            })
            .done(function(response) {   
                if(response.status == 1){
                    t.data('name',new_name);
                    p.find('a.remove-terms').data('name',new_name);
                    p.find('a.nav-link').text(new_name);
                    p.find('.change-terms-name').attr('placeholder',new_name);
                    isTermsEdit = false;
                    cancelEdit( p );
                }
                t.removeClass('disabled');
                showAlert(response);
            })
            .fail(function() { 
                t.removeClass('disabled');
                showAlert(JSON.parse('{"status":4,"msg":"<?php _e('网络错误 --.','i_theme') ?>"}'));
            }) 
        }
    });
    function termsBlur(event){
        isTermsEdit = false;
        var t = $(event.target); 
        var p = t.parents('li.sites-li');
        cancelEdit( p );
    }
    function cancelEdit( p ){ 
        p.find('a.nav-link').removeClass('d-none').addClass('d-block');
        p.find('.change-terms-name').removeClass('d-block').addClass('d-none').unbind('blur');
        p.find('a.edit-terms').attr("title","<?php _e('修改','i_theme') ?>").html('<i class="iconfont icon-modify"></i>');
        if(!isTermsEdit) {
            current ='';
        }
    };
});
</script>
<?php get_footer(); ?>