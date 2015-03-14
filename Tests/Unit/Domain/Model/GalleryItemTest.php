<?php

namespace TYPO3\GenericGallery\Tests\Unit\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014-2015 Felix Nagel <info@felixnagel.com>
 *
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
 * Test case for class \TYPO3\GenericGallery\Domain\Model\GalleryItem.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Felix Nagel <info@felixnagel.com>
 */
class GalleryItemTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManager
	 */
	protected $objectManager = NULL;

	/**
	 * @var \TYPO3\GenericGallery\Domain\Model\GalleryItem
	 */
	protected $fixture = NULL;

	/**
	 * @return void
	 */
	protected function setUp() {
		$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$this->fixture = $this->objectManager->get('TYPO3\\GenericGallery\\Domain\\Model\\GalleryItem');
	}

	/**
	 * @return void
	 */
	protected function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getTitleReturnsInitialValueForString() {
		$this->markTestSkipped("to be written (FAL related)");

		$this->assertSame(
			'',
			$this->fixture->getTitle()
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function setTitleForStringSetsTitle() {
		$this->fixture->setTitle('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'title',
			$this->fixture
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getLinkReturnsInitialValueForString() {
		$this->markTestSkipped("to be written (FAL related)");

		$this->assertSame(
			'',
			$this->fixture->getLink()
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function setLinkForStringSetsLink() {
		$this->fixture->setLink('http://www.domain.com');

		$this->assertAttributeEquals(
			'http://www.domain.com',
			'link',
			$this->fixture
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getImageReturnsInitialValueForFileReference() {
		$this->assertEquals(
			NULL,
			$this->fixture->getImage()
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function setImageForFileReferenceSetsImage() {
		// @todo FAL relations needs fixing
		$fileReferenceFixture = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Domain\\Model\\FileReference');
		$this->fixture->setImage($fileReferenceFixture);

		$this->assertAttributeEquals(
			$fileReferenceFixture,
			'image',
			$this->fixture
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getTextItemsReturnsInitialValueForTextItem() {
		$this->assertEquals(
			new \TYPO3\CMS\Extbase\Persistence\ObjectStorage(),
			$this->fixture->getTextItems()
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function setTextItemsForTextItemSetsTextItems() {
		$textItemsFixture = $this->objectManager->get('TYPO3\\GenericGallery\\Domain\\Model\\TextItem');
		$this->fixture->addTextItem($textItemsFixture);

		$this->assertAttributeEquals(
			$this->fixture->getTextItems(),
			'textItems',
			$this->fixture
		);
	}
}
