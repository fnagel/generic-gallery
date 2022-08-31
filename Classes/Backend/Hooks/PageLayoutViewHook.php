<?php

namespace FelixNagel\GenericGallery\Backend\Hooks;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use FelixNagel\GenericGallery\Service\SettingsService;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use FelixNagel\GenericGallery\Utility\EmConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Object\Container\Container;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Hook class for PageLayoutView hook `list_type_Info`.
 *
 * @todo Use localization
 */
class PageLayoutViewHook
{
    protected ?object $objectContainer = null;

    protected ?ObjectManager $objectManager = null;

    protected ?object $settingsService = null;

    /*
     * Current page settings
     *
     * @var array
     */
    private ?array $settings = null;

    /*
     * Current row data
     *
     * @var array
     */
    private $data = null;

    /**
     * Table information.
     */
    public array $tableData = [];

    /**
     * Image previews.
     */
    public string $imagePreviewHtml = '';

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
    public function getExtensionSummary(array &$parameters = [], &$parentObject = null)
    {
        if ($parameters['row']['list_type'] !== 'genericgallery_pi1') {
            return '';
        }

        if (!EmConfiguration::getSettings()->isEnableCmsLayout()) {
            return '';
        }

        $this->data = $parameters['row'];
        $this->settings = $this->getTypoScriptService()->getTypoScriptSettingsFromBackend($this->data['pid']);

        return $this->renderPreview();
    }

    /**
     * Render header.
     *
     * @return string
     */
    protected function renderHeader()
    {
        $editLink = GeneralUtility::makeInstance(UriBuilder::class)->buildUriFromRoute('record_edit') . ('&edit[tt_content]['.$this->data['uid'].']=edit') . '&returnUrl=' . rawurlencode(GeneralUtility::getIndpEnv('REQUEST_URI'));

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

    protected function renderCollectionPreview()
    {
        $collection = BackendUtility::getRecord('sys_file_collection', $this->data['tx_generic_gallery_collection']);

        $this->tableData[] = ['Source', 'collection (' . $collection['type'] . ')'];
        $this->tableData[] = [
            'Name',
            $this->getRecordLink($collection, 'sys_file_collection', ' ' . $collection['title']),
        ];

        switch ($collection['type']) {
            case 'folder':
                // @todo Add preview images for folder images
                $this->tableData[] = ['Folder', $collection['folder']];
                break;

            case 'files':
                $this->tableData[] = ['Images', $collection['files']];
                $this->imagePreviewHtml = BackendUtility::thumbCode($collection, 'sys_file_collection', 'files');
                break;

            case 'category':
                // @todo Add preview images for category images
                $category = BackendUtility::getRecord('sys_category', $collection['category']);
                $this->tableData[] = ['Category', $this->getRecordLink($category, 'sys_category', $category['title'])];
                break;
        }
    }

    protected function renderImagesPreview()
    {
        $this->tableData[] = ['Source', 'images'];
        $this->tableData[] = ['Images', $this->data['tx_generic_gallery_images']];

        $this->imagePreviewHtml = BackendUtility::thumbCode($this->data, 'tt_content', 'tx_generic_gallery_images');
    }

    protected function renderItemsPreview()
    {
        // @todo Use localization
        $this->tableData[] = ['Source', 'items'];

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

        $table = 'tx_generic_gallery_pictures';
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        $queryBuilder
            ->from($table)
            ->select('uid', 'title', 'images')
            ->where(
                $queryBuilder->expr()->eq('tt_content_id', $queryBuilder->createNamedParameter($data['uid']))
            )
            ->orderBy('sorting');

        $statement = $queryBuilder->execute();
        $rows = $statement->fetchAll();
        $this->tableData[] = ['Images', is_countable($rows) ? count($rows) : 0];
        if ($rows === null) {
            return $result;
        }

        // Get thumbs
        foreach ($rows as $row) {
            $result .= BackendUtility::thumbCode($row, 'tx_generic_gallery_pictures', 'images');
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
            $iconFactory = GeneralUtility::makeInstance(IconFactory::class);
            $icon = $iconFactory->getIconForRecord($table, $record, Icon::SIZE_SMALL)->render();

            $content = $icon.$content;
        }

        // @todo Change this when TYPO3 10 is no longer needed
        return BackendUtility::wrapClickMenuOnIcon($content, $table, $record['uid'], 1, '', '+info,edit');
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
     * @return SettingsService
     */
    protected function getTypoScriptService()
    {
        if ($this->settingsService === null) {
            $this->settingsService = $this->getObjectContainer()->getInstance(SettingsService::class);
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
        if ($this->objectContainer === null) {
            $this->objectContainer = GeneralUtility::makeInstance(Container::class);
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

        $this->tableData[] = ['Type', $typeName];
    }
}
