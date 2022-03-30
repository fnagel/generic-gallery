<?php

namespace FelixNagel\GenericGallery\Controller;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;

/**
 * GalleryCollectionController.
 */
class GalleryCollectionController extends AbstractController
{
    /**
     * {@inheritdoc}
     */
    protected function initializeView(ViewInterface $view)
    {
        $this->template = $this->currentSettings['template'];

        parent::initializeView($view);
    }

    /**
     * Show gallery.
     */
    public function showAction(int $page = 1): ResponseInterface
    {
        $paginator = new ArrayPaginator($this->collection->getArray(), $page, $this->getItemsPerPage());

        $this->view->assignMultiple([
            'collection' => $this->collection,
            'paginator' => $paginator,
            'pagination' => new SimplePagination($paginator),
        ]);

        return $this->htmlResponse();
    }

    protected function getItemsPerPage(): int
    {
        return array_key_exists('paginate', $this->currentSettings) && $this->currentSettings['paginate']['itemsPerPage']
            ? (int)$this->currentSettings['paginate']['itemsPerPage'] : 10;
    }
}
