<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

call_user_func(function($packageKey)
	{
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
			'tx_generic_gallery_pictures',
			'EXT:generic_gallery/Resources/Private/Language/locallang_csh_tx_genericgallery_domain_model_galleryitem.xlf'
		);
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_generic_gallery_pictures');

		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
			'tx_generic_gallery_content',
			'EXT:generic_gallery/Resources/Private/Language/locallang_csh_tx_genericgallery_domain_model_textitem.xlf'
		);
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_generic_gallery_content');

		// Use old icon path for TYPO3 6.2
		// @todo Remove this when 6.2 is no longer relevant
		if (version_compare(TYPO3_branch, '7.0', '<')) {
			$iconPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($packageKey) . 'Resources/Public/Icons/';
			\TYPO3\CMS\Backend\Sprite\SpriteManager::addSingleIcons(array(
				'pictures' => $iconPath . 'tx_genericgallery_domain_model_galleryitem.gif',
				'content' => $iconPath . 'tx_genericgallery_domain_model_textitem.gif',
			), 'generic-gallery');
		} else {
			/* @var $iconRegistry \TYPO3\CMS\Core\Imaging\IconRegistry */
			$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
			$iconRegistry->registerIcon(
				'extensions-generic-gallery-pictures',
				\TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
				['source' => 'EXT:generic_gallery/Resources/Public/Icons/tx_genericgallery_domain_model_galleryitem.gif']
			);
			$iconRegistry->registerIcon(
				'extensions-generic-gallery-content',
				\TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
				['source' => 'EXT:generic_gallery/Resources/Public/Icons/tx_genericgallery_domain_model_textitem.gif']
			);
		}

		// Add page TS config
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
			'<INCLUDE_TYPOSCRIPT: source="FILE:EXT:generic_gallery/Configuration/TypoScript/pageTsConfig.ts">'
		);

	},
	$_EXTKEY
);
