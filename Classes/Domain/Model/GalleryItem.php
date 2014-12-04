<?php
namespace TYPO3\GgExtbase\Domain\Model;


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

use \TYPO3\CMS\Extbase\Persistence\ObjectStorage;

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
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\GgExtbase\Domain\Model\TextItem>
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
		if ($this->title === NULL) {
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
		if ($this->imageReference !== NULL) {
			return $this->imageReference->getOriginalResource()->getOriginalFile();
		}

		return $this->image;
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
	 * @param \TYPO3\GgExtbase\Domain\Model\TextItem $textItems
	 * @return void
	 * @api
	 */
	public function addTextItem(\TYPO3\GgExtbase\Domain\Model\TextItem $textItems) {
		$this->textItems->attach($textItems);
	}

	/**
	 * Removes a textItem
	 *
	 * @param \TYPO3\GgExtbase\Domain\Model\TextItem $textItems
	 * @return void
	 * @api
	 */
	public function removeTextItem(\TYPO3\GgExtbase\Domain\Model\TextItem $textItems) {
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