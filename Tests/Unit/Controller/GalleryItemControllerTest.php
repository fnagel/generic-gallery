<?php

namespace FelixNagel\GenericGallery\Tests\Unit\Controller;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Tests\UnitTestCase;
use FelixNagel\GenericGallery\Controller\GalleryItemController;
use FelixNagel\GenericGallery\Domain\Model\GalleryCollection;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

/**
 * Test case for class FelixNagel\GenericGallery\Controller\GalleryItemController.
 */
class GalleryItemControllerTest extends UnitTestCase
{
    /**
     * @var \FelixNagel\GenericGallery\Controller\GalleryItemController
     */
    protected $fixture = null;

    /**
     */
    protected function setUp()
    {
        $this->fixture = $this->getMock(
            GalleryItemController::class,
            ['redirect', 'forward', 'addFlashMessage'],
            [],
            '',
            false
        );
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
    public function showActionAssignsTheGivenGalleryItemToView()
    {
        $this->markTestSkipped('to be rewritten');

        $item = new GalleryCollection();
        $view = $this->getMock(ViewInterface::class);

        $this->inject($this->fixture, 'view', $view);
        $view->expects($this->once())->method('assign')->with('item', $item);

        $this->fixture->showAction($item);
    }
}
