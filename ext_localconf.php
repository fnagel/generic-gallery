<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(function () {
    $configuration = \FelixNagel\GenericGallery\Utility\EmConfiguration::getSettings();

    // BE preview
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['genericgallery_pi1'][] =
        'FelixNagel\GenericGallery\Backend\Hooks\PageLayoutViewHook->getExtensionSummary';

    // Add page TS config
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
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
    if ($configuration->isHideRelations()) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(trim('
            mod.web_list.table.tx_generic_gallery_pictures.hideTable = 1
            mod.web_list.table.tx_generic_gallery_content.hideTable = 1
        '));
    }

    // FE plugin
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'FelixNagel.GenericGallery',
        'Pi1',
        [
            'GalleryCollection' => 'show',
            'GalleryItem' => 'show',
        ],
        // non-cacheable actions
        [
            'GalleryCollection' => '',
            'GalleryItem' => '',
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
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['GenericGalleryImageItemMapper'] =
        \FelixNagel\GenericGallery\Routing\Aspect\ImageItemMapper::class;

    // File collection
    if ($configuration->getAddImageCollection()) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['registeredCollections']['images'] =
            \TYPO3\CMS\Core\Resource\Collection\StaticFileCollection::class;
    }
});
