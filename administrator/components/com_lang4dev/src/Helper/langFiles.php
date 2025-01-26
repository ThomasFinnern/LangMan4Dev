<?php
/**
 * This class contains translations file
 * with references to empty lines and comments
 *
 * @version
 * @package       Lang4dev
 * @copyright  (c)  2022-2025 Lang4dev Team
 * @license
 */

namespace Finnern\Component\Lang4dev\Administrator\Helper;

use Exception;
use Joomla\CMS\Factory;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;

use Finnern\Component\Lang4dev\Administrator\Helper\langFile;
use RuntimeException;

use function defined;

// no direct access
defined('_JEXEC') or die;

/**
 * Collect file information and contents of all translation files
 * for one base folder (existing)
 * Write the changes set inlcuding ToDo: complete sentence
 * The files uses is limitet as *.ini are not useful
 *
 * @package Lang4dev
 */
class langFiles extends langFileNamesSet
{
//	public $requiredLangIds = [];

    /** @var array<langfile> */
    protected $langFilesData = []; // [$langId] -> translations from langFiles read and file names

    /**
     * matchTranslationsFile2Locations
     *
     * Joke: sort like Cinderella put the good ones in the pot, the bad ones in the crop
     *
     * Each input name is sorted into one of three types
     *  * missing:    transId item is not defined in lang file
     *  * translated: transId item has a translation item matching in lang file
     *  * notUsed:    not used translations in lang file
     *
     * @param $codeTransIds
     *
     *
     * @since version
     */
    //public function separateFilesByTransIds ($codeTransIds)
    /**
     * @param $codeTransIds
     * @param $langId
     *
     * @return array
     *
     * @throws Exception
     * @since version
     */
    public function matchTranslationsFile2Locations($codeTransIds, $langId = "en-GB")
    {
        $missing    = [];
        $translated = [];
        $notUsed    = [];

        $translations = [];

        try {
            //--- collect translations of all files in sub project -----------------

            if (!empty ($this->langFilesData [$langId])) {
                foreach ($this->langFilesData[$langId] as $langFileData) {
                    // array_push($translations,  array_values($langFileData->translations));
                    $translations = $translations + $langFileData->translations;
                }

                [$missing, $translated, $notUsed] =
                    $this->matchTranslations2Locations($codeTransIds, $translations);
            }
        } catch (RuntimeException $e) {
            $OutTxt = 'Error executing ' . __CLASS__ . '::' . __FUNCTION__ . ': "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return [$missing, $translated, $notUsed];
    }

    /**
     * seperateByTransIds
     *
     * Joke: sort like Cinderella put the good ones in the pot, the bad ones in the crop
     *
     * Each input name is sorted into one of three types
     *  * missing:    transId item is not defined in lang file
     *  * translated: transId item has a translation item matching in lang file
     *  * notUsed:    not used translations in lang file
     *
     * @param $codeTransIds
     *
     *
     * @since version
     */
    //public function separateByTransIds ($codeTransIds , $translations) {
    /**
     * @param $codeTransIds
     * @param $translations
     *
     * @return array[]
     *
     * @throws Exception
     * @since version
     */
    public function matchTranslations2Locations($codeTransIds, $translations)
    {
        $missing    = [];
        $translated = [];
        $notUsed    = [];

        try {
            //--- missing and translated ------------------------------------

            foreach ($codeTransIds as $codeTransId) {
                // ID is missing
                if (empty ($translations[$codeTransId])) {
                    $missing[] = $codeTransId;
                } else {
                    // ID is translated
                    $translated[] = $codeTransId;
                }
            }

            //--- not used in code ------------------------------------

            foreach ($this->getItemNames($translations) as $TransId) {
                // ID is not used
                if (!in_array($TransId, $translated)) {
                    $notUsed[] = $TransId;
                }
            }
        } catch (RuntimeException $e) {
            $OutTxt = 'Error executing ' . __CLASS__ . '::' . __FUNCTION__ . ': "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        // sort once
        sort($missing);
        sort($translated);
        sort($notUsed);

        return [$missing, $translated, $notUsed];
    }

    /**
     * @param $translations
     *
     * @return array
     *
     * @throws Exception
     * @since version
     */
    public function getItemNames($translations)
    {
        $names = [];

        try {
            // ToDo: cache it for second use, reset cache after assign /read
            foreach ($translations as $transId => $translation) {
                $names [] = $transId;
            }
        } catch (RuntimeException $e) {
            $OutTxt = 'Error executing getItemNames: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $names;
    }

    /**
     *
     * @return array lines
     *
     * @since version
     */
    public function __toText()
    {
        $lines = parent::__toText();

        $lines[] = '--- langFiles ---------------------------';

        foreach ($this->langFilesData as $langId => $langFiles) {
            $lines[] = '    [' . $langId . ']';

            foreach ($langFiles as $langFile) {
                // $lines[] = '        ' . $langFile;

                $langFileDataText = $langFile->__toText();
                array_push($lines, ...$langFileDataText);
            }
        }

//		$lines[] = '------------------------------------------------';

        return $lines;
    }

} // class