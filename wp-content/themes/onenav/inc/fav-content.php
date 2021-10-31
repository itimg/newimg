<?php
/*!
 * Theme Name:One Nav
 * Theme URI:https://www.iotheme.cn/
 * Author:iowen
 * Author URI:https://www.iowen.cn/
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
/**
 * 加载单个分类内容
 * 
 * @param object $mid   分类对象
 * @param string $pname 父级分类名称
 * @return *
 */
function fav_con($mid,$pname = "") { 
    $taxonomy = $mid->taxonomy;
    $quantity = io_get_option('card_n');
    $icon = get_tag_ico($taxonomy,$mid);
    ?>
        <div class="d-flex flex-fill ">
            <h4 class="text-gray text-lg mb-4">
                <i class="site-tag <?php echo $icon ?> icon-lg mr-1" id="term-<?php echo $mid->term_id; ?>"></i>
                <?php if( $pname != "" && io_get_option("tab_p_n")&& !wp_is_mobile() ){ 
                    echo $pname . '<span style="color:#f1404b"> · </span>';
                } 
                echo $mid->name; ?>
            </h4>
            <div class="flex-fill"></div>
            <?php 
            $site_n = isset($quantity[get_type_name($taxonomy)])?$quantity[get_type_name($taxonomy)]:$quantity['apps'];
            $category_count   = $mid->category_count;
            $count            = $site_n;
            if($site_n == 0)  $count = min(get_option('posts_per_page'),$category_count);
            if($site_n >= 0 && $count < $category_count){
                $link =  is_page_template('template-mininav.php')?esc_url( get_term_link( $mid, $taxonomy ).'?menu-id='.get_post_meta( get_queried_object_id(), 'nav-id', true ).'&mininav-id='.get_queried_object_id() ):esc_url( get_term_link( $mid, $taxonomy ) );
                echo "<a class='btn-move text-xs' href='$link'>more+</a>";
            } 
            ?>
        </div>
        <div class="row io-mx-n2">
        <?php show_card($site_n,$mid->term_id,$taxonomy); ?>
        </div>   
<?php }  
/**
 * 加载单个菜单内容
 * 
 * @param array $mid   菜单数组
 * @param string $pname 父级分类名称
 * @return *
 */
function fav_con_a($mid,$pname = "") { 
    $taxonomy = $mid['object'];
    $quantity = io_get_option('card_n');
    $icon = get_tag_ico($taxonomy,$mid);
    
    /**
     * -----------------------------------------------------------------------
     * HOOK : ACTION HOOK
     * io_before_show_category_code
     * 
     * 在内容卡片前挂载其他内容。
     * 也可以在特定内容前挂载其他内容，通过判断$parent_term['object_id']
     * @since  3.0731
     * -----------------------------------------------------------------------
     */
    do_action( 'io_before_show_category_code' ,$mid );
    ?>
        <div class="d-flex flex-fill ">
            <h4 class="text-gray text-lg mb-4">
                <i class="site-tag <?php echo $icon ?> icon-lg mr-1" id="term-<?php echo $mid['object_id']; ?>"></i>
                <?php if( $pname != "" && io_get_option("tab_p_n")&& !wp_is_mobile() ){ 
                    echo $pname . '<span style="color:#f1404b"> · </span>';
                } 
                echo $mid['title']; ?>
            </h4>
            <div class="flex-fill"></div>
            <?php 
            $site_n = isset($quantity[get_type_name($taxonomy)])?$quantity[get_type_name($taxonomy)]:$quantity['apps'];
            $category_count   = io_get_category_count($mid['object_id'],$taxonomy);//10;//$mid->category_count;
            $count            = $site_n;
            if($site_n == 0)  $count = min(get_option('posts_per_page'),$category_count);
            if($site_n >= 0 && $count < $category_count){
                $link =  is_page_template('template-mininav.php')?$mid['url'].'?menu-id='.get_post_meta( get_queried_object_id(), 'nav-id', true ).'&mininav-id='.get_queried_object_id() :$mid['url'];//esc_url( get_term_link( $mid, $taxonomy ) );
                echo "<a class='btn-move text-xs' href='$link'>more+</a>";
            } 
            ?>
        </div>
        <div class="row io-mx-n2">
        <?php show_card($site_n,$mid['object_id'],$taxonomy); ?>
        </div>   
<?php } 
/**
 * ajax模式下加载菜单卡片
 * @param array $category 子菜单
 * @param array $parent_term  父级菜单
 * @return *
 */
function fav_con_tab_ajax($category,$parent_term) { 
    $_mid = '';  
    $quantity = io_get_option('card_n');
    $icon = get_tag_ico($parent_term['object'],$parent_term);
    
    /**
     * -----------------------------------------------------------------------
     * HOOK : ACTION HOOK
     * io_before_show_category_code
     * 
     * 在内容卡片前挂载其他内容。
     * 也可以在特定内容前挂载其他内容，通过判断$parent_term['object_id']
     * @since  3.0731
     * -----------------------------------------------------------------------
     */
    do_action( 'io_before_show_category_code' ,$parent_term );
    
    ?>
        <?php if(io_get_option("tab_p_n") ){ //父级名称 ?>
        <h4 class="text-gray text-lg">
            <i class="site-tag <?php echo $icon ?> icon-lg mr-1" id="term-<?php echo $parent_term['object_id'] ?>"></i>
            <?php echo $parent_term['title']; ?>
        </h4>
        <?php }else{echo '<div class="parent-category" id="term-'.$parent_term['object_id'].'"></div>';} ?>
        <!-- tab模式菜单 -->
        <div class="d-flex flex-fill flex-tab">
            <div class="d-flex slider-menu-father">
            <div class='overflow-x-auto slider_menu mini_tab ajax-list-home' sliderTab="sliderTab" data-id="<?php echo $parent_term['object_id'] ?>">
                <ul class="nav nav-pills menu" role="tablist"> 
                    <?php 
                    $i_menu = 0; 
                    foreach($category as $mid) { 
                        if($mid['type'] != 'taxonomy' ){
                            $url = trim($mid['url']);
                            if( strlen($url)>1 ) {
                                if(substr( $url, 0, 1 ) == '#' || substr( $url, 0, 4 ) == 'http' )
                                    continue;
                            }
                        }
                        if($i_menu == 0) $_mid = $mid;
                        $taxonomy = $mid['object'];
                        echo'<li class="pagenumber nav-item"><a id="term-'. $mid['object_id'] .'" class="nav-link '. ($i_menu==0?'active':'') .'" data-post_id="'.get_queried_object_id().'" data-action="load_home_tab" data-taxonomy="'. $taxonomy .'" data-id="'. $mid['object_id'] .'" >'. $mid['title'] .'</a></li>';
                        $i_menu++; 
                    } ?>
                </ul>
            </div>
            </div> <!-- slider-menu-father end -->
            <div class="flex-fill"></div>
            <?php 
            if(!$_mid){ //说明没有有效的子分类
                echo '</div>
                <!-- tab模式菜单,无内容 end -->';
                return;
            }
            $site_n = isset($quantity[get_type_name($_mid['object'])])?$quantity[get_type_name($_mid['object'])]:$quantity['apps'];
            $category_count   = io_get_category_count($_mid['object_id'],$_mid['object']);//10;//$_mid->category_count;
            $count            = $site_n;
            if($site_n == 0)  $count = min(get_option('posts_per_page'),$category_count);
            if($site_n >= 0 && $count < $category_count){
                $link =  is_page_template('template-mininav.php')?$_mid['url'].'?menu-id='.get_post_meta( get_queried_object_id(), 'nav-id', true ).'&mininav-id='.get_queried_object_id() : $_mid['url'];//esc_url( get_term_link( $_mid, $taxonomy ) );
                echo "<a class='btn-move tab-move text-xs ml-2' href='$link' style='line-height:34px'>more+</a>";
            }
            elseif($site_n >= 0) {
                echo "<a class='btn-move tab-move text-xs ml-2' href='#' style='line-height:34px;display:none'>more+</a>";
            }
            ?>
        </div>
        <!-- tab模式菜单 end -->
        <div class="row io-mx-n2 ajax-<?php echo $parent_term['object_id'] ?> mt-4" style="position: relative;">
        <?php show_card($site_n,$_mid['object_id'],$_mid['object']); ?>
        </div>
<?php } 

/**
 * 加载完整菜单tab卡片
 * @param array $category 子菜单
 * @param array $parent_term  父级菜单
 * @return *
 */
function fav_con_tab($category,$parent_term) { 
    $_link = '';  
    $quantity = io_get_option('card_n');
    $icon = get_tag_ico($parent_term['object'],$parent_term); 
    
    /**
     * -----------------------------------------------------------------------
     * HOOK : ACTION HOOK
     * io_before_show_category_code
     * 
     * 在内容卡片前挂载其他内容。
     * 也可以在特定内容前挂载其他内容，通过判断$parent_term['object_id']
     * @since  3.0731
     * -----------------------------------------------------------------------
     */
    do_action( 'io_before_show_category_code' ,$parent_term );
    ?>
        <?php if(io_get_option("tab_p_n") ){ ?>
        <h4 class="text-gray text-lg">
            <i class="site-tag <?php echo $icon ?> icon-lg mr-1" id="term-<?php echo $parent_term['object_id'] ?>"></i>
            <?php echo $parent_term['title']; ?>
        </h4>
        <?php }else{echo '<div class="parent-category" id="term-'.$parent_term['object_id'].'"></div>';} ?>
        <!-- tab模式菜单 -->
        <div class="d-flex flex-fill flex-tab">
            <div class="d-flex slider-menu-father">
            <div class='overflow-x-auto slider_menu mini_tab ajax-list-home' sliderTab="sliderTab" data-id="<?php echo  $parent_term['object_id'] ?>">
                <ul class="nav nav-pills menu" role="tablist"> 
                <?php 
                $i_menu = 0; 
                foreach($category as $mid) { 
                    if($mid['type'] != 'taxonomy' ){
                        $url = trim($mid['url']);
                        if( strlen($url)>1 ) {
                            if(substr( $url, 0, 1 ) == '#' || substr( $url, 0, 4 ) == 'http' )
                                continue;
                        }
                    }
                    $taxonomy = $mid['object']; 
                    $site_n = isset($quantity[get_type_name($mid['object'])])?$quantity[get_type_name($mid['object'])]:$quantity['apps'];
                    $category_count   = io_get_category_count($mid['object_id'],$mid['object']);
                    $count            = $site_n;
                    if($site_n == 0)  $count = min(get_option('posts_per_page'),$category_count);
                    $link = '';
                    if($site_n >= 0 && $count < $category_count){
                        $link = is_page_template('template-mininav.php')?$mid['url'].'?menu-id='.get_post_meta( get_queried_object_id(), 'nav-id', true ).'&mininav-id='.get_queried_object_id() :$mid['url'];
                    }
                    if($i_menu == 0) $_link = $link;
                    echo '<li class="pagenumber nav-item"><a id="term-'. $mid['object_id'] .'" class="nav-link tab-noajax '. ($i_menu==0?'active':'') .'" data-toggle="pill" href="#tab-'. $mid['object_id'].'" data-link="'.$link.'">'. $mid['title'].'</a></li>';
                    $i_menu++; 
                } ?>
                </ul>
            </div>
            </div> 
            <div class="flex-fill"></div>
            <?php  
            //显示更多按钮，通过js切换href
            if($_link != ''){
                echo "<a class='btn-move tab-move text-xs ml-2' href='$_link' style='line-height:34px'>more+</a>";
            } else {
                echo "<a class='btn-move tab-move text-xs ml-2' href='#' style='line-height:34px;display:none'>more+</a>";
            }
            ?>
        </div>
        <!-- tab模式菜单 end -->
        <div class="tab-content mt-4">
            <?php  
            $i_menu_box = 0; 
            foreach($category as $mid) { 
                if($mid['type'] != 'taxonomy' ){
                    $url = trim($mid['url']);
                    if( strlen($url)>1 ) {
                        if(substr( $url, 0, 1 ) == '#' || substr( $url, 0, 4 ) == 'http' )
                            continue;
                    }
                }
            ?>
            <div id="tab-<?php echo $mid['object_id']; ?>" class="tab-pane  <?php echo $i_menu_box==0?'active':'' ?>">  
                <div class="row io-mx-n2 mt-4" style="position: relative;">
                <?php show_card($site_n,$mid['object_id'],$mid['object']); ?>
                </div>
            </div>
            <?php 
                $i_menu_box++;
            } 
            ?>
        </div> 
<?php } 


if(!function_exists('show_card')):
/**
 * 显示分类内容
 * @param  String $site_n 需显示的数量
 * @param  String $cat_id 分类id
 * @param  String $taxonomy 分类名
 * @param  String $ajax  
 */
function show_card($site_n,$cat_id,$taxonomy,$ajax=''){ 
    if ( !in_array( $taxonomy, get_menu_category_list() ) ){
        echo "<div class='card py-3 px-4'><p style='color:#f00'><i class='iconfont icon-crying mr-3'></i>该菜单内容不是分类，请到菜单删除并重新添加正确的内容。</p></div>";
        return;
    }
    $_order = io_get_option('home_sort')[get_type_name($taxonomy)];
    if($_order == 'views'){
        $args = array(      
            'meta_key' => 'views',
            'orderby'  => array( 'meta_value_num' => 'DESC', 'date' => 'DESC' ),
        );
    } elseif($_order == '_sites_order') { 
        if ( io_get_option('sites_sortable')){
            $args = array(      
                'orderby' => array( 'menu_order' => 'ASC', 'ID' => 'DESC' ),
            );
        }else{
            $args = array(      
                'meta_key' => '_sites_order',
                'orderby'  => array( 'meta_value_num' => 'DESC', 'date' => 'DESC' ),
            );
        }
    } elseif($_order == '_down_count') { 
        $args = array(      
            'meta_key' => '_down_count',
            'orderby'  => array( 'meta_value_num' => 'DESC', 'date' => 'DESC' ),
        );
    } else {
        $args = array(      
            'orderby' => $_order,
            'order'   => 'DESC',
        );
    } 
    $args2 = array(   
        'post_type'           => to_post_type($taxonomy),
        //'ignore_sticky_posts' => 1,              
        'posts_per_page'      => $site_n,    
        'post_status'         => array( 'publish', 'private' ),//'publish',
        'perm'                => 'readable',
        'tax_query'           => array(
            array(
                'taxonomy' => $taxonomy,       
                'field'    => 'id',            
                'terms'    => $cat_id,    
            )
        ),
    );
    $cache_key = 'io_home_posts_'.$cat_id.'_'.$taxonomy.'_'. $site_n.'_'.$ajax;//.':'.wp_cache_get_last_changed('home-card');
    $myposts = wp_cache_get( $cache_key ,'home-card');
    if ( false === $myposts ) { 
        $myposts = new WP_Query( array_merge($args,$args2) );
        if( io_get_option('show_sticky'))
            $myposts= sticky_posts_to_top($myposts,to_post_type($taxonomy),$taxonomy,$cat_id);
        //show_post($myposts,$taxonomy,$ajax);
        if( $_order == '_down_count' || $_order == 'views' )
            wp_cache_set( $cache_key, $myposts ,'home-card', 1 * HOUR_IN_SECONDS); 
        else
            wp_cache_set( $cache_key, $myposts ,'home-card', 24 * HOUR_IN_SECONDS); 
        wp_reset_postdata();
    }
    show_post($myposts,$taxonomy,$ajax,$cat_id);
    
}
endif;
if(!function_exists('show_post')){
function show_post($myposts,$taxonomy,$ajax,$cat_id=0){
    global $post;  
    if(!$myposts->have_posts()): ?>
        <div class="col-lg-12">
            <div class="nothing mb-4"><?php _e('没有内容','i_theme') ?></div>
        </div>
    <?php
    elseif ($myposts->have_posts()): while ($myposts->have_posts()): $myposts->the_post(); 
    
    if($taxonomy == "favorites"||$taxonomy == "sitetag"){
        if($card_mode = get_term_meta( $cat_id, 'card_mode', true )){
            switch($card_mode){
                case 'null':
                    goto S_SETTING;
                    break;
                case 'max':
                    goto S_MAX;
                    break;
                case 'min':
                    goto S_MIM;
                    break;
                default:
                    goto S_DEF;
            }
        }
        S_SETTING: 
        if(io_get_option('site_card_mode') == 'max'){ 
            S_MAX:
            echo '<div class="url-card io-px-2 '.get_columns(false).' '. before_class($post->ID).' '.$ajax.'">';
            include( get_theme_file_path('/templates/card-sitemax.php') );
            echo '</div>';
        }elseif(io_get_option('site_card_mode') == 'min'){ 
            S_MIM:
            echo '<div class="url-card io-px-2 col-6 '.get_columns(false).' '. before_class($post->ID).' '.$ajax.'">';
            include( get_theme_file_path('/templates/card-sitemini.php') );
            echo '</div>';
        }else{ 
            S_DEF:
            echo '<div class="url-card io-px-2 '.(io_get_option('two_columns')?"col-6 ":"").get_columns(false).' '. before_class($post->ID).' '.$ajax.'">';
            include( get_theme_file_path('/templates/card-site.php') );
            echo '</div>';
        }
        
    } elseif($taxonomy == "apps"||$taxonomy == "apptag") {
        if($card_mode = get_term_meta( $cat_id, 'card_mode', true )){
            switch($card_mode){
                case 'null':
                    goto A_SETTING;
                    break;
                case 'card':
                    goto A_CARD;
                    break;
                default:
                    goto A_DEF;
            }
        }
        A_SETTING: 
        if(io_get_option('app_card_mode') == 'card'){
            A_CARD:
            echo'<div class="io-px-2 col-12 col-md-6 col-lg-4 col-xxl-5a '.$ajax.'">';
            include( get_theme_file_path('/templates/card-appcard.php') ); 
            echo'</div>';
        }else{
            A_DEF:
            echo'<div class="io-px-2 col-4 col-md-3 col-lg-2 col-xl-8a col-xxl-10a pb-1 '.$ajax.'">';
            include( get_theme_file_path('/templates/card-app.php') ); 
            echo'</div>';
        }
    } elseif($taxonomy == "books"||$taxonomy == "series"||$taxonomy == "booktag") { 
            echo'<div class="io-px-2 col-6 col-sm-4 col-md-3 col-lg-2 col-xxl-8a '.$ajax.'">';
            include( get_theme_file_path('/templates/card-book.php') ); 
            echo'</div>'; 
    } elseif($taxonomy == "category"||$taxonomy == "post_tag") {
        if($card_mode = get_term_meta( $cat_id, 'card_mode', true )){
            switch($card_mode){
                case 'null':
                    goto P_SETTING;
                    break;
                case 'card':
                    goto P_CARD;
                    break;
                default:
                    goto P_DEF;
            }
        }
        P_SETTING:
        if(io_get_option('post_card_mode')=="card"){
            P_CARD:
            echo '<div class="io-px-2 col-12 col-sm-6 col-lg-4 col-xxl-3 '.$ajax.'">';
            get_template_part( 'templates/card','postmin' );
            echo '</div>';
        }elseif(io_get_option('post_card_mode')=="default"){
            P_DEF:
            echo '<div class="io-px-2 col-6 col-md-4 col-xl-3 col-xxl-6a py-2 py-md-3 '.$ajax.'">';
            get_template_part( 'templates/card','post' );
            echo '</div>';
        } 
    }

    endwhile; endif;
}
}

function to_post_type($taxonomy){
    if( $taxonomy=="favorites"||$taxonomy=="sitetag" )
        return 'sites';
    if( $taxonomy=="apps"||$taxonomy=="apptag" )
        return 'app';
    if( $taxonomy=="books"||$taxonomy=="booktag" ||$taxonomy=="series")
        return 'book';
}
function to_post_tag($post){
    if( $post=="sites" )
        return 'sitetag';
    if( $post=="app" )
        return 'apptag';
    if( $post=="book" )
        return 'booktag';
    return 'post_tag';
}

function get_type_name($taxonomy){
    if( $taxonomy=="favorites"||$taxonomy=="sitetag" )
        return 'favorites';
    if( $taxonomy=="apps"||$taxonomy=="apptag" )
        return 'apps';
    if( $taxonomy=="books"||$taxonomy=="booktag" ||$taxonomy=="series" )
        return 'books';
    if( $taxonomy=="category"||$taxonomy=="post_tag" )
        return 'category';
}