<?php
declare(strict_types = 1);

namespace FelixNagel\GenericGallery\Routing\Aspect;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use FelixNagel\GenericGallery\Domain\Model\GalleryItem;
use TYPO3\CMS\Core\Routing\Aspect\PersistedPatternMapper;

/**
 * ImageItemMapper
 *
 * Modified persisted pattern mapper. Adds some changes in order to
 * deal with the fact we have two tables to check for the given value.
 */
class ImageItemMapper extends PersistedPatternMapper
{
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
    protected $routeFieldResultNamesFileReference = [];

    /**
     * @var string|null
     */
    protected $languageParentFieldNameFileReference;

    /**
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

    protected function getFileReferencePrefix(): string
    {
        return GalleryItem::FILE_REFERENCE_IDENTIFIER_PREFIX;
    }

    protected function removeFileReferencePrefix(string $value): string
    {
        return substr($value, strlen($this->getFileReferencePrefix()));
    }

    protected function isFileReference(string $value): bool
    {
        return (str_starts_with($value, $this->getFileReferencePrefix()));
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
