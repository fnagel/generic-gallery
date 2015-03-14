<?php

namespace TYPO3\GenericGallery\Domain\Model\Dto;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) Georg Ringer <typo3@ringerge.org>
 *  (c) 2015 Felix Nagel <info@felixnagel.com>
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
 * Utility class to get the settings from Extension Manager
 */
class EmConfiguration {

	/**
	 * Fill the properties properly
	 *
	 * @param array $configuration em configuration
	 */
	public function __construct(array $configuration) {
		foreach ($configuration as $key => $value) {
			$key = GeneralUtility::underscoredToLowerCamelCase($key);

			if (property_exists(__CLASS__, $key)) {
				$this->$key = $value;
			}
		}
	}

	/**
	 * @var boolean
	 */
	protected $enableCmsLayout = TRUE;

	/**
	 * @var integer
	 */
	protected $useInlineCollection = 1;

	/**
	 * @return int
	 */
	public function getUseInlineCollection() {
		return $this->useInlineCollection;
	}

	/**
	 * @param int $useInlineCollection
	 */
	public function setUseInlineCollection($useInlineCollection) {
		$this->useInlineCollection = $useInlineCollection;
	}

	/**
	 * @return boolean
	 */
	public function isEnableCmsLayout() {
		return $this->enableCmsLayout;
	}

	/**
	 * @param boolean $enableCmsLayout
	 */
	public function setEnableCmsLayout($enableCmsLayout) {
		$this->enableCmsLayout = $enableCmsLayout;
	}
}
