<?php

namespace FelixNagel\GenericGallery\UpgradeWizard;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Install\Updates\ChattyInterface;
use TYPO3\CMS\Install\Updates\ConfirmableInterface;
use TYPO3\CMS\Install\Updates\Confirmation;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * Abstract upgrade wizard.
 */
abstract class AbstractUpgradeWizard implements UpgradeWizardInterface, ChattyInterface, ConfirmableInterface
{
    /**
     * The database connection.
     */
    protected ConnectionPool $connectionPool;

    /**
     * @var OutputInterface
     */
    protected $output;

    public function __construct()
    {
        $this->connectionPool =  GeneralUtility::makeInstance(
            ConnectionPool::class
        );
    }

    public function getIdentifier(): string
    {
        return static::class;
    }

    public function getDescription(): string
    {
        return $this->getTitle();
    }

    public function updateNecessary(): bool
    {
        return true;
    }

    public function getConfirmation(): Confirmation
    {
        return new Confirmation(
            'Are you sure?',
            'This wizard will alter the database. Be careful in production environments!',
            false
        );
    }

    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class,
        ];
    }

    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }
}
