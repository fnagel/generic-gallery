<?php

use FelixNagel\GenericGallery\Utility\EmConfiguration;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use FelixNagel\GenericGallery\Controller\GalleryCollectionController;
use FelixNagel\GenericGallery\Controller\GalleryItemController;
use FelixNagel\GenericGallery\Routing\Aspect\ImageItemMapper;
use TYPO3\CMS\Core\Resource\Collection\StaticFileCollection;

defined('TYPO3') || die();

call_user_func(function () {
    $configuration = EmConfiguration::getSettings();

    // BE preview
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['genericgallery_pi1'][] =
        'FelixNagel\GenericGallery\Backend\Hooks\PageLayoutViewHook->getExtensionSummary';

    // Add page TS config
    ExtensionManagementUtility::addPageTSConfig(
        '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:generic_gallery/Configuration/TSconfig/page.tsconfig">'
    );
    if (!$configuration->isEnableTypeItems()) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            'TCEFORM.tt_content.tx_generic_gallery_items.disabled = 1'
        );
    }
    if (!$configuration->isEnableTypeImages()) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            'TCEFORM.tt_content.tx_generic_gallery_images.disabled = 1'
        );
    }
    if (!$configuration->isEnableTypeCollection()) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            'TCEFORM.tt_content.tx_generic_gallery_collection.disabled = 1'
        );
    }

    // FE plugin
    ExtensionUtility::configurePlugin(
        'GenericGallery',
        'Pi1',
        [
            GalleryCollectionController::class => 'show',
            GalleryItemController::class => 'show',
        ],
        // non-cacheable actions
        [
            GalleryCollectionController::class => '',
            GalleryItemController::class => '',
        ]
    );

    // Add cHash configuration
    // See: http://forum.typo3.org/index.php?t=msg&th=203350
    $requiredParameters = [
        'tx_genericgallery_pi1[controller]',
        'tx_genericgallery_pi1[action]',
        'tx_genericgallery_pi1[item]',
        'tx_genericgallery_pi1[contentElement]',
    ];
    $GLOBALS['TYPO3_CONF_VARS']['FE']['cHashRequiredParameters'] .= ','.implode(',', $requiredParameters);

    // Routing
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['GenericGalleryImageItemMapper'] = ImageItemMapper::class;

    // File collection
    if ($configuration->getAddImageCollection()) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['registeredCollections']['images'] =
            StaticFileCollection::class;
    }
});
