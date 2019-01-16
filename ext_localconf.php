<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(function () {
    // BE preview
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['genericgallery_pi1'][] =
        'FelixNagel\GenericGallery\Backend\Hooks\PageLayoutViewHook->getExtensionSummary';

    // Add page TS config
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:generic_gallery/Configuration/TypoScript/pageTsConfig.ts">'
    );

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

    // add cHash configuration
    // See: http://forum.typo3.org/index.php?t=msg&th=203350
    $requiredParameters = [
        'tx_genericgallery_pi1[controller]',
        'tx_genericgallery_pi1[action]',
        'tx_genericgallery_pi1[item]',
        'tx_genericgallery_pi1[contentElement]',
    ];
    $GLOBALS['TYPO3_CONF_VARS']['FE']['cHashRequiredParameters'] .= ','.implode(',', $requiredParameters);
});
