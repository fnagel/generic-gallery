<?php
namespace TYPO3\GenericGallery\Tests\Unit\Controller;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Felix Nagel <info@felixnagel.com>
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
 * Test case for class TYPO3\GenericGallery\Controller\GalleryCollectionController.
 *
 * @author Felix Nagel <info@felixnagel.com>
 */
class GalleryCollectionControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \TYPO3\GenericGallery\Controller\GalleryCollectionController
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = $this->getMock('TYPO3\\GenericGallery\\Controller\\GalleryCollectionController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllGalleryCollectionsFromRepositoryAndAssignsThemToView() {

		$allGalleryCollections = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$galleryCollectionRepository = $this->getMock('TYPO3\\GenericGallery\\Domain\\Repository\\GalleryCollectionRepository', array('findAll'), array(), '', FALSE);
		$galleryCollectionRepository->expects($this->once())->method('findAll')->will($this->returnValue($allGalleryCollections));
		$this->inject($this->subject, 'galleryCollectionRepository', $galleryCollectionRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('galleryCollections', $allGalleryCollections);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenGalleryCollectionToView() {
		$galleryCollection = new \TYPO3\GenericGallery\Domain\Model\GalleryCollection();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('galleryCollection', $galleryCollection);

		$this->subject->showAction($galleryCollection);
	}
}
