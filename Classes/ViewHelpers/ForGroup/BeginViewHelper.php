<?php

namespace FelixNagel\GenericGallery\ViewHelpers\ForGroup;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * ForGroupViewHelper.
 */
class BeginViewHelper extends AbstractViewHelper
{
	/**
	 * @inheritDoc
	 */
	public static function verdict(array $arguments, RenderingContextInterface $renderingContext) {
		return $arguments['iteration']['isFirst'] || ($arguments['iteration']['cycle'] % $arguments['max']) === 1;
	}
}
