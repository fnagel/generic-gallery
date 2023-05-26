<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

ExtensionManagementUtility::addStaticFile(
    'generic_gallery',
    'Configuration/TypoScript',
    'Generic Gallery: default'
);
ExtensionManagementUtility::addStaticFile(
    'generic_gallery',
    'Configuration/TypoScript/Examples/Bootstrap3',
    'Generic Gallery: Example - Bootstrap 3'
);
ExtensionManagementUtility::addStaticFile(
    'generic_gallery',
    'Configuration/TypoScript/Examples/Bootstrap4',
    'Generic Gallery: Example - Bootstrap 4'
);
