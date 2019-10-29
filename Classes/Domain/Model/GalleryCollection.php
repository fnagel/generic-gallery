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
     * @return GalleryItem
     */
    public function getByUid($uid)
    {
        if (empty($uid)) {
            return null;
        }

        $storage = array_values($this->storage);
        foreach ($storage as $item) {
            /* @var $galleryItem GalleryItem */
            $galleryItem = $item['obj'];

            if ((string) $uid === (string) $galleryItem->getUid()) {
                return $galleryItem;
            }
        }

        return null;
    }

    /**
     * Adds all objects-data pairs from an array
     *
     * @param array $items
     * @return void
     */
    public function addAllFromArray(array $items)
    {
        foreach ($items as $object) {
            $this->attach($object);
        }
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function addAllFromFiles(array $data)
    {
        foreach ($data as $object) {
            $item = new GalleryItem();

            if ($object instanceof \TYPO3\CMS\Core\Resource\FileReference) {
                /* @var $object \TYPO3\CMS\Core\Resource\FileReference */
                $item->setImage($object->getOriginalFile());
                $item->setImageReference($object);
            }

            if ($object instanceof \TYPO3\CMS\Core\Resource\File) {
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
