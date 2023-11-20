<?php

namespace FelixNagel\GenericGallery\Domain\Model;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * TextItem.
 */
class TextItem extends AbstractEntity
{
    protected string $bodytext = '';

    protected string $position = '';

    protected string $width = '';

    public function getBodytext(): string
    {
        return $this->bodytext;
    }

    public function getText(): string
    {
        return $this->getBodytext();
    }

    public function setBodytext(string $bodytext)
    {
        $this->bodytext = $bodytext;
    }

    public function getPosition(): array
    {
        $coordsArray = GeneralUtility::intExplode(',', $this->position, true);

        return [
            'x' => $coordsArray[0],
            'y' => $coordsArray[1],
        ];
    }

    public function setPosition(string $position): void
    {
        $this->position = $position;
    }

    public function getWidth(): string
    {
        return $this->width;
    }

    public function setWidth(string $width)
    {
        $this->width = $width;
    }

    public function getCssStyles(): string
    {
        $string = '';
        $position = $this->getPosition();
        $classes = [
            'position' => 'absolute',
            'left' => $position['x'].'px',
            'top' => $position['y'].'px',
        ];

        if ($this->getWidth() !== '') {
            $classes['width'] = $this->getWidth().'px';
        }

        foreach ($classes as $class => $value) {
            $string .= $class.': '.$value.'; ';
        }

        return $string;
    }
}
