<?php

namespace TYPO3\GenericGallery\Backend\Hooks;

/***************************************************************
 * Copyright notice
 *
 * (c) 2014 Felix Nagel (info@felixnagel.com)
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

use \TYPO3\GenericGallery\Utility\EmConfiguration,
	\TYPO3\CMS\Backend\Utility\IconUtility,
	\TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * Hook class for PageLayoutView hook `list_type_Info`
 *
 * @todo Use localization
 */
class PageLayoutViewHook {

	/*
	 * Current row data
	 *
	 * @var array
	 */
	private $data = NULL;

	/**
	 * Table information
	 *
	 * @var array
	 */
	public $tableData = array();

	/**
	 * Image previews
	 *
	 * @var string
	 */
	public $imagePreviewHtml = '';


	/**
	 * Returns information about this plugin content
	 *
	 * @param array &$parameters Parameters for the hook:
	 *                  'pObj' => reference to \TYPO3\CMS\Backend\View\PageLayoutView
	 *                  'row' => $row,
	 *                  'infoArr' => $infoArr
	 * @param \TYPO3\CMS\Backend\View\PageLayoutView &$parentObject
	 * @return string Rendered output for PageLayoutView
	 */
	public function getExtensionSummary(
		array &$parameters = array(),
		\TYPO3\CMS\Backend\View\PageLayoutView &$parentObject
	) {
		if ($parameters['row']['list_type'] !== 'genericgallery_pi1') {
			return '';
		}

		if (!EmConfiguration::getSettings()->isEnableCmsLayout()) {
			return '';
		}

		$this->data = $parameters['row'];

		return $this->renderPreview();
	}

	/**
	 * Render infos
	 *
	 * @return void
	 */
	protected function renderInfo() {
		// @todo Use TS setting gallery name
		$this->tableData[] = array('Type', rtrim($this->data['tx_generic_gallery_predefined'], '.'));
	}

	/**
	 * @return string
	 */
	protected function renderPreview() {
		$html = '';

		$this->renderInfo();

		if ($this->data['tx_generic_gallery_collection']) {
			$this->renderCollectionPreview();

		} elseif ($this->data['tx_generic_gallery_images']) {
			$this->renderImagesPreview();

		} elseif ($this->data['tx_generic_gallery_items']) {
			$this->renderItemsPreview();
		}

		$html .= '<strong>Generic Gallery</strong><br>';

		$html .= $this->renderInfoTable();

		if ($this->imagePreviewHtml !== '') {
			$html .= '<br>' . $this->imagePreviewHtml;
		}

		return $html;
	}

	/**
	 * @todo Add image preview
	 *
	 * @return void
	 */
	protected function renderCollectionPreview() {
		$collection = BackendUtility::getRecord('sys_file_collection', $this->data['tx_generic_gallery_collection']);
		$nameValue = $this->getRecordLink($collection, 'sys_file_collection', $collection['title']);

		$this->tableData[] = array('Source', 'collection');
		$this->tableData[] = array('Name', $nameValue);
		$this->tableData[] = array('Images', $collection['files']);

		$this->imagePreviewHtml = '';
	}

	/**
	 * @return void
	 */
	protected function renderImagesPreview() {
		$this->tableData[] = array('Source', 'images');

		$this->imagePreviewHtml = BackendUtility::thumbCode(
			$this->data,
			'tt_content',
			'tx_generic_gallery_images',
			'tx_generic_gallery_picture_single',
			$GLOBALS['BACK_PATH']
		);
	}

	/**
	 * @return void
	 */
	protected function renderItemsPreview() {
		// @todo Use localization
		$this->tableData[] = array('Source', 'items');

		$this->imagePreviewHtml = $this->getItemsImagePreviews($this->data);
	}

	/**
	 * @param array $data
	 * @return string
	 */
	protected function getItemsImagePreviews($data) {
		$result = '';

		/* @var $database \TYPO3\CMS\Core\Database\DatabaseConnection */
		$database = $GLOBALS['TYPO3_DB'];
		$select = 'uid, title';
		$table = 'tx_generic_gallery_pictures';
		$where = 'tt_content_id = ' . $data['uid'];
		$where .= BackendUtility::BEenableFields($table) . ' AND ' . $table . '.deleted = 0';
		$order = 'sorting';
		$group = '';
		$limit = '';

		$res = $database->exec_SELECTquery($select, $table, $where, $group, $order, $limit);

		while (($row = $database->sql_fetch_assoc($res))) {
			if (is_array($row)) {
				$result .= BackendUtility::thumbCode(
					$row,
					'tx_generic_gallery_pictures',
					'images',
					'tx_generic_gallery_picture_single',
					$GLOBALS['BACK_PATH']
				);
			}
		}

		$database->sql_free_result($res);

		return $result;
	}


	/**
	 * @return string
	 */
	protected function getRecordLink($record, $table, $content = '', $addIcon = TRUE) {
		if ($content === '') {
			$content = htmlspecialchars(BackendUtility::getRecordTitle($table, $record));
		}

		if ($addIcon) {
			$icon = IconUtility::getSpriteIconForRecord($table, $record, array('title' => 'Uid: ' . $record['uid']));
			$content = $icon . $content;
		}

		return $GLOBALS['SOBE']->doc->wrapClickMenuOnIcon($content, $table, $record['uid'], 1, '', '+info,edit');
	}

	/**
	 * Render the settings as table for Web>Page module
	 *
	 * @return string
	 */
	protected function renderInfoTable() {
		if (count($this->tableData) == 0) {
			return '';
		}

		$content = '';
		foreach ($this->tableData as $line) {
			$content .= '<strong style="width: 80px; display: inline-block;">' . $line[0] . '</strong>' . ' ' . $line[1] . '<br />';
		}

		return '<br><pre style="white-space: normal;">' . $content . '</pre>';
	}
}