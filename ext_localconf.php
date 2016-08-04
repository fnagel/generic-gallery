<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(function () {
    // BE preview
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['genericgallery_pi1'][] =
        'EXT:generic_gallery/Classes/Backend/Hooks/PageLayoutViewHook.php:TYPO3\GenericGallery\Backend\Hooks\PageLayoutViewHook->getExtensionSummary';

    // FE plugin
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'TYPO3.GenericGallery',
        'Pi1',
        array(
            'GalleryCollection' => 'show',
            'GalleryItem' => 'show',
        ),
        // non-cacheable actions
        array(
            'GalleryCollection' => '',
            'GalleryItem' => '',
        )
    );
});
