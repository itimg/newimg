<?php if ( ! defined( 'ABSPATH' )  ) { die; }
 
if (!is_admin()) return;
$prefix = 'io_get_option';

CSF::createOptions( $prefix, array(
    'framework_title' => 'One Nav <small>V'.wp_get_theme()->get('Version').'</small>',
    'menu_title'      => __('主题设置','csf'),
    'menu_slug'       => 'theme_settings', 
    'menu_position'   => 59,
    'save_defaults'   => true,
    'ajax_save'       => true,
    'show_bar_menu'   => false,
    'theme'           => 'dark',
    'show_search'     => true,
    'footer_text'     => esc_html__('运行在', 'io_setting' ).'： WordPress '. get_bloginfo('version') .' / PHP '. PHP_VERSION,
    'footer_credit'   => '感谢您使用 <a href="https://www.iotheme.cn/" target="_blank">一为</a>的WordPress主题',
));

$views_use_ajax = ( defined( 'WP_CACHE' ) && WP_CACHE )?'':'csf-depend-visible csf-depend-on';
$set_search = io_get_option('custom_search')?'<br><br><a href="'.esc_url(add_query_arg('page', 'search_settings', admin_url('options-general.php'))).'" class="button button-primary">前往配置</a>':'';
//
// 开始使用
//
CSF::createSection( $prefix, array(
    'title'        => __('开始使用','io_setting'),
    'icon'         => 'fa fa-shopping-cart',
    'fields'       => array(
        array(
            'type'    => 'notice',
            'style'   => 'warning',
            'content' => '<li style="font-size:18px;color: red">'.__('先保存一遍主题设置选项，否则可能会报错（点右上“保存”按钮）','i_theme').'</li>',
        ),
        array(
            'id'      => 'theme_key',
            'type'    => 'text',
            'title'   => __('主题激活码','io_setting'),
            'after'   => __('请先使用订单激活码<a href="//www.iotheme.cn/user?try=reg" target="_blank" title="注册域名">注册域名</a>。 如果没有购买，请访问<a href="//www.iotheme.cn/store/onenav.html" target="_blank" title="购买主题">iTheme</a>购买。','io_setting'),
        ),
        array(
            'id'      => 'update_theme',
            'type'    => 'switcher',
            'title'   => __('检测主题更新','io_setting'),
            'label'   => __('在线更新为替换更新，如果你修改了主题代码，请关闭（如需修改，可以使用子主题）','io_setting'),
            'default' => true,
        ),
        array(
            'id'      => 'update_beta',
            'type'    => 'switcher',
            'title'   => ' ┗━━ '.__('检测Beta版更新','io_setting'),
            'label'   => __('Beta版及测试版，可体验最新功能，同时也会各种bug。','io_setting'),
            'class'   => 'compact',
            'default' => false,
            'dependency' => array( 'update_theme', '==', true )
        ),
        array(
            'type'    => 'notice',
            'style'   => 'success',
            'content' => '<p>---> <a href="https://www.iotheme.cn/one-nav-zhutishouce.html" target="_blank">使用手册</a> <---</p>',
        ),
        array(
            'type'    => 'notice',
            'style'   => 'success',
            'content' => '<p>---> 下载 <a href="https://www.iotheme.cn/one-nav-yidaohangyanshishujushiyongjiaocheng.html" target="_blank">演示数据</a> <---</p>',
        ),
        array(
            'type'    => 'notice',
            'style'   => 'success',
            'content' => '
            <p>首次安装检查如下设置：</p>
            <ul style="list-style:decimal;padding-left:15px">
                <li>404问题请检查服务器伪静态规则和wp固定链接格式，推荐“/%post_id%.html”。<a href="https://www.iowen.cn/wordpress-version-webstack/#in3" target="_blank">伪静态设置方法</a></li>
                <li>首次启用主题必须保存一遍主题选项才能打开首页，否则可能会报错。</li>
                <li style="color: red">启用主题前请禁用所有插件，以免插件冲突。</li>
            </ul>
            <p>主题使用注意事项：</p>
            <ul style="list-style:decimal;padding-left:15px">
                <li>请先查看：<a href="https://www.iowen.cn/your-first-website" target="_blank">主题使用教程</a></li>
                <li>菜单图标设置请查看主题使用说明和群公共。</li>
                <li>先创建网址分类，然后这添加网址。</li>
                <li>分类最多两级，且第一级不要添加内容。</li>
                <li style="color: red">更新主题后请重新保存主题设置。</li>
                <li>投搞、博客等页面请新建页面然后选择对应的页面模板。</li>
                <li>阿里图标 Iconfont：<a href="https://www.iowen.cn/webstack-pro-navigation-theme-iconfont/" target="_blank">使用方法</a></li>
                <li>侧栏菜单设置方法：<a href="https://www.iowen.cn/webstack-pro-theme-main-menu-setting-description/" target="_blank" style="color: red">必看</a></li>
            </ul>',
        ),
        array(
            'type'    => 'notice',
            'style'   => 'success',
            'content' => '<p>推荐插件：</p>
            <ul style="list-style:decimal;padding-left:15px">
                <li>自动将文章、分类、标签的地址转化为拼音，<a href="https://wordpress.org/plugins/so-pinyin-slugs/" target="_blank">获取插件</a></li>
                <li>对象缓存插件 Memcached， <a href="https://www.baidu.com/s?wd=wordpress%20Memcached" target="_blank">使用方法</a></li>
                <li>XML Sitemap插件，<a href="https://wordpress.org/plugins/xml-sitemap-feed/" target="_blank">获取插件</a></li>
                <li style="color: red">如果不会操作，可以都不用哦 --. 不影响使用</li>
            </ul>',
        ),
    )
));
//
// 图标设置
//
CSF::createSection( $prefix, array(
    'title'        => __('图标设置','io_setting'),
    'icon'         => 'fa fa-star',
    'description'  => __('网站LOGO和Favicon设置','io_setting'),
    'fields'       => array(
    array(
        'id'        => 'logo_normal',
        'type'      => 'upload',
        'title'     => '暗色主题Logo',
        'add_title' => __('上传','io_setting'),
        'after'     => '<p class="cs-text-muted">'.__('建议高80px，长小于360px','io_setting'),
        'default'   => get_theme_file_uri( '/images/logo@2x.png'),
    ),
    array(
        'id'        => 'logo_normal_light',
        'type'      => 'upload',
        'title'     => __('亮色主题Logo','io_setting'),
        'add_title' => __('上传','io_setting'),
        'after'     => '<p class="cs-text-muted">'.__('建议高80px，长小于360px','io_setting'),
        'default'   => get_theme_file_uri('/images/logo_l@2x.png'),
    ),
    array(
        'id'        => 'logo_small',
        'type'      => 'upload',
        'title'     => __('暗色主题方形 Logo','io_setting'),
        'add_title' => __('上传','io_setting'),
        'after'     => '<p class="cs-text-muted">'.__('建议 80x80','io_setting'),
        'default'   => get_theme_file_uri('/images/logo-collapsed@2x.png'),
    ),
    array(
        'id'        => 'logo_small_light',
        'type'      => 'upload',
        'title'     => __('亮色主题方形 Logo','io_setting'),
        'add_title' => __('上传','io_setting'),
        'after'     => '<p class="cs-text-muted">'.__('建议 80x80','io_setting'),
        'default'   => get_theme_file_uri('/images/logo-dark_collapsed@2x.png'),
    ),
    array(
        'id'        => 'favicon',
        'type'      => 'upload',
        'title'     => __('上传 Favicon','io_setting'),
        'add_title' => __('上传','io_setting'),
        'default'   => get_theme_file_uri('/images/favicon.png'),
    ),
    array(
        'id'        => 'apple_icon',
        'type'      => 'upload',
        'title'     => __('上传 apple_icon','io_setting'),
        'add_title' => __('上传','io_setting'),
        'default'   => get_theme_file_uri('/images/app-ico.png'),
    ),
    )
));

//
// 主题颜色
//
CSF::createSection( $prefix, array(
    'title'        => __('颜色效果','io_setting'),
    'icon'         => 'fa fa-tachometer',
    'fields'       => array( 
        array(
            'id'      => 'theme_mode',
            'type'    => 'radio',
            'title'   => __('颜色主题','io_setting'),
            'default' => 'io-grey-mode',
            'inline'  => true,
            'options' => array(
                'io-black-mode'  => __('暗色','io_setting'),
                'io-white-mode'  => __('黑白','io_setting'),
                'io-grey-mode'   => __('亮灰','io_setting'),
            ),
            'after'   => __('如果在前台通过“主题切换开关”手动切换主题，此设置无效，或者清除浏览器cookie才能生效','io_setting')
        ),
        array(
            'id'      => 'home_width',
            'type'    => 'switcher',
            'title'   => __('自定义首页内容宽度','io_setting'),
            'default' => false,
            'class'   => '',
        ),
        array(
            'id'      => 'h_width',
            'type'    => 'slider',
            'title'   => ' ┗━━ '.'宽度',
            'class'   => '',
            'min'     => 1320,
            'max'     => 2000,
            'step'    => 10,
            'unit'    => 'px',
            'default' => 1900,
            'class'   => 'compact',
            'dependency' => array( 'home_width', '==', true )
        ),
        array(
            'id'      => 'loading_fx',
            'type'    => 'switcher',
            'title'   => __('全屏加载效果','io_setting'),
            'default' => false,
        ),
        array(
            'id'        => 'loading_type',
            'type'      => 'image_select',
            'title'     => __('加载效果','io_setting'),
            'options'   => array(
                'rand'  => get_theme_file_uri('/images/loading/load0.png'),
                '1'     => get_theme_file_uri('/images/loading/load1.png'),
                '2'     => get_theme_file_uri('/images/loading/load2.png'),
                '3'     => get_theme_file_uri('/images/loading/load3.png'),
                '4'     => get_theme_file_uri('/images/loading/load4.png'),
                '5'     => get_theme_file_uri('/images/loading/load5.png'),
                '6'     => get_theme_file_uri('/images/loading/load6.png'),
                '7'     => get_theme_file_uri('/images/loading/load7.png'),
            ),
            'default'   => '1',
            'class'     => '',
            'subtitle'  => __('包括go跳转页,go跳转页不受上面开关影响','io_setting'),
        ),
        array(
            'id'        => 'login_ico',
            'type'      => 'upload',
            'title'     => __('登录页图片','io_setting'),
            'add_title' => __('上传','io_setting'),
            'default'   => get_theme_file_uri('/images/login.jpg'),
        ),
        array(
            'id'        => 'login_color',
            'type'      => 'color_group',
            'title'     => '登录页背景色',
            'class'     => '',
            'options'   => array(
                'color-l'   => '左边',
                'color-r'   => '右边',
            ),
            'default'   => array(
                'color-l'   => '#7d00a0',
                'color-r'   => '#c11b8d',
            ),
        ),
        array(
            'id'      => 'custom_color',
            'type'    => 'switcher',
            'title'   => __('自定义颜色','io_setting'),
            'default' => false,
        ),
        array(
            'id'        => 'bnt_c',
            'type'      => 'color_group',
            'title'     => '按钮颜色',
            'options'   => array(
                'color'   => '默认颜色',
                'color-t' => '默认文字颜色',
                'hover'   => 'hover 颜色',
                'hover-t' => 'hover 文字颜色',
            ),
            'default'   => array(
                'color'   => '#f1404b',
                'color-t' => '#ffffff',
                'hover'   => '#14171B',
                'hover-t' => '#ffffff',
            ),
            'dependency' => array( 'custom_color', '==', true )
        ),
        array(
            'id'      => 'link_c',
            'type'    => 'link_color',
            'title'   => '文章 a 链接颜色',
            'default' => array(
                'color' => '#f1404b',
                'hover' => '#f9275f',
            ),
            'dependency' => array( 'custom_color', '==', true )
        ),
        array(
            'id'      => 'card_a_c',
            'type'    => 'color',
            'title'   => '卡片链接高亮',
            'default' => '#f1404b',
            'dependency' => array( 'custom_color', '==', true )
        ),
        array(
            'id'      => 'piece_c',
            'type'    => 'color',
            'title'   => '高亮色块',
            'default' => '#f1404b',
            'dependency' => array( 'custom_color', '==', true )
        ),
    )
));

//
// 基础设置
//
CSF::createSection( $prefix, array(
    'title'  => __('基础设置','io_setting'),
    'icon'   => 'fa fa-th-large',
    'fields' => array(
        array(
            'id'      => 'nav_comment',
            'type'    => 'switcher',
            'title'   => __('站点评论','io_setting'),
            'text_on' => '启用',
            'text_off'=> '禁用',
            'text_width' => 80,
            'default' => true,
        ),
        array(
            'id'      => 'min_nav',
            'type'    => 'switcher',
            'title'   => __('mini 侧边栏','io_setting'),
            'label'   => __('默认使用 Mini 侧边栏，开启前请设置好菜单项图标','io_setting'),
            'default' => false,
        ),
        array(
            'id'      => 'nav_top_mobile',
            'type'    => 'text',
            'title'   => __('移动设备顶部菜单名称','io_setting'),
            'after'   => __('大屏顶部菜单在移动设备上显示到侧边栏菜单，-->留空则不显示<--','io_setting'),
            'default' => '站点推荐',
            'class'   => '',
        ),
        array(
            'id'          => 'cdn_resources',
            'type'        => 'select',
            'title'       => '静态文件使用公共库',
            'chosen'      => true,
            'options'     => array(
                'local'         => '本地',
                'jsdelivr'      => 'jsdelivr.net',
                //'staticfile'    => 'staticfile.org',
            ),
            'settings' => array(
                'width'   => '120px',
            ),
            'default'     => 'local'
        ),
        array(
            'id'      => 'bing_cache',
            'type'    => 'switcher',
            'title'   => __('必应背景图片本地缓存','io_setting'),
            'label'   => __('文明获取，避免每次都访问 bing 服务器','io_setting'),
            'after'   => '<br>'.__('使用了oss等图床插件的请关闭此功能','io_setting'),
            'default' => false,
        ),
        array(
            'id'      => 'sites_sortable',
            'type'    => 'switcher',
            'title'   => __('网址拖拽排序','io_setting'),
            'label'   => __('在后台网址列表使用拖拽排序,请同时选择“首页网址分类排序”为“自定义排序字段”','io_setting'),
            'after'   => '<br>'.__('如果想继续使用老版的排序字段，请关闭此功能','io_setting'),
            'class'   => '',
            'default' => true,
        ),
        array(
            'id'      => 'user_center',
            'type'    => 'switcher',
            'title'   => __('启用用户中心','io_setting'),
            'label'   => __('同时启用个性化登录页','io_setting'),
            'class'   => '',
            'default' => false,
        ),
        array(
            'type'    => 'submessage',
            'style'   => 'danger',
            'content' => '━━━━━ <i class="fa fa-fw fa-info-circle fa-fw"></i> 启用和禁用<b>[用户中心]</b>后必须重新保存固定链接 ━━━━━',
        ),
        array(
            'id'      => 'nav_login',
            'type'    => 'switcher',
            'title'   => __('顶部登陆按钮','io_setting'),
            'default' => false,
        ),
        array(
            'id'        => 'sticky_tag',
            'type'      => 'fieldset',
            'title'     => __('置顶标签','io_setting'),
            'fields'    => array(
                array(
                    'id'    => 'switcher',
                    'type'  => 'switcher',
                    'title' => __('显示','io_setting'),
                ),
                array(
                    'id'    => 'name',
                    'type'  => 'text',
                    'title' => __('显示内容','io_setting'),
                ),
            ),
            'default'        => array(
                'switcher'    => false,
                'name'        => 'T',
            ),
        ),
        array(
            'id'        => 'new_tag',
            'type'      => 'fieldset',
            'title'     => __('NEW 标签','io_setting'),
            'fields'    => array(
                array(
                    'id'    => 'switcher',
                    'type'  => 'switcher',
                    'title' => __('显示','io_setting'),
                ),
                array(
                    'id'    => 'name',
                    'type'  => 'text',
                    'title' => __('显示内容','io_setting'),
                ),
                array(
                    'id'    => 'date',
                    'type'  => 'spinner',
                    'title' => __('时间','io_setting'),
                    'after' => __('几天内的内容标记为新内容','io_setting'),
                    'unit'  => '天',
                    'step'  => 1,
                ),
            ),
            'default'        => array(
                'switcher'    => false,
                'name'        => 'N',
                'date'        => 7,
            ),
        ),
        array(
            'id'      => 'is_nofollow',
            'type'    => 'switcher',
            'title'   => __('网址块添加nofollow','io_setting'),
            'after'   => __('详情页开启则不添加','io_setting'),
            'default' => true,
        ),
        array(
            'id'      => 'details_page',
            'type'    => 'switcher',
            'title'   => __('详情页','io_setting'),
            'subtitle'=> __('启用网址详情页','io_setting'),
            'label'   => __('关闭状态为网址块直接跳转到目标网址。','io_setting'),
            'after'   => __('<strong>“公众号”</strong>和<strong>“下载资源”</strong>默认开启详情页，不受此选项限制。','io_setting'),
            'default' => false,
        ),
        array(
            'id'      => 'url_rank',
            'type'    => 'switcher',
            'title'   => ' ┗━━ '.__('爱站权重','io_setting'),
            'label'   => __('详情页显示爱站权重','io_setting'),
            'default' => true, 
            'class'   => 'compact',
            'dependency' => array( 'details_page', '==', true )
        ),
        array(
            'id'      => 'sites_preview',
            'type'    => 'switcher',
            'title'   => ' ┗━━ '.__('网址预览','io_setting'),
            'label'   => __('显示目标网址预览，如api服务失效，请关闭。','io_setting'),
            'default' => false,
            'class'   => 'compact',
            'dependency' => array( 'details_page', '==', true )
        ),
        array(
            'id'      => 'togo',
            'type'    => 'switcher',
            'title'   => ' ┗━━ '.__('直达按钮','io_setting'),
            'label'   => __('网址块显示直达按钮','io_setting'),
            'default' => true,
            'class'   => 'compact',
            'dependency' => array( 'details_page', '==', true )
        ),
        array(
            'id'      => 'show_speed',
            'type'    => 'switcher',
            'title'   => ' ┗━━ '.__('显示网址链接速度(死链)','io_setting'),
            'label'   => __('在网址详情页显示目标网址的链接速度、国家或地区等信息','io_setting'),
            'after'   => __('为网址失效状态提供数据来源<br>前台 JS 检测，不影响服务器性能','io_setting'),
            'default' => false,
            'class'   => 'compact',
            'dependency' => array( 'details_page', '==', true )
        ),
        array(
            'id'      => 'failure_valve',
            'type'    => 'spinner',
            'title'   => ' ┗━━ '.__('网址失效状态(死链)','io_setting'),
            'after'   => __('详情页检测链接失败几次后提示管理员检测网址有效性<br>0为关闭提示','io_setting'),
            'default' => 0,
            'class'   => 'compact',
            'dependency' => array( 'details_page|show_speed', '==|==', 'true|true' )
        ),
        array(
            'id'      => 'new_window',
            'type'    => 'switcher',
            'title'   => __('新标签中打开内链','io_setting'),
            'label'   => __('站点所有内部链接在新标签中打开','io_setting'),
            'default' => true,
        ),
        array(
            'id'      => 'is_go',
            'type'    => 'switcher',
            'title'   => __('内链跳转(go跳转)','io_setting'),
            'label'   => __('站点所有外链跳转，效果：http://您的域名/go/?url=外链','io_setting'),
            'default' => false,
        ),
        array(
            'id'      => 'exclude_links', 
            'type'    => 'textarea',
            'title'   => __('go跳转白名单','io_setting'),
            'subtitle'=> __('go跳转和正文nofollow白名单','io_setting'),
            'after'   => __('一行一个地址，注意不要有空格。<br>需要包含http(s)://<br>iowen.cn和www.iowen.cn为不同的网址<br>此设置同时用于 nofollow 的排除。','io_setting'),
        ),
        array(
            'id'      => 'lazyload',
            'type'    => 'switcher',
            'title'   => __('图标懒加载','io_setting'),
            'label'   => __('所有图片懒加载','io_setting'),
            'default' => true,
        ),
        array(
            'id'      => 'show_friendlink',
            'type'    => 'switcher',
            'title'   => __('启用友链','io_setting'),
            'label'   => __('启用自定义文章类型“链接(友情链接)”，启用后需刷新页面','io_setting'),
            'default' => true,
        ),
        array(
            'id'         => 'links',
            'type'       => 'switcher',
            'title'      => ' ┗━━ '.__('友情链接','io_setting'),
            'label'      => __('在首页底部添加友情链接','io_setting'),
            'default'    => true,
            'class'      => 'compact',
            'dependency' => array( 'show_friendlink', '==', true )
        ),
        array(
            'id'          => 'home_links',
            'type'        => 'checkbox',
            'title'       => ' ┗━━ '.__('首页显示分类','io_setting'),
            'after'       => __('不选则全部显示。','io_setting'),
            'inline'      => true,
            'class'       => 'compact',
            'options'     => 'categories',
            'query_args'  => array(
                'taxonomy'  => 'link_category',
            ),
            'dependency'  => array( 'show_friendlink|links', '==|==', 'true|true' )
        ),
        array(
            'id'          => 'links_pages',
            'type'        => 'select',
            'title'       => ' ┗━━ '.__('友情链接归档页','io_setting'),
            'after'       => __(' 如果没有，新建页面，选择“友情链接”模板并保存。','io_setting'),
            'options'     => 'pages',
            'class'       => 'compact',
            'query_args'  => array(
                'posts_per_page'  => -1,
            ),
            'placeholder' => __('选择友情链接归档页面', 'io_setting'),
            'dependency'  => array( 'show_friendlink|links', '==|==', 'true|true' )
        ),
        array(
            'id'      => 'save_image',
            'type'    => 'switcher',
            'title'   => __('本地化外链图片','io_setting'),
            'label'   => __('自动存储外链图片到本地服务器','io_setting'),
            'after'   => __('<br><p>只支持经典编辑器</p><strong>注：</strong>使用古腾堡(区块)编辑器的请不要开启，否则无法保存文章','io_setting'),
            'default' => false,
        ),
        array(
            'id'      => 'exclude_image', 
            'type'    => 'textarea',
            'title'   => __('本地化外链图片白名单','io_setting'),
            'after'   => __('一行一个地址，注意不要有空格。<br>不需要包含http(s)://<br>如：iowen.cn','io_setting'),
            'class'   => 'compact',
            'default' => 'alicdn.com',
            'dependency' => array( 'save_image', '==', true )
        ),
    )
));

//
// 首页设置
//
CSF::createSection( $prefix, array(
    'id'    => 'home_setting',
    'title' => __('首页设置','io_setting'),
    'icon'  => 'fas fa-laptop-house',
));

//
// 常规设置
//
CSF::createSection( $prefix, array(
    'parent'      => 'home_setting',
    'title'       => __('常规设置','io_setting'),
    'icon'        => 'fas fa-igloo', 
    'fields'       => array(  
        array(
            'id'      => 'po_prompt',
            'type'    => 'radio',
            'title'   => __('网址块弹窗提示','io_setting'),
            'desc'    => __('网址块默认的弹窗提示内容','io_setting'),
            'default' => 'url',
            'inline'  => true,
            'options' => array(
                'null'      => __('无','io_setting'),
                'url'       => __('链接','io_setting'),
                'summary'   => __('简介','io_setting'),
                'qr'        => __('二维码','io_setting'),
            ),
            'after'   => __('如果网址添加了自定义二维码，此设置无效','io_setting'),
        ),
        array(
            'id'         => 'columns',
            'type'       => 'radio',
            'title'      => __('网址列数','io_setting'),
            'subtitle'   => __('网址块列表一行显示的个数','io_setting'),
            'default'    => '6',
            'inline'     => true,
            'options'    => array(
                '2'  => '2',
                '3'  => '3',
                '4'  => '4',
                '6'  => '6',
                '10' => '10'
            ),
            'after'      => '只对网址有效。',
        ),
        array(
            'id'      => 'two_columns',
            'type'    => 'switcher',
            'title'   => __('小屏显示两列','io_setting'),
            'label'   => __('手机等小屏幕显示两列。不支持[大]号卡片样式，前往“内容设置”->“网址设置”里修改','io_setting'),
            'default' => false,
        ),
        array(
            'id'        => 'card_n',
            'type'      => 'fieldset',
            'title'     => __('在首页分类下显示的内容数量','io_setting'),
            'fields'    => array(
                array(
                    'id'    => 'favorites',
                    'type'  => 'spinner',
                    'title' => __('网址数量','io_setting'),
                    'step'       => 1,
                ),
                array(
                    'id'    => 'apps',
                    'type'  => 'spinner',
                    'title' => __('App 数量','io_setting'),
                    'step'       => 1,
                ),
                array(
                    'id'    => 'books',
                    'type'  => 'spinner',
                    'title' => __('书籍数量','io_setting'),
                    'step'       => 1,
                ),
                array(
                    'id'    => 'category',
                    'type'  => 'spinner',
                    'title' => __('文章数量','io_setting'),
                    'step'       => 1,
                ),
            ),
            'default'        => array(
                'favorites'   => 20,
                'apps'        => 16,
                'books'       => 16,
                'category'    => 16,
            ),
            'after'      => '填写需要显示的数量。<br>-1 为显示分类下所有网址<br>&nbsp;0 为根据<a href="'.home_url().'/wp-admin/options-reading.php">系统设置数量显示</a>',
        ),
        array(
            'id'        => 'second_count',
            'type'      => 'switcher',
            'title'     => __('次级导航页显示数量','io_setting'),
            'text_on' => '全部显示',
            'text_off'=> '继承上方设置项',
            'text_width' => 120,
        ),
        array(
            'id'      => 'show_sticky',
            'type'    => 'switcher',
            'title'   => __('置顶内容前置','io_setting'),
            'label'   => __('首页置顶的内容显示在前面','io_setting'),
            'default' => false,
        ),
        array(
            'id'      => 'category_sticky',
            'type'    => 'switcher',
            'title'   => ' ┗━━ '.__('分类&归档页置顶内容前置','io_setting'),
            'default' => false, 
            'class'   => 'compact',
            'dependency' => array( 'show_sticky', '==', true )
        ),
        array(
            'id'        => 'home_sort',
            'type'      => 'fieldset',
            'title'     => __('首页分类排序','io_setting'),
            'fields'    => array(
                array(
                    'id'    => 'favorites',
                    'type'  => 'radio',
                    'title' => __('网址排序','io_setting'),
                    'inline'     => true,
                    'options'    => array(
                        '_sites_order'  => '自定义排序字段',
                        'ID'            => 'ID',
                        'modified'      => '修改日期',
                        'date'          => '创建日期',
                        'views'         => '查看次数',
                    ),
                ),
                array(
                    'id'    => 'apps',
                    'type'  => 'radio',
                    'title' => __('APP 排序','io_setting'),
                    'inline'     => true,
                    'options'    => array(
                        'ID'            => 'ID',
                        'modified'      => '修改日期',
                        'date'          => '创建日期',
                        'views'         => '查看次数',
                        '_down_count'   => '下载次数',
                    ),
                ),
                array(
                    'id'    => 'books',
                    'type'  => 'radio',
                    'title' => __('书籍排序','io_setting'),
                    'inline'     => true,
                    'options'    => array(
                        'ID'            => 'ID',
                        'modified'      => '修改日期',
                        'date'          => '创建日期',
                        'views'         => '查看次数',
                    ),
                ),
                array(
                    'id'    => 'category',
                    'type'  => 'radio',
                    'title' => __('文章排序','io_setting'),
                    'inline'     => true,
                    'options'    => array(
                        'ID'            => 'ID',
                        'modified'      => '修改日期',
                        'date'          => '创建日期',
                        'views'         => '查看次数',
                    ),
                ),
            ),
            'default'        => array(
                'favorites'   => '_sites_order',
                'apps'        => 'modified',
                'books'       => 'modified',
                'category'    => 'date',
            ),
            'after'   => '<p style="color: red">'.__('启用“查看次数”“下载次数”等排序方法请开启相关统计，如果对象没有相关数据，则不会显示。','io_setting').'</p>',
        ),
        array(
            'id'      => 'same_ico',
            'type'    => 'switcher',
            'title'   => __('统一图标','io_setting'),
            'label'   => __('首页侧边栏和内容标题统一图标','io_setting'),
            'default' => false,
            'class'   => '',
        ),
        array(
            'id'      => 'tab_type',
            'type'    => 'switcher',
            'title'   => __('tab(选项卡)模式','io_setting'),
            'label'   => __('首页使用标签模式展示2级收藏网址','io_setting'),
            'default' => true,
        ),
        array(
            'id'      => 'tab_ajax',
            'type'    => 'switcher',
            'title'   => ' ┗━━ '.__('tab模式 ajax 加载','io_setting'),
            'label'   => __('降低首次载入时间，但切换tab时有一定延时','io_setting'),
            'default' => true,
            'class'   => 'compact',
            'dependency' => array( 'tab_type', '==', true )
        ),
        array(
            'id'      => 'tab_p_n',
            'type'    => 'switcher',
            'title'   => __('父级名称','io_setting'),
            'label'   => __('网址块分类名前面显示父级分类名称','io_setting'),
            'default' => false,
        ),
    )
));

//
// 首页内容
//
CSF::createSection( $prefix, array(
    'parent'      => 'home_setting',
    'title'       => __('首页内容','io_setting'),
    'icon'        => 'fa fa-home', 
    'fields'   => array(  
        array(
            'id'      => 'show_bulletin',
            'type'    => 'switcher',
            'title'   => __('启用公告','io_setting'),
            'label'   => __('启用自定义文章类型“公告”，启用后刷新页面','io_setting'),
            'default' => true,
        ),
        array(
            'id'         => 'bulletin',
            'type'       => 'switcher',
            'title'      => ' ┗━━ '.__('显示公告','io_setting'),
            'label'      => __('在首页顶部显示公告','io_setting'),
            'default'    => true,
            'class'      => 'compact',
            'dependency' => array( 'show_bulletin', '==', true )
        ),
        array(
            'id'         => 'bulletin_n',
            'type'       => 'spinner',
            'title'      => ' ┗━━ '.__('公告数量','io_setting'),
            'after'      => __('需要显示的公告篇数','io_setting'),
            'max'        => 10,
            'min'        => 1,
            'step'       => 1,
            'default'    => 2,
            'class'      => 'compact',
            'dependency' => array( 'bulletin|show_bulletin', '==|==', 'true|true' )
        ),
        array(
            'id'             => 'all_bull',
            'type'           => 'select',
            'title'          => ' ┗━━ '.__('公告归档页','io_setting'),
            'after'           => __(' 如果没有，新建页面，选择“所有公告”模板并保存。','io_setting'),
            'options'        => 'pages',
            'query_args'     => array(
                'posts_per_page'  => -1,
            ),
            'class'          => 'compact',
            'placeholder'    => __('选择公告归档页面', 'io_setting'),
            'dependency'     => array( 'bulletin|show_bulletin', '==|==', 'true|true' )
        ),
        array(
            'id'         => 'customize_card',
            'type'       => 'switcher',
            'title'      => __('自定义网址（我的导航）','io_setting'),
            'label'      => __('显示游客自定义网址模块，允许游客自己添加网址和记录最近点击，数据保存于游客电脑。','io_setting'),
            'default'    => true,
        ),
        array(
            'id'         => 'customize_d_n',
            'type'       => 'text',
            'title'      => ' ┗━━ '.__('预设网址（我的导航）','io_setting'),
            'class'      => 'compact',
            'after'      => __('自定义网址模块添加预设网址，显示位置：<br>1、首页“我的导航”模块预设网址<br>2、“mini 书签页”快速导航列表<br><br>例：1,22,33,44 用英语逗号分开（填文章ID）','io_setting'), 
        ),
        array(
            'id'         => 'customize_show',
            'type'       => 'switcher',
            'title'      => ' ┗━━ '.__('始终显示预设网址','io_setting'),
            'label'      => __('开启用户中心后仍然显示预设网址','io_setting'), 
            'default'    => true,
            'class'      => 'compact',
            'dependency' => array( 'customize_card', '==', true )
        ),
        array(
            'id'         => 'customize_count',
            'type'       => 'spinner',
            'title'      => ' ┗━━ '.__('最多分类','io_setting'),
            'after'      => __('最多显示多少用户自定义网址分类，0 为全部显示','io_setting'), 
            'step'       => 1,
            'default'    => 8,
            'class'      => '',
            'class'      => 'compact',
            'dependency' => array( 'customize_card', '==', true )
        ),
        array(
            'id'         => 'customize_n',
            'type'       => 'spinner',
            'title'      => ' ┗━━ '.__('最近点击','io_setting'),
            'after'      => __('最近点击网址记录的最大数量','io_setting'),
            'max'        => 50,
            'min'        => 1,
            'step'       => 1,
            'default'    => 10,
            'class'      => 'compact',
            'dependency' => array( 'customize_card', '==', true )
        ),
        array(
            'id'         => 'hot_card',
            'type'       => 'switcher',
            'title'      => __('首页热门网址','io_setting'),
            'label'      => __('首页显示热门网址模块，需开启访问统计，并产生了访问和点赞数据','io_setting'),
            'default'    => false,
        ),
        array(
            'id'         => 'hot_card_mini',
            'type'       => 'switcher',
            'title'      => ' ┗━━  '.__('mini网址块','io_setting'),
            'class'      => 'compact',
            'label'      => __('显示热门网址启用mini网址块，只对网址有效','io_setting'),
            'default'    => false,
            'dependency' => array( 'hot_card', '==', true )
        ),
        array(
            'id'         => 'hot_n',
            'type'       => 'spinner',
            'title'      => ' ┗━━ '.__('热门数量','io_setting'),
            'max'        => 50,
            'min'        => 1,
            'step'       => 1,
            'default'    => 10,
            'class'      => 'compact',
            'dependency' => array( 'hot_card', '==', true )
        ),
        array(
            'id'             => 'hot_menu_1',
            'type'           => 'sorter',
            'title'          => ' ┗━━  '.__('热门菜单排序','io_setting'),
            'class'          => 'compact',
            'default'        => array(
                'enabled'      => array(
                    'sites-views'       => '热门网址',
                    'sites-_like_count' => '大家喜欢',
                    'sites-date'        => '最新网址',
                ),
                'disabled'     => array(
                    'app-views'        => '热门 App',
                    'app-_like_count'  => '最爱 App',
                    'app-date'         => '最新 App',
                    'app-_down_count'  => '下载最多 APP',
                    'book-views'       => '热门书籍',
                    'book-_like_count' => '最爱书籍',
                    'book-date'        => '最新书籍',
                ),
            ),
            'enabled_title'  => __('启用','io_setting'),
            'disabled_title' => __('隐藏', 'io_setting'),
            'dependency' => array( 'hot_card', '==', true )
        ),
    )
));

//
// 搜索设置
//
CSF::createSection( $prefix, array(
    'parent'      => 'home_setting',
    'title'       => __('搜索设置','io_setting'),
    'icon'        => 'fas fa-search', 
    'fields'   => array(   
        array(
            'id'      => 'search_position',
            'type'    => 'checkbox',
            'title'   => __('搜索位置','io_setting'),
            'default' => 'home',
            'inline'  => true,
            'options' => array(
                'home'      => __('默认位置','io_setting'),
                'top'       => __('头部','io_setting'),
                'tool'      => __('页脚小工具','io_setting'),
            ), 
            'after'      => __('默认位置在首页内容前面和分类内容前面显示搜索框','io_setting'),
        ),
        array(
            'id'         => 'baidu_hot_words',
            'type'       => 'radio',
            'title'      => __('搜索词补全','io_setting'),
            'default'    => 'baidu',
            'inline'     => true,
            'options'    => array(
                'null'    => '无',
                'baidu'   => '百度',
                'google'  => 'Google',
            ),
            'after'      => '选择搜索框词补全源，选无则不补全。',
        ),
        array(
            'id'        => 'search_skin',
            'type'      => 'fieldset',
            'title'     => __('首页顶部搜索设置','io_setting'),
            'fields'    => array(
                array(
                    'id'         => 'search_big',
                    'type'       => 'switcher',
                    'title'      => __('搜索样式','io_setting'),
                    'text_on'    => 'big 模式',
                    'text_off'   => '简洁模式',
                    'text_width' => 100,
                    'default'    => true,
                ),
                array(
                    'id'         => 'search_station',
                    'type'       => 'switcher',
                    'title'      => __('前置站内搜索','io_setting'),
                    'label'      => __('开头显示站内搜索，关闭将不显示搜索推荐','io_setting'),
                    'default'    => true,
                    'dependency' => array( 'search_big', '==', true )
                ),
                array(
                    'id'         => 'big_title',
                    'type'       => 'text',
                    'title'      => __('大字标题','io_setting'),
                    'after'      => __('留空不显示','io_setting'), 
                    'class'   => '', 
                    'dependency' => array( 'search_big', '==', true )
                ),
                array(
                    'id'         => 'changed_bg',
                    'type'       => 'switcher',
                    'title'      => __('暗色主题压暗背景','io_setting'),
                    'label'      => __('切换到暗色主题时自动压暗背景','io_setting'),
                    'default'    => true,
                    'dependency' => array( 'search_big', '==', true )
                ),
                array(
                    'id'      => 'big_skin',
                    'type'    => 'radio',
                    'title'   => __('背景模式','io_setting'),
                    'default' => 'css-color',
                    'inline'  => true,
                    'options' => array(
                        'no-bg'         => __('无背景','io_setting'),
                        'css-color'     => __('颜色','io_setting'),
                        'css-img'       => __('自定义图片','io_setting'),
                        'css-bing'      => __('bing 每日图片','io_setting'),
                        'canvas-fx'     => __('canvas 特效','io_setting'),
                    ),
                    'dependency' => array( 'search_big', '==', true )
                ),
                array(
                    'id'        => 'search_color',
                    'type'      => 'color_group',
                    'title'     => 'Color Group',
                    'options'   => array(
                        'color-1' => 'Color 1',
                        'color-2' => 'Color 2',
                        'color-3' => 'Color 3',
                    ),
                    'default'   => array(
                        'color-1' => '#ff3a2b',
                        'color-2' => '#ed17de',
                        'color-3' => '#f4275e',
                    ),
                    'dependency' => array( 'search_big|big_skin', '==|==', 'true|css-color' )
                ),
                array(
                    'id'        => 'search_img',
                    'type'      => 'upload',
                    'title'     => '背景图片',
                    'add_title' => __('上传','io_setting'),
                    'dependency' => array( 'search_big|big_skin', '==|==', 'true|css-img' )
                ),
                array(
                    'id'      => 'canvas_id',
                    'type'    => 'radio',
                    'title'   => __('canvas 样式','io_setting'),
                    'default' => '0',
                    'inline'  => true,
                    'options' => get_all_fx_bg(),
                    'dependency' => array( 'search_big|big_skin', '==|==', 'true|canvas-fx' )
                ),
                array(
                    'id'         => 'custom_canvas',
                    'type'       => 'text',
                    'title'      => __('canvas地址','io_setting'),
                    'after'      => __('留空会爆炸，既然选择了，请不要留空！！！<br>示例：//owen0o0.github.io/ioStaticResources/canvas/01.html<br>注意：可能会有跨域问题，解决方法百度。','io_setting'), 
                    'default'    => '//owen0o0.github.io/ioStaticResources/canvas/01.html',
                    'attributes' => array(
                        'style'    => 'width: 100%'
                    ),
                    'dependency' => array( 'search_big|canvas_id|big_skin', '==|==|==', 'true|custom|canvas-fx' )
                ),
                array(
                    'id'         => 'bg_gradual',
                    'type'       => 'switcher',
                    'title'      => __('背景渐变','io_setting'),
                    'default'    => false,
                    'dependency' => array( 'search_big|big_skin', '==|!=', 'true|no-bg' )
                ),
                array(
                    'id'         => 'post_top',
                    'type'       => 'switcher',
                    'title'      => __('文章轮播上移','io_setting'),
                    'default'    => true,
                    'dependency' => array( 'search_big', '==', true )
                ),
            ),
            'dependency' => array( 'search_position', 'any', 'home' )
        ),
        array(
            'id'      => 'custom_search',
            'type'    => 'switcher',
            'title'   => __('自定义搜索列表','io_setting'),
            'label'   => __('启用后保存再刷新页面','io_setting'),
            'after'   => $set_search,
            'class'   => 'new',
            'default' => false,
        ), 
    )
));
//
// 统计浏览
//
CSF::createSection( $prefix, array(
    'title'    => __('统计浏览','io_setting'),
    'icon'     => 'fa fa-eye',
    'fields'   => array(  
        array(
            'id'      => 'post_views',
            'type'    => 'switcher',
            'title'   => __('访问统计','io_setting'),
            'label'   => __('启用前先禁用WP-PostViews插件，因为功能重叠','io_setting'),
            'default' => true,
        ),
        array(
            'type'    => 'notice',
            'style'   => 'danger',
            'content' => '注意：关闭“访问统计”后，以下功能会受影响！',
            'dependency' => array( 'post_views', '==', false )
        ),
        array(
            'id'      => 'views_n',
            'type'    => 'text',
            'title'   => ' ┗━━ '.__('访问基数','io_setting'),
            'after'   => __('随机访问基数，取值范围：(0~10)*访问基数<br>设置大于0的整数启用，会导致访问统计虚假，酌情开启，关闭请填0','io_setting'),
            'default' => 0,
            'class'      => 'compact',
            'dependency' => array( 'post_views', '==', true )
        ),
        array(
            'id'      => 'views_r',
            'type'    => 'text',
            'title'   => ' ┗━━ '.__('访问随机计数','io_setting'),
            'after'   => __('访问一次随机增加访问次数，比如访问一次，增加5次<br>取值范围：(1~10)*访问随机数<br>设置大于0的数字启用，可以是小数，如：0.5，但小于0.5会导致取0值<br>会导致访问统计虚假，酌情开启，关闭请填0','io_setting'),
            'default' => 0,
            'class'      => 'compact',
            'dependency' => array( 'post_views', '==', true )
        ),
        array(
            'id'      => 'like_n',
            'type'    => 'text',
            'title'   => __('点赞基数','io_setting'),
            'after'   => __('随机点赞基数，取值范围：(0~10)*点赞基数<br>设置大于0的整数启用，会导致点赞统计虚假，酌情开启，关闭请填0','io_setting'),
            'default' => 0,
            'dependency' => array( 'user_center', '==', false,'all' )
        ),
        array(
            'id'      => 'leader_board',
            'type'    => 'switcher',
            'title'   => __('按天记录统计数据','io_setting'),
            'label'   => 'BETA',
            'default' => true,
        ),
        array(
            'id'      => 'details_chart',
            'type'    => 'switcher',
            'title'   => ' ┗━━ '.__('详情页显示统计图表','io_setting'),
            'label'   => 'BETA', 
            'class'   => 'compact',
            'default' => true,
        ),
        array(
            'id'         => 'how_long',
            'type'       => 'spinner',
            'title'      => ' ┗━━ '.__('统计数据保留天数','io_setting'),
            'after'      => __('最少30天','io_setting'),
            'unit'       => '天',
            'step'       => 1,
            'default'    => 30,
            'class'      => 'compact',
            'dependency' => array( 'leader_board', '==', true )
        ),
        array(
            'id'        => 'views_options',
            'type'      => 'fieldset',
            'title'     => __('浏览计数设置','io_setting'),
            'fields'    => array(
                array(
                    'id'          => 'count',
                    'type'        => 'select',
                    'title'       => __( '计数来源', 'io_setting' ),
                    'options'     => array(
                        '0'  => __( '所有人', 'io_setting' ),
                        '1'  => __( '只有访客', 'io_setting' ),
                        '2'  => __( '只有注册用户', 'io_setting' ),
                    ),
                ),
                array(
                    'id'      => 'exclude_bots',
                    'type'    => 'switcher',
                    'title'   => __('排除机器人(爬虫等)','io_setting'),
                ),
                array(
                    'id'          => 'template',
                    'type'        => 'select',
                    'title'       => __( '显示模板', 'io_setting' ),
                    'options'     => array(
                        '0'  => __( '正常显示计数', 'io_setting' ),
                        '1'  => __( '以千单位显示', 'io_setting' ),
                    ),
                ),
                array(
                    'id'      => 'use_ajax',
                    'type'    => 'switcher',
                    'title'   => __('使用Ajax更新浏览次数','io_setting'),
                    'class'   => $views_use_ajax,
                    'label'      => '如果启用了静态缓存，将使用AJAX更新浏览计数，且“随机计数”失效。',
                ),
            ),
            'default'        => array(
                'count'         => '0',
                'exclude_bots'  => true,
                'template'      => '0',
                'use_ajax'      => true,
            ),
            'dependency' => array( 'post_views', '==', true )
        ),
    )
));
//
// 内容设置
//
CSF::createSection( $prefix, array(
    'id'    => 'srticle_settings',
    'title' => __('内容设置','io_setting'),
    'icon'  => 'fa fa-file-text',
));
//
// 文章
//
CSF::createSection( $prefix, array(
    'parent'   => 'srticle_settings',
    'title'    => __('文章','io_setting'),
    'icon'     => 'fas fa-newspaper',
    'fields'   => array(  
        array(
            'id'         => 'article_module',
            'type'       => 'switcher',
            'title'      => __('首页显示文章模块','io_setting'),
            'label'      => __('头部启用文章模块','io_setting'),
            'default'    => false,
        ),
        array(
            'id'         => 'article_n',
            'type'       => 'spinner',
            'title'      => ' ┗━━ '.__('幻灯片数量','io_setting'),
            'max'        => 10,
            'min'        => 1,
            'step'       => 1,
            'default'    => 5,
            'class'      => 'compact',
            'after'      => __('显示置顶的文章，请把需要显示的文章置顶。','io_setting'),
            'dependency' => array( 'article_module', '==', true )
        ),
        array(
            'id'          => 'two_article',
            'type'        => 'text',
            'title'       => ' ┗━━ '.__('两篇文章','io_setting'),
            'after'          => __('自定义文章模块中间的两篇文章，留空则随机展示。<br>填写两个文章id，用英语逗号分开，如：11,100','io_setting'),
            'class'       => 'compact',
            'dependency'  => array( 'article_module', '==', true ),
        ),
        array(
          'id'             => 'blog_pages',
          'type'           => 'select',
          'title'          => ' ┗━━ '.__('博客页面','io_setting'),
          'after'           => __(' 如果没有，新建页面，选择“博客页面”模板并保存。<br>用于最新资讯旁边的“所有”按钮。','io_setting'),
          'options'        => 'pages',
          'class'          => 'compact',
          'query_args'     => array(
            'posts_per_page'  => -1,
          ),
          'placeholder'    => __('选择一个页面', 'io_setting'),
          'dependency'     => array( 'article_module', '==', true )
        ),
        array(
            'id'          => 'article_not_in',
            'type'        => 'text',
            'title'       => ' ┗━━ '.__('资讯列表排除分类','io_setting'),
            'after'          => __('填写分类id，用英语逗号分开，如：11,100<br>文章分类id列表：','io_setting').get_cats_id(),
            'class'       => 'compact',
            'dependency'  => array( 'article_module', '==', true ),
        ),
        array(
            'id'        => 'post_card_mode',
            'type'      => 'image_select',
            'title'     => __('文章卡片样式','io_setting'),
            'options'   => array(
                'card'    => get_theme_file_uri('/images/op-app-c-card.png'),
                'default' => get_theme_file_uri('/images/op-post-c-def.png'),
            ),
            'default'   => 'default',
        ),
    )
));
//
// 网址设置
//
CSF::createSection( $prefix, array(
    'parent'   => 'srticle_settings',
    'title'    => __('网址设置','io_setting'),
    'icon'     => 'fa fa-sitemap',
    'fields'   => array(  
        array(
            'id'        => 'site_card_mode',
            'type'      => 'image_select',
            'title'     => __('网址卡片样式','io_setting'),
            'options'   => array(
                'max'     => get_theme_file_uri('/images/op-site-c-max.png'),
                'default' => get_theme_file_uri('/images/op-site-c-def.png'),
                'min'     => get_theme_file_uri('/images/op-site-c-min.png'),
            ),
            'default'   => 'default',
            'after'   => __('选择首页网址块显示风格：大、中、小','io_setting'),
        ),
        array(
            'id'        => 'url_reverse',
            'type'      => 'switcher',
            'title'     => __('“直达”和“详情页” url 颠倒','io_setting'),
            'label'     => __('需先开启“网址详情页”','io_setting'),
            'dependency'  => array( 'details_page', '==', true, 'all', 'visible' ),
        ),
        array(
            'id'        => 'site_archive_n',
            'type'      => 'number',
            'title'     => __('网址分类页显示数量','io_setting'),
            'default'   => 30,
            'after'     => '填写需要显示的数量。填写 0 为根据<a href="'.home_url().'/wp-admin/options-reading.php">系统设置数量显示</a>',
        ),
        array(
            'id'         => 'no_ico',
            'type'       => 'switcher',
            'title'      => __('无图标模式','io_setting'),
            'default'    => false,
        ),
    )
));
//
// app设置
//
CSF::createSection( $prefix, array(
    'parent'   => 'srticle_settings',
    'title'    => __('app设置','io_setting'),
    'icon'     => 'fa fa-shopping-bag',
    'fields'   => array( 
        array(
            'id'        => 'app_card_mode',
            'type'      => 'image_select',
            'title'     => __('app 卡片样式','io_setting'),
            'options'   => array(
              'card'    => get_theme_file_uri('/images/op-app-c-card.png'),
              'default' => get_theme_file_uri('/images/op-app-c-def.png'),
            ),
            'default'   => 'default',
            'after'   => __('选择首页app块显示风格','io_setting')
        ), 
        array(
            'id'        => 'app_archive_n',
            'type'      => 'number',
            'title'     => __('App 分类页显示数量','io_setting'),
            'default'   => 30,
            'after'     => '填写需要显示的数量。<br>填写 0 为根据<a href="'.home_url().'/wp-admin/options-reading.php">系统设置数量显示</a>',
        ),
        //array(
        //    'id'        => 'default_app_screen',
        //    'type'      => 'upload',
        //    'title'     => __('app 默认截图','io_setting'),
        //    'add_title' => __('添加','io_setting'),
        //    'after'     => __('app截图为空时显示这项设置的内容','io_setting'),
        //    'default'   => get_theme_file_uri('/screenshot.jpg'),
        //),
    )
));
//
// 书籍设置
//
CSF::createSection( $prefix, array(
    'parent'   => 'srticle_settings',
    'title'    => __('书籍设置','io_setting'),
    'icon'     => 'fa fa-book',
    'fields'   => array(  
        array(
            'id'        => 'book_archive_n',
            'type'      => 'number',
            'title'     => __('书籍分类页显示数量','io_setting'),
            'default'   => 20,
            'after'     => '填写需要显示的数量。<br>填写 0 为根据<a href="'.home_url().'/wp-admin/options-reading.php">系统设置数量显示</a>',
        ),
        array(
            'id'            => 'books_metadata',
            'type'          => 'group',
            'title'         => '书籍&影视元数据默认值',
            'fields'        => array(
                array(
                    'id'    => 'term',
                    'type'  => 'text',
                    'title' => '项目(控制在5个字内)',
                ),
                array(
                    'id'          => 'detail',
                    'type'        => 'text',
                    'title'       => '内容',
                    'placeholder' => __('如留空，请删除此项','io_setting'),
                ),
            ),
            'default' => array(
                array(
                    'term'    => '作者',
                ),
                array(
                    'term'    => '出版社',
                ),
                array(
                    'term'    => '发行日期',
                    'detail'  => date('Y-m'),
                ),
            ),
        ),
    )
));

//
// 页脚设置
//
CSF::createSection( $prefix, array(
    'title'    => __('页脚设置','io_setting'),
    'icon'     => 'fa fa-caret-square-o-down',
    'fields'   => array( 
        array(
            'id'     => 'icp',
            'type'   => 'text',
            'title'  => __('备案号','io_setting'), 
            'subtitle'   => __('此选项“自定义页脚版权”非空则禁用','io_setting'),
            'dependency'  => array( 'footer_copyright', '==', '', '', 'visible' ),
        ),
        array(
            'id'     => 'police_icp',
            'type'   => 'text',
            'title'  => __('公安备案号','io_setting'), 
            'subtitle'   => __('此选项“自定义页脚版权”非空则禁用','io_setting'),
            'dependency'  => array( 'footer_copyright', '==', '', '', 'visible' ),
            'class'     => ''
        ),

        array(
            'id'          => 'footer_copyright',
            'type'        => 'wp_editor',
            'title'       => __('自定义页脚版权','io_setting'),
            'height'      => '100px',
            'sanitize'    => false,
        ),

        array(
            'id'            => 'footer_statistics',
            'type'          => 'wp_editor',
            'title'         => __('统计代码','io_setting'),
            'tinymce'       => false,
            'quicktags'     => true,
            'media_buttons' => false,
            'height'        => '100px',
            'sanitize'      => false,
            'after'         => '显示在页脚的统计代码，如需要添加到 &lt;/head&gt; 前，请到“添加代码”中添加。',
        ),

        array(
            'id'          => 'down_statement',
            'type'        => 'wp_editor',
            'title'       => __('下载页版权声明','io_setting'),
            'default'     => __('本站大部分下载资源收集于网络，只做学习和交流使用，版权归原作者所有。若您需要使用非免费的软件或服务，请购买正版授权并合法使用。本站发布的内容若侵犯到您的权益，请联系站长删除，我们将及时处理。','io_setting'),
            'height'      => '100px',
            'sanitize'    => false,
        ),
    )
));

//
// SEO设置
//
CSF::createSection( $prefix, array(
    'title'       => __('SEO设置','io_setting'),
    'icon'        => 'fa fa-paw',
    'description' => __('主题seo获取规则：<br>标题：页面、文章的标题<br>关键词：默认获取文章的标签，如果没有，则为标题加网址名称<br>描叙：默认获取文章简介','io_setting'),
    'fields'      => array(
        array(
            'id'     => 'seo_home_keywords',
            'type'   => 'text',
            'title'  => __('首页关键词','io_setting'),
            'after'  => __('其他页面如果获取不到关键词，默认调取此设置','io_setting'),
        ),
        array(
            'id'     => 'seo_home_desc',
            'type'   => 'textarea',
            'title'  => __('首页描述','io_setting'),
            'after'  => __('其他页面如果获取不到描述，默认调取此设置','io_setting'),
        ),
        array(
            'id'        => 'og_img',
            'type'      => 'upload',
            'title'     => __('og 标签默认图片','io_setting'),
            'add_title' => __('上传','io_setting'),
            'after'     => __('QQ、微信分享时显示的缩略图<br>主题会默认获取文章、网址等内容的图片，但是如果内容没有图片，则获取此设置','io_setting'),
            'default'   => get_theme_file_uri('/screenshot.jpg'),
        ),
        array(
            'id'      => 'site_map',
            'title'   => __('SiteMAP','io_setting'),
            'type'    => 'switcher',
            'label'    => __('启用主题 sitemap，生成 sitemap.xml 文件','io_setting'),
            'after'  => '<br><br>'.__('不适应于多站点模式，请改用其他插件。','io_setting'),
            'default' => false,
        ),
        array(
            'id'        => 'site_options',
            'type'      => 'fieldset',
            'title'     => __('SiteMAP选项','io_setting'),
            'fields'    => array(
                array(
                    'type'    => 'content',
                    'content' => '<span>自动生成xml文件，遵循Sitemap协议，用于指引搜索引擎快速、全面的抓取或更新网站上内容及处理错误信息。兼容百度、google、360等主流搜索引擎。</span><span style="display:block; margin-top: 10px;">注意：参数需要保存后才生效，请设置完参数保存后再点击&quot;生成sitemap&quot;按钮。</span>',
                ),
                array(
                    'id'      => 'baidu-post-types',
                    'type'    => 'checkbox',
                    'title'   => __('生成链接文章类型','io_setting'),
                    'options' => 'post_types',
                    'inline'  => true,
                    'after'      => '例：如果仅希望生成文章的sitemap，则只勾选文章即可。'
                ),
                array(
                    'id'      => 'baidu-taxonomies',
                    'type'    => 'checkbox',
                    'title'   => __( '生成链接分类', 'io_setting' ),
                    'options' => 'setting_get_taxes',
                    'inline'  => true,
                ),
                array(
                    'id'      => 'baidu-num',
                    'type'    => 'text',
                    'title'   => __('生成链接数量','io_setting'),
                    'after'   => '链接数越大所占用的资源也越大，根据自己的服务器配置情况设置数量。最新发布的文章首先排在最前。 -1 表示所有。<br />此数量仅指post type的数量总和，不包括分类，勾选的分类是全部生成链接。',
                ),
                array(
                    'id'      => 'baidu-auto-update',
                    'type'    => 'switcher',
                    'title'   => __('自动更新','io_setting'),
                    'label'   => '勾选则发布新文章或者删除文章时自动更新sitemap。',
                ),
                array(
                    'type'     => 'callback',
                    'function' => 'io_site_map_but',
                ),
            ),
            'default' => array(
                'sitemap-file'        => 'sitemap', 
                'baidu-post-types'    => array( 'post', 'page' ),
                'baidu-taxonomies'    => array( 'category' ),
                'baidu-num'            => '500',
                'baidu-auto-update'    => true,
            ),
            'dependency' => array( 'site_map', '==', true )
        ),
        array(
            'id'        => 'baidu_submit',
            'type'      => 'fieldset',
            'title'     => __('百度主动推送','io_setting'),
            'fields'    => array(
                array(
                    'id'    => 'switcher',
                    'type'  => 'switcher',
                    'title' => __('开启','io_setting'),
                ),    
                array(
                    'id'       => 'token_p',
                    'type'     => 'text',
                    'title'    => __('推送token值','io_setting'),
                    'after'    => '<br>'.__('输入百度主动推送token值','io_setting'),
                    'dependency'   => array( 'switcher', '==', 'true' )
                ), 
            ),
            'default'        => array(
                'switcher'    => false,
            ),
        ),
        array(
            'id'        => 'baidu_xzh',
            'type'      => 'fieldset',
            'title'     => __('百度熊掌号推送','io_setting'),
            'fields'    => array(
                array(
                    'id'    => 'switcher',
                    'type'  => 'switcher',
                    'title' => __('开启','io_setting'),
                ),
                array(
                    'id'       => 'xzh_id',
                    'title'    => __('熊掌号 appid','io_setting'),
                    'type'     => 'text',
                    'dependency'   => array( 'switcher', '==', 'true' )
                ),
                array(
                    'id'       => 'xzh_token',
                    'title'    => __('熊掌号 token','io_setting'),
                    'type'     => 'text',
                    'dependency'   => array( 'switcher', '==', 'true' )
                ),
            ),
            'default'        => array(
                'switcher'    => false,
            ),
        ),
        array(
            'id'          => 'sites_default_content',
            'type'        => 'switcher',
            'title'       => __('网址详情页“数据评估”开关','io_setting'),
            'desc'        => __('内容可在主题文件夹里的 templates\content-site.php 底部修改','io_setting'),
            'class'       => '',
        ),
        array(
            'id'        => 'tag_c',
            'type'      => 'fieldset',
            'title'     => __('自动为文章中的关键词添加链接','io_setting'),
            'fields'    => array(
                array(
                    'id'    => 'switcher',
                    'type'  => 'switcher',
                    'title' => __('开启','io_setting'),
                ),
                array(
                    'id'         => 'chain_n',
                    'title'      => __('链接数量','io_setting'),
                    'default'    => '1',
                    'type'       => 'number',
                    'desc'       => __('一篇文章中同一个标签最多自动链接几次，建议不大于2','io_setting'),
                    'dependency' => array( 'switcher', '==', 'true' ),
                ),
            ),
            'default'        => array(
                'switcher'    => true,
            ),
        ),                 
        array(
            'id'      => 'seo_linker',
            'type'    => 'text',
            'title'   => __('连接符','io_setting'),
            'after'   => '<br>'. __('一般用“-”“|”，如果需要左右留空，请自己左右留空格。','io_setting'),
            'default' => ' | ',
        ),
        array(
            'id'         => 'rewrites_types',
            'type'       => 'button_set',
            'title'      => __('固定链接模式','io_setting'),
            'subtitle'   => '<span style="color:#f00">'.__('设置后需重新保存一次固定链接','io_setting').'</span>',
            'options'    =>  array(
                'post_id'  => '/%post_id%/',
                'postname' => '/%postname%/',
            ),
            'default'    => 'post_id'
        ),
        array(
            'id'         => 'rewrites_end',
            'type'       => 'switcher',
            'title'      => __('html 结尾','io_setting'),
            'subtitle'   => '<span style="color:#f00">'.__('设置后需重新保存一次固定链接','io_setting').'</span>',
            'label'      => __('如：http://www.w.com/123.html','io_setting'),
            'default'    => true,
        ),
        array(
            'id'        => 'sites_rewrite',
            'type'      => 'fieldset',
            'title'     => '网址文章固定链接前缀',
            'subtitle'   => '<span style="color:#f00">'.__('设置后需重新保存一次固定链接','io_setting').'</span>',
            'fields'    => array(
                array(
                    'id'    => 'post',
                    'type'  => 'text',
                    'title' => '网址',
                ),
                array(
                    'id'    => 'taxonomy',
                    'type'  => 'text',
                    'title' => '网址分类',
                ),
                array(
                    'id'    => 'tag',
                    'type'  => 'text',
                    'title' => '网址标签',
                ),
            ),
            'default'        => array(
                'post'        => 'sites',
                'taxonomy'    => 'favorites',
                'tag'         => 'sitetag',
            ),
            'after'     => __('设置后需重新保存一次固定链接','io_setting'),
        ),
        array(
            'id'        => 'app_rewrite',
            'type'      => 'fieldset',
            'title'     => 'app文章固定链接前缀',
            'subtitle'   => '<span style="color:#f00">'.__('设置后需重新保存一次固定链接','io_setting').'</span>',
            'fields'    => array(
                array(
                    'id'    => 'post',
                    'type'  => 'text',
                    'title' => 'app',
                ),
                array(
                    'id'    => 'taxonomy',
                    'type'  => 'text',
                    'title' => 'app分类',
                ),
                array(
                    'id'    => 'tag',
                    'type'  => 'text',
                    'title' => 'app标签',
                ),
            ),
            'default'        => array(
                'post'        => 'app',
                'taxonomy'    => 'apps',
                'tag'         => 'apptag',
            ),
            'after'     => __('设置后需重新保存一次固定链接','io_setting'),
        ),
        array(
            'id'        => 'book_rewrite',
            'type'      => 'fieldset',
            'title'     => '书籍文章固定链接前缀',
            'subtitle'   => '<span style="color:#f00">'.__('设置后需重新保存一次固定链接','io_setting').'</span>',
            'fields'    => array(
                array(
                    'id'    => 'post',
                    'type'  => 'text',
                    'title' => '书籍',
                ),
                array(
                    'id'    => 'taxonomy',
                    'type'  => 'text',
                    'title' => '书籍分类',
                ),
                array(
                    'id'    => 'tag',
                    'type'  => 'text',
                    'title' => '书籍标签',
                ),
            ),
            'default'        => array(
                'post'        => 'book',
                'taxonomy'    => 'books',
                'tag'         => 'booktag',
            ),
            'after'     => __('设置后需重新保存一次固定链接','io_setting'),
        ),
    )
));

//
// 其他功能
//
CSF::createSection( $prefix, array(
    'title'  => __('其他功能','io_setting'),
    'icon'   => 'fa fa-flask',
    'fields' => array(
        array(
            'id'      => 'weather',
            'type'    => 'switcher',
            'title'   => __('天气','io_setting'),
            'label'   => __('显示天气小工具','io_setting'),
            'default' => false,
        ),
        array(
            'id'      => 'weather_location',
            'type'    => 'radio',
            'title'   => __('天气位置','io_setting'),
            'default' => __('footer','io_setting'),
            'inline'  => true,
            'options' => array(
                'header'  => __('头部', 'io_setting'),
                'footer'  => __('右下小工具', 'io_setting'),
            ),
            'class'      => 'compact',
            'dependency' => array( 'weather', '==', true )
        ),
        array(
            'id'      => 'hitokoto',
            'type'    => 'switcher',
            'title'   => __('一言', 'io_setting'),
            'label'   => __('右上角显示一言', 'io_setting'),
            'default' => false,
        ),
        array(
            'id'         => 'is_iconfont',
            'type'       => 'switcher',
            'title'      => __('字体图标源', 'io_setting'),
            'label'      => __('fa 和阿里图标二选一，为轻量化资源，不能共用。', 'io_setting'),
            'after'      => '<a href="https://www.iotheme.cn/onenavzhuticaidantubiaoshezhi.html" target="_blank">教程--></a>',
            'text_on'    => '阿里图标',
            'text_off'   => 'fa图标',
            'text_width' => 100,
            'default'    => false,
        ),
        array(
            'type'       => 'notice',
            'style'      => 'success',
            'content'    => __('fa图标库使用CDN，cdn地址修改请在 inc\theme-start.php 文件里修改。默认 CDN 由 www.jsdelivr.com 提供', 'io_setting'),
            'dependency' => array( 'is_iconfont', '==', false )
        ),
        array(
            'id'         => 'iconfont_url',  
            'type'       => 'textarea',
            'title'      => ' ┗━━ '.__('阿里图标库地址', 'io_setting'),
            'after'       => '<h4>输入阿里图标库在线链接，可多个，一行一个地址，注意不要有空格。</h4>图标库地址：<a href="https://www.iconfont.cn/" target="_blank">--></a><br>教程地址：<a href="https://www.iowen.cn/webstack-pro-navigation-theme-iconfont/" target="_blank">--></a>
            <br><p><i class="fa fa-fw fa-info-circle fa-fw"></i> 阿里图标库项目的 FontClass/Symbol前缀 必须为 “<b>io-</b>”，Font Family 必须为 “<b>io</b>”，具体看上面的教程。</p>注意：项目之间的图标名称不能相同',
            'class'      => 'compact',
            'dependency' => array( 'is_iconfont', '==', true )
        ),
        array(
            'id'      => 'is_publish',
            'type'    => 'switcher',
            'title'   => __('投稿直接发布', 'io_setting'),
            'label'   => __('游客投稿的“网址”不需要审核直接发布', 'io_setting'),
            'default' => false,
        ),
        array(
            'id'          => 'tougao_category',
            'type'        => 'select',
            'title'       => ' ┗━━ '.__('游客投稿分类', 'io_setting'),
            'after'       => '<br>'.__('不审核直接发布到指定分类，如果设置此项，前台投稿页的分类选择将失效。', 'io_setting'),
            'placeholder' => __('选择分类','io_setting'),
            'options'     => 'categories',
            'class'       => 'compact',
            'query_args'  => array(
                'taxonomy'    => array('favorites','apps'),
            ),
            'dependency'  => array( 'is_publish', '==', true )
        ),
        array(
            'id'         => 'publish_img_size',
            'type'       => 'spinner',
            'title'      => __('投稿图标大小', 'io_setting'),
            'after'      => __('默认64kb','io_setting'),
            'max'        => 128,
            'min'        => 16,
            'step'       => 2,
            'unit'       => 'kb',
            'default'    => 64,
        ),
        array(
            'type'    => 'submessage',
            'style'   => 'danger',
            'content' => __('邮件发信服务设置，如果你不需要评论邮件通知等功能，可不设置。<p>国内一般使用 SMTP 服务</p>如果要关闭请选择‘PHP’','io_setting'),
        ),
        array(
            'type'     => 'callback',
            'function' => 'io_test_mail',
        ),
        array(
            'id'      => 'i_default_mailer',
            'type'    => 'radio',
            'title'   => 'SMTP/PHPMailer',
            'default' => 'php',
            'inline'  => true,
            'options' => array(
                'php'   => 'PHP',
                'smtp'  => 'SMTP'
            ),
            'after'    => __('使用SMTP或PHPMail作为默认邮件发送方式','io_setting'),
        ),
        array(
            'id'         => 'i_smtp_host',
            'type'       => 'text',
            'title'      => __('SMTP 主机','io_setting'),
            'after'      => __('您的 SMTP 服务主机','io_setting'),
            'class'      => 'compact',
            'dependency' => array( 'i_default_mailer', '==', 'smtp' )
        ), 
        array(
            'id'         => 'i_smtp_port',
            'type'       => 'text',
            'title'      => __('SMTP 端口','io_setting'),
            'after'      => __('您的 SMTP 服务端口','io_setting'),
            'default'    => 465,
            'class'      => 'compact',
            'dependency' => array( 'i_default_mailer', '==', 'smtp' )
        ), 
        array(
            'id'       => 'i_smtp_secure',
            'type'     => 'radio',
            'title'    => __('SMTP 安全','io_setting'),
            'after'    => __('您的 SMTP 服务器安全协议','io_setting'),
            'default'  => 'ssl',
            'inline'   => true,
            'options'  => array(
                'auto'   => 'Auto',
                'ssl'    => 'SSL',
                'tls'    => 'TLS',
                'none'   => 'None'
            ),
            'class'      => 'compact',
            'dependency' => array( 'i_default_mailer', '==', 'smtp' )
        ), 
        array(
            'id'      => 'i_smtp_username',
            'type'    => 'text',
            'title'   => __('SMTP 用户名','io_setting'),
            'after'   => __('您的 SMTP 用户名','io_setting'),
            'class'   => 'compact',
            'dependency'   => array( 'i_default_mailer', '==', 'smtp' )
        ),  
        array(
            'id'      => 'i_smtp_password',
            'type'    => 'text',
            'title'   => __('SMTP 密码','io_setting'),
            'after'   => __('您的 SMTP 密码','io_setting'),
            'class'   => 'compact',
            'dependency'   => array( 'i_default_mailer', '==', 'smtp' )
        ),  
        array(
            'id'      => 'i_smtp_name',
            'type'    => 'text',
            'title'   => __('你的姓名','io_setting'),
            'default' => '一为',
            'after'   => __('你发送的邮件中显示的名称','io_setting'),
            'class'   => 'compact',
            'dependency'   => array( 'i_default_mailer', '==', 'smtp' ), 
        ),  
        array(
            'id'         => 'i_mail_custom_sender',
            'type'       => 'text',
            'title'      => __('PHP Mail 发信人姓名','io_setting'),
            'default'    => '一为',
            'after'      => __('使用 PHPMailer 发送邮件的显示名称','io_setting'),
            'class'      => 'compact',
            'dependency' => array( 'i_default_mailer', '==', 'php' )
        ),  
        array(
            'id'         => 'i_mail_custom_address',
            'type'       => 'text',
            'title'      => __('PHP Mail 发信人地址','io_setting'),
            'after'      => __('当使用 PHPMailer 发信时可使用自定义的发信人地址','io_setting'),
            'class'      => 'compact',
            'dependency' => array( 'i_default_mailer', '==', 'php' )
        ), 
        
        array(
            'type'    => 'submessage',
            'style'   => 'danger',
            'content' => __('下面的功能尽量不要动，出问题了点击上方“重置部分”重置此页设置','io_setting'),
        ),
        array(
            'id'      => 'ico-source',
            'type'    => 'fieldset',
            'title'   => __('图标源设置','io_setting'),
            'subtitle'   => __('自建图标源api源码地址：','io_setting').'<a href="https://api.iowen.cn/favicon" target="_blank">--></a>',
            'fields'  => array(
                array(
                    'id'      => 'url_format',
                    'type'    => 'switcher',
                    'title'   => __('不包含 http(s)://','io_setting'),
                    'default' => true,
                    'subtitle'    => __('根据图标源 api 要求设置，如果api要求不能包含协议名称，请开启此选项','io_setting'),
                ),
                array(
                    'id'      => 'ico_url',
                    'type'    => 'text',
                    'title'   => __('图标源','io_setting'),
                    'default' => 'https://api.iowen.cn/favicon/',
                    'subtitle'    => __('api 地址','io_setting'),
                ),
                array(
                    'id'      => 'ico_png',
                    'type'    => 'text',
                    'title'   => __('图标源api后缀','io_setting'),
                    'default' => '.png',
                    'subtitle'=> __('如：.png ,请根据api格式要求设置，如不需要请留空','io_setting'),
                ),
            ),
        ),
        array(
            'id'         => 'qr_url',
            'type'       => 'text',
            'title'      => __('二维码api','io_setting'),
            'subtitle'   => __('可用二维码api源地址：','io_setting').'<a href="https://www.iowen.cn/latest-qr-code-api-service-https-available/" target="_blank">--></a>',
            'default'    => '//api.qrserver.com/v1/create-qr-code/?size=$sizex$size&margin=10&data=$url',
            'after'      => '参数：<br>$size 大小 <br>$url  地址 <br>如：s=$size<span style="color: #ff0000;">x</span>$size 、 size=$size 、 width=$size&height=$size<br><br>默认内容：//api.qrserver.com/v1/create-qr-code/?size=$sizex$size&margin=10&data=$url',
        ),
        array(
            'id'         => 'random_head_img',
            'type'       => 'textarea',
            'title'      => __('博客随机头部图片','io_setting'),
            'subtitle'   => __('缩略图、文章页随机图片','io_setting'),
            'after'      => __('一行一个图片地址，注意不要有空格<br>','io_setting'),
            'default'    => '//iowen.gitee.io/ioimg/screenshots/1.jpg'.PHP_EOL.'//iowen.gitee.io/ioimg/screenshots/2.jpg'.PHP_EOL.'//iowen.gitee.io/ioimg/screenshots/3.jpg'.PHP_EOL.'//iowen.gitee.io/ioimg/screenshots/4.jpg'.PHP_EOL.'//iowen.gitee.io/ioimg/screenshots/5.jpg'.PHP_EOL.'//iowen.gitee.io/ioimg/screenshots/6.jpg'.PHP_EOL.'//iowen.gitee.io/ioimg/screenshots/7.jpg'.PHP_EOL.'//iowen.gitee.io/ioimg/screenshots/8.jpg'.PHP_EOL.'//iowen.gitee.io/ioimg/screenshots/9.jpg'.PHP_EOL.'//iowen.gitee.io/ioimg/screenshots/0.jpg',
        ),
    )
));

//
// 添加代码
//
CSF::createSection( $prefix, array(
    'title'       => '添加代码',
    'icon'        => 'fa fa-code',
    'fields'      => array(
        array(
            'id'       => 'custom_css',
            'type'     => 'code_editor',
            'title'    => '自定义样式css代码',
            'subtitle' => '显示在网站头部 &lt;head&gt;',
            'after'    => '<p class="cs-text-muted">'.__('自定义 CSS,自定义美化...<br>如：','io_setting').'body .test{color:#ff0000;}<br><span style="color:#f00">'.__('注意：','io_setting').'</span>'.__('不要填写','io_setting').'<strong>&lt;style&gt; &lt;/style&gt;</strong></p>',
            'settings' => array(
                'tabSize' => 2,
                'theme'   => 'mbo',
                'mode'    => 'css',
            ),
            'sanitize' => false,
        ),
        array(
            'id'       => 'code_2_header',
            'type'     => 'code_editor',
            'title'    => '顶部(header)自定义 js 代码',
            'subtitle' => '显示在网站底部',
            'after'    => '<p class="cs-text-muted">'.__('出现在网站顶部 &lt;/head&gt; 前。','io_setting').'<br><span style="color:#f00">'.__('注意：','io_setting').'</span>'.__('必须填写','io_setting').'<strong>&lt;script&gt; &lt;/script&gt;</strong></p>',
            'settings' => array(
                'theme'  => 'dracula',
                'mode'   => 'javascript',
            ),
            'sanitize' => false,
            'class'   => '',
        ),
        array(
            'id'       => 'code_2_footer',
            'type'     => 'code_editor',
            'title'    => '底部(footer)自定义 js 代码',
            'subtitle' => '显示在网站底部',
            'after'    => '<p class="cs-text-muted">'.__('出现在网站底部 body 前。','io_setting').'<br><span style="color:#f00">'.__('注意：','io_setting').'</span>'.__('必须填写','io_setting').'<strong>&lt;script&gt; &lt;/script&gt;</strong></p>',
            'settings' => array(
                'theme'  => 'dracula',
                'mode'   => 'javascript',
            ),
            'sanitize' => false,
        ),
    )
));

//
// 轮播广告
//
CSF::createSection( $prefix, array(
    'id'    => 'add-ad',
    'title' => __('轮播&广告','io_setting'),
    'icon'  => 'fa fa-google',
));
//
// 弹窗轮播
//
CSF::createSection( $prefix, array(
    'parent'      => 'add-ad',
    'title'       => __('弹窗轮播','io_setting'),
    'icon'        => 'fas fa-solar-panel',
    'fields'      => array(
        array(
            'id'    => 'enable_popup',
            'type'  => 'switcher',
            'title' => __('启用弹窗','io_setting'),
            'class'     => '',
        ),
        array(
            'id'        => 'popup_set',
            'type'      => 'fieldset',
            'title'     => __('全局弹窗','io_setting'),
            'fields'    => array(
                array(
                    'id'    => 'delay',
                    'type'  => 'spinner',
                    'title' => __('延时','io_setting'),
                    'after' => __('延时多少秒后显示弹窗','io_setting'),
                    'unit'  => '秒',
                    'step'  => 1,
                ),
                array(
                    'id'    => 'only_home',
                    'type'  => 'switcher',
                    'title' => __('仅首页显示','io_setting'),
                ),
                array(
                    'id'    => 'show',
                    'type'  => 'switcher',
                    'title' => __('显示一次','io_setting'),
                    'label' => __('同一个游客id一天只显示一次','io_setting'),
                ),
                array(
                    'id'    => 'logged_show',
                    'type'  => 'switcher',
                    'title' => __('登录用户只显示一次','io_setting'),
                    'label' => __('同一个用户登录有效期只显示一次','io_setting'),
                ),
                array(
                    'id'    => 'update_date',
                    'type'  => 'date',
                    'title' => '┗━━'.__('公告日期','io_setting'),
                    'settings' => array(
                        'dateFormat'      => 'yy-mm-dd',
                        'changeMonth'     => true,
                        'changeYear'      => true, 
                        'showButtonPanel' => true,
                    ),
                    'after' => __('用于登录用户判断是否有更新（不会显示在弹窗里）','io_setting'),
                    'class'      => 'compact',
                    'dependency' => array( 'logged_show', '==', 'true' ),
                ),
                array(
                    'id'         => 'title',
                    'type'       => 'text',
                    'title'      => __('标题','io_setting'), 
                    'subtitle'   => __('留空不显示','io_setting'),
                ),
                array(
                    'id'          => 'content',
                    'type'        => 'wp_editor',
                    'title'       => __('弹窗内容','io_setting'),
                    'height'      => '100px',
                    'sanitize'    => false,
                    'after'       => '如果a标签想关闭弹窗，请添加class:  popup-close',
                ),
                array(
                    'id'      => 'width',
                    'type'    => 'slider',
                    'title'   => '宽度',
                    'class'   => '',
                    'min'     => 340,
                    'max'     => 1024,
                    'step'    => 10,
                    'unit'    => 'px',
                ),
                array(
                    'id'    => 'valid',
                    'type'  => 'switcher',
                    'title' => __('有效期','io_setting'),
                    'label' => __('设置弹窗有效期','io_setting'),
                ),
                array(
                    'id'    => 'begin_time',
                    'type'  => 'date',
                    'title' => __('开始时间','io_setting'),
                    'settings' => array(
                        'dateFormat'      => 'yy-mm-dd',
                        'changeMonth'     => true,
                        'changeYear'      => true, 
                        'showButtonPanel' => true,
                    ),
                    'dependency' => array( 'valid', '==', 'true' ),
                ), 
                array(
                    'id'    => 'end_time',
                    'type'  => 'date',
                    'title' => __('结束时间','io_setting'),
                    'settings' => array(
                        'dateFormat'      => 'yy-mm-dd',
                        'changeMonth'     => true,
                        'changeYear'      => true, 
                        'showButtonPanel' => true,
                    ),
                    'dependency' => array( 'valid', '==', 'true' ),
                ), 
            ),
            'default'        => array(
                'delay'         => 0,
                'show'          => true,
                'update_date'   => date('Y-m-d'),
                'begin_time'    => date('Y-m-d'),
                'end_time'      => date("Y-m-d", strtotime("+10 day")),
                'width'         => 560,
            ),
            'dependency'  => array( 'enable_popup', '==', 'true', '', 'visible' ),
        ),
        array(
            'id'        => 'carousel_img',
            'type'      => 'repeater',
            'title'     => '首页&博客轮播模块',
            'fields'    => array(
                array(
                    'id'        => 'title',
                    'type'      => 'text',
                    'title'     => '标题',
                ),
                array(
                    'id'      => 'img',
                    'type'    => 'upload',
                    'title'   => __('图片','io_setting'),
                    'library' => 'image',
                ),
                array(
                    'id'        => 'url',
                    'type'      => 'text',
                    'title'     => '目标URL',
                ),
                array(
                    'id'      => 'is_ad',
                    'type'    => 'switcher',
                    'title'   => '是广告',
                    'label'   => __('注意：广告将直达目标URL,不会添加跳转和nofollow','io_setting'),
                ),
            )
        ),
        //TODO 待加轮播
    )
));
//
// 首页广告
//
CSF::createSection( $prefix, array(
    'parent'      => 'add-ad',
    'title'       => __('首页广告','io_setting'),
    'icon'        => 'fa fa-google',
    'fields'      => array(
        array(
            'id'      => 'ad_home_s',
            'type'    => 'switcher',
            'title'   => __('首页顶部广告位','io_setting'),
            'default' => false,
        ),
        array(
            'type'    => 'submessage',
            'style'   => 'danger',
            'content' => '<b>注意：</b>需关掉‘big搜索’才能显示“首页顶部广告位”内容',
            'dependency' => array( 'search_big|ad_home_s', '==|==', 'true|true', 'all' )
        ),
        array(
            'id'      => 'ad_home_mobile',
            'type'    => 'switcher',
            'title'   => ' ┗━━ '. __('首页顶部广告位在移动端显示','io_setting'), 
            'default' => false,
            'dependency' => array( 'ad_home_s', '==', true )
        ),
        array(
            'id'      => 'ad_home_s2',
            'type'    => 'switcher',
            'title'   => ' ┗━━ '. __('首页顶部广告位2','io_setting'),
            'label'   => __('大屏并排显示2个广告位，小屏幕自动隐藏','io_setting'),
            'class'      => 'compact',
            'default' => false,
            'dependency' => array( 'ad_home_s', '==', true )
        ),
        array(
            'id'         => 'ad_home',
            'type'       => 'code_editor',
            'title'      => ' ┗━━ '. __('首页顶部广告位内容','io_setting'),
            'default'    => '<a href="https://www.iowen.cn/wordpress-version-webstack/" target="_blank"><img src="' . get_theme_file_uri('/images/ad.jpg').'" alt="广告也精彩" /></a>',
            'settings'   => array(
                'theme'  => 'dracula',
                'mode'   => 'javascript',
            ),
            'sanitize'   => false,
            'class'      => 'compact',
            'dependency' => array( 'ad_home_s', '==', true )
        ),
        array(
            'id'         => 'ad_home2',
            'type'       => 'code_editor',
            'title'      => ' ┗━━ '. __('首页顶部广告位2内容','io_setting'),
            'subtitle'   => __('首页顶部第二个广告位的内容','io_setting'),
            'default'    => '<a href="https://www.iowen.cn/wordpress-version-webstack/" target="_blank"><img src="' . get_theme_file_uri('/images/ad.jpg').'" alt="广告也精彩" /></a>',
            'settings'   => array(
                'theme'  => 'dracula',
                'mode'   => 'javascript',
            ),
            'sanitize'   => false,
            'class'      => 'compact',
            'dependency' => array( 'ad_home_s|ad_home_s2', '==|==', 'true|true' )
        ),


        array(
            'id'      => 'ad_home_s_second',
            'type'    => 'switcher',
            'title'   => __('首页网址块上方广告位','io_setting'),
            'label'   => __('网址块上方广告位','io_setting'),
            'default' => false,
        ),
        array(
            'id'      => 'ad_home_mobile_second',
            'type'    => 'switcher',
            'title'   => ' ┗━━ '. __('在移动端显示','io_setting'),
            'default' => false,
            'class'      => 'compact',
            'dependency' => array( 'ad_home_s_second', '==', true )
        ),
        array(
            'id'      => 'ad_home_s2_second',
            'type'    => 'switcher',
            'title'   => ' ┗━━ '. __('广告位2','io_setting'),
            'label'   => __('大屏并排显示2个广告位，小屏幕自动隐藏','io_setting'),
            'default' => false,
            'class'      => 'compact',
            'dependency' => array( 'ad_home_s_second', '==', true )
        ),
        array(
            'id'         => 'ad_home_second',
            'type'       => 'code_editor',
            'title'      => ' ┗━━ '. __('广告位内容','io_setting'),
            'default'    => '<a href="https://www.iowen.cn/wordpress-version-webstack/" target="_blank"><img src="' . get_theme_file_uri('/images/ad.jpg').'" alt="广告也精彩" /></a>',
            'settings'   => array(
                'theme'  => 'dracula',
                'mode'   => 'javascript',
            ),
            'sanitize'   => false,
            'class'      => 'compact',
            'dependency' => array( 'ad_home_s_second', '==', true )
        ),
        array(
            'id'         => 'ad_home2_second',
            'type'       => 'code_editor',
            'title'      => ' ┗━━ '. __('广告位2内容','io_setting'),
            'subtitle'   => __('第二个广告位的内容','io_setting'),
            'default'    => '<a href="https://www.iowen.cn/wordpress-version-webstack/" target="_blank"><img src="' . get_theme_file_uri('/images/ad.jpg').'" alt="广告也精彩" /></a>',
            'settings'   => array(
                'theme'  => 'dracula',
                'mode'   => 'javascript',
            ),
            'sanitize'   => false,
            'class'      => 'compact',
            'dependency' => array( 'ad_home_s_second|ad_home_s2_second', '==|==', 'true|true' )
        ),


        array(
            'id'      => 'ad_home_s_link',
            'type'    => 'switcher',
            'title'   => __('友链上方广告位','io_setting'),
            'label'   => __('首页底部友链上方广告位','io_setting'),
            'default' => false,
        ),
        array(
            'id'      => 'ad_home_mobile_link',
            'type'    => 'switcher',
            'title'   => ' ┗━━ '. __('在移动端显示','io_setting'),
            'default' => false,
            'class'      => 'compact',
            'dependency' => array( 'ad_home_s_link', '==', true )
        ),
        array(
            'id'      => 'ad_home_s2_link',
            'type'    => 'switcher',
            'title'   => ' ┗━━ '. __('友链上方广告位2','io_setting'),
            'label'   => __('大屏并排显示2个广告位，小屏幕自动隐藏','io_setting'),
            'default' => false,
            'class'      => 'compact',
            'dependency' => array( 'ad_home_s_link', '==', true )
        ),
        array(
            'id'         => 'ad_home_link',
            'type'       => 'code_editor',
            'title'      => ' ┗━━ '. __('友链上方广告位内容','io_setting'),
            'default'    => '<a href="https://www.iowen.cn/wordpress-version-webstack/" target="_blank"><img src="' . get_theme_file_uri('/images/ad.jpg').'" alt="广告也精彩" /></a>',
            'settings'   => array(
                'theme'  => 'dracula',
                'mode'   => 'javascript',
            ),
            'sanitize'   => false,
            'class'      => 'compact',
            'dependency' => array( 'ad_home_s_link', '==', true )
        ),
        array(
            'id'         => 'ad_home2_link',
            'type'       => 'code_editor',
            'title'      => ' ┗━━ '. __('友链上方广告位2内容','io_setting'),
            'subtitle'   => __('第二个广告位的内容','io_setting'),
            'default'    => '<a href="https://www.iowen.cn/wordpress-version-webstack/" target="_blank"><img src="' . get_theme_file_uri('/images/ad.jpg').'" alt="广告也精彩" /></a>',
            'settings'   => array(
                'theme'  => 'dracula',
                'mode'   => 'javascript',
            ),
            'sanitize'   => false,
            'class'      => 'compact',
            'dependency' => array( 'ad_home_s_link|ad_home_s2_link', '==|==', 'true|true' )
        ),



        array(
            'id'      => 'ad_footer_s',
            'type'    => 'switcher',
            'title'   => 'footer 广告位',
            'label'   => __('全站 footer 位广告','io_setting'),
            'default' => false,
        ),
        array(
            'id'         => 'ad_footer',
            'type'       => 'code_editor',
            'class'      => 'compact',
            'title'      => ' ┗━━ '. __('footer 广告位内容','io_setting'),
            'default'    => '<a href="https://www.iowen.cn/wordpress-version-webstack/" target="_blank"><img src="' . get_theme_file_uri('/images/ad.jpg').'" alt="广告也精彩" /></a>',
            'settings'   => array(
                'theme'  => 'dracula',
                'mode'   => 'javascript',
            ),
            'sanitize'   => false,
            'dependency' => array( 'ad_footer_s', '==', true )
        ),
    )
));
//
// 文章广告
//
CSF::createSection( $prefix, array(
    'parent'      => 'add-ad',
    'title'       => __('文章广告','io_setting'),
    'icon'        => 'fa fa-google',
    'fields'      => array(
        array(
            'id'      => 'ad_right_s',
            'type'    => 'switcher',
            'title'   => __('详情页右边广告位','io_setting'),
            'default' => false,
        ),
        array(
            'id'         => 'ad_right',
            'type'       => 'code_editor',
            'class'      => 'compact',
            'title'      => ' ┗━━ '. __('详情页右边广告位内容','io_setting'),
            'default'    => '<a href="https://www.iowen.cn/wordpress-version-webstack/" target="_blank"><img src="' . get_theme_file_uri('/screenshot.jpg').'" alt="广告也精彩" /></a>',
            'settings'   => array(
                'theme'  => 'dracula',
                'mode'   => 'javascript',
            ),
            'sanitize'   => false,
            'dependency' => array( 'ad_right_s', '==', true )
        ),
        array(
            'id'      => 'ad_app_s',
            'type'    => 'switcher',
            'title'   => __('网址、app正文上方广告位','io_setting'),
            'default' => true,
        ),
        array(
            'id'         => 'ad_app',
            'type'       => 'code_editor',
            'class'      => 'compact',
            'title'      => ' ┗━━ '. __('网址、下载、app详情页正文上方广告位内容','io_setting'),
            'default'    => '<a href="https://www.iowen.cn/wordpress-version-webstack/" target="_blank"><img src="' . get_theme_file_uri('/images/ad.jpg').'" alt="广告也精彩" /></a>',
            'settings'   => array(
                'theme'  => 'dracula',
                'mode'   => 'javascript',
            ),
            'sanitize'   => false,
            'dependency' => array( 'ad_app_s', '==', true )
        ),
        array(
            'id'         => 'ad_po',
            'type'       => 'code_editor',
            'title'      => __('文章内广告短代码','io_setting'),
            'default'    => '<a href="https://www.iowen.cn/wordpress-version-webstack/" target="_blank"><img src="' . get_theme_file_uri('/images/ad.jpg').'" alt="广告也精彩" /></a>',
            'settings'   => array(
                'theme'  => 'dracula',
                'mode'   => 'javascript',
            ),
            'sanitize'   => false,
            'subtitle'   => __('在文章中添加短代码 [ad] 即可调用','io_setting'),
        ),
        array(
            'id'         => 'ad_s_title',
            'title'      => __('正文标题广告位','io_setting'),
            'type'       => 'switcher'
        ),
        array(
            'id'         => 'ad_s_title_c',
            'title'      => ' ┗━━ '. __('输入正文标题广告代码','io_setting'),
            'default'    => '<a href="https://www.iowen.cn/wordpress-version-webstack/" target="_blank"><img src="' . get_theme_file_uri('/images/ad.jpg').'" alt="广告也精彩" /></a>',
            'type'       => 'code_editor',
            'settings'   => array(
                'theme'  => 'dracula',
                'mode'   => 'javascript',
            ),
            'sanitize'   => false,
            'class'      => 'compact',
            'dependency' => array( 'ad_s_title', '==', true )
        ),
        array(
            'id'         => 'ad_s_b',
            'title'      => __('正文底部广告位','io_setting'),
            'default'    => '0',
            'type'       => 'switcher'
        ),
        array(
            'id'         => 'ad_s_b_c',
            'title'      => ' ┗━━ '. __('输入正文底部广告代码','io_setting'),
            'desc'       => '',
            'default'    => '<a href="https://www.iowen.cn/wordpress-version-webstack/" target="_blank"><img src="' . get_theme_file_uri('/images/ad.jpg').'" alt="广告也精彩" /></a>',
            'type'       => 'code_editor',
            'settings'   => array(
                'theme'  => 'dracula',
                'mode'   => 'javascript',
            ),
            'sanitize'   => false,
            'class'      => 'compact',
            'dependency' => array( 'ad_s_b', '==', true )
        ),
        array(
            'id'         => 'ad_c',
            'title'      => __('评论上方广告位','io_setting'),
            'type'       => 'switcher'
        ),
        array(
            'id'         => 'ad_c_c',
            'title'      => ' ┗━━ '. __('输入评论上方广告代码','io_setting'),
            'default'    => '<a href="https://www.iowen.cn/wordpress-version-webstack/" target="_blank"><img src="' . get_theme_file_uri('/images/ad.jpg').'" alt="广告也精彩" /></a>',
            'type'       => 'code_editor',
            'settings'   => array(
                'theme'  => 'dracula',
                'mode'   => 'javascript',
            ),
            'sanitize'   => false,
            'class'      => 'compact',
            'dependency' => array( 'ad_c', '==', true )
        ),
        array(
            'id'         => 'ad_down_popup_s',
            'title'      => __('下载弹窗广告位','io_setting'),
            'type'       => 'switcher'
        ),
        array(
            'id'         => 'ad_down_popup',
            'title'      => ' ┗━━ '. __('下载弹窗广告代码','io_setting'),
            'default'    => '<a href="https://www.iowen.cn/wordpress-version-webstack/" target="_blank"><img src="' . get_theme_file_uri('/images/ad.jpg').'" alt="广告也精彩" /></a>',
            'type'       => 'code_editor',
            'settings'   => array(
                'theme'  => 'dracula',
                'mode'   => 'javascript',
            ),
            'sanitize'   => false,
            'class'      => 'compact',
            'dependency' => array( 'ad_down_popup_s', '==', true )
        ),
    )
));

//
// 优化设置
//
CSF::createSection( $prefix, array(
    'id'    => 'optimize',
    'title' => __('优化设置','io_setting'),
    'icon'  => 'fa fa-rocket',
));
  
//
// 禁用功能
//
CSF::createSection( $prefix, array(
    'parent'      => 'optimize',
    'title'       => __('禁用功能','io_setting'),
    'icon'        => 'fa fa-wordpress',
    'fields'      => array(

        array(
            'type'    => 'notice',
            'style'   => 'warning',
            'content' => '<li style="font-size:18px;color: red">'.__('如果不了解下面选项的作用，请保持原样！','i_theme').'</li>',
        ),
        array(
            'id'      => 'disable_rest_api',
            'type'    => 'switcher',
            'title'   => __('禁用REST API','io_setting'),
            'label'   => __('禁用REST API、移除wp-json链接（默认关闭，如果你的网站没有做小程序或是APP，建议禁用REST API）','io_setting'),
            'text_on' => '已禁用',
            'text_off'=> '未禁用',
            'text_width' => 80,
            'default' => false
        ),
 
        
        array(
            'id'      => 'diable_revision',
            'type'    => 'switcher',
            'title'   => __('禁用文章修订功能','io_setting'),
            'label'   => __('禁用文章修订功能，精简 Posts 表数据。(如果古滕堡报错，请关闭该选项)','io_setting'),
            'text_on' => '已禁用',
            'text_off'=> '未禁用',
            'text_width' => 80,
            'default' => false
        ),
 
        
        array(
            'id'      => 'disable_texturize',
            'type'    => 'switcher',
            'title'   => __('禁用字符转码','io_setting'),
            'label'   => __('禁用字符换成格式化的 HTML 实体功能。','io_setting'),
            'text_on' => '已禁用',
            'text_off'=> '未禁用',
            'text_width' => 80,
            'default' => true
        ),

        array(
            'id'      => 'disable_feed',
            'type'    => 'switcher',
            'title'   => __('禁用站点Feed','io_setting'),
            'label'   => __('禁用站点Feed，防止文章快速被采集。','io_setting'),
            'text_on' => '已禁用',
            'text_off'=> '未禁用',
            'text_width' => 80,
            'default' => true
        ),

        array(
            'id'      => 'disable_trackbacks',
            'type'    => 'switcher',
            'title'   => __('禁用Trackbacks','io_setting'),
            'label'   => __('Trackbacks协议被滥用，会给博客产生大量垃圾留言，建议彻底关闭Trackbacks。','io_setting'),
            'text_on' => '已禁用',
            'text_off'=> '未禁用',
            'text_width' => 80,
            'default' => true
        ),

        array(
            'id'      => 'disable_gutenberg',
            'type'    => 'switcher',
            'title'   => __('禁用古腾堡编辑器','io_setting'),
            'label'   => __('禁用Gutenberg编辑器，换回经典编辑器。','io_setting'),
            'text_on' => '已禁用',
            'text_off'=> '未禁用',
            'text_width' => 80,
            'default' => true
        ),

        array(
            'id'      => 'disable_xml_rpc',
            'type'    => 'switcher',
            'title'   => ' ┗━━ '.__('禁用XML-RPC','io_setting'),
            'label'   => __('XML-RPC协议用于客户端发布文章，如果你只是在后台发布，可以关闭XML-RPC功能。Gutenberg编辑器需要XML-RPC功能。','io_setting'),
            'text_on' => '已禁用',
            'text_off'=> '未禁用',
            'text_width' => 80,
            'default' => false,
            'class'      => 'compact',
            'dependency' => array( 'disable_gutenberg', '==', true )
        ),

        array(
            'id'      => 'disable_privacy',
            'type'    => 'switcher',
            'title'   => __('禁用后台隐私（GDPR）','io_setting'),
            'label'   => __('GDPR（General Data Protection Regulation）是欧洲的通用数据保护条例，WordPress为了适应该法律，在后台设置很多隐私功能，如果只是在国内运营博客，可以移除后台隐私相关的页面。','io_setting'),
            'text_on' => '已禁用',
            'text_off'=> '未禁用',
            'text_width' => 80,
            'default' => false
        ),
        array(
            'id'      => 'emoji_switcher',
            'type'    => 'switcher',
            'title'   => __('禁用emoji代码','io_setting'),
            'label'   => __('WordPress 为了兼容在一些比较老旧的浏览器能够显示 Emoji 表情图标，而准备的功能。','io_setting'),
            'text_on' => '已禁用',
            'text_off'=> '未禁用',
            'text_width' => 80,
            'default' => true
        ),
        array(
            'id'      => 'disable_autoembed',
            'type'    => 'switcher',
            'title'   => __('禁用Auto Embeds','io_setting'),
            'label'   => __('禁用 Auto Embeds 功能，加快页面解析速度。 Auto Embeds 支持的网站大部分都是国外的网站，建议禁用。','io_setting'),
            'text_on' => '已禁用',
            'text_off'=> '未禁用',
            'text_width' => 80,
            'default' => true
        ),
        array(
            'id'      => 'disable_post_embed',
            'type'    => 'switcher',
            'title'   => __('禁用文章Embed','io_setting'),
            'label'   => __('禁用可嵌入其他 WordPress 文章的Embed功能','io_setting'),
            'text_on' => '已禁用',
            'text_off'=> '未禁用',
            'text_width' => 80,
            'default' => false
        ),
        array(
            'id'      => 'remove_dns_prefetch',
            'type'    => 'switcher',
            'title'   => __('禁用s.w.org','io_setting'),
            'label'   => __('移除 WordPress 头部加载 DNS 预获取（s.w.org 国内根本无法访问）','io_setting'),
            'text_on' => '已禁用',
            'text_off'=> '未禁用',
            'text_width' => 80,
            'default' => false
        ),
    )
));

//
// 优化加速
//
CSF::createSection( $prefix, array(
    'parent'      => 'optimize',
    'title'       => __('优化加速','io_setting'),
    'icon'        => 'fa fa-envira',
    'fields'      => array(

        array(
            'type'    => 'notice',
            'style'   => 'warning',
            'content' => '<li style="font-size:18px;color: red">'.__('如果不了解下面选项的作用，请保持原样！','i_theme').'</li>',
        ),
        //array(
        //    'id'     => 'vpc_ip',
        //    'type'   => 'text',
        //    'title'  => __('解决WordPress 429','io_setting'),
        //    'after'  => '<br>'. __('如果需要，请填写 47.75.163.183:3128 感谢 wbolt 提供的服务器','io_setting'),
        //),
        array(
            'id'      => 'remove_head_links',
            'type'    => 'switcher',
            'title'   => __('移除头部代码','io_setting'),
            'label'   => __('WordPress会在页面的头部输出了一些link和meta标签代码，这些代码没什么作用，并且存在安全隐患，建议移除WordPress页面头部中无关紧要的代码。','io_setting'),
            'default' => true
        ),

        array(
            'id'      => 'remove_admin_bar',
            'type'    => 'switcher',
            'title'   => __('移除admin bar','io_setting'),
            'label'   => __('WordPress用户登陆的情况下会出现Admin Bar，此选项可以帮助你全局移除工具栏，所有人包括管理员都看不到。','io_setting'),
            'default' => true
        ),
        array(
            'id'      => 'ioc_category',
            'type'    => 'switcher',
            'title'   => __('去除分类标志','io_setting'),
            'label'   => __('去除链接中的分类category标志，有利于SEO优化，每次开启或关闭此功能，都需要重新保存一下固定链接！','io_setting'),
            'default' => true
        ),
        //array(
        //    'id'      => 'locale',
        //    'type'    => 'switcher',
        //    'title'   => __('前台不加载语言包','io_setting'),
        //    'label'   => __('前台不加载语言包，节省加载语言包所需的0.1-0.5秒。','io_setting'),
        //    'default' => false
        //),

        array(
            'id'      => 'gravatar',
            'type'    => 'select',
            'title'   => 'Gravatar加速',
            'default' => 'geekzu',
            'options' => array(
                'garav'    => __('使用Gravatar默认服务器','io_setting'),
                'v2ex'     => __('使用v2ex镜像加速服务','io_setting'),
                'chinayes' => __('使用wp-china-yes.cn镜像加速服务','io_setting'),
                'geekzu'   => __('使用极客族提供的Gravatar加速服务','io_setting'),
            ),
        ),
        array(
            'id'      => 'remove_help_tabs',
            'type'    => 'switcher',
            'title'   => __('移除帮助按钮','io_setting'),
            'label'   => __('移除后台界面右上角的帮助','io_setting'),
            'default' => false
        ),
        array(
            'id'      => 'remove_screen_options',
            'type'    => 'switcher',
            'title'   => __('移除选项按钮','io_setting'),
            'label'   => __('移除后台界面右上角的选项','io_setting'),
            'default' => false
        ),
        array(
            'id'      => 'no_admin',
            'type'    => 'switcher',
            'title'   => __('禁用 admin','io_setting'),
            'label'   => __('禁止使用 admin 用户名尝试登录 WordPress。','io_setting'),
            'default' => false
        ),
        array(
            'id'      => 'compress_html',
            'type'    => 'switcher',
            'title'   => __('压缩 html 源码','io_setting'),
            'label'   => __('压缩网站源码，提高加载速度。（如果启用发现网站布局错误，请禁用。）','io_setting'),
            'default' => false
        ),
    )
));
 
//
// 用户&安全
//
CSF::createSection( $prefix, array(
    'id'    => 'user_security',
    'title' => __('用户&安全','io_setting'),
    'icon'  => 'fa fa-street-view',
));
//
// 用户注册
//
CSF::createSection( $prefix, array(
    'parent'      => 'user_security',
    'title'       => '用户注册',
    'icon'        => 'fa fa-user-plus',
    'fields'      => array(
        array(
            'content' => '本页某些设置依赖一下功能：<ol><li>启用用户中心</li><li>社交登录</li><li>邮件发信设置</li></ol><i class="fa fa-fw fa-info-circle fa-fw"></i> 开启前请检查相关设置是否配置正确。',
            'style' => 'info',
            'type' => 'submessage',
        ),
        array(
            'id'      => 'reg_verification',
            'type'    => 'switcher',
            'title'   => __('注册时验证邮箱','io_setting'),
            'label'   => __('发送邮箱验证码，请先在“其他功能”中配置好邮件发信服务。','io_setting'),
            'default' => false
        ),
        array(
            'id'           => 'bind_email',
            'type'         => 'button_set',
            'title'        => '绑定邮箱',
            'options'      => array(
                'null'  => __('不绑定','io_setting'),
                'bind'  => __('绑定邮箱','io_setting'),
                'must'  => __('强制绑定','io_setting'),
            ),
            'class'     => 'new',
            'default'   => 'null',
            'after'     => '<br><p>社交登录后是否提示绑定邮箱。</p><ol><li>不绑定：就是不绑定。</li><li>绑定邮箱：提示绑定，并跳转到绑定页。</li><li>强制绑定：用户第一次使用社交登录后并未完成注册，需添加邮箱、密码等操作后才能真正完成注册，同时也可以绑定现有账号（比如用户以前用邮箱注册了账号，就可以通过登录以前的账号自动关联社交账户）。</li></ol>
            <p><i class="fa fa-fw fa-info-circle fa-fw"></i> 如果选择“强制绑定”，用户没有完成绑定前不会插入用户表，同时实现绑定已有账号。</p>
            <i class="fa fa-fw fa-info-circle fa-fw"></i> 此功能需邮箱验证码，请提前配置好邮件发信设置。'
        ),
        array(
            'id'      => 'remind_bind',
            'type'    => 'switcher',
            'title'   => __('提醒绑定邮箱','io_setting'),
            'label'   => __('未绑定邮箱的用户，每次登录都提醒绑定邮箱','io_setting'),
            'class'     => 'new',
            'default' => false
        ),
        array(
            'id'      => 'remind_only',
            'type'    => 'switcher',
            'title'   => __('提醒1次','io_setting'),
            'label'   => __('一天只提醒一次（同一个会话周期）','io_setting'),
            'default' => true,
            'class'   => 'compact',
            'dependency' => array( 'remind_bind', '==', 'true' ),
        ),
        array(
            'id'        => 'user_nickname_stint',
            'type'      => 'textarea',
            'title'     => __('用户昵称限制', 'io_setting'),
            'subtitle'  => __('禁止的昵称关键词', 'io_setting'),
            'desc'      => __('前台注册或修改昵称时，不能使用包含这些关键字的昵称(请用逗号或换行分割)', 'io_setting'),
            'default'   => "赌博,博彩,彩票,性爱,色情,做爱,爱爱,淫秽,傻b,妈的,妈b,admin,test",
            'sanitize'  => false,
        ),
        array(
            'id'        => 'nickname_exists',
            'type'      => 'switcher',
            'title'     => '昵称唯一',
            'label'     => '禁止昵称重复，不允许修改为已存在的昵称',
            'default'   => true,
        ), 
    )
));

//
// 社交登录
//
CSF::createSection( $prefix, array(
    'parent' => 'user_security',
    'title'  => __('社交登录','io_setting'),
    'icon'   => 'fa fa-share-alt-square',
    'fields' => array(
        array(
            'id'         => 'open_qq',
            'type'       => 'switcher',
            'title'      => __('qq登录','io_setting'),
            'label'   => '回调地址：'.get_template_directory_uri().'/inc/auth/qq-callback.php',
            'subtitle'      => '接口申请：https://connect.qq.com', 
        ),
        array(
            'id'        => 'open_qq_key',
            'type'      => 'fieldset',
            'title'     => __('参数设置','io_setting'),
            'fields'    => array(
                array(
                    'content' => '<h4><b>回调地址：</b>' . esc_url(get_template_directory_uri().'/inc/auth/qq-callback.php') . '</h4>QQ登录申请地址：<a target="_blank" href="https://connect.qq.com/">https://connect.qq.com</a>',
                    'style' => 'info',
                    'type' => 'submessage',
                ),
                array(
                    'id'    => 'appid',
                    'type'  => 'text', 
                    'title' => 'APPID',
                ),    
                array(
                    'id'       => 'appkey',
                    'type'     => 'text',
                    'title'    => 'APPKEY',
                ), 
            ),
            'class'      => 'compact',
            'dependency'   => array( 'open_qq', '==', 'true' ),
        ),
        array(
            'id'         => 'open_weibo',
            'type'       => 'switcher',
            'title'      => __('微博登录','io_setting'),
            'label'      => '回调地址：'.get_template_directory_uri().'/inc/auth/sina-callback.php',
            'subtitle'      => '接口申请：http://open.weibo.com', 
        ),
        array(
            'id'        => 'open_weibo_key',
            'type'      => 'fieldset',
            'title'     => __('参数设置','io_setting'),
            'fields'    => array(
                array(
                    'content' => '<h4><b>回调地址：</b>' . esc_url(get_template_directory_uri().'/inc/auth/sina-callback.php') . '</h4>微博登录申请地址：<a target="_blank" href="https://open.weibo.com/authentication/">https://open.weibo.com/authentication</a>',
                    'style' => 'info',
                    'type' => 'submessage',
                ),
                array(
                    'id'    => 'appid',
                    'type'  => 'text', 
                    'title' => 'APPID',
                ),    
                array(
                    'id'       => 'appkey',
                    'type'     => 'text',
                    'title'    => 'APPSECRET',
                ), 
            ),
            'class'      => 'compact',
            'dependency'   => array( 'open_weibo', '==', 'true' ),
        ),
        array(
            'type'    => 'submessage',
            'style'   => 'danger',
            'content' => '<div style="text-align:center"><b><i class="fa fa-fw fa-ban fa-fw"></i> 微信三种方式请三选一即可，不互通。</b></div>',
        ),
        array(
            'id'        => 'open_weixin_gzh',
            'type'      => 'switcher',
            'title'     => __('微信登录(公众号模式)', 'io_setting'),
            'subtitle'  => '接口申请：https://mp.weixin.qq.com/',  
            'class'   => 'new',
        ),
        array(
            'title'     => '微信公众号登录配置',
            'id'        => 'open_weixin_gzh_key',
            'type'      => 'fieldset',
            'class'     => 'compact',
            'fields'    => array(
                array(
                    'content' => '<h4><b>服务器接口URL：</b>' . esc_url(get_template_directory_uri().'/inc/auth/gzh-callback.php') . '</h4>
                    <h4><b>JS接口安全域名：</b>' . preg_replace('/^(?:https?:\/\/)?([^\/]+).*$/im', '$1', home_url()) . '</h4>
                    申请地址：<a target="_blank" href="https://mp.weixin.qq.com/">https://mp.weixin.qq.com/</a>
                    </br>教程：<a target="_blank" href="https://www.iotheme.cn/yiweizhutidisanfangdenglu-wangzhanjieruweixingongzhonghaodenglutuwenjiaocheng.html">查看设置教程</a>
                    </br><i class="fa fa-fw fa-info-circle fa-fw"></i> 微信登录请三选一开启',
                    'style' => 'info',
                    'type' => 'submessage',
                ),
                array(
                    'id' => 'appid',
                    'type' => 'text',
                    'title' => '公众号AppID',
                ),
                array(
                    'id' => 'appkey',
                    'type' => 'text',
                    'title' => '公众号AppSecret',
                    'class' => 'compact',
                ),
                array(
                    'id' => 'token',
                    'type' => 'text',
                    'title' => '接口验证token',
                    'desc' => '此处token用于在微信平台校验服务器URL时使用，自行填写，和微信平台一致即可。 <a target="_blank" href="https://developers.weixin.qq.com/doc/offiaccount/Basic_Information/Access_Overview.html">查看说明</a>',
                ),
                array(
                    'id' => 'subscribe_msg',
                    'type' => 'textarea',
                    'title' => '新关注消息',
                    'desc' => '用户首次扫码关注后自动回复的消息',
                    'class' => 'compact',
                    'default' => '感谢您的关注' .PHP_EOL. home_url(),
                    'attributes' => array(
                        'rows' => 2
                    ),
                    'sanitize' => false,
                ),
                array(
                    'id' => 'scan_msg',
                    'type' => 'textarea',
                    'title' => '扫码登录消息',
                    'desc' => '已经关注的用户扫码登录时候自动回复的消息',
                    'class' => 'compact',
                    'default' => '登录成功' .PHP_EOL. home_url(),
                    'attributes' => array(
                        'rows' => 2
                    ),
                    'sanitize' => false,
                ),
            ),
            'class'      => 'compact',
            'dependency' => array('open_weixin_gzh', '==', 'true'),
        ), 
        
        array(
            'id'        => 'open_weixin_dyh',
            'type'      => 'switcher',
            'title'     => __('微信登录(公众号模式[未认证号])', 'io_setting'),
            'subtitle'  => '接口申请：https://mp.weixin.qq.com/',  
            'class'   => 'new',
        ),
        array(
            'id'        => 'open_weixin_dyh_key',
            'type'      => 'fieldset',
            'title'     => __('参数设置','io_setting'),
            'fields'    => array(
                array(
                    'content' => '<h4><b>服务器接口URL：</b>' . esc_url(get_template_directory_uri().'/inc/auth/dyh-callback.php') . '</h4>
                    <h4><b>JS接口安全域名：</b>' . preg_replace('/^(?:https?:\/\/)?([^\/]+).*$/im', '$1', home_url()) . '</h4>
                    申请地址：<a target="_blank" href="https://mp.weixin.qq.com/">https://mp.weixin.qq.com/</a>
                    </br>参考微信登录(公众号模式)的教程：<a target="_blank" href="https://www.iotheme.cn/yiweizhutidisanfangdenglu-wangzhanjieruweixingongzhonghaodenglutuwenjiaocheng.html">查看设置教程</a>
                    </br><i class="fa fa-fw fa-info-circle fa-fw"></i> 微信登录请三选一开启',
                    'style' => 'info',
                    'type' => 'submessage',
                ),
                array(
                    'id' => 'appid',
                    'type' => 'text',
                    'title' => '公众号AppID',
                ),
                array(
                    'id' => 'appkey',
                    'type' => 'text',
                    'title' => '公众号AppSecret',
                    'class' => 'compact',
                ),
                array(
                    'id' => 'token',
                    'type' => 'text',
                    'title' => '接口验证token',
                    'desc' => '此处token用于在微信平台校验服务器URL时使用，自行填写，和微信平台一致即可。 <a target="_blank" href="https://developers.weixin.qq.com/doc/offiaccount/Basic_Information/Access_Overview.html">查看说明</a>',
                ),    
                array(
                    'id'        => 'qr_code',
                    'type'      => 'upload',
                    'title'     => '公众号二维码',
                ), 
            ),
            'class'      => 'compact',
            'dependency'   => array( 'open_weixin_dyh', '==', 'true' ),
        ),
        array(
            'id'         => 'open_wechat',
            'type'       => 'switcher',
            'title'      => __('微信登录(开放平台模式)','io_setting'),
            'subtitle'      => '接口申请：https://open.weixin.qq.com', 
        ),
        array(
            'id'        => 'open_wechat_key',
            'type'      => 'fieldset',
            'title'     => __('参数设置','io_setting'),
            'fields'    => array(
                array(
                    'content' => '<h4><b>开放平台回调地址：</b>' . esc_url(get_template_directory_uri().'/inc/auth/wechat-callback.php') . '</h4>
                    微信登录申请地址：<a target="_blank" href="https://open.weixin.qq.com/">https://open.weixin.qq.com</a>
                    </br><i class="fa fa-fw fa-info-circle fa-fw"></i> 微信登录请三选一开启',
                    'style' => 'info',
                    'type' => 'submessage',
                ),
                array(
                    'id'    => 'appid',
                    'type'  => 'text', 
                    'title' => 'APPID',
                ),    
                array(
                    'id'       => 'appkey',
                    'type'     => 'text',
                    'title'    => 'APPSECRET',
                ), 
            ),
            'class'      => 'compact',
            'dependency'   => array( 'open_wechat', '==', 'true' ),
        ),
        array(
            'id'         => 'open_login_url',  
            'type'       => 'text',
            'title'      => __('登录后返回地址', 'io_setting'),
            'desc'       => '登录后返回的地址，一般是首页或者个人中心页',
            'default'    => esc_url(home_url()),
        ),
    )
));

//
// 安全相关
//
CSF::createSection( $prefix, array(
    'parent'      => 'user_security',
    'title'       => '安全相关',
    'icon'        => 'fa fa-shield',
    'fields'      => array(
        array(
            'id'        => 'io_administrator',
            'type'      => 'fieldset',
            'title'     => '禁止冒充管理员留言',
            'fields'    => array(
                array(
                    'id'         => 'admin_name',
                    'type'       => 'text',
                    'title'      => __('管理员名称','io_setting'),
                ),
                array(
                    'id'         => 'admin_email',
                    'type'       => 'text',
                    'title'      => __('管理员邮箱','io_setting'),
                ),
            ),
            'default'  => array(
                'admin_email'    => get_option( 'admin_email' ),
            ),
        ),
        array(
            'id'        => 'io_comment_set',
            'type'      => 'fieldset',
            'title'     => '评论过滤',
            'fields'    => array(
                array(
                    'id'         => 'no_url',
                    'type'       => 'switcher',
                    'title'      => __('评论禁止链接','io_setting'),
                ),
                array(
                    'id'         => 'no_chinese',
                    'type'       => 'switcher',
                    'title'      => __('评论必须包含汉字','io_setting'),
                ),
            ),
            'default'  => array(
                'no_url'        => true,
                'no_chinese'    => false,
            ),
        ),
        array(
            'id'      => 'bookmark_share',
            'type'    => 'switcher',
            'title'   => __('禁用“用户个人书签页”分享','io_setting'),
            'label'   => __('全局开关，避免非法地址影响域名安全','io_setting'),
            'class'   => 'new',
            'default' => true,
        ),
        array(
            'id'        => 'io_captcha',
            'type'      => 'fieldset',
            'title'     => '腾讯防水墙',
            'subtitle'  => '<span style="color:#f00">开启后，请认真填写，填错会造成无法登陆后台</span>',
            'before'    => '如果开启的防水墙后进不了后台，请将主题文件‘functions.php’里“LOGIN_007”的 true 改为 false 。',
            'fields'    => array(
                array(
                    'id'        => 'tcaptcha_007',
                    'type'      => 'switcher',
                    'title'     => __('启用腾讯防水墙','io_setting'),
                    'desc'      => __('在登录页、投稿、评论等低添加验证，提升安全性','io_setting'),
                ),    
                array(
                    'id'        => 'appid_007',
                    'type'      => 'text',
                    'title'     => __('腾讯防水墙 App ID','io_setting'),
                    'dependency'=> array( 'tcaptcha_007', '==', 'true' ),
                ),    
                array(
                    'id'        => 'appsecretkey_007',
                    'type'      => 'text',
                    'title'     => __('腾讯防水墙 App Secret Key','io_setting'),
                    'after'     => __('请填写完整，包括后面的**','io_setting'),
                    'dependency'=> array( 'tcaptcha_007', '==', 'true' ),
                ),    
                array(
                    'type'    => 'subheading',
                    'content' => __('App ID 申请地址：','io_setting').'<a href="https://cloud.tencent.com/login?s_url=https%3A%2F%2Fconsole.cloud.tencent.com%2Fcaptcha" target="_blank">防水墙</a>',
                    'dependency'=> array( 'tcaptcha_007', '==', 'true' ),
                ),
                array(
                    'id'        => 'comment_007',
                    'type'      => 'switcher',
                    'title'     => __('评论开启验证','io_setting'),
                    'dependency'=> array( 'tcaptcha_007', '==', 'true' ),
                ),
            ),
            'default'  => array(
                'tcaptcha_007'   => false, 
            ),
        ), 
    )
));

//
// 今日热点
//
CSF::createSection( $prefix, array(
    'title'        => __('今日热点','io_setting'),
    'icon'         => 'fab fa-hotjar new',
    'fields'       => array(
        array(
            'content' => '<h4><b>此选项卡内容留空不影响主题使用，如需要以下服务必须填。</b></h4>1、热搜榜、新闻源等卡片数据获取</br>
            </br>教程：<a href="https://www.iotheme.cn/io-api-user-manual.html"  target="_blank">api 使用手册</a>
            <br><i class="fa fa-fw fa-info-circle fa-fw"></i> 一为热榜 api 服务集成，此服务不影响主题的任何功能
            <br><i class="fa fa-fw fa-info-circle fa-fw"></i> 注意：JSON和RSS为免费服务',
            'style' => 'info',
            'type' => 'submessage',
        ),
        array(
            'id'      => 'iowen_key',
            'type'    => 'text',
            'title'   => __('一为 API 在线服务激活码','io_setting'),
            'after'   => 'iowen 在线服务为订阅服务，购买主题免费赠送一年，请先使用订单激活码<a href="//www.iotheme.cn/user?try=reg" target="_blank" title="注册域名">注册域名</a>。 如果没有购买或者过期，请访问<a href="//www.iotheme.cn/store/iowenapi.html" target="_blank" title="购买服务">iTheme</a>购买。',
        ),
        array(
            'id'        => 'hot_new',
            'type'      => 'group',
            'title'     => '新闻热搜',
            'fields'    => array(
                array(
                    'id'        => 'name',
                    'type'      => 'text',
                    'title'     => '名称',
                ),
                array(
                    'id'           => 'hot_type',
                    'type'         => 'button_set',
                    'title'        => __('类型','io_setting'),
                    'options'      => array(
                        'json'  => 'JSON',
                        'rss'   => 'RSS',
                        'api'   => 'API（需 KEY）',
                    ),
                    'default'      => 'api',
                ),
                array(
                    'id'        => 'description',
                    'type'      => 'text',
                    'title'     => '描述',
                    'class'     => 'disabled',
                    'subtitle'  => '只读',
                    'dependency' => array( 'hot_type', '==', 'api' )
                ),
                array(
                    'type'    => 'submessage',
                    'style'   => 'success',
                    'content' => '<h4>前往“<a href="'.esc_url(add_query_arg('page', 'hot_search_settings', admin_url('options-general.php'))).'">自定义热榜</a>”设置配置自定义热榜</h4>下方ID为对应规则的序号，如1，6，8',
                    'dependency' => array( 'hot_type', '!=', 'api')
                ),
                array(
                    'id'        => 'rule_id',
                    'type'      => 'text', 
                    'title'     => '热榜ID', 
                    'after'     => '如果选择 JSON 或者 RSS ，此项填“自定义热榜”对应类型的序号，如 JSON 类型的第一个，则填 1',
                    'dependency' => array( 'hot_type', '!=', 'api', '', 'visible' )
                ),
                array(
                    'id'      => 'is_iframe',
                    'type'    => 'checkbox',
                    'title'   => 'iframe 加载',
                    'label'   => '在页面内以 iframe 加载，如果目标站不支持，请关闭',
                    'default' => false,
                ),
                array(
                    'id'      => 'ico',
                    'type'    => 'upload',
                    'title'   => __('LOGO，标志','io_setting'),
                    'library' => 'image',
                    'after'   => __('建议 30x30 ，留空则不显示。','io_setting'),
                    'default' => get_theme_file_uri('/images/hot_ico.png'),
                ),
                array(
                    'type'    => 'content',
                    'content' => '<div style="text-align:center;"><a id="hot-option" href="javascript:" class="button button-primary" data-id="hot_new" data-user="'.io_get_option('iowen_key').'" style="padding:8px 80px"> 配 置 </a></div>',
                    'dependency' => array( 
                        array( 'rule_id', '==', '' ),
                        array( 'hot_type', '==', 'api' )
                    )
                ),
                array(
                    'type'    => 'content',
                    'content' => '<div style="text-align:center;"><a id="hot-modify" href="javascript:" class="button button-primary" data-id="hot_new" data-user="'.io_get_option('iowen_key').'" style="padding:8px 80px"> 修 改 </a></div>',
                    'dependency' => array( 
                        array( 'rule_id', '!=', '' ),
                        array( 'hot_type', '==', 'api' )
                    ),
                ),
            ),
            'max'     => 6,
        ),
        array(
            'id'         => 'hot_iframe',
            'type'       => 'switcher',
            'title'      => __('热点 iframe 加载总开关','io_setting'),
            'label'      => __('如果开启了此选项链接还是在新窗口打开，说明对方不支持 iframe 嵌套','io_setting'),
            'default'    => false,
        ),
        array(
            'id'        => 'hot_home_list',
            'type'      => 'group',
            'title'     => '今日热榜页列表',
            'fields'    => array(
                array(
                    'id'        => 'name',
                    'type'      => 'text',
                    'title'     => '名称',
                ),
                array(
                    'id'           => 'hot_type',
                    'type'         => 'button_set',
                    'title'        => __('类型','io_setting'),
                    'options'      => array(
                        'json'  => 'JSON',
                        'rss'   => 'RSS',
                        'api'   => 'API（需 KEY）',
                    ),
                    'default'      => 'api',
                ),
                array(
                    'type'    => 'submessage',
                    'style'   => 'success',
                    'content' => '<h4>前往“<a href="'.esc_url(add_query_arg('page', 'hot_search_settings', admin_url('options-general.php'))).'">自定义热榜</a>”设置配置自定义热榜</h4>下方ID为对应规则的序号，如1，6，8',
                    'dependency' => array( 'hot_type', '!=', 'api')
                ),
                array(
                    'id'        => 'rule_id',
                    'type'      => 'text',
                    'title'     => '热榜ID',
                    'after'     =>'如果选择 JSON 或者 RSS ，此项填“自定义热榜”对应类型的序号，如 JSON 类型的第一个，则填 1<br>如果选择 API ，请前往“ID列表”查看ID<br>
                    <i class="fa fa-fw fa-info-circle fa-fw"></i> ID列表：<a target="_blank" href="https://www.ionews.top/list.html">查看</a>'
                ),
                array(
                    'id'        => 'ico',
                    'type'      => 'upload',
                    'title'     => 'ico',
                    'add_title' => __('上传','io_setting'),
                    'after'     => '<p class="cs-text-muted">'.__('建议30px30','io_setting'),
                    'default'   => get_theme_file_uri('/images/hot_ico.png'),
                ),
                array(
                    'id'      => 'is_iframe',
                    'type'    => 'switcher',
                    'title'   => 'iframe 加载',
                    'label'   => '在页面内以 iframe 加载，如果目标站不支持，请关闭',
                    'default' => false
                ),
            ),
            'before'  => '<h4>今日热榜页显示的新闻源</h4>最多添加30个<br><i class="fa fa-fw fa-info-circle fa-fw"></i> ID必填，ID列表：<a target="_blank" href="https://www.ionews.top/list.html">查看</a>',
            'default' => all_topnew_list(),
            'accordion_title_number' => true,
            'max'     => 30,
        ),
    )
));

//
// 备份
//
CSF::createSection( $prefix, array(
    'title'       => __('备份设置','io_setting'),
    'icon'        => 'fa fa-undo',
    'fields'      => array( 

        array(
            'type'     => 'callback',
            'class'  => 'csf-field-submessage',
            'function' => 'io_backup',
        ),
        // 备份
        array(
            'type' => 'backup',
        ),
    )
));

