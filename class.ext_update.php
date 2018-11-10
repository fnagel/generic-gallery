<?php

/**
 * Class ext_update.
 *
 * Performs update tasks for extension genericgallery
 */
class ext_update
{
    /**
     * Main function, returning the HTML content of the module.
     *
     * @return string HTML
     */
    public function main()
    {
        $table = 'tt_content';
        $connectionPool = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)
            ->getConnectionForTable($table);

        $count = $connectionPool->update(
            $table,
            ['list_type' => 'genericgallery_pi1'],
            ['list_type' => 'generic_gallery_pi1']
        );

        return $count.' rows have been updated.';
    }



    /**
     * Checks how many rows are found and returns true if there are any
     * (this function is called from the extension manager).
     *
     * @param string $what : what should be updated
     *
     * @return bool
     */
    public function access($what = 'all')
    {
        return $GLOBALS['BE_USER']->isAdmin();
    }
}
