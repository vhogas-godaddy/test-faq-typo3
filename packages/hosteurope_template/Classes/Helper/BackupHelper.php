<?php
namespace HostEuropeGmbh\HosteuropeTemplate\Helper;

use \TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class BackupHelper
 * @package HostEuropeGmbh\HosteuropeTemplate\Helper
 */
class BackupHelper
{   
    /**
     * @var string
     */
    const DEFAULT_DIR_LOG = 'typo3temp/custom_backup';

    /**
     * @var string
     */
    const DEFAULT_FILE_ENDING = '.json';

    /**
     * @name saveBackup
     *
     * @param integer $entityId
     * @param string $type
     * @param mixed $content
     * 
     * @return void
     */
    public static function saveBackup($entityId = null, $type = 'category', $content = '')
    {
        if ( ! is_null($entityId) && ! empty($content)) {
            
            // If the content is array make it a json
            if (is_array($content)) {
                $content = json_encode($content);
            }

            /**
             * Creating the directory (if it not exists)
             */
            $dir = PATH_site . self::DEFAULT_DIR_LOG . DIRECTORY_SEPARATOR . $type;

            if ( ! is_dir($dir)) {
                $dirCreated = GeneralUtility::mkdir_deep($dir);
            } else {
                $dirCreated = true;
            }

            if ($dirCreated) {
                /**
                 * Create the file (if it not exists)
                 */

                $fileName = $dir . DIRECTORY_SEPARATOR . $entityId . self::DEFAULT_FILE_ENDING;
                @file_put_contents($fileName, $content);
            }
        }
    }

    /**
     * @name getBackup
     *
     * @param integer $entityId
     * @param string $type
     *
     * @return mixed (false if backup is not found, array otherwise)
     */
    public static function getBackup($entityId = null, $type = 'category')
    {
        if ( ! is_null($entityId)) {
            $dir = PATH_site . self::DEFAULT_DIR_LOG . DIRECTORY_SEPARATOR . $type;
            $fileName = $dir . DIRECTORY_SEPARATOR . $entityId . self::DEFAULT_FILE_ENDING;

            $content =  @file_get_contents($fileName);

            if ($content !== false) {
                return json_decode($content);
            }
        }

        return false;
    }
}







