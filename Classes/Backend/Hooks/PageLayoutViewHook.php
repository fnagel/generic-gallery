<?php

namespace TYPO3\GenericGallery\Backend\Hooks;

/***************************************************************
 * Copyright notice
 *
 * (c) 2015-2016 Felix Nagel (info@felixnagel.com)
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\GenericGallery\Utility\EmConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\IconUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * Hook class for PageLayoutView hook `list_type_Info`.
 *
 * @todo Use localization
 */
class PageLayoutViewHook
{
    /**
     * @var \TYPO3\CMS\Extbase\Object\Container\Container
     */
    protected $objectContainer = null;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager = null;

    /**
     * @var \TYPO3\GenericGallery\Service\SettingsService
     */
    protected $settingsService = null;

    /*
     * Current page settings
     *
     * @var array
     */
    private $settings = null;

    /*
     * Current row data
     *
     * @var array
     */
    private $data = null;

    /**
     * Table information.
     *
     * @var array
     */
    public $tableData = array();

    /**
     * Image previews.
     *
     * @var string
     */
    public $imagePreviewHtml = '';

    /**
     * Returns information about this plugin content.
     *
     * No type hint for $parentObject parameter due to fatal error when TemplaVoila is installed
     *
     * @param array                                  &$parameters   Parameters for the hook:
     *                                                              'pObj' => reference to \TYPO3\CMS\Backend\View\PageLayoutView
     *                                                              'row' => $row,
     *                                                              'infoArr' => $infoArr
     * @param \TYPO3\CMS\Backend\View\PageLayoutView &$parentObject
     *
     * @return string Rendered output for PageLayoutView
     */
    public function getExtensionSummary(array &$parameters = array(), &$parentObject)
    {
        if ($parameters['row']['list_type'] !== 'genericgallery_pi1') {
            return '';
        }

        if (!EmConfiguration::getSettings()->isEnableCmsLayout()) {
            return '';
        }

        $this->data = $parameters['row'];
        $this->settings = $this->getTypoScriptService()->getTypoScriptSettings();

        return $this->renderPreview();
    }

    /**
     * Render header.
     *
     * @return string
     */
    protected function renderHeader()
    {
        $editLink = BackendUtility::editOnClick('&edit[tt_content]['.$this->data['uid'].']=edit', $GLOBALS['BACK_PATH']);

        return '<strong><a href="#" onclick="'.$editLink.'">Generic Gallery</strong><br>';
    }

    /**
     * @return string
     */
    protected function renderPreview()
    {
        $html = '';

        $this->setGalleryType();

        if ($this->data['tx_generic_gallery_collection']) {
            $this->renderCollectionPreview();
        } elseif ($this->data['tx_generic_gallery_images']) {
            $this->renderImagesPreview();
        } elseif ($this->data['tx_generic_gallery_items']) {
            $this->renderItemsPreview();
        }

        $html .= $this->renderHeader();
        $html .= $this->renderInfoTable();

        if ($this->imagePreviewHtml !== '') {
            $html .= '<br>'.$this->imagePreviewHtml;
        }

        return $html;
    }

    /**
     */
    protected function renderCollectionPreview()
    {
        $collection = BackendUtility::getRecord('sys_file_collection', $this->data['tx_generic_gallery_collection']);
        $nameValue = $this->getRecordLink($collection, 'sys_file_collection', $collection['title']);

        $this->tableData[] = array('Source', 'collection');
        $this->tableData[] = array('Images', $collection['files']);
        $this->tableData[] = array('Name', $nameValue);

        $this->imagePreviewHtml = BackendUtility::thumbCode(
            $collection,
            'sys_file_collection',
            'files',
            $GLOBALS['BACK_PATH']
        );
    }

    /**
     */
    protected function renderImagesPreview()
    {
        $this->tableData[] = array('Source', 'images');
        $this->tableData[] = array('Images', $this->data['tx_generic_gallery_images']);

        $this->imagePreviewHtml = BackendUtility::thumbCode(
            $this->data,
            'tt_content',
            'tx_generic_gallery_images',
            $GLOBALS['BACK_PATH']
        );
    }

    /**
     */
    protected function renderItemsPreview()
    {
        // @todo Use localization
        $this->tableData[] = array('Source', 'items');

        $this->imagePreviewHtml = $this->getItemsImagePreviews($this->data);
    }

    /**
     * @param array $data
     *
     * @return string
     */
    protected function getItemsImagePreviews($data)
    {
        $result = '';

        $select = 'uid, title';
        $table = 'tx_generic_gallery_pictures';
        $where = 'tt_content_id = '.$data['uid'];
        $where .= BackendUtility::BEenableFields($table).' AND '.$table.'.deleted = 0';

        $rows = $this->getDatabase()->exec_SELECTgetRows($select, $table, $where, '', 'sorting');
        $this->tableData[] = array('Images', count($rows));
        if ($rows === null) {
            return $result;
        }

        // Get thumbs
        foreach ($rows as $row) {
            $result .= BackendUtility::thumbCode(
                $row,
                'tx_generic_gallery_pictures',
                'images',
                $GLOBALS['BACK_PATH']
            );
        }

        return $result;
    }

    /**
     * @return string
     */
    protected function getRecordLink($record, $table, $content = '', $addIcon = true)
    {
        if ($content === '') {
            $content = htmlspecialchars(BackendUtility::getRecordTitle($table, $record));
        }

        if ($addIcon) {
            if (version_compare(TYPO3_branch, '7.0', '<')) {
                $icon = IconUtility::getSpriteIconForRecord($table, $record, array('title' => 'Uid: '.$record['uid']));
            } else {
                $iconFactory = GeneralUtility::makeInstance(IconFactory::class);
                $icon = $iconFactory->getIconForRecord($table, $record, Icon::SIZE_SMALL)->render();
            }

            $content = $icon.$content;
        }

        if (version_compare(TYPO3_branch, '7.0', '<')) {
            $output = $GLOBALS['SOBE']->doc->wrapClickMenuOnIcon($content, $table, $record['uid'], 1, '', '+info,edit');
        } else {
            $output = BackendUtility::wrapClickMenuOnIcon($content, $table, $record['uid'], 1, '', '+info,edit');
        }

        return $output;
    }

    /**
     * Render the settings as table for Web>Page module.
     *
     * @return string
     */
    protected function renderInfoTable()
    {
        if (count($this->tableData) == 0) {
            return '';
        }

        $content = '';
        foreach ($this->tableData as $line) {
            $content .= '<strong style="width: 80px; display: inline-block;">'.
                $line[0].'</strong>'.' '.$line[1].'<br />';
        }

        return '<br><pre style="white-space: normal;">'.$content.'</pre>';
    }

    /**
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected function getDatabase()
    {
        return $GLOBALS['TYPO3_DB'];
    }

    /**
     * @param string $key
     * @param string $keyPrefix
     *
     * @return string
     */
    protected function translate(
        $key,
        $keyPrefix = 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf'
    ) {
        return $GLOBALS['LANG']->sL($keyPrefix.':'.$key);
    }

    /**
     * @return \TYPO3\GenericGallery\Service\SettingsService
     */
    protected function getTypoScriptService()
    {
        if ($this->settingsService === null) {
            $this->settingsService = $this->getObjectContainer()->getInstance(
                'TYPO3\\GenericGallery\\Service\\SettingsService'
            );
        }

        return $this->settingsService;
    }

    /**
     * Get object container.
     *
     * @return \TYPO3\CMS\Extbase\Object\Container\Container
     */
    protected function getObjectContainer()
    {
        if ($this->objectContainer == null) {
            $this->objectContainer = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\Container\\Container');
        }

        return $this->objectContainer;
    }

    /**
     * Set gallery type name.
     */
    protected function setGalleryType()
    {
        $typeName = rtrim($this->data['tx_generic_gallery_predefined'], '.');

        if (
            array_key_exists($typeName, $this->settings['gallery']) &&
            array_key_exists('name', $this->settings['gallery'][$typeName])
        ) {
            $typeName = $this->settings['gallery'][$typeName]['name'];
        }

        $this->tableData[] = array('Type', $typeName);
    }
}
