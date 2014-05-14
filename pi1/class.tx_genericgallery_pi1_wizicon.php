<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010 Felix Nagel <f.nagel@paints.de>
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
 * Class that adds the wizard icon.
 *
 * @author    Felix Nagel <f.nagel@paints.de>
 * @package    TYPO3
 * @subpackage    user_headerslider
 */
class tx_genericgallery_pi1_wizicon {

	/**
	 * Processing the wizard items array
	 *
	 * @param    array $wizardItems : The wizard items
	 * @return    Modified array with wizard items
	 */
	function proc($wizardItems) {
		global $LANG;

		$LL = $this->includeLocalLang();

		$wizardItems['plugins_tx_genericgallery_pi1'] = array(
			'icon' => t3lib_extMgm::extRelPath('generic_gallery') . 'pi1/ce_wiz.gif',
			'title' => $LANG->getLLL('pi1_title', $LL),
			'description' => $LANG->getLLL('pi1_plus_wiz_description', $LL),
			'params' => '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=generic_gallery_pi1'
		);

		return $wizardItems;
	}

	/**
	 * Reads the [extDir]/locallang.xml and returns the $LOCAL_LANG array found in that file.
	 *
	 * @return    The array with language labels
	 */
	function includeLocalLang() {
		$llFile = t3lib_extMgm::extPath('generic_gallery') . 'locallang.xml';
		$LOCAL_LANG = t3lib_div::readLLfile($llFile, $GLOBALS['LANG']->lang);

		return $LOCAL_LANG;
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/generic_gallery/pi1/class.tx_genericgallery_pi1_wizicon.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/generic_gallery/pi1/class.tx_genericgallery_pi1_wizicon.php']);
}

?>