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

class langSubProject extends langFiles
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

	protected $transIdLocations = [];
	protected $transStringsLocations = [];
	protected $transIdsClassified;

	public function __construct($prjId = '',
		$prjType = projectType::PRJ_TYPE_NONE,
		$prjRootPath = '',
		$prjXmlPathFilename = '')
	{
		parent::__construct();

		$this->prjType            = $prjType;
		$this->prjId              = $prjId;
		$this->prjRootPath        = $prjRootPath;
		$this->prjXmlPathFilename = $prjXmlPathFilename;
		$this->prjXmlFilePath     = dirname($prjXmlPathFilename);

//	    $this->prjXmlFile = $prjXmlFile;
//	    $this->prjScriptFile = $prjScriptFile;

		if ($this->prjType == projectType::PRJ_TYPE_COMP_BACK_SYS)
		{
			$this->useLangSysIni = true;
		}
	}

	private function checkRootPath()
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

	public function retrieveMainPrefixId()
	{

		// ToDo: create class for maintenance to make public all single variables, then use class for class $prefix ...
		// ToDo: adjust finder below accordingly

		// ToDo: replace with better solution -> may need mor through search for different palces plugin, modules
		$finder = new sysFilesContent($this->prjId, $this->prjType, $this->prjRootPath, $this->prjXmlFilePath);

		// ToDo: prjXmlFilePath <-> use prjXmlPathFileName (actually empty so ...

		[$installFileName, $langIdPrefix] = $finder->extractPrjVars($this->prjXmlPathFilename);
		$this->langIdPrefix        = $langIdPrefix;
		$this->installPathFilename = $installFileName;

		return;
	}

	public function findPrjFiles()
	{

		$isFilesFound = false;

		try
		{

			//--- check valid path ---------------------------------------------------

			// continue when path is valid
			$isRootPathValid = $this->checkRootPath();
			if ($isRootPathValid)
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
	public function getLangIds()
	{
		$langIds = [];

		foreach ($this->langFilesData as $langId => $langFile)
		{
			$langIds [] = $langId;
		}

		return $langIds;
	}

	// get translations from langFiles (read) and keep file names
	public function getLangFilesData($langId = 'en-GB', $isReadOriginal = false)
	{

		// if not cached or $isReadOriginal
		if (empty($this->langFileNamesSet [$langId]) || $isReadOriginal)
		{
			return $this->readLangFiles($langId = 'en-GB', $isReadOriginal = false);
		}

		return $this->langFilesData [$langId];
	}

	// read translations from langFiles and keep file names
	public function readLangFiles($langId = 'en-GB')
	{
		if ($langId == '') {
			$langId = 'en-GB';
		}

		$langFileNames = $this->langFileNamesSet [$langId];

		foreach ($langFileNames as $langFileName)
		{
			$fileName = basename ($langFileName);
			$translations = $this->readLangFile($langFileName);

			$this->langFilesData [$langId][$fileName] = $translations;
		}
		// if (empty($langFiles [$langId]) 0=> return empty ? ...

		return $this->langFilesData [$langId];
	}

	// read translations from langFile and keep file name
	public function readLangFile($langFileName)
	{
		$langFileData = new langFile ();
		$langFileData->readFileContent($langFileName);

		return $langFileData;
	}

	public function scanCode4TransIdsLocations($useLangSysIni = false)
	{

		$searchTransIdLocations = new searchTransIdLocations ();

		$searchTransIdLocations->useLangSysIni       = $this->useLangSysIni;
		$searchTransIdLocations->prjXmlPathFilename  = $this->prjXmlPathFilename;
		$searchTransIdLocations->installPathFilename = $this->installPathFilename;

		$searchTransIdLocations->langIdPrefix = $this->langIdPrefix;
		// sys file selected
		if ($useLangSysIni || $this->useLangSysIni)
		{

			//--- scan project files  ------------------------------------

			// scan project XML
			$searchTransIdLocations->searchTransIds_in_XML_file(
				baseName($this->prjXmlPathFilename), dirname($this->prjXmlPathFilename));

			// scan install file
			$searchTransIdLocations->searchTransIds_in_PHP_file(
				baseName($this->installPathFilename), dirname($this->installPathFilename));
		}
		else
		{
			//--- scan all not project files ------------------------------------

			// start path
			$searchPath = $this->prjXmlFilePath;
			if (empty($searchPath))
			{
				$searchPath = $this->prjRootPath;
			}
			$searchTransIdLocations->searchPaths = array($searchPath);

			//--- do scan all not project files ------------------------------------

			$searchTransIdLocations->findAllTranslationIds();
		}

		$this->transIdLocations = $searchTransIdLocations->transIdLocations->items;

		return $this->transIdLocations;
	}

	public function scanCode4TransStringsLocations($useLangSysIni = false)
	{

		$searchTransIdLocations = new searchTransStrings ();

		$searchTransIdLocations->useLangSysIni       = $this->useLangSysIni;
		$searchTransIdLocations->prjXmlPathFilename  = $this->prjXmlPathFilename;
		$searchTransIdLocations->installPathFilename = $this->installPathFilename;

		$searchTransIdLocations->langIdPrefix = $this->langIdPrefix;
		// sys file selected
		if ($useLangSysIni || $this->useLangSysIni)
		{

			//--- scan project files  ------------------------------------

			// scan install file
			$searchTransIdLocations->searchTransStrings_in_PHP_file(
				baseName($this->installPathFilename), dirname($this->installPathFilename));
		}
		else
		{
			//--- scan all not project files ------------------------------------

			// start path
			$searchPath = $this->prjXmlFilePath;
			if (empty($searchPath))
			{
				$searchPath = $this->prjRootPath;
			}
			$searchTransIdLocations->searchPaths = array($searchPath);

			//--- do scan all not project files ------------------------------------

			$searchTransIdLocations->findAllTranslationStrings();
		}

		$this->transStringsLocations = $searchTransIdLocations->transStringLocations->items;

		return $this->transStringsLocations;
	}

	public function getPrjTransIdLocations()
	{
		$names = [];

		try
		{

			foreach ($this->transIdLocations as $name => $val)
			{
				$names [] = $name;
			}

		}
		catch (\RuntimeException $e)
		{
			$OutTxt = 'Error executing getPrjTransIdLocations: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $names;
	}

	public function getTransIdLocations($isScanOriginal = false)
	{
		// if not cached or $isReadOriginal
		if (empty($this->transIdLocations) || $isScanOriginal)
		{

			$this->scanCode4TransIdsLocations($this->useLangSysIni);
		}

		return $this->transIdLocations;
	}

	public function getTransStringsLocations($isScanOriginal = false)
	{
		// if not cached or $isReadOriginal
		if (empty($this->transStringsLocations) || $isScanOriginal)
		{

			$this->scanCode4TransStringsLocations($this->useLangSysIni);
		}

		return $this->transStringsLocations;
	}

	public function getTransIdsClassified($langId="en-GB", $isDoClassifyTransIds = false)
	{

		if (empty($this->transIdsClassified) || $isDoClassifyTransIds)
		{

			return $this->classifyTransIds($langId);
		}

		return $this->transIdsClassified;
	}

	public function classifyTransIds($langId="en-GB")
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

	private function collectDoubles($langId="en-GB")
	{
		$doubles = [];

		foreach ($this->langFilesData[$langId] as $langFile)
		{
			$fileName  = baseName ($langFile->getlangPathFileName());
			$doubles[basename($fileName)] = $langFile->collectDoubles();
		}

		return $doubles;
	}

	public function getPrjTypeText()
	{

		return projectType::getPrjTypeText($this->prjType);

	}

	public function getPrjIdAndTypeText()
	{

		return $this->prjId . ': ' . $this->getPrjTypeText();
	}

	public function detectLangFiles()
	{

		try
		{

			// Manifest tells if files have to be searched inside component or old on joomla standard paths
			$manifestLang = new manifestLangFiles ($this->prjXmlPathFilename);

			// new style lang file origins
			if ( ! $manifestLang->isLanguagesItemExist)
			{
				//--- search in component path -------------------------------

				parent::collectFolderLangFiles();
			}
			else
			{
				//--- use joomla standard paths ------------------------------

				parent::collectManifestLangFiles ($manifestLang, $this->prjType);
			}
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

	public function alignTranslationsByMain($mainLangId)
	{

		$mainTrans = [];

		try
		{

			// fetch main translation items
			foreach ($this->langFilesData as $langId => $langFile)
			{
				if ($langId == $mainLangId)
				{

					$mainTrans = $this->langFilesData[$langId]->translations;
				}
			}

			// for each other call
			foreach ($this->langFilesData as $langId => $temp)
			{
				if ($langId != $mainLangId)
				{

					$this->langFilesData[$langId]->alignTranslationsByMain($mainTrans);
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

