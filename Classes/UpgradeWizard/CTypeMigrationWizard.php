<?php

namespace FelixNagel\GenericGallery\UpgradeWizard;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\AbstractListTypeToCTypeUpdate;

#[UpgradeWizard(CTypeMigrationWizard::class)]
class CTypeMigrationWizard extends AbstractListTypeToCTypeUpdate
{
    protected function getListTypeToCTypeMapping(): array
    {
        return ['genericgallery_pi1' => 'genericgallery_pi1'];
    }

    public function getTitle(): string
    {
        return 'Migrate "generic_gallery" plugins to content elements (TYPO3 v13)';
    }

    public function getDescription(): string
    {
        return 'The "generic_gallery" plugin is now registered as content element.'.
            ' Update migrates existing records and backend user permissions.';
    }
}
