<?php

namespace FelixNagel\GenericGallery\ViewHelpers\ForGroup;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * ForGroupViewHelper.
 */
class AbstractViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * @inheritdoc
     */
    protected $escapeOutput = false;

    /**
     * Initializes the arguments for the ViewHelper.
     */
    public function initializeArguments()
    {
        $this->registerArgument('iteration', 'array', 'The for VH iteration array', true);
        $this->registerArgument('max', 'int', 'Max items in one for group', true, 2);
    }

    /**
     * Initializes the view helper before invoking the render method.
     *
     * Override this method to solve tasks before the view helper content is rendered.
     *
     * @api
     */
    public function initialize()
    {
        $this->iteration = $this->arguments['iteration'];
        $this->max = $this->arguments['max'];
    }
}
