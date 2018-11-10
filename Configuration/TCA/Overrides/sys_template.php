<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'generic_gallery',
    'Configuration/TypoScript',
    'Generic Gallery: default'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'generic_gallery',
    'Configuration/TypoScript/Examples/Bootstrap',
    'Generic Gallery: Example - Bootstrap'
);
