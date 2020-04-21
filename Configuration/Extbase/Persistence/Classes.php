<?php

return [
    \FelixNagel\GenericGallery\Domain\Model\GalleryItem::class => [
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
    \FelixNagel\GenericGallery\Domain\Model\TextItem::class => [
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
