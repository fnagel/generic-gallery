<?php

namespace FelixNagel\GenericGallery\Controller;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

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
    public function showAction()
    {
        $this->view->assign('collection', $this->collection);
    }
}
