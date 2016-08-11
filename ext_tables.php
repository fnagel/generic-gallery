<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(function () {

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
        'tx_generic_gallery_pictures',
        'EXT:generic_gallery/Resources/Private/Language/locallang_csh_tx_genericgallery_domain_model_galleryitem.xlf'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_generic_gallery_pictures');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
        'tx_generic_gallery_content',
        'EXT:generic_gallery/Resources/Private/Language/locallang_csh_tx_genericgallery_domain_model_textitem.xlf'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_generic_gallery_content');

    /* @var $iconRegistry \TYPO3\CMS\Core\Imaging\IconRegistry */
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        'extensions-generic-gallery-pictures',
        \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
        ['source' => 'EXT:generic_gallery/Resources/Public/Icons/tx_genericgallery_domain_model_galleryitem.gif']
    );
    $iconRegistry->registerIcon(
        'extensions-generic-gallery-content',
        \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
        ['source' => 'EXT:generic_gallery/Resources/Public/Icons/tx_genericgallery_domain_model_textitem.gif']
    );

    // Add page TS config
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:generic_gallery/Configuration/TypoScript/pageTsConfig.ts">'
    );

});
