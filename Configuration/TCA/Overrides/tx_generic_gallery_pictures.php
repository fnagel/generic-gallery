<?php
defined('TYPO3_MODE') || die();

// @todo Remove this when 6.2 is no longer relevant
if (version_compare(TYPO3_branch, '7.0', '<')) {
    unset($GLOBALS['TCA']['tx_generic_gallery_pictures']['columns']['link']['config']['wizards']['link']['module']);

    $GLOBALS['TCA']['tx_generic_gallery_pictures']['columns']['link']['config']['wizards']['link']['script'] = 'browse_links.php?mode=wizard';
    $GLOBALS['TCA']['tx_generic_gallery_pictures']['columns']['link']['config']['wizards']['link']['icon'] = 'link_popup.gif';

    $GLOBALS['TCA']['tx_generic_gallery_pictures']['types']['0']['showitem'] =
        str_replace(
            'frontend/Resources/Private/Language/locallang_ttc.xlf',
            'cms/locallang_ttc.xlf',
            $GLOBALS['TCA']['tx_generic_gallery_pictures']['types']['0']['showitem']
        );
}
