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

use \TYPO3\CMS\Core\Utility\GeneralUtility,
	\TYPO3\CMS\Extbase\Mvc\View\ViewInterface,
	\TYPO3\GgExtbase\Domain\Model\GalleryCollection;

/**
 * GalleryCollectionController
 */
class GalleryCollectionController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	protected $uid = NULL;

	protected $cObjData = array();

	protected $collection = NULL;

	protected $gallerySettings = array();

	protected $currentSettings = array();

	/**
	 * Object manager
	 *
	 * @inject
	 *
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 */
	protected $objectManager = NULL;

	/**
	 * Construct class
	 *
	 * @return \TYPO3\GgExtbase\Controller\GalleryCollectionController
	 */
	public function __construct() {
		$this->collection = new GalleryCollection();
	}

	protected function initializeAction() {
		$this->cObjData = $this->configurationManager->getContentObject()->data;
		$this->uid = (int) ($this->cObjData['_LOCALIZED_UID']) ? $this->cObjData['_LOCALIZED_UID'] : $this->cObjData['uid'];
		$this->gallerySettings = $this->settings['gallery'];
		$this->currentSettings = $this->gallerySettings[rtrim($this->cObjData['tx_generic_gallery_predefined'], '.')];

		$this->generateCollection();
	}

	protected function initializeView(ViewInterface $view) {
		$view->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName($this->currentSettings['template']));
	}

	/**
	 * Show gallery
	 *
	 * @return void
	 */
	public function showAction() {
		$this->view->assign('collection', $this->collection);
	}

	/**
	 * Generate collection item
	 *
	 * @return void
	 */
	protected function generateCollection() {
		if ($this->cObjData['tx_generic_gallery_items']) {
			$this->collection->addAll($this->getSigleItems());
			return;
		}

		if ($this->cObjData['tx_generic_gallery_images']) {
			$this->collection->addAllFromFiles($this->getMultipleImages());
			return;
		}

		if ($this->cObjData['tx_generic_gallery_collection']) {
			$this->collection->addAllFromFiles($this->getCollection());
			return;
		}
	}

	/**
	 * Method to get the image data from one FCE
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult
	 */
	protected function getSigleItems() {
		/* @var $itemRepository \TYPO3\GgExtbase\Domain\Repository\GalleryItemRepository */
		$itemRepository = $this->objectManager->get('TYPO3\\GgExtbase\\Domain\\Repository\\GalleryItemRepository');
		$items = $itemRepository->findByTtContentUid($this->uid);

		return $items;
	}

	/**
	 * @return array
	 */
	protected function getMultipleImages() {
		/* @var $fileRepository \TYPO3\CMS\Core\Resource\FileRepository */
		$fileRepository = $this->objectManager->get('TYPO3\\CMS\\Core\\Resource\\FileRepository');

		return $fileRepository->findByRelation('tt_content', 'tx_generic_gallery_picture_single', $this->uid);
	}

	/**
	 * @return array
	 */
	protected function getCollection() {
		/* @var $resourceFactory \TYPO3\CMS\Core\Resource\ResourceFactory */
		$resourceFactory = $this->objectManager->get('TYPO3\\CMS\\Core\\Resource\\ResourceFactory');

		/* @var $collection \TYPO3\CMS\Core\Resource\Collection\AbstractFileCollection */
		$collection = $resourceFactory->getCollectionObject((int) $this->cObjData['tx_generic_gallery_collection']);
		$collection->loadContents();

		return $collection->getItems();

	}

}