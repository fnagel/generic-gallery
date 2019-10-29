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
class BeginViewHelper extends AbstractViewHelper
{
    /**
     * @return mixed
     */
    public function render()
    {
        if ($this->iteration['isFirst'] || ($this->iteration['cycle'] % $this->max) === 1) {
            return $this->renderChildren();
        }

        return '';
    }
}
