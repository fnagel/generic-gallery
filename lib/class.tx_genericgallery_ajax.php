<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010-2012 Felix Nagel <f.nagel@paints.de>
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

require_once(PATH_tslib . 'class.tslib_eidtools.php');

/**
 * eID for the 'generic_gallery' extension.
 *
 * @author	Felix Nagel <info@felixnagel.com>
 * @package	TYPO3
 * @subpackage	tx_generic_gallery
 */
class tx_genericgallery_ajax {

	protected	$elementUid;

	/**
	 * Initializes the instance of this class.
	 *
	 * @return	void
	 */
	public function __construct() {
		if (t3lib_div::compat_version('4.6')) {
			$this->elementUid = t3lib_utility_Math::convertToPositiveInteger( t3lib_div::_GP('uid') );
		} else {
			// TODO remove this in later versions
			$this->elementUid = t3lib_div::intval_positive( t3lib_div::_GP('uid') );
		}
	}

	/**
	 * Handles incoming trackback requests
	 *
	 * @return	void
	 */
	public function main() {
		if ($this->elementUid) {
			$cacheContent = array();
			$content = "";
			$output = "";
			$type = "text/html";

			tslib_eidtools::connectDB();

			$cacheContent = $this->getCache($this->elementUid, 'ajax-content');
			$content = unserialize($cacheContent);

			switch (t3lib_div::_GP('type')) {
				case "xml":
					$type = "text/xml";
					$output = t3lib_div::array2xml($content, $NSprefix='', $level=0, $docTag='imagesMarker');
					break;
				case "json":
				default:
					$type = "application/json";
					$output = json_encode($content);
					break;
			}

			header('Content-type: ' . $type);
			echo $output;
		}
		exit;
	}

	/**
	* Gets the cache entry
	*
	* @param string $key cache key
	* @param string $identifier unique identifier
	* @return string serialized cache entry
	*/
	function getCache($key, $identifier) {
		$cacheIdentifier = 'generic_gallery-' . $identifier;
		$cacheHash = md5($cacheIdentifier . $key);
		return t3lib_pageSelect::getHash($cacheHash);
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/generic_gallery/lib/class.tx_genericgallery_ajax.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/generic_gallery/lib/class.tx_genericgallery_ajax.php']);
}


if (!(TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_FE)) {
	die();
} else {
	$ajax = t3lib_div::makeInstance('tx_genericgallery_ajax');
	$ajax->main();
}

?>