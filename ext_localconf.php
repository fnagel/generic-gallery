<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

// add plugin
t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_genericgallery_pi1.php', '_pi1', 'list_type', 1);

// add dynamic fields
//include_once(t3lib_extMgm::extPath($_EXTKEY).'pi1/class.tx_genericgallery_pi1_addFields.php');

// add eID for AJX requests
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include'][$_EXTKEY] = 'EXT:' . $_EXTKEY . '/lib/class.tx_genericgallery_ajax.php';

// add BE layout
//$TYPO3_CONF_VARS['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['generic_gallery_pi1'][] = 'EXT:generic_gallery/pi1/class.tx_genericgallery_pi1_cms_layout.php:tx_genericgallery_cms_layout->getExtensionSummary';

// add records to the search
$GLOBALS['TYPO3_CONF_VARS']['SYS']['livesearch']['gg_pictures'] = 'tx_generic_gallery_pictures';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['livesearch']['gg_content'] = 'tx_generic_gallery_content';
?>