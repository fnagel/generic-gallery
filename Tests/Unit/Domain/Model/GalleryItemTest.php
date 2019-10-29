<?php

namespace FelixNagel\GenericGallery\Tests\Unit\Domain\Model;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Test case for class \FelixNagel\GenericGallery\Domain\Model\GalleryItem.
 */
class GalleryItemTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
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
        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->fixture = $this->objectManager->get('FelixNagel\\GenericGallery\\Domain\\Model\\GalleryItem');
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
     */
    public function getTextItemsReturnsInitialValueForTextItem()
    {
        $this->assertEquals(
            new \TYPO3\CMS\Extbase\Persistence\ObjectStorage(),
            $this->fixture->getTextItems()
        );
    }

    /**
     * @test
     */
    public function setTextItemsForTextItemSetsTextItems()
    {
        $textItemsFixture = $this->objectManager->get('FelixNagel\\GenericGallery\\Domain\\Model\\TextItem');
        $this->fixture->addTextItem($textItemsFixture);

        $this->assertAttributeEquals(
            $this->fixture->getTextItems(),
            'textItems',
            $this->fixture
        );
    }
}
