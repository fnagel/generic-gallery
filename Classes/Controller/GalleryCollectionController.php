<?php

namespace TYPO3\GgExtbase\Controller;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014 Felix Nagel <info@felixnagel.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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

use \TYPO3\CMS\Extbase\Persistence\ObjectStorage,
	\TYPO3\GgExtbase\Domain\Model\GalleryItem;

/**
 * GalleryCollectionController
 */
class GalleryCollectionController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	protected $cObjData = array();

	protected $gallery = NULL;

	/**
	 * File repository
	 *
	 * @inject
	 *
	 * @var \TYPO3\CMS\Core\Resource\FileRepository
	 */
	protected $fileRepository = NULL;


	/**
	 * Construct class
	 *
	 *
	 * @return \TYPO3\GgExtbase\Domain\Model\GalleryCollection
	 */
	public function __construct() {
		$this->gallery = new GalleryCollection();
	}

	protected function initializeAction() {
		$this->cObjData = $this->configurationManager->getContentObject()->data;

		$this->uid = ($this->cObjData['_LOCALIZED_UID']) ? $this->cObjData['_LOCALIZED_UID'] : intval($this->cObjData['uid']);
		$this->getItems();
	}

	protected function getGalleryMode() {
		if ($this->cObjData['tx_generic_gallery_items']) {
			return 'items';
		}

		if ($this->cObjData['tx_generic_gallery_images']) {
			return 'images';
		}

		if ($this->cObjData['tx_generic_gallery_collection']) {
			return 'collaction';
		}

		return '';
	}

	protected function getItems() {

		switch($this->getGalleryMode()) {
			case 'items':
				$this->gallery->addAll($this->getSigleImages());
				break;

			case 'images':
				$this->gallery->addAll($this->getMultipleImages());
				break;

			case 'collaction':
				$this->gallery->addAll();
				break;

			default:
				throw new \Exception('Gallery mode undefined.');
		}
	}

	/**
	 * Method to get the image data from one FCE
	 *
	 * @return array Array with the picture rows
	 */
	protected function getSigleImages() {
		/* @var $fileRepository TYPO3\CMS\Core\Resource\FileRepository */
		$fileRepository = t3lib_div::makeInstance('TYPO3\\CMS\\Core\\Resource\\FileRepository');
		$fileObjects = $fileRepository->findByRelation('tx_generic_gallery_pictures', 'tx_generic_gallery_picture_single', $imgUid);

		$item = new GalleryItem();

		/* @var $object \TYPO3\CMS\Core\Resource\FileReference */
		$item->setImage($object);
		$item->setTitle($object->getTitle());
		$item->setLink($object->getPublicUrl());

		$this->items->attach($item);

		$data = array();
		$data['files'] = array();
		$data['dam'] = array();

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
		return $this->fileRepository->findByRelation('tt_content', 'tx_generic_gallery_picture_single', $this->uid);
	}

	/**
	 * Show gallery
	 *
	 * @return void
	 */
	public function showAction() {
		$this->view->assign('gallery', $this->gallery);
	}

}