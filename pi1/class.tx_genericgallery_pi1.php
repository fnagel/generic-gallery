<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2014 Felix Nagel <f.nagel@paints.de>
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
class tx_genericgallery_pi1 extends tslib_pibase {
	/**
	 * @var string
	 */
	public $prefixId = 'tx_genericgallery_pi1';

	/**
	 * @var string
	 */
	public $scriptRelPath = 'pi1/class.tx_generic_gallery_pi1.php';

	/**
	 * @var string
	 */
	public $extKey = 'generic_gallery';

	/**
	 * @var array
	 */
	public $conf;

	/**
	 * @var tslib_cObj
	 */
	public $cObj;


	/**
	 * @var array
	 */
	protected $currentConf = array();

	/**
	 * @var bool
	 */
	public $pi_checkCHash = TRUE;

	/**
	 * @var array
	 */
	protected $images = array();

	/**
	 * @var null
	 */
	protected $templateHtml = NULL;

	/**
	 * @var bool
	 */
	protected $error = FALSE;

	/**
	 * The main method of the PlugIn
	 *
	 * @param    string $content : The PlugIn content
	 * @param    array $conf : The PlugIn configuration
	 * @return    The content that is displayed on the website
	 */
	public function main($content, $conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();

		// set localized UID
		$this->uid = ($this->cObj->data['_LOCALIZED_UID']) ? intval($this->cObj->data['_LOCALIZED_UID']) : intval($this->cObj->data['uid']);

		// test if a configuration is present
		if (!is_array($this->conf['gallery.'])) {
			$this->handleError('No configuration array found. Please check your TypoScript configuration.');
		} else {
			if (strlen($this->cObj->data['tx_generic_gallery_predefined']) === 0) {
				$this->handleError('Please choose a gallery type.');
			} else {
				// store current gallery configuration
				foreach ($this->conf['gallery.'] as $configName => $configArray) {
					if ($configName == $this->cObj->data['tx_generic_gallery_predefined']) {
						$this->currentConf = $this->conf['gallery.'][$configName];
					}
				}
				// check if there is a matching configuration
				if (!is_array($this->currentConf)) {
					$this->handleError('No matching configuration array found. Please check your TypoScript configuration and gallery type defined within the FCE.');
				} else {
					$content = $this->renderGallery();
				}
			}
		}

		// set error message
		if ($this->error) {
			$append = $content;
			$content = '<div style="text-align: left; text-size: 10px; color: red; margin: 5px; padding: 5px; background: white; border: 3px solid red;"><strong>Generic Gallery Extension Error</strong><br /><p><em>PID: ' . $this->cObj->data['pid'] . '</em><br /><em>UID: ' . $this->cObj->data['uid'] . '</em></p><p>' . implode('<br />', $this->error) . '</p></div>';
			$content .= $append;
		}

		if (isset($this->conf['baseWrap.'])) {
			return $this->cObj->stdWrap($content, $this->conf['baseWrap.']);
		} else {
			return $this->pi_wrapInBaseClass($content);
		}
	}


	/**
	 * Method to get the image data from one FCE
	 *
	 * @return    Array with the picture rows
	 */

	protected function renderGallery() {
		$this->images = array();
		$this->imageMaxWidth = 0;
		$this->imageMaxHeight = 0;

		// get the template
		$this->getTemplateFile();

		// get image array
		if ($this->cObj->data['tx_generic_gallery_images']) {
			$this->images = $this->getMultipleImages();
		} else {
			$this->images = $this->getSigleImages();
		}

		// shuffle images?
		if ($this->currentConf['shuffleImages']) {
			// get keys
			$index = array_keys($this->images['files']);
			// shuffle keys
			shuffle($index);
			// reorder all sub arrays
			foreach ($this->images as $key => $data) {
				$helper = array_combine($index, $data);
				ksort($helper);
				$this->images[$key] = $helper;
			}
		}

		// get image markers
		$this->imagesMarker = $this->getImageMarkerArray();

		// get custom markers
		$this->customMarker = $this->getCustomMarkerArray();

		// render code output
		$code = $this->renderTemplate('code');
		if ($this->currentConf['codeToFooter']) {
			$GLOBALS['TSFE']->getPageRenderer()->addJsFooterInlineCode($this->prefixId . '_' . $this->uid, $code);
		} else {
			$GLOBALS['TSFE']->getPageRenderer()->addJsInlineCode($this->prefixId . '_' . $this->uid, $code);
		}

		// render content output
		$content = $this->renderTemplate('content');

		// cache eID content
		if ($this->currentConf['ajax']) {
			// save remote data in cache
			$this->setCache($this->uid, 'ajax-content', $this->imagesMarker);
		}

		// add header data from template file
		$templateFiles = $this->cObj->getSubpart($this->templateHtml, '###TEMPLATE_FILES###');
		$subpartArray = $this->getDefaultMarker();
		$GLOBALS['TSFE']->getPageRenderer()->addHeaderData($this->cObj->substituteMarkerArrayCached($templateFiles, $subpartArray));

		return $content;
	}


	/**
	 * Method to render the first image and JS headerdata
	 *
	 * @param $template
	 *
	 * @return    array Array with the picture rows
	 */
	protected function renderTemplate($template) {
		// which template subpart should be rendered?
		if ($template == 'content') {
			$templateMarker = '###TEMPLATE_CONTENT###';
		} else {
			$templateMarker = '###TEMPLATE_CODE###';
		}

		// Extract subparts from the template
		$subparts['template'] = $this->cObj->getSubpart($this->templateHtml, $templateMarker);
		$subparts['row'] = $this->cObj->getSubpart($subparts['template'], '###ROW###');

		// Fill default subpart marker
		$subpartArray = $this->getDefaultMarker();

		if ($subparts['row'] != '' && preg_match_all('/###IMAGE_\d+###/', $subparts['row'], $imageMarker)) {

			// count image marker
			$imageMarkerCount = count($imageMarker[0]) / 2;

			for ($x = 1; $x <= $imageMarkerCount; $x++) {
				// prepare different image marker subparts
				$subparts['image_' . $x] = $this->cObj->getSubpart($subparts['template'], '###IMAGE_' . $x . '###');
				// remove unnecessary image marker from row subpart
				if ($x > 1) {
					$subparts['row'] = $this->cObj->substituteSubpart($subparts['row'], '###IMAGE_' . $x . '###', '');
				}
			}

			$contentImages = '';
			$switchImageMarker = 1;
			$switchRow = 0;
			$contentImageArray = array();

			// check for range option
			if ($this->currentConf['range.'][$template]) {
				$indexArray = t3lib_div::intExplode('-', $this->currentConf['range.'][$template]);
				$indexStart = $indexArray[0] - 1;
				$indexEnd = (count($this->images['files']) < $indexArray[1]) ? count($this->images['files']) : $indexArray[1];
			} else {
				$indexStart = 0;
				$indexEnd = count($this->images['files']);
			}

			// render each defined image
			for ($y = $indexStart; $y < $indexEnd; $y++) {
				// Fill marker array
				$imageMarkerArray = $this->imagesMarker[$y];

				// Substitute markers and append to result string
				$contentImages .= $this->cObj->substituteMarkerArrayCached($subparts['image_' . $switchImageMarker], $imageMarkerArray);

				// make rows
				$switchImageMarker++;
				if ($switchImageMarker > $imageMarkerCount || $y == $indexEnd - 1) {
					$switchImageMarker = 1;

					$contentImageArray[$switchRow] = $contentImages;
					$contentImages = '';
					$switchRow++;
				}
			}

			$contentRows = '';
			// add all rows
			foreach ($contentImageArray as $index => $rowContent) {
				$contentRows .= $this->cObj->substituteSubpart($subparts['row'], '###IMAGE_1###', $rowContent);
			}

			// Fill subpart marker
			$subpartArray['###ROW###'] = $contentRows;
		}

		// Complete the template expansion by replacing the 'row' marker in the template
		$content = $this->cObj->substituteMarkerArrayCached($subparts['template'], NULL, $subpartArray);

		return $content;
	}

	/**
	 * @return array
	 */
	protected function getImageMarkerArray() {
		$imageMarkerArray = array();

		for ($y = 0; $y < count($this->images['files']); $y++) {
			// Fill marker array
			$imageMarkerArray[$y] = $this->renderImageMarker($y);
			$imageMarkerArray[$y]['###TITLE###'] = $this->images['title'][$y];
			$imageMarkerArray[$y]['###TXT###'] = $this->images['text'][$y];
			$imageMarkerArray[$y]['###LINK###'] = $this->images['link'][$y];
			$imageMarkerArray[$y]['###FILEPATH###'] = $this->images['files'][$y];
			$imageMarkerArray[$y]['###COUNT###'] = $y + 1;
		}

		return $imageMarkerArray;
	}

	/**
	 * @return array
	 */
	protected function getCustomMarkerArray() {
		$customMarkerArray = array();

		if (is_array($this->currentConf['custom.'])) {
			foreach ($this->currentConf['custom.'] as $element) {
				if (strlen($element['marker']) > 0) {
					$customMarker = trim($element['marker']);
					if (t3lib_div::compat_version('4.6')) {
						$imageIndex = t3lib_utility_Math::convertToPositiveInteger($element['index']);
					} else {
						$imageIndex = t3lib_div::intval_positive($element['index']);
					}
					if ($imageIndex) {
						if (is_array($element['content.'])) {
							// type: image
							$imgConf = array();
							// add file path
							$imgConf['file'] = $this->imagesMarker[$imageIndex - 1]['###FILEPATH###'];
							// add image conf from TS array
							$imgConf['file.'] = $element['content.'];
							$customMarkerArray[$customMarker] = $this->cObj->IMG_RESOURCE($imgConf);
						} else {
							// type: marker
							$content = trim($element['content']);
							if (array_key_exists($content, $this->imagesMarker[$imageIndex - 1])) {
								$customMarkerArray[$customMarker] = $this->imagesMarker[$imageIndex - 1][$content];
							}
						}
					} else {
						// type: UID of an FCE
						$renderedOutput = '';
						foreach ($element['content.'] as $name => $options) {
							if (!strstr($name, '.')) {
								if (!isset($element['content.'][$name . '.'])) {
									return $element['content.'][$name];
								}
								if (!isset($element['content.'][$name . '.']['sanitize'])) {
									$element['content.'][$name . '.']['sanitize'] = 1;
								}
								$renderedOutput .= $this->cObj->cObjGetSingle($element['content.'][$name], $element['content.'][$name . '.']);
							}
						}
						$customMarkerArray[$customMarker] = $renderedOutput;
					}
				}
			}
		}
		return $customMarkerArray;
	}

	/**
	 * @param $imageIndex
	 * @return array
	 */
	protected function renderImageMarker($imageIndex) {
		$markerArray = array();

		// render custom marker
		if (is_array($this->currentConf['marker.'])) {
			foreach ($this->currentConf['marker.'] as $index => $fields) {
				if (t3lib_div::isFirstPartOfStr($index, 'IMAGE_')) {
					$fieldsArray = t3lib_div::trimExplode('//', $fields, TRUE);
					foreach ($fieldsArray as $field) {
						if ($this->images['dam'][$imageIndex][$field]) {
							$markerArray['###' . $index . '###'] = $this->images['dam'][$imageIndex][$field];
							break;
						} else {
							$markerArray['###' . $index . '###'] = '';
						}
					}
				}
			}
		}

		// add file path
		$imgConf['file'] = $this->images['files'][$imageIndex];

		if (is_array($this->currentConf['image.'])) {
			// IMG conf
			$imgConf['file.'] = $this->currentConf['image.'];
			$markerArray['###IMAGE###'] = $this->cObj->IMG_RESOURCE($imgConf);

			// get actually image size
			$imageSizeArray = $GLOBALS['TSFE']->lastImgResourceInfo;
			if (is_array($imageSizeArray)) {
				$markerArray['###IMAGE_WIDTH###'] = $imageSizeArray[0];
				$markerArray['###IMAGE_HEIGHT###'] = $imageSizeArray[1];
				if ($imageSizeArray[0] > $this->imageMaxWidth) {
					$this->imageMaxWidth = $imageSizeArray[0];
				}
				if ($imageSizeArray[1] > $this->imageMaxHeight) {
					$this->imageMaxHeight = $imageSizeArray[1];
				}
			}
		}

		// process thumbs
		if (is_array($this->currentConf['thumb.'])) {
			foreach ($this->currentConf['thumb.'] as $index => $confArray) {
				$imgConf['file.'] = $confArray;
				$indexPic = substr($index, 0, -1);
				$markerArray['###THUMB_' . $indexPic . '###'] = $this->cObj->IMG_RESOURCE($imgConf);

				$imageSizeArray = $GLOBALS['TSFE']->lastImgResourceInfo;
				if (is_array($imageSizeArray)) {
					$markerArray['###THUMB_' . $indexPic . '_WIDTH###'] = $imageSizeArray[0];
					$markerArray['###THUMB_' . $indexPic . '_HEIGHT###'] = $imageSizeArray[1];
				}
			}
		}
		return $markerArray;
	}

	/**
	 * @param $imgUid
	 *
	 * @return array
	 */
	protected function getDAMImageData($imgUid) {
		$data = array();

		/* @var $fileRepository TYPO3\CMS\Core\Resource\FileRepository */
		$fileRepository = t3lib_div::makeInstance('TYPO3\\CMS\\Core\\Resource\\FileRepository');
		$fileObjects = $fileRepository->findByRelation('tx_generic_gallery_pictures', 'tx_generic_gallery_picture_single', $imgUid);

		if (count($fileObjects) > 0) {
			/* @var $file TYPO3\CMS\Core\Resource\FileReference */
			$file = current($fileObjects);
			$data['files'] = $file->getPublicUrl();
			$data['dam'] = $file->getOriginalFile()->getProperties();
		}

		return $data;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	protected function prepareMetaData($data) {
		foreach ($data as $key => $value) {
			switch($key) {
				case 'flash':
					if (isset($GLOBALS['TCA']['sys_file_metadata']['columns']['flash']['config']['items'])) {
						$items = (array) $GLOBALS['TCA']['sys_file_metadata']['columns']['flash']['config']['items'];
						foreach ($items as $item) {
							if ($item[1] === $value) {
								$data[$key] = $item[0];
							}
						}
					}
					break;

				default:
			}
		}

		return $data;
	}

	/**
	 * Method to get the image data from one FCE
	 *
	 * @return array Array with the picture rows
	 */
	protected function getSigleImages() {
		$damArray = array();
		$damArray['files'] = array();
		$damArray['dam'] = array();

		$select = 'uid, pid, title, link, images, contents';
		$table = 'tx_generic_gallery_pictures';
		$where = 'tt_content_id = ' . $this->uid;
		$where .= $GLOBALS['TSFE']->sys_page->enableFields($table);
		$order = 'sorting';
		$group = '';
		$limit = '';
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $table, $where, $group, $order, $limit);

		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			if (is_array($row)) {
				// make typolink
				$linkConf = array(
					'parameter' => $row['link'],
					'useCacheHash' => TRUE
				);
				$data = $this->getDAMImageData($row['uid']);
				$damArray['files'][] = $data['files'];
				$damArray['dam'][] = $this->prepareMetaData($data['dam']);
				$damArray['text'][] = $this->getDescription($row['uid']);
				$damArray['title'][] = htmlspecialchars($row['title']);
				$damArray['link'][] = $this->cObj->typoLink_URL($linkConf);
			}
		}

		$GLOBALS['TYPO3_DB']->sql_free_result($res);

		return $damArray;
	}

	/**
	 * @return array
	 */
	protected function getMultipleImages() {
		$damArray = array();
		$damArray['files'] = array();
		$damArray['dam'] = array();

		/* @var $fileRepository TYPO3\CMS\Core\Resource\FileRepository */
		$fileRepository = t3lib_div::makeInstance('TYPO3\\CMS\\Core\\Resource\\FileRepository');
		$fileObjects = $fileRepository->findByRelation('tt_content', 'tx_generic_gallery_picture_single', $this->uid);

		/* @var $file TYPO3\CMS\Core\Resource\FileReference */
		foreach ($fileObjects as $file) {
			$damArray['files'][] = $file->getPublicUrl();
			$damArray['dam'][] = $this->prepareMetaData($file->getOriginalFile()->getProperties());
			$damArray['title'][] = $file->getTitle();
			$damArray['text'][] = $file->getDescription();
			$damArray['link'][] = '';
		}

		return $damArray;
	}

	/**
	 * Method to render the bodytext areas for every image
	 *
	 * @param $pictureUid
	 *
	 * @return   string The string with our imagemap
	 */
	protected function getDescription($pictureUid) {

		$select = 'uid, pid, bodytext, position, width';
		$table = 'tx_generic_gallery_content';
		$where = 'pictures_id = ' . $pictureUid;
		$where .= $GLOBALS['TSFE']->sys_page->enableFields($table);
		$order = '';
		$group = '';
		$limit = '';
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $table, $where, $group, $order, $limit);

		$content = '';
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			if (is_array($row)) {
				// parse RTE text
				// TODO make RTE configurable
				$parsedBodyText = $this->pi_RTEcssText($row['bodytext']);
				// remove linebreaks so we can use the text within JS strings
				$parsedBodyText = preg_replace('/\n\r|\r\n|\n|\r/s', '', $parsedBodyText);
				$parsedBodyText = t3lib_div::slashJS($parsedBodyText);

				// get coordinates
				$coordsArray = t3lib_div::intExplode(',', $row['position'], TRUE);

				if (count($coordsArray) && $coordsArray[0] && $coordsArray[1]) {
					// get width of the rte
					$width = '';
					if (strlen($row['width']) > 0) {
						$width = 'width: ' . (int) $row['width'] . 'px; ';
					}
					$content .= '<div style="position: absolute; ' . $width . 'top: ' . $coordsArray[0] . 'px; left: ' . $coordsArray[1] . 'px;">' . $parsedBodyText . '</div>';
				} else {
					$content .= $parsedBodyText;
				}
			}
		}

		$GLOBALS['TYPO3_DB']->sql_free_result($res);

		return $content;
	}

	/**
	 * Function to render the default marker
	 *
	 */
	protected function getDefaultMarker() {
		$subpartArray = $this->customMarker;

		$subpartArray['###UID###'] = intval($this->cObj->data['uid']);
		$subpartArray['###_LOCALIZED_UID###'] = intval($this->cObj->data['_LOCALIZED_UID']);
		$subpartArray['###IMAGE_MAX_WIDTH###'] = $this->imageMaxWidth;
		$subpartArray['###IMAGE_MAX_HEIGHT###'] = $this->imageMaxHeight;

		return $subpartArray;
	}

	/**
	 * Function to fetch the template file
	 *
	 * @return void
	 */
	protected function getTemplateFile() {
		// Get the template
		$templateFile = (strlen(trim($this->currentConf['template'])) > 0) ? trim($this->currentConf['template']) : 'EXT:generic_gallery/res/templates/default.html';
		$this->templateHtml = $this->cObj->fileResource($templateFile);

		if (!$this->templateHtml) {
			$this->handleError('Error while fetching the template file: <em>' . $templateFile . '</em>');
		}
	}

	/**
	 * Stores data in cache
	 *
	 * @param string $key cache key
	 * @param string $identifier unique identifier
	 * @param array $data your data to store in cache
	 *
	 * @return void
	 */
	protected function setCache($key, $identifier, $data) {
		$cacheIdentifier = $this->extKey . '-' . $identifier;
		$cacheHash = md5($cacheIdentifier . $key);
		t3lib_pageSelect::storeHash(
			$cacheHash,
			serialize($data),
			$cacheIdentifier
		);
	}

	/**
	 * Handles error output for frontend and TYPO3 logging
	 *
	 * @param  string $msg Message to output
	 * @see    t3lib::devLog()
	 * @see    t3lib_div::sysLog()
	 *
	 * @return    void
	 */
	protected function handleError($msg) {
		// prepare FE output
		if ($this->error === FALSE) {
			$this->error = array();
		}
		$this->error[] = $msg . '<br />';

		t3lib_div::sysLog($msg, $this->extKey, 3);
		// write dev log if enabled
		if ($GLOBALS['TYPO3_CONF_VARS']['SYS']['enable_DLOG']) {
			t3lib_div::devLog($msg, $this->extKey, 3);
		}
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/generic_gallery/pi1/class.tx_genericgallery_pi1.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/generic_gallery/pi1/class.tx_genericgallery_pi1.php']);
}

?>