<?php

namespace FelixNagel\GenericGallery\Domain\Model;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Resource\FileReference as CoreFileReference;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference as ExtbaseFileReference;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Core\Resource\File;

/**
 * Class GalleryItem.
 */
class GalleryItem extends AbstractEntity
{
    protected ?int $ttContentUid = null;

    protected ?string $title = null;

    protected ?string $link = null;

    /**
     * Annotation needed for Extbase.
     *
     * @var ExtbaseFileReference
     */
    protected ?ExtbaseFileReference $imageReference = null;

    protected ?File $image = null;

    /**
     * @var ObjectStorage<TextItem>
     */
    protected ?ObjectStorage $textItems = null;


    protected ?array $imageProperties = null;

    public function __construct()
    {
        $this->textItems = new ObjectStorage();
    }

    public function getIdentifier(): int|string|null
    {
        if ($this->isVirtual()) {
            return $this->getImage()->getUid();
        }

        return $this->getUid();
    }

    /**
     * If object is virtual.
     *
     * Virtual means it's generated and not a DB relation
     * So, if the object is virtual the plugin is of type
     * 'images' or 'collection'
     */
    public function isVirtual(): bool
    {
        return !(parent::getUid());
    }

    public function getLinkArguments(): array
    {
        return [
            'contentElement' => $this->getTtContentUid(),
            ($this->isVirtual() ? 'file' : 'item') => $this->getIdentifier(),
        ];
    }

    public function setTtContentUid(int $ttContentUid): void
    {
        $this->ttContentUid = $ttContentUid;
    }

    public function getTtContentUid(): int
    {
        return $this->ttContentUid;
    }

    public function getTitle(): string
    {
        if ($this->isVirtual() || empty($this->title)) {
            return !empty($this->getImageData()['title']) ?
                $this->getImageData()['title'] : (!empty($this->getImageData()['description']) ?
                    $this->getImageData()['description'] :
                    $this->getImageData()['name'] ?? '');
        }

        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getLink(): string
    {
        if ($this->isVirtual() || $this->link === '') {
            if ($this->getImageReference() !== null &&
                $this->getImageReference()->getOriginalResource()->getProperty('crop') !== null
            ) {
                // Render cropped image if reference with crop available
                return $this->getCroppedImageLinkFromReference();
            }

            return $this->getImage()->getPublicUrl();
        }

        return $this->link;
    }

    /**
     * Get url to cropped image from reference.
     */
    protected function getCroppedImageLinkFromReference(): string
    {
        /* @var $imageService ImageService */
        $imageService = GeneralUtility::makeInstance(ImageService::class);

        $processedImage = $imageService->applyProcessingInstructions(
            $this->getImageReference()->getOriginalResource(),
            ['crop' => $this->getImageReference()->getOriginalResource()->getProperty('crop')]
        );

        return $imageService->getImageUri($processedImage);
    }

    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    public function getImage(): File
    {
        if ($this->image === null) {
            return $this->getImageReference()->getOriginalResource()->getOriginalFile();
        }

        return $this->image;
    }

    public function setImage(File $image): void
    {
        $this->image = $image;
    }

    /**
     * Shortcut for image properties.
     */
    public function getImageData(): array
    {
        if ($this->imageProperties === null) {
            if ($this->getImageReference() !== null) {
                // Use reference inline data overridden properties
                $imageData = $this->getImageReference()->getOriginalResource()->getProperties();
            } else {
                $imageData = $this->getImage()->getProperties();
            }

            // Merge with additional metadata
            $this->imageProperties = array_merge($imageData, $this->getAdditionalImageProperties());
        }

        return $this->imageProperties;
    }

    /**
     * Return formatted image properties.
     */
    protected function getAdditionalImageProperties(): array
    {
        $properties = $this->getImage()->getProperties();

        if (ExtensionManagementUtility::isLoaded('metadata')) {
            return $this->processPropertiesForMetadaExtension($properties);
        }

        if (ExtensionManagementUtility::isLoaded('extractor')) {
            return $this->processPropertiesForExtractorExtension($properties);
        }

        return [];
    }

    protected function processPropertiesForExtractorExtension(array $properties): array
    {
        $data = [];

        // Process exif data
        $data['shutter_speed'] = $properties['shutter_speed'].'s';
        $data['aperture'] = 'f/'.$properties['aperture'];
        $data['focal_length'] = $properties['focal_length'].'mm';
        $data['iso_speed'] = 'ISO'.$properties['iso_speed'];

        if (version_compare(ExtensionManagementUtility::getExtensionVersion('extractor'), '2.0.0', '>=')) {
            $data['flash'] = LocalizationUtility::translate($this->getFlashLabelFromTca($properties));
        }

        return $data;
    }

    protected function processPropertiesForMetadaExtension(array $properties): array
    {
        return [
            // Process exif data
            'shutter_speed_value' => $properties['shutter_speed_value'].'s',
            'aperture_value' => 'f/'.$properties['aperture_value'],
            'focal_length' => $properties['focal_length'].'mm',
            'iso_speed_ratings' => 'ISO'.$properties['iso_speed_ratings'],
            'flash' => $this->getFlashLabelFromTca($properties),
        ];
    }

    /**
     * Process flash data
     */
    protected function getFlashLabelFromTca(array $properties): ?string
    {
        if (isset($GLOBALS['TCA']['sys_file_metadata']['columns']['flash']['config']['items'])) {
            $items = (array)$GLOBALS['TCA']['sys_file_metadata']['columns']['flash']['config']['items'];

            foreach ($items as $item) {
                if (array_key_exists('value', $item) && array_key_exists('label', $item) &&
                    array_key_exists('flash', $properties) && (int)$item['value'] === (int)$properties['flash']
                ) {
                    return $item['label'];
                }
            }
        }

        return null;
    }

    public function setImageReference(ExtbaseFileReference|CoreFileReference $imageReference): void
    {
        $fileReference = $imageReference;

        // Normalize to extbase file reference
        if ($imageReference instanceof CoreFileReference) {
            $fileReference = new ExtbaseFileReference();
            $fileReference->setOriginalResource($imageReference);
        }

        $this->imageReference = $fileReference;
    }

    public function getImageReference(): ?ExtbaseFileReference
    {
        return $this->imageReference;
    }

    /**
     * @api
     */
    public function setTextItems(ObjectStorage $textItems): void
    {
        $this->textItems = $textItems;
    }

    /**
     * @api
     */
    public function addTextItem(TextItem $textItems): void
    {
        $this->textItems->attach($textItems);
    }

    /**
     * @api
     */
    public function removeTextItem(TextItem $textItems): void
    {
        $this->textItems->detach($textItems);
    }

    /**
     * @return ObjectStorage An object storage containing the textItems
     *
     * @api
     */
    public function getTextItems(): ObjectStorage
    {
        return $this->textItems;
    }
}
