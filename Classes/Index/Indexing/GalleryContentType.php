<?php

declare(strict_types=1);

namespace FelixNagel\GenericGallery\Index\Indexing;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Lochmueller\Index\Indexing\Database\ContentType\ContentTypeInterface;
use Lochmueller\Index\Indexing\Database\ContentType\HeaderContentType;
use Lochmueller\Index\Indexing\Database\DatabaseIndexingDto;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use TYPO3\CMS\Core\Domain\Record;
use TYPO3\CMS\Core\Resource\FileCollectionRepository;
use TYPO3\CMS\Core\Resource\FileInterface;

/**
 * Content type for the database indexing of EXT:index.
 *
 * Adds the header and the title and description of all images. Supports all gallery sources:
 * single items (incl. text items), images and file collections.
 */
#[AutoconfigureTag(name: 'index.content_type')]
class GalleryContentType implements ContentTypeInterface
{
    public function __construct(
        protected HeaderContentType $headerContentType,
        protected FileCollectionRepository $fileCollectionRepository,
    ) {
    }

    public function canHandle(Record $record): bool
    {
        return $record->getRecordType() === 'genericgallery_pi1';
    }

    public function addContent(Record $record, DatabaseIndexingDto $dto): void
    {
        $this->headerContentType->addContent($record, $dto);

        $content = [];
        foreach ($this->getItems($record) as $item) {
            $content[] = $item->get('title');
            $content = [...$content, ...$this->getFileContent($item->get('images'))];

            foreach ($this->toIterable($item->get('contents')) as $textItem) {
                $content[] = $textItem->get('bodytext');
            }
        }

        $content = [...$content, ...$this->getFileContent($record->get('tx_generic_gallery_images'))];
        $content = [...$content, ...$this->getCollectionContent($record)];

        $dto->content .= implode(' ', array_filter(array_map('trim', array_map('strval', $content))));
    }

    /**
     * @param \SplQueue<DatabaseIndexingDto> $queue
     */
    public function addVariants(Record $record, \SplQueue &$queue): void
    {
    }

    /**
     * @return iterable<Record>
     */
    protected function getItems(Record $record): iterable
    {
        return $this->toIterable($record->get('tx_generic_gallery_items'));
    }

    protected function getCollectionContent(Record $record): array
    {
        $uid = (int)$record->getRawRecord()->get('tx_generic_gallery_collection');
        if ($uid <= 0) {
            return [];
        }

        try {
            $collection = $this->fileCollectionRepository->findByUid($uid);
        } catch (\Exception) {
            return [];
        }
        $collection->loadContents();

        return $this->getFileContent($collection);
    }

    protected function getFileContent(mixed $files): array
    {
        $content = [];
        foreach ($this->toIterable($files) as $file) {
            if (!$file instanceof FileInterface) {
                continue;
            }

            foreach (['title', 'description'] as $property) {
                if ($file->hasProperty($property)) {
                    $content[] = $file->getProperty($property);
                }
            }
        }

        return $content;
    }

    /**
     * Relations with maxitems = 1 are resolved as single object.
     */
    protected function toIterable(mixed $value): iterable
    {
        if ($value === null) {
            return [];
        }

        return is_iterable($value) ? $value : [$value];
    }
}
