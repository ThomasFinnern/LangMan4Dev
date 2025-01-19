<?php
/**
 * @package         Lang4dev
 * @subpackage
 *
 * @copyright  (c)  2022-2024 Lang4dev Team
 * @license         GPL2
 */

namespace Finnern\Component\Lang4dev\Administrator\Helper;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;


class basePrjPathFinder
{
    public string $prJRootPath = '';   // ($prjXmlFilePath) detected complete from path origin on
    public string $prjXmlFilePath = ''; // same as root path (prJRootPath)
    public string $prjXmlPathFilename = '';
    public string $subPrjPath = ''; // user known path, may be behind JPATH_ROOT on server

    public string $prjId = ''; // project id 'com_...' helper for detecting the path

    public bool $isRootValid = false;
	public bool $isManifestFileFound = false;
	public bool $isInstalled = false;

    /**
     * @since __BUMP_VERSION__
     */
    public function __construct($prjId, $subPrjPath)
    {
        $this->prjId      = $prjId;
        $this->subPrjPath = $subPrjPath;

        // at least the project id is given
        if ($prjId != '') {
            [
                $this->isRootValid,
                $this->isInstalled,
                $this->prJRootPath,
                $this->subPrjPath,
                $this->prjXmlFilePath
            ]
                = $this->detectRootPath();

			//--- detect manifest file -------------------------------------------

			if ($this->isRootValid) {
				$prjXmlFilename = strtolower(substr($this->prjId, 4)) . '.xml';
				$this->isManifestFileFound = $this->searchManifestFilePath($this->prJRootPath, $prjXmlFilename);
			}

        }
    }

    /**
     *
     * @return string
     *
     * @since version
     */
    public function getManifestPathFilename()
    {
        return $this->prjXmlPathFilename;
    }

    /**
     *
     * @return mixed|string
     *
     * @since version
     */
    public function getSubPrjPath()
    {
        return $this->subPrjPath;
    }

    /**
     * @param $prjId
     * @param $subPrjPath
     *
     * @return array
     *
     * @since version
     */
    private function detectRootPath($prjId = '', $subPrjPath = '')
    {
        $isRootFound  = false;
        $isInstalled = false;
        $rootPath     = '';

        // previous setting expected ?
        if ($prjId == '') {
            $prjId = $this->prjId;
        }

	    // previous setting expected ?
        if ($subPrjPath == '') {
            $subPrjPath = $this->subPrjPath;
        }

        //--- path accidentally a filename (XML?) -------------------------

        if (File::exists($subPrjPath)) {
            $subPrjPath = dirname($subPrjPath);
        }

        //--- path already valid -------------------------

        $rootPath = $subPrjPath;

        if ($rootPath != '') {

			// standard
            if (Folder::exists($rootPath)
                && $rootPath != '/'
                && $rootPath != '\\'
            ) {

				$isRootFound = true;

            } else {
                //--- fast lane for sources in joomla installation -------------------------

	            // try root path of component on server (joomla installation)
	            if (str_starts_with($subPrjPath, '/',) || str_starts_with($subPrjPath, '\\',))
	            {
		            $rootPath = JPATH_ROOT . $subPrjPath;
	            } else {
		            $rootPath = JPATH_ROOT . '/' . $subPrjPath;
	            }

				// normalize
	            $rootPath   = str_replace('\\', '/', $rootPath);

	            // path already defined and within joomla
                if (Folder::exists($rootPath)) {
                    $isRootFound  = true;
                    $isInstalled = true;
                }
            }
        }

	    //--- path on server by project ID -------------------------

	    // path not given but project ID can be found on joomla server folder
        if (!$isRootFound) {
            // detect sub path also
            if ($subPrjPath == '') {
                // com plg, mod
                switch (strtolower(substr($prjId, 0, 3))) {
                    case "com":
                        $rootPath = JPATH_ADMINISTRATOR . '/components/' . $prjId;
                        break;
                    case "mod":
                        $rootPath = JPATH_ROOT . '/modules/' . $prjId;
                        break;
                    case "plg":
                        // second part of name is part of path
                        // // plg_finder_...
                        $parts = explode('_', $prjId, 2);
                        if (count($parts) > 1) {
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
	            $isInstalled = true;
            }
        }

        //--- detect if joomla path is part of source ------------------------

        if ($isRootFound) {
            // use found path
            if ($subPrjPath == '') {
                $subPrjPath = $rootPath;
            }

// yyyy toDo: .... use function
//

	        $isInstalled = $this->isPathOnJxServer($rootPath);

			if ($isInstalled)
			{
				// reduce path to project path within joomla directory
				$subPrjPath = $this->behindPathOnJxServer ($subPrjPath);
			}
        }

        // path already defined and outside joomla
        if (!$isRootFound) {
            // path already defined
            $rootPath = $subPrjPath;
            // ToDo: should the project id checked as part of the path ?
            if (Folder::exists($rootPath)) {
                $isRootFound = true;
            }
        }

//        // next check .... ???
//        if (!$isRootFound) {
//            // root path is not defined jet
//            if ($basePrjPathFinder == '') {
//            } else {
//            }
//        }

		$this->isInstalled = $isInstalled;
        return [$isRootFound, $isInstalled, $rootPath, $subPrjPath, $rootPath];
    }

	private function searchManifestFilePath($rootPath, $prjXmlFilename)
	{
		$isManifestFileFound = false;

		// root path is valid before
		$prjXmlPathFilename = $rootPath . '/' . $prjXmlFilename;

		if (is_file($prjXmlPathFilename))
		{
			$isManifestFileFound      = true;
			$this->prjXmlPathFilename = $prjXmlPathFilename;
		}

		#--- Search in each sub folder -------------------------------------

		// example project file already/still only in prJRootPath/administrator/component/ subfolder

		if (!$isManifestFileFound)
		{
			foreach (Folder::folders($rootPath) as $folderName)
			{
				$subFolder = $rootPath . "/" . $folderName;

				$isManifestFileFound = $this->searchManifestFilePath($subFolder, $prjXmlFilename);

				if ($isManifestFileFound)
				{
					break;
				}
			}
		}

		return $isManifestFileFound;
	}

	public function isPathOnJxServer($prjPathFilename)
	{
		$isPathOnJxServer = false;

		$lowerJxPath = strtolower (JPATH_ROOT);
		$lowerPrjPath = strtolower ($prjPathFilename);

		$slashJxPath = str_replace('\\', '/', $lowerJxPath);;
		$slashPrjPath = str_replace('\\', '/', $lowerPrjPath);;

		// project path starts with root path
		if (str_starts_with($slashPrjPath, $slashJxPath)) {
			$isPathOnJxServer = true;
		}

		return $isPathOnJxServer;
	}

	// reduce path to project path within joomla directory
	public function behindPathOnJxServer($prjPath)
	{
		$behindPath = '';
		$behindPath = $prjPath;

		$lowerJxPath = strtolower (JPATH_ROOT);
		$lowerPrjPath = strtolower ($prjPath);

		$slashJxPath = str_replace('\\', '/', $lowerJxPath);;
		$slashPrjPath = str_replace('\\', '/', $lowerPrjPath);;

		// project path starts with root path
		if (str_starts_with($slashPrjPath, $slashJxPath)) {

			// remove joomla root path
			$jxPathLength = strlen($slashJxPath);
			$behindPath = substr($slashPrjPath, $jxPathLength + 1);
		}

		return $behindPath;
	}
}

