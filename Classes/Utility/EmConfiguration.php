<?php

namespace FelixNagel\GenericGallery\Utility;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Utility class to get the settings from Extension Manager.
 */
class EmConfiguration
{
    /**
     * Parses the extension settings.
     *
     * @return \FelixNagel\GenericGallery\Domain\Model\Dto\EmConfiguration
     *
     * @throws \Exception If the configuration is invalid.
     */
    public static function getSettings()
    {
        $configuration = self::parseSettings();

        // @todo Check if this is still needed
        require_once(ExtensionManagementUtility::extPath('generic_gallery').'Classes/Domain/Model/Dto/EmConfiguration.php');

        return new \FelixNagel\GenericGallery\Domain\Model\Dto\EmConfiguration($configuration);
    }

    /**
     * Parse settings and return it as array.
     *
     * @return array unserialized extconf settings
     */
    public static function parseSettings()
    {
        $settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['generic_gallery']);

        if (!is_array($settings)) {
            $settings = [];
        }

        return $settings;
    }
}
