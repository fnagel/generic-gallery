<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:tx_genericgallery_domain_model_galleryitem',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'delete' => 'deleted',
        'sortby' => 'sorting',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'title,link,image,text_items,',
        'typeicon_classes' => [
            'default' => 'extensions-generic-gallery-pictures',
        ],
    ],
    'types' => [
        '0' => ['showitem' => '
		--div--;LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:tx_genericgallery_domain_model_galleryitem.tab.general,
			--palette--;;paletteGeneral, images,
		--div--;LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:tx_genericgallery_domain_model_galleryitem.tab.contents,
			contents,
		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
			hidden, --palette--;;visibility
	'],
    ],
    'palettes' => [
        'visibility' => [
            'showitem' => 'starttime, endtime',
        ],
        'paletteGeneral' => [
            'showitem' => 'title, link',
            'canNotCollapse' => false,
        ],
    ],
    'columns' => [
        'crdate' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'tstamp' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'deleted' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ]
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0
            ],
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly'
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ]
            ],
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly'
        ],

        'title' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:tx_genericgallery_domain_model_galleryitem.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'link' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:tx_genericgallery_domain_model_galleryitem.link',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'renderType' => 'inputLink',
                'softref' => 'typolink,url',
            ],
        ],
        'images' => [
            'label' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:tx_genericgallery_domain_model_galleryitem.images',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'tx_generic_gallery_picture_single',
                [
                    'size' => 1,
                    'maxitems' => 1,
                    'minitems' => 1,
                    'overrideChildTca' => [
                        'types' => [
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                                'showitem' => '
							--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.basicoverlayPalette;genericGalleryImagePalette,
							--palette--;;filePalette',
                            ],
                        ],
                    ],
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
        'tt_content_id' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'contents' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:tx_genericgallery_domain_model_galleryitem.text_items',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_generic_gallery_content',
                'foreign_field' => 'pictures_id',
                'minitems' => 0,
                'maxitems' => 99,
                'appearance' => [
                    'useSortable' => 1,
                    'collapseAll' => 1,
                    'expandSingle' => 1,
                ],
            ],
        ],
    ],
];
