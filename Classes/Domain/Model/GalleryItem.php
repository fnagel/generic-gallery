<?php

namespace FelixNagel\GenericGallery\Domain\Model;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014-2018 Felix Nagel <info@felixnagel.com>
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

use TYPO3\CMS\Core\Resource\FileReference as CoreFileReference;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference as ExtbaseFileReference;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Service\ImageService;

/**
 * Class GalleryItem.
 */
class GalleryItem extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    const FILE_REFERENCE_IDENTIFIER_PREFIX = 'file-';

    /**
     * tt_content UID.
     *
     * @var int
     */
    protected $ttContentUid;

    /**
     * title.
     *
     * @var string
     */
    protected $title;

    /**
     * link.
     *
     * @var string
     */
    protected $link;

    /**
     * image.
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    protected $imageReference = null;

    /**
     * image.
     *
     * @var \TYPO3\CMS\Core\Resource\File
     */
    protected $image = null;

    /**
     * textItems.
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FelixNagel\GenericGallery\Domain\Model\TextItem>
     */
    protected $textItems;

    /**
     * Construct class.
     */
    public function __construct()
    {
        $this->textItems = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * @return string
     */
    public function getUid()
    {
        $uid = parent::getUid();

        if ($uid === null) {
            $uid = self::FILE_REFERENCE_IDENTIFIER_PREFIX.$this->getImage()->getUid();
        }

        return $uid;
    }

    /**
     * If object is virtual.
     *
     * Virtual means it's generated and not a DB relation
     * So, if the object is virtual the plugin is of type
     * 'images' or 'collection'
     *
     * @return bool
     */
    public function isVirtual()
    {
        return !((bool) parent::getUid());
    }

    /**
     * @param int $ttContentUid
     */
    public function setTtContentUid($ttContentUid)
    {
        $this->ttContentUid = $ttContentUid;
    }

    /**
     * @return int
     */
    public function getTtContentUid()
    {
        return $this->ttContentUid;
    }

    /**
     * Returns the title.
     *
     * @return string $title
     */
    public function getTitle()
    {
        if ($this->isVirtual()) {
            return $this->getImageData()['title'];
        }

        return $this->title;
    }

    /**
     * Sets the title.
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the link.
     *
     * @return string $link
     */
    public function getLink()
    {
        if ($this->isVirtual() || $this->link === '') {
            if (
                $this->imageReference !== null &&
                $this->imageReference->getOriginalResource()->getProperty('crop') !== null
            ) {
                // Render cropped image if reference with crop available
                return $this->getCroppedImageLinkFromReference();
            }

            return $this->getImage()->getPublicUrl();
        }

        return $this->link;
    }

    /**
     * Get url to cropped image from reference.
     *
     * @return string
     */
    protected function getCroppedImageLinkFromReference()
    {
        /* @var $objectManager \TYPO3\CMS\Extbase\Object\ObjectManagerInterface */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        /* @var $imageService ImageService */
        $imageService = $objectManager->get(ImageService::class);

        $processedImage = $imageService->applyProcessingInstructions(
            $this->imageReference->getOriginalResource(),
            ['crop' => $this->imageReference->getOriginalResource()->getProperty('crop')]
        );

        return $imageService->getImageUri($processedImage);
    }

    /**
     * Sets the link.
     *
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * Returns the image.
     *
     * @return \TYPO3\CMS\Core\Resource\File $image
     */
    public function getImage()
    {
        if ($this->image === null) {
            return $this->imageReference->getOriginalResource()->getOriginalFile();
        }

        return $this->image;
    }

    /**
     * Shortcut for image properties.
     *
     * @return array
     */
    public function getImageData()
    {
        $imageData = $this->getImage()->getProperties();

        if ($this->imageReference !== null) {
            // Overwrite with merged reference inline data
            $imageData = $this->imageReference->getOriginalResource()->getProperties();
        }

        // Merge with modified meta data
        $imageData = array_merge($imageData, $this->getAdditionalImageProperties());

        return $imageData;
    }

    /**
     * Return formatted image properties.
     *
     * @return array
     */
    private function getAdditionalImageProperties()
    {
        $properties = $this->getImage()->getProperties();
        $data = [];

        // process exif data
        $data['shutter_speed_value'] = $properties['shutter_speed_value'].'s';
        $data['aperture_value'] = 'f/'.$properties['aperture_value'];
        $data['focal_length'] = $properties['focal_length'].'mm';
        $data['iso_speed_ratings'] = 'ISO'.$properties['iso_speed_ratings'];

        // process flash data
        if (isset($GLOBALS['TCA']['sys_file_metadata']['columns']['flash']['config']['items'])) {
            $items = (array) $GLOBALS['TCA']['sys_file_metadata']['columns']['flash']['config']['items'];
            foreach ($items as $item) {
                if ((int) $item[1] === (int) $properties['flash']) {
                    $data['flash'] = $item[0];
                }
            }
        }

        return $data;
    }

    /**
     * Sets the image.
     *
     * @param \TYPO3\CMS\Core\Resource\File $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * Sets the imageReference.
     *
     * @param CoreFileReference|ExtbaseFileReference $imageReference
     */
    public function setImageReference($imageReference)
    {
        $fileReference = $imageReference;

        // Normalize to extbase file reference
        if ($imageReference instanceof CoreFileReference) {
            $fileReference = new ExtbaseFileReference();
            $fileReference->setOriginalResource($imageReference);
        }

        $this->imageReference = $fileReference;
    }

    /**
     * Gets the imageReference.
     *
     * @return ExtbaseFileReference
     */
    public function getImageReference()
    {
        return $this->imageReference;
    }

    /**
     * Sets the textItems.
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $textItems
     *
     * @api
     */
    public function setTextItems(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $textItems)
    {
        $this->textItems = $textItems;
    }

    /**
     * Adds a textItem.
     *
     * @param \FelixNagel\GenericGallery\Domain\Model\TextItem $textItems
     *
     * @api
     */
    public function addTextItem(\FelixNagel\GenericGallery\Domain\Model\TextItem $textItems)
    {
        $this->textItems->attach($textItems);
    }

    /**
     * Removes a textItem.
     *
     * @param \FelixNagel\GenericGallery\Domain\Model\TextItem $textItems
     *
     * @api
     */
    public function removeTextItem(\FelixNagel\GenericGallery\Domain\Model\TextItem $textItems)
    {
        $this->textItems->detach($textItems);
    }

    /**
     * Returns the textItems.
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage An object storage containing the textItems
     *
     * @api
     */
    public function getTextItems()
    {
        return $this->textItems;
    }
}
