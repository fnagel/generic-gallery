<?php

namespace FelixNagel\GenericGallery\Backend\Hooks;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Doctrine\DBAL\ParameterType;
use FelixNagel\GenericGallery\Service\SettingsService;
use TYPO3\CMS\Backend\Form\FormDataProvider\TcaSelectItems;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook class for TCA hook.
 */
class TcaHook
{
    protected ?object $settingsService = null;

    /**
     * Sets the items for the "Predefined" dropdown.
     *
     * @param array $config
     */
    public function addPredefinedFields(array &$config): void
    {
        $optionList = [];

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

                $config['items'] = array_merge($config['items'], $optionList);
                return;
            }

            // For each view
            $optionList = [
                [
                    'label' => $this->translate('cms_layout.please_select'),
                    'value' => '',
                ],
            ];
            foreach ($settings['gallery'] as $key => $view) {
                if (is_array($view)) {
                    $optionList[] = [
                        'label' => ($view['name']) ?: $key,
                        'value' => $key.'.',
                        'icon' => (isset($view['icon']) && $view['icon']) ? $view['icon'] : 'extensions-generic-gallery',
                    ];
                }
            }

            $config['items'] = array_merge($config['items'], $optionList);
        }
    }

    /**
     * Determines the page id for a given record of a database table.
     *
     * Taken from \TYPO3\CMS\Backend\View\BackendLayoutView::determinePageId
     *
     * @param string $tableName
     * @return int|bool Returns page id or false on error
     */
    protected function determinePageId($tableName, array $data): int|bool
    {
        if (str_starts_with($data['uid'], 'NEW')) {
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
                            $queryBuilder->createNamedParameter(abs($data['pid']), ParameterType::INTEGER)
                        )
                    )->executeQuery()
                    ->fetchOne();
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

    protected function translate(
        string $key,
        string $keyPrefix = 'LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf'
    ): string {
        return $GLOBALS['LANG']->sL($keyPrefix.':'.$key);
    }

    /**
     * @return SettingsService
     */
    protected function getTypoScriptService()
    {
        if ($this->settingsService === null) {
            $this->settingsService = GeneralUtility::makeInstance(SettingsService::class);
        }

        return $this->settingsService;
    }
}
