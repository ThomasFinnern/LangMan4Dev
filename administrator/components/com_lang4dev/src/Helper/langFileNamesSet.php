<?php
/**
 * This class contains translations files names with base path
 *
 *
 * @version
 * @package       Lang4dev
 * @copyright (C) 2022-2022 Lang4dev Team
 * @license
 */


namespace Finnern\Component\Lang4dev\Administrator\Helper;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\Folder;

//use Finnern\Component\Lang4dev\Administrator\Helper\langFiles;

// no direct access
\defined('_JEXEC') or die;

/**
 *
 *

 * The files uses is limitet as *.ini are not useful
 *
 * @package Lang4dev
 */
class langFileNamesSet
{
    public $basePath = '';
    public $baseName = '';
    public $langIds = '';

    protected $isSysFiles = false;
    protected $isLangInFolders = false; // lang file are divided in folders instead of containing the name i front
    protected $isLangIdPre2Name = false; // ToDo: is this needed ?

    public function __construct($basePath = '') {
        $this->basePath = $basePath;
    }

    public function detectBasePath ($basePath = '') {

        if (!is_dir($basePath))
        {

            //--- path does not exist -------------------------------

            $OutTxt = 'Warning: langFileNamesSet.detectBasePath: Base path does not exist "' . $basePath . '"<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'warning');

            return;
        }

        $isPathFound = $this->searchDir4LangID ($basePath);


        // ToDo: may be done outside
        if ( ! $isPathFound)
        {

            //--- path does not exist -------------------------------

            $OutTxt = 'Warning: langFileNamesSet.detectBasePath: Base path not found within  "' . $basePath . '"<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'warning');

        }

        return $isPathFound;
    }

    /*
        - language ID used in front of file:
            a) if file name is found in folder there is no 'en-GB' in containing folder
               ==> All files will contain lang ID in front and are kept in same folder
            b) Lang Id may be found on file name in subfolder of Lang ID folder.
               ==> has actual no influence so is ignored
    */


    protected function searchDir4LangID ($searchPath, $langId='en-GB') {

        $isPathFound = false;

        #--- All files (en-GB. ... .ini) in folder -------------------------------------

        foreach (Folder::files($searchPath) as $fileName)
        {
            if (str_starts_with($fileName, $langId)) {

                $isPathFound = true;
                $this->basePath = $searchPath;

                //--- flags --------------------------------

                $this->isLangInFolders = false;
                $this->isLangIdPre2Name = true;

            }
        }

        if ( ! $isPathFound) {

            #--- All sub folders in folder -------------------------------------

            foreach (Folder::folders($searchPath) as $folderName) {
                $subFolder = $searchPath . DIRECTORY_SEPARATOR . $folderName;

                if (str_starts_with($subFolder, $langId)) {

                    $isPathFound = true;
                    $this->basePath = $searchPath;

                    //--- flags --------------------------------

                    $this->isLangInFolders = true;
                    $this->isLangIdPre2Name = false;

                } else
                {
                    $isPathFound = $this->searchDir4LangID($subFolder);
                }
            }
        }

        return $isPathFound;
    }

    public function collectLangFiles ($isSysFiles = false) {

        $isFound = false;

        if ($isSysFiles == false) {
            $this->isSysFiles = false;

            $isFound = searchSysLangFiles ('.ini');

        } else {
            $this->isSysFiles = true;

            $isFound = searchSysLangFiles ('.sys.ini');
        }

        return $isFound;
    }

    protected function searchSysLangFiles ($ending ='.ini') {

        // lang ID in front
        if ($isLangIdPre2Name) {
            $files =

        } else {






        }




    }



} // class