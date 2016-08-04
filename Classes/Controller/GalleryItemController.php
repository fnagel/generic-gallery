<?php

namespace TYPO3\GenericGallery\Controller;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014-2016 Felix Nagel <info@felixnagel.com>
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
        $this->view->assign('item', $this->collection->getByUid($item));
    }
}
