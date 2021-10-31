<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-10 21:22:37
 * @LastEditors: iowen
 * @LastEditTime: 2021-08-05 19:37:31
 * @FilePath: \onenav\inc\widgets\w.hot.sites.php
 * @Description: 热门网址小工具
 */

CSF::createWidget( 'hot_sites', array(
    'title'       => '热门网址',
    'classname'   => 'io-widget-post-list',
    'description' => __( '按条件显示热门网址，可选“浏览数”“点赞收藏数”“评论量”','io_setting' ),
    'fields'      => array(

        array(
            'id'      => 'title',
            'type'    => 'text',
            'title'   => '名称',
            'default' => '热门网址',
        ),

        array(
            'id'      => 'title_ico',
            'type'    => 'text',
            'title'   => '图标代码',
            'default' => 'iconfont icon-chart-pc',
        ),

        array(
            'id'          => 'window',
            'type'        => 'switcher',
            'title'       => '在新窗口打开标题链接',
            'default'     => true,
        ), 

        array(
            'id'          => 'similar',
            'type'        => 'switcher',
            'title'       => '匹配同类',
            'default'     => true,
            'help'        => '匹配同标签和分类',
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
            'id'          => 'go',
            'type'        => 'switcher',
            'title'       => '直达',
            'default'     => false,
            'help'        => '如果主题设置中关闭了“详情页”，则默认直达',
        ),

        array(
            'id'          => 'nofollow',
            'type'        => 'switcher',
            'title'       => '不使用 go 跳转和 nofollow',
            'default'     => false,
            'dependency'  => array( 'go', '==', true )
        ) 
    )
) );
if ( ! function_exists( 'hot_sites' ) ) {
    function hot_sites( $args, $instance ) {
        echo $args['before_widget'];
        if ( !empty( $instance['title'] ) ) {
            $title_ico = !empty($instance['title_ico']) ? '<i class="mr-2 '.$instance['title_ico'].'"></i>' : ''; 
            echo $args['before_title'] . $title_ico. apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        global $post;
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
        $i          = 0;
        $html       = '';
        $post_num   = $instance['number'];
        if($instance['similar']){
            $post_id        = $post->ID;
            $post_type      = 'sites';
            $taxonomy_tag   = 'sitetag';
            $taxonomy_cat   = 'favorites';
            $exclude        = array ($post_id);
            $posttags       = get_the_terms($post_id, $taxonomy_tag);  
            if($posttags){
                $tags = []; foreach ( $posttags as $tag ) $tags[]= $tag->term_id ;
                $basis_args = array(
                    'post_type'         => $post_type, 
                    'post_status'       => 'publish',
                    'posts_per_page'    => $post_num, 
                    'tax_query'         => array(
                        array(
                            'taxonomy'  => $taxonomy_tag, 
                            'field'     => 'id',
                            'terms'     => $tags
                        )
                    ),
                    'post__not_in'      => $exclude, 
                );
                $p_args  = array_merge($basis_args,$order_args);
                $myposts = new WP_Query( $p_args );
                if($myposts->have_posts()){
                    $data = load_widgets_min_sites_html($myposts,$instance,$i);
                    $html .= $data['html'];
                    $i    = $data['index'];
                }
                wp_reset_postdata();
            }
            if($i < $post_num){
                $custom_taxterms = get_the_terms( $post_id,$taxonomy_cat);
                if(is_array($custom_taxterms)){
                    $terms = []; 
                    foreach ( $custom_taxterms as $term ) $terms[]= $term->term_id ;
                    $basis_args = array(
                        'post_type'         => $post_type, 
                        'post_status'       => 'publish',
                        'posts_per_page'    => $post_num-$i, 
                        'tax_query'         => array(
                            array(
                                'taxonomy'  => $taxonomy_cat, 
                                'field'     => 'id',
                                'terms'     => $terms
                            )
                        ),
                        'post__not_in'      => $exclude, 
                    );
                    $p_args  = array_merge($basis_args,$order_args);
                    $myposts = new WP_Query( $p_args ); 
                    if($myposts->have_posts()){
                        $data = load_widgets_min_sites_html($myposts,$instance,$i);
                        $html .= $data['html'];
                        $i    = $data['index'];
                    }
                    wp_reset_postdata();
                }
            }
        }
        if($i < $post_num){
            $basis_args = array(
                'post_type'           => 'sites', 
                'post_status'         => array( 'publish', 'private' ),//'publish',
                'perm'                => 'readable',
                'ignore_sticky_posts' => 1,              
                'posts_per_page'      => $instance['number'] - $i,
                'date_query' => array(
                    array(
                        'after' => $instance['days'].' day ago',
                    ),
                ),             
            );
            $p_args  = array_merge($basis_args,$order_args);
            $myposts = new WP_Query( $p_args ); 
            $data = load_widgets_min_sites_html($myposts,$instance,$i);
            $html .= $data['html'];
            wp_reset_postdata();
        }

        echo'<div class="card-body"><div class="row row-sm my-n1">';
        echo $html; 
        echo '</div></div>';

        echo $args['after_widget'];
    }
}
