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
use FelixNagel\GenericGallery\Domain\Model\GalleryCollection;

/**
 * Test case for class \FelixNagel\GenericGallery\Domain\Model\GalleryCollection.
 */
class GalleryCollectionTest extends UnitTestCase
{
    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager = null;

    /**
     * @var \FelixNagel\GenericGallery\Domain\Model\GalleryCollection
     */
    protected $fixture = null;

    /**
     */
    protected function setUp()
    {
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->fixture = $this->objectManager->get(GalleryCollection::class);
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
    public function dummyTestToNotLeaveThisFileEmpty()
    {
        $this->markTestIncomplete();
    }
}
