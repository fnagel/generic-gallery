<?php

namespace TYPO3\GenericGallery\Utility;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) Alexander Buchgeher
 *  (c) Georg Ringer <typo3@ringerge.org>
 *  (c) 2015-2016 Felix Nagel <info@felixnagel.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Utility class to get the settings from Extension Manager.
 */
class EmConfiguration
{
    /**
     * Parses the extension settings.
     *
     * @return \TYPO3\GenericGallery\Domain\Model\Dto\EmConfiguration
     *
     * @throws \Exception If the configuration is invalid.
     */
    public static function getSettings()
    {
        $configuration = self::parseSettings();
        GeneralUtility::requireOnce(
            ExtensionManagementUtility::extPath('generic_gallery').'Classes/Domain/Model/Dto/EmConfiguration.php'
        );

        return new \TYPO3\GenericGallery\Domain\Model\Dto\EmConfiguration($configuration);
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
            $settings = array();
        }

        return $settings;
    }
}
