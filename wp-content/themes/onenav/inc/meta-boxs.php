<?php
/*!
 * Theme Name:One Nav
 * Theme URI:https://www.iotheme.cn/
 * Author:iowen
 * Author URI:https://www.iowen.cn/
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
//文章SEO
if( class_exists( 'CSF' ) ) {
    $post_options = 'post-seo_post_meta';
    CSF::createMetabox($post_options, array(
        'title'     => 'SEO',
        'post_type' => 'post',
        'data_type' => 'unserialize',
        'theme'     => 'light',
        'context'   => 'side',
        'priority'  => 'default',
    ));
    CSF::createSection( $post_options, array(
        'fields' => array(
            array(
                'id'    => '_seo_title',
                'type'  => 'text',
                'title' => __('自定义标题','io_setting'),
                'desc' => 'Title 一般建议15到30个字符',
                'after' => __('留空则获取文章标题','io_setting'),
                'attributes' => array(
                    'style'    => 'width: 100%'
                ),
            ),
            array(
                'id'    => '_seo_metakey',
                'type'  => 'text',
                'title' => __('自定义关键词','io_setting'),
                'desc' => 'Keywords 每个关键词用英语逗号隔开',
                'after' => __('留空则获取文章标签','io_setting'),
                'attributes' => array(
                    'style'    => 'width: 100%'
                ),
            ),
            array(
                'id'    => '_seo_desc',
                'type'  => 'textarea',
                'title' => __('自定义描述','io_setting'),
                'desc' => 'Description 一般建议50到150个字符',
                'after' => __('留空则获取文章简介或摘要','io_setting'),
                'attributes' => array(
                    'style'    => 'width: 100%'
                ),
            ),
        )
    ));
}

//页面SEO
if( class_exists( 'CSF' ) ) {
    $page_options = 'page-seo_post_meta';
    CSF::createMetabox($page_options, array(
        'title'     => 'SEO',
        'post_type' => 'page',
        'data_type' => 'unserialize',
        'theme'     => 'light',
        'context'   => 'side',
        'priority'  => 'default',
    ));
    CSF::createSection( $page_options, array(
        'fields' => array(
            array(
                'id'    => '_seo_title',
                'type'  => 'text',
                'title' => __('自定义标题','io_setting'),
                'desc' => 'Title 一般建议15到30个字符',
                'after' => __('留空则获取页面标题','io_setting'),
                'attributes' => array(
                    'style'    => 'width: 100%'
                ),
            ),
            array(
                'id'    => '_seo_metakey',
                'type'  => 'text',
                'title' => __('自定义关键词','io_setting'),
                'desc' => 'Keywords 每个关键词用英语逗号隔开',
                'attributes' => array(
                    'style'    => 'width: 100%'
                ),
            ),
            array(
                'id'    => '_seo_desc',
                'type'  => 'textarea',
                'title' => __('自定义描述','io_setting'),
                'desc' => 'Description 一般建议50到150个字符',
                'attributes' => array(
                    'style'    => 'width: 100%'
                ),
            ),
        )
    ));
}

$sortable = '';
if(io_get_option('sites_sortable')){
    $sortable = 'disabled';
}
// 网站
if( class_exists( 'CSF' ) ) {
    $site_options = 'sites_post_meta';
    CSF::createMetabox($site_options, array(
        'title'     => __('站点信息','io_setting'),
        'post_type' => 'sites',
        'data_type' => 'unserialize',
        'theme'     => 'light',
        'priority'  => 'high',
    ));
    CSF::createSection($site_options, array(
        'fields'    => array(
            array(
                'id'           => '_sites_type',
                'type'         => 'button_set',
                'title'        => __('类型','io_setting'),
                'options'      => array(
                    'sites'  => __('网站','io_setting'),
                    'wechat' => __('公众号/小程序','io_setting'),
                    'down'   => __('下载资源','io_setting'),
                ),
                'default'      => 'sites',
            ),
            array(
                'type'		 => 'submessage',
                'style'		=> 'danger',
                'content'	  => __('下载资源已不再支持，请使用“APP/资源”添加内容，已经存在的内容不受影响。','io_setting'), 
                'dependency' => array( '_sites_type', '==', 'down' ), 
            ),
            array(
                'id'      => '_goto',
                'type'    => 'switcher',
                'title'   => __('直接跳转','io_setting'),
                'label'   => '不添加 go 跳转和 nofollow',
                'default' => false,
                'dependency' => array( '_sites_type', '==', 'sites' ),
            ),
            array(
                'id'      => '_wechat_id',
                'type'    => 'text',
                'title'   => __('微信号','io_setting'),
                'dependency' => array( '_sites_type', '==', 'wechat' ),
            ),
            array(
                'id'      => '_is_min_app',
                'type'    => 'switcher',
                'title'   => __('小程序','io_setting'),
                'default' => false,
                'dependency' => array( '_sites_type', '==', 'wechat' ),
            ),
            array(
                'id'      => '_sites_link',
                'type'    => 'text',
                'class'   => 'sites_link',
                'title'   => __('链接','io_setting'),
                'desc'    => __('需要带上http://或者https://','io_setting'),
                'dependency' => array( '_sites_type', '!=', 'down' ),
            ),
            array(
                'id'      => '_spare_sites_link',
                'type'    => 'group',
                'title'   => __('备用链接地址（其他站点）','io_setting'),
                'subtitle'=> __('如果有多个链接地址，请在这里添加。','io_setting'),
                'fields'  => array(
                    array(
                        'id'    => 'spare_name',
                        'type'  => 'text',
                        'title' => __('站点名称','io_setting'),
                    ),
                    array(
                        'id'    => 'spare_url',
                        'type'  => 'text',
                        'title' => __('站点链接','io_setting'),
                        'desc'  => __('需要带上http://或者https://','io_setting'),
                    ),
                    array(
                        'id'    => 'spare_note',
                        'type'  => 'text',
                        'title' => __('备注','io_setting'),
                    ),
                ),
                'dependency' => array( '_sites_type', '==', 'sites' ),
            ),
            array(
                'id'      => '_sites_sescribe',
                'type'    => 'text',
                'class'   => 'sites_sescribe',
                'title'   => __('一句话描述（简介）','io_setting'),
                'after'   => '<br>'.__('推荐不要超过150个字符，详细介绍加正文。','io_setting'),
                'attributes' => array(
                'style'    => 'width: 100%'
                ),
            ),
            array(
                'id'      => '_sites_language',
                'type'    => 'text',
                'title'   => __('站点语言','io_setting'),
                'after'   => '<br>'.__('站点支持的语言，多个用英语逗号分隔，请使用缩写，如：zh,en ，','io_setting').'<a href="https://zh.wikipedia.org/wiki/ISO_639-1" target="_blank">各国语言缩写参考</a>',
                'dependency' => array( '_sites_type', '==', 'sites' ),
            ),
            array(
                'id'      => '_sites_country',
                'type'    => 'text',
                'class'   => 'sites_country',
                'title'   => __('站点所在国家或地区','io_setting'),
                'dependency' => array( '_sites_type', '==', 'sites' ),
            ),
            array(
                'id'      => '_sites_order',
                'type'    => 'text',
                'title'   => __('排序','io_setting'),
                'desc'    => $sortable==''?__('网址排序数值越大越靠前','io_setting'):'您已经启用拖动排序，请前往列表拖动内容排序',
                'default' => '0',
                'class'   => $sortable,
            ),
            array(
                'id'      => '_thumbnail',
                'type'    => 'upload',
                'title'   => __('LOGO，标志','io_setting'),
                'library' => 'image',
                'class'   => 'sites-ico',
                'before'  =>  '获取图标可以自动下载目标图标到本地。 <span class="sites-ico-msg" style="display:none;color:#dc1e1e;"></span>',
                'desc'    => __('使用自定义图标','io_setting'),
                //'url'     => false,
            ),
            array(
                'id'      => '_wechat_qr',
                'type'    => 'upload',
                'title'   => __('公众号二维码','io_setting'),
                //'url'     => false,
                'dependency' => array( '_sites_type', '!=', 'down' ),
            ),
            array(
                'id'      => '_down_version',
                'type'    => 'text',
                'title'   => __('资源版本','io_setting'),
                'dependency' => array( '_sites_type', '==', 'down' ),
            ),
            array(
                'id'      => '_down_size',
                'type'    => 'text',
                'title'   => __('资源大小','io_setting'),
                'after'   => __('填写单位：KB,MB,GB,TB' ,'io_setting'),
                'dependency' => array( '_sites_type', '==', 'down' ),
            ),
            array(
                'id'     => '_down_url_list',
                'type'   => 'group',
                'title'  => __('下载地址列表','io_setting'),
                'before' => __('添加下载地址，提取码等信息','io_setting'),
                'fields' => array(
                    array(
                        'id'    => 'down_btn_name',
                        'type'  => 'text',
                        'title' => __('按钮名称','io_setting'),
                        'default' => __('百度网盘','io_setting'),
                    ),
                    array(
                        'id'    => 'down_btn_url',
                        'type'  => 'text',
                        'title' => __('下载地址','io_setting'),
                    ),
                    array(
                        'id'    => 'down_btn_tqm',
                        'type'  => 'text',
                        'title' => __('提取码','io_setting'),
                    ),
                    array(
                        'id'    => 'down_btn_info',
                        'type'  => 'text',
                        'title' => __('描述','io_setting'),
                    ),
                ), 
                'dependency' => array( '_sites_type', '==', 'down' ),
            ),
            array(
                'id'      => '_dec_password',
                'type'    => 'text',
                'title'   => __('解压密码','io_setting'),
                'dependency' => array( '_sites_type', '==', 'down' ),
            ),
            array(
                'id'      => '_app_platform',
                'type'    => 'checkbox',
                'title'   => __('支持平台','io_setting'),
                'inline'  => true,
                'options' => array(
                    'icon-microsoft'        => 'PC',
                    'icon-mac'              => 'MAC OS',
                    'icon-linux'            => 'linux',
                    'icon-android'          => __('安卓','io_setting'),
                    'icon-app-store-fill'   => 'ios',
                ),
                'dependency' => array( '_sites_type', '==', 'down' ),
            ),
            array(
                'id'      => '_down_preview',
                'type'    => 'text',
                'title'   => __('演示地址','io_setting'),
                'dependency' => array( '_sites_type', '==', 'down' ),
            ),
            array(
                'id'      => '_down_formal',
                'type'    => 'text',
                'title'   => __('官方地址','io_setting'),
                'dependency' => array( '_sites_type', '==', 'down' ),
            ),
            array(
                'id'    => '_sites_screenshot',
                'type'  => 'gallery',
                'title' => __('添加截图','io_setting'),
                'dependency' => array( '_sites_type', '==', 'down' ),
            ),
        )
    ));
}
if( class_exists( 'CSF' ) ) {
    $site_options = 'sites-seo_post_meta';
    CSF::createMetabox($site_options, array(
        'title'     => 'SEO',
        'post_type' => 'sites',
        'data_type' => 'unserialize',
        'theme'     => 'light',
        'context'   => 'side',
        'priority'  => 'high', 
    ));
    CSF::createSection( $site_options, array(
        'fields' => array(
            array(
                'id'    => '_seo_title',
                'type'  => 'text',
                'title' => __('自定义标题','io_setting'),
                'desc' => 'Title 一般建议15到30个字符',
                'after' => __('留空则获取文章标题','io_setting'),
                'attributes' => array(
                    'style'    => 'width: 100%'
                ),
            ),
            array(
                'id'    => '_seo_metakey',
                'type'  => 'text',
                'title' => __('自定义关键词','io_setting'),
                'desc' => 'Keywords 每个关键词用英语逗号隔开',
                'after' => __('留空则获取文章标签','io_setting'),
                'attributes' => array(
                    'style'    => 'width: 100%'
                ),
            ),
            array(
                'id'    => '_seo_desc',
                'type'  => 'textarea',
                'title' => __('自定义描述','io_setting'),
                'desc' => 'Description 一般建议50到150个字符',
                'after' => __('留空则获取文章简介或摘要','io_setting'),
                'attributes' => array(
                    'style'    => 'width: 100%'
                ),
            ),
      
        )
    ));
}

// app
if( class_exists( 'CSF' ) ) {
    $site_options = 'app_post_meta';
    CSF::createMetabox($site_options, array(
        'title'     => __('APP 信息','io_setting'),
        'post_type' => 'app',
        'data_type' => 'unserialize',
        'theme'     => 'light',
        'priority'  => 'high', 
        'nav'       => 'inline',
    ));
    CSF::createSection($site_options, array(
        'title'  => '基础信息',
        'icon'   => 'fas fa-dice-d6',
        'fields'    => array(
            array(
                'id'           => '_app_type',
                'type'         => 'button_set',
                'title'        => __('类型','io_setting'),
                'options'      => array(
                    'app'    => __('软件','io_setting'),
                    'down'   => __('资源','io_setting'),
                ),
                'default'      => 'app',
            ),
            array(
                'type'    => 'content',
                'content' => __('排序：根据文章修改时间排序','io_setting'),//文章标题和seo标题为：app名称+app版本+更新日期+简介+APP状态<br>
            ),
            array(
                'id'      => '_app_ico',
                'type'    => 'upload',
                'title'   => __('图标 *','io_setting'),
                'subtitle'=> __('推荐256x256 必填','io_setting'),
                'library' => 'image',
                'class'   => 'cust_app_ico',
                'desc'    => __('添加图标地址，调用自定义图标','io_setting'),
            ),
            array(
                'id'     => 'app_ico_o',
                'type'   => 'fieldset',
                'title'  => __('图标选项','io_setting'),
                'fields' => array(
                    array(
                        'type'    => 'content',
                        'content' => __('预览','io_setting'),
                        'dependency' => array( 'ico_a', '==', true )
                    ),
                    array(
                        'id'    => 'ico_a',
                        'type'  => 'switcher',
                        'title' => __('透明','io_setting'),
                        'label' => __('图片是否透明？','io_setting'),
                    ),
                    array(
                        'id'        => 'ico_color',
                        'type'      => 'color_group',
                        'title'     => __('背景颜色','io_setting'),
                        'options'   => array(
                            'color-1' => __('颜色 1','io_setting'),
                            'color-2' => __('颜色 2','io_setting'),
                        ),
                        'default'   => array(
                            'color-1' => '#f9f9f9',
                            'color-2' => '#e8e8e8',
                        ),
                        'dependency' => array( 'ico_a', '==', true )
                    ),
                    array(
                        'id'      => 'ico_size',
                        'type'    => 'slider',
                        'title'   => __('缩放','io_setting'),
                        'min'     => 20,
                        'max'     => 100,
                        'step'    => 1,
                        'unit'    => '%',
                        'default' => 70,
                        'dependency' => array( 'ico_a', '==', true )
                    ),
                ),
            ),
            array(
                'id'      => '_app_name',
                'type'    => 'text',
                'title'   => __('名称','io_setting'),
            ),
            array(
                'id'      => '_app_platform',
                'type'    => 'checkbox',
                'title'   => __('支持平台','io_setting'),
                'inline'  => true,
                'options' => array(
                    'icon-microsoft'        => 'PC',
                    'icon-mac'              => 'MAC OS',
                    'icon-linux'            => 'linux',
                    'icon-android'          => __('安卓','io_setting'),
                    'icon-app-store-fill'   => 'ios',
                ),
            ),
            array(
                'id'      => '_down_formal',
                'type'    => 'text',
                'title'   => __('官方地址','io_setting'),
                'attributes' => array(
                    'style'    => 'width: 100%'
                ),
            ),
            array(
                'id'    => '_app_screenshot',
                'type'  => 'gallery',
                'title' => __('添加截图','io_setting'),
                'add_title'   => '添加截图',
                'edit_title'  => '编辑截图',
            ),
            array(
                'id'      => '_app_sescribe',
                'type'    => 'text',
                'title'   => __('简介','io_setting'),
                'after'   => '<br>'.__('推荐不要超过150个字符，详细介绍加正文。','io_setting'),
                'attributes' => array(
                    'style'    => 'width: 100%'
                ),
            ), 
        )
    )); 
    CSF::createSection($site_options, array(
        'title'  => '版本信息',
        'icon'   => 'fab fa-vine',
        'fields'    => array(
            array(
                'content' => '<p>填写资源下载地址和版本控制</p>',
                'style' => 'info',
                'type' => 'submessage',
            ), 
            array(
                'id'     => 'app_down_list',
                'type'   => 'group', 
                'before' => __('APP 版本信息（添加版本，可构建历史版本）', 'io_setting'),
                'fields' => array(
                    array(
                        'id'    => 'app_version',
                        'type'  => 'text',
                        'title' => __('版本号','io_setting'),
                        'placeholder'=>__('添加版本号','io_setting'),
                    ),
                    array(
                        'id'    => 'app_date',
                        'type'  => 'date',
                        'title' => __('更新日期','io_setting'),
                        'settings' => array(
                            'dateFormat'      => 'yy-m-d',
                            'changeMonth'     => true,
                            'changeYear'      => true, 
                            'showButtonPanel' => true,
                        ),
                        'default' => date('Y-m-d'),
                    ),
                    array(
                        'id'     => 'app_size',
                        'type'   => 'text',
                        'title'  => __('APP 大小', 'io_setting'),
                        'after'  => __('填写单位：KB,MB,GB,TB' ,'io_setting'),
                    ),
                    array(
                        'id'     => 'down_url',
                        'type'   => 'group',
                        'before' => __('下载地址信息','io_setting'),
                        'fields' => array(
                            array(
                                'id'    => 'down_btn_name',
                                'type'  => 'text',
                                'title' => __('按钮名称','io_setting'),
                            ),
                            array(
                                'id'    => 'down_btn_url',
                                'type'  => 'text',
                                'title' => __('下载地址','io_setting'),
                            ),
                            array(
                                'id'    => 'down_btn_tqm',
                                'type'  => 'text',
                                'title' => __('提取码','io_setting'),
                            ),
                            array(
                                'id'    => 'down_btn_info',
                                'type'  => 'text',
                                'title' => __('描述','io_setting'),
                            ),
                        ), 
                    ),
                    array(
                        'id'      => 'app_status',
                        'type'    => 'radio',
                        'title'   => __('APP状态','io_setting'),
                        'inline'  => true,
                        'options' => array(
                            'official'  => __('官方版','io_setting'),
                            'cracked'   => __('开心版','io_setting'),
                        ),
                        'default' => 'official',
                    ),
                    array(
                        'id'    => 'app_ad',
                        'type'  => 'switcher',
                        'title' => __('是否有广告','io_setting'),
                    ),
                    array(
                        'id'      => 'app_language',
                        'type'    => 'text',
                        'title'   => __('支持语言','io_setting'),
                        'default' => __('中文','io_setting'),
                    ),
                    array(
                        'id'            => 'version_describe',
                        'type'          => 'wp_editor',
                        'title'         => __('版本描述','io_setting'), 
                        'tinymce'       => true,
                        'quicktags'     => true,
                        'media_buttons' => false,
                        'height'        => '100px',
                    ),
                ),
                'default' => array(
                    array( 
                        'app_version' => '最新版',
                        'app_date' => date('Y-m-d'),
                        'down_url' => array(
                            array(
                                'down_btn_name' => __('百度网盘','io_setting'),
                            )
                        ), 
                        'app_status' => 'official',
                        'app_language' => __('中文','io_setting')
                    ),
                )
            ),
        )
    ));
}
if( class_exists( 'CSF' ) ) {
    $site_options = 'app-seo_post_meta';
    CSF::createMetabox($site_options, array(
        'title'     => 'SEO',
        'post_type' => 'app',
        'data_type' => 'unserialize',
        'theme'     => 'light',
        'priority'  => 'high', 
        'context'   => 'side',
    ));
    CSF::createSection( $site_options, array(
        'fields' => array(
            array(
                'id'    => '_seo_title',
                'type'  => 'text',
                'title' => __('自定义标题','io_setting'),
                'desc' => 'Title 一般建议15到30个字符',
                'after' => __('留空则获取文章标题','io_setting'),
                'attributes' => array(
                    'style'    => 'width: 100%'
                ),
            ),
            array(
                'id'    => '_seo_metakey',
                'type'  => 'text',
                'title' => __('自定义关键词','io_setting'),
                'desc' => 'Keywords 每个关键词用英语逗号隔开',
                'after' => __('留空则获取文章标签','io_setting'),
                'attributes' => array(
                    'style'    => 'width: 100%'
                ),
            ),
            array(
                'id'    => '_seo_desc',
                'type'  => 'textarea',
                'title' => __('自定义描述','io_setting'),
                'desc' => 'Description 一般建议50到150个字符',
                'after' => __('留空则获取文章简介或摘要','io_setting'),
                'attributes' => array(
                    'style'    => 'width: 100%'
                ),
            ),
        )
    ));
}

// 书籍
if( class_exists( 'CSF' ) ) {
    $site_options = 'book_post_meta';
    CSF::createMetabox($site_options, array(
        'title'     => __('书籍信息','io_setting'),
        'post_type' => 'book',
        'data_type' => 'unserialize',
        'theme'     => 'light',
        'priority'  => 'high',
    ));
    CSF::createSection($site_options, array(
        'fields'    => array(
            array(
                    'id'           => '_book_type',
                    'type'         => 'button_set',
                    'title'        => __('类型','io_setting'),
                    'options'      => array(
                    'books'      => __('图书','io_setting'),
                    'periodical' => __('期刊','io_setting'),
                    'movie'      => __('电影','io_setting'),
                    'tv'         => __('电视剧','io_setting'),
                    'video'      => __('小视频','io_setting'),
                ),
                    'default'      => 'books',
            ),
            array(
                'id'      => '_thumbnail',
                'type'    => 'upload',
                'title'   => __('封面','io_setting'),
                'library' => 'image',
            ),
            array(
                'id'      => '_summary',
                'type'    => 'text',
                'title'   => __('一句话描述（简介）','io_setting'),
                'after'   => '<br>'.__('推荐不要超过150个字符，详细介绍加正文。','io_setting'),
                'attributes' => array(
                    'style'    => 'width: 100%'
                ),
            ),
            array(
                'id'      => '_journal',
                'type'    => 'radio',
                'title'   => __('期刊类型','io_setting'),
                'default' => '3',
                'inline'  => true,
                'options' => array(
                    '12'       => __('周刊','io_setting'),
                    '9'        => __('旬刊','io_setting'),
                    '6'        => __('半月刊','io_setting'),
                    '3'        => __('月刊','io_setting'),
                    '2'        => __('双月刊','io_setting'),
                    '1'        => __('季刊','io_setting'),
                ),
                'dependency' => array( '_book_type', '==', 'periodical' ),
            ),
            array(
                'id'     => '_books_data',
                'type'   => 'group',
                'title'  => __('元数据','io_setting'),
                'fields' => array(
                    array(
                        'id'    => 'term',
                        'type'  => 'text',
                        'title' => __('项目(控制在5个字内)','io_setting'),
                    ),
                    array(
                        'id'    => 'detail',
                        'type'  => 'text',
                        'title' => __('内容','io_setting'),
                        'placeholder' => __('如留空，请删除项','io_setting'),
                    ),
                ), 
                'default' => io_get_option('books_metadata'),
            ),
            array(
                'id'     => '_buy_list',
                'type'   => 'group',
                'title'  => __('购买列表','io_setting'),
                'fields' => array(
                    array(
                        'id'      => 'term',
                        'type'    => 'text',
                        'title'   => __('按钮名称','io_setting'),
                        'default' => __('当当网','io_setting'),
                    ),
                    array(
                        'id'    => 'url',
                        'type'  => 'text',
                        'title' => __('购买地址','io_setting'),
                    ),
                    array(
                        'id'    => 'price',
                        'type'  => 'text',
                        'title' => __('价格','io_setting'),
                    ),
                ), 
            ),
            array(
                'id'     => '_down_list',
                'type'   => 'group',
                'title'  => __('下载地址列表','io_setting'),
                'before' => __('添加下载地址，提取码等信息','io_setting'),
                'fields' => array(
                    array(
                        'id'    => 'name',
                        'type'  => 'text',
                        'title' => __('按钮名称','io_setting'),
                        'default' => __('百度网盘','io_setting'),
                    ),
                    array(
                        'id'    => 'url',
                        'type'  => 'text',
                        'title' => __('下载地址','io_setting'),
                    ),
                    array(
                        'id'    => 'tqm',
                        'type'  => 'text',
                        'title' => __('提取码','io_setting'),
                    ),
                    array(
                        'id'    => 'info',
                        'type'  => 'text',
                        'title' => __('描述','io_setting'),
                        'placeholder' => __('格式、大小等','io_setting'),
                    ),
                ), 
            ),
        )
    ));
}
if( class_exists( 'CSF' ) ) {
    $site_options = 'book-seo_post_meta';
    CSF::createMetabox($site_options, array(
        'title'     => 'SEO',
        'post_type' => 'book',
        'data_type' => 'unserialize',
        'theme'     => 'light',
        'context'   => 'side',
        'priority'  => 'high', 
    ));
    CSF::createSection( $site_options, array(
        'fields' => array(
            array(
                'id'    => '_seo_title',
                'type'  => 'text',
                'title' => __('自定义标题','io_setting'),
                'desc' => 'Title 一般建议15到30个字符',
                'after' => __('留空则获取文章标题','io_setting'),
                'attributes' => array(
                    'style'    => 'width: 100%'
                ),
            ),
            array(
                'id'    => '_seo_metakey',
                'type'  => 'text',
                'title' => __('自定义关键词','io_setting'),
                'desc' => 'Keywords 每个关键词用英语逗号隔开',
                'after' => __('留空则获取文章标签','io_setting'),
                'attributes' => array(
                    'style'    => 'width: 100%'
                ),
            ),
            array(
                'id'    => '_seo_desc',
                'type'  => 'textarea',
                'title' => __('自定义描述','io_setting'),
                'desc' => 'Description 一般建议50到150个字符',
                'after' => __('留空则获取文章简介或摘要','io_setting'),
                'attributes' => array(
                    'style'    => 'width: 100%'
                ),
            ),
        )
    ));
}

// 公告
if( class_exists( 'CSF' ) ) {
    $site_options = 'bulletin_post_meta';
    CSF::createMetabox($site_options, array(
        'title'     => __('公告设置','io_setting'),
        'post_type' => 'bulletin',
        'data_type' => 'unserialize',
        'theme'     => 'light',
        'priority'  => 'high',
    ));
    CSF::createSection($site_options, array(
        'fields'    => array(
            array(
                'id'      => '_goto',
                'type'    => 'text',
                'title'   => __('直达地址','io_setting'),
                'after'   => '<br>'.__('添加直达地址，如：https://www.baidu.com','io_setting'),
                'attributes' => array(
                    'style'    => 'width: 100%'
                ),
            ),
            array(
                'id'    => '_is_go',
                'type'  => 'switcher', 
                'title'   => __('GO 跳转','io_setting'),
                'text_on' => '启用',
                'text_off'=> '禁用',
                'default' => false,
                'dependency' => array( '_goto', '!=', '' )
            ),
            array(
                'id'    => '_nofollow',
                'type'  => 'switcher', 
                'title'   => __('nofollow','io_setting'),
                'text_on' => '启用',
                'text_off'=> '禁用',
                'default' => false,
                'dependency' => array( '_goto', '!=', '' )
            ),
        )
    ));
}




// Metabox 选项框架
if( class_exists( 'CSF' ) ) {
    $prefix = 'links_post_options';
    CSF::createMetabox( $prefix, array(
        'title'           => '友情链接选项',
        'post_type'       => 'page',
        'page_templates'  => 'template-links.php',
        'context'         => 'side',
        'data_type'       => 'unserialize'
    ) );
    CSF::createSection( $prefix, array(
        'title'  => '默认内容',
        'fields' => array(
            array(
                'id'    => '_disable_links_content',
                'type'  => 'switcher', 
                'text_on' => '启用',
                'text_off'=> '禁用',
                'default' => true,
            ),
        )
    ));
}

if( class_exists( 'CSF' ) ) {
    $prefix = 'mininav_post_options';
    CSF::createMetabox( $prefix, array(
        'title'           => '次级导航选项',
        'post_type'       => 'page',
        'page_templates'  => 'template-mininav.php',
        'priority'        => 'high',
        'data_type'       => 'unserialize'
    ) );
    CSF::createSection( $prefix, array(
        'fields' => array(
            array(
                'id'          => 'nav-id',
                'type'        => 'select',
                'title'       => '请选择菜单',
                'placeholder' => '选择菜单',
                'options'     => 'menus'
            ),
            array(
                'id'        => 'search_box',
                'type'      => 'switcher', 
                'title'     => '顶部搜索框',
                'after'     => '依赖于首页设置',
                'text_on'   => '启用',
                'text_off'  => '禁用',
                'default'   => true,
            ),
            /**
             * #TODO 次级导航自定义搜索
            array(
                'id'        => '_search_id',
                'type'      => 'text',
                'title'     => '自定义搜索列表ID',
                'after'     => '<i class="fa fa-fw fa-info-circle fa-fw"></i> '.'需先在主题设置中开启“<a href="'.admin_url('admin.php?page=theme_settings#tab=%e9%a6%96%e9%a1%b5%e8%ae%be%e7%bd%ae/%e6%90%9c%e7%b4%a2%e8%ae%be%e7%bd%ae').'">自定义搜索列表</a>”，然后在搜索列表设置中设置“<a href="'.admin_url('options-general.php?page=search_settings#tab=%e6%ac%a1%e7%ba%a7%e5%af%bc%e8%88%aa%e8%87%aa%e5%ae%9a%e4%b9%89%e6%90%9c%e7%b4%a2').'">次级导航自定义搜索</a>”',
                'dependency' => array( 'search_box', '==', true )
            ),
            */
            array(
                'id'        => 'hot_box',
                'type'      => 'switcher', 
                'title'     => '热门内容',
                'after'     => '依赖于首页设置',
                'text_on'   => '启用',
                'text_off'  => '禁用',
                'default'   => true,
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
                        'default' => false
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
                        )
                    ),
                ),
                'max'     => 6,
            ),
        )
    ));
}
if( class_exists( 'CSF' ) ) {
    $prefix = 'ranking_post_options';
    CSF::createMetabox( $prefix, array(
        'title'           => '排行榜选项',
        'post_type'       => 'page',
        'page_templates'  => 'template-rankings.php',
        'data_type'       => 'unserialize'
    ) );
    CSF::createSection( $prefix, array(
        'fields' => array(
            array(
                'id'          => '_show-count',
                'type'        => 'spinner',
                'title'       => '数量',
                'after'       => __('列表显示数量','io_setting'),
                'step'        => 1,
                'default'     => 10,
            ),
            array(
                'id'           => '_show-list',
                'type'         => 'sorter',
                'title'        => '显示和排序',
                'default'      => array(
                    'enabled'    => array(
                        'sites'  => '网址排行榜',
                        'post'   => '文章排行榜',
                    ),
                    'disabled'   => array(
                        'book' => '书籍排行榜',
                        'app'  => '软件排行榜',
                    ),
                ),
            ),
            array(
                'id'          => '_url_go',
                'type'        => 'switcher',
                'title'       => '直达目标网址',
                'after'       => '依赖于主题设置，如果主题设置中关闭了详情页，则此设置无效。',
            ),
        )
    ));
}
