<?php

namespace FelixNagel\GenericGallery\Backend\Hooks;

/***************************************************************
 * Copyright notice
 *
 * (c) 2014-2018 Felix Nagel (info@felixnagel.com)
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use FelixNagel\GenericGallery\Service\SettingsService;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\Container\Container;

/**
 * Hook class for TCA hook.
 *
 * @author Felix Nagel <info@felixnagel.com>
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
                $optionList[] = array(
                    0 => $this->translate('cms_layout.missing_config'), 1 => '',
                );

                return $config['items'] = array_merge($config['items'], $optionList);
            }

            // For each view
            $optionList = array();
            $optionList[] = array(0 => $this->translate('cms_layout.please_select'), 1 => '');
            foreach ($settings['gallery'] as $key => $view) {
                if (is_array($view)) {
                    $optionList[] = array(
                        0 => ($view['name']) ? $view['name'] : $key,
                        1 => $key.'.',
                    );
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
