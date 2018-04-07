<?php

namespace TYPO3\GenericGallery\Domain\Model\Dto;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) Georg Ringer <typo3@ringerge.org>
 *  (c) 2015-2018 Felix Nagel <info@felixnagel.com>
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

            if (property_exists(__CLASS__, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @var bool
     */
    protected $enableCmsLayout = true;

    /**
     * @var int
     */
    protected $useInlineCollection = 1;

    /**
     * @var int
     */
    protected $addImageCollection = 0;


    /**
     * @return int
     */
    public function getUseInlineCollection()
    {
        return $this->useInlineCollection;
    }

    /**
     * @param int $useInlineCollection
     */
    public function setUseInlineCollection($useInlineCollection)
    {
        $this->useInlineCollection = $useInlineCollection;
    }

    /**
     * @return bool
     */
    public function isEnableCmsLayout()
    {
        return $this->enableCmsLayout;
    }

    /**
     * @param bool $enableCmsLayout
     */
    public function setEnableCmsLayout($enableCmsLayout)
    {
        $this->enableCmsLayout = $enableCmsLayout;
    }

    /**
     * @return int
     */
    public function getAddImageCollection()
    {
        return $this->addImageCollection;
    }

    /**
     * @param int $addImageCollection
     */
    public function setAddImageCollection($addImageCollection)
    {
        $this->addImageCollection = $addImageCollection;
    }
}
