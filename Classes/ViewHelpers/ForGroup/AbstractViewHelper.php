<?php

namespace FelixNagel\GenericGallery\ViewHelpers\ForGroup;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

/**
 * ForGroupViewHelper.
 */
class AbstractViewHelper extends AbstractConditionViewHelper
{
	/**
	 * @inheritdoc
	 */
	protected $escapeOutput = false;

	/**
	 * @inheritDoc
	 */
	public function initializeArguments() {
		$this->registerArgument('iteration', 'array', 'The for VH iteration array', true);
		$this->registerArgument('max', 'int', 'Max items in one for group', true, 2);
	}
}
