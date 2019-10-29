<?php

namespace FelixNagel\GenericGallery\Backend\Hooks;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use FelixNagel\GenericGallery\Service\SettingsService;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\Container\Container;

/**
 * Hook class for TCA hook.
 */
class TcaHook
{
    /**
     * @var \TYPO3\CMS\Extbase\Object\Container\Container
     */
    protected $objectContainer = null;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager = null;

    /**
     * @var \FelixNagel\GenericGallery\Service\SettingsService
     */
    protected $settingsService = null;

    /**
     * Sets the items for the "Predefined" dropdown.
     *
     * @param array $config
     *
     * @return array The config including the items for the dropdown
     */
    public function addPredefinedFields($config)
    {
        if (is_array($config['items'])) {
            $pid = $config['row']['pid'];
            if ($pid < 0) {
                $pid = $this->getRecordPid((int) str_replace('-', '', $pid));
            }

            $settings = $this->getTypoScriptService()->getTypoScriptSettingsFromBackend($pid);

            // No config available
            if (!is_array($settings['gallery']) || count($settings['gallery']) < 1) {
                $optionList[] = [
                    0 => $this->translate('cms_layout.missing_config'), 1 => '',
                ];

                return $config['items'] = array_merge($config['items'], $optionList);
            }

            // For each view
            $optionList = [];
            $optionList[] = [0 => $this->translate('cms_layout.please_select'), 1 => ''];
            foreach ($settings['gallery'] as $key => $view) {
                if (is_array($view)) {
                    $optionList[] = [
                        0 => ($view['name']) ? $view['name'] : $key,
                        1 => $key.'.',
                    ];
                }
            }

            $config['items'] = array_merge($config['items'], $optionList);
        }

        return $config;
    }

    /**
     * @param int $uid
     * @return int
     */
    protected function getRecordPid($uid)
    {
        $table = 'tt_content';
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        $queryBuilder
            ->from($table)
            ->select('pid')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid))
            )
            ->setMaxResults(1);

        $statement = $queryBuilder->execute();
        $rows = $statement->fetchAll();

        return (int) $rows['pid'];
    }

    /**
     * @param string $key
     * @param string $keyPrefix
     *
     * @return string
     */
    protected function translate(
        $key,
        $keyPrefix = 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf'
    ) {
        return $GLOBALS['LANG']->sL($keyPrefix.':'.$key);
    }

    /**
     * @return \FelixNagel\GenericGallery\Service\SettingsService
     */
    protected function getTypoScriptService()
    {
        if ($this->settingsService === null) {
            $this->settingsService = $this->getObjectContainer()->getInstance(SettingsService::class);
        }

        return $this->settingsService;
    }

    /**
     * Get object container.
     *
     * @return \TYPO3\CMS\Extbase\Object\Container\Container
     */
    protected function getObjectContainer()
    {
        if ($this->objectContainer == null) {
            $this->objectContainer = GeneralUtility::makeInstance(Container::class);
        }

        return $this->objectContainer;
    }
}
