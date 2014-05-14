<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

include_once(t3lib_extMgm::extPath('generic_gallery') . 'pi1/class.tx_genericgallery_pi1_cms_layout.php');

t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key,pages';


t3lib_extMgm::addPlugin(array(
	'LLL:EXT:generic_gallery/locallang_db.xml:tt_content.list_type_pi1',
	$_EXTKEY . '_pi1',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
),'list_type');


t3lib_extMgm::addStaticFile($_EXTKEY,'res/ts/','Generic Gallery demos');

if (TYPO3_MODE == 'BE') {
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_genericgallery_pi1_wizicon'] = t3lib_extMgm::extPath($_EXTKEY).'pi1/class.tx_genericgallery_pi1_wizicon.php';
}

$TCA['tx_generic_gallery_pictures'] = array (
	'ctrl' => array (
		'title'    			=> 'LLL:EXT:generic_gallery/locallang_db.xml:generic_gallery_pictures',		
		'label'     		=> 'title',	
		'label_userFunc'	=> 'tx_genericgallery_cms_layout->getPictureTitle',	
		'tstamp'    		=> 'tstamp',
		'crdate'    		=> 'crdate',
		'cruser_id' 		=> 'cruser_id',
		'sortby' 			=> 'sorting',	
		'delete' 			=> 'deleted',	
		'enablecolumns' 	=> array (		
			'disabled' 			=> 'hidden',	
			'starttime' 		=> 'starttime',	
			'endtime' 			=> 'endtime',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_genericgallery_pictures.gif',
		'searchFields' => 'title,link',
	),
);

$TCA['tx_generic_gallery_content'] = array (
	'ctrl' => array (
		'title'     		=> 'LLL:EXT:generic_gallery/locallang_db.xml:generic_gallery_content',		
		'label'     		=> 'bodytext',
		'tstamp'   			=> 'tstamp',
		'crdate'    		=> 'crdate',
		'cruser_id' 		=> 'cruser_id',
		'default_sortby' 	=> 'ORDER BY crdate',	
		'delete' 			=> 'deleted',	
		'enablecolumns' 	=> array (		
			'disabled' 			=> 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_genericgallery_content.gif',
		'searchFields' => 'bodytext',
	),
);

$tempColumns = array (
	'tx_generic_gallery_items' => Array (		
		'exclude' => 1,		
		'label' => 'LLL:EXT:generic_gallery/locallang_db.xml:generic_gallery_items',		
		'config' => Array (
			'type' => 'inline',
			'foreign_table' => 'tx_generic_gallery_pictures',
			'foreign_field' => 'tt_content_id',
			'appearance' => Array (
				'useSortable' => 1,
				'collapseAll' => 1,
				'expandSingle' => 1,
			),
			'maxitems' => 5000,
		)
	),
	'tx_generic_gallery_predefined' => Array (		
		'exclude' => 1,
		'label' => 'LLL:EXT:generic_gallery/locallang_db.xml:generic_gallery_predefined',
		'config' => Array (
			'type' => 'select',
			'allowNonIdValues' => 1,
			'itemsProcFunc' => 'tx_genericgallery_addFields->addFields_predefined',
			'size' => 1,
			'minitems' => 0,
			'maxitems' => 1,
		)
	),	
//	'tx_generic_gallery_images' => txdam_getMediaTCA('image_field','tx_generic_gallery_picture_single'),
);


$tempColumns["tx_generic_gallery_images"]["exclude"] = 1;
$tempColumns["tx_generic_gallery_images"]["label"] = "LLL:EXT:generic_gallery/locallang_db.xml:generic_gallery_images";
$tempColumns["tx_generic_gallery_images"]["config"]["maxitems"] = 1000;
$tempColumns["tx_generic_gallery_images"]["config"]["size"] = 20;
$tempColumns["tx_generic_gallery_images"]["config"]["allowed_types"] = "jpg,gif,jpeg,png";
$tempColumns["tx_generic_gallery_images"]["config"]["disallowed_types"] = "" ;


// add field to tt_content so we can use irre within our plugin
t3lib_div::loadTCA('tt_content');
t3lib_extMgm::addTCAcolumns('tt_content',$tempColumns,1);
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1'] = 'tx_generic_gallery_predefined,tx_generic_gallery_items,tx_generic_gallery_images';

// enable usage of our entries within a standard page
t3lib_extMgm::allowTableOnStandardPages('tx_generic_gallery_pictures');
t3lib_extMgm::allowTableOnStandardPages('tx_generic_gallery_content');

?>