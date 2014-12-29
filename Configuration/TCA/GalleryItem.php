<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$GLOBALS['TCA']['tx_generic_gallery_pictures'] = array(
	'ctrl' => $GLOBALS['TCA']['tx_generic_gallery_pictures']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden, title, link, images, text_items',
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, title;;2;;1-1-1, link, images')
	),
	'palettes' => array(
		'1' => array('showitem' => 'starttime, endtime'),
		'2' => array('showitem' => 'contents')
	),
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),

		'title' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:gg_extbase/Resources/Private/Language/locallang_db.xlf:tx_ggextbase_domain_model_galleryitem.title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'link' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:gg_extbase/Resources/Private/Language/locallang_db.xlf:tx_ggextbase_domain_model_galleryitem.link',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim',
				'wizards' => array(
					'_PADDING' => 2,
					'link' => array(
						'type' => 'popup',
						'title' => 'Link',
						'icon' => 'link_popup.gif',
						'script' => 'browse_links.php?mode=wizard',
						'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
					)
				)
			),
		),
		'images' => array(
			'label' => 'LLL:EXT:gg_extbase/Resources/Private/Language/locallang_db.xlf:tx_ggextbase_domain_model_galleryitem.images',
			'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
				'tx_generic_gallery_picture_single',
				array(
					'size' => 1,
					'maxitems' => 1,
					'minitems' => 1,
				),
				$GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
			),
		),
		'contents' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:gg_extbase/Resources/Private/Language/locallang_db.xlf:tx_ggextbase_domain_model_galleryitem.text_items',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_generic_gallery_content',
				'foreign_field' => 'pictures_id',
				'minitems' => 0,
				'maxitems' => 99,
				'appearance' => array(
					'useSortable' => 1,
					'collapseAll' => 1,
					'expandSingle' => 1,
				),
			),
		),
	),
);
