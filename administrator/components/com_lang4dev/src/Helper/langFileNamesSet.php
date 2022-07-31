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

use Finnern\Component\Lang4dev\Administrator\Helper\projectType;

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
    public $langFileNameSet = []; // [LangIds] [filename]

    protected $useLangSysIni = false;
    protected $isLangInFolders = false; // lang file are divided in folders instead of containing the name in front
    protected $isLangIdPre2Name = false; // ToDo: is this needed ?

    public function __construct($basePath = '') {
        $this->langBasePath = $basePath;
    }

    public function clear () {

        $this->langBasePath = '';
        $this->baseName = '';
        $this->langIds = [];
        $this->langFileNameSet = [];

        $this->useLangSysIni = false;
        $this->isLangInFolders = false; // lang file are divided in folders instead of containing the name i front
        $this->isLangIdPre2Name = false; // ToDo: is this needed ?

    }

    public function detectLangBasePath ($basePath = '', $useLangSysIni = false) {

    	if ($basePath == '') {

		    $basePath = $this->langBasePath;
	    } else {

		    $this->langBasePath = $basePath;
	    }

        if ($basePath == '' or $basePath == '/' or $basePath == '\\')
        {
            //--- path does not exist -------------------------------

            $OutTxt = 'Warning: langFileNamesSet.detectBasePath: Base path invalid "' . $basePath . '"<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'warning');

            return false;
        }

        if (!is_dir($basePath))
        {
            //--- path does not exist -------------------------------

            $OutTxt = 'Warning: langFileNamesSet.detectBasePath: Base path does not exist "' . $basePath . '"<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'warning');

            return false;
        }

	    $this->useLangSysIni = $useLangSysIni;
        $isPathFound = $this->searchDir4LangID ($basePath);


        // ToDo: may be done outside
        if ( ! $isPathFound)
        {

            //--- path does not exist -------------------------------

            $OutTxt = 'Warning: langFileNamesSet.searchDir4LangID: Base path for lang names not found behind path  "' . $basePath . '"<br>';

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
        if ($this->useLangSysIni) {
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

    // ToDo: detectBasePath does not need to know about useLangSysIni, do it here ? or tell by construct
    public function collectLangFiles () {

        $isFound = false;

        // try ?
        $isFound = $this->collectFolderLangFiles ();

        return $isFound;
    }

    protected function collectFolderLangFiles () {

    	$isBaseNameSet = false;

	    $this->langIds = [];
	    $baseName = '';

	    if ($this->useLangSysIni == true) {
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

	            $this->langIds []                  = $langId;
	            $this->langFileNameSet [$langId][] = $langFile;

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

			        if ($fileNames != false)
			        {
				        $baseName = $fileNames[0];
				        $this->baseName = $baseName;

				        $isBaseNameSet  = true;
			        }
		        }

		        $langFile = $subFolder . DIRECTORY_SEPARATOR . $baseName;

		        $this->langFileNameSet [$langId][] = $langFile;
	        }
        }

        return $isBaseNameSet;
    }

	public function collectManifestLangFiles($manifestLang, $prjType)
	{
		$isCheck4Ini = false;

		/*--- lang file origin defined in manifest file -----------------------*/

		// on backend use administrator files
		if (   $prjType == projectType::PRJ_TYPE_COMP_BACK
			|| $prjType == projectType::PRJ_TYPE_COMP_BACK_SYS)
		{
			$xmlLangNames = $manifestLang->adminLangFilePaths;
		} else {
			// On site, modul and plugin
			$xmlLangNames = $manifestLang->stdLangFilePaths;
		}

		$langBasePath = $this->langBasePathJoomla ($prjType) ;

		// Within joomla use standard paths
		if ($manifestLang->isInstalled)
		{

			if (count($xmlLangNames) > 0)
			{
				foreach ($xmlLangNames as $idx => $langFilePathInfo)
				{
					foreach ($langFilePathInfo as $langId => $langFilePath)
					{
						$isSysIni = str_ends_with($langFilePath, '.sys.ini');

						// backend system and *.sys.ini found
						if($this->useLangSysIni && $isSysIni) {

							$this->langFileNameSet [$langId][] = $langBasePath . '/' . $langFilePath;

						}

						// backend or site and no *.sys.ini file
						if(! $this->useLangSysIni && ! $isSysIni)
						{
							$this->langFileNameSet [$langId][] = $langBasePath . '/' . $langFilePath;
						}
					}
				}
			}

		}
		else
		{

			//--- on local development folder ------------------------------

			/**
			if (count($xmlLangNames) > 0)
			{
			foreach ($xmlLangNames as $idx => $langFilePathInfo)
			{
			foreach ($langFilePathInfo as $langId => $langFilePath)
			{
			$isSysIni = str_ends_with($langFilePath, '.sys.ini');

			// On backend PRJ_TYPE_COMP_BACK_SYS only sys.ini files used
			if ( ! $isCheck4Ini || $isSysIni)
			{
			$this->langFileNames [$langId] = $langBasePath . '/' . $langFilePath;
			}
			}
			}
			}

			/**/
		}



	}






	public function __toText () {

    	$lines = [];

	    $lines [] = '$basePath = "' . $this->langBasePath . '"';
	    $lines [] = '$baseName = "' . $this->baseName . '"';
	    $lines [] = '$useLangSysIni = "' . ($this->useLangSysIni  ? 'true' : 'false') . '"';
	    $lines [] = '$isLangInFolders = "' . ($this->isLangInFolders  ? 'true' : 'false') . '"';
	    $lines [] = '$isLangIdPre2Name = "' . ($this->isLangIdPre2Name  ? 'true' : 'false') . '"';

	    $lines [] = '--- $langIds ------------------------';
	    $langIdsLine = '';
	    foreach ($this->langIds as $langId) {
		    $langIdsLine .= $langId . ', ';
	    }
	    $lines [] = $langIdsLine;

	    $lines [] = '--- $sourceLangFiles ------------------------';
	    foreach ($this->langFileNameSet as $LangId => $langFiles)
	    {
		    $lines [] = '[' . $LangId . ']';

		    foreach ($langFiles as $langFile)
		    {
			    $lines [] = '   * ' . $langFile;
		    }
	    }

	    return $lines;
    }

	private function langBasePathJoomla($prjType)
	{
		// most used is admin backend
		$basePath = JPATH_ADMINISTRATOR . '/language';

		switch ($prjType){

			case projectType::PRJ_TYPE_NONE:
				break;

			case projectType::PRJ_TYPE_COMP_BACK_SYS:
				// admin
				break;

			case projectType::PRJ_TYPE_COMP_BACK:
				// admin
				break;

			case projectType::PRJ_TYPE_COMP_SITE:
				// site
				$basePath = JPATH_ROOT . '/language';
				break;

			case projectType::PRJ_TYPE_MODEL:
				// site
				$basePath = JPATH_ROOT . '/language';
				break;

			case projectType::PRJ_TYPE_PLUGIN:
				// admin
				break;

		}

		return $basePath;
	}

} // class
