<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-10 21:22:37
 * @LastEditors: iowen
 * @LastEditTime: 2021-07-02 14:34:35
 * @FilePath: \onenav\inc\widgets\w.hot.post.php
 * @Description: 热门文章小工具
 */

CSF::createWidget( 'hot_post_img', array(
    'title'       => __('热门文章','io_setting'),
    'classname'   => 'io-widget-post-list',
    'description' => __( '按条件显示热门文章，可选“浏览数”“点赞收藏数”“评论量”','io_setting' ),
    'fields'      => array(

        array(
            'id'      => 'title',
            'type'    => 'text',
            'title'   => '名称',
            'default' => __('热门文章','io_setting'),
        ),

        array(
            'id'      => 'title_ico',
            'type'    => 'text',
            'title'   => '图标代码',
            'default' => 'iconfont icon-chart-pc',
        ),

        array(
            'id'          => 'newWindow',
            'type'        => 'switcher',
            'title'       => '在新窗口打开标题链接',
            'default'     => true,
        ), 

        array(
            'id'          => 'meta-key',
            'type'        => 'select',
            'title'       => '选择数据条件',
            'options'     => array(
                'views'     => '浏览数',
                'like'      => '点赞收藏',
                'comment'   => '评论量',
            ),
            'default' => 'views',
        ), 

        array(
            'id'          => 'number',
            'type'        => 'number',
            'title'       => '显示数量',
            'unit'        => '条',
            'default'     => 5,
        ),

        array(
            'id'          => 'days',
            'type'        => 'number',
            'title'       => '时间周期',
            'unit'        => '天',
            'default'     => 120,
            'help'        => '只显示此选项设置时间内发布的内容',
        ),

        array(
            'id'          => 'show_thumbs',
            'type'        => 'switcher',
            'title'       => '显示缩略图',
            'default'     => true,
        ),
    )
) );
if ( ! function_exists( 'hot_post_img' ) ) {
    function hot_post_img( $args, $instance ) {
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            $title_ico = !empty($instance['title_ico']) ? '<i class="mr-2 '.$instance['title_ico'].'"></i>' : ''; 
            echo $args['before_title'] . $title_ico. apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        global $post;
        $basis_args =  array(
            'post_type'             => array( 'post' ),
            'posts_per_page'        => $instance['number'],
            'ignore_sticky_posts'   => true,
            'date_query'            => array(
                array(
                    'after' => $instance['days'].' day ago',
                ),
            ), 
        );
        switch ($instance['meta-key']){
            case 'views':  
                $order_args = array(
                    'meta_key'  => 'views',      
                    'orderby'   => array( 'meta_value_num' => 'DESC', 'date' => 'DESC' ), 
                );
                break;
            case 'like':  
                $meta_key =io_get_option('user_center')?'_star_count':'_like_count';
                $order_args = array(
                    'meta_key'  => $meta_key,      
                    'orderby'   => array( 'meta_value_num' => 'DESC', 'date' => 'DESC' ), 
                );
                break;
            case 'comment': 
                $order_args = array(
                    'orderby' => 'comment_count',
                    'order' => 'dsc',
                );
                break;
            default:  
                $order_args = array(
                    'orderby' => 'comment_count',
                    'order' => 'dsc',
                );
        }
        $p_args         = array_merge($basis_args,$order_args);
        $myposts        = new WP_Query( $p_args );
        $new_window     = !empty($instance['newWindow']) ? true : false;
        $show_thumbs    = !empty($instance['show_thumbs']) ? true : false;
        $newWindow      = '';
        if ($new_window) $newWindow = "target='_blank'";
        echo'<div class="card-body"><div class="list-grid list-rounded my-n2">';
        if(!$myposts->have_posts()): ?>
            <div class="col-lg-12">
                <div class="nothing mb-4"><?php _e('没有数据！','i_theme') ?></div>
            </div>
        <?php
        elseif ($myposts->have_posts()): while ($myposts->have_posts()): $myposts->the_post();  
                $temp = ''; 
                $post_title = get_the_title();
                switch ($instance['meta-key']){
                    case 'views':
                        $s_data = !function_exists( 'the_views' )?'': the_views( false, '<span class="views"><i class="iconfont icon-chakan"></i> ','</span>' );
                        break;
                    case 'like': 
                        $s_data ='<span class="discuss"><i class="iconfont icon-like"></i>'.get_like(get_the_ID(),'post').'</span>';
                        break;
                    case 'comment': 
                        $s_data = '<span class="discuss"><i class="iconfont icon-comment"></i>'.get_comments_number().'</span>';
                        break;
                    default:  
                        $s_data = '<span class="discuss"><i class="iconfont icon-comment"></i>'.get_comments_number().'</span>';
                }
                $temp .= "<div class='list-item py-2'>";
                if($show_thumbs){
                    $temp .= "<div class='media media-3x2 rounded col-4 mr-3'>";
                    $thumbnail =  io_theme_get_thumb();
                    if(io_get_option('lazyload'))
                        $temp .= '<a class="media-content" href="'.get_permalink().'" '. $newWindow .' title="'.get_the_title().'" data-src="'.$thumbnail.'"></a>';
                    else
                        $temp .= '<a class="media-content" href="'.get_permalink().'" '. $newWindow .' title="'.get_the_title().'" style="background-image: url('.$thumbnail.');"></a>';
                    $temp .= "</div>"; 
                }
                $temp .= '
                    <div class="list-content py-0">
                        <div class="list-body">
                            <a href="'.get_permalink().'" class="list-title overflowClip_2" '. $newWindow .' rel="bookmark">'.get_the_title().'</a>
                        </div>
                        <div class="list-footer">
                            <div class="d-flex flex-fill text-muted text-xs">
                                <time class="d-inline-block">'.timeago(get_the_time('Y-m-d G:i:s')).'</time>
                                <div class="flex-fill"></div>' 
                                .$s_data.
                            '</div>
                        </div> 
                    </div> 
                </div>'; 
                echo $temp;
        endwhile; endif; wp_reset_postdata();  
        echo '</div></div>';

        echo $args['after_widget'];
    }
}
