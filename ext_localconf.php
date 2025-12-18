<?php

use FelixNagel\GenericGallery\Utility\EmConfiguration;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use FelixNagel\GenericGallery\Controller\GalleryCollectionController;
use FelixNagel\GenericGallery\Controller\GalleryItemController;
use TYPO3\CMS\Core\Resource\Collection\StaticFileCollection;

defined('TYPO3') || die();

call_user_func(static function () {
    $configuration = EmConfiguration::getSettings();

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
        ],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    // Add cHash configuration
    $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['requireCacheHashPresenceParameters'] =
        array_merge($GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['requireCacheHashPresenceParameters'], [
            'tx_genericgallery_pi1[controller]',
            'tx_genericgallery_pi1[action]',
            'tx_genericgallery_pi1[item]',
            'tx_genericgallery_pi1[file]',
            'tx_genericgallery_pi1[contentElement]',
        ]);

    // File collection
    if ($configuration->getAddImageCollection()) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['registeredCollections']['images'] =
            StaticFileCollection::class;
    }
});
