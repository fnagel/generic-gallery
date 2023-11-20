<?php

namespace FelixNagel\GenericGallery\UpgradeWizard;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class PluginImageFieldWizard extends AbstractUpgradeWizard
{
    public function getTitle(): string
    {
        return 'Generic Gallery: Migrate old image field name (TYPO3 v12)';
    }

    public function executeUpdate(): bool
    {
        $table = 'sys_file_reference';
        $count = 0;

        $count += $this->connectionPool->getConnectionForTable($table)->update(
            $table,
            ['fieldname' => 'tx_generic_gallery_images'],
            [
                'tablenames' => 'tt_content',
                'fieldname' => 'tx_generic_gallery_picture_single',
            ]
        );
        $count += $this->connectionPool->getConnectionForTable($table)->update(
            $table,
            ['fieldname' => 'images'],
            [
                'tablenames' => 'tx_generic_gallery_pictures',
                'fieldname' => 'tx_generic_gallery_picture_single',
            ]
        );

        $this->output->writeln($count.' rows have been updated.');

        return true;
    }
}
