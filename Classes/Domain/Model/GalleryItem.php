<?php

namespace TYPO3\GenericGallery\Domain\Model;

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

use TYPO3\CMS\Core\Resource\FileReference as CoreFileReference;
use TYPO3\CMS\Extbase\Domain\Model\FileReference as ExtbaseFileReference;

/**
 * Class GalleryItem
 */
class GalleryItem extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * tt_content UID
	 *
	 * @var int
	 */
	protected $ttContentUid;

	/**
	 * title
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * link
	 *
	 * @var string
	 */
	protected $link;

	/**
	 * image
	 *
	 * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
	 */
	protected $imageReference = NULL;

	/**
	 * image
	 *
	 * @var \TYPO3\CMS\Core\Resource\File
	 */
	protected $image = NULL;

	/**
	 * textItems
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\GenericGallery\Domain\Model\TextItem>
	 */
	protected $textItems;


	/**
	 * Construct class
	 */
	public function __construct() {
		$this->textItems = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}


	/**
	 * @return string
	 */
	public function getUid() {
		$uid = parent::getUid();

		if ($uid === NULL) {
			$uid = 'file_reference_' . $this->getImage()->getUid();
		}

		return $uid;
	}

	/**
	 * If object is virtual
	 *
	 * Virtual means its generated and not a DB relation
	 * So, if the object is virtual the plugin is of type
	 * 'imgaes' or 'collection'
	 *
	 * @return boolean
	 */
	public function isVirtual() {
		return !((bool)parent::getUid());
	}

	/**
	 * @param int $ttContentUid
	 * @return void
	 */
	public function setTtContentUid($ttContentUid) {
		$this->ttContentUid = $ttContentUid;
	}

	/**
	 * @return int
	 */
	public function getTtContentUid() {
		return $this->ttContentUid;
	}

	/**
	 * Returns the title
	 *
	 * @return string $title
	 */
	public function getTitle() {
		if ($this->isVirtual()) {
			return $this->getImage()->getProperties()['title'];
		}

		return $this->title;
	}

	/**
	 * Sets the title
	 *
	 * @param string $title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Returns the link
	 *
	 * @return string $link
	 */
	public function getLink() {
		if ($this->isVirtual() || $this->link === '') {
			return $this->getImage()->getPublicUrl();
		}

		return $this->link;
	}

	/**
	 * Sets the link
	 *
	 * @param string $link
	 * @return void
	 */
	public function setLink($link) {
		$this->link = $link;
	}

	/**
	 * Returns the image
	 *
	 * @return \TYPO3\CMS\Core\Resource\File $image
	 */
	public function getImage() {
		if ($this->image === NULL) {
			return $this->imageReference->getOriginalResource()->getOriginalFile();
		}

		return $this->image;
	}

	/**
	 * Shortcut for image properties
	 *
	 * @return array
	 */
	public function getImageData() {
		$imageData = $this->getImage()->getProperties();

		if ($this->imageReference !== NULL) {
			// Overwrite with merged reference inline data
			$imageData = $this->imageReference->getOriginalResource()->getProperties();
		}

		// Merge with modified meta data
		$imageData = array_merge($imageData, $this->getAdditionalImageProperties());

		return $imageData;
	}

	/**
	 * Return formatted image properties
	 *
	 * @return array
	 */
	private function getAdditionalImageProperties() {
		$properties = $this->getImage()->getProperties();
		$data = array();

		// process exif data
		$data['shutter_speed_value'] = $properties['shutter_speed_value'] . 's';
		$data['aperture_value'] = 'f/' . $properties['aperture_value'];
		$data['focal_length'] = $properties['focal_length'] . 'mm';
		$data['iso_speed_ratings'] = 'ISO' . $properties['iso_speed_ratings'];

		// process flash data
		if (isset($GLOBALS['TCA']['sys_file_metadata']['columns']['flash']['config']['items'])) {
			$items = (array)$GLOBALS['TCA']['sys_file_metadata']['columns']['flash']['config']['items'];
			foreach ($items as $item) {
				if ($item[1] === $properties['flash']) {
					$data['flash'] = $item[0];
				}
			}
		}

		return $data;
	}

	/**
	 * Sets the image
	 *
	 * @param \TYPO3\CMS\Core\Resource\File $image
	 * @return void
	 */
	public function setImage($image) {
		$this->image = $image;
	}

	/**
	 * Sets the imageReference
	 *
	 * @param CoreFileReference|ExtbaseFileReference $imageReference
	 * @return void
	 */
	public function setImageReference($imageReference) {
		$fileReference = $imageReference;

		// Normalize to extbase file reference
		if ($imageReference instanceof CoreFileReference) {
			$fileReference = new ExtbaseFileReference();
			$fileReference->setOriginalResource($imageReference);
		}

		$this->imageReference = $fileReference;
	}

	/**
	 * Sets the textItems
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $textItems
	 * @return void
	 * @api
	 */
	public function setTextItems(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $textItems) {
		$this->textItems = $textItems;
	}

	/**
	 * Adds a textItem
	 *
	 * @param \TYPO3\GenericGallery\Domain\Model\TextItem $textItems
	 * @return void
	 * @api
	 */
	public function addTextItem(\TYPO3\GenericGallery\Domain\Model\TextItem $textItems) {
		$this->textItems->attach($textItems);
	}

	/**
	 * Removes a textItem
	 *
	 * @param \TYPO3\GenericGallery\Domain\Model\TextItem $textItems
	 * @return void
	 * @api
	 */
	public function removeTextItem(\TYPO3\GenericGallery\Domain\Model\TextItem $textItems) {
		$this->textItems->detach($textItems);
	}

	/**
	 * Returns the textItems
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage An object storage containing the textItems
	 * @api
	 */
	public function getTextItems() {
		return $this->textItems;
	}


}