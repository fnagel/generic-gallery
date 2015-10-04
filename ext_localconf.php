<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}
// BE preview
$TYPO3_CONF_VARS['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['genericgallery_pi1'][] =
	'EXT:generic_gallery/Classes/Backend/Hooks/PageLayoutViewHook.php:TYPO3\GenericGallery\Backend\Hooks\PageLayoutViewHook->getExtensionSummary';

$boot = function ($packageKey) {
	// FE plugin
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
		'TYPO3.' . $packageKey,
		'Pi1',
		array(
			'GalleryCollection' => 'show',
			'GalleryItem' => 'show',
		),
		// non-cacheable actions
		array(
			'GalleryCollection' => '',
			'GalleryItem' => '',
		)
	);
};

$boot($_EXTKEY);
unset($boot);