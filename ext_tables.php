<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

$extensionName = \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToLowerCamelCase($_EXTKEY);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Gallery',
	'Gallery'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
	$_EXTKEY, 'Configuration/TypoScript', 'Generic Gallery Extbase'
);


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
	'tx_ggextbase_domain_model_gallerycollection',
	'EXT:gg_extbase/Resources/Private/Language/locallang_csh_tx_ggextbase_domain_model_gallerycollection.xlf'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_ggextbase_domain_model_gallerycollection');
$GLOBALS['TCA']['tx_ggextbase_domain_model_gallerycollection'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:gg_extbase/Resources/Private/Language/locallang_db.xlf:tx_ggextbase_domain_model_gallerycollection',
		'label' => 'uid',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => '',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) .
			'Configuration/TCA/GalleryCollection.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) .
			'Resources/Public/Icons/tx_ggextbase_domain_model_gallerycollection.gif'
	),
);


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
	'tx_ggextbase_domain_model_galleryitem',
	'EXT:gg_extbase/Resources/Private/Language/locallang_csh_tx_ggextbase_domain_model_galleryitem.xlf'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_ggextbase_domain_model_galleryitem');
$GLOBALS['TCA']['tx_ggextbase_domain_model_galleryitem'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:gg_extbase/Resources/Private/Language/locallang_db.xlf:tx_ggextbase_domain_model_galleryitem',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'title,link,image,text_items,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) .
			'Configuration/TCA/GalleryItem.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) .
			'Resources/Public/Icons/tx_ggextbase_domain_model_galleryitem.gif'
	),
);


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
	'tx_ggextbase_domain_model_textitem',
	'EXT:gg_extbase/Resources/Private/Language/locallang_csh_tx_ggextbase_domain_model_textitem.xlf'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_ggextbase_domain_model_textitem');
$GLOBALS['TCA']['tx_ggextbase_domain_model_textitem'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:gg_extbase/Resources/Private/Language/locallang_db.xlf:tx_ggextbase_domain_model_textitem',
		'label' => 'bodytext',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'bodytext,position,width,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) .
			'Configuration/TCA/TextItem.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) .
			'Resources/Public/Icons/tx_ggextbase_domain_model_textitem.gif'
	),
);


$tempColumns = array(
	// gallery type
	'tx_generic_gallery_predefined' => Array(
		'exclude' => 1,
		'label' => 'LLL:EXT:generic_gallery/locallang_db.xml:generic_gallery_predefined',
		'config' => Array(
			'type' => 'select',
			'allowNonIdValues' => 1,
			'itemsProcFunc' => 'tx_genericgallery_addFields->addFields_predefined',
			'size' => 1,
			'minitems' => 0,
			'maxitems' => 1,
		)
	),

	// single items
	'tx_generic_gallery_items' => Array(
		'exclude' => 1,
		'label' => 'LLL:EXT:generic_gallery/locallang_db.xml:generic_gallery_items',
		'config' => Array(
			'type' => 'inline',
			'foreign_table' => 'tx_generic_gallery_pictures',
			'foreign_field' => 'tt_content_id',
			'appearance' => Array(
				'useSortable' => 1,
				'collapseAll' => 1,
				'expandSingle' => 1,
			),
			'maxitems' => 5000,
		)
	),

	// file reference
	'tx_generic_gallery_images' => Array(
		'exclude' => 1,
		'label' => 'LLL:EXT:generic_gallery/locallang_db.xml:generic_gallery_images',
		'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
			'tx_generic_gallery_picture_single',
			array(
				'size' => 20,
				'maxitems' => 1000,
				'minitems' => 0,
				'autoSizeMax' => 40,
			),
			'jpg,gif,jpeg,png'
		)
	),

	// collection reference
	'tx_generic_gallery_collection' => Array(
		'exclude' => 1,
		'label' => 'Collection',
		'config' => Array(
			'type' => 'inline',
			'foreign_table' => 'sys_file_collection',
			'foreign_field' => 'uid',
			'appearance' => Array(
				'useSortable' => 1,
				'collapseAll' => 1,
				'expandSingle' => 1,
			),
			'maxitems' => 1,
		)
	),
);

// add field to tt_content
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns, 1);
$TCA['tt_content']['types']['list']['subtypes_addlist'][strtolower($extensionName) . '_gallery'] =
	'tx_generic_gallery_predefined,tx_generic_gallery_items,tx_generic_gallery_images,tx_generic_gallery_collection';
