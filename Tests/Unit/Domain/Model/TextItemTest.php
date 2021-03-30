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
use FelixNagel\GenericGallery\Domain\Model\TextItem;

/**
 * Test case for class \FelixNagel\GenericGallery\Domain\Model\TextItem.
 */
class TextItemTest extends UnitTestCase
{
    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager = null;

    /**
     * @var \FelixNagel\GenericGallery\Domain\Model\TextItem
     */
    protected $fixture = null;

    /**
     */
    protected function setUp()
    {
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->fixture = $this->objectManager->get(TextItem::class);
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
    public function getBodytextReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->fixture->getBodytext()
        );
    }

    /**
     * @test
     */
    public function setBodytextForStringSetsBodytext()
    {
        $this->fixture->setBodytext('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'bodytext',
            $this->fixture
        );
    }

    /**
     * @test
     */
    public function getPositionReturnsInitialValueForString()
    {
        $this->assertSame(
            [
                'x' => null,
                'y' => null,
            ],
            $this->fixture->getPosition()
        );
    }

    /**
     * @test
     */
    public function setPositionForStringSetsPosition()
    {
        $this->fixture->setPosition('100,200');

        $this->assertSame(
            [
                'x' => 100,
                'y' => 200,
            ],
            $this->fixture->getPosition()
        );
    }

    /**
     * @test
     */
    public function getWidthReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->fixture->getWidth()
        );
    }

    /**
     * @test
     */
    public function setWidthForStringSetsWidth()
    {
        $this->fixture->setWidth(100);

        $this->assertAttributeEquals(
            100,
            'width',
            $this->fixture
        );
    }
}
