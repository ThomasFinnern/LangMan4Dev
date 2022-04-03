<?php
/**
 * @package     Finnern\Component\Lang4dev\Administrator\Helper
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Finnern\Component\Lang4dev\Administrator\Helper;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\Folder;

use Finnern\Component\Lang4dev\Administrator\Helper\langSubProject;

class sysFilesContent
{
	public $prjId;
	public $prjType;
	public $prjRootPath;
	public $prjXmlFilePath;

	public $prjXmlPathFilename;
	public $installPathFilename;

	public function findPrjFiles ($prjId='', $prjType=langSubProject::PRJ_TYPE_NONE,
                                  $prjRootPath = '', $prjXmlFilePath = '') {

        $isFilesFound = false;

        try {
            //--- Assign from function call variables ------------------------------------

	        if ($prjId != '') {
		        $this->prjId = $prjId;
	        }

	        if ($prjType != langSubProject::PRJ_TYPE_NONE) {
		        $this->prjType = $prjType;
	        }

	        if ($prjRootPath != '') {
                $this->prjRootPath = $prjRootPath;
            }

            if ($prjXmlFilePath != '') {
                $this->prjXmlFilePath = $prjXmlFilePath;
            }

            //--- file searches by type  ------------------------------------

	        [$isSearchXml, $isSearchInstall] = $this->enabledByType ($this->prjType);

	        $isFileFound = false;

	        //--- find project xml file  ------------------------------------

            if ($isSearchXml)
            {

                $projectFileName = $this->projectFileName ();

	            // By expected path first
	            if (strlen($this->prjXmlFilePath) > 5)
	            {
		            $isFileFound = $this->searchXmlProjectFile($projectFileName, $this->prjXmlFilePath);
	            }
	            // Not found, find from root
	            if (!$isFileFound)
	            {
		            $isFileFound = $this->searchXmlProjectFile($projectFileName, $this->prjRootPath);
	            }

	            if (!$isFileFound)
	            {
		            $OutTxt = 'Error XmlProjectFile not found in path: "' . $this->prjXmlFilePath
			            . '" or root path: "' . $this->prjRootPath . '"' . '<br>';

		            $app = Factory::getApplication();
		            $app->enqueueMessage($OutTxt, 'error');

		            return $isFilesFound;
	            }
            }

            //--- find install file  ------------------------------------

	        if ($isSearchXml)
	        {
		        $isFileFound = $this->findInstallFile();

		        if (!$isFileFound)
		        {
			        $OutTxt = 'Error InstallFile not found in path: "' . $this->prjXmlFilePath . '"' . '<br>';

			        $app = Factory::getApplication();
			        $app->enqueueMessage($OutTxt, 'error');

			        return $isFilesFound;
		        }
	        }

//            //---   ------------------------------------
//
//            //$this->detectLangBasePath($this->prjRootPath);
//            $this->detectLangBasePath($this->prjXmlFilePath, $this->isSysFiles);
//            $this->searchLangFiles();
//
            $isFilesFound = true;
        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing detectInstallFile: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }


        return $isFilesFound;
    }

    public function searchXmlProjectFile ($projectFileName, $searchPath) {

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

			        $this->prjXmlFilePath = $searchPath;
			        $this->prjXmlPathFilename = $prjXmlPathFilename;
			        $isFileFound          = true;

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
	        catch (\RuntimeException $e)
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


    // Expects it parallel to project xml file
    public function findInstallFile () {

        $isFileFound = false;
        $installFile = '';

        try {

            //--- fast guess script name --------------------------------

            $installFile = $this->prjXmlFilePath . '/' . 'script.php';

            if (is_file ($installFile)) {
                $isFileFound = true;
            }
            else
            {
                //--- extract from project xml file --------------------------

                $prjXmlPathFilename = $this->prjXmlPathFilename;
                // Not found
	            if ( ! is_file ($prjXmlPathFilename))
	            {
		            $prjXmlPathFilename = $this->projectFileName();
	            }

	            if (is_file ($prjXmlPathFilename))
	            {

		            $fileName = $this->extractInstallFileName($prjXmlPathFilename);

		            $installFile = $this->prjXmlFilePath . '/' . $fileName;

		            if (is_file($installFile))
		            {
			            $isFileFound = true;
			            $this->installPathFilename = $installFile;
		            }
	            }
            }

        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing detectInstallFile: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $isFileFound;
    }


    // Expects it parallel to project xml file
    public function extractInstallFileName ($prjXmlPathFileName)
    {
        $installFileName = '';

        try {

            // content of file
            $context = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
            $xml = file_get_contents($prjXmlPathFileName, false, $context);

            // Data is valid
            if ($xml) {
                //--- read xml to json ---------------------------------------------------

                $prjXml = simplexml_load_string($xml);

                if (!empty ($prjXml)) {

                    //Encode the SimpleXMLElement object into a JSON string.
                    $prjJsonString = json_encode($prjXml);
                    //Convert it back into an associative array
                    $prjArray = json_decode($prjJsonString, true);

                    //--- reduce to version items -------------------------------------------

                    // standard : change log for each version are sub items
                    if (array_key_exists('scriptfile', $prjArray)) {

                        $installFileName = $prjArray ['scriptfile'];

                    }
                }
            }

        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing detectInstallFile: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $installFileName;
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

				$subFolder = $searchPath . '/' . $folderName;

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

				$subFolder = $this->langBasePath . '/' . $folderName;

				// set base name once
				if ($isBaseNameSet == false)
				{
					$fileNames = Folder::files ($subFolder, $regex);

					//if (count ($fileNames) > 0)
					if ($fileNames != false)
					{
						$baseName = $fileNames[0];
						$this->baseName = $baseName;

						$isBaseNameSet  = true;
					}
				}

				$langFile = $subFolder . '/' . $baseName;

				$this->langFileNames [$langId] = $langFile;
			}
		}

		return $isBaseNameSet;
	}

	private function enabledByType($prjType)
	{
		$isSearchXml = false;
		$isSearchInstall = false;

		if (   $prjType == langSubProject::PRJ_TYPE_COMP_BACK_SYS
			|| $prjType == langSubProject::PRJ_TYPE_MODEL
			|| $prjType == langSubProject::PRJ_TYPE_PLUGIN
			) {
			$isSearchXml = true;
		}

		if (   $prjType == langSubProject::PRJ_TYPE_COMP_BACK_SYS
			|| $prjType == langSubProject::PRJ_TYPE_MODEL
			|| $prjType == langSubProject::PRJ_TYPE_PLUGIN
			) {
			$isSearchInstall = true;
		}

		return [$isSearchXml, $isSearchInstall];
	}

    private function projectFileName()
    {
        $projectFileName = $this->prjId;

        if (   $this->prjType == langSubProject::PRJ_TYPE_COMP_BACK_SYS
            || $this->prjType == langSubProject::PRJ_TYPE_COMP_BACK)
        {
            // $projectFileName = 'com_' . $this->prjId;
            $projectFileName = substr($this->prjId, 4);
        }

        $projectFileName = $projectFileName . '.xml';

        return $projectFileName;
    }

} // class

