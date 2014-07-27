<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010 Felix Nagel <info@felixnagel.com>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Plugin 'Generic Gallery' for the 'generic_gallery' extension.
 *
 * @author    Felix Nagel <info@felixnagel.com>
 * @package    TYPO3
 * @subpackage    tx_generic_gallery
 */
class tx_genericgallery_cms_layout extends tslib_pibase {

	/**
	 * Returns picture slide string
	 */
	public function getPictureTitle(&$params, $pObj) {
		if ($params['table'] === 'tx_generic_gallery_pictures') {
			if (strlen($params['row']['title']) > 0) {
				$params['title'] = $params['row']['title'];
			}
		}
	}


	/**
	 * Returns information about this extension's pi1 plugin
	 *
	 * @param    array $params Parameters to the hook
	 * @param    object $pObj A reference to calling object
	 * @return    string        Information about pi1 plugin
	 */
	public function getExtensionSummary($params, &$pObj) {
		$content = '';

		if ($params['row']['list_type'] == 'generic_gallery_pi1') {

			// get Extension Manager config
			$extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['generic_gallery']);

			if ($extensionConfiguration['enable_cms_layout']) {
				return '';
			}

			// add mode info
			if ($params['row']['tx_generic_gallery_images']) {
				$content .= $GLOBALS['LANG']->sL('LLL:EXT:generic_gallery/locallang_db.xml:generic_gallery_images');
			} else {
				$content .= $GLOBALS['LANG']->sL('LLL:EXT:generic_gallery/locallang_db.xml:generic_gallery_items');
			}

			$content .= '<br /><br />';

			if ($params['row']['tx_generic_gallery_images']) {
				$content .= $this->thumbCode(
					$params['row'],
					'tt_content',
					'tx_generic_gallery_images',
					'tx_generic_gallery_picture_single',
					$GLOBALS['BACK_PATH']
				);
			} else {
				$select = 'uid, title';
				$table = 'tx_generic_gallery_pictures';
				$where = 'tt_content_id = ' . $params['row']['uid'];
				$where .= t3lib_BEfunc::BEenableFields($table);
				$order = 'sorting';
				$group = '';
				$limit = '';
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $table, $where, $group, $order, $limit);

				while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
					if (is_array($row)) {
						$content .= $this->thumbCode(
							$row,
							'tx_generic_gallery_pictures',
							'images',
							'tx_generic_gallery_picture_single',
							$GLOBALS['BACK_PATH']
						);
					}
				}

				$GLOBALS['TYPO3_DB']->sql_free_result($res);
			}
		}

		return $content;
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
		if ($size = trim($size)) {
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
				TYPO3\CMS\Backend\Utility\BackendUtility::deleteClause('sys_file_reference') .
				TYPO3\CMS\Backend\Utility\BackendUtility::versioningPlaceholderClause('sys_file_reference'),
				'',
				$sortingField
			);
			foreach ($referenceUids as $referenceUid) {
				$fileReferenceObject = TYPO3\CMS\Core\Resource\ResourceFactory::getInstance()->getFileReferenceObject($referenceUid['uid']);
				$fileObject = $fileReferenceObject->getOriginalFile();

				if ($fileObject->isMissing()) {
					$flashMessage = \TYPO3\CMS\Core\Resource\Utility\BackendUtility::getFlashMessageForMissingFile($fileObject);
					$thumbData .= $flashMessage->render();
					continue;
				}

				// Web image
				if (TYPO3\CMS\Core\Utility\GeneralUtility::inList(
					$GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
					$fileReferenceObject->getExtension())
				) {
					$imageUrl = $fileObject->process(TYPO3\CMS\Core\Resource\ProcessedFile::CONTEXT_IMAGEPREVIEW, array(
						'width' => $sizeParts[0],
						'height' => $sizeParts[1]
					))->getPublicUrl(TRUE);
					$imgTag = '<img src="' . $imageUrl . '" alt="' . htmlspecialchars($fileReferenceObject->getName()) . '" />';
				} else {
					// Icon
					$imgTag = TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIconForResource(
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

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/generic_gallery/lib/class.tx_genericgallery_pi1_cms_layout.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/generic_gallery/lib/class.tx_genericgallery_pi1_cms_layout.php']);
}

?>