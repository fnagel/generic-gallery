<?php
namespace TYPO3\GgExtbase\Domain\Model;


/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014 Felix Nagel <info@felixnagel.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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

use \TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * TextItem
 */
class TextItem extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * bodytext
	 *
	 * @var string
	 */
	protected $bodytext = '';

	/**
	 * position
	 *
	 * @var string
	 */
	protected $position = '';

	/**
	 * width
	 *
	 * @var string
	 */
	protected $width = '';

	/**
	 * Returns the bodytext
	 *
	 * @return string $bodytext
	 */
	public function getBodytext() {
		return $this->bodytext;
	}

	/**
	 * Returns the bodytext
	 *
	 * @return string $bodytext
	 */
	public function getText() {
		return $this->getBodytext();
	}

	/**
	 * Sets the bodytext
	 *
	 * @param string $bodytext
	 * @return void
	 */
	public function setBodytext($bodytext) {
		$this->bodytext = $bodytext;
	}

	/**
	 * Returns the position
	 *
	 * @return string $position
	 */
	public function getPosition() {
		$coordsArray = GeneralUtility::intExplode(',', $this->position, TRUE);

		return array(
			'x' => $coordsArray[0],
			'y' => $coordsArray[1]
		);
	}

	/**
	 * Sets the position
	 *
	 * @param string $position
	 * @return void
	 */
	public function setPosition($position) {
		$this->position = $position;
	}

	/**
	 * Returns the width
	 *
	 * @return string $width
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * Sets the width
	 *
	 * @param string $width
	 * @return void
	 */
	public function setWidth($width) {
		$this->width = $width;
	}

	/**
	 * Returns the position
	 *
	 * @return string $position
	 */
	public function getCssStyles() {
		$string = '';
		$position = $this->getPosition();
		$classes = array(
			'position' => 'absolute',
			'left' => $position['x'] . 'px',
			'top' => $position['y'] . 'px',
		);

		if ($this->getWidth() !== '') {
			$classes['width'] = $this->getWidth() . 'px';
		}

		foreach($classes as $class => $value) {
			$string .= $class . ': ' . $value . '; ';
		}

		return $string;
	}
}