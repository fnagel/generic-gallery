<?php

namespace FelixNagel\GenericGallery\Controller;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\NullResponse;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Fluid\View\FluidViewAdapter;

/**
 * GalleryItemController.
 */
class GalleryItemController extends AbstractController
{
    /**
     * {@inheritdoc}
     */
    public function processRequest(RequestInterface $request): ResponseInterface
    {
        if (!$this->isContentElement($request)) {
            // Request should be handled by another instance of this plugin
            return new NullResponse();
        }

        return parent::processRequest($request);
    }

    protected function initializeView(FluidViewAdapter $view): void
    {
        $this->template = $this->currentSettings['itemTemplate'] ?? null;

        parent::initializeView($view);
    }

    /**
     * Display single item.
     */
    public function showAction(?string $item = null, ?string $file = null): ResponseInterface
    {
        $item = $this->collection->getByIdentifier($item ?: $file);

        if ($item === null) {
            // @extensionScannerIgnoreLine
            $this->pageNotFoundAndExit();
        }

        $this->view->assign('item', $item);

        return $this->htmlResponse();
    }
}
