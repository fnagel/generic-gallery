<?php

namespace FelixNagel\GenericGallery\Domain\Repository;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use \TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * The repository for GalleryItems.
 */
class GalleryItemRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * @var array
     */
    protected $defaultOrderings = [
        'sorting' => QueryInterface::ORDER_ASCENDING
    ];

    /**
     * Initializes the repository.
     */
    public function initializeObject()
    {
        /** @var $querySettings Typo3QuerySettings */
        $querySettings = $this->objectManager->get(Typo3QuerySettings::class);

        $querySettings->setRespectStoragePage(false);

        $this->setDefaultQuerySettings($querySettings);
    }
}
