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
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Configuration\Exception;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Frontend\Page\PageRepository;

/**
 * Class SettingsService.
 */
class SettingsService
{
    /**
     * Extension name.
     *
     * Needed as parameter for configurationManager->getConfiguration when used in BE context
     * Otherwise generated TS will be incorrect or missing
     *
     * @var string
     */
    protected $extensionName = 'GenericGallery';

    /**
     * Extension key.
     *
     * @var string
     */
    protected $extensionKey = 'tx_genericgallery';

    /**
     * Plugin name.
     *
     * @var string
     */
    protected $pluginName = '';

    /**
     * @var mixed
     */
    protected $typoScriptSettings = null;

    /**
     * @var mixed
     */
    protected $frameworkSettings = null;

    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     * @inject
     */
    protected $configurationManager;

    /**
     * @var \TYPO3\CMS\Core\TypoScript\TypoScriptService
     * @inject
     */
    protected $typoScriptService;

    /**
     * Returns all framework settings.
     *
     * @return array
     *
     * @throws Exception
     */
    public function getFrameworkSettings()
    {
        if ($this->frameworkSettings === null) {
            $this->frameworkSettings = $this->configurationManager->getConfiguration(
                ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
                $this->extensionName,
                $this->pluginName
            );
        }

        if ($this->frameworkSettings === null) {
            throw new Exception('No framework typoscript settings available.');
        }

        return $this->frameworkSettings;
    }

    /**
     * Returns all TS settings.
     *
     * @return array
     *
     * @throws Exception
     */
    public function getTypoScriptSettings()
    {
        if ($this->typoScriptSettings === null) {
            $this->typoScriptSettings = $this->configurationManager->getConfiguration(
                ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
                $this->extensionName,
                $this->pluginName
            );
        }

        if ($this->typoScriptSettings === null) {
            throw new Exception('No typoscript settings available.');
        }

        return $this->typoScriptSettings;
    }

    /**
     * Returns all TS settings.
     *
     * @param int $pid
     * @return array
     *
     * @throws Exception
     */
    public function getTypoScriptSettingsFromBackend($pid)
    {
        if ($this->typoScriptSettings === null) {
            /* @var $pageRepository PageRepository */
            $pageRepository = GeneralUtility::makeInstance(PageRepository::class);
            $rootLine = $pageRepository->getRootLine($pid);

            /* @var $templateService TemplateService */
            $templateService = GeneralUtility::makeInstance(TemplateService::class);
            $templateService->tt_track = 0;
            $templateService->init();
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
     *
     * @param string $path
     *
     * @return mixed
     */
    public function getTypoScriptByPath($path)
    {
        return ObjectAccess::getPropertyPath($this->getTypoScriptSettings(), $path);
    }
}
