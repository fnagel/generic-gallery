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
	function getPictureTitle($params, $pObj) {

		if (strlen($params['row']['title']) > 0) {
			$params['title'] = $params['row']['title'];
		} else if ($params['row']['images']) {
			$helperArray = $this->getDAMImageData($params['row']['uid']);
			if (is_array($helperArray['rows'])) {
				$params['title'] = (strlen($helperArray['rows']['title']) > 0) ? $helperArray['rows']['title'] : $helperArray['rows']['file_name'];
			}
		}

		return $params;
	}


	/**
	 * Returns information about this extension's pi1 plugin
	 *
	 * @param    array $params Parameters to the hook
	 * @param    object $pObj A reference to calling object
	 * @return    string        Information about pi1 plugin
	 */
	function getExtensionSummary($params, &$pObj) {
		$content = '';

		if ($params['row']['list_type'] == 'generic_gallery_pi1') {

			// get Extension Manager config
			$extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['generic_gallery']);

			// add mode info
			if ($params['row']['tx_generic_gallery_images']) {
				$content .= $GLOBALS['LANG']->sL('LLL:EXT:generic_gallery/locallang_db.xml:generic_gallery_images');
			} else {
				$content .= $GLOBALS['LANG']->sL('LLL:EXT:generic_gallery/locallang_db.xml:generic_gallery_items');
			}

			// add image info
			if ($extensionConfiguration['enable_cms_layout']) {
				if ($params['row']['tx_generic_gallery_images']) {
					// get multiple images
//					$filesArray = tx_dam_db::getReferencedFiles("tt_content", $params['row']['uid'], 'tx_generic_gallery_picture_single','', 'tx_dam.*');
				} else {
					$filesArray = array();
					$filesArray["files"] = array();
					$filesArray["rows"] = array();

					$select = 'uid, title';
					$table = 'tx_generic_gallery_pictures';
					$where = 'tt_content_id = ' . $params['row']['uid'];
					// always (!) use TYPO3 default function for adding hidden = 0, deleted = 0, group and date statements
					$where .= t3lib_BEfunc::BEenableFields($table);
					$order = 'sorting';
					$group = '';
					$limit = '';
					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $table, $where, $group, $order, $limit);

					while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
						if (is_array($row)) {
							$helperArray = $this->getDAMImageData($row['uid']);
							if (is_array($helperArray)) {
								$uid = $helperArray["rows"]['uid'];
								$filesArray["files"][$uid] = $helperArray["files"];
								$filesArray["rows"][$uid] = $helperArray["rows"];
								// add item title if set
								if (strlen($row['title']) > 0) {
									$filesArray["rows"][$uid]['generic_gallery_title'] = $row['title'];
								}
							}
						}
					}

					$GLOBALS['TYPO3_DB']->sql_free_result($res);
				}
				// Wrap span-tags:
				$content .= '<br /><br />' . $this->renderSummery($filesArray);
			}
		}

		return $content;
	}

	function getDAMImageData($imgUid) {
		$damArray = array();
		$damArray["files"] = array();
		$damArray["rows"] = array();

//		$damFiles = tx_dam_db::getReferencedFiles('tx_generic_gallery_pictures', intval($imgUid), 'tx_generic_gallery_picture_single','', 'tx_dam.*');

		// check if our row is valid
		if (isset($damFiles['files']) && count($damFiles['files']) > 0) {
			$damArray["files"] = current($damFiles['files']);
			$damArray["rows"] = current($damFiles['rows']);

			return $damArray;
		}

		return false;
	}

	function renderSummery($damArray) {
		$out = "";
		$count = 0;
		$shortSum = (count($damArray['rows']) > 10) ? true : false;
		foreach ($damArray['rows'] as $rowDAM) {
//			$thumb = tx_dam_guiFunc::thumbnail($rowDAM);
			$title = "";
			if ($rowDAM['generic_gallery_title']) {
				$label = "<strong>" . $GLOBALS['LANG']->sL('LLL:EXT:generic_gallery/locallang_db.xml:generic_gallery_pictures.title') . ":</strong> ";
				$title = "<p>" . $label . $rowDAM['generic_gallery_title'] . "</p>";
			}
			if ($shortSum) {
				$thumb = '<div style="float:left; width:56px; height:56px; margin: 2px 5px 2px 0; padding: 3px; background-color:#fff; border:solid 1px #ccc;">' . $thumb . '</div>';
			} else {
//				$caption = tx_dam_guiFunc::meta_compileInfoData($rowDAM, 'title, file_name, description:truncate:50', 'paragraph');
				$thumb = '<div style="float:left; width:56px; height:56px; margin: 2px 5px 2px 0; padding: 5px; background-color:#fff; border:solid 1px #ccc;">' . $thumb . '</div>';
				$thumb = '<div>' . $thumb . $title . $caption . '</div><div style="clear:both"></div>';
			}
			$count++;

			$out .= $thumb;
		}
		if ($shortSum) $out = '<div>' . $out . '</div><div style="clear:both"></div>';

		return $out;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/generic_gallery/lib/class.tx_genericgallery_pi1_cms_layout.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/generic_gallery/lib/class.tx_genericgallery_pi1_cms_layout.php']);
}

?>