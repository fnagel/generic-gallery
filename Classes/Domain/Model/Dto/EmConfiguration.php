<?php

namespace FelixNagel\GenericGallery\Domain\Model\Dto;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Utility class to get the settings from Extension Manager.
 */
class EmConfiguration
{
    /**
     * Fill the properties properly.
     *
     * @param array $configuration em configuration
     */
    public function __construct(array $configuration)
    {
        foreach ($configuration as $key => $value) {
            $key = GeneralUtility::underscoredToLowerCamelCase($key);

            if (property_exists(self::class, $key)) {
                $this->$key = $value;
            }
        }
    }

    protected bool $enableCmsLayout = true;

    protected int $useInlineCollection = 1;

    protected int $addImageCollection = 1;

    /**
     * @return bool
     */
    public function isEnableCmsLayout()
    {
        return $this->enableCmsLayout;
    }

    /**
     * @return int
     */
    public function getUseInlineCollection()
    {
        return $this->useInlineCollection;
    }

    /**
     * @return int
     */
    public function getAddImageCollection()
    {
        return $this->addImageCollection;
    }
}
