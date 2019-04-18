<?php if ( ! defined( 'ABSPATH' )  ) { die; } // Cannot access directly.

//
// Set a unique slug-like ID
//
$prefix = 'cs_my_options';

defined( 'CS_OPTION' ) or define( 'CS_OPTION',$prefix );

//
// Create options
//
CSF::createOptions( $prefix, array(
  'menu_title' => '主题设置',
  'menu_slug'  => 'cs-options',
) );

//
// Create a 基本设置
//
CSF::createSection( $prefix, array(
  'title'  => '基本设置',
  'icon'   => 'fa fa-rocket',
  'fields' => array(

   
    array(
      'id'        => 'seo',
      'type'      => 'fieldset',
      'title'     => 'SEO相关',
      'fields'    => array(

        array(
          'id'        => 'web_keywords',
          'type'      => 'text',
          'title'     => '网站关键词',
          'desc'      => '3-5个关键词，用英文逗号隔开',
          'attributes' => array(
            'style'    => 'width: 100%;'
          ),
          'default'      => '日主题,rizhuti.com,rizhuti',
        ),
        array(
          'id'        => 'web_description',
          'type'      => 'textarea',
          'title'     => '网站描述',
          'default'      => '日主题，让男人欲罢不能的WordPress主题，喜欢你就日一下',
        ),

      ),
    ),

    array(
      'id'    => 'connector',
      'type'  => 'text',
      'title' => '全站链接符',
      'desc' => '一经选择，切勿更改，对SEO不友好，一般为“-”或“_”',
      'default' => '-',
    ),

    

    array(
      'id'    => 'ac_qqhao',
      'type'  => 'text',
      'title' => '在线咨询QQ号码',
      'default' => '200933220',
    ),
    array(
      'id'        => 'post_default_thumb',
      'type'      => 'media',
      'title'     => '文章默认缩略图',
      'add_title' => '上传图片',
      'desc'      => '设置文章默认缩略图',
      'default'      => array('url' =>get_stylesheet_directory_uri() . '/img/thumb.png'),
    ),

    array(
      'id'                => 'thumbnail_handle',
      'type'              => 'radio',
      'title'             => '缩略图裁剪模式',
      'desc'              => '默认为timthumb.php模式',
      'options'           => array(
        'timthumb_php'   => 'timthumb.php裁剪（可保持缩略图大小一致）',
        'timthumb_mi'    => 'WP自带裁剪',
      ),
      'default'           => 'timthumb_php',
    ),
    array(
      'id'    => 'set_postthumbnail',
      'type'  => 'switcher',
      'title' => '自动保存文章第一张图片为缩略图',
      'label' => '设置本地发布时候上传的第一张图片为缩略图',
      'default' => true,
    ),
   
    
    array(
      'id'    => 'enabled_cdn_assets',
      'type'  => 'switcher',
      'title' => '开启CDN加载前端公共库加速Jquery',
      'label' => '速度超快',
      'default' => false,
    ),

    array(
      'id'    => 'no_categoty',
      'type'  => 'switcher',
      'title' => '分类url去除category字样',
      'label' => '该功能和no-category插件作用相同，可停用no-category插件',
      'default' => false,
    ),

    

    

  )
) );

//
// Field: 顶部设置
//
CSF::createSection( $prefix, array(
  'title' => '顶部设置',
  'icon'  => 'fa fa-long-arrow-up',
  'fields'      => array(


    array(
      'id'    => 'is_header_fixed',
      'type'  => 'switcher',
      'title' => '顶部导航浮动风格',
      'label' => '手机端口体验不好，只在PC生效',
      'default' => true,
    ),

    array(
      'id'        => 'header_logo',
      'type'      => 'media',
      'title'     => '网站LOGO',
      'desc'      => '尺寸大小参考：128px*80px',
      'add_title' => '上传图片',
      'default'      => array('url' =>get_stylesheet_directory_uri() . '/img/logo.png'),
    ),
    array(
      'id'        => 'web_favicon',
      'type'      => 'media',
      'title'     => 'favicon图标',
      'add_title' => '上传图标',
      'desc'      => '上传 .ico 格式的文件<br>图标制作可搜索：favicon图标制作',
      'default'      => array('url' =>get_stylesheet_directory_uri() . '/img/vipimg.png'),
    ),
     array(
      'id'       => 'web_css',
      'type'     => 'code_editor',
      'title'    => '自定义CSS样式代码',
      'before'   => '<p class="csf-text-muted"><strong>位于顶部，自定义修改CSS</strong>不用添加<strong>&lt;style></strong>标签</p>',
      'settings' => array(
        'theme'  => 'mbo',
        'mode'   => 'css',
      ),
      'default' =>'',
    ),


  )
) );

// 列表设置

CSF::createSection( $prefix, array(
  'title' => '列表设置',
  'icon'  => 'fa fa-reorder',
  'fields'      => array(

    array(
      'id'      => 'list_cols',
      'type'    => 'select',
      'title'   => '列布局',
      'options' => array(
            '5' => '5列布局',
            '4' => '4列布局',
            '3' => '3列布局',
            '2' => '2列布局',
        ),
      'default'  => 4,
    ),
    array(
      'id'    => 'target_blank',
      'type'  => 'switcher',
      'title' => '新窗口打开列表文章',
      'label' => '',
      'default' => false,
    ),
    array(
      'id'    => 'list_imagetext',
      'type'  => 'switcher',
      'title' => '列布局：图文一体',
      'label' => '开启后文字会显示在图片上方',
      'default' => false,
    ),
    array(
      'id'    => 'list_is_time',
      'type'  => 'switcher',
      'title' => '是否显示时间',
      'label' => '可能会一些美观',
      'default' => false,
    ),
    array(
      'id'    => 'excerpt_hot_s',
      'type'  => 'switcher',
      'title' => '智能热门',
      'label' => '开启后如果文章满足以下智能热门限制就会在列表的最前展示',
      'default' => false,
    ),
    array(
      'id'      => 'excerpt_hot_date',
      'type'    => 'slider',
      'title'   => '智能热门限制多少天内的文章',
      'default' => 7,
    ),
    array(
      'id'      => 'excerpt_hot_items',
      'type'    => 'slider',
      'title'   => '智能热门限制文章数',
      'default' => 2,
    ),
    array(
      'id'      => 'excerpt_hot_minviews',
      'type'    => 'slider',
      'title'   => '智能热门限制文章阅读量',
      'default' => 100,
    ),
    array(
      'id'    => 'phone_list_news',
      'type'  => 'switcher',
      'title' => '手机端使用新闻列表',
      'label' => '',
      'default' => true,
    ),
    array(
      'id'      => 'paging_type',
      'type'    => 'radio',
      'title'   => '翻页按钮风格',
      'inline'  => true,
      'options' => array(
            'next' => ' 上一页 和 下一页',
            'multi' => ' 显示页码，如：上一页 1 2 3 4 5 下一页'
        ),
      'default'  => 'multi',
    ),
    array(
      'id'    => 'ajaxpager_s',
      'type'  => 'switcher',
      'title' => 'PC端分页无限加载',
      'label' => '',
      'default' => false,
    ),
    array(
      'id'    => 'ajaxpager_s_m',
      'type'  => 'switcher',
      'title' => '手机端分页无限加载',
      'label' => '',
      'default' => false,
    ),
    array(
      'id'      => 'ajaxpager',
      'type'    => 'slider',
      'title'   => '分页无限加载页数',
      'desc'   => '为0时表示不开启分页无限加载功能，默认为10',
      'default' => 10,
    ),


    

  )
) );


//
// 首页设置
//
CSF::createSection( $prefix, array(
  'id'    => 'basic_fields',
  'title' => '首页设置',
  'icon'  => 'fa fa-plus-circle',
) );


//
// 首页设置: 布局
//
CSF::createSection( $prefix, array(
  'parent'      => 'basic_fields',
  'title'       => '模块布局',
  'icon'        => 'fa fa-server',
  'description' => '拖拽要启用的模块和排序',
  'fields'      => array(

    array(
      'id'             => 'home_module',
      'type'           => 'sorter',
      'title'          => '首页模块排序和启用',
      'enabled_title'  => '显示的模块',
      'disabled_title' => '隐藏',
      'default'        => array(
        'enabled'      => array(
          'slider'      => '幻灯片',
          'catbox'      => '分类推荐',
          'postlist'      => '最新文章',
          'catcms'      => '分类CMS',
          'about'      => '关于我们',
          'vip'      => 'VIP介绍模块',
          'service'      => '服务模块',
        ),
        'disabled'     => array(
          'banner'      => '搜索banner',
          'html'      => '自定义HTML',
        ),
      ),
    ),
  )
) );


//
// 首页设置: 幻灯片
//
CSF::createSection( $prefix, array(
  'parent'      => 'basic_fields',
  'title'       => '幻灯片',
  'icon'        => 'fa fa-picture-o',
  'description' => '无限幻灯片设置',
  'fields'      => array(

    array(
      'id'    => 'focusslide_pagination',
      'type'  => 'switcher',
      'title' => '幻灯片导航分页',
      'label' => '',
      'default' => true,
    ),
    array(
      'id'    => 'focusslide_button',
      'type'  => 'switcher',
      'title' => '幻灯片左右翻页按钮',
      'label' => '',
      'default' => false,
    ),

    array(
      'id'     => 'focusslide',
      'type'   => 'repeater',
      'title'  => '新建幻灯片',
      'fields' => array(
        array(
          'id'    => '_blank',
          'type'  => 'switcher',
          'title' => '新窗口打开链接',
          'label' => '',
          'default' => true,
        ),
        array(
          'id'    => '_title',
          'type'  => 'text',
          'title' => '标题',
          'default' => '幻灯片标题',
        ),
        array(
          'id'    => '_desc',
          'type'  => 'text',
          'title' => '描述内容',
          'default' => '这里是幻灯片的描述内容',
        ),
        array(
          'id'    => '_href',
          'type'  => 'text',
          'title' => '链接地址',
          'default' => '#',
        ),
        array(
          'id'    => '_btn',
          'type'  => 'text',
          'title' => '按钮名称',
          'default' => '查看详情',
        ),

        array(
      'id'           => '_src',
      'type'         => 'upload',
      'title'        => '上传幻灯片',
      'library'      => 'image',
      'placeholder'  => 'http://',
      'default'  => 'https://s2.ax1x.com/2019/02/17/ksXfHJ.jpg',
    ),
        // array(
        //   'id'        => '_src',
        //   'type'      => 'media',
        //   'title'     => '图片',
        //   'add_title' => '上传',
        //   'desc'      => '图片建议尺寸统一：'.'1920*600',
        //   'default'      => array('url' =>'https://s2.ax1x.com/2019/02/17/ksXfHJ.jpg'),
        // ),
      ),
    ),

  )
) );


//
// 首页设置: 搜索banner
//
CSF::createSection( $prefix, array(
  'parent'      => 'basic_fields',
  'title'       => '搜索Banner模块',
  'icon'        => 'fa fa-search',
  'description' => '顶部搜索模块详细设置',
  'fields'      => array(

    array(
      'id'    => 'banner_title',
      'type'  => 'text',
      'title' => '搜索大标题',
      'default' => '日主题RiTheme，搜索从这里开始',
    ),

    array(
      'id'    => 'search_btn',
      'type'  => 'text',
      'title' => '搜索按钮名称',
      'default' => '日一下',
    ),

    // array(
    //   'id'        => 'search_bgimg',
    //   'type'      => 'media',
    //   'title'     => '搜索背景图片',
    //   'add_title' => '上传',
    //   'desc'      => '图片建议尺寸统一：'.'1920*600',
    //   'default'      => array('url' =>'https://s2.ax1x.com/2019/02/17/ksXy90.jpg'),
    // ),
    array(
    'id'           => 'search_bgimg',
    'type'         => 'upload',
    'title'        => '上传图片',
    'library'      => 'image',
    'placeholder'  => 'http://',
    'default'  => 'https://s2.ax1x.com/2019/02/17/ksXfHJ.jpg',
  ),
    array(
      'id'    => 'search_no_page',
      'type'  => 'switcher',
      'title' => '搜索结果排除页面',
      'label' => '',
      'default' => true,
    ),


  )
) );


//
// 首页设置: 分类box
//
CSF::createSection( $prefix, array(
  'parent'      => 'basic_fields',
  'title'       => '分类推荐模块',
  'icon'        => 'fa fa-book',
  'description' => '此处设置内容为首页顶部分类推荐展示模块',
  'fields'      => array(

    array(
      'id'     => 'catbox',
      'type'   => 'repeater',
      'title'  => '新建分类推荐',
      'max'  => 4,
      'fields' => array(
        array(
          'id'          => 'cat_id',
          'type'        => 'select',
          'title'       => '推荐的分类',
          'placeholder' => '选择一个分类',
          'options'     => 'categories',
        ),
        array(
          'id'        => 'cat_bgimg',
          'type'      => 'media',
          'title'     => '推荐背景',
          'add_title' => '上传',
          'desc'      => '图片建议尺寸统一：'.'300*180',
          'default'      => array('url' =>'https://rizhuti.com/wp-content/uploads/2019/02/d7ee241bdd6b1a6.jpg'),
        ),
      ),
    ),

  )
) );


//
// 首页设置: 最新文章模块
//
CSF::createSection( $prefix, array(
  'parent'      => 'basic_fields',
  'title'       => '最新文章模块',
  'icon'        => 'fa fa-braille',
  'description' => '首页最新文章模块设置',
  'fields'      => array(

    array(
      'id'    => 'mo_postlist_title',
      'type'  => 'text',
      'title' => '模块标题',
      'default' => '最新资源',
    ),

    array(
      'id'    => 'mo_postlist_desc',
      'type'  => 'text',
      'title' => '模块描述',
      'default' => '关注前沿设计风格，紧跟行业趋势，精选优质好资源！',
    ),
    array(
      'id'          => 'mo_postlist_no_cat',
      'type'        => 'select',
      'title'       => '首页要排除的分类',
      'placeholder' => '选择要排除的分类',
      'chosen' => true,
      'multiple' => true,
      'options'     => 'categories',
    ),
   
  )
) );


//
// 首页设置: 分类CMS展示
//
CSF::createSection( $prefix, array(
  'parent'      => 'basic_fields',
  'title'       => '分类CMS展示',
  'icon'        => 'fa fa-newspaper-o',
  'description' => '此处设置内容为首页分类CMS展示模块，可以设置无限个',
  'fields'      => array(

    array(
      'id'     => 'catcms',
      'type'   => 'repeater',
      'title'  => '无限分类CMS',
      'fields' => array(
        array(
          'id'    => 'cms_title',
          'type'  => 'text',
          'title' => 'CMS标题',
          'default' => 'CMS资源展示',
        ),
        array(
          'id'    => 'cms_desc',
          'type'  => 'text',
          'title' => 'CMS描述',
          'default' => '关注前沿设计风格，紧跟行业趋势，精选CMS优质好资源！',
        ),
        array(
          'id'          => 'cms_cat_id',
          'type'        => 'select',
          'title'       => '文章的分类',
          'placeholder' => '选择一个分类',
          'options'     => 'categories',
        ),
        array(
          'id'      => 'cms_cat_num',
          'type'    => 'slider',
          'title'   => 'CMS显示文章数量',
          'desc'   => '默认为8',
          'default' => 8,
        ),
        array(
          'id'    => 'cms_btn',
          'type'  => 'text',
          'title' => '更多按钮名称',
          'default' => '查看更多',
        ),
      ),
    ),

  )
) );

//
// 首页设置: 关于我们
//
CSF::createSection( $prefix, array(
  'parent'      => 'basic_fields',
  'title'       => '关于我们模块',
  'icon'        => 'fa fa-info-circle',
  'description' => '关于我们模块详细设置',
  'fields'      => array(

    array(
      'id'    => 'about_title',
      'type'  => 'text',
      'title' => '模块标题',
      'default' => '关于日主题 ABOUT',
    ),
    array(
      'id'    => 'about_desc',
      'type'  => 'text',
      'title' => '模块描述',
      'default' => '如少女般纯洁，干净，无需任何插件，极度优化，支持支付宝，微信付款，具备完善的设置选项',
    ),

    array(
      'id'    => 'about_btn',
      'type'  => 'text',
      'title' => '按钮名称',
      'default' => '联系我们',
    ),
    array(
      'id'    => 'about_btn_href',
      'type'  => 'text',
      'title' => '按钮链接',
      'default' => '#',
    ),

    array(
      'id'        => 'about_bgimg',
      'type'      => 'media',
      'title'     => '模块背景图片',
      'add_title' => '上传',
      'desc'      => '图片建议尺寸统一：'.'1920*600',
      'default'      => array('url' =>'https://s2.ax1x.com/2019/02/17/ksjCgf.jpg'),
    ),
  )
) );


//
// 首页设置: VIP介绍
//
CSF::createSection( $prefix, array(
  'parent'      => 'basic_fields',
  'title'       => 'VIP介绍模块',
  'icon'        => 'fa fa-diamond',
  'description' => 'VIP介绍模块详细设置',
  'fields'      => array(

    array(
      'id'        => 'mo_home_vip',
      'type'      => 'group',
      'title'     => '添加介绍',
      'max'     => '3',
      'fields'    => array(
        array(
          'id'    => '_title',
          'type'  => 'text',
          'title' => '标题',
          'default' => 'VIP会员',
        ),
        array(
          'id'    => '_desc',
          'type'  => 'text',
          'title' => '描述',
          'default' => '月费、年费、终身套餐',
        ),

        array(
          'id'        => '_img',
          'type'      => 'media',
          'title'     => '模块背景图片',
          'add_title' => '上传',
          'desc'      => '图片建议尺寸统一：'.'1920*600',
          'default'   => array('url' =>get_stylesheet_directory_uri() . '/img/block-1.png'),
        ),
      ),
    ),
    
  )
) );


//
// 首页设置: 服务信息
//
CSF::createSection( $prefix, array(
  'parent'      => 'basic_fields',
  'title'       => '服务信息模块',
  'icon'        => 'fa fa-cubes',
  'description' => 'VIP介绍模块详细设置',
  'fields'      => array(
    array(
      'id'    => 'mo_home_service_title',
      'type'  => 'text',
      'title' => '模块主标题',
      'default' => '这是一个神奇的网站',
    ),
    array(
      'id'    => 'mo_home_service_desc',
      'type'  => 'text',
      'title' => '模块描述',
      'default' => '我们痴迷于高质量的源码和生产力，敬请期待更多优质数字资源',
    ),
    array(
      'id'        => 'mo_home_service',
      'type'      => 'group',
      'title'     => '添加介绍',
      'max'     => '3',
      'fields'    => array(
        array(
          'id'    => '_title',
          'type'  => 'text',
          'title' => '标题',
          'default' => '1665',
        ),
        array(
          'id'    => '_desc',
          'type'  => 'text',
          'title' => '描述',
          'default' => '会员',
        ),

        array(
          'id'        => '_img',
          'type'      => 'media',
          'title'     => '图标',
          'add_title' => '上传',
          'desc'      => '图片建议尺寸统一：'.'100*100',
          'default'   => array('url' =>get_stylesheet_directory_uri() . '/img/block-1.png'),
        ),
      ),
    ),
    
  )
) );


//
// 首页设置: 自定义HTML
//
CSF::createSection( $prefix, array(
  'parent'      => 'basic_fields',
  'title'       => '自定义HTML模块',
  'icon'        => 'fa fa-code',
  'description' => '自定义HTML设置,将html代码等展示',
  'fields'      => array(
    array(
      'id'       => 'home_mod_html',
      'type'     => 'code_editor',
      'title'    => '自定义HTML',
      'subtitle' => '自定义HTML代码',
      'settings' => array(
        'theme'  => 'dracula',
        'mode'   => 'html',
      ),
      'default' =>'',
    ),
    
  )
) );



// 文章页面设置

CSF::createSection( $prefix, array(
  'title' => '文章设置',
  'icon'  => 'fa fa-newspaper-o',
  'fields'      => array(

    array(
      'id'    => 'post_alt_title_s',
      'type'  => 'switcher',
      'title' => '文章中图片自动增加alt和title属性',
      'label' => '开启，开启后自动给文章中所有的图片增加alt和title（不论之前有无alt或title），alt为文章标题，title为文章标题+网站标题',
      'default' => false,
    ),

    array(
      'id'    => 'post_copyright_s',
      'type'  => 'switcher',
      'title' => '文章页尾版权',
      'label' => '',
      'default' => false,
    ),
    array(
      'id'    => 'post_copyright',
      'type'  => 'text',
      'title' => '版权文字',
      'default' => '未经允许不得转载：',
      'dependency'  => array( 'post_copyright_s', '==', 'true' ),
    ),
    array(
      'id'    => 'post_tags_s',
      'type'  => 'switcher',
      'title' => '文章标签',
      'label' => '底部显示文章标签',
      'default' => true,
    ),
    array(
      'id'    => 'post_wechats_s',
      'type'  => 'switcher',
      'title' => '公众号推荐模块',
      'label' => '',
      'default' => false,
    ),
    array(
      'id'        => 'post_wechat_1_image',
      'type'      => 'media',
      'title'     => '公众号：二维码',
      'add_title' => '上传',
      'desc'      => '',
      'default'   => array('url' =>'https://s2.ax1x.com/2019/02/17/ksXang.png'),
      'dependency'  => array( 'post_wechats_s', '==', 'true' ),
    ),
    array(
      'id'    => 'post_wechat_1_title',
      'type'  => 'text',
      'title' => '公众号标题',
      'default' => '微信公众号：rizhuti',
      'dependency'  => array( 'post_wechats_s', '==', 'true' ),
    ),
    array(
      'id'    => 'post_wechat_1_desc',
      'type'  => 'text',
      'title' => '公众号：介绍',
      'default' => '关注我们，获取更多的全网素材资源，有趣有料！',
      'dependency'  => array( 'post_wechats_s', '==', 'true' ),
    ),
    array(
      'id'    => 'post_wechat_1_users',
      'type'  => 'text',
      'title' => '公众号：关注数',
      'default' => '12000人已关注',
      'dependency'  => array( 'post_wechats_s', '==', 'true' ),
    ),

    // array(
    //   'id'    => 'is_share_poster',
    //   'type'  => 'switcher',
    //   'title' => '生成海报',
    //   'label' => '',
    //   'default' => false,
    // ),
    // array(
    //   'id'    => 'bigger_desc',
    //   'type'  => 'text',
    //   'title' => '海报图片底部文字',
    //   'default' => 'RIZHUTI 2019 右键图片另存为',
    //   'dependency'  => array( 'is_share_poster', '==', 'true' ),
    // ),
    array(
      'id'    => 'post_rewards_s',
      'type'  => 'switcher',
      'title' => '打赏',
      'label' => '',
      'default' => false,
    ),
    array(
      'id'    => 'post_rewards_text',
      'type'  => 'text',
      'title' => '打赏：显示文字',
      'default' => '打赏',
      'dependency'  => array( 'post_rewards_s', '==', 'true' ),
    ),
    array(
      'id'    => 'post_rewards_title',
      'type'  => 'text',
      'title' => '打赏：弹出层标题',
      'default' => '觉得文章有用就打赏一下文章作者',
      'dependency'  => array( 'post_rewards_s', '==', 'true' ),
    ),
    array(
      'id'        => 'post_rewards_alipay',
      'type'      => 'media',
      'title'     => '打赏：支付宝收款二维码',
      'add_title' => '上传',
      'desc'      => '',
      'default'   => array('url' =>'https://s2.ax1x.com/2019/02/17/ksXang.png'),
      'dependency'  => array( 'post_rewards_s', '==', 'true' ),
    ),
    array(
      'id'        => 'post_rewards_wechat',
      'type'      => 'media',
      'title'     => '打赏：微信收款二维码',
      'add_title' => '上传',
      'desc'      => '',
      'default'   => array('url' =>'https://s2.ax1x.com/2019/02/17/ksXang.png'),
      'dependency'  => array( 'post_rewards_s', '==', 'true' ),
    ),
    array(
      'id'    => 'post_like_s',
      'type'  => 'switcher',
      'title' => '点赞',
      'label' => '',
      'default' => true,
    ),
    array(
      'id'    => 'post_prevnext_s',
      'type'  => 'switcher',
      'title' => '上一篇和下一篇文章',
      'label' => '',
      'default' => true,
    ),
    array(
      'id'    => 'post_related_s',
      'type'  => 'switcher',
      'title' => '相关文章+',
      'label' => '',
      'default' => true,
    ),
    array(
      'id'      => 'post_related_style',
      'type'    => 'radio',
      'title'   => '相关文章：显示风格',
      'inline'  => true,
      'options' => array(
            'style_0' => '带缩略图',
            'style_1' => '简洁标题'
        ),
      'default'  => 'style_0',
      'dependency'  => array( 'post_related_s', '==', 'true' ),
    ),
    array(
      'id'    => 'related_title',
      'type'  => 'text',
      'title' => '相关文章：标题',
      'default' => '相关推荐',
      'dependency'  => array( 'post_related_s', '==', 'true' ),
    ),

    array(
      'id'      => 'post_related_n',
      'type'    => 'slider',
      'title'   => '相关文章：显示数量',
      'default' => 8,
    ),


    array(
      'id'    => 'post_share_s',
      'type'  => 'switcher',
      'title' => '文章 底部分享模块',
      'label' => '',
      'default' => true,
    ),
    array(
      'id'    => 'share_post_image_thumb',
      'type'  => 'switcher',
      'title' => '被分享时优先选择文章特色图像',
      'label' => '',
      'default' => true,
    ),
    array(
      'id'        => 'share_base_image',
      'type'      => 'media',
      'title'     => '被分享时的默认图片',
      'add_title' => '上传',
      'desc'      => '',
      'default'   => array('url' =>get_stylesheet_directory_uri() . '/img/logo.png'),
      'dependency'  => array( 'post_rewards_s', '==', 'true' ),
    ),


    
  )
) );



// 页面设置

CSF::createSection( $prefix, array(
  'title' => '页面设置',
  'icon'  => 'fa fa-credit-card',
  'fields'      => array(
    array(
      'id'    => 'is_login_popup',
      'type'  => 'switcher',
      'title' => '登录注册弹窗模式',
      'label' => '开启-仅在PC端，大屏幕有效，手机端默认页面模式',
      'default' => true,
    ),

    array(
      'id'        => 'login_bg_img',
      'type'      => 'media',
      'title'     => '登陆注册页面背景图片',
      'add_title' => '上传图片',
      'desc'      => '建议尺寸：1920*1080px',
      'default'      => array('url' =>'https://s2.ax1x.com/2019/02/17/ksXccT.jpg'),
    ),
    array(
      'id'    => 'is_write',
      'type'  => 'switcher',
      'title' => '简易投稿功能',
      'default' => false,
    ),
    
    array(
      'id'    => 'is_filters_cat',
      'type'  => 'switcher',
      'title' => '分类页面开启多功能筛选栏',
      'default' => true,
    ),
    array(
      'id'    => 'filters_cat_is',
      'type'  => 'switcher',
      'title' => '筛选--分类',
      'default' => true,
      'dependency'  => array( 'is_filters_cat', '==', 'true' ),
    ),
    array(
      'id'    => 'is_filters_tag_is',
      'type'  => 'switcher',
      'title' => '筛选--标签',
      'default' => true,
      'dependency'  => array( 'is_filters_cat', '==', 'true' ),
    ),
    array(
      'id'    => 'page_share_s',
      'type'  => 'switcher',
      'title' => '页面 底部分享模块',
      'label' => '',
      'default' => true,
    ),

    array(
      'id'    => 'page_like_s',
      'type'  => 'switcher',
      'title' => '页面点赞',
      'default' => true,
    ),

    array(
      'id'      => 'page_week_count',
      'type'    => 'slider',
      'title'   => '页面模板：7天热门显示文章数量',
      'default' => 50,
    ),
    array(
      'id'      => 'page_month_count',
      'type'    => 'slider',
      'title'   => '页面模板：30天热门显示文章数量',
      'default' => 50,
    ),
    array(
      'id'      => 'page_lieks_count',
      'type'    => 'slider',
      'title'   => '页面模板：点赞排行显示文章数量',
      'default' => 50,
    ), 
    array(
      'id'      => 'page_tags_count',
      'type'    => 'slider',
      'title'   => '页面模板：热门标签显示文章数量',
      'default' => 50,
    ),

  )
) );





//
// 商城设置
//
CSF::createSection( $prefix, array(
  'title'       => '商城设置',
  'icon'        => 'fa fa-shopping-cart',
  // 'description' => '商城详细设置',
  'fields'      => array(

    array(
      'type'    => 'subheading',
      'content' => '商城系统基本设置',
    ),
    array(
      'id'         => 'is_sened_paymail',
      'type'       => 'switcher',
      'title'      => '付款成功发送邮件订单消息',
      'label'       => '用户付款成功，订单消息将发送到用户邮箱，需要自行在辅助功能设置好（SMTP服务），免登录用户暂无法接收',
      'default'      => false,
    ),
    array(
      'id'         => 'is_down_rasmd5',
      'type'       => 'switcher',
      'title'      => '下载地址简单加密',
      'label'       => '简单加密也可防采集跳转，如有地址乱码等，可以开启此功能',
      'default'      => false,
    ),
    //
    // Dependency example 1
    array(
      'id'         => 'no_loginpay',
      'type'       => 'switcher',
      'title'      => '免登录下载',
      'label'       => '开启后只有登录才可以下载购买资源',
      'default'      => true,
    ),
    array(
      'id'         => 'pay_key',
      'type'       => 'text',
      'title'      => '免登录购买临时KEY',
      'default'      => 'rizhuti-key',
      'dependency' => array( 'no_loginpay', '==', 'true' ),
    ),
    array(
      'id'      => 'pay_days',
      'type'    => 'slider',
      'title'   => '免登录购买后有效期天数',
      'default' => 7,
      'dependency' => array( 'no_loginpay', '==', 'true' ),
    ),
    array(
      'id'         => 'rzt_down_downkey',
      'type'       => 'text',
      'title'      => '文件下载地址加密KEY',
      'default'      => 'rizhutidownkey',
    ),
    array(
      'type'    => 'subheading',
      'content' => 'VIP价格等详细设置设置',
    ),
    array(
      'id'            => 'vip_options',
      'type'          => 'accordion',
      'title'         => '价格和下载次数',
      'accordions'    => array(
        array(
          'title'     => '月费会员',
          'icon'      => 'fa fa-caret-square-o-down',
          'fields'    => array(
            array(
              'id'    => 'vip_price_31',
              'type'  => 'text',
              'title' => '会员价格',
              'default' => '5',
            ),
            array(
              'id'    => 'vip_price_31_downum',
              'type'  => 'text',
              'title' => '下载次数',
              'default' => '5',
            ),
          )
        ),
        array(
          'title'     => '年费会员',
          'icon'      => 'fa fa-caret-square-o-down',
          'fields'    => array(
            array(
              'id'    => 'vip_price_365',
              'type'  => 'text',
              'title' => '会员价格',
              'default' => '15',
            ),
            array(
              'id'    => 'vip_price_365_downum',
              'type'  => 'text',
              'title' => '下载次数',
              'default' => '10',
            ),
          )
        ),
        array(
          'title'     => '终身会员',
          'icon'      => 'fa fa-caret-square-o-down',
          'fields'    => array(
            array(
              'id'    => 'vip_price_3600',
              'type'  => 'text',
              'title' => '会员价格',
              'default' => '30',
            ),
            array(
              'id'    => 'vip_price_3600_downum',
              'type'  => 'text',
              'title' => '下载次数',
              'default' => '15',
            ),
          )
        ),

      )
    ),

    array(
      'type'    => 'subheading',
      'content' => '企业版支付配置，建议使用企业版接口，一劳永逸',
    ),
    array(
      'type'       => 'notice',
      'style'      => 'success',
      'content'    => '支付宝配置教程在会员群有文档!<br/>注意：扫码支付需要在支付宝商户签约当面付产品<br/>蚂蚁金服开放平台APPID https://open.alipay.com 账户中心->密钥管理->开放平台密钥，填写添加了电脑网站支付的应用的APPID',
      'dependency' => array( 'alpay', '==', 'true' ),
    ),
    array(
      'id'         => 'alpay',
      'type'       => 'switcher',
      'title'      => '支付宝-扫码支付',
      'label'       => '',
      'default'      => true,
    ),
    array(
      'id'         => 'alipay_appid',
      'type'       => 'text',
      'title'      => '支付宝应用appid',
      'desc'      => '蚂蚁金服开放平台APPID',
      'default'      => '',
      'dependency' => array( 'alpay', '==', 'true' ),
    ),
    array(
      'id'         => 'alipay_privatekey',
      'type'       => 'textarea',
      'title'      => '支付宝私钥privatekey',
      'desc'      => '商户私钥，填写对应签名算法类型的私钥',
      'default'      => '',
      'dependency' => array( 'alpay', '==', 'true' ),
    ),
    array(
      'id'         => 'alipay_publickey',
      'type'       => 'textarea',
      'title'      => '支付宝公钥publickey',
      'desc'      => '商户公钥，填写对应签名算法类型的公钥',
      'default'      => '',
      'dependency' => array( 'alpay', '==', 'true' ),
    ),
    // 微信支付
    array(
      'type'       => 'notice',
      'style'      => 'success',
      'content'    => '微信支付配置!比支付宝配置简单！<br/>注意：需要您拥有小程序或者公众号开通微信商户支付，商户不用填写回调链接！<br/>硬要填写的话：扫码回调链接：'.get_stylesheet_directory_uri() . '/shop/payment/weixin/notify.php',
      'dependency' => array( 'weixinpay', '==', 'true' ),
    ),
    array(
      'id'         => 'weixinpay',
      'type'       => 'switcher',
      'title'      => '微信-扫码支付',
      'label'       => '',
      'default'      => true,
    ),
    array(
      'id'         => 'weixinpay_mchid',
      'type'       => 'text',
      'title'      => '微信支付商户号',
      'desc'      => '微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送',
      'default'      => '',
      'dependency' => array( 'weixinpay', '==', 'true' ),
    ),
    array(
      'id'         => 'weixinpay_appid',
      'type'       => 'text',
      'title'      => '公众号或小程序APPID',
      'desc'      => '公众号APPID 通过微信支付商户资料审核后邮件发送',
      'default'      => '',
      'dependency' => array( 'weixinpay', '==', 'true' ),
    ),
    array(
      'id'         => 'weixinpay_apikey',
      'type'       => 'text',
      'title'      => '微信支付API密钥',
      'desc'      => '帐户设置-安全设置-API安全-API密钥-设置API密钥',
      'default'      => '',
      'dependency' => array( 'weixinpay', '==', 'true' ),
    ),


    array(
      'type'    => 'subheading',
      'content' => '免签约支付配置，该接口仅提供没有企业资质的用户使用<br/>使用免签约后,只能开启一个免签约，企业支付无法并用，只能走免签约通道，二选一选择，适合没有接口的用户,如果想换回企业支付，请关闭免签功能，然后保存刷新即可出现企业支付设置选项',
    ),
    array(
      'type'       => 'notice',
      'style'      => 'success',
      'content'    => '免签约接口全网没有好的，稳定的，水太深，这里使用的是（收款宝免签约），是会员们亲测很久推荐集成的<br/>收款宝免签约官网：https://codepay.zlkb.net',
      'dependency' => array( 'is_mianqian_skb', '==', 'true' ),
    ),
    array(
      'id'         => 'is_mianqian_skb',
      'type'       => 'switcher',
      'title'      => '收款宝免签约支付',
      'label'       => '前往https://codepay.zlkb.net登录查看',
      'default'      => false,
      'dependency' => array( 'is_mianqian_mzf', '==', 'false' ),
    ),
    array(
      'id'         => 'zlkb_appid',
      'type'       => 'text',
      'title'      => '收款宝AppID',
      'desc'      => '前往https://codepay.zlkb.net登录查看',
      'default'      => '',
      'dependency' => array( 'is_mianqian_skb', '==', 'true' ),
    ),
    array(
      'id'         => 'zlkb_secret',
      'type'       => 'text',
      'title'      => '收款宝AppSecret',
      'desc'      => '前往https://codepay.zlkb.net登录查看',
      'default'      => '',
      'dependency' => array( 'is_mianqian_skb', '==', 'true' ),
    ),

    // 码支付配置
    array(
      'type'       => 'notice',
      'style'      => 'success',
      'content'    => '码支付免签约官网：https://codepay.fateqq.com<br/>请在码支付后台云端设置设置通知地址为：'.get_stylesheet_directory_uri() . '/shop/payment/codepay/notify.php',
      'dependency' => array( 'is_mianqian_mzf', '==', 'true' ),
    ),
    array(
      'id'         => 'is_mianqian_mzf',
      'type'       => 'switcher',
      'title'      => '码支付免签约支付',
      'label'       => '前往https://codepay.fateqq.com登录查看',
      'default'      => false,
      'dependency' => array( 'is_mianqian_skb', '==', 'false' ),
    ),
    array(
      'id'         => 'mzf_appid',
      'type'       => 'text',
      'title'      => '码支付-ID',
      'desc'      => '必填-前往码支付云端设置查看',
      'default'      => '',
      'dependency' => array( 'is_mianqian_mzf', '==', 'true' ),
    ),
    array(
      'id'         => 'mzf_secret',
      'type'       => 'text',
      'title'      => '码支付-通信密钥',
      'desc'      => '必填-前往码支付云端设置查看',
      'default'      => '',
      'dependency' => array( 'is_mianqian_mzf', '==', 'true' ),
    ),
    array(
      'id'         => 'mzf_token',
      'type'       => 'text',
      'title'      => '码支付-token',
      'desc'      => '必填',
      'default'      => '',
      'dependency' => array( 'is_mianqian_mzf', '==', 'true' ),
    ),


  )
) );




//
// 社交登陆
//
CSF::createSection( $prefix, array(
  'title' => '社交登录',
  'icon'  => 'fa fa-paper-plane-o',
  'fields'      => array(
    array(
      'id'    => 'is_email_reg',
      'type'  => 'switcher',
      'title' => '注册需要邮件验证码',
      'default' => false,
    ),
    array(
      'type'    => 'subheading',
      'content' => 'QQ社交登录需要备案域名和自行申请接口，申请地址：https://connect.qq.com/<br/>',
    ),
    array(
      'id'    => 'is_oauth_qq',
      'type'  => 'switcher',
      'title' => 'QQ社交登录+',
      'label' => '',
      'default' => false,
    ),
    array(
      'type'       => 'notice',
      'style'      => 'success',
      'content'    => '回调地址为：'.get_stylesheet_directory_uri() . '/oauth/qq/callback.php',
      'dependency' => array( 'is_oauth_qq', '==', 'true' ),
    ),
    array(
      'id'    => 'oauth_qqid',
      'type'  => 'text',
      'title' => 'QQ id',
      'default' => '',
      'dependency'  => array( 'is_oauth_qq', '==', 'true' ),
    ),
    array(
      'id'    => 'oauth_qqkey',
      'type'  => 'text',
      'title' => 'QQ key',
      'default' => '',
      'dependency'  => array( 'is_oauth_qq', '==', 'true' ),
    ),

  )
) );


//
// 广告设置
//
CSF::createSection( $prefix, array(
  'title' => '广告设置',
  'icon'  => 'fa fa-legal',
  'fields'      => array(

    array(
      'id'    => 'ad_list_header_s',
      'type'  => 'switcher',
      'title' => '列表头部',
      'label' => '',
      'default' => false,
    ),
    array(
      'id'       => 'ad_list_header',
      'type'     => 'code_editor',
      'title'    => '广告代码',
      'subtitle' => '广告HTML代码',
      'settings' => array(
        'theme'  => 'dracula',
        'mode'   => 'html',
      ),
      'default' =>'', 
      'dependency'  => array( 'ad_list_header_s', '==', 'true' ),
    ),

    array(
      'id'    => 'ad_list_footer_s',
      'type'  => 'switcher',
      'title' => '列表底部',
      'label' => '',
      'default' => false,
    ),
    array(
      'id'       => 'ad_list_footer',
      'type'     => 'code_editor',
      'title'    => '广告代码',
      'subtitle' => '广告HTML代码',
      'settings' => array(
        'theme'  => 'dracula',
        'mode'   => 'html',
      ),
      'default' =>'', 
      'dependency'  => array( 'ad_list_footer_s', '==', 'true' ),
    ),

    array(
      'id'    => 'ad_post_header_s',
      'type'  => 'switcher',
      'title' => '文章内容上',
      'label' => '',
      'default' => false,
    ),
    array(
      'id'       => 'ad_post_header',
      'type'     => 'code_editor',
      'title'    => '广告代码',
      'subtitle' => '广告HTML代码',
      'settings' => array(
        'theme'  => 'dracula',
        'mode'   => 'html',
      ),
      'default' =>'', 
      'dependency'  => array( 'ad_post_header_s', '==', 'true' ),
    ),

     array(
      'id'    => 'ad_post_footer_s',
      'type'  => 'switcher',
      'title' => '文章内容下',
      'label' => '',
      'default' => false,
    ),
    array(
      'id'       => 'ad_post_footer',
      'type'     => 'code_editor',
      'title'    => '广告代码',
      'subtitle' => '广告HTML代码',
      'settings' => array(
        'theme'  => 'dracula',
        'mode'   => 'html',
      ),
      'default' =>'', 
      'dependency'  => array( 'ad_post_footer_s', '==', 'true' ),
    ),


  )
) );


//
// SMTP设置
//
CSF::createSection( $prefix, array(
  'title'       => 'SMTP设置',
  'icon'        => 'fa fa-envelope',
  'description' => 'SMTP设置可以解决wordpress无法发送邮件问题，建议用QQ邮箱，注意QQ邮箱的密码是独立密码。不是QQ密码！',
  'fields'      => array(

    array(
      'id'    => 'mail_smtps',
      'type'  => 'switcher',
      'title' => '是否启用SMTP服务',
      'label' => '该设置主题自带，不能与插件重复开启',
      'default' => false,
    ),
    array(
      'id'       => 'mail_name',
      'type'     => 'text',
      'title'    => '发信邮箱',
      'subtitle' => '请填写发件人邮箱帐号',
      'default'  => '88888888@qq.com',
      'validate' => 'csf_validate_email',
    ),

    array(
      'id'       => 'mail_host',
      'type'     => 'text',
      'title'    => '邮件服务器',
      'subtitle' => '请填写SMTP服务器地址',
      'default'  => 'smtp.qq.com',
    ),
    array(
      'id'       => 'mail_port',
      'type'     => 'text',
      'title'    => '服务器端口',
      'subtitle' => '请填写SMTP服务器端口',
      'default'  => '465',
    ),
    array(
      'id'       => 'mail_passwd',
      'type'     => 'text',
      'title'    => '邮箱密码',
      'subtitle' => '请填写SMTP服务器邮箱密码',
      'default'  => '88888888',
    ),
    array(
      'id'    => 'mail_smtpauth',
      'type'  => 'switcher',
      'title' => '启用SMTPAuth服务',
      'label' => '是否启用SMTPAuth服务',
      'default' => true,
    ),
    array(
      'id'       => 'mail_smtpsecure',
      'type'     => 'text',
      'title'    => 'SMTPSecure设置',
      'subtitle' => '若启用SMTPAuth服务则填写ssl，若不启用则留空',
      'default'  => 'ssl',
    ),

    

  )
) );

//
// 底部设置
//
CSF::createSection( $prefix, array(
  'title' => '底部设置',
  'icon'  => 'fa fa-window-minimize',
  'description'  => '底部模块详细设置。顺序从左到右',
  'fields'      => array(
     array(
      'id'    => 'footer_moble_is',
      'type'  => 'switcher',
      'title' => '手机端使用精简底部',
      'label' => '',
      'default' => true,
    ),
    array(
      'id'     => 'footer_mod_1',
      'type'   => 'fieldset',
      'title'  => '底部模块1',
      'fields' => array(
        array(
          'type'    => 'subheading',
          'content' => '底部模块1',
        ),
        array(
          'id'      => '_desc',
          'type'    => 'textarea',
          'title'   => 'LOGO下方文字内容',
          'default' => '如少女般纯洁，干净，无需任何插件，极度优化，支持支付宝，微信付款，具备完善的设置选项。',
        ),
        

      ),
    ),

    array(
      'id'     => 'footer_mod_2',
      'type'   => 'fieldset',
      'title'  => '底部模块2',
      'fields' => array(
        array(
          'type'    => 'subheading',
          'content' => '底部模块2',
        ),
        array(
          'id'      => '_title',
          'type'    => 'text',
          'title'   => '主标题',
          'default' => '底部链接',
        ),
        array(
          'id'      => '_hrefneme_1',
          'type'    => 'text',
          'title'   => '名称—1',
          'default' => '链接-1',
        ),
        array(
          'id'      => '_href_1',
          'type'    => 'text',
          'title'   => '链接地址-1',
          'default' => '#',
        ),
        array(
          'id'      => '_hrefneme_2',
          'type'    => 'text',
          'title'   => '链接名称—2',
          'default' => '链接-2',
        ),
        array(
          'id'      => '_href_2',
          'type'    => 'text',
          'title'   => '链接地址-2',
          'default' => '#',
        ),
        array(
          'id'      => '_hrefneme_3',
          'type'    => 'text',
          'title'   => '链接名称—3',
          'default' => '链接-3',
        ),
        array(
          'id'      => '_href_3',
          'type'    => 'text',
          'title'   => '链接地址-3',
          'default' => '#',
        ),
        
        
      ),
    ),

    array(
      'id'     => 'footer_mod_3',
      'type'   => 'fieldset',
      'title'  => '底部模块3',
      'fields' => array(
        array(
          'type'    => 'subheading',
          'content' => '底部模块3',
        ),
        array(
          'id'      => '_title',
          'type'    => 'text',
          'title'   => '主标题',
          'default' => '底部链接',
        ),
        array(
          'id'      => '_hrefneme_1',
          'type'    => 'text',
          'title'   => '名称—1',
          'default' => '链接-1',
        ),
        array(
          'id'      => '_href_1',
          'type'    => 'text',
          'title'   => '链接地址-1',
          'default' => '#',
        ),
        array(
          'id'      => '_hrefneme_2',
          'type'    => 'text',
          'title'   => '链接名称—2',
          'default' => '链接-2',
        ),
        array(
          'id'      => '_href_2',
          'type'    => 'text',
          'title'   => '链接地址-2',
          'default' => '#',
        ),
        array(
          'id'      => '_hrefneme_3',
          'type'    => 'text',
          'title'   => '链接名称—3',
          'default' => '链接-3',
        ),
        array(
          'id'      => '_href_3',
          'type'    => 'text',
          'title'   => '链接地址-3',
          'default' => '#',
        ),
        
        
      ),
    ),

    array(
      'id'     => 'footer_mod_4',
      'type'   => 'fieldset',
      'title'  => '底部模块4-5',
      'fields' => array(
        array(
          'type'    => 'subheading',
          'content' => '底部模块4-5',
        ),
        array(
          'id'      => '_wx_title',
          'type'    => 'text',
          'title'   => '标题',
          'default' => '官方微信',
        ),
        array(
          'id'        => 'wx_img',
          'type'      => 'media',
          'title'     => '二维码',
          'add_title' => '上传二维码',
          'desc'      => '图片建议尺寸统一：'.'300*300',
          'default'      => array('url' =>'https://s2.ax1x.com/2019/02/17/ksXang.png'),
        ),
        array(
          'id'      => '_ali_title',
          'type'    => 'text',
          'title'   => '标题',
          'default' => '官方支付宝',
        ),
        array(
          'id'        => 'ali_img',
          'type'      => 'media',
          'title'     => '二维码',
          'add_title' => '上传二维码',
          'desc'      => '图片建议尺寸统一：'.'300*300',
          'default'      => array('url' =>'https://s2.ax1x.com/2019/02/17/ksXang.png'),
        ),
      ),
    ),

    array(
      'id'    => 'footer_by_info',
      'type'  => 'switcher',
      'title' => '主题版权信息',
      'label' => '启用显示theme by rizhuti',
      'default' => true,
    ),

    array(
      'id'       => 'web_js',
      'type'     => 'code_editor',
      'title'    => '网站底部自定义JS代码',
      'subtitle' => '位于底部，用于添加第三方流量数据统计代码，如：Google analytics、百度统计、CNZZ不用添加<strong>&lt;script></strong>标签',
      'settings' => array(
        'theme'  => 'dracula',
        'mode'   => 'javascript',
      ),
      'default' =>'', 
    ),


  )
) );





//
// Others
//
CSF::createSection( $prefix, array(
  'title'       => '其他设置',
  'icon'        => 'fa fa-bolt',
  'description' => '内置WordPress优化功能，这会另您获得更好的体验！',
  'fields'      => array(
    
    array(
      'id'    => 'display_errors',
      'type'  => 'switcher',
      'title' => '开启调试模式',
      'default' => false,
    ),
    array(
      'id'    => 'display_wp_update',
      'type'  => 'switcher',
      'title' => '禁用WP更新提示',
      'default' => true,
    ),
    array(
      'id'    => 'disabled_block_editor',
      'type'  => 'switcher',
      'title' => '禁用WP5.0+ 古滕堡反人类编辑器',
      'desc' => '建议关闭，使用传统编辑器',
      'default' => true,
    ),
    array(
      'id'    => 'disabled_open_sans',
      'type'  => 'switcher',
      'title' => '禁止后台加载谷歌字体,提高wp后台速度',
      'default' => true,
    ),
    array(
      'id'    => 'disabled_of_theme_meta',
      'type'  => 'switcher',
      'title' => '清除wordpress自带的meta标签',
      'default' => true,
    ),
    array(
      'id'    => 'disabled_pingback_ping',
      'type'  => 'switcher',
      'title' => '防pingback攻击',
      'default' => true,
    ),
    array(
      'id'    => '_get_ssl2_avatar',
      'type'  => 'switcher',
      'title' => 'SSL Gravatar 官方头像https加速',
      'default' => true,
    ),
    array(
      'id'    => 'disabled_emoji',
      'type'  => 'switcher',
      'title' => '禁用wp自带Emoj表情',
      'default' => true,
    ),
    array(
      'id'    => '_jpeg_quality',
      'type'  => 'switcher',
      'title' => '全站图片100%质量输出显示',
      'default' => true,
    ),
    array(
      'id'    => '_new_filename',
      'type'  => 'switcher',
      'title' => '上传图片重命名',
      'desc' => '可解决wp上传中文名字图片无法显示问题',
      'default' => true,
    ),
    array(
      'id'    => 'hide_admin_bar',
      'type'  => 'switcher',
      'title' => '前台隐藏wp自带顶部黑条',
      'default' => true,
    ),

  )
) );


//
// Field: backup
//
CSF::createSection( $prefix, array(
  'title'       => '备份恢复',
  'icon'        => 'fa fa-shield',
  'description' => '备份-恢复您的主题设置，方便迁移快速复刻网站</a>',
  'fields'      => array(

    array(
      'type' => 'backup',
    ),

  )
) );



//
// Field: about
//
$rizhuti_copyright = '<li>本主题官方正版地址： <a target="_bank" href="https://rizhuti.com/">https://rizhuti.com/</a> </li>';
$rizhuti_copyright .= '<li>日主题不反对盗版用户使用，喜欢就好，盗版用户不供任何支持。</li>';
$rizhuti_copyright .= '<li>本类主题属于支付+资源+会员，安全问题比较重要，盗版用户自行斟酌。</li>';
$rizhuti_copyright .= '<li>作为一款小众且实用的主题，虽然很多地方不足，但是我们有良好的会员群一起督促完善本主题。</li>';
$rizhuti_copyright .= '<li>本主题干净极致，无需任何插件，无加密，无授权。完美适合WordPress虚拟资源分享下载站或者其他的素材资源站点</li>';
$rizhuti_copyright .= '<li>如果你觉得确实帮到了您，您可以购买一份正版加入尊贵璀璨至尊无敌VIP 用的安全放心 日的为所欲为。</li>';
$rizhuti_copyright .= '<li>凡不是和作者QQ200933220购买，不在日主题的会员群用户，全部为盗版！</li>';
$rizhuti_copyright .= '<li>对作者的支持就是提供更新和发布更好作品的动力！</li>';
$rizhuti_copyright .= '<a class="button button-primary" target="_blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=200933220&amp;site=qq&amp;menu=yes">去支持正版</a>';

CSF::createSection( $prefix, array(
  'title'       => '关于授权',
  'icon'        => 'fa fa-handshake-o',
  'description' => '<i class="fa fa-heart" style=" color: red; "></i> 感谢您使用本主题进行创作，不管您是不是从本主题官方渠道购买的正版，但我们感谢您的支持肯定和宠爱。<i class="fa fa-heart" style=" color: red; "></i>',
  'fields'      => array(

    array(
      'type'       => 'notice',
      'style'      => 'success',
      'content'    => $rizhuti_copyright,
    ),
    array(
      'type'       => 'notice',
      'style'      => 'warning',
      'content'    => '关于有个别群、盗版用户、不管是出于何种目的，自己用就用了，用了还在那骂日主题作者沙雕，不加密不授权做公益，加密了自己又跟狗一样到处求破解版，网络喷子无处不在，请自重！<br/>如果觉得辣鸡，就网开一面不要践踏我们，加密授权对用户的使用体验是一种损耗，也不方便用户自己修改二开。就算加密授权又能如何，支持的人自然会支持，不支持的人，随便去找人解密了，孰轻孰重孰能忍，最大的受益者永远是用户！',
    ),

  )
) );