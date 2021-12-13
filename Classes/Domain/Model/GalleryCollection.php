<?php

namespace FelixNagel\GenericGallery\Domain\Model;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\File;

/**
 * GalleryCollection.
 */
class GalleryCollection extends ObjectStorage
{
    /**
     * Returns item by uid.
     *
     * @param int|string $uid UID int or virtual UID string
     *
     * @param int|string $uid
     * @return GalleryItem
     */
    public function getByIdentifier($uid): ?GalleryItem
    {
        if (empty($uid)) {
            return null;
        }

        $storage = array_values($this->storage);
        foreach ($storage as $item) {
            /* @var $galleryItem GalleryItem */
            $galleryItem = $item['obj'];

            if ((string) $uid === (string) $galleryItem->getIdentifier()) {
                return $galleryItem;
            }
        }

        return null;
    }

    /**
     * Adds all objects-data pairs from an array
     *
     * @return void
     */
    public function addAllFromArray(array $items)
    {
        foreach ($items as $object) {
            $this->attach($object);
        }
    }

    /**
     * @return $this
     */
    public function addAllFromFiles(array $data)
    {
        foreach ($data as $object) {
            $item = new GalleryItem();

            if ($object instanceof FileReference) {
                /* @var $object \TYPO3\CMS\Core\Resource\FileReference */
                $item->setImage($object->getOriginalFile());
                $item->setImageReference($object);
            }

            if ($object instanceof File) {
                /* @var $object \TYPO3\CMS\Core\Resource\File */
                $item->setImage($object);
            }

            $this->attach($item);
        }

        return $this;
    }

    public function removeNonImageFiles()
    {
        $extensions = GeneralUtility::trimExplode(',', $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'], true);

        /* @var $item GalleryItem */
        foreach ($this as $item) {
            if (!in_array($item->getImage()->getExtension(), $extensions)) {
                $this->detach($item);
            }
        }
    }
}
