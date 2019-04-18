<?php
//
// Metabox of the POST
// Set a unique slug-like ID
//
$prefix_post_opts = 'wppay-postmeta-box';

//
// Create a metabox
//
CSF::createMetabox( $prefix_post_opts, array(
  'title'        => '付费资源设置-NEW',
  'post_type'    => 'post',
  'show_restore' => true,
  'data_type' => 'unserialize',
  'priority' => 'high',
) );

//
// Create a section
//
CSF::createSection( $prefix_post_opts, array(
  'fields' => array(

  	array(
      'id'      => 'wppay_type',
      'type'    => 'select',
      'title'   => '资源类型',
      'inline'  => true,
      'options' => array(
	        '0' => '不启用',
	        '1' => '全部内容',
	        '2' => '部分内容（利用短代码[wppay]隐藏内容[/wppay]）',
	        '3' => '收费下载',
			'4' => '免费下载',
      ),
      'default' => 0,
    ),

    array(
      'id'      => 'wppay_vip_auth',
      'type'    => 'select',
      'title'   => '会员权限',
      'subtitle'   => '权限关系是包含关系，终身可查看年月',
      'inline'  => true,
      'options' => array(
	        '0' => '不启用',
            '1' => '月费-会员免费',
            '2' => '年费-会员免费',
            '3' => '终身-会员免费',
      ),
      'default' => 0,
    ),
    // array(
    //   'id'    => 'wppay_pay_auth',
    //   'type'  => 'switcher',
    //   'title' => '会员专属',
    //   'label' => '开启后，资源仅限VIP购买或者查看！',
    //   'default' => false,
    // ),

    array(
      'id'    => 'wppay_price',
      'type'  => 'text',
      'title' => '收费价格',
      'subtitle'   => '请输入数字',
      'default'   => '0.1',
      'validate' => 'csf_validate_numeric',
    ),
    array(
      'id'    => 'wppay_down',
      'type'  => 'text',
      'title' => '下载地址',
      'subtitle'   => '支持https:,thunder:,magnet:,ed2k 开头地址',
      'default'   => 'https://rizhuti.com/',
    ),
    array(
      'id'    => 'wppay_down_info',
      'type'  => 'text',
      'title' => '下载密码',
      'subtitle'   => '例：Eq76,为空则无密码',
      'default'   => '',
    ),
    array(
      'id'         => 'wppay_depend_info',
      'type'       => 'switcher',
      'label' => '设置文件详细信息等！',
      'title'      => '其他信息',
    ),
    array(
      'id'    => 'wppay_demourl',
      'type'  => 'text',
      'title' => '演示地址',
      'subtitle'   => '为空则不显示',
      'default'   => '',
      'dependency'  => array( 'wppay_depend_info', '==', 'true' ),
    ),
    array(
      'id'    => 'wppay_info_v',
      'type'  => 'text',
      'title' => '当前版本',
      'subtitle'   => '例：V1.0.2,为空则不显示',
      'default'   => '',
      'dependency'  => array( 'wppay_depend_info', '==', 'true' ),
    ),
    array(
      'id'    => 'wppay_info_g',
      'type'  => 'text',
      'title' => '文件格式',
      'subtitle'   => '例：zip,为空则不显示',
      'default'   => '',
      'dependency'  => array( 'wppay_depend_info', '==', 'true' ),
    ),
    array(
      'id'    => 'wppay_info_d',
      'type'  => 'text',
      'title' => '文件大小',
      'subtitle'   => '例：1.3MB,为空则不显示',
      'default'   => '',
      'dependency'  => array( 'wppay_depend_info', '==', 'true' ),
    ),
    // array(
    //   'id'         => 'wppay_form_is',
    //   'type'       => 'switcher',
    //   'label' => '购买时需要填写基本联系信息',
    //   'title'      => '购买者信息',
    //   'dependency'  => array( 'wppay_type', '==', '3' ),
    // ),

  )
) );

//
// Metabox of the PAGE and POST both.
// Set a unique slug-like ID
//
$prefix_meta_opts = '_prefix_meta_options';

//
// Create a metabox
//
CSF::createMetabox( $prefix_meta_opts, array(
  'title'     => '文章顶部背景图',
  'post_type' => array( 'post'),
  'context'   => 'side',
  'data_type' => 'unserialize',
) );

//
// Create a section
//
CSF::createSection( $prefix_meta_opts, array(
  'fields' => array(
    array(
      'id'        => 'single_header_img',
      'type'      => 'media',
      'desc'      => '图片建议尺寸统一：'.'1920*600,不设置则自动使用缩略图',
    ),
  )
) );
