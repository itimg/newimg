<?php 
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-08-30 14:41:57
 * @LastEditors: iowen
 * @LastEditTime: 2021-09-30 23:56:48
 * @FilePath: \onenav\inc\search-settings.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' )  ) { die; }
if (!is_admin()) return;
$prefix = 'io_search_list';
include( get_theme_file_path('/inc/search-list.php') ); 

CSF::createOptions( $prefix, array(
    'framework_title' => '搜索源配置',
    'menu_title'      => '搜索源配置',
    'menu_slug'       => 'search_settings', 
    'show_search'             => false,  
    'show_reset_section'      => false,
    'show_footer'             => false,
    'show_all_options'        => false, 
    
    'show_sub_menu'           => false,
    
    'menu_icon'       => 'dashicons-search',
    'menu_type'       => 'submenu',
    'menu_parent'     => 'options-general.php',
    'show_bar_menu'   => false,
    'theme'           => 'light',  
    'nav'             => 'inline',
));

//
// 搜索设置
//
CSF::createSection( $prefix, array(  
    //'title'       => '默认搜索列表', 
    'fields'   => array( 
        array(
            'content' => '<p><b>搜索引擎URL结构，比如：</b> https://www.baidu.com/s?wd=关键词 ，请填写 https://www.baidu.com/s?wd=%s% </p>
            <p><b>再比如：</b> https://xx.xxx.xx?wd=关键词.key ，其中<b>.key</b>为搜索引擎必须加的后缀，请填写 https://xx.xxx.xx?wd=%s%.key </p>
            <p>规则为：使用 <b>%s%</b> 替换搜索引擎URL的关键词位置</p>
            <br><i class="fa fa-fw fa-info-circle fa-fw"></i> 修改后如果前台不显示选单，请清空浏览器缓存，如果还是不显示，请检测对应“默认值”对应的 id 是否存在',
            'style' => 'info',
            'type' => 'submessage',
        ), 
        array(
            'id'        => 'search_list',
            'type'      => 'group', 
            'fields'    => array(
                array(
                    'id'    => 'name',
                    'type'  => 'text',
                    'title' => '名称',
                ),
                array(
                    'id'    => 'id',
                    'type'  => 'text',
                    'title' => 'ID',
                    'after' => '<i class="fa fa-fw fa-info-circle fa-fw"></i> id 必须唯一，前缀必须是 <b>group-</b>'
                ),
                array(
                    'id'    => 'default',
                    'type'  => 'text',
                    'title' => '默认值',
                    'after' => '<i class="fa fa-fw fa-info-circle fa-fw"></i> 填写下方列表里的ID值'
                ),
                array(
                    'id'        => 'list',
                    'type'      => 'group',
                    'title'     => '列表',
                    'fields'    => array(
                        array(
                            'id'    => 'name',
                            'type'  => 'text',
                            'title' => '名称',
                        ),
                        array(
                            'id'    => 'id',
                            'type'  => 'text',
                            'title' => 'ID',
                            'after' => '<i class="fa fa-fw fa-info-circle fa-fw"></i> id 必须唯一，前缀必须是 <b>type-</b>'
                        ),
                        array(
                            'id'    => 'placeholder',
                            'type'  => 'text',
                            'title' => '占位符',
                        ),
                        array(
                            'id'    => 'url',
                            'type'  => 'text',
                            'title' => 'URL地址',
                            'help'  => '使用 <b>%s%</b> 替换搜索引擎URL的关键词位置',
                            'attributes' => array(
                                'style'    => 'width: 100%'
                            ),
                        ),
                    ),
                ),
            ),
            'default'   => $search_list
        ),
        // 备份
        array(
            'type' => 'backup',
            'before' => '<i class="fa fa-fw fa-info-circle fa-fw"></i> 导出数据分享给好友。'
        ),
    )
));

/** 
 * #TODO 次级导航自定义搜索
CSF::createSection( $prefix, array(  
    'title'       => '次级导航自定义搜索', 
    'fields'   => array( 
        array(
            'content' => '<i class="fa fa-fw fa-info-circle fa-fw"></i> 搜索列表填写规则和“默认搜索列表”一致',
            'style' => 'info',
            'type' => 'submessage',
        ), 
        array(
            'id'        => 'custom_search_list',
            'type'      => 'group',  
            'fields'    =>array(
                array(
                    'id'    => 'search_list_id',
                    'type'  => 'text',
                    'title' => '列表ID',
                    'after' => '<i class="fa fa-fw fa-info-circle fa-fw"></i> 必须唯一，用于次级导航设置选项中的“自定义搜索列表ID”'
                ),
                array(
                    'id'        => 'search_list',
                    'type'      => 'group', 
                    'title'     => '搜索列表',
                    'fields'    => array(
                        array(
                            'id'    => 'name',
                            'type'  => 'text',
                            'title' => '名称',
                        ),
                        array(
                            'id'    => 'id',
                            'type'  => 'text',
                            'title' => 'ID',
                            'after' => '<i class="fa fa-fw fa-info-circle fa-fw"></i> id 必须唯一，前缀必须是 <b>group-</b>'
                        ),
                        array(
                            'id'    => 'default',
                            'type'  => 'text',
                            'title' => '默认值',
                            'after' => '<i class="fa fa-fw fa-info-circle fa-fw"></i> 填写下方列表里的ID值'
                        ),
                        array(
                            'id'        => 'list',
                            'type'      => 'group',
                            'title'     => '列表',
                            'fields'    => array(
                                array(
                                    'id'    => 'name',
                                    'type'  => 'text',
                                    'title' => '名称',
                                ),
                                array(
                                    'id'    => 'id',
                                    'type'  => 'text',
                                    'title' => 'ID',
                                    'after' => '<i class="fa fa-fw fa-info-circle fa-fw"></i> id 必须唯一，前缀必须是 <b>type-</b>'
                                ),
                                array(
                                    'id'    => 'placeholder',
                                    'type'  => 'text',
                                    'title' => '占位符',
                                ),
                                array(
                                    'id'    => 'url',
                                    'type'  => 'text',
                                    'title' => 'URL地址',
                                    'help'  => '使用 <b>%s%</b> 替换搜索引擎URL的关键词位置',
                                    'attributes' => array(
                                        'style'    => 'width: 100%'
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'default'   => array(
                array(
                "search_list_id" => "001",
                'search_list'   => $search_list
                )
            )
        ),
    )
));
*/