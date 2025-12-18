<?php

namespace FelixNagel\GenericGallery\Domain\Repository;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Collection\CollectionInterface;
use TYPO3\CMS\Core\Resource\FileCollectionRepository;
use TYPO3\CMS\Core\SingletonInterface;

class CollectionRepository implements SingletonInterface
{
    public function __construct(
        protected readonly FileCollectionRepository $fileCollectionRepository,
    ) {}

    public function findByUid(int $uid): CollectionInterface
    {
        return $this->fileCollectionRepository->findByUid($uid);
    }
}
