<?php

return [
    'ctrl' => array(
        'title' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:tx_genericgallery_domain_model_textitem',
        'label' => 'bodytext',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'delete' => 'deleted',
        'enablecolumns' => array(
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ),
        'searchFields' => 'bodytext,position,width,',
        'typeicon_classes' => [
            'default' => 'extensions-generic-gallery-content',
        ],
    ),
    'interface' => array(
        'showRecordFieldList' => 'hidden, starttime, endtime, bodytext, position, width',
    ),
    'types' => array(
        '0' => array(
            'showitem' => 'hidden, --palette--;;palettePositioning, bodytext',
        ),
    ),
    'palettes' => array(
        'palettePositioning' => array(
            'showitem' => 'position, width,',
            'canNotCollapse' => false,
        ),
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
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
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
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
                ),
            ),
        ),

        'bodytext' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:tx_genericgallery_domain_model_textitem.bodytext',
            'config' => array(
                'type' => 'text',
                'cols' => '48',
                'rows' => '5',
                'softref' => 'typolink_tag,email[subst],url',
            ),
            'defaultExtras' => 'richtext[*]:rte_transform[flag=rte_enabled|mode=ts]',
        ),
        'position' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:tx_genericgallery_domain_model_textitem.position',
            'config' => array(
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim',
                'default' => '0,0',
            ),
        ),
        'width' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:tx_genericgallery_domain_model_textitem.width',
            'config' => array(
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim',
                'default' => '0',
            ),
        ),
    ),
];
