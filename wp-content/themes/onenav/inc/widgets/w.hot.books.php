<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-10 21:22:37
 * @LastEditors: iowen
 * @LastEditTime: 2021-06-21 19:18:07
 * @FilePath: \onenav\inc\widgets\w.hot.books.php
 * @Description: 热门书籍小工具
 */

CSF::createWidget( 'io_hot_books', array(
    'title'       => '热门书籍',
    'classname'   => 'io-widget-post-list',
    'description' => __( '按条件显示热门书籍影视，可选“浏览数”“点赞收藏数”“评论量”','io_setting' ),
    'fields'      => array(

        array(
            'id'      => 'title',
            'type'    => 'text',
            'title'   => '名称',
            'default' => '热门书籍',
        ),

        array(
            'id'      => 'title_ico',
            'type'    => 'text',
            'title'   => '图标代码',
            'default' => 'iconfont icon-book',
        ),

        array(
            'id'          => 'new-window',
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
            'id'          => 'opt-num',
            'type'        => 'number',
            'title'       => '显示数量',
            'unit'        => '条',
            'default'     => 6,
        ),

        array(
            'id'          => 'opt-day',
            'type'        => 'number',
            'title'       => '时间周期',
            'unit'        => '天',
            'default'     => 120,
            'help'        => '只显示此选项设置时间内发布的内容',
        )
    )
) );
if ( ! function_exists( 'io_hot_books' ) ) {
    function io_hot_books( $args, $instance ) {
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            $title_ico = !empty($instance['title_ico']) ? '<i class="mr-2 '.$instance['title_ico'].'"></i>' : ''; 
            echo $args['before_title'] . $title_ico. apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        global $post;
        $basis_args = array(
            'post_type'           => 'book', 
            'post_status'         => array( 'publish', 'private' ),//'publish',
            'perm'                => 'readable',
            'ignore_sticky_posts' => 1,              
            'posts_per_page'      => $instance['opt-num'],   
            'date_query' => array(
                array(
                    'after' => $instance['opt-day'].' day ago',
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
                    'orderby'   => 'comment_count',
                    'order'     => 'dsc',
                );
                break;
            default:  
                $order_args = array(
                    'meta_key'  => 'views',      
                    'orderby'   =>  array( 'meta_value_num' => 'DESC', 'date' => 'DESC' ),  
                );
        }
        $p_args         = array_merge($basis_args,$order_args);
        $myposts = new WP_Query( $p_args );
        echo'<div class="card-body"><div class="row row-sm">';
        if(!$myposts->have_posts()): ?>
            <div class="col-lg-12">
                <div class="nothing mb-4"><?php _e('没有数据！','i_theme') ?></div>
            </div>
        <?php
        elseif ($myposts->have_posts()): while ($myposts->have_posts()): $myposts->the_post();   
        ?> 
            <div class="col-4">
            <?php
                $new_window = !empty($instance['new-window']) ? true : false;
                include( get_theme_file_path('/templates/card-book.php') ); 
            ?>
            </div>
        <?php  endwhile; endif; wp_reset_postdata();  
        echo '</div></div>';

        echo $args['after_widget'];
    }
}