<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(function ($packageKey) {
    $extensionName = \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToLowerCamelCase($packageKey);
    $pluginSignature = strtolower($extensionName).'_pi1';
    $configuration = \TYPO3\GenericGallery\Utility\EmConfiguration::getSettings();

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'TYPO3.GenericGallery',
        'Pi1',
        'Generic Gallery'
    );

    $tempColumns = array(
        // gallery type
        'tx_generic_gallery_predefined' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xml:generic_gallery_predefined',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'allowNonIdValues' => 1,
                'itemsProcFunc' => 'TYPO3\GenericGallery\Backend\Hooks\TcaHook->addPredefinedFields',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
            ),
        ),

        // single items
        'tx_generic_gallery_items' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xml:generic_gallery_items',
            'config' => array(
                'type' => 'inline',
                'foreign_table' => 'tx_generic_gallery_pictures',
                'foreign_field' => 'tt_content_id',
                'appearance' => array(
                    'useSortable' => 1,
                    'collapseAll' => 1,
                    'expandSingle' => 1,
                ),
                'maxitems' => 1000,
                'minitems' => 0,
            ),
        ),

        // file reference
        'tx_generic_gallery_images' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xml:generic_gallery_images',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'tx_generic_gallery_picture_single',
                array(
                    'size' => 20,
                    'maxitems' => 2000,
                    'minitems' => 0,
                    'autoSizeMax' => 40,
                    'foreign_types' => array(
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => array(
                            'showitem' => '
									--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.basicoverlayPalette;genericGalleryImagePalette,
									--palette--;;filePalette',
                        ),
                    ),
                ),
                'jpg,gif,jpeg,png'
            ),
        ),

        // collection reference
        'tx_generic_gallery_collection' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xml:generic_gallery_collection',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'sys_file_collection',
                'size' => 1,
                'maxitems' => 1,
                'minitems' => 0,
                'wizards' => array(
                    'suggest' => array(
                        'type' => 'suggest',
                    ),
                ),
            ),
        ),
    );

    // @todo Remove this when 6.2 is no longer relevant
    if (version_compare(TYPO3_branch, '7.0', '<')) {
        $tempColumns['tx_generic_gallery_collection']['config']['wizards'] = array(
            'edit' => array(
                'type' => 'popup',
                'title' => 'Edit',
                'script' => 'wizard_edit.php',
                'icon' => 'edit2.gif',
                'popup_onlyOpenIfSelected' => 1,
                'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
            ),
            'add' => array(
                'type' => 'script',
                'title' => 'Create new',
                'icon' => 'add.gif',
                'params' => array(
                    'table' => 'sys_file_collection',
                    'pid' => '###CURRENT_PID###',
                    'setValue' => 'prepend',
                ),
                'script' => 'wizard_add.php',
            ),
            'suggest' => array(
                'type' => 'suggest',
            ),
        );
    }

    if ($configuration->getUseInlineCollection()) {
        unset($tempColumns['tx_generic_gallery_collection']['config']['wizards']);
        $tempColumns['tx_generic_gallery_collection']['config'] = array(
            'type' => 'inline',
            'foreign_table' => 'sys_file_collection',
            'appearance' => array(
                'collapseAll' => 0,
                'expandSingle' => 1,
            ),
            'maxitems' => 1,
            'minitems' => 0,
        );
    }

    // Add field to tt_content
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns);
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] =
        'tx_generic_gallery_predefined,tx_generic_gallery_items,tx_generic_gallery_images,tx_generic_gallery_collection';

    // Remove unneeded fields
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'select_key,recursive,pages';

}, 'generic_gallery');
