<?php

namespace FelixNagel\GenericGallery\Domain\Model;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014-2018 Felix Nagel <info@felixnagel.com>
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
