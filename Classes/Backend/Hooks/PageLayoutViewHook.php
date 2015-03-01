<?php

namespace TYPO3\GenericGallery\Backend\Hooks;

/***************************************************************
 * Copyright notice
 *
 * (c) 204 Felix Nagel (info@felixnagel.com)
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

use TYPO3\CMS\Core\Utility\GeneralUtility,
	TYPO3\CMS\Backend\Utility\BackendUtility,
	TYPO3\CMS\Core\Resource\ResourceFactory,
	TYPO3\CMS\Core\Resource\ProcessedFile,
	TYPO3\CMS\Backend\Utility\IconUtility;

/**
 * Hook class for PageLayoutView hook `list_type_Info`
 *
 * @author Felix Nagel <info@felixnagel.com>
 * @package \TYPO3\GenericGallery\Backend\Hooks
 */
class PageLayoutViewHook {

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

		if (!$this->isEnabledInExtensionManager()) {
			return '';
		}

		return $this->renderPreview($parameters['row']);
	}

	/**
	 * @param string $type
	 * @return string
	 */
	protected function renderTitle($type = 'invalid') {
		$title = '';

		$title .= '<strong>Generic Gallery</strong><br>';
		$title .= 'Source: <em>' . $type . '</em><br><br>';

		return $title;
	}

	/**
	 * @param array $data
	 * @return string
	 */
	protected function renderPreview($data) {
		$result = '';

		if ($data['tx_generic_gallery_collection']) {

			$result .= $this->renderTitle('collection');
			$result .= $this->renderCollectionPreview($data);

		} elseif ($data['tx_generic_gallery_images']) {

			$result .= $this->renderTitle('images');
			$result .= $this->renderImagesPreview($data);

		} elseif ($data['tx_generic_gallery_items']) {

			$result .= $this->renderTitle('collection');
			$result .= $this->renderItemsPreview($data);
		}

		return $result;
	}

	/**
	 * @todo
	 *
	 * @param array $data
	 * @return string
	 */
	protected function renderCollectionPreview($data) {
		return '';
	}

	/**
	 * @param array $data
	 * @return string
	 */
	protected function renderImagesPreview($data) {
		return $this->thumbCode(
			$data,
			'tt_content',
			'tx_generic_gallery_images',
			'tx_generic_gallery_picture_single',
			$GLOBALS['BACK_PATH']
		);
	}

	/**
	 * @param array $data
	 * @return string
	 */
	protected function renderItemsPreview($data) {
		$result = '';

		/* @var $database \TYPO3\CMS\Core\Database\DatabaseConnection */
		$database = $GLOBALS['TYPO3_DB'];
		$select = 'uid, title';
		$table = 'tx_generic_gallery_pictures';
		$where = 'tt_content_id = ' . $data['uid'];
		$where .= BackendUtility::BEenableFields($table);
		$order = 'sorting';
		$group = '';
		$limit = '';

		$res = $database->exec_SELECTquery($select, $table, $where, $group, $order, $limit);

		while (($row = $database->sql_fetch_assoc($res))) {
			if (is_array($row)) {
				$result .= $this->thumbCode(
					$data,
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
	 * @return bool
	 */
	protected function isEnabledInExtensionManager() {
		// get Extension Manager config
		$extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['generic_gallery']);

		if (intval($extensionConfiguration['enable_cms_layout']) !== 1) {
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Returns a linked image-tag for thumbnail(s)/fileicons/truetype-font-previews
	 * from a database row with a list of image files in a field All
	 * $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'] extension are made to
	 * thumbnails + ttf file (renders font-example) Thumbsnails are linked to the
	 * show_item.php script which will display further details.
	 *
	 * @param array $row Row is the database row from the table, $table.
	 * @param string $table Table name for $row (present in TCA)
	 * @param string $field Field is pointing to the list of image files
	 * @param string $fieldname Refrence field FAL
	 * @param string $backPath Back path prefix for image tag src="" field
	 * @param int|string $size Optional: $size is [w]x[h] of the thumbnail. 56 is default.
	 * @param boolean $linkInfoPopup Whether to wrap with a link opening the info popup
	 *
	 * @return string Thumbnail image tag.
	 *
	 * taken from TYPO3 6.2.3
	 */
	protected function thumbCode($row, $table, $field, $fieldname, $backPath, $size = '', $linkInfoPopup = TRUE) {
		$tcaConfig = $GLOBALS['TCA'][$table]['columns'][$field]['config'];
		// Check and parse the size parameter
		$sizeParts = array(64, 64);
		if (($size = trim($size))) {
			$sizeParts = explode('x', $size . 'x' . $size);
			if (!(int)$sizeParts[0]) {
				$size = '';
			}
		}
		$thumbData = '';
		// FAL references
		if ($tcaConfig['type'] === 'inline') {
			$sortingField = isset($tcaConfig['foreign_sortby']) ? $tcaConfig['foreign_sortby'] : '';
			$referenceUids = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
				'uid',
				'sys_file_reference',
				'tablenames = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($table, 'sys_file_reference') .
				' AND fieldname=' . $GLOBALS['TYPO3_DB']->fullQuoteStr($fieldname, 'sys_file_reference') .
				' AND uid_foreign=' . (int)$row['uid'] .
				BackendUtility::deleteClause('sys_file_reference') .
				BackendUtility::versioningPlaceholderClause('sys_file_reference'),
				'',
				$sortingField
			);
			foreach ($referenceUids as $referenceUid) {
				$fileReferenceObject = ResourceFactory::getInstance()->getFileReferenceObject($referenceUid['uid']);
				$fileObject = $fileReferenceObject->getOriginalFile();

				if ($fileObject->isMissing()) {
					$flashMessage = BackendUtility::getFlashMessageForMissingFile($fileObject);
					$thumbData .= $flashMessage->render();
					continue;
				}

				// Web image
				if (GeneralUtility::inList(
					$GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
					$fileReferenceObject->getExtension())
				) {
					$imageUrl = $fileObject->process(ProcessedFile::CONTEXT_IMAGEPREVIEW, array(
						'width' => $sizeParts[0],
						'height' => $sizeParts[1]
					))->getPublicUrl(TRUE);
					$imgTag = '<img src="' . $imageUrl . '" alt="' . htmlspecialchars($fileReferenceObject->getName()) . '" />';
				} else {
					// Icon
					$imgTag = IconUtility::getSpriteIconForResource(
						$fileObject,
						array('title' => $fileObject->getName())
					);
				}
				if ($linkInfoPopup) {
					$onClick = 'top.launchView(\'_FILE\',\'' . $fileObject->getUid() . '\',\'' . $backPath . '\'); return false;';
					$thumbData .= '<a href="#" onclick="' . htmlspecialchars($onClick) . '">' . $imgTag . '</a> ';
				} else {
					$thumbData .= $imgTag;
				}
			}
		}

		return $thumbData;
	}
}