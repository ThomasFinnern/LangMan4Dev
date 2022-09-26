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
use Joomla\CMS\Language\Text;
use RuntimeException;

//use Finnern\Component\Lang4dev\Administrator\Helper\sysFilesContent;
//use Finnern\Component\Lang4dev\Administrator\Helper\searchTransIdLocations;

class langSubProject extends langFiles
{
	public $prjId = '';
	public $prjType = 0;

	// ToDo: Separate std path (plugin/module -> ), admin path, site path

	/**
	 *
	 * @var string
	 * @since version
	 */
	public $prjRootPath = '';
	public $prjXmlFilePath = '';

	// is also admin
	public $prjDefaultPath = '';
	public $prjAdminPath = '';

	public $prjXmlPathFilename = '';
	public $installPathFilename = '';
	public $configPathFilename = '';
	public $langIdPrefix = '';

	// external
	// public $parentId = 0;
	// public $twinId = '';

	// !!! ToDo: text_prefix !!!
	// public $text_prefix;

	public $useLangSysIni = false;
	public $isLangAtStdJoomla = false;

	protected $transIdLocations = [];
	protected $transStringsLocations = [];
	protected $transIdsClassified;

	/**
	 * @param $prjId
	 * @param $prjType
	 * @param $prjRootPath
	 * @param $prjXmlPathFilename
	 */
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

		// Admin path

		if ($this->prjType == projectType::PRJ_TYPE_COMP_BACK_SYS)
		{
			$this->useLangSysIni = true;
		}

		//--- project XML and script file -------------------------------------------------

		// ToDo: yyy read manifest file once for all !!!!

		$manifestLang = new manifestLangFiles ($this->prjXmlPathFilename);
		$this->projectXMLAndScriptFile($manifestLang);

		$this->MainAndSitePath($manifestLang);

	}

	/**
	 *
	 * @return bool
	 *
	 * @since version
	 */
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

					// ToDo: keep root path without JPATH_ROOT part.
					// Needs a access function of the prjRootPath
					// with flag it is on server (instead of PC)
					$this->prjRootPath = $testPath;
				}
			}
		}

		return $isOk;
	}

	/**
	 *
	 * @return bool
	 *
	 * @since version
	 */
	private function checkManifestPath()
	{
		$isManifestPathValid = false;

		// continue when path has enough characters
		if (strlen($this->prjXmlPathFilename) > 5)
		{
			if (is_file($this->prjXmlPathFilename))
			{
				$isManifestPathValid = true;

				// ToDo: create path from ....
				$this->prjXmlFilePath = dirname($this->prjXmlPathFilename);
			}
			else
			{
				// else should not be needed ?
				$this->prjXmlPathFilename = $this->prjXmlPathFilename . "";
			}
		}

		return $isManifestPathValid;
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
	 * @throws \Exception
	 * @since version
	 */
	public function findPrjFiles($isAddLangFileNames = true)
	{

		$isFilesFound = false;

		$isManifestPathValid = false;
		$isRootPathValid     = false;

		try
		{
			//--- check valid project root path ---------------------------------------------------

			$isRootPathValid = $this->checkRootPath();

			$isManifestPathValid = $this->checkManifestPath();

			// xml may be in administrator / ... sub path
			if (!$isRootPathValid)
			{
				$projectFileName = $this->projectFileName();

				// sets $prjXmlFilePath
				$isFileFound     = $this->searchXmlProjectFile($projectFileName, $this->prjRootPath); // $this->prjXmlFilePath); //
				$isRootPathValid = $this->checkManifestPath();
			}

			// manifest found ?
			if ($isRootPathValid)
			{
				//--- check valid manifest path ---------------------------------------------------

				$isManifestPathValid = $this->checkManifestPath();

				if (!$isManifestPathValid)
				{

					$projectFileName = $this->projectFileName();

					$isFileFound         = $this->searchXmlProjectFile($projectFileName, $this->prjRootPath); // $this->prjXmlFilePath); //
					$isManifestPathValid = $this->checkManifestPath();

				}
			}

			// manifest found ?
			if ($isManifestPathValid)
			{
				//--- open manifest file -------------------------------------------------

				// Manifest tells if files have to be searched inside component or old on joomla standard paths
				$manifestLang = new manifestLangFiles ($this->prjXmlPathFilename);

				//--- project XML and script file -------------------------------------------------

				$this->projectXMLAndScriptFile($manifestLang);

				//--- lang files list by manifest ----------------------------------------

				if ($isAddLangFileNames)
				{
					if ($this->isLangAtStdJoomla)
					{

						// includes detectLangBasePath
						$this->collectManifestLangFiles($manifestLang, $this->prjType);

						// ToDo: extendManifestLangFilesList()
						// search for late additions not mentioned in manifest
						$this->extendManifestLangFilesList();

					}
					else
					{
						$startPath = '';

						// is component installed or in develop folder
						if ($manifestLang->isInstalled)
						{

							$startPath = $this->langBasePathJoomla($this->prjType);
						}
						else
						{
							// on development folder read manifest data
							$startPath = $manifestLang->defaultLangPath;
							if ($this->prjType == projectType::PRJ_TYPE_COMP_BACK || $this->prjType == projectType::PRJ_TYPE_COMP_BACK_SYS)
							{
								$startPath = $manifestLang->adminLangPath;
							}

							$this->detectLangBasePath($this->prjRootPath . "/" . $startPath, $this->useLangSysIni);

							$this->collectPrjFolderLangFiles();

						}
					}
				}
			}

		}
		catch (RuntimeException $e)
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

	/**
	 *
	 * @return array
	 *
	 * @since version
	 */
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
		if (empty($this->langFileNamesSet [$langId]) || $isReadOriginal)
		{
			return $this->readLangFiles($langId = 'en-GB', $isReadOriginal = false);
		}

		return $this->langFilesData [$langId];
	}

	// read translations from langFiles and keep file names

	/**
	 * @param $langId
	 *
	 * @return langFile
	 *
	 * @since version
	 */
	public function readLangFiles($langId = 'en-GB')
	{
		if ($langId == '')
		{
			$langId = 'en-GB';
		}

		if (! empty ($this->langFileNamesSet [$langId]))
		{
			$langFileNames = $this->langFileNamesSet [$langId];

			if (!empty ($langFileNames))
			{
				foreach ($langFileNames as $langFileName)
				{
					$fileName     = basename($langFileName);
					$translations = $this->readLangFile($langFileName);

					$this->langFilesData [$langId][$fileName] = $translations;
				}
			}
			else
			{
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

			if ($this->prjType == projectType::PRJ_TYPE_COMP_SITE)
			{
				// ToDo: on develop (not installed) path may be in manifest file,
				// ToDo: to be retrieved before creating sub project ?
				$searchPath = $this->prjRootPath;
				// $basePath = JPATH_ROOT . '/language';
				// JPATH_SITE . '/components/com_lang4dev'
			}

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

	/**
	 *
	 * @return array
	 *
	 * @throws \Exception
	 * @since version
	 */
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
		catch (RuntimeException $e)
		{
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
		if (empty($this->transIdLocations) || $isScanOriginal)
		{

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
		if (empty($this->transStringsLocations) || $isScanOriginal)
		{

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

		if (empty($this->transIdsClassified) || $isDoClassifyTransIds)
		{

			return $this->classifyTransIds($langId);
		}

		return $this->transIdsClassified;
	}

	/**
	 * @param $langId
	 *
	 * @return array
	 *
	 * @throws \Exception
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

		if (! empty ($this->langFilesData [$langId]))
		{
			// ToDo: each langFilesData[$langId] as $langFile get data not file name
			foreach ($this->langFilesData[$langId] as $langFile)
			{
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
	 * @throws \Exception
	 * @since version
	 */
	public function alignTranslationsByMain($mainLangId)
	{

		$mainTrans = [];

		try
		{

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
			foreach ($transLangIds as $transLangId)
			{
				// Not main language
				if ($transLangId != $mainLangId)
				{

					//--- all main lang files -----------------------------------------------

					$transFilesData = $this->langFilesData[$transLangId];

					foreach ($mainLangFilesData as $mainFileData)
					{
						//--- create matching translation file name -----------------------------------------------

						$mainLangFileName = $mainFileData->getlangPathFileName();
						$mainTrans        = $mainFileData->translations;

						$matchTransFileName = $this->matchingNameByTransId($mainLangId, $mainLangFileName, $transLangId);

						// look up the matching translation
						foreach ($transFilesData as $transFileData)
						{
							$actTransFileName = $transFileData->getlangPathFileName();

							// toDo: should not be needed
							$actTransFileName = str_replace('\\', '/', $actTransFileName);
							if ($actTransFileName == $matchTransFileName)
							{

								// align order of items in matching translation
								$transFileData->alignTranslationsByMain($mainTrans);
							}
						}

					} // main files
				}
			} // for translation ids

		}
		catch (RuntimeException $e)
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

	/**
	 * @param $projectFileName
	 * @param $searchPath
	 *
	 * @return bool
	 *
	 * @throws \Exception
	 * @since version
	 */
	public function searchXmlProjectFile($projectFileName, $searchPath)
	{

		$isFileFound = false;

		if ($searchPath)
		{
			// expected path and file name
			$prjXmlPathFilename = $searchPath . '/' . $projectFileName;

			try
			{

				//--- ? path to file given ? --------------------------------------
				// d:\Entwickl\2022\_github\LangMan4Dev\administrator\components\com_lang4dev\lang4dev.xml

				if (is_file($prjXmlPathFilename))
				{

					$this->prjXmlFilePath     = $searchPath;
					$this->prjXmlPathFilename = $prjXmlPathFilename;
					$isFileFound              = true;

				}
				else
				{
					#--- All sub folders in folder -------------------------------------

					foreach (Folder::folders($searchPath) as $folderName)
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
			catch (RuntimeException $e)
			{
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

		if (! empty ($this->langFileNamesSet [$langId]))
		{
			foreach ($this->langFileNamesSet[$langId] as $filePathName)
			{
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

		if ($isInstallPhp)
		{
			$this->installPathFilename = $this->prjXmlFilePath . '/' . $manifestLang->getSriptFile();
			// ToDo: function checkInstallFile ();

		}

		if ($isConfigXml)
		{
			// ToDo: getConfigFile instead of direct below
			// $this->configPathFilename = $this->prjXmlFilePath . '/' . $manifestLang->getConfigFile();
			$this->configPathFilename = $this->prjXmlFilePath . '/' . 'config.xml';
		}

		// lang id of project
		$this->langIdPrefix = strtoupper($manifestLang->getName());

		//--- pre check type -----------------

		if ($this->prjType == projectType::PRJ_TYPE_COMP_BACK_SYS)
		{
			$this->useLangSysIni = true;
		}

		// manifest tells about defined list of lang files
		$this->isLangAtStdJoomla = $manifestLang->getIsLangAtStdJoomla();
	}

	private function MainAndSitePath (manifestLangFiles $manifestLang) {

		// on server
		if ($manifestLang->isInstalled)
		{
			// admin given over $prjXmlFilePath
			$this->prjDefaultPath = $this->prjXmlFilePath;
			$this->prjAdminPath = JPATH_COMPONENT_SITE;
		} else {
			// admin given over $prjXmlFilePath
			$this->prjDefaultPath = $this->prjXmlFilePath;

			$this->prjDefaultPath = $this->prjRootPath . '/'. $manifestLang->prjDefaultPath;
			$this->prjAdminPath = $this->prjRootPath . '/'. $manifestLang->prjAdminPath;
		}

	}


} // class

