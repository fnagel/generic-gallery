<?php

namespace FelixNagel\GenericGallery\Domain\Repository;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use \TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * The repository for GalleryItems.
 */
class GalleryItemRepository extends Repository
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
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);

        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * @param int $uid Uid of the content element (tt_content)
     */
    public function findForContentElement(int $uid): QueryResultInterface
    {
        $query = $this->createQuery();
        $constraints = [
            $query->equals('ttContentUid', (int) $uid),
            $query->equals('imageReference.hidden', 0),
        ];

        $query->matching($query->logicalAnd($constraints));

        return $query->execute();
    }
}
