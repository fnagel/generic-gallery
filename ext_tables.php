<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(function () {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_generic_gallery_pictures');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_generic_gallery_content');

    /* @var $iconRegistry \TYPO3\CMS\Core\Imaging\IconRegistry */
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        'extensions-generic-gallery',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:generic_gallery/Resources/Public/Icons/Extension.svg']
    );
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
});
