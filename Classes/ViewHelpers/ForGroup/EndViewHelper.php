<?php

namespace FelixNagel\GenericGallery\ViewHelpers\ForGroup;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * ForGroupViewHelper.
 */
class EndViewHelper extends AbstractViewHelper
{
	/**
	 * @inheritDoc
	 */
	public static function verdict(array $arguments, RenderingContextInterface $renderingContext) {
		return $arguments['iteration']['isLast'] || ($arguments['iteration']['cycle'] % $arguments['max']) === 0;
	}
}
