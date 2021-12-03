<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;

defined('TYPO3') || die();

call_user_func(function () {
    ExtensionManagementUtility::allowTableOnStandardPages('tx_generic_gallery_pictures');
    ExtensionManagementUtility::allowTableOnStandardPages('tx_generic_gallery_content');

    /* @var $iconRegistry IconRegistry */
    $iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
    $iconRegistry->registerIcon(
        'extensions-generic-gallery',
        SvgIconProvider::class,
        ['source' => 'EXT:generic_gallery/Resources/Public/Icons/Extension.svg']
    );
    $iconRegistry->registerIcon(
        'extensions-generic-gallery-pictures',
        BitmapIconProvider::class,
        ['source' => 'EXT:generic_gallery/Resources/Public/Icons/tx_genericgallery_domain_model_galleryitem.gif']
    );
    $iconRegistry->registerIcon(
        'extensions-generic-gallery-content',
        BitmapIconProvider::class,
        ['source' => 'EXT:generic_gallery/Resources/Public/Icons/tx_genericgallery_domain_model_textitem.gif']
    );
});
