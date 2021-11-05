<?php

defined('TYPO3') or die();

// Add new palette type
$GLOBALS['TCA']['sys_file_reference']['palettes']['genericGalleryImagePalette'] = [
    'showitem' => 'title, alternative, --linebreak--, description,--linebreak--,crop',
    'canNotCollapse' => true,
];
