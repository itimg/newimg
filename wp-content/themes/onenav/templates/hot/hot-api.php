<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:56:02
 * @LastEditors: iowen
 * @LastEditTime: 2021-10-01 16:13:51
 * @FilePath: \onenav\templates\hot\hot-api.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }  ?>
    <div class="hot-card-body">
        <div class="card hot-card mb-2">
            <div class="card-header widget-header d-flex align-items-center" style="background-color:transparent;border-bottom:0">
                <span><?php echo ($ico?'<img class="hot-ico" src="'. $ico .'" alt="'. $title .'">':'') ?><span class="title-<?php echo $t ?>"><?php echo $title ?></span></span>
                <span class="ml-auto d-none d-md-block text-xs slug-<?php echo $t ?>"></span>
            </div>
            <div class="card-body pb-3 pt-0">
                <div class="overflow-auto hot-body">
                    <div id="hot_news-<?php echo $t ?>">
                    </div>
                </div>
                <div class="d-flex text-xs text-muted pt-2 mb-n2">
                    <a href= "<?php echo esc_url(home_url('/hotnews/')) ?>" title='<?php _e('更多','i_theme') ?>' style="color:#6c757d; <?php echo ((is_home() || is_front_page() || is_page_template('template-mininav.php'))?'':'display:none') ?>" ><?php _e('更多','i_theme') ?></a>
                    <div class="flex-fill"></div>
                    <span><a href= "javascript:" id="hot-lod-<?php echo $t ?>" title='<?php _e('刷新','i_theme') ?>' style="color:#6c757d" ><i class="iconfont icon-refresh icon-lg"></i></a></span>
                </div>
            </div>
        </div>
        <div id="hot-loading-<?php echo $t ?>" class="ajax-loading text-center rounded" style="position:absolute;display:flex;width:100%;left:0;top:0;bottom:.5rem;background:rgba(125,125,125,.5)"><div id="hot-success-<?php echo $t ?>" class="col align-self-center"><i class="iconfont icon-loading icon-spin icon-2x"></i></div></div>
    </div>
<script>
(function($){ 
    var ruleId =  "<?php echo $rule_id ?>" ;
    getList(ruleId);
    
    $('#hot-lod-<?php echo $t ?>').on('click', function() {
        $(this).children('i').addClass('icon-spin');
        $("#hot-success-<?php echo $t ?>").html('<i class="iconfont icon-loading icon-spin icon-2x"></i>');
        $("#hot-loading-<?php echo $t ?>").fadeIn(200); 
        getList(ruleId);
    });
    function getList(id){
        $.post("<?php echo $api ?>", { rule_id: id,key:"<?php echo $key ?>" },function(response,status){ 
            var html = '';
            var data = response.data;
            $('.title-<?php echo $t ?>').text(response.title);
            $('.slug-<?php echo $t ?>').text(response.subtitle);
            if(response.type === 'hot'){
            for(var i=0;i<data.length;i++) {
                <?php if( io_get_option('hot_iframe') && $iframe && !wp_is_mobile()): ?>
                html += '<div class="d-flex text-sm mb-2"><div><span class="hot-rank hot-rank-'+ (data[i]['index'])+' text-xs text-center">'+ (data[i]['index'])+'</span><a class="ml-2" data-fancybox data-type="iframe" data-src="'+data[i]['link'].replace(/^https?:/,"")+'" href="javascript:;">'+data[i]['title']+'</a></div><div class="ml-auto hot-heat d-none d-md-block text-muted">'+data[i]['hot']+'</div></div>'
                <?php else: ?>
                html += '<div class="d-flex text-sm mb-2"><div><span class="hot-rank hot-rank-'+ (data[i]['index'])+' text-xs text-center">'+ (data[i]['index'])+'</span><a class="ml-2" href="'+data[i]['link']+'" target="_blank" rel="external noopener nofollow">'+data[i]['title']+'</a></div><div class="ml-auto hot-heat d-none d-md-block text-muted">'+data[i]['hot']+'</div></div>';
                <?php endif; ?>
            }
            }else if(response.type === 'taoke'){
            for(var i=0;i<data.length;i++) {
                html += '<div class="text-sm mb-3">';
                <?php if( io_get_option('hot_iframe') && $iframe && !wp_is_mobile()): ?>
                html += '<div class="mb-1"><span class="hot-rank hot-rank-'+ (data[i]['index']) +' text-xs text-center">'+ (data[i]['index']) +'</span><a class="ml-2" data-fancybox data-type="iframe" data-src="'+data[i]['link'].replace(/^https?:/,"")+'" href="javascript:;">'+data[i]['title']+'</a></div>';
                <?php else: ?>
                html += '<div class="mb-1"><span class="hot-rank hot-rank-'+ (data[i]['index']) +' text-xs text-center">'+ (data[i]['index']) +'</span><a class="ml-2" href="'+data[i]['link']+'" target="_blank" rel="external noopener nofollow">'+data[i]['title']+'</a></div>';
                <?php endif; ?>
                html += '<div class="d-flex">'+
                '<span class="hot-platform text-xs text-center"><span>'+ (data[i]['platform'])+'</span></span><div class="ml-auto d-none d-md-block text-muted">'+data[i]['hot']+'</div>'+
                '</div></div>';
            }
            }else{
            for(var i=0;i<data.length;i++) {
                <?php if( io_get_option('hot_iframe') && $iframe && !wp_is_mobile()): ?>
                html += '<div class="d-flex text-sm mb-2"><div><span class="hot-rank hot-rank-'+ (data[i]['index'])+' text-xs text-center">'+ (data[i]['index'])+'</span><a class="ml-2" data-fancybox data-type="iframe" data-src="'+data[i]['link'].replace(/^https?:/,"")+'" href="javascript:;">'+data[i]['title']+'</a></div></div>';
                <?php else: ?>
                html += '<div class="d-flex text-sm mb-2"><div><span class="hot-rank hot-rank-'+ (data[i]['index'])+' text-xs text-center">'+ (data[i]['index'])+'</span><a class="ml-2" href="'+data[i]['link']+'" target="_blank" rel="external noopener nofollow">'+data[i]['title']+'</a></div></div>';
                <?php endif; ?>
            } 
            }
            $("#hot-loading-<?php echo $t ?>").fadeOut(200); 
            $('#hot-lod-<?php echo $t ?>').children('i').removeClass('icon-spin');
            $("#hot_news-<?php echo $t ?>").html(html);
        }).fail(function () {
            $('#hot-lod-<?php echo $t ?>').children('i').removeClass('icon-spin');
            $("#hot-success-<?php echo $t ?>").show(200).html('<?php _e('获取失败，请再试一次！','i_theme') ?>').parent().delay(3500).fadeOut(200); 
        });
    }
})(jQuery)
</script> 