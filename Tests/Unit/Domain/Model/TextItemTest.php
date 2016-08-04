<?php

namespace TYPO3\GenericGallery\Tests\Unit\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014-2016 Nagel <info@felixnagel.com>
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
 * Test case for class \TYPO3\GenericGallery\Domain\Model\TextItem.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @author Felix Nagel <info@felixnagel.com>
 */
class TextItemTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager = null;

    /**
     * @var \TYPO3\GenericGallery\Domain\Model\TextItem
     */
    protected $fixture = null;

    /**
     */
    protected function setUp()
    {
        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->fixture = $this->objectManager->get('TYPO3\\GenericGallery\\Domain\\Model\\TextItem');
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
            array(
                'x' => null,
                'y' => null,
            ),
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
            array(
                'x' => 100,
                'y' => 200,
            ),
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
