<?php

namespace FelixNagel\GenericGallery\Utility;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
        $configuration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('generic_gallery');

        return new \FelixNagel\GenericGallery\Domain\Model\Dto\EmConfiguration($configuration);
    }
}
