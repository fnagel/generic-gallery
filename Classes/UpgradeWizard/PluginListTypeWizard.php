<?php

namespace FelixNagel\GenericGallery\UpgradeWizard;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Install\Attribute\UpgradeWizard;

#[UpgradeWizard(PluginListTypeWizard::class)]
class PluginListTypeWizard extends AbstractUpgradeWizard
{
    public function getTitle(): string
    {
        return 'Generic Gallery: Migrate old plugin list type name (TYPO3 v6)';
    }

    /**
     * @deprecated Remove this with TYPO3 14 as this migration is ancient.
     */
    public function executeUpdate(): bool
    {
        $table = 'tt_content';
        $count = $this->connectionPool->getConnectionForTable($table)->update(
            $table,
            ['list_type' => 'genericgallery_pi1'],
            ['list_type' => 'generic_gallery_pi1']
        );

        $this->output->writeln($count.' rows have been updated.');

        return true;
    }
}
