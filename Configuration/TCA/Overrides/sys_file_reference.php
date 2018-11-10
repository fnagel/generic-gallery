<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// Add new palette type
$GLOBALS['TCA']['sys_file_reference']['palettes']['genericGalleryImagePalette'] = [
    'showitem' => 'title, alternative, --linebreak--, description,--linebreak--,crop',
    'canNotCollapse' => true,
];
