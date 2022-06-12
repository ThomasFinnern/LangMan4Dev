<?php
/**
 * @package     Lang4dev
 * @subpackage
 *
 * @copyright   (C) 2022-2022 Lang4dev Team
 * @license     GPL2
 */

namespace Finnern\Component\Lang4dev\Administrator\Helper;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;

class subPrjPath
{
	protected $rootPath = '';   // complete from path origin on
	protected $subPrjPath = ''; // user known path, behind JPATH_ROOT on server
	protected $prjId = ''; // helper for detecting the path

	public $isRootValid;
	public $isJoomlaPath;

	/**
	 * @since __BUMP_VERSION__
	 */
	public function __construct($prjId, $subPrjPath)
	{
		$this->prjId = $prjId;
		$this->subPrjPath = $subPrjPath;

		// at least the project id is given
		if ($prjId != '')
		{
			[$this->isRootValid,
				$this->isJoomlaPath,
				$this->rootPath,
				$this->subPrjPath]
				= $this->detectRootPath();
		}

	}

	public function getRootPath () {return $this->rootPath;}
	public function getSubPrjPath () {return $this->subPrjPath;}


	private function detectRootPath($prjId='', $subPrjPath='')
	{
		$isRootFound  = false;
		$isJoomlaPath = false;
		$rootPath     = '';

		// local or external ?
		if ($prjId == '')
		{
			$prjId = $this->prjId;
		}
		if ($subPrjPath == '')
		{
			$subPrjPath = $this->subPrjPath;
		}

		//--- path already valid -------------------------

		$rootPath = $subPrjPath;
		if ($rootPath != '' && Folder::exists($rootPath))
		{
			$isRootFound = true;
		}

		//--- fast lane for sources in joomla installation -------------------------

		if ( ! $isRootFound)
		{
			if ($subPrjPath != '')
			{
				// path already defined and within joomla
				$rootPath = JPATH_ROOT . '/' . $subPrjPath;
				if (Folder::exists($rootPath))
				{
					$isRootFound  = true;
					$isJoomlaPath = true;
				}
			}
		}

		// path not given but project in admin components
		if ( ! $isRootFound)
		{
			// detect sub path also
			if ($subPrjPath == '')
			{
				// com plg, mod
				switch (strtolower(substr($prjId, 0, 3)))
				{
					case "com":
						$rootPath = JPATH_ADMINISTRATOR  . '/components/' . $prjId;
						break;
					case "mod":
						$rootPath = JPATH_ROOT . '/modules/' . $prjId;
						break;
					case "plg":
						// second part of name is part of path
						// // plg_finder_...
						$parts = explode('_', $prjId, 2);
						if(count ($parts) > 1)
						{
							$rootPath = JPATH_ROOT . '/plugins/' . $parts [1] . '/' . $prjId;
						}
						break;
					default:
						// dummy
						$rootPath = JPATH_ROOT . '/' . $prjId;
				}
			}

			if (Folder::exists($rootPath)) {
				$isRootFound = true;
			}

		}

		// detect if joomla path is part of source
		if ($isRootFound) {
			// use found path
			if ($subPrjPath == '')
			{
				$subPrjPath = $rootPath;
			}

			// replace backslash
			$jroot_slash = str_replace('\\', '/', JPATH_ROOT);
			$rootPath_slash = str_replace('\\', '/', $rootPath);
			$subPrjPath_slash = str_replace('\\', '/', $subPrjPath);

			// Is a path within joomla installation found ?
			if(str_starts_with (strtolower($rootPath_slash), strtolower($jroot_slash)))
			{

				$isJoomlaPath = true;

			}

			// reduce path to project path within joomla directory
			if(str_starts_with (strtolower($subPrjPath_slash), strtolower($jroot_slash)))
			{
				// remove joomla root path
				$j_pathLength = strlen($jroot_slash);
				$subPrjPath   = substr($subPrjPath_slash, $j_pathLength+1);
			} else {
				// $subPrjPath   = $subPrjPath_slash; // ToDo: shall we use converted ?
			}
		}

		// path already defined and outside joomla
		if ( ! $isRootFound)
		{
			// path already defined
			$rootPath = $subPrjPath;
			// ToDo: should the project id checked as part of the path ?
			if (Folder::exists($rootPath))
			{
				$isRootFound  = true;
			}
		}

		// next check .... ???
		if ( ! $isRootFound)
		{

			// root path is not defined jet
			if ($subPrjPath == '')
			{


			}
			else
			{


			}

		}




		return [$isRootFound, $isJoomlaPath, $rootPath, $subPrjPath];
	}


}