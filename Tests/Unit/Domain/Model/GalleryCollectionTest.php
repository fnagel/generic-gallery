<?php

namespace FelixNagel\GenericGallery\Tests\Unit\Domain\Model;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Test case for class \FelixNagel\GenericGallery\Domain\Model\GalleryCollection.
 */
class GalleryCollectionTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
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
        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->fixture = $this->objectManager->get('FelixNagel\\GenericGallery\\Domain\\Model\\GalleryCollection');
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
