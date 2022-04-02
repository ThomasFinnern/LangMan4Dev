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

	public function findFiles()
	{
		$isFilesFound = false;

		try
		{

			foreach ($this->subProjects as $subProject)
			{

				$subProject->findFiles(
					/**
					$subProject->prjId,
					$subProject->prjType,
					$subProject->prjRootPath,
					$subProject->prjXmlFilePath
					/**/
				);

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

	public function prjXmlPathFilename()
	{
		return $this->prjXmlFilePath . DIRECTORY_SEPARATOR . $this->prjName . '.xml';
	}


} // class

