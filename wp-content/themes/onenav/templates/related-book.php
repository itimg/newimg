<?php
/*!
 * Theme Name:One Nav
 * Theme URI:https://www.iotheme.cn/
 * Author:iowen
 * Author URI:https://www.iowen.cn/
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }?>
<?php
global $post;
$post_num       = 6;
$post_type      = 'book';
$taxonomy_tag   = 'booktag';
$taxonomy_cat   = 'books';
$exclude        = array ($post->ID);
$i = 0;
if ($i < $post_num) {
    $posttags = get_the_terms($post->ID, $taxonomy_tag);  
    if($posttags){
        $tags = []; foreach ( $posttags as $tag ) $tags[]= $tag->term_id ;
        $args = array(
        'post_type'         => $post_type ,// 文章类型
        'post_status'       => 'publish',
        'posts_per_page'    => $post_num, // 文章数量
        'orderby'           => 'rand', // 随机排序
        'tax_query'         => array(
            array(
                'taxonomy'  => $taxonomy_tag, // 分类法
                'field'     => 'id',
                'terms'     => $tags
            )
        ),
        'post__not_in'      => $exclude, // 排除当前文章
        );
        $related_items = new WP_Query( $args ); 
        if ($related_items->have_posts()) :
            while ( $related_items->have_posts() ) : $related_items->the_post();
            $exclude[] = get_the_ID();
            ?>
                <div class="col-6 col-sm-4 col-md-3 col-xl-6a">
                <?php include( get_theme_file_path('/templates/card-book.php') ); ?>
                </div>
            <?php
            $i++; 
        endwhile; endif; wp_reset_postdata();
    }
    if($i < $post_num){
        $custom_taxterms = get_the_terms( $post->ID,$taxonomy_cat);
        if(is_array($custom_taxterms)){
        $terms = []; foreach ( $custom_taxterms as $term ) $terms[]= $term->term_id ;
        $args = array(
        'post_type'         => $post_type ,// 文章类型
        'post_status'       => 'publish',
        'posts_per_page'    => $post_num-$i, // 文章数量
        'orderby'           => 'rand', // 随机排序
        'tax_query'         => array(
            array(
                'taxonomy'  => $taxonomy_cat, // 分类法
                'field'     => 'id',
                'terms'     => $terms
            )
        ),
        'post__not_in'      => $exclude, // 排除当前文章
        );
        $related_items = new WP_Query( $args ); 
        if ($related_items->have_posts()) :
            while ( $related_items->have_posts() ) : $related_items->the_post();
            ?>
                <div class="col-6 col-sm-4 col-md-3 col-xl-6a">
                <?php include( get_theme_file_path('/templates/card-book.php') ); ?>
                </div>
            <?php
            $i++; 
        endwhile; endif; wp_reset_postdata();
        }
    }
}
if ($i == 0) echo '<div class="col-lg-12"><div class="nothing">'.__('没有相关内容!','i_theme').'</div></div>';
?>
