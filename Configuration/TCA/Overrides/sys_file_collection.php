<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(function () {
    $configuration = \FelixNagel\GenericGallery\Utility\EmConfiguration::getSettings();

    if ($configuration->getAddImageCollection()) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['registeredCollections']['images'] =
            \TYPO3\CMS\Core\Resource\Collection\StaticFileCollection::class;

        $GLOBALS['TCA']['sys_file_collection']['columns']['type']['config']['items'][] = array(
            'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:sys_file_collection.type.images',
            'images'
        );

        $GLOBALS['TCA']['sys_file_collection']['types']['images'] = $GLOBALS['TCA']['sys_file_collection']['types']['static'];
        $GLOBALS['TCA']['sys_file_collection']['types']['images']['columnsOverrides'] = array(
            'files' => array(
                'config' => array(
                    'overrideChildTca' => array(
                        'columns' => array(
                            'uid_local' => array(
                                'config' => array(
                                    'appearance' => array(
                                        'elementBrowserType' => 'file',
                                        'elementBrowserAllowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'filter' => array(
                        array(
                            'userFunc' => \TYPO3\CMS\Core\Resource\Filter\FileExtensionFilter::class . '->filterInlineChildren',
                            'parameters' => array(
                                'allowedFileExtensions' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
                                'disallowedFileExtensions' => ''
                            ),
                        ),
                    ),
                ),
            ),
        );
    }
});
