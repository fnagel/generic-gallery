<?php

namespace FelixNagel\GenericGallery\Tests\Unit\Controller;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Test case for class FelixNagel\GenericGallery\Controller\GalleryItemController.
 */
class GalleryItemControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
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
            'FelixNagel\\GenericGallery\\Controller\\GalleryItemController',
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

        $item = new \FelixNagel\GenericGallery\Domain\Model\GalleryCollection();
        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');

        $this->inject($this->fixture, 'view', $view);
        $view->expects($this->once())->method('assign')->with('item', $item);

        $this->fixture->showAction($item);
    }
}
