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
<div class="main-content flex-fill single">
<?php get_template_part( 'templates/header','banner' ); ?>
<div id="content" class="container my-4 my-md-5">
                <?php 
                $sites_type = get_post_meta(get_the_ID(), '_sites_type', true);
                if($sites_type == "down") include( get_theme_file_path('/templates/content-down.php') );
                else include( get_theme_file_path('/templates/content-site.php') );
                ?>

                <h2 class="text-gray text-lg my-4"><i class="site-tag iconfont icon-tag icon-lg mr-1" ></i><?php _e('相关导航','i_theme') ?></h2>
                <div class="row mb-n4 customize-site"> 
                    <?php get_template_part( 'templates/related','sites' ); ?>
                </div>
                <?php 
                if ( comments_open() || get_comments_number() ) :
                comments_template();
                endif; 
                ?>
                
        </div><!-- col- end -->
        <?php get_sidebar('sites');  ?>
    </div><!-- row end -->
</div>
<?php if($sites_type == "sites" && io_get_option("show_speed")){ ?>
<script type='text/javascript'>
    loadFunc(function() {
        var urlStatus = '<?php theUrlStatus($m_link_url) ?>';
        if(urlStatus!='') {
            $("#security_check_img").html(urlStatus);
            $("#check_s").show();
            $("#country").show();
            if(!$("#country").hasClass('loadcountry'))
                ipanalysis($(".security_check.d-none").data('ip'));
        }else{
            $("#check_s").remove();
            $("#country:not(.loadcountry)").remove();
        }
    });
    var tim=1;
    var timer = setInterval("tim++",100); 
    function check(url){
        var msg ="";
        if(tim>100) { 
            clearInterval(timer);
            $.getJSON('//api.iowen.cn/webinfo/get.php?url='+url,function(data){
                if(data.code==0){
                    msg = '<i class="iconfont icon-crying-circle mr-1" style="color:#f12345"></i><?php _e('链接超时，网站可能下线了，请点击直达试试','i_theme') ?> <i class="iconfont icon-crying"></i>';
                    updateStatus(false);
                }
                else{
                    msg = '<i class="iconfont icon-smiley-circle mr-1" style="color:#f1b223"></i><?php _e('墙外世界需要梯子','i_theme') ?> <i class="iconfont icon-smiley"></i>';
                    updateStatus(true);
                }
                $("#check_s").html(msg); 
            }).fail(function () {
                msg = '<i class="iconfont icon-crying-circle mr-1" style="color:#f12345"></i><?php _e('链接超时，网站可能下线了，请点击直达试试','i_theme') ?> <i class="iconfont icon-crying"></i>';
                $("#check_s").html(msg); 
                updateStatus(false);
            });
            clearInterval(timer);
        }else {
            msg = '<i class="iconfont icon-smiley-circle mr-1" style="color:#26f123"></i><?php _e('链接成功:','i_theme') ?>' + tim/10 + '<?php _e('秒','i_theme') ?>';
            $("#check_s").html(msg); 
            updateStatus(true);
            clearInterval(timer);
        }
    } 
    function ipanalysis(ip){
        $.getJSON('//api.iotheme.cn/ip/get.php?ip='+ip,function(data){
            if(data.status == 'success'){
                $("#country").html('<i class="iconfont icon-globe mr-1"></i>'+ data.country); 
                $.ajax({
                    url : "<?php echo admin_url( 'admin-ajax.php' ) ?>",  
                    data : {
                        action: "io_set_country",
                        country: data.country,
                        id: <?php echo get_the_ID() ?>
                    },
                    type : 'POST',
                    error:function(){ 
                        console.log('<?php _e('网络错误','i_theme') ?> --.'); 
                    }
                });
            }else{
                $("#country").html('<i class="iconfont icon-crying-circle mr-1"></i><?php _e("查询失败","i_theme") ?>'); 
            }
        }).fail(function () {
            $("#country").html('<i class="iconfont icon-crying-circle"></i>'); 
        });
    }
    function updateStatus(isInvalid){ 
        $.ajax({
            url : "<?php echo admin_url( 'admin-ajax.php' ) ?>",  
            data : {
                action: "link_failed",
                is_inv: isInvalid,
                post_id: <?php echo get_the_ID() ?>
            },
            type : 'POST',
            error:function(){ 
                console.log('<?php _e("网络错误","i_theme") ?> --.'); 
            }
        });
    }
</script>
<?php } ?>
<script>
<?php if( io_get_option('leader_board') && io_get_option('details_chart')){ ?>
loadFunc(function() {
    loadChart();
});
function loadChart() {
    ioChart = echarts.init(domChart,chartTheme);
    var post_data;
    ioChart.showLoading();
    jQuery.ajax({
        type : 'POST',
        url : theme.ajaxurl,  
        dataType: 'json',
        data : {
            action: "get_post_ranking_data",
            data: jQuery(domChart).data(),
        },
        success : function( response ){
            ioChart.hideLoading();
            if(response.status==1){
                post_data= response.data;
                var _series = post_data.series;
                var Max1 = calMax(post_data.count);
                var _yAxisData = [
                        {
                            type: 'value',
                            axisLabel: {
                                formatter: '{value}'
                            },
                            max: Max1,
                            splitNumber: 4,
                            interval: Max1 / 4
                        }
                    ];
                var _seriesData = [
                        {
                            name: _series[0],
                            type: 'bar',
                            data: post_data.desktop
                        },
                        {
                            name: _series[1],
                            type: 'bar',
                            data: post_data.mobile
                        },
                        {
                            name: _series[2],
                            type: 'line',
                            smooth: true,
                            data: post_data.count
                        }
                    ];
                if(response.type == "down"){
                    var Max2 = calMax(post_data.download);
                    _yAxisData.push(
                        {
                            type: 'value',
                            axisLabel: {
                                formatter: '{value}'
                            },
                            max: Max2,
                            splitNumber: 4,
                            interval: Max2 / 4
                        }
                    );
                    _seriesData.push(
                        {
                            name: _series[3],
                            type: 'line',
                            yAxisIndex: 1,itemStyle:{
                                normal:{
                                    lineStyle:{
                                        width:2,
                                        type:'dotted'
                                    }
                                }
                            },
                            data: post_data.download
                        }
                    );
                }
                chartOption = {
                    backgroundColor:'rgba(0,0,0,0)', 
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'none',
                            crossStyle: {
                                color: '#999'
                            }
                        }
                    }, 
                    legend: {
                        data: _series
                    },
                    xAxis: [
                        {
                            type: 'category',
                            data: post_data.x_axis,
                            axisPointer: {
                                type: 'shadow'
                            },  
                            axisLabel: {   
                                formatter: function(value) {
                                    return echarts.format.formatTime("MM.dd", new Date(value));
                                },
                            },
                        }
                    ],
                    yAxis: _yAxisData, 
                    series: _seriesData
                };
                if (chartOption && typeof chartOption === 'object') {
                    ioChart.setOption(chartOption);
                };
            }else{
                showAlert(JSON.parse('{"status":4,"msg":"<?php _e("网络错误","i_theme") ?>"}'));
            }
        },
        error:function(){ 
            showAlert(JSON.parse('{"status":4,"msg":"<?php _e("网络错误","i_theme") ?>"}'));
        }
    });
};
function calMax(arrs) {
    var max = arrs[0];
    for(var i = 1,ilen = arrs.length; i < ilen; i++) {
        if(arrs[i] > max) {
            max = arrs[i];
        }
    }
    if(max<4)
        return 4;
    else
        return Math.ceil(max/4)*4;
}
<?php } ?>
</script>
<?php get_footer();  ?>