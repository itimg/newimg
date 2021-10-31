<?php
global $corepress_post_meta;
if ($corepress_post_meta['postrighttag']['open'] == 1) {
    ?>
    <style>
        .post-content:before {
            content: "";
            position: absolute;
            right: 0;
            top: 0;
            color: #fff;
            width: 0;
            height: 0;
            border-top: 80px solid<?php echo $corepress_post_meta['postrighttag']['color']?>;
            border-left: 80px solid transparent;
        }

        .post-content:after {
            content: "<?php echo $corepress_post_meta['postrighttag']['text']?>";
            color: #fff;
            position: absolute;
            right: 10px;
            top: 14px;
            z-index: 9;
            transform: rotate(45deg);
        }
    </style>
    <?php
}
?>
    <style>
        .post-content {
            box-shadow: 0 0 2px 0 rgba(98, 124, 153, .1);
            margin-bottom: 10px;
            background: #fff;
            overflow: hidden;
        }
    </style>
    <div class="post-content">
        <h1 class="post-title">
            <?php the_title();
            global $set;
            ?>
        </h1>
        <div class="post-info">
            <div class="post-info-left">
                <?php
                $author = get_the_author_meta('ID');
                $author_url = get_author_posts_url($author);
                $author_name = get_the_author();
                ?>
                <a class="nickname url fn j-user-card" data-user="<?php echo $author; ?>"
                   href="<?php echo $author_url; ?>"><i class="fa fa-user"
                                                        aria-hidden="true"></i><?php echo $author_name; ?>
                </a>
                <span class="dot">•</span>
                <time class="entry-date published"
                      datetime="<?php echo get_post_time('c', false, $post); ?>>" pubdate><i class="far fa-clock"></i>
                    <?php echo format_date(get_post_time('U', false, $post)); ?>
                </time>
                <?php if (function_exists('the_views')) {
                    $views = intval(get_post_meta($post->ID, 'views', true));
                    ?>
                    <span class="dot">•</span>
                    <span><i class="fa fa-eye"
                             aria-hidden="true"></i><?php echo sprintf('%s 阅读', $views); ?></span>
                <?php }
                if (get_edit_post_link() != null) {
                    ?>
                    <span class="dot">•</span>
                    <a href="<?php echo get_edit_post_link(); ?>"><i class="fas fa-edit"></i> 编辑</a>
                    <?php
                }
                ?>
            </div>
            <div class="post-info-right">
            <span title="关闭或显示侧边栏" class="post-info-switch-sidebar post-info-switch-sidebar-show"><i
                        class="fas fa-toggle-on"></i></span>
            </div>
        </div>
        <div class="post-content-post">
            <div class="post-content-content">
                <?php
                the_content();
                if (is_page_template('page-links.php')) {
                    get_template_part('component/page/template-links');
                } elseif (is_page_template('page-friends.php')) {
                    get_template_part('component/page/template-friends');
                }
                ?>
            </div>
            <div class="post-end-tools">
            </div>
            <div class="post-tool-plane">
                <?php
                if ($corepress_post_meta['catalog'] == 1) {
                    ?>
                    <div id="post-catalog">
                        <div class="catalog-title">文章目录</div>
                        <div id="post-catalog-list">
                        </div>
                        <div class="catalog-close" onclick="close_show(0)">关闭</div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
<?php
if (comments_open() != 0) {
    comments_template();
}
?>