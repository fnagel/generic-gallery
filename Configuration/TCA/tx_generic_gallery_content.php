<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:tx_genericgallery_domain_model_textitem',
        'label' => 'bodytext',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'bodytext,position,width,',
        'typeicon_classes' => [
            'default' => 'extensions-generic-gallery-content',
        ],
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, starttime, endtime, bodytext, position, width',
    ],
    'types' => [
        '0' => [
            'showitem' => 'hidden, --palette--;;palettePositioning, bodytext',
        ],
    ],
    'palettes' => [
        'palettePositioning' => [
            'showitem' => 'position, width,',
            'canNotCollapse' => false,
        ],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
                ],
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
                ],
            ],
        ],

        'bodytext' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:tx_genericgallery_domain_model_textitem.bodytext',
            'config' => [
                'type' => 'text',
                'cols' => '48',
                'rows' => '5',
                'wizards' => [
                    '_PADDING' => 2,
                    'RTE' => [
                        'notNewRecords' => 1,
                        'RTEonly' => 1,
                        'type' => 'script',
                        'title' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:bodytext.W.RTE',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_rte.gif',
                        'module' => [
                            'name' => 'wizard_rte',
                        ],
                    ],
                ],
                'softref' => 'typolink_tag,email[subst],url',
            ],
            'defaultExtras' => 'richtext:rte_transform[mode=ts_css]',
        ],
        'position' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:tx_genericgallery_domain_model_textitem.position',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim',
                'default' => '0,0',
            ],
        ],
        'width' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:tx_genericgallery_domain_model_textitem.width',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim',
                'default' => '0',
            ],
        ],
    ],
];
