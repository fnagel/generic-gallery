<?php

namespace FelixNagel\GenericGallery\Tests\Unit\Controller;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Tests\UnitTestCase;
use FelixNagel\GenericGallery\Controller\GalleryCollectionController;
use FelixNagel\GenericGallery\Domain\Model\GalleryCollection;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

/**
 * Test case for class FelixNagel\GenericGallery\Controller\GalleryCollectionController.
 */
class GalleryCollectionControllerTest extends UnitTestCase
{
    /**
     * @var \FelixNagel\GenericGallery\Controller\GalleryCollectionController
     */
    protected $subject = null;

    /**
     */
    protected function setUp()
    {
        $this->subject = $this->getMock(GalleryCollectionController::class, ['redirect', 'forward', 'addFlashMessage'], [], '', false);
    }

    /**
     */
    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenGalleryCollectionToView()
    {
        $this->markTestSkipped('to be rewritten');

        $galleryCollection = new GalleryCollection();

        $view = $this->getMock(ViewInterface::class);
        $this->inject($this->subject, 'view', $view);
        $view->expects($this->once())->method('assign')->with('collection', $galleryCollection);

        $this->subject->showAction();
    }
}
