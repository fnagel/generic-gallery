<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005 Felix Nagel (info@felixnagel.com)
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
 * Function to add select options dynamically (loaded out of TS)
 * 
 * @author	Felix Nagel <info@felixnagel.com>
 * @package	TYPO3
 * @subpackage	tx_beautyofcode
 */
class tx_genericgallery_addFields {

	/**
	 * Sets the items for the "Predefined" dropdown.
	 *
	 * @param array $config
	 * @return array The config including the items for the dropdown
	 */
	function addFields_predefined ($config) {
		global $LANG;
		
		if (is_array($config['items'])) {
			$pid = $config['row']['pid'];
			if($pid < 0) {
				$contentUid = str_replace('-','',$pid);
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('pid','tt_content','uid='.$contentUid);
				if($res) {
					$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
					$pid = $row['pid'];
					$GLOBALS['TYPO3_DB']->sql_free_result($res);
				}
			}
			$ts = $this->loadTS($pid);
			
			$predef = array();

			# no config available
			if (!is_array($ts['plugin.']['tx_genericgallery_pi1.']['gallery.']) || sizeof($ts['plugin.']['tx_genericgallery_pi1.']['gallery.']) == 0) {
				$optionList[] = array(0 => $LANG->sL('LLL:EXT:generic_gallery/locallang_db.xml:cms_layout.missing_config'), 1 => '');
				return $config['items'] = array_merge($config['items'], $optionList);
			}

			# for each view
			foreach($ts['plugin.']['tx_genericgallery_pi1.']['gallery.'] as $key=>$view) {

				if (is_array($view)) {
					$beName = $view['name'];
					if (!$predef[$key]) $predef[$key] = $beName;
				}
			}
			
			$optionList = array();
			$optionList[] = array(0 => $LANG->sL('LLL:EXT:generic_gallery/locallang_db.xml:cms_layout.please_select'), 1 => '');
			foreach($predef as $k => $v) {
				$optionList[] = array(0 => $v, 1 => $k);
			}
			$config['items'] = array_merge($config['items'], $optionList);
			
			return $config;
		}
	}

	/**
	 * Loads the TypoScript for the current page
	 *
	 * @param int $pageUid
	 * @return array The TypoScript setup
	 */
	function loadTS($pageUid) {
		$sysPageObj = t3lib_div::makeInstance('t3lib_pageSelect');
		$rootLine = $sysPageObj->getRootLine($pageUid);
		$TSObj = t3lib_div::makeInstance('t3lib_tsparser_ext');
		$TSObj->tt_track = 0;
		$TSObj->init();
		$TSObj->runThroughTemplates($rootLine);
		$TSObj->generateConfig();
		return $TSObj->setup;
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/generic_gallery/pi1/class.tx_genericgallery_addFields.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/generic_gallery/pi1/class.tx_genericgallery_addFields.php']);
}
?>