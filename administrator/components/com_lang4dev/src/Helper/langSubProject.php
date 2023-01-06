<?php
/**
 * @version
 * @package       Lang4dev
 * @copyright (C) 2022-2022 Lang4dev Team
 * @license
 */

namespace Finnern\Component\Lang4dev\Administrator\Helper;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Language\Text;
use RuntimeException;

//use Finnern\Component\Lang4dev\Administrator\Helper\sysFilesContent;
//use Finnern\Component\Lang4dev\Administrator\Helper\searchTransIdLocations;

class langSubProject extends langFiles
{
    /** @var int */
    public $prjId = '';
    /** @var int */
    public $prjType = 0;

    // ToDo: Separate std path (plugin/module -> ), admin path, site path

    /** @var string */
    public $prjRootPath = '';
    /** @var string */
    public $prjXmlFilePath = '';
    /** @var string */
    public $langIdPrefix = '';

    // is also admin
    /** @var string */
    public $prjDefaultPath = '';
    /** @var string */
    public $prjAdminPath = '';

    /** @var string */
    public $prjXmlPathFilename = '';
    /** @var string */
    public $installPathFilename = '';
    /** @var string */
    public $configPathFilename = '';

    // external
    // public $parentId = 0;
    // public $twinId = '';

    // !!! ToDo: text_prefix !!!
    // public $text_prefix;

    // public $useLangSysIni = false;
    /** @var bool */
    public $isLangAtStdJoomla = false;
    /** @var bool */
    public $isManifestRead = false;

    public $isLangPathDefined = false;

    /** @var array[string]array[transIdLocation] */
    protected $transIdLocations = [];
    /** @var array[string]array[transIdLocation] */
    protected $transIdsClassified;
    // ToDo: ** @var array[string]array[transIdLocation] */
    protected $transStringsLocations = [];

    /**
     * @param $prjId
     * @param $prjType
     * @param $prjRootPath
     * @param $prjXmlPathFilename
     */
    public function __construct(
        $prjId = '',
        $prjType = projectType::PRJ_TYPE_NONE,
        $prjRootPath = '',
        $prjXmlPathFilename = ''
    ) {
        parent::__construct();

        $this->prjType            = $prjType;
        $this->prjId              = $prjId;
        $this->prjRootPath        = $prjRootPath;
        $this->prjXmlPathFilename = $prjXmlPathFilename;
        $this->prjXmlFilePath     = dirname($prjXmlPathFilename);

        // Admin path

        if ($this->prjType == projectType::PRJ_TYPE_COMP_BACK_SYS) {
            $this->useLangSysIni = true;
        }

        //--- lang paths, project XML and script file -------------------------------------------------

        $this->isManifestRead = $this->RetrieveBaseManifestData();

    }

    // ToDo: make two functions as second part finds standard lang files
    public function RetrieveBaseManifestData()
    {
        $isManifestRead = false;

        //  subproject valid ? project only used when lang path exist
        $this->isLangPathDefined = false;

        try {
            //--- root path exist ? ----------------------------------------------------------

	        // Checks given path or matching path on joomla installation for existence
            $isRootPathValid = $this->checkRootPath();

            if (!$isRootPathValid) {
                // search for project xml file and define root path with it

                $projectFileName = $this->projectFileName();

                // sets $prjXmlFilePath
                $isFileFound = $this->searchXmlProjectFile(
                    $projectFileName,
                    $this->prjRootPath
                ); // $this->prjXmlFilePath); //

                $isRootPathValid = $this->checkRootPath();
            }

            if ($isRootPathValid) {

	            //--- manifest file exists ? ----------------------------------------------------------

	            $isManifestFileExist = $this->checkManifestFile();

                if ($isManifestFileExist) {

                    //--- open manifest file ----------------------------------------------------------

                    // Manifest tells if files have to be searched inside component or old on joomla standard paths
                    $manifestLang = new manifestLangFiles ($this->prjXmlPathFilename);

                    $isManifestRead = $manifestLang->isValidXml;

                    //--- project XML and script file -------------------------------------------------

                    $this->projectXMLAndScriptFile($manifestLang);

                    //--- base paths to default and admin ---------------------------------------------

	                // prjDefaultPath, prjAdminPath
                    $this->DefaultAndAdminPath($manifestLang);

                    /*----------------------------------------------------------
                    lang file list
                    ----------------------------------------------------------*/

                    // component is installed on joomla server
                    if ($manifestLang->isInstalled) {

                        //--- component on joomla server folder ---------------------------------------

                        // new standard: lang inside component ?
                        if (!$this->isLangAtStdJoomla) {
                            //--- lang files in component folder ------------------------------------------

                            $startPath = $this->langBasePathInsideProject($this->prjXmlFilePath,  $this->prjType);

                            // project only used when project path exist
                            if (is_dir(dirname($startPath))) {

	                            // subproject valid ? project only used when lang path exist
	                            $this->isLangPathDefined = $this->detectLangBasePath($startPath, $this->useLangSysIni);

	                            if ($this->isLangPathDefined)
	                            {
		                            $this->collectPrjFolderLangFiles();
	                            }
							}
                        } else {
                            //--- lang files in joomla standard folder -------------------------------------
//--------------------------------
                            // old style manifest .... already installed parallel joomla lang path

	                        $langBasePath = $this->langBasePathJoomlaStd($this->prjType);

	                        // subproject valid ? project only used when lang path exist
                            $this->isLangPathDefined = $this->collectManifestLangFiles_OnJoomla($manifestLang, $this->prjType, $langBasePath);
                            if ($this->isLangPathDefined) {

                                // search for late additions not mentioned in manifest
                                $this->extendManifestLangFilesList();
                            }
                        }

                    } else {

                        //--- component on develop folder ---------------------------------------

	                    // new standard: lang inside component ?
                        if (!$this->isLangAtStdJoomla) {

                            //--- lang files in new standard component folder ------------------------------------------

                            //--- Lang path by manifest definition ---------------------------------------
                            $startPath = $this->prjRootPath . "/" . $manifestLang->defaultLangPath;
                            if ($this->prjType == projectType::PRJ_TYPE_COMP_BACK || $this->prjType == projectType::PRJ_TYPE_COMP_BACK_SYS) {
                                $startPath = $this->prjRootPath . "/" . $manifestLang->adminLangPath;
                            }

                            // project only used when project path exist
                            if (is_dir(dirname($startPath))) {

	                            // subproject valid ? project only used when lang path exist
	                            $this->isLangPathDefined = $this->detectLangBasePath($startPath, $this->useLangSysIni);

								if ($this->isLangPathDefined)
								{
									$this->collectPrjFolderLangFiles();
								}
                            }


                        } else {

	                        //--- lang files by manifest in develop ------------------------------------------
//--------------------------------
	                        // old style manifest .... ' file explicit defined in manifest

	                        $this->langBasePath = $this->prjRootPath; // root path expected

	                        // old style .... '<languages>' xml element exists
                            if (is_dir(dirname($this->langBasePath))) {

                                // subproject valid ? project only used when lang path exist
                                $this->isLangPathDefined = $this->collectManifestLangFiles_OnDevelop($manifestLang,
	                                $this->prjType, $this->langBasePath);

                                if ($this->isLangPathDefined) {
                                    // ToDo: old style: check for additional files not mentioned ==> has to be tested
                                    // search for late additions not mentioned in manifest
                                    $this->extendManifestLangFilesList();
                                }
                            }
                        }
                    }
                }
            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing RetrieveBaseManifestData: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $isManifestRead;
    }

    /**
     * Checks given path or matching path on joomla installation for existence
     *
     * @return bool
     *
     * @since version
     */
    private function checkRootPath()
    {
        $isRootPathValid = false;

        // path has enough characters ?
        if (strlen($this->prjRootPath) > 5) {

            // given dir exists (path on develop folder)
            if (is_dir($this->prjRootPath)) {
                $isRootPathValid = true;
            } else {
                // try root path of component on server (joomla installation)
                if (str_starts_with($this->prjRootPath, '/',) || str_starts_with($this->prjRootPath, '\\',)) {
                    $testPath = JPATH_ROOT . $this->prjRootPath;
                } else {
                    $testPath = JPATH_ROOT . '/' . $this->prjRootPath;
                }

                if (is_dir($testPath)) {
                    $isRootPathValid = true;

                    // ToDo: keep root path without JPATH_ROOT part.
                    // Needs a access function of the prjRootPath
                    // with flag it is on server (instead of PC)
                    $this->prjRootPath = $testPath;
                }
            }
        }

        return $isRootPathValid;
    }

    /**
     * Checks given path to file or file path on joomla installation for existence
     *
     * @return bool
     *
     * @since version
     */
    private function checkManifestFile()
    {
        $isManifestFileFound = false;

        // path has enough characters ?
        if (strlen($this->prjXmlPathFilename) > 5) {
        
            // given file exists (path on develop folder)
            if (is_file($this->prjXmlPathFilename)) {
                $isManifestFileFound = true;

                // save base path to file
                $this->prjXmlFilePath = dirname($this->prjXmlPathFilename);
            } else {

                // try root path of component on server (joomla installation)
                if (str_starts_with($this->prjXmlPathFilename, '/',) || str_starts_with($this->prjXmlPathFilename, '\\',)) {
                    $testFile = JPATH_ROOT . $this->prjXmlPathFilename;
                } else {
                    $testFile = JPATH_ROOT . '/' . $this->prjXmlPathFilename;
                }

                if (is_dir($testFile)) {
                    $isRootPathValid = true;

                    // save changed path with file
                    $this->prjXmlPathFilename = $testFile;
                    // save base path to file
                    $this->prjXmlFilePath = dirname($this->prjXmlPathFilename);

                }

            }
        }

        return $isManifestFileFound;
    }

    /**
     *
     * @return string
     *
     * @since version
     */
    private function projectFileName()
    {
        $projectFileName = $this->prjId;

//		if (   $this->prjType == projectType::PRJ_TYPE_COMP_BACK_SYS
//			|| $this->prjType == projectType::PRJ_TYPE_COMP_BACK)
//		{
//			// $projectFileName = 'com_' . $this->prjId;
//			$projectFileName = substr($this->prjId, 4);
//		}
//
//		$projectFileName = $projectFileName . '.xml';
        $projectFileName = substr($this->prjId, 4) . '.xml';

        return $projectFileName;
    }


    //
    // script- / install file, language files as list, transId
    /**
     * @param $isAddLangFileNames
     *
     * @return false
     *
     * @throws Exception
     * @since version
     */

    /**
     *
     * @return array
     *
     * @since version
     */
    public function getLangIds()
    {
        $langIds = [];

        foreach ($this->langFilesData as $langId => $langFile) {
            $langIds [] = $langId;
        }

        return $langIds;
    }

    // get translations from langFiles (read) and keep file names

    /**
     * @param $langId
     * @param $isReadOriginal
     *
     * @return langFile
     *
     * @since version
     */
    public function getLangFilesData($langId = 'en-GB', $isReadOriginal = false)
    {
        // if not cached or $isReadOriginal
        if (empty($this->langFileNamesSet [$langId]) || $isReadOriginal) {
            return $this->readLangFiles($langId = 'en-GB', $isReadOriginal = false);
        }

        return $this->langFilesData [$langId];
    }

    // read translations from langFiles and keep file names

    /**
     * @param string $langId
     *
     * @return langFile
     *
     * @since version
     */
    public function readLangFiles($langId = 'en-GB')
    {
        if ($langId == '') {
            $langId = 'en-GB';
        }

        if (!empty ($this->langFileNamesSet [$langId])) {
            $langFileNames = $this->langFileNamesSet [$langId];

            if (!empty ($langFileNames)) {
                foreach ($langFileNames as $langFileName) {
                    $fileName     = basename($langFileName);
                    $translations = $this->readLangFile($langFileName);

                    $this->langFilesData [$langId][$fileName] = $translations;
                }
            } else {
                // ToDo: Is warning needed
                $OutTxt = Text::_('No lang files for: ' . $langId . ' found');
                $OutTxt .= 'Project: ' . $this->getPrjIdAndTypeText();
                $app    = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'warning');

                // Needed ?
                $this->langFilesData [$langId] = [];
            }
        } else {
            // ToDo: Is warning needed
            $OutTxt = Text::_('Empty langFileNamesSet[] for: ' . $langId . ' found');
            $OutTxt .= 'Project: ' . $this->getPrjIdAndTypeText();
            $app    = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'warning');

            // Needed ?
            $this->langFilesData [$langId] = [];
        }

        return $this->langFilesData [$langId];
    }

    // read translations from langFile and keep file name

    /**
     * @param $langFileName
     *
     * @return langFile
     *
     * @since version
     */
    public function readLangFile($langFileName)
    {
        $langFileData = new langFile ();
        $langFileData->readFileContent($langFileName);

        return $langFileData;
    }

    /**
     * @param $useLangSysIni
     *
     * @return array|mixed
     *
     * @since version
     */
    public function scanCode4TransIdsLocations($useLangSysIni = false)
    {
        $searchTransIdLocations = new searchTransIdLocations ($this->langIdPrefix);

        $searchTransIdLocations->useLangSysIni       = $this->useLangSysIni;
        $searchTransIdLocations->prjXmlPathFilename  = $this->prjXmlPathFilename;
        $searchTransIdLocations->installPathFilename = $this->installPathFilename;

        // $searchTransIdLocations->langIdPrefix = $this->langIdPrefix;

        // sys file selected
        if ($useLangSysIni || $this->useLangSysIni) {
            //--- scan project files  ------------------------------------

            // scan project XML
            $searchTransIdLocations->searchTransIds_in_XML_file(
                baseName($this->prjXmlPathFilename),
                dirname($this->prjXmlPathFilename)
            );

            // scan install file
            $searchTransIdLocations->searchTransIds_in_PHP_file(
                baseName($this->installPathFilename),
                dirname($this->installPathFilename)
            );
        } else {
            //--- scan all not project files ------------------------------------

            // standard
            if ($this->prjType != projectType::PRJ_TYPE_COMP_BACK_SYS && $this->prjType != projectType::PRJ_TYPE_COMP_BACK) {

                //--- site, module or plugin --------------------------------------

                $searchPath = $this->prjDefaultPath;
            } else {

                //--- backend ------------------------

                $searchPath = $this->prjAdminPath;
            }

            if (empty($searchPath)) {
                $searchPath = $this->prjRootPath;
            }
            $searchTransIdLocations->searchPaths = array($searchPath);

            //--- do scan all not project files ------------------------------------

            $searchTransIdLocations->findAllTranslationIds();
        }

        $this->transIdLocations = $searchTransIdLocations->transIdLocations->items;

        return $this->transIdLocations;
    }

    /**
     * @param $useLangSysIni
     *
     * @return array|mixed
     *
     * @since version
     */
    public function scanCode4TransStringsLocations($useLangSysIni = false)
    {
        $searchTransIdLocations = new searchTransStrings ($this->langIdPrefix);

        $searchTransIdLocations->useLangSysIni       = $this->useLangSysIni;
        $searchTransIdLocations->prjXmlPathFilename  = $this->prjXmlPathFilename;
        $searchTransIdLocations->installPathFilename = $this->installPathFilename;

        // sys file selected
        if ($useLangSysIni || $this->useLangSysIni) {
            //--- scan project files  ------------------------------------

            // scan install file
            $searchTransIdLocations->searchTransStrings_in_PHP_file(
                baseName($this->installPathFilename),
                dirname($this->installPathFilename)
            );
        } else {
            //--- scan all not project files ------------------------------------

            // standard
            if ($this->prjType != projectType::PRJ_TYPE_COMP_BACK_SYS && $this->prjType != projectType::PRJ_TYPE_COMP_BACK) {

                //--- site, module or plugin --------------------------------------

                $searchPath = $this->prjDefaultPath;
            } else {

                //--- backend ------------------------

                $searchPath = $this->prjAdminPath;
            }

            if (empty($searchPath)) {
                $searchPath = $this->prjRootPath;
            }

            $searchTransIdLocations->searchPaths = array($searchPath);

            //--- do scan all not project files ------------------------------------

            $searchTransIdLocations->findAllTranslationStrings();
        }

        $this->transStringsLocations = $searchTransIdLocations->transStringLocations->items;

        return $this->transStringsLocations;
    }

    /**
     *
     * @return array
     *
     * @throws Exception
     * @since version
     */
    public function getPrjTransIdLocations()
    {
        $names = [];

        try {
            foreach ($this->transIdLocations as $name => $val) {
                $names [] = $name;
            }
        } catch (RuntimeException $e) {
            $OutTxt = 'Error executing getPrjTransIdLocations: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $names;
    }

    /**
     * @param $isScanOriginal
     *
     * @return array|mixed
     *
     * @since version
     */
    public function getTransIdLocations($isScanOriginal = false)
    {
        // if not cached or $isReadOriginal
        if (empty($this->transIdLocations) || $isScanOriginal) {
            $this->scanCode4TransIdsLocations($this->useLangSysIni);
        }

        return $this->transIdLocations;
    }

    /**
     * @param $isScanOriginal
     *
     * @return array|mixed
     *
     * @since version
     */
    public function getTransStringsLocations($isScanOriginal = false)
    {
        // if not cached or $isReadOriginal
        if (empty($this->transStringsLocations) || $isScanOriginal) {
            $this->scanCode4TransStringsLocations($this->useLangSysIni);
        }

        return $this->transStringsLocations;
    }

    /**
     * @param $langId
     * @param $isDoClassifyTransIds
     *
     * @return array
     *
     * @since version
     */
    public function getTransIdsClassified($langId = "en-GB", $isDoClassifyTransIds = false)
    {
        if (empty($this->transIdsClassified) || $isDoClassifyTransIds) {
            return $this->classifyTransIds($langId);
        }

        return $this->transIdsClassified;
    }

    /**
     * @param $langId
     *
     * @return array
     *
     * @throws Exception
     * @since version
     */
    public function classifyTransIds($langId = "en-GB")
    {
        //
        $codeTransIds = $this->getPrjTransIdLocations();

        [$missing, $same, $notUsed] = $this->matchTranslationsFile2Locations($codeTransIds, $langId);

        $transIdsClassified            = [];
        $transIdsClassified['missing'] = $missing;
        $transIdsClassified['same']    = $same;
        $transIdsClassified['notUsed'] = $notUsed;

        $transIdsClassified['doubles'] = $this->collectDoubles($langId);

        $this->transIdsClassified = $transIdsClassified;

        return $this->transIdsClassified;
    }

    /**
     * @param $langId
     *
     * @return array
     *
     * @since version
     */
    private function collectDoubles($langId = "en-GB")
    {
        $doubles = [];

        if (!empty ($this->langFilesData [$langId])) {
            // ToDo: each langFilesData[$langId] as $langFile get data not file name
            foreach ($this->langFilesData[$langId] as $langFile) {
                $fileName                     = baseName($langFile->getlangPathFileName());
                $doubles[basename($fileName)] = $langFile->collectDoubles();
            }
        }

        return $doubles;
    }

    /**
     *
     * @return string
     *
     * @since version
     */
    public function getPrjTypeText()
    {
        return projectType::getPrjTypeText($this->prjType);
    }

    /**
     *
     * @return string
     *
     * @since version
     */
    public function getPrjIdAndTypeText()
    {
        return $this->prjId . ': ' . $this->getPrjTypeText();
    }

    /**
     * @param $mainLangId
     *
     *
     * @throws Exception
     * @since version
     */
    public function alignTranslationsByMain($mainLangId)
    {
        $mainTrans = [];

        try {
//			// for each other call
//			foreach ($this->langFilesData as $langId => $temp)
//			{
//				if ($langId != $mainLangId)
//				{
//
//					$this->langFilesData[$langId]->alignTranslationsByMain($mainTrans);
//				}
//			}

            $mainLangFilesData = $this->langFilesData[$mainLangId];

            $transLangIds = $this->getLangIds();

            // all other lang ids
            foreach ($transLangIds as $transLangId) {
                // Not main language
                if ($transLangId != $mainLangId) {
                    //--- all main lang files -----------------------------------------------

                    $transFilesData = $this->langFilesData[$transLangId];

                    foreach ($mainLangFilesData as $mainFileData) {
                        //--- create matching translation file name -----------------------------------------------

                        $mainLangFileName = $mainFileData->getlangPathFileName();
                        $mainTrans        = $mainFileData->translations;

                        $matchTransFileName = $this->matchingNameByTransId(
                            $mainLangId,
                            $mainLangFileName,
                            $transLangId
                        );

                        // look up the matching translation
                        foreach ($transFilesData as $transFileData) {
                            $actTransFileName = $transFileData->getlangPathFileName();

                            // toDo: should not be needed
                            $actTransFileName = str_replace('\\', '/', $actTransFileName);
                            if ($actTransFileName == $matchTransFileName) {
                                // align order of items in matching translation
                                $transFileData->alignTranslationsByMain($mainTrans);
                            }
                        }
                    } // main files
                }
            } // for translation ids

        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing alignTranslationsByMain: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return; // $isFilesFound;
        // ToDo: ....
    }

    /**
     * @param $projectFileName
     * @param $searchPath
     *
     * @return bool
     *
     * @throws Exception
     * @since version
     */
    public function searchXmlProjectFile($projectFileName, $searchPath)
    {
        $isFileFound = false;

        if ($searchPath) {
            // expected path and file name
            $prjXmlPathFilename = $searchPath . '/' . $projectFileName;

            try {
                //--- ? path to file given ? --------------------------------------
                // d:\Entwickl\2022\_github\LangMan4Dev\administrator\components\com_lang4dev\lang4dev.xml

                if (is_file($prjXmlPathFilename)) {
                    $this->prjXmlFilePath     = $searchPath;
                    $this->prjXmlPathFilename = $prjXmlPathFilename;
                    $isFileFound              = true;
                } else
                {
	                #--- All sub folders in folder -------------------------------------

	                $subFolders = Folder::folders($searchPath);
	                if (!empty ($subFolders))
	                {
		                foreach ($subFolders as $folderName)
		                {
			                $subFolder = $searchPath . '/' . $folderName;

			                $isPathFound = $this->searchXmlProjectFile($projectFileName, $subFolder);

			                if ($isPathFound)
			                {
				                break;
			                }
		                }
	                }
                }
            } catch (RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing searchXmlProjectFile: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        return $isFileFound;
    }

    /**
     * @param $langId
     *
     * @return array
     *
     * @since version
     */
    public function getLangFileNames($langId)
    {
        $fileNames = [];

        if (!empty ($this->langFileNamesSet [$langId])) {
            foreach ($this->langFileNamesSet[$langId] as $filePathName) {
                $fileNames [] = basename($filePathName);
            }
        }

        return $fileNames;
    }

    /**
     * @param   manifestLangFiles  $manifestLang
     *
     *
     * @since version
     */
    public function projectXMLAndScriptFile(manifestLangFiles $manifestLang): void
    {
        //--- project XML and script file -------------------------------------------------

        // files config.xml and  to expect for subproject
        [$isConfigXml, $isInstallPhp] = projectType::enabledByType($this->prjType);

        if ($isInstallPhp) {
            $this->installPathFilename = $this->prjXmlFilePath . '/' . $manifestLang->getSriptFile();
            // ToDo: function checkInstallFile ();

        }

        if ($isConfigXml) {
            // ToDo: getConfigFile instead of direct below
            // $this->configPathFilename = $this->prjXmlFilePath . '/' . $manifestLang->getConfigFile();
            $this->configPathFilename = $this->prjXmlFilePath . '/' . 'config.xml';
        }

        // lang id of project
        $this->langIdPrefix = strtoupper($manifestLang->getName());

        //--- pre check type -----------------

        if ($this->prjType == projectType::PRJ_TYPE_COMP_BACK_SYS) {
            $this->useLangSysIni = true;
        }

        // manifest tells about defined list of lang files
        $this->isLangAtStdJoomla = $manifestLang->getIsLangAtStdJoomla();
    }

    private function DefaultAndAdminPath(manifestLangFiles $manifestLang)
    {
        // on server
        if ($manifestLang->isInstalled) {
            // admin given by component in joomla over $prjXmlFilePath
            $this->prjDefaultPath = str_replace('/administrator', '', $this->prjXmlFilePath);;
            $this->prjAdminPath   = $this->prjXmlFilePath;
        } else {
            // default/admin given by manifest file or auto paths
            $this->prjDefaultPath = $this->prjRootPath . '/' . $manifestLang->prjDefaultPath;
            $this->prjAdminPath   = $this->prjRootPath . '/' . $manifestLang->prjAdminPath;
        }
    }

    /**
     *
     * @return array lines
     *
     * @since version
     */
    public function __toText()
    {

        $lines[] = '<h5>--- langSubProject ---------------------------</h5>';

        $lines [] = $this->getPrjIdAndTypeText();
        $lines [] = '$prjId = "' . $this->prjId . '"';
        $lines [] = '$prjType = "' . $this->prjType . '"';

        $lines [] = '$prjRootPath = "' . $this->prjRootPath . '"';
        $lines [] = '$prjXmlFilePath = "' . $this->prjXmlFilePath . '"';
        $lines [] = '$langIdPrefix = "' . $this->langIdPrefix . '"';

        $lines [] = '$prjDefaultPath = "' . $this->prjDefaultPath . '"';
        $lines [] = '$prjAdminPath = "' . $this->prjAdminPath . '"';

        $lines [] = '$prjXmlPathFilename = "' . $this->prjXmlPathFilename . '"';
        $lines [] = '$installPathFilename = "' . $this->installPathFilename . '"';
        $lines [] = '$configPathFilename = "' . $this->configPathFilename . '"';

        $lines [] = '$useLangSysIni = "' . ($this->useLangSysIni ? 'true' : 'false') . '"';
        $lines [] = '$isLangAtStdJoomla = "' . ($this->isLangAtStdJoomla ? 'true' : 'false') . '"';

        $parentLines = parent::__toText();
        array_push($lines, ...$parentLines);

        $lines[] = '--- transIdLocations ---------------------------';
        $lines[] = '            %                                     ';

//		foreach ($this->langFilesData as $langFileData) {
//
//			$langFileDataText = $langFileData->__toText();
//			array_push($lines, ...$langFileDataText);
//
//		}
//
        $lines[] = '--- transStringsLocations ---------------------------';
        $lines[] = '            %                                     ';

//		foreach ($this->langFilesData as $langFileData) {
//
//			$langFileDataText = $langFileData->__toText();
//			array_push($lines, ...$langFileDataText);
//
//		}
//

        $lines[] = '--- transIdsClassified ---------------------------';
        $lines[] = '            %                                     ';

//		foreach ($this->langFilesData as $langFileData) {
//
//			$langFileDataText = $langFileData->__toText();
//			array_push($lines, ...$langFileDataText);
//
//		}
//

        return $lines;
    }

} // class

