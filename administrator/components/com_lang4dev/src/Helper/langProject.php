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

use Finnern\Component\Lang4dev\Administrator\Helper\langSubProject;
use Finnern\Component\Lang4dev\Administrator\Helper\projectType;

class langProject
{
	public $prjName = '';
	public $prjRootPath = '';

	public $subProjects = [];

    public $isSysFileFound = false;
	public $langIdPrefix = "";

	//external
	//public $twinId = "";
	public $dbId = 0;


    /**
	 * @since __BUMP_VERSION__
	 */
	public function __construct($prjName = '', $prjRootPath = '')
	{
		$this->prjName     = $prjName;
		$this->prjRootPath = $prjRootPath;

	}

	public function addSubProject($prjId = '',
		$prjType = '', // ToDo: enum from sub ?
		$prjRootPath = '',
		$prjXmlFilePath = '')
	{


		$subPrj = new langSubProject (
			$prjId,
			$prjType,
			$prjRootPath,
			$prjXmlFilePath
		);

		$this->subProjects [] = $subPrj;

		return $subPrj;
	}

	public function findPrjFiles()
	{

		try
		{

			foreach ($this->subProjects as $subProject)
			{

//				$hasSysFiles = ! ($subProject->prjType == projectType::PRJ_TYPE_COMP_BACK
//					|| $subProject->prjType == projectType::PRJ_TYPE_COMP_SITE);

				// On sys file receive langIdPrefix
				$isFilesFound = $subProject->findPrjFiles();

				/**  see below
				if($hasSysFiles && $isFilesFound)
				{
					$this->langIdPrefix = $subProject->langIdPrefix;
				}
				else
				{
					// On not sys file assign langIdPrefix (ToDo: solve: may not exist yet)
					$subProject->langIdPrefix = $this->langIdPrefix;
					// $subProject->findPrjFiles();
				}
				/**/

				// It is expected that function detectDetails defines the sub project langIdPrefix
				$this->langIdPrefix = $subProject->langIdPrefix;

                /**
                if ($subProject->useLangSysIni) {

                    $this->isSysFileFound = true;

                    $this->prjRootPath  = $subProject->prjRootPath;
                    $this->prjXmlPathFilename  = $subProject->prjXmlPathFilename;
                    $this->installPathFilename = $subProject->installPathFilename;
                }
                /**/

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

		return $this;
	}

    // one file each sub (used mostly for eng_GB)
    public function readLangFiles($langId = 'en-GB', $isReadOriginal=false)
    {
        $isFilesRead = false;

	    if ($langId == '') {
		    $langId = 'en-GB';
	    }

	    try
        {
	        // all subprojects
            foreach ($this->subProjects as $subProject)
            {
//	            // given lang ID
//	            foreach ($subProject->langFileNamesSet[$langId] as $langFile)
//	            {
//		            // Read translations
//		            $subProject->readLangFile($langId, $isReadOriginal);
//	            }

	            $subProject->readLangFiles($langId);
            }

        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing readLangFiles: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        // return $isFilesRead;
	    return $this;
    }

    // one file each sub (used mostly for eng_GB)
	// does look in sub project to find available langIds
    public function readAllLangFiles($isReadOriginal=false)
    {
        $isFilesRead = false;

        try
        {
            // all subprojects
            foreach ($this->subProjects as $subProject)
            {
                // all existing
                foreach ($subProject->langIds as $langId) {

	                $this->readLangFiles ($langId);

                }
            }

        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing readAllLangFiles: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $isFilesRead;
    }

    public function scanCode4TransIds()
    {
        $isFilesFound = false;

        try
        {

            foreach ($this->subProjects as $subProject)
            {

	            $transIdLocations = $subProject->scanCode4TransIdsLocations();
	            $isFilesFound = count($transIdLocations) > 0;

            }
        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing findFiles: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        //return $isFilesFound;
	    return $this;
    }

    public function scanCode4TransStrings()
    {
        $isFilesFound = false;

        try
        {
            foreach ($this->subProjects as $subProject)
            {

	            $transStringsLocations = $subProject->scanCode4TransStringsLocations(); // scanCode4TransIdsLocations
	            $isFilesFound = count($transStringsLocations) > 0;
            }
        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing findFiles: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        // return $isFilesFound;
	    return $this;
    }

	public function detectLangFiles() {

		try
		{

			foreach ($this->subProjects as $subProject)
			{
				$subProject->detectLangFiles();
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

		return $this;
	}

	public function alignTranslationsByMain($mainLangId)
	{
		$isAligned = false;

		try
		{

			foreach ($this->subProjects as $subProject)
			{
				$subProject->alignTranslationsByMain($mainLangId);
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

		return $isAligned;
	}

	public function LangFileNamesCollection () {

		$langFileSetsPrjs = [];

		try
		{
			foreach ($this->subProjects as $subProject)
			{

				$prjId = $subProject->prjId . ':' . projectType::getPrjTypeText($subProject->prjType);
				$langFileSetsPrjs [$prjId] = $subProject->langFileNamesSet;

			}
		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing findFiles: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		// return $isFilesFound;
		return $langFileSetsPrjs;
	}

	public function getTransIdsClassified ($langId = "en-GB"){

		$classified = [];

		if ($langId == '') {
			$langId = 'en-GB';
		}

		try
		{
			foreach ($this->subProjects as $subProject)
			{

				$classified [$subProject->getPrjIdAndTypeText ()] = $subProject->getTransIdsClassified($langId);

			}
		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing findFiles: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		// return $isFilesFound;
		return $classified;
	}

} // class

