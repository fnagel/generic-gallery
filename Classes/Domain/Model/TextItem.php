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
    /**
     * bodytext.
     *
     * @var string
     */
    protected $bodytext = '';

    /**
     * position.
     *
     * @var string
     */
    protected $position = '';

    /**
     * width.
     *
     * @var string
     */
    protected $width = '';

    /**
     * Returns the bodytext.
     *
     * @return string $bodytext
     */
    public function getBodytext()
    {
        return $this->bodytext;
    }

    /**
     * Returns the bodytext.
     *
     * @return string $bodytext
     */
    public function getText()
    {
        return $this->getBodytext();
    }

    /**
     * Sets the bodytext.
     *
     * @param string $bodytext
     */
    public function setBodytext($bodytext)
    {
        $this->bodytext = $bodytext;
    }

    /**
     * Returns the position.
     *
     * @return array $position
     */
    public function getPosition()
    {
        $coordsArray = GeneralUtility::intExplode(',', $this->position, true);

        return [
            'x' => $coordsArray[0],
            'y' => $coordsArray[1],
        ];
    }

    /**
     * Sets the position.
     *
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * Returns the width.
     *
     * @return string $width
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Sets the width.
     *
     * @param string $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * Returns the position.
     *
     * @return string $position
     */
    public function getCssStyles()
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
