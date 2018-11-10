<?php

namespace FelixNagel\GenericGallery\Tests\Unit\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014-2018 Felix Nagel <info@felixnagel.com>
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
 * Test case for class FelixNagel\GenericGallery\Controller\GalleryCollectionController.
 *
 * @author Felix Nagel <info@felixnagel.com>
 */
class GalleryCollectionControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \FelixNagel\GenericGallery\Controller\GalleryCollectionController
     */
    protected $subject = null;

    /**
     */
    protected function setUp()
    {
        $this->subject = $this->getMock('FelixNagel\\GenericGallery\\Controller\\GalleryCollectionController', array('redirect', 'forward', 'addFlashMessage'), array(), '', false);
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

        $galleryCollection = new \FelixNagel\GenericGallery\Domain\Model\GalleryCollection();

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $this->inject($this->subject, 'view', $view);
        $view->expects($this->once())->method('assign')->with('collection', $galleryCollection);

        $this->subject->showAction();
    }
}
