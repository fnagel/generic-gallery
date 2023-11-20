<?php

use FelixNagel\GenericGallery\Utility\EmConfiguration;

defined('TYPO3') || die();

call_user_func(static function () {
    $configuration = EmConfiguration::getSettings();

    if ($configuration->getAddImageCollection()) {
        $GLOBALS['TCA']['sys_file_collection']['columns']['type']['config']['items'][] = [
            'label' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:sys_file_collection.type.images',
            'value' => 'images',
        ];

        $GLOBALS['TCA']['sys_file_collection']['types']['images'] = $GLOBALS['TCA']['sys_file_collection']['types']['static'];
        $GLOBALS['TCA']['sys_file_collection']['types']['images']['columnsOverrides'] = [
            'files' => [
                'config' => [
                    'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
                ],
            ],
        ];
    }
});
