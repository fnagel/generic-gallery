<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$GLOBALS['TCA']['tx_generic_gallery_content'] = array(
	'ctrl' => $GLOBALS['TCA']['tx_generic_gallery_content']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden, bodytext, position, width',
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, bodytext;;2;;1-1-1, ')
	),
	'palettes' => array(
		'1' => array('showitem' => 'starttime, endtime'),
		'2' => array('showitem' => 'position, width')
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

		'bodytext' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:gg_extbase/Resources/Private/Language/locallang_db.xlf:tx_ggextbase_domain_model_textitem.bodytext',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'defaultExtras' => 'richtext[*]:rte_transform[flag=rte_enabled|mode=ts]',
			),
		),
		'position' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:gg_extbase/Resources/Private/Language/locallang_db.xlf:tx_ggextbase_domain_model_textitem.position',
			'config' => array(
				'type' => 'input',
				'size' => 10,
				'eval' => 'trim',
				'default' => '0,0',
			),
		),
		'width' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:gg_extbase/Resources/Private/Language/locallang_db.xlf:tx_ggextbase_domain_model_textitem.width',
			'config' => array(
				'type' => 'input',
				'size' => 10,
				'eval' => 'trim',
				'default' => '0',
			),
		),

	),
);
