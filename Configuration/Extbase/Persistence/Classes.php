<?php

use FelixNagel\GenericGallery\Domain\Model\GalleryItem;
use FelixNagel\GenericGallery\Domain\Model\TextItem;

return [
    GalleryItem::class => [
        'tableName' => 'tx_generic_gallery_pictures',
        'properties' => [
            'title' => [
                'fieldName' => 'title'
            ],
            'link' => [
                'fieldName' => 'link'
            ],
            'imageReference' => [
                'fieldName' => 'images'
            ],
            'textItems' => [
                'fieldName' => 'contents'
            ],
            'ttContentUid' => [
                'fieldName' => 'tt_content_id'
            ],
        ],
    ],
    TextItem::class => [
        'tableName' => 'tx_generic_gallery_content',
        'properties' => [
            'bodytext' => [
                'fieldName' => 'bodytext'
            ],
            'position' => [
                'fieldName' => 'position'
            ],
            'width' => [
                'fieldName' => 'width'
            ],
        ],
    ],
];
