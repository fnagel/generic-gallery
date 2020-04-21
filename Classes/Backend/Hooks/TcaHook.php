<?php

namespace FelixNagel\GenericGallery\Backend\Hooks;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use FelixNagel\GenericGallery\Service\SettingsService;
use TYPO3\CMS\Backend\Form\FormDataProvider\TcaSelectItems;
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
    public function addPredefinedFields($config, TcaSelectItems $tcaSelectItems)
    {
        if (is_array($config['items'])) {
            $pid = $this->determinePageId($config['table'], $config['row']);

            if ($pid === false) {
                throw new \Exception('No record PID determined!', 1577109733537);
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
                        0 => ($view['name']) ?: $key,
                        1 => $key.'.',
                        2 => ($view['icon']) ?: 'extensions-generic-gallery',
                    ];
                }
            }

            $config['items'] = array_merge($config['items'], $optionList);
        }

        return $config;
    }

    /**
     * Determines the page id for a given record of a database table.
     *
     * Taken from \TYPO3\CMS\Backend\View\BackendLayoutView::determinePageId
     *
     * @param string $tableName
     * @param array $data
     * @return int|bool Returns page id or false on error
     */
    protected function determinePageId($tableName, array $data)
    {
        if (strpos($data['uid'], 'NEW') === 0) {
            // negative uid_pid values of content elements indicate that the element
            // has been inserted after an existing element so there is no pid to get
            // the backendLayout for and we have to get that first
            if ($data['pid'] < 0) {
                $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($tableName);
                $queryBuilder->getRestrictions()->removeAll();
                $pageId = $queryBuilder
                    ->select('pid')
                    ->from($tableName)
                    ->where(
                        $queryBuilder->expr()->eq(
                            'uid',
                            $queryBuilder->createNamedParameter(abs($data['pid']), \PDO::PARAM_INT)
                        )
                    )
                    ->execute()
                    ->fetchColumn();
            } else {
                $pageId = $data['pid'];
            }
        } elseif ($tableName === 'pages') {
            $pageId = $data['uid'];
        } else {
            $pageId = $data['pid'];
        }

        return $pageId;
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
