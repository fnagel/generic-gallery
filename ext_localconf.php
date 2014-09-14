<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'TYPO3.' . $_EXTKEY,
	'Gallery',
	array(
		'GalleryCollection' => 'list, show',
		
	),
	// non-cacheable actions
	array(
		'GalleryCollection' => '',
		
	)
);
