<?php

namespace TYPO3\GenericGallery\Backend\Hooks;

/***************************************************************
 * Copyright notice
 *
 * (c) 2014-2015 Felix Nagel (info@felixnagel.com)
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook class for PageLayoutView hook `list_type_Info`
 *
 * @author Felix Nagel <info@felixnagel.com>
 */
class TcaHook {

	/**
	 * @var \TYPO3\CMS\Extbase\Object\Container\Container
	 */
	protected $objectContainer = NULL;

	/**
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManager
	 */
	protected $objectManager = NULL;

	/**
	 * @var \TYPO3\GenericGallery\Service\SettingsService
	 */
	protected $settingsService = NULL;

	/**
	 * Sets the items for the "Predefined" dropdown.
	 *
	 * @param array $config
	 * @return array The config including the items for the dropdown
	 */
	public function addPredefinedFields($config) {
		if (is_array($config['items'])) {
			$pid = $config['row']['pid'];
			if ($pid < 0) {
				$contentUid = str_replace('-', '', $pid);
				$row = $this->getDatabase()->exec_SELECTgetSingleRow('pid', 'tt_content', 'uid=' . $contentUid);
			}

			$settings = $this->getTypoScriptService()->setPageUid($row['pid'])->getTypoScriptSettings();

			// no config available
			if (!is_array($settings['gallery']) || count($settings['gallery']) < 1) {
				$optionList[] = array(
					0 => $this->translate('cms_layout.missing_config'), 1 => ''
				);

				return $config['items'] = array_merge($config['items'], $optionList);
			}

			// for each view
			$optionList = array();
			$optionList[] = array(0 => $this->translate('cms_layout.please_select'), 1 => '');
			foreach ($settings['gallery'] as $key => $view) {

				if (is_array($view)) {
					$optionList[] = array(
						0 => ($view['name']) ? $view['name'] : $key,
						1 => $key . '.',
					);
				}
			}

			$config['items'] = array_merge($config['items'], $optionList);
		}

		return $config;
	}

	/**
	 * @return \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected function getDatabase() {
		return $GLOBALS['TYPO3_DB'];
	}

	/**
	 * @param string $key
	 * @param string $keyPrefix
	 *
	 * @return string
	 */
	protected function translate(
		$key,
		$keyPrefix = 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf'
	) {
		return $GLOBALS['LANG']->sL($keyPrefix . ':' . $key);
	}

	/**
	 * @return \TYPO3\GenericGallery\Service\SettingsService
	 */
	protected function getTypoScriptService() {
		if ($this->settingsService === NULL) {
			$this->settingsService = $this->getObjectContainer()->getInstance(
				'TYPO3\\GenericGallery\\Service\\SettingsService'
			);
		}

		return $this->settingsService;
	}

	/**
	 * Get object container
	 *
	 * @return \TYPO3\CMS\Extbase\Object\Container\Container
	 */
	protected function getObjectContainer() {
		if ($this->objectContainer == NULL) {
			$this->objectContainer = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\Container\\Container');
		}

		return $this->objectContainer;
	}
}