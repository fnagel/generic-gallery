<?php
declare(strict_types = 1);

namespace FelixNagel\GenericGallery\Routing\Aspect;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 Felix Nagel <info@felixnagel.com>
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

use FelixNagel\GenericGallery\Domain\Model\GalleryItem;
use TYPO3\CMS\Core\Routing\Aspect\PersistedPatternMapper;
use TYPO3\CMS\Core\Site\SiteLanguageAwareTrait;

/**
 * ImageItemMapper
 *
 * Modified persisted pattern mapper. Adds some changes in order to
 * deal with the fact we have two tables to check for the given value.
 */
class ImageItemMapper extends PersistedPatternMapper
{
    use SiteLanguageAwareTrait;

    /**
     * @var string
     */
    protected $tableNameFileReference;

    /**
     * @var string
     */
    protected $routeFieldPatternFileReference;

    /**
     * @var string
     */
    protected $routeFieldResultFileReference;

    /**
     * @var string[]
     */
    protected $routeFieldResultNamesFileReference;

    /**
     * @var string|null
     */
    protected $languageParentFieldNameFileReference;

    /**
     * @param array $settings
     * @throws \InvalidArgumentException
     */
    public function __construct(array $settings)
    {
        parent::__construct($settings);

        $tableNameFileReference = $settings['tableNameFileReference'] ?? null;
        $routeFieldPatternFileReference = $settings['routeFieldPatternFileReference'] ?? null;
        $routeFieldResultFileReference = $settings['routeFieldResultFileReference'] ?? null;

        if (!is_string($tableNameFileReference)) {
            throw new \InvalidArgumentException('tableNameFileReference must be string', 1569340715360);
        }
        if (!is_string($routeFieldPatternFileReference)) {
            throw new \InvalidArgumentException('routeFieldPatternFileReference must be string', 1569340717709);
        }
        if (!is_string($routeFieldResultFileReference)) {
            throw new \InvalidArgumentException('routeFieldResultFileReference must be string', 1569340720105);
        }
        if (!preg_match_all(static::PATTERN_RESULT, $routeFieldResultFileReference, $routeFieldResultNamesFileReference)) {
            throw new \InvalidArgumentException(
                'routeFieldResultFileReference must contain substitutable field names',
                1569340740933
            );
        }

        $this->tableNameFileReference = $tableNameFileReference;
        $this->routeFieldPatternFileReference = $routeFieldPatternFileReference;
        $this->routeFieldResultFileReference = $routeFieldResultFileReference;
        $this->routeFieldResultNamesFileReference = $routeFieldResultNamesFileReference['fieldName'] ?? [];
        $this->languageParentFieldNameFileReference = $GLOBALS['TCA'][$this->tableNameFileReference]['ctrl']['transOrigPointerField'] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(string $value): ?string
    {
        if ($this->isFileReference($value)) {
            $this->changeSettingsForFileReference();
        }

        return parent::generate($value);
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(string $value): ?string
    {
        $isFileReference = $this->isFileReference($value);

        if ($isFileReference) {
            $this->changeSettingsForFileReference();
        }

        $result = parent::resolve($value);

        if ($isFileReference && $result !== null) {
            return $this->getFileReferencePrefix().$result;
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    protected function filterNamesKeys(array $array): array
    {
        $array = parent::filterNamesKeys($array);

        // Remove prefix so the related item can be found
        foreach ($array as $key => $value) {
            if ($key === 'uid' && $this->isFileReference($value)) {
                $array[$key] = $this->removeFileReferencePrefix($value);
                break;
            }
        }
        
        return $array;
    }

    /**
     * @return string
     */
    protected function getFileReferencePrefix(): string
    {
        return GalleryItem::FILE_REFERENCE_IDENTIFIER_PREFIX;
    }

    /**
     * @param string $value
     * @return string
     */
    protected function removeFileReferencePrefix(string $value): string
    {
        return substr($value, strlen($this->getFileReferencePrefix()));
    }

    /**
     * @param string $value
     * @return bool
     */
    protected function isFileReference(string $value): bool
    {
        return (strpos($value, $this->getFileReferencePrefix()) === 0);
    }

    /**
     * @return void
     */
    protected function changeSettingsForFileReference()
    {
        $this->tableName = $this->tableNameFileReference;
        $this->routeFieldPattern = $this->routeFieldPatternFileReference;
        $this->routeFieldResult = $this->routeFieldResultFileReference;
        $this->routeFieldResultNames = $this->routeFieldResultNamesFileReference;
        $this->languageParentFieldName = $this->languageParentFieldNameFileReference;
    }
}
