<?php

use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
    'extensions-generic-gallery' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:generic_gallery/Resources/Public/Icons/Extension.svg',
    ],
    'extensions-generic-gallery-pictures' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:generic_gallery/Resources/Public/Icons/tx_genericgallery_domain_model_galleryitem.gif',
    ],
    'extensions-generic-gallery-content' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:generic_gallery/Resources/Public/Icons/tx_genericgallery_domain_model_textitem.gif',
    ],
];
