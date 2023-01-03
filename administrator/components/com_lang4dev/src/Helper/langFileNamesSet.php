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
    public function detectLangBasePath($basePath = '', $useLangSysIni = false, $langId = 'en-GB')
    { 
        $isPathFound = false;
        if ($basePath == '') {
            $basePath = $this->langBasePath;
        } else {
            $this->langBasePath = $basePath;
        }

	    // path does not exist ? nothing given
	    if ($basePath == '' or $basePath == '/' or $basePath == '\\') {
            $OutTxt = 'Warning: langFileNamesSet.detectBasePath: Base path invalid "' . $basePath . '"<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'warning');

            return false;
        }

	    // given path does not exist
        if (!is_dir($basePath)) {

            $OutTxt = 'Warning: langFileNamesSet.detectBasePath: Base path does not exist "' . $basePath . '"<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'warning');

            return false;
        }

	    //--- search folders for language construct -----------------------------------------

	    $end = '.ini';
	    if ($this->useLangSysIni) {
		    $end = '.sys.ini';
	    }

	    $this->isLangInFolders  = false;
	    $this->useLangSysIni = $useLangSysIni;

	    $isPathFound = $this->search4LangIdFolder($basePath, $langId, $end);

        //
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
     * Lists with LangId + Path and Filename
     * @param $prjType
     * @param $manifestLang
     *
     * @return mixed
     *
     * @since version
     */
    public function manifestLangFilePaths($prjType, $manifestLang): array
    {
	    $xmlLangNames = [];

        /*--- lang file origin defined in manifest file -----------------------*/

        // on backend use administrator files
        if ($prjType == projectType::PRJ_TYPE_COMP_BACK
            || $prjType == projectType::PRJ_TYPE_COMP_BACK_SYS) {

            $LangFileNames = $manifestLang->adminLangFilePaths;

			// Select matching the type
	        if (count($LangFileNames) > 0) {
		        foreach ($LangFileNames as $idx => $langFilePathInfo) {
			        foreach ($langFilePathInfo as $langId => $langFilePath)
			        {
				        $isSysIni = str_ends_with($langFilePath, '.sys.ini');

				        // backend system ?
				        if ($prjType == projectType::PRJ_TYPE_COMP_BACK_SYS && $isSysIni)
				        {
					        $xmlLangNames [] = $langFilePathInfo;
				        }

				        // backend standard ?
				        if ($prjType == projectType::PRJ_TYPE_COMP_BACK && !$isSysIni)
				        {
					        $xmlLangNames [] = $langFilePathInfo;
				        }
			        }
				}
			}

        } else {
            // On site, module and plugin
            $xmlLangNames = $manifestLang->stdLangFilePaths;
        }

        return $xmlLangNames;
    }

    /**
     *
     * Lists with LangId + Filename
     * @param $prjType
     * @param $manifestLang
     *
     * @return mixed
     *
     * @since version
     */
    public function manifestLangFiles($prjType, $manifestLang): array
    {
	    $xmlLangNames = [];

        /*--- lang file origin defined in manifest file -----------------------*/

        // on backend use administrator files
        if ($prjType == projectType::PRJ_TYPE_COMP_BACK
            || $prjType == projectType::PRJ_TYPE_COMP_BACK_SYS) {

            $LangFileNames = $manifestLang->adminLangFilePaths;

			// divide langId and lang file name
	        if (count($LangFileNames) > 0)
	        {
		        foreach ($LangFileNames as $idx => $langFilePathInfo)
		        {
			        foreach ($langFilePathInfo as $langId => $langFilePath)
			        {
				        //--- create langId:lang file name assoc array ---------------------

				        $LangFileName               = basename($langFilePath);
				        $langFilePathInfo [$langId] = $LangFileName;

				        $isSysIni = str_ends_with($LangFileName, '.sys.ini');

				        //--- assign depending on type ---------------------

				        if ($prjType == projectType::PRJ_TYPE_COMP_BACK_SYS && $isSysIni)
				        {
					        $xmlLangNames [] = $langFilePathInfo;
				        }

				        // backend standard ?
				        if ($prjType == projectType::PRJ_TYPE_COMP_BACK && !$isSysIni)
				        {
					        $xmlLangNames [] = $langFilePathInfo;
				        }
			        }
		        }
	        }

        } else {
            // On site, module and plugin

	        $LangFileNames = $manifestLang->stdLangFilePaths;

	        // divide langId and lang file name
	        if (count($LangFileNames) > 0)
	        {
		        foreach ($LangFileNames as $idx => $langFilePathInfo)
		        {
			        foreach ($langFilePathInfo as $langId => $langFilePath)
			        {

				        //--- create langId:lang file name assoc array ---------------------

				        $LangFileName               = basename($langFilePath);
				        $langFilePathInfo [$langId] = $LangFileName;

				        $xmlLangNames[] = $langFilePathInfo;
			        }
		        }
	        }
        }

        return $xmlLangNames;
    }

	/**
	 * @param   string $searchPath
	 * @param   string $langId
	 * @param   string $end        '.sys.ini' or '.ini'
	 *
	 * @return bool                 the type of lang file name (pre 'en-GB...ini' or 'com_...ini' files)
	 *
	 * @since version
	 */
	public function check4LangIdPreName(string $searchPath, string $langId, string $end): bool
	{
		$isLangIdPre2Name = false;

		#--- All files (en-GB. ... .ini) in folder -------------------------------------

		$this->isLangIdPre2Name = false;

		#--- search path starts with lang ID ? -----------------------------

		// all files
		foreach (Folder::files($searchPath) as $fileName)
		{
			if (str_starts_with($fileName, $langId))
			{
				if (str_ends_with($fileName, $end))
				{
					//--- flags --------------------------------

					$this->isLangIdPre2Name = true;
					$isLangIdPre2Name            = true;

					break;
				}
			}
		}

		return $isLangIdPre2Name;
	}

	public function check4LangIdFolderName($searchPath, $langId, string $end): bool
	{
		$isPathFound = false;

		#--- Search Lang ID as sub folders in -------------------------------------

		foreach (Folder::folders($searchPath) as $folderName)
		{
			// base found ?
			if ($folderName == $langId)
			{

				$this->isLangInFolders = true;
				$this->langBasePath    = $searchPath;

				$isPathFound = true;

				break;
			}
		}

		return $isPathFound;
	}

	/**
	 * Determines langBasePath and type of lang file name (pre 'en-GB...ini' or 'com_...ini' files)
	 *  1) pre file found in search folder -> path = search path
	 *  2) path name matches lang ID -> path = search path
	 *  3) continue in subfolders if path not found
	 * On path found remember the type of lang file name (pre 'en-GB...ini' or 'com_...ini' files)
	 *
	 * @param   string $searchPath
	 * @param   string $langId
	 * @param   string $end        '.sys.ini' or '.ini'
	 *
	 * @return bool                is langBasePath found ?
	 *
	 * @since version
	 */
	public function search4LangIdFolder(string $searchPath, string $langId, string $end): bool
	{
		$isPathFound = false;

		#--- Search in given folder for 'pre en-GB.com...' files -------------------------------------

		// determine type of lang file "en-GB.com_...ini
		$isLangIdPre2Name = $this->check4LangIdPreName($searchPath, $langId, $end);

		// found
		if ($isLangIdPre2Name) {

			$this->isLangInFolders = false;
			$this->langBasePath = $searchPath;

			$isPathFound        = true;
		}

		if (!$isPathFound)
		{
			#--- Search Lang ID as sub folders -------------------------------------

			// -> $this->langBasePath
			$isPathFound = $this->check4LangIdFolderName($searchPath, $langId, $end);

			if ($isPathFound) {

				#--- Search in found folder for 'pre en-GB' files -----------------------------------

				$subFolder = $this->langBasePath . "/" . $langId;
				$isLangIdPre2Name = $this->check4LangIdPreName($subFolder, $langId, $end);
			}

		}

		#--- Search in each sub folder -------------------------------------

		if (!$isPathFound)
		{
			foreach (Folder::folders($searchPath) as $folderName)
			{
				$subFolder = $searchPath . "/" . $folderName;

				// search in sub folder
				$isPathFound = $this->search4LangIdFolder($subFolder, $langId, $end);

				if ($isPathFound)
				{
					break;
				}
			}
		}

		return $isPathFound;
	}

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

	    //--- lang ID as folder name --------------------------------

	    if ($this->isLangInFolders) {

	        if (is_dir($this->langBasePath)) {
		        // all folders are language IDs
		        foreach (Folder::folders($this->langBasePath) as $langId) {

			        // append new lang ID
			        $this->langIds [] = $langId;

			        $subFolder = $this->langBasePath . '/' . $langId;

			        // all matching file names
			        $fileNames = Folder::files($subFolder, $regex);
			        foreach ($fileNames as $fileName) {

				        $langFile = $subFolder . DIRECTORY_SEPARATOR . $fileName;

				        // append found lang file
				        $this->langFileNamesSet [$langId][] = $langFile;

				        $isFound = true;
			        }
		        }
	        }
        } else {
	        //--- lang in one folder ----------------------------------------

		    // ToDo: extract only files with extension name when in standard folder ?

	        $langFiles = Folder::files($this->langBasePath, $regex);

	        // all files in dir
	        foreach ($langFiles as $langFile) {
		        [$langId, $baseName] = explode('.', $langFile, 2);

		        // append new lang ID
		        if (!in_array($langId, $this->langIds)) {
			        $this->langIds [] = $langId;
		        }

				// append found lang file
		        $this->langFileNamesSet [$langId][] = $this->langBasePath . '/' . $langFile;

		        $isFound = true;
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
    public function collectManifestLangFiles_OnJoomla($manifestLang, $prjType, $langBasePath)
    {
        $isLangPathDefined = false;
        $this->langIds = [];

        $xmlLangNames = $this->manifestLangFiles($prjType, $manifestLang);

	    //--- transfer to lang files list ------------------------------

	    if (count($xmlLangNames) > 0) {
			// all items
		    foreach ($xmlLangNames as $idx => $langFilePathInfo) {
				// into lang Id  / path
			    foreach ($langFilePathInfo as $langId => $langFilePath) {

				    // append found lang file
				    $this->langFileNamesSet [$langId][] = $langBasePath . '/' . $langId . '/' . $langFilePath;

				    // append new lang ID
				    if (!in_array($langId, $this->langIds)) {
					    $this->langIds [] = $langId;
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
    public function collectManifestLangFiles_OnDevelop($manifestLang, $prjType, $langBasePath)
    {
	    $isLangPathDefined = false;
        $isCheck4Ini = false;

        $xmlLangNames = $this->manifestLangFilePaths($prjType, $manifestLang);

        //--- transfer to lang files list ------------------------------

        if (count($xmlLangNames) > 0) {
	        // all items
            foreach ($xmlLangNames as $idx => $langFilePathInfo) {
	            // into lang Id  / path
                foreach ($langFilePathInfo as $langId => $langFilePath) {

	                // append found lang file
					$this->langFileNamesSet [$langId][] = $langBasePath . '/' . $langFilePath;

	                // append new lang ID
	                if (!in_array($langId, $this->langIds)) {
		                $this->langIds [] = $langId;
	                }
                }
            }

	        $isLangPathDefined = true;
        }

		return $isLangPathDefined;
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

	                                // append found lang file
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
    public function langBasePathJoomlaStd($prjType)
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
    public function langBasePathInsideProject(string $prjXmlFilePath='', int $prjType=projectType::PRJ_TYPE_NONE)
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
