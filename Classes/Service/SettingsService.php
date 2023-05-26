<?php

namespace FelixNagel\GenericGallery\Service;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\RootlineUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Configuration\Exception;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;

/**
 * Provide a way to get the configuration just everywhere.
 */
class SettingsService
{
    /**
     * Extension name.
     *
     * Needed as parameter for configurationManager->getConfiguration when used in BE context
     * Otherwise generated TS will be incorrect or missing
     */
    protected string $extensionName = 'GenericGallery';

    protected string $extensionKey = 'tx_genericgallery';

    protected string $pluginName = '';

    protected ?array $typoScriptSettings = null;

    protected ?array $frameworkSettings = null;

    /**
     * SettingsService constructor.
     */
    public function __construct(
        protected ConfigurationManagerInterface $configurationManager,
        protected TypoScriptService $typoScriptService
    )
    {
    }

    /**
     * Returns all framework settings.
     */
    public function getFrameworkSettings(): array
    {
        if ($this->frameworkSettings === null) {
            $this->frameworkSettings = $this->configurationManager->getConfiguration(
                ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
                $this->extensionName,
                $this->pluginName
            );
        }

        if ($this->frameworkSettings === null) {
            throw new Exception('No framework TypoScript configuration available!', 1592249266);
        }

        return $this->frameworkSettings;
    }

    /**
     * Returns all TS settings.
     */
    public function getTypoScriptSettings(): array
    {
        if ($this->typoScriptSettings === null) {
            $this->typoScriptSettings = $this->configurationManager->getConfiguration(
                ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
                $this->extensionName,
                $this->pluginName
            );
        }

        if ($this->typoScriptSettings === null) {
            throw new Exception('No settings TypoScript configuration available!', 1592249324);
        }

        return $this->typoScriptSettings;
    }

    /**
     * Returns all TS settings.
     */
    public function getTypoScriptSettingsFromBackend(int $pid): array
    {
        if ($this->typoScriptSettings === null) {
            /* @var $rootLineUtility RootlineUtility */
            $rootLineUtility = GeneralUtility::makeInstance(RootlineUtility::class, $pid);
            $rootLine = $rootLineUtility->get();

            /* @var $templateService TemplateService */
            $templateService = GeneralUtility::makeInstance(TemplateService::class);
            $templateService->tt_track = 0;
            $templateService->runThroughTemplates($rootLine);
            $templateService->generateConfig();

            if (!empty($templateService->setup['plugin.'][$this->extensionKey.'.']['settings.'])) {
                $this->typoScriptSettings = $this->typoScriptService->convertTypoScriptArrayToPlainArray(
                    $templateService->setup['plugin.'][$this->extensionKey.'.']['settings.']
                );
            }
        }

        if ($this->typoScriptSettings === null) {
            throw new Exception('No typoscript settings available.');
        }

        return $this->typoScriptSettings;
    }

    /**
     * Returns the settings at path $path, which is separated by ".",
     * e.g. "pages.uid".
     * "pages.uid" would return $this->settings['pages']['uid'].
     *
     * If the path is invalid or no entry is found, false is returned.
     */
    public function getTypoScriptByPath(string $path)
    {
        return ObjectAccess::getPropertyPath($this->getTypoScriptSettings(), $path);
    }
}
