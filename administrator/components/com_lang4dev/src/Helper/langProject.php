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

class langProject
{
	public $prjName = '';
	public $prjRootPath = '';

	public $subProjects = [];

    public $isSysFileFound = false;
	public $componentPrefix = "";
	public $twinId = "";


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
		//$isFilesFound = false;

		try
		{

			foreach ($this->subProjects as $subProject)
			{

				$hasSysFiles = ! ($subProject->prjType == langSubProject::PRJ_TYPE_COMP_BACK
					|| $subProject->prjType == langSubProject::PRJ_TYPE_COMP_SITE);

				// On sys file receive componentPrefix
					$subProject->findPrjFiles();
				if($hasSysFiles)
				{
					$this->componentPrefix = $subProject->componentPrefix;
				}
				else
				{
					// On not sys file assign componentPrefix (ToDo: solve: may not exist yet)
					$subProject->componentPrefix = $this->componentPrefix;
					// $subProject->findPrjFiles();
				}

                /**
                if ($subProject->isSysFiles) {

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
			$OutTxt .= 'Error executing findFiles: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return; // $isFilesFound;
	}

    // one file each sub (used mostly for eng_GB)
    public function readSubsLangFile($langId='en-GB', $isReadOriginal=false)
    {
        $isFilesRead = false;

        try
        {

            foreach ($this->subProjects as $subProject)
            {
                $subProject->readLangFile($langId, $isReadOriginal);
            }

        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing readSubsLangFile: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $isFilesRead;
    }

    // one file each sub (used mostly for eng_GB)
    public function readAllLangFiles($isReadOriginal=false)
    {
        $isFilesRead = false;

        try
        {
            // all sub projects
            foreach ($this->subProjects as $subProject)
            {
                // all existing
                foreach ($this->subProjects->langIds as $langId) {

                    $subProject->readLangFile($langId, $isReadOriginal);
                }
            }

        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing readSubsLangFile: "' . '<br>';
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

                $subProject->scanCode4TransIdsLocations();

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

        return $isFilesFound;
    }


} // class

