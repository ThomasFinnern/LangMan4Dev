<?php
/**
 * @version
 * @package       Lang4dev
 * @copyright (C) 2022-2022 Lang4dev Team
 * @license
 */

namespace Finnern\Component\Lang4dev\Administrator\Helper;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;

use Finnern\Component\Lang4dev\Administrator\Helper\sysFilesContent;
use Finnern\Component\Lang4dev\Administrator\Helper\searchTransIdLocations;

class langSubProject extends langFileNamesSet
{
	public $prjId = '';
	public $prjType = 0;
	public $prjRootPath = '';
	public $prjXmlFilePath = '';

	public $prjXmlPathFilename = '';
	public $installPathFilename = '';
    public $langIdPrefix = '';

	// external
    // public $parentId = 0;
	// public $twinId = '';


    // !!! ToDo: text_prefix !!!
    // public $text_prefix;

    public $useLangSysIni = false;

    protected $langFiles = []; // $langId -> translation file(s)
	protected $transIdLocations = [];
	protected $transStringsLocations = [];
    protected $transIdsClassified;

	public function __construct($prjId = '',
		$prjType = projectType::PRJ_TYPE_NONE,
		$prjRootPath = '',
		$prjXmlPathFilename = '')
	{
		parent::__construct();

		$this->prjType     = $prjType;
		$this->prjId       = $prjId;
		$this->prjRootPath = $prjRootPath;
		$this->prjXmlPathFilename = $prjXmlPathFilename;
		$this->prjXmlFilePath = dirname($prjXmlPathFilename);

//	    $this->prjXmlFile = $prjXmlFile;
//	    $this->prjScriptFile = $prjScriptFile;

		if ($this->prjType == projectType::PRJ_TYPE_COMP_BACK_SYS)
		{
			$this->useLangSysIni = true;
		}
	}

    private function checkRootPath ()
    {
	    $isOk = false;

	    // continue when path has enough characters
	    if (strlen($this->prjRootPath) > 5)
	    {
		    if (is_dir($this->prjRootPath))
		    {
			    $isOk = true;
		    }
		    else
		    {
			    // try root path of component
			    if (str_starts_with($this->prjRootPath, '/',) || str_starts_with($this->prjRootPath, '\\',))
			    {
				    $testPath = JPATH_ROOT . $this->prjRootPath;
			    }
			    else
			    {
				    $testPath = JPATH_ROOT . '/' . $this->prjRootPath;
			    }

			    if (is_dir($testPath))
			    {

				    $isOk = true;

				    // ToDO: keep root path without JPATH_ROOT part.
				    // Needs a access function of the prjRootPath
				    // with flag it is on server (instead of PC)
				    $this->prjRootPath = $testPath;
			    }
		    }
	    }

		return $isOk;
    }

	public function retrieveMainPrefixId () {

		// ToDo: create class for maintenance to make public all single variables, then use class for class $prefix ...
		// ToDo: adjust finder below accordingly

		// ToDo: replace with better solution -> may need mor through search for different palces plugin, modules
		$finder = new sysFilesContent($this->prjId, $this->prjType, $this->prjRootPath, $this->prjXmlFilePath);

		// ToDo: prjXmlFilePath <-> use prjXmlPathFileName (actually empty so ...

		[$installFileName, $langIdPrefix] = $finder->extractPrjVars($this->prjXmlPathFilename);
		$this->langIdPrefix = $langIdPrefix;
		$this->installPathFilename = $installFileName;

		return;
	}

    public function findPrjFiles () {

        $isFilesFound = false;

        try {

        	//--- check valid path ---------------------------------------------------

	        // continue when path is valid
	        $isRootPathValid = $this->checkRootPath ();
			if($isRootPathValid)
			{

				//--- pre check type -----------------

				if ($this->prjType == projectType::PRJ_TYPE_COMP_BACK_SYS)
				{
					$this->useLangSysIni = true;
				}

				//--- project XML and script file -------------------------------------------------

				$hasSysFiles = projectType::subPrjHasSysFiles($this->prjType);
				if ($hasSysFiles)
				{

					//--- Assign from variables function call ------------------------------------

					$sysXmlData = new sysFilesContent();

					$sysXmlData->prjId       = $this->prjId;
					$sysXmlData->prjType     = $this->prjType;
					$sysXmlData->prjRootPath = $this->prjRootPath;

					// use sysFilesContent
					// new ...;

					$isFilesFound = $sysXmlData->findPrjFiles();

					// take results
					if ($isFilesFound)
					{
						$this->prjXmlFilePath = $sysXmlData->prjXmlFilePath;

						$this->prjXmlPathFilename  = $sysXmlData->prjXmlPathFilename;
						$this->installPathFilename = $sysXmlData->installPathFilename;
						$this->langIdPrefix        = $sysXmlData->langIdPrefix;
					}

					$this->detectLangBasePath($this->prjXmlFilePath, $this->useLangSysIni);
				}
				else
				{
					$this->prjXmlFilePath = $this->prjRootPath;
					$this->detectLangBasePath($this->prjRootPath, $this->useLangSysIni);
				}

			}
        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing findPrjFiles: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $isFilesFound;
    }

    // read content of language file  ==> get translation in langFiles
    public function getLangFile ($langId='en-GB', $isReadOriginal=false)
    {

        // if not cached or $isReadOriginal
        if (empty($this->langFiles [$langId]) || $isReadOriginal) {

            return $this->readLangFile ($langId='en-GB', $isReadOriginal=false);
        }

        return $this->langFiles [$langId];
    }

    // read content of language file  ==> get translation in langFiles
    public function getLangIds ()
    {
    	$langIds = [];


    	foreach ($this->langFiles as $langId => $langFile) {

		    $langIds [] = $langId;

	    }

        return $langIds;
    }


    // read content of language file  ==> get translation in langFiles
    public function readLangFile ($langId='en-GB') {

        $langFileName =  $this->langFileNames [$langId];

        // $langFile = new langFile ($langFileName);
        $langFile = new langFile ();
        $langFile->readFileContent($langFileName);

        $this->langFiles [$langId] = $langFile;

        // if (empty($langFiles [$langId]) 0=> return empty ? ...

        return $this->langFiles [$langId];
    }

    public function scanCode4TransIdsLocations ($useLangSysIni=false) {

		$searchTransIdLocations = new searchTransIdLocations ();

	    $searchTransIdLocations->useLangSysIni = $this->useLangSysIni;
	    $searchTransIdLocations->prjXmlPathFilename = $this->prjXmlPathFilename;
        $searchTransIdLocations->installPathFilename = $this->installPathFilename;

        $searchTransIdLocations->langIdPrefix = $this->langIdPrefix;
        // sys file selected
        if ($useLangSysIni || $this->useLangSysIni) {

            //--- scan project files  ------------------------------------

            // scan project XML
            $searchTransIdLocations->searchTransIds_in_XML_file(
                baseName($this->prjXmlPathFilename), dirname($this->prjXmlPathFilename));

            // scan install file
            $searchTransIdLocations->searchTransIds_in_PHP_file(
                baseName($this->installPathFilename), dirname($this->installPathFilename));
        }
        else {
            //--- scan all not project files ------------------------------------

            // start path
            $searchPath = $this->prjXmlFilePath;
            if (empty($searchPath)) {
                $searchPath = $this->prjRootPath;
            }
            $searchTransIdLocations->searchPaths = array ($searchPath);

            //--- do scan all not project files ------------------------------------

            $searchTransIdLocations->findAllTranslationIds();
        }

        $this->transIdLocations = $searchTransIdLocations->transIdLocations->items;

        return $this->transIdLocations;
    }

    public function scanCode4TransStringsLocations ($useLangSysIni=false) {

		$searchTransIdLocations = new searchTransStrings ();

	    $searchTransIdLocations->useLangSysIni = $this->useLangSysIni;
	    $searchTransIdLocations->prjXmlPathFilename = $this->prjXmlPathFilename;
        $searchTransIdLocations->installPathFilename = $this->installPathFilename;

        $searchTransIdLocations->langIdPrefix = $this->langIdPrefix;
        // sys file selected
        if ($useLangSysIni || $this->useLangSysIni) {

            //--- scan project files  ------------------------------------

            // scan install file
            $searchTransIdLocations->searchTransStrings_in_PHP_file(
                baseName($this->installPathFilename), dirname($this->installPathFilename));
        }
        else {
            //--- scan all not project files ------------------------------------

            // start path
            $searchPath = $this->prjXmlFilePath;
            if (empty($searchPath)) {
                $searchPath = $this->prjRootPath;
            }
            $searchTransIdLocations->searchPaths = array ($searchPath);

            //--- do scan all not project files ------------------------------------

            $searchTransIdLocations->findAllTranslationStrings();
        }

        $this->transStringsLocations = $searchTransIdLocations->transStringLocations->items;

        return $this->transStringsLocations;
    }

    public function getPrjTransIdNames ()
    {
        $names = [];

        try {

            foreach ($this->transIdLocations as $name => $val) {

                $names [] = $name;
            }

        }
        catch (\RuntimeException $e)
        {
            $OutTxt = 'Error executing getPrjTransIdNames: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $names;
    }

    public function getTransIdLocations ($isScanOriginal=false)
    {
        // if not cached or $isReadOriginal
        if (empty($this->transIdLocations) || $isScanOriginal) {

	        $this->scanCode4TransIdsLocations ($this->useLangSysIni);
        }

        return $this->transIdLocations;
    }

    public function getTransStringsLocations ($isScanOriginal=false)
    {
        // if not cached or $isReadOriginal
        if (empty($this->transStringsLocations) || $isScanOriginal) {

	        $this->scanCode4TransStringsLocations ($this->useLangSysIni);
        }

        return $this->transStringsLocations;
    }

    public function classifyTransIds (){

        $codeTransIds = $this->getPrjTransIdNames();

        // ToDo: MainLangId
        $this->MainLangId = 'en-GB';
        $langId = $this->MainLangId;

        $langFile = $this->langFiles [$langId];
		[$missing, $same, $notUsed] = $langFile->separateByTransIds($codeTransIds);

        $transIdsClassified = [];
		$transIdsClassified['missing'] = $missing;
		$transIdsClassified['same'] = $same;
		$transIdsClassified['notUsed'] = $notUsed;

	    $transIdsClassified['doubles'] = $this->collectDoubles();

        $this->transIdsClassified = $transIdsClassified;

        return $this->transIdsClassified;
    }

    public function getTransIdsClassified ($isClassifyTransIds=false){

        if (empty($this->transIdsClassified) || $isClassifyTransIds) {

            return $this->classifyTransIds ();
        }

        return $this->transIdsClassified;
    }

	private function collectDoubles()
	{
		$doubles = [];

		$langId = $this->MainLangId;

		$langFile = $this->langFiles [$langId];
		$doubles = $langFile->collectDoubles();

		return $doubles;
	}

	public function getPrjTypeText () {

		return projectType::getPrjTypeText ($this->prjType);

	}

    public function detectLangFiles() {

        try
        {
            parent::detectLangFiles();
        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing detectLangFiles: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return; // $isFilesFound;
        // ToDo: ....
    }

    public function alignTranslationsByMain($mainLangId) {

		$mainTrans = [];

        try
        {

        	// fetch main translation items
			foreach ($this->langFiles as $langId => $langFile)
			{
				if ($langId == $mainLangId) {

					$mainTrans = $this->langFiles[$langId]->translations;
				}
			}


	        // for each other call
	        foreach ($this->langFiles as $langId => $temp)
	        {
		        if ($langId != $mainLangId) {

		        	$this->langFiles[$langId]->alignTranslationsByMain ($mainTrans);
		        }
	        }

        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing alignTranslationsByMain: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return; // $isFilesFound;
        // ToDo: ....
    }







} // class

