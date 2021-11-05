<?php

defined('TYPO3') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'generic_gallery',
    'Configuration/TypoScript',
    'Generic Gallery: default'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'generic_gallery',
    'Configuration/TypoScript/Examples/Bootstrap3',
    'Generic Gallery: Example - Bootstrap 3'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'generic_gallery',
    'Configuration/TypoScript/Examples/Bootstrap4',
    'Generic Gallery: Example - Bootstrap 4'
);
