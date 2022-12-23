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

use Exception;
use Finnern\Component\Lang4dev\Administrator\Helper\projectType;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\Folder;

use RuntimeException;

use function defined;

// no direct access
defined('_JEXEC') or die;

/**
 *
 *
 *
 * @package Lang4dev
 */
class langFileNamesSet
{
    /** @var string */
    public $langBasePath = '';
//	public $baseName = '';
    /** @var array[string] */
    public $langIds = [];
    /** @var array[string] string */
    public $langFileNamesSet = []; // [LangIds] [filename]

    /** @var bool */
    public $useLangSysIni = false;
    /** @var bool */
    protected $isLangInFolders = false; // lang file are divided in folders instead of containing the name in front
    /** @var bool */
    protected $isLangIdPre2Name = false; // ToDo: is this needed ?

    /**
     * @param $basePath
     */
    public function __construct($basePath = '')
    {
        $this->langBasePath = $basePath;
    }

    /**
     *
     *
     * @since version
     */
    public function clear()
    {
        $this->langBasePath = '';
        //$this->baseName         = '';
        $this->langIds          = [];
        $this->langFileNamesSet = [];

        $this->useLangSysIni    = false;
        $this->isLangInFolders  = false; // lang file are divided in folders instead of containing the name i front
        $this->isLangIdPre2Name = false; // ToDo: is this needed ?

    }

    /**
     * @param $basePath
     * @param $useLangSysIni
     *
     * @return bool
     *
     * @throws Exception
     * @since version
     */
    public function detectLangBasePath($basePath = '', $useLangSysIni = false)
    { 
        $isPathFound = false;
        if ($basePath == '') {
            $basePath = $this->langBasePath;
        } else {
            $this->langBasePath = $basePath;
        }

        if ($basePath == '' or $basePath == '/' or $basePath == '\\') {
            //--- path does not exist -------------------------------

            $OutTxt = 'Warning: langFileNamesSet.detectBasePath: Base path invalid "' . $basePath . '"<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'warning');

            return false;
        }

        if (!is_dir($basePath)) {
            //--- path does not exist -------------------------------

            $OutTxt = 'Warning: langFileNamesSet.detectBasePath: Base path does not exist "' . $basePath . '"<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'warning');

            return false;
        }

        $this->useLangSysIni = $useLangSysIni;
        $isPathFound         = $this->searchDir4LangID($basePath);

        // ToDo: may be done outside
        if (!$isPathFound) {
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
            b) Lang id may be found on file name in sub folder of Lang ID folder.
               ==> has actual no influence so is ignored
    */

    /**
     * @param $searchPath
     * @param $langId
     *
     * @return bool
     *
     * @since version
     */
	 // ToDo: rename
    protected function searchDir4LangID($searchPath, $langId = 'en-GB')
    {
        $isPathFound = false;

        $end = '.ini';
        if ($this->useLangSysIni) {
            $end = '.sys.ini';
        }

        $this->isLangInFolders  = false;

		// Files with Pre lang id found ? then path is ak
	    $isPathFound = $this->check4LangIdPreName($searchPath, $langId, $end, $isPathFound);

	    if (!$isPathFound) {

			// check4LangIdInFolderNames
		    $isPathFound = $this->check4LangIdInFolderNames($searchPath, $langId, $end, $isPathFound);
	    }

        return $isPathFound;
    }

    /**
     * @param $prjType
     * @param $manifestLang
     *
     * @return mixed
     *
     * @since version
     */
    public function manifestLangFilePaths($prjType, $manifestLang): mixed
    {
        /*--- lang file origin defined in manifest file -----------------------*/

        // on backend use administrator files
        if ($prjType == projectType::PRJ_TYPE_COMP_BACK
            || $prjType == projectType::PRJ_TYPE_COMP_BACK_SYS) {
            $xmlLangNames = $manifestLang->adminLangFilePaths;
        } else {
            // On site, module and plugin
            $xmlLangNames = $manifestLang->stdLangFilePaths;
        }

        return $xmlLangNames;
    }

	/**
	 * @param           $searchPath
	 * @param           $langId
	 * @param   string  $end
	 * @param   bool    $isPathFound
	 *
	 * @return bool
	 *
	 * @since version
	 */
	public function check4LangIdPreName($searchPath, $langId, string $end, bool $isPathFound): bool
	{
		$isPathFound = false;

		#--- All files (en-GB. ... .ini) in folder -------------------------------------

		$this->isLangIdPre2Name = false;

		foreach (Folder::files($searchPath) as $fileName)
		{
			if (str_starts_with($fileName, $langId))
			{
				if (str_ends_with($fileName, $end))
				{

					$this->langBasePath = $searchPath;

					//--- flags --------------------------------

					$this->isLangIdPre2Name = true;
					$isPathFound            = true;

					break;
				}
			}
		}

		return $isPathFound;
	}

	/**
	 * @param         $searchPath
	 * @param         $langId
	 * @param   bool  $isPathFound
	 *
	 * @return bool
	 *
	 * @since version
	 */
	public function check4LangIdInFolderNames($searchPath, $langId, string $end, bool $isPathFound): bool
	{
		$isPathFound = false;

		#--- Search Lang ID as sub folders in -------------------------------------

		foreach (Folder::folders($searchPath) as $folderName)
		{
			// base found ?
			if ($folderName == $langId)
			{

				$isPathFound        = true;

				$this->isLangInFolders = true;
				$this->langBasePath = $searchPath;

				//--- flags --------------------------------

				$subFolder = $searchPath . "/" . $folderName;
				$this->check4LangIdPreName($subFolder, $langId, $end, $isPathFound);

				break;
			}

		}

		#--- Search in each sub folder -------------------------------------

		if (!$isPathFound)
		{
			foreach (Folder::folders($searchPath) as $folderName)
			{
				$subFolder = $searchPath . "/" . $folderName;

				// search in sub folder
				$isPathFound = $this->searchDir4LangID($subFolder);

				if ($isPathFound)
				{
					break;
				}
			}
		}


		return $isPathFound;
	}

	/**
     * // ToDo: detectBasePath does not need to know about useLangSysIni, do it here ? or tell by construct
     * public function collectLangFiles () {
     *
     * $isFound = false;
     *
     * // try ?
     * $isFound = $this->collectFolderLangFiles ();
     *
     * return $isFound;
     * }
     * /**/

    protected function collectPrjFolderLangFiles()
    {
        $isFound = false;

        $this->langIds = [];

        if ($this->useLangSysIni == true) {
            $regex = '\.sys\.ini$';
        } else {
            // ToDo: regex with check for not .sys. before search string
            $regex = '(?<!\.sys)\.ini$';
        }

        //--- lang ID in front ----------------------------------------

        // ToDo: lang name may be in sub folders ?
        if ($this->isLangIdPre2Name) {
            $langFiles = Folder::files($this->langBasePath, $regex);

            // all files in dir
            foreach ($langFiles as $langFile) {
                [$langId, $baseName] = explode('.', $langFile, 2);

                $this->langIds []                   = $langId;
                $this->langFileNamesSet [$langId][] = $this->langBasePath . '/' . $langFile;

                $isFound = true;
            }
        } else {
            //--- lang ID as folder name --------------------------------

            if (is_dir($this->langBasePath)) {
                // all folders
                foreach (Folder::folders($this->langBasePath) as $folderName) {
                    $langId           = $folderName;
                    $this->langIds [] = $langId;

                    $subFolder = $this->langBasePath . '/' . $folderName;

                    // all matching file names
                    $fileNames = Folder::files($subFolder, $regex);
                    foreach ($fileNames as $fileName) {

                        $langFile = $subFolder . DIRECTORY_SEPARATOR . $fileName;

                        $this->langFileNamesSet [$langId][] = $langFile;

                        $isFound = true;
                    }
                }
            }
        }

        return $isFound;
    }

    // restrict to sub prj type

    /**
     * Extract the names and folders from XML definition
     *
     * @param $manifestLang
     * @param $prjType
     *
     *
     * @since version
     */
    public function collectManifestLangFiles_OnJoomla($manifestLang, $prjType)
    {
        $isLangPathDefined = false;
        $this->langIds = [];

        $xmlLangNames = $this->manifestLangFilePaths($prjType, $manifestLang);

        $langBasePath = $this->langBasePathJoomla($prjType);

        // Within joomla use standard paths
        $this->langBasePath = $this->langBasePathJoomla($prjType);

        if (count($xmlLangNames) > 0) {
            foreach ($xmlLangNames as $idx => $langFilePathInfo) {
                foreach ($langFilePathInfo as $langId => $langFilePath) {
                    $isSysIni = str_ends_with($langFilePath, '.sys.ini');

                    // backend system and *.sys.ini found
                    if ($this->useLangSysIni && $isSysIni) {
                        $this->langFileNamesSet [$langId][] = $langBasePath . '/' . $langFilePath;

                        // append new lang ID
                        if (!in_array($langId, $this->langIds)) {
                            $this->langIds [] = $langId;
                        }
                    }

                    // backend or site and no *.sys.ini file
                    if (!$this->useLangSysIni && !$isSysIni) {
                        $this->langFileNamesSet [$langId][] = $langBasePath . '/' . $langFilePath;

                        // append new lang ID
                        if (!in_array($langId, $this->langIds)) {
                            $this->langIds [] = $langId;
                        }
                    }
                }
            }

            $isLangPathDefined = count ($this->langIds) > 0;
        }

        return $isLangPathDefined;
    }

    /**
     * Extract the names and folders from XML definition
     *
     * @param $manifestLang
     * @param $prjType
     *
     *
     * @since version
     */
    public function collectManifestLangFiles_OnDevelop($manifestLang, $prjType)
    {
        $isCheck4Ini = false;

        $xmlLangNames = $this->manifestLangFilePaths($prjType, $manifestLang);

        $langBasePath = $this->langBasePathJoomla($prjType);

        //--- on local development folder and joomla standard paths------------------------------

//			$this->langBasePath =  $this->langBasePathJoomla ($prjType);

        /**/
        if (count($xmlLangNames) > 0) {
            foreach ($xmlLangNames as $idx => $langFilePathInfo) {
                foreach ($langFilePathInfo as $langId => $langFilePath) {
                    $isSysIni = str_ends_with($langFilePath, '.sys.ini');

                    // On backend PRJ_TYPE_COMP_BACK_SYS only sys.ini files used
                    if (!$isCheck4Ini || $isSysIni) {
                        $this->langFileNames [$langId] = $langBasePath . '/' . $langFilePath;
                    }
                }
            }
        }
        /**/
    }

    /**
     *
     *
     * @throws Exception
     * @since version
     */
    public function extendManifestLangFilesList()
    {
        try {
            if (count($this->langIds) > 0) {
                //--- Select basis language / files to match others ----------------------

                $firstLangId = $this->langIds[0];
                $langFiles   = $this->langFileNamesSet [$firstLangId];

                if (count($langFiles) > 0) {
                    //--- basis folder -------------------------------------------------------

                    $firstLangFile  = $langFiles [0];
                    $langBaseFolder = dirname($firstLangFile, 2);

                    //--- all lang IDs (en-GB ...) in folder -------------------------

                    $folderLangIds = langPathFileName::allLangIds_FromSubFolderNames($langBaseFolder);
                    foreach ($folderLangIds as $folderLangId) {
                        //--- all not detected lang IDs -----------------------------------

                        if (!in_array($folderLangId, $this->langIds) && $folderLangId != 'overrides') {
                            // check for existence of matching lang ID file
                            foreach ($langFiles as $baseLangFile) {
                                //--- create matching name with actual lang ID -------------------

                                $matchLangFile = new langPathFileName ($baseLangFile);

                                // exchange lang ID in path and pre name
                                $matchLangFile->setlangID($folderLangId);

                                $matchLangFilePathName = $matchLangFile->getlangPathFileName();

                                if (file_exists($matchLangFilePathName)) {
                                    // first match ?
                                    if (!in_array($folderLangId, $this->langIds)) {
                                        $this->langIds [] = $folderLangId;
                                    }

                                    $this->langFileNamesSet [$folderLangId][] = $matchLangFilePathName;
                                }
                            }
                        }
                    }
                }
            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing extendManifestLangFilesList: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }
    }


    /**
     * Extract the lang folder from XML definition by type
     *
     * @param $manifestLang
     * @param $prjType
     *
     *
     * @since version
     */
    public function YYYcollectManifestLangFolder_OnDevelop($manifestLang, $basePath = '', $prjType)
    {



    }


    // search for matching filename

    /**
     * @param $mainLangId
     * @param $mainLangFileName
     * @param $transLangId
     *
     * @return mixed|string
     *
     * @since version
     */
    public function matchingNameByTransId($mainLangId, $mainLangFileName, $transLangId)
    {
        // create empty lang file with just a filename
        $langFile = new langfile(); // empty lang file
        $langFile->setLangPathFileName($mainLangFileName);

        // Exchange lang ID with source lang ID
        $langFile->replaceLangId($transLangId);

        $matchLangFileName = $langFile->getLangPathFileName();

        return $matchLangFileName;
    }

    /**
     * @param $prjType
     *
     * @return string
     *
     * @since version
     */
    public function langBasePathJoomla($prjType)
    {
        // most used is admin backend
        $basePath = JPATH_ADMINISTRATOR . '/language';

        switch ($prjType) {
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

    /**
     * @param $prjType
     *
     * @return string
     *
     * @since version
     */
    public function langBasePathProject(string $prjXmlFilePath='', int $prjType=projectType::PRJ_TYPE_NONE)
    {
        // most used is xml path
        $basePath = $prjXmlFilePath . '/language';

        if ($prjType == projectType::PRJ_TYPE_COMP_SITE) {
            //$basePath = str_replace($basePath, '/administrator', '');
            $basePath = str_replace('/administrator', '', $basePath);
        }

//        switch ($prjType) {
//            case projectType::PRJ_TYPE_COMP_SITE:
//                // site
//                $basePath = str_replace ($basePath, '/administrator', '');
//                break;
//
//            case projectType::PRJ_TYPE_NONE:
//                break;
//
//            case projectType::PRJ_TYPE_COMP_BACK_SYS:
//                break;
//
//            case projectType::PRJ_TYPE_COMP_BACK:
//                break;
//
//            case projectType::PRJ_TYPE_MODEL:
//                break;
//
//            case projectType::PRJ_TYPE_PLUGIN:
//                break;
//        }

        return $basePath;
    }

    /**
     *
     * @return array
     *
     * @since version
     */
    public function __toText()
    {
        $lines = [];

        $lines[] = '--- langFileNamesSet ---------------------------';

        $lines [] = 'langBasePath = "' . $this->langBasePath . '"';
        //$lines [] = '$baseName = "' . $this->baseName . '"';
        $lines [] = 'useLangSysIni = "' . ($this->useLangSysIni ? 'true' : 'false') . '"';
        $lines [] = 'isLangInFolders = "' . ($this->isLangInFolders ? 'true' : 'false') . '"';
        $lines [] = 'isLangIdPre2Name = "' . ($this->isLangIdPre2Name ? 'true' : 'false') . '"';

        $lines []    = '--- $langIds ------------------------';
        $langIdsLine = '';
        foreach ($this->langIds as $langId) {
            $langIdsLine .= $langId . ', ';
        }
        $lines [] = $langIdsLine;

        $lines [] = '--- $sourceLangFiles ------------------------';
        foreach ($this->langFileNamesSet as $langId => $langFiles) {
            $lines [] = '[' . $langId . ']';

            foreach ($langFiles as $langFile) {
                $lines [] = '   * ' . $langFile;
            }
        }

        return $lines;
    }

} // class
