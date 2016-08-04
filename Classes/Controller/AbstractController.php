<?php

namespace TYPO3\GenericGallery\Controller;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014-2016 Felix Nagel <info@felixnagel.com>
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

use TYPO3\GenericGallery\Domain\Model\GalleryCollection;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

/**
 * BaseController.
 */
abstract class AbstractController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    const GALLERY_TYPE_SINGLE = 'single';
    const GALLERY_TYPE_IMAGES = 'images';
    const GALLERY_TYPE_COLLECTION = 'collection';

    /**
     * @var null
     */
    protected $uid = null;

    /**
     * @var array
     */
    protected $cObjData = array();

    /**
     * @var array
     */
    protected $gallerySettings = array();

    /**
     * @var array
     */
    protected $currentSettings = array();

    /**
     * @var string
     */
    protected $galleryType = null;

    /**
     * @var string
     */
    protected $template = null;

    /**
     * GalleryCollection.
     *
     * @var \TYPO3\GenericGallery\Domain\Model\GalleryCollection
     */
    protected $collection = null;

    /**
     * Object manager.
     *
     * @inject
     *
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected $objectManager = null;

    /**
     * Construct class.
     */
    public function __construct()
    {
        parent::__construct();

        $this->collection = new GalleryCollection();
    }

    /**
     * Get current plugin CE's uid.
     *
     * @return int
     */
    protected function getContentElementUid()
    {
        if ($this->uid === null) {
            $data = $this->configurationManager->getContentObject()->data;
            $this->uid = ($data['_LOCALIZED_UID']) ? $data['_LOCALIZED_UID'] : $data['uid'];
        }

        return (int) $this->uid;
    }

    /**
     * {@inheritdoc}
     */
    protected function initializeView(ViewInterface $view)
    {
        $this->view->assign('uid', $this->getContentElementUid());
        $this->view->assign('galleryType', $this->galleryType);

        $template = GeneralUtility::getFileAbsFileName($this->template);

        if ($template !== '') {
            $view->setTemplatePathAndFilename($template);
        } else {
            $this->logError('Template for settings.gallery.'.$this->galleryKey.' not found!');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function initializeAction()
    {
        $this->cObjData = $this->configurationManager->getContentObject()->data;
        $this->gallerySettings = $this->settings['gallery'];
        $this->galleryKey = rtrim($this->cObjData['tx_generic_gallery_predefined'], '.');
        $this->currentSettings = $this->gallerySettings[$this->galleryKey];

        $this->determineGalleryType();
        $this->generateCollection();
    }

    /**
     * Generate collection item.
     */
    protected function generateCollection()
    {
        switch ($this->galleryType) {
            case self::GALLERY_TYPE_SINGLE:
                $this->collection->addAll($this->getSigleItems());
                break;

            case self::GALLERY_TYPE_IMAGES:
                $this->collection->addAllFromFiles($this->getMultipleImages());
                break;

            case self::GALLERY_TYPE_COLLECTION:
                $this->collection->addAllFromFiles($this->getCollection());
                break;

            default;
        }
    }

    /**
     * Determine gallery type.
     */
    protected function determineGalleryType()
    {
        if ($this->cObjData['tx_generic_gallery_collection']) {
            $this->setGalleryType(self::GALLERY_TYPE_COLLECTION);

            return;
        }

        if ($this->cObjData['tx_generic_gallery_images']) {
            $this->setGalleryType(self::GALLERY_TYPE_IMAGES);

            return;
        }

        if ($this->cObjData['tx_generic_gallery_items']) {
            $this->setGalleryType(self::GALLERY_TYPE_SINGLE);

            return;
        }
    }

    /**
     * Set gallery tpye.
     *
     * @param string $key
     */
    protected function setGalleryType($key)
    {
        $this->galleryType = $key;
    }

    /**
     * Method to get the image data from one FCE.
     *
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult
     */
    protected function getSigleItems()
    {
        /* @var $itemRepository \TYPO3\GenericGallery\Domain\Repository\GalleryItemRepository */
        $itemRepository = $this->objectManager->get('TYPO3\\GenericGallery\\Domain\\Repository\\GalleryItemRepository');
        $items = $itemRepository->findByTtContentUid($this->getContentElementUid());

        return $items;
    }

    /**
     * @return array
     */
    protected function getMultipleImages()
    {
        /* @var $fileRepository \TYPO3\CMS\Core\Resource\FileRepository */
        $fileRepository = $this->objectManager->get('TYPO3\\CMS\\Core\\Resource\\FileRepository');

        return $fileRepository->findByRelation(
            'tt_content', 'tx_generic_gallery_picture_single', $this->getContentElementUid()
        );
    }

    /**
     * @return array
     */
    protected function getCollection()
    {
        /* @var $resourceFactory \TYPO3\CMS\Core\Resource\ResourceFactory */
        $resourceFactory = $this->objectManager->get('TYPO3\\CMS\\Core\\Resource\\ResourceFactory');

        /* @var $collection \TYPO3\CMS\Core\Resource\Collection\AbstractFileCollection */
        $collection = $resourceFactory->getCollectionObject((int) $this->cObjData['tx_generic_gallery_collection']);
        $collection->loadContents();

        return $collection->getItems();
    }

    /**
     * Log error.
     *
     * @param string $message Error message
     * @param int    $error   Error level. 0 = message, 1 = error (user problem), 2 = System Error (which should not happen), 3 = security notice (admin)
     */
    protected function logError($message = '', $error = 2)
    {
        /* @var $backendUserAuthentication \TYPO3\CMS\Core\Authentication\BackendUserAuthentication */
        $backendUserAuthentication = GeneralUtility::makeInstance(
            'TYPO3\\CMS\\Core\\Authentication\\BackendUserAuthentication'
        );
        $backendUserAuthentication->simplelog('Error: '.$message, 'tx_generic_gallery', $error);
    }
}
