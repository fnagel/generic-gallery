<?php

use FelixNagel\GenericGallery\Utility\EmConfiguration;
use TYPO3\CMS\Core\Resource\Filter\FileExtensionFilter;

defined('TYPO3') || die();

call_user_func(static function () {
    $configuration = EmConfiguration::getSettings();

    if ($configuration->getAddImageCollection()) {
        $GLOBALS['TCA']['sys_file_collection']['columns']['type']['config']['items'][] = [
            'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:sys_file_collection.type.images',
            'images'
        ];

        $GLOBALS['TCA']['sys_file_collection']['types']['images'] = $GLOBALS['TCA']['sys_file_collection']['types']['static'];
        $GLOBALS['TCA']['sys_file_collection']['types']['images']['columnsOverrides'] = [
            'files' => [
                'config' => [
                    'overrideChildTca' => [
                        'columns' => [
                            'uid_local' => [
                                'config' => [
                                    'appearance' => [
                                        'elementBrowserType' => 'file',
                                        'elementBrowserAllowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'filter' => [
                        [
                            'userFunc' => FileExtensionFilter::class . '->filterInlineChildren',
                            'parameters' => [
                                'allowedFileExtensions' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
                                'disallowedFileExtensions' => ''
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
});
