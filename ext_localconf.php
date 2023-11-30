<?php

use FelixNagel\GenericGallery\Utility\EmConfiguration;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use FelixNagel\GenericGallery\Controller\GalleryCollectionController;
use FelixNagel\GenericGallery\Controller\GalleryItemController;
use FelixNagel\GenericGallery\Routing\Aspect\ImageItemMapper;
use FelixNagel\GenericGallery\UpgradeWizard;
use TYPO3\CMS\Core\Resource\Collection\StaticFileCollection;

defined('TYPO3') || die();

call_user_func(static function () {
    $configuration = EmConfiguration::getSettings();

    // Add page TS config
    ExtensionManagementUtility::addPageTSConfig(
        '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:generic_gallery/Configuration/TSconfig/page.tsconfig">'
    );
    if (!$configuration->isEnableTypeItems()) {
        ExtensionManagementUtility::addPageTSConfig(
            'TCEFORM.tt_content.tx_generic_gallery_items.disabled = 1'
        );
    }
    if (!$configuration->isEnableTypeImages()) {
        ExtensionManagementUtility::addPageTSConfig(
            'TCEFORM.tt_content.tx_generic_gallery_images.disabled = 1'
        );
    }
    if (!$configuration->isEnableTypeCollection()) {
        ExtensionManagementUtility::addPageTSConfig(
            'TCEFORM.tt_content.tx_generic_gallery_collection.disabled = 1'
        );
    }
    if ($configuration->isHideRelations()) {
        ExtensionManagementUtility::addPageTSConfig(trim('
            mod.web_list.table.tx_generic_gallery_pictures.hideTable = 1
            mod.web_list.table.tx_generic_gallery_content.hideTable = 1
        '));
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
    $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['requireCacheHashPresenceParameters'] =
        array_merge($GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['requireCacheHashPresenceParameters'], [
            'tx_genericgallery_pi1[controller]',
            'tx_genericgallery_pi1[action]',
            'tx_genericgallery_pi1[item]',
            'tx_genericgallery_pi1[contentElement]',
        ]);

    // Routing
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['GenericGalleryImageItemMapper'] = ImageItemMapper::class;

    // File collection
    if ($configuration->getAddImageCollection()) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['registeredCollections']['images'] =
            StaticFileCollection::class;
    }

    // Upgrade wizards
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update'][UpgradeWizard\PluginListTypeWizard::class]
        = UpgradeWizard\PluginListTypeWizard::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update'][UpgradeWizard\PluginImageFieldWizard::class]
        = UpgradeWizard\PluginImageFieldWizard::class;
});
