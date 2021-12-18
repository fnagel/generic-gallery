<?php

defined('TYPO3') or die();

call_user_func(function ($packageKey) {
    $extensionName = \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToLowerCamelCase($packageKey);
    $pluginSignature = strtolower($extensionName).'_pi1';
    $configuration = \FelixNagel\GenericGallery\Utility\EmConfiguration::getSettings();

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'TYPO3.GenericGallery',
        'Pi1',
        'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:generic_gallery.plugin.title'
    );

    $tempColumns = [
        // gallery type
        'tx_generic_gallery_predefined' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:generic_gallery_predefined',
            'description' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:generic_gallery_predefined.desc',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'allowNonIdValues' => 1,
                'itemsProcFunc' => 'FelixNagel\GenericGallery\Backend\Hooks\TcaHook->addPredefinedFields',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],

        // single items
        'tx_generic_gallery_items' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:generic_gallery_items',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_generic_gallery_pictures',
                'foreign_field' => 'tt_content_id',
                'appearance' => [
                    'useSortable' => 1,
                    'collapseAll' => 1,
                    'expandSingle' => 1,
                ],
                'maxitems' => 1000,
                'minitems' => 0,
            ],
        ],

        // file reference
        'tx_generic_gallery_images' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:generic_gallery_images',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'tx_generic_gallery_picture_single',
                [
                    'size' => 20,
                    'maxitems' => 2000,
                    'minitems' => 0,
                    'autoSizeMax' => 40,
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
                'jpg,gif,jpeg,png'
            ),
        ],

        // collection reference
        'tx_generic_gallery_collection' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:generic_gallery_collection',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'sys_file_collection',
                'size' => 1,
                'maxitems' => 1,
                'minitems' => 0,
                'wizards' => [
                    'suggest' => [
                        'type' => 'suggest',
                    ],
                ],
            ],
        ],
    ];

    if ($configuration->getUseInlineCollection()) {
        unset($tempColumns['tx_generic_gallery_collection']['config']['wizards']);
        $tempColumns['tx_generic_gallery_collection']['config'] = [
            'type' => 'inline',
            'foreign_table' => 'sys_file_collection',
            'appearance' => [
                'collapseAll' => 0,
                'expandSingle' => 1,
            ],
            'maxitems' => 1,
            'minitems' => 0,
        ];
    }

    // Add field to tt_content
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns);
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] =
        'tx_generic_gallery_predefined,tx_generic_gallery_items,tx_generic_gallery_images,tx_generic_gallery_collection';

    // Remove unneeded fields
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'select_key,recursive,pages';
}, 'generic_gallery');
