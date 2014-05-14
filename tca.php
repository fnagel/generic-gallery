<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_generic_gallery_pictures'] = array (
	'ctrl' => $TCA['tx_generic_gallery_pictures']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'hidden,starttime,endtime,tt_content_id,images'
	),
	'feInterface' => $TCA['tx_generic_gallery_pictures']['feInterface'],
	'columns' => array (
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'starttime' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'default'  => '0',
				'checkbox' => '0'
			)
		),
		'endtime' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'checkbox' => '0',
				'default'  => '0',
				'range'    => array (
					'upper' => mktime(3, 14, 7, 1, 19, 2038),
					// 'lower' => mktime(0, 0, 0, date('m')-1, date('d'), date('Y'))
					'lower' => 0
				)
			)
		),
		'tt_content_id' => Array (		
			'config' => Array (
				'type' => 'passthrough',
			)
		),		
		'title' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:generic_gallery/locallang_db.xml:generic_gallery_pictures.title',				
			'config' => array (
				'type'     => 'input',
				'size'     => '30',
				'max'      => '255',
				'checkbox' => '',
				'eval'     => 'trim',
			)
		),	
		'link' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:generic_gallery/locallang_db.xml:generic_gallery_pictures.link',		
			'config' => array (
				'type'     => 'input',
				'size'     => '15',
				'max'      => '255',
				'checkbox' => '',
				'eval'     => 'trim',
				'wizards'  => array(
					'_PADDING' => 2,
					'link'     => array(
						'type'         => 'popup',
						'title'        => 'Link',
						'icon'         => 'link_popup.gif',
						'script'       => 'browse_links.php?mode=wizard',
						'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
					)
				)
			)
		),
//		'images' => txdam_getMediaTCA('image_field','tx_generic_gallery_picture_single'),
		'contents' => Array (			
			'exclude' => 1,		
			'label' => 'LLL:EXT:generic_gallery/locallang_db.xml:generic_gallery_pictures.contents',		
			'config' => Array (
				'type' => 'inline',
				'foreign_table' => 'tx_generic_gallery_content',
				'foreign_field' => 'pictures_id',
				'appearance' => Array (
					'useSortable' => 1,
					'collapseAll' => 1,
					'expandSingle' => 1,
				),
				'maxitems' => 99,
			)
		),	
	),	
	'types' => array (
		'0' => array('showitem' => 'hidden;;1;;1-1-1, title;;2;;1-1-1, link, images')
	),
	'palettes' => array (
		'1' => array('showitem' => 'starttime, endtime'),
		'2' => array('showitem' => 'contents')
	)
);



$TCA['tx_generic_gallery_content'] = array (
	'ctrl' => $TCA['tx_generic_gallery_content']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'hidden,bodytext,position'
	),
	'feInterface' => $TCA['tx_generic_gallery_content']['feInterface'],
	'columns' => array (
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'bodytext' => Array (
			'exclude' => 1,		
			'label' => 'LLL:EXT:generic_gallery/locallang_db.xml:generic_gallery_content.bodytext',	
			'config' => Array (
				'type' => 'text',
				'cols' => '48',
				'rows' => '5',
			),
			'defaultExtras' => 'richtext[*]:rte_transform[flag=rte_enabled|mode=ts]',
		),
		'position' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:generic_gallery/locallang_db.xml:generic_gallery_content.position',		
			'config' => Array (
				'type' => 'input',
				'size' => '10',
				'default' => '0,0',
			)
		),
		'width' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:generic_gallery/locallang_db.xml:generic_gallery_content.width',		
			'config' => Array (
				'type' => 'input',	
				'size' => '10',
				'default' => '0',
			)
		)
	),
	'types' => array (
		'0' => array('showitem' => 'hidden;;1;;1-1-1, bodytext')
	),
	'palettes' => array (
		'1' => array('showitem' => 'position, width')
	)
);


// change DAM default config
//$TCA["tx_generic_gallery_pictures"]["columns"]["images"]["label"] = "LLL:EXT:generic_gallery/locallang_db.xml:generic_gallery_pictures.images";
//$TCA["tx_generic_gallery_pictures"]["columns"]["images"]["config"]["minitems"] = 1;
//$TCA["tx_generic_gallery_pictures"]["columns"]["images"]["config"]["maxitems"] = 1;
//$TCA["tx_generic_gallery_pictures"]["columns"]["images"]["config"]["size"] = 1;
//$TCA["tx_generic_gallery_pictures"]["columns"]["images"]["config"]["allowed_types"] = "jpg,gif,jpeg,png";
//$TCA["tx_generic_gallery_pictures"]["columns"]["images"]["config"]["disallowed_types"] = "" ;


?>