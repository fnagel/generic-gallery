<?php
defined('TYPO3_MODE') || die();

// Add new palette type
$GLOBALS['TCA']['sys_file_reference']['palettes']['genericGalleryImagePalette'] = array(
    'showitem' => 'title, alternative, --linebreak--, description',
    'canNotCollapse' => TRUE
);
// @todo Move this to default some time
if (version_compare(TYPO3_branch, '7.2', '>=')) {
    // Add image cropping functionality
    $GLOBALS['TCA']['sys_file_reference']['palettes']['genericGalleryImagePalette']['showitem'] .= ',--linebreak--,crop';
}
