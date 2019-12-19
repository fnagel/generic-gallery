<?php

namespace FelixNagel\GenericGallery\Controller;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Mvc\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

/**
 * GalleryItemController.
 */
class GalleryItemController extends AbstractController
{
    /**
     * {@inheritdoc}
     */
    public function processRequest(RequestInterface $request, ResponseInterface $response)
    {
        if (
            !$request->hasArgument('contentElement') ||
            $this->getContentElementUid() !== (int) $request->getArgument('contentElement')
        ) {
            // Request should be handled by another instance of this plugin
            $this->request = $request;
            $this->request->setDispatched(true);

            return;
        }

        parent::processRequest($request, $response);
    }

    /**
     * {@inheritdoc}
     */
    protected function initializeView(ViewInterface $view)
    {
        $this->template = $this->currentSettings['itemTemplate'];

        parent::initializeView($view);
    }

    /**
     * Display single item.
     *
     * @param string $item
     */
    public function showAction($item)
    {
        $item = $this->collection->getByIdentifier($item);

        if ($item === null) {
            $this->pageNotFoundAndExit();
        }

        $this->getTypoScriptFrontendController()->reqCHash();
        $this->view->assign('item', $item);
    }
}
