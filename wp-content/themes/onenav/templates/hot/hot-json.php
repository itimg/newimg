<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:56:02
 * @LastEditors: iowen
 * @LastEditTime: 2021-10-01 16:39:39
 * @FilePath: \onenav\templates\hot\hot-json.php
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
    var link_regular = "<?php echo $link_regular ?>" ;
    var datas = {<?php //if(!empty($request_data)) foreach($request_data as $v){echo 'data['.$v['key'].']'.':"'.$v['value'].'",';} ?>type:'<?php echo $type ?>', action:'get_hot_data',url:"<?php echo $api ?>"};
    getList(ruleId);
    $('#hot-lod-<?php echo $t ?>').on('click', function() {
        $(this).children('i').addClass('icon-spin');
        $("#hot-success-<?php echo $t ?>").html('<i class="iconfont icon-loading icon-spin icon-2x"></i>');
        $("#hot-loading-<?php echo $t ?>").fadeIn(200); 
        getList(ruleId);
    });
    function getList(id){
        $.ajax({
            url: "<?php echo admin_url( 'admin-ajax.php' ) ?>",
            type: "GET",  
            data : datas,
            dataType: 'json',
            cache: true,
        })
        .done(function(response) {  
            if(!response.status){
                $('#hot-lod-<?php echo $t ?>').children('i').removeClass('icon-spin');
                $("#hot-success-<?php echo $t ?>").show(200).html(response.data).parent().delay(3500).fadeOut(200); 
                return;
            }
            var html = '';
            var link = '';
            var data = response.data.<?php echo $datas_node ?>;
            $('.title-<?php echo $t ?>').text(<?php echo '"'.$title.'"' ?>);
            $('.slug-<?php echo $t ?>').text(<?php echo '"'.$subtitle.'"' ?>);
            <?php if (!empty($hot_node)): ?>
            for(var i=0;i<data.length;i++) {
                link = data[i].<?php echo $link_node ?>;
                if(link_regular!==''){
                    link = link_regular.replace("%s%",link);
                }
                <?php if( io_get_option('hot_iframe') && $iframe && !wp_is_mobile()): ?>
                html += '<div class="d-flex text-sm mb-2"><div><span class="hot-rank hot-rank-'+ (i+1)+' text-xs text-center">'+ (i+1)+'</span><a class="ml-2" data-fancybox data-type="iframe" data-src="'+link.replace(/^https?:/,"")+'" href="javascript:;">'+data[i].<?php echo $title_node ?>+'</a></div><div class="ml-auto hot-heat d-none d-md-block text-muted">'+data[i].<?php echo $hot_node ?>+'</div></div>'
                <?php else: ?>
                html += '<div class="d-flex text-sm mb-2"><div><span class="hot-rank hot-rank-'+ (i+1)+' text-xs text-center">'+ (i+1)+'</span><a class="ml-2" href="'+link+'" target="_blank" rel="external noopener nofollow">'+data[i].<?php echo $title_node ?>+'</a></div><div class="ml-auto hot-heat d-none d-md-block text-muted">'+data[i].<?php echo $hot_node ?>+'</div></div>';
                <?php endif; ?>
            }
            <?php else: ?>
            for(var i=0;i<data.length;i++) {
                link = data[i].<?php echo $link_node ?>;
                if(link_regular!==''){
                    link = link_regular.replace("%s%",link);
                }
                <?php if( io_get_option('hot_iframe') && $iframe && !wp_is_mobile()): ?>
                html += '<div class="d-flex text-sm mb-2"><div><span class="hot-rank hot-rank-'+ (i+1)+' text-xs text-center">'+ (i+1)+'</span><a class="ml-2" data-fancybox data-type="iframe" data-src="'+link.replace(/^https?:/,"")+'" href="javascript:;">'+data[i].<?php echo $title_node ?>+'</a></div></div>';
                <?php else: ?>
                html += '<div class="d-flex text-sm mb-2"><div><span class="hot-rank hot-rank-'+ (i+1)+' text-xs text-center">'+ (i+1)+'</span><a class="ml-2" href="'+link+'" target="_blank" rel="external noopener nofollow">'+data[i].<?php echo $title_node ?>+'</a></div></div>';
                <?php endif; ?>
            } 
            <?php endif; ?>
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