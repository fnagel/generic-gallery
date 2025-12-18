<?php

use FelixNagel\GenericGallery\Backend\Hooks\TcaHook;
use FelixNagel\GenericGallery\Backend\Preview\ContentElementPreview;
use TYPO3\CMS\Core\Resource\FileType;
use FelixNagel\GenericGallery\Utility\EmConfiguration;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

call_user_func(static function() {
    $configuration = EmConfiguration::getSettings();

    // Add plugin
    $contentTypeName = ExtensionUtility::registerPlugin(
        'GenericGallery',
        'Pi1',
        'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:generic_gallery.plugin.title',
        'extensions-generic-gallery',
        'plugins',
        'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:generic_gallery.plugin.description'
    );

    $tempColumns = [
        // gallery type
        'tx_generic_gallery_predefined' => [
            'exclude' => true,
            'label' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:generic_gallery_predefined',
            'description' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:generic_gallery_predefined.desc',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'allowNonIdValues' => true,
                'itemsProcFunc' => TcaHook::class.'->addPredefinedFields',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],

        // single items
        'tx_generic_gallery_items' => [
            'exclude' => true,
            'label' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:generic_gallery_items',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_generic_gallery_pictures',
                'foreign_field' => 'tt_content_id',
                'appearance' => [
                    'useSortable' => true,
                    'collapseAll' => true,
                    'expandSingle' => true,
                ],
                'maxitems' => 1000,
                'minitems' => 0,
            ],
        ],

        // file reference
        'tx_generic_gallery_images' => [
            'exclude' => true,
            'label' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:generic_gallery_images',
            'config' => [
                'type' => 'file',
                'allowed' => 'jpg,gif,jpeg,png',
                'size' => 20,
                'maxitems' => 2000,
                'minitems' => 0,
                'autoSizeMax' => 40,
                'appearance' => [
                    'useSortable' => true,
                    'collapseAll' => true,
                    'expandSingle' => true,
                ],
                'overrideChildTca' => [
                    'types' => [
                        FileType::IMAGE->value => [
                            'showitem' => '
                                --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.basicoverlayPalette;genericGalleryImagePalette,
                                --palette--;;filePalette',
                        ],
                    ],
                ],
            ],
        ],

        // collection reference
        'tx_generic_gallery_collection' => [
            'exclude' => true,
            'label' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:generic_gallery_collection',
            'config' => [
                'type' => 'group',
                'allowed' => 'sys_file_collection',
                'size' => 1,
                'maxitems' => 1,
                'minitems' => 0,
                'default' => 0,
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
                'collapseAll' => false,
                'expandSingle' => true,
            ],
            'maxitems' => 1,
            'minitems' => 0,
            'default' => 0,
        ];
    }

    // Add fields to tt_content
    ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns);
    ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:plugin,
            tx_generic_gallery_predefined,tx_generic_gallery_items,
            tx_generic_gallery_images,tx_generic_gallery_collection',
        $contentTypeName,
        'after:palette:headers'
    );

    // Preview
    if (EmConfiguration::getSettings()->isEnableCmsLayout()) {
        $GLOBALS['TCA']['tt_content']['types'][$contentTypeName]['previewRenderer'] = ContentElementPreview::class;
    }
});
