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
 *
 * @package Lang4dev
 */
class langFileNamesSet
{
    public $langBasePath = '';
    public $baseName = '';
    public $langIds = [];
    public $langFileNames = [];

    protected $isSysFiles = false;
    protected $isLangInFolders = false; // lang file are divided in folders instead of containing the name i front
    protected $isLangIdPre2Name = false; // ToDo: is this needed ?

    public function __construct($basePath = '') {
        $this->langBasePath = $basePath;
    }

    public function clear () {

        $this->langBasePath = '';
        $this->baseName = '';
        $this->langIds = [];
        $this->langFileNames = [];

        $this->isSysFiles = false;
        $this->isLangInFolders = false; // lang file are divided in folders instead of containing the name i front
        $this->isLangIdPre2Name = false; // ToDo: is this needed ?

    }

    public function detectLangBasePath ($basePath = '', $isSysFiles = false) {

    	if ($basePath == '') {

		    $basePath = $this->$basePath;
	    } else {

		    $this->$basePath = $basePath;
	    }

        if (!is_dir($basePath))
        {

            //--- path does not exist -------------------------------

            $OutTxt = 'Warning: langFileNamesSet.detectBasePath: Base path does not exist "' . $basePath . '"<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'warning');

            return;
        }

	    $this->isSysFiles = $isSysFiles;
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

        $end = '.ini';
        if ($this->isSysFiles) {
	        $end = '.sys.ini';
        }

        #--- All files (en-GB. ... .ini) in folder -------------------------------------

        foreach (Folder::files($searchPath) as $fileName)
        {
            if (str_starts_with($fileName, $langId)) {

	            if (str_ends_with($fileName, $end))
	            {
		            $isPathFound    = true;
		            $this->langBasePath = $searchPath;

		            //--- flags --------------------------------

		            $this->isLangInFolders  = false;
		            $this->isLangIdPre2Name = true;

		            break;
	            }
            }
        }

        if ( ! $isPathFound) {

            #--- All sub folders in folder -------------------------------------

            foreach (Folder::folders($searchPath) as $folderName) {

                $subFolder = $searchPath . DIRECTORY_SEPARATOR . $folderName;

                if (str_starts_with($folderName, $langId)) {

                    $isPathFound = true;
                    $this->langBasePath = $searchPath;

                    //--- flags --------------------------------

                    $this->isLangInFolders = true;
                    $this->isLangIdPre2Name = false;

                } else
                {
                    $isPathFound = $this->searchDir4LangID($subFolder);

                    if($isPathFound) {
                    	break;
                    }
                }
            }
        }

        return $isPathFound;
    }

    // ToDo: detectBasePath does not need to know about isSysFiles, do it here ? or tell by construct
    public function collectLangFiles () {

        $isFound = false;

        // try ?
        $isFound = $this->searchLangFiles ();

        return $isFound;
    }

    protected function searchLangFiles () {

    	$isBaseNameSet = false;
	    $this->langIds = [];
	    $baseName = '';

	    if ($this->isSysFiles == true) {
		    $regex = '\.sys\.ini$';
	    } else {
	    	// ToDO: regex with check for not .sys. before search string
		    $regex = '(?<!\.sys)\.ini$';
	    }

        //--- lang ID in front ----------------------------------------

        if ($this->isLangIdPre2Name) {

            $langFiles = Folder::files ($this->langBasePath, $regex);

            // all files in dir
            foreach ($langFiles as $langFile) {

	            [$langId, $baseName] = explode('.', $langFile, 2);

	            $this->langIds [] = $langId;
	            $this->langFileNames [$langId] = $langFile;

	            // set base name once
	            if($isBaseNameSet == false)
	            {
		            $this->baseName = $baseName;
		            $isBaseNameSet  = true;
	            }

            }

        } else {

	        //--- lang ID as folder name --------------------------------

	        // all folders
	        foreach (Folder::folders($this->langBasePath) as $folderName)
	        {
		        $langId = $folderName;
		        $this->langIds []          = $langId;

		        $subFolder = $this->langBasePath . DIRECTORY_SEPARATOR . $folderName;

		        // set base name once
		        if ($isBaseNameSet == false)
		        {
			        $fileNames = Folder::files ($subFolder, $regex);

			        if (count ($fileNames) > 0)
			        {
				        $baseName = $fileNames[0];
				        $this->baseName = $baseName;

				        $isBaseNameSet  = true;
			        }
		        }

		        $langFile = $subFolder . DIRECTORY_SEPARATOR . $baseName;

		        $this->langFileNames [$langId] = $langFile;
	        }
        }

        return $isBaseNameSet;
    }

    public function __toText () {

    	$lines = [];

	    $lines [] = '$basePath = "' . $this->langBasePath . '"';
	    $lines [] = '$baseName = "' . $this->baseName . '"';
	    $lines [] = '$isSysFiles = "' . ($this->isSysFiles  ? 'true' : 'false') . '"';
	    $lines [] = '$isLangInFolders = "' . ($this->isLangInFolders  ? 'true' : 'false') . '"';
	    $lines [] = '$isLangIdPre2Name = "' . ($this->isLangIdPre2Name  ? 'true' : 'false') . '"';

	    $lines [] = '--- $langIds ------------------------';
	    $langIdsLine = '';
	    foreach ($this->langIds as $langId) {
		    $langIdsLine .= $langId . ', ';
	    }
	    $lines [] = $langIdsLine;

	    $lines [] = '--- $langFiles ------------------------';
	    foreach ($this->langFileNames as $langFile) {
		    $lines [] = $langFile;
	    }

	    return $lines;
    }


} // class
