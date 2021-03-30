<?php

namespace FelixNagel\GenericGallery\Tests\Unit\Domain\Model;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use FelixNagel\GenericGallery\Domain\Model\GalleryItem;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use FelixNagel\GenericGallery\Domain\Model\TextItem;

/**
 * Test case for class \FelixNagel\GenericGallery\Domain\Model\GalleryItem.
 */
class GalleryItemTest extends UnitTestCase
{
    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager = null;

    /**
     * @var \FelixNagel\GenericGallery\Domain\Model\GalleryItem
     */
    protected $fixture = null;

    /**
     */
    protected function setUp()
    {
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->fixture = $this->objectManager->get(GalleryItem::class);
    }

    /**
     */
    protected function tearDown()
    {
        unset($this->fixture);
    }

    /**
     * @test
     */
    public function getTitleReturnsInitialValueForString()
    {
        $this->markTestSkipped('to be written (FAL related)');

        $this->assertSame(
            '',
            $this->fixture->getTitle()
        );
    }

    /**
     * @test
     */
    public function setTitleForStringSetsTitle()
    {
        $this->fixture->setTitle('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'title',
            $this->fixture
        );
    }

    /**
     * @test
     */
    public function getLinkReturnsInitialValueForString()
    {
        $this->markTestSkipped('to be written (FAL related)');

        $this->assertSame(
            '',
            $this->fixture->getLink()
        );
    }

    /**
     * @test
     */
    public function setLinkForStringSetsLink()
    {
        $this->fixture->setLink('http://www.domain.com');

        $this->assertAttributeEquals(
            'http://www.domain.com',
            'link',
            $this->fixture
        );
    }

    /**
     * @test
     */
    public function getImageReturnsInitialValueForFileReference()
    {
        $this->assertEquals(
            null,
            $this->fixture->getImage()
        );
    }

    /**
     * @test
     */
    public function setImageForFileReferenceSetsImage()
    {
        // @todo FAL relations needs fixing
        $fileReferenceFixture = $this->objectManager->get(FileReference::class);
        $this->fixture->setImage($fileReferenceFixture);

        $this->assertAttributeEquals(
            $fileReferenceFixture,
            'image',
            $this->fixture
        );
    }

    /**
     * @test
     */
    public function getTextItemsReturnsInitialValueForTextItem()
    {
        $this->assertEquals(
            new ObjectStorage(),
            $this->fixture->getTextItems()
        );
    }

    /**
     * @test
     */
    public function setTextItemsForTextItemSetsTextItems()
    {
        $textItemsFixture = $this->objectManager->get(TextItem::class);
        $this->fixture->addTextItem($textItemsFixture);

        $this->assertAttributeEquals(
            $this->fixture->getTextItems(),
            'textItems',
            $this->fixture
        );
    }
}
