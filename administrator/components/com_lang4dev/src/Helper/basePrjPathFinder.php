<?php
/**
 * @package         LangMan4Dev
 * @subpackage      com_lang4dev
 * @author          Thomas Finnern <InsideTheMachine.de>
 * @copyright  (c)  2022-2025 Lang4dev Team
 * @license         GPL2
 */

namespace Finnern\Component\Lang4dev\Administrator\Helper;

use Joomla\CMS\Factory;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;

class basePrjPathFinder
{
    public string $prjRootPath = '';   // ($prjXmlFilePath) detected complete from path origin on
    public string $prjXmlFilePath = ''; // same as root path (prjRootPath)
    public string $prjXmlPathFilename = '';
    public string $subPrjPath = ''; // user known path, may be behind JPATH_ROOT on server

    public string $prjId = ''; // project id 'com_...' helper for detecting the path

    public bool $isRootValid = false;
	public bool $isManifestFileFound = false;
	public bool $isInstalled = false;

    /**
     * @since __BUMP_VERSION__
     */
    public function __construct($prjId, $prjRootPath)
    {
        $this->prjId      = $prjId;
        $this->prjRootPath = $prjRootPath;

        // at least the project id is given
        if ($prjId != '') {
            [
                $this->isRootValid,
                $this->isInstalled,
                $this->prjRootPath,
                $this->subPrjPath,
                $this->prjXmlFilePath
            ]
                = $this->detectProjectPaths();

			//--- detect manifest file -------------------------------------------

			if ($this->isRootValid) {

                [$isManifestFileFound, $prjXmlPathFilename]
                    = $this->searchManifestFileInPath($this->prjRootPath, $this->prjId);

                $this->prjXmlPathFilename = $prjXmlPathFilename;
                $this->isManifestFileFound = $isManifestFileFound;

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
     * @param $prjRootPath
     *
     * @return array
     *
     * @since version
     */
    private function detectProjectPaths($prjId = '', $prjRootPath = '')
    {
        $isRootFound  = false;
        $isInstalled = false;
        $rootPath     = '';

        // previous setting expected ?
        if ($prjId == '') {
            $prjId = $this->prjId;
        }

	    // previous setting expected ?
        if ($prjRootPath == '') {
            $prjRootPath = $this->prjRootPath;
        }

        //--- path accidentally a filename (XML?) -------------------------

        if (is_file($prjRootPath)) {
            $prjRootPath = dirname($prjRootPath);
        }

        //--- path already valid -------------------------

        // fall back
        $rootPath = $prjRootPath;

        if ($rootPath != '') {

			// standard
            if (is_dir($rootPath)
                && $rootPath != '/'
                && $rootPath != '\\'
            ) {

				$isRootFound = true;

            } else {
                //--- fast lane for sources in joomla installation -------------------------

	            // try root path of component on server (joomla installation)
	            if (str_starts_with($prjRootPath, '/',) || str_starts_with($prjRootPath, '\\',))
	            {
		            $rootPath = JPATH_ROOT . $prjRootPath;
	            } else {
		            $rootPath = JPATH_ROOT . '/' . $prjRootPath;
	            }

				// normalize
	            $rootPath   = str_replace('\\', '/', $rootPath);

	            // path already defined and within joomla
                if (is_dir($rootPath)) {
                    $isRootFound  = true;
                    $isInstalled = true;
                }
            }
        }

	    //--- path on server by project ID -------------------------

	    // path not given but project ID can be found on joomla server folder
        if (!$isRootFound) {
            // detect sub path also
            if ($prjRootPath == '') {
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

            if (is_dir($rootPath)) {
                $isRootFound = true;
	            $isInstalled = true;
            }
        }

        //--- detect if joomla path is part of source ------------------------

        if ($isRootFound) {
            // use found path
            if ($prjRootPath == '') {
                $prjRootPath = $rootPath;
            }

// yyyy toDo: .... use function
//

	        $isInstalled = $this->isPathOnJxServer($rootPath);

			if ($isInstalled)
			{
				// reduce path to project path within joomla directory
				$prjRootPath = $this->behindPathOnJxServer ($prjRootPath);
			}
        }

        // path already defined and outside joomla
        if (!$isRootFound) {
            // path already defined
            $rootPath = $prjRootPath;
            // ToDo: should the project id checked as part of the path ?
            if (is_dir($rootPath)) {
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
        return [$isRootFound, $isInstalled, $rootPath, $prjRootPath, $rootPath];
    }

    /** look for existing manifest file
     * path may be in subfolder ../administrator/component/.../*.xml
     * file may start with 'com_' or without
     *
     * @param string $rootPath
     * @param string $prjId
     *
     * @return array
     *
     * @throws \Exception
     * @since version
     */
	private function searchManifestFileInPath($rootPath, $prjId) : array
	{
		$isManifestFileFound = false;
        $prjXmlPathFilename = "";

        try {

            //-- Test direct in given Path -----------------------------------

            // short version without com_, mod_..., plg_ ...
            $prjXmlShortFilename = $rootPath . '/' . strtolower(substr($prjId, 4)) . '.xml';
            // long version with com_, mod_..., plg_ ...
            $prjXmlLongPathFilename = $rootPath . '/' . strtolower($prjId) . '.xml';

            // short
            if (file_exists($prjXmlShortFilename)) {
                $isManifestFileFound = true;
                $prjXmlPathFilename = $prjXmlShortFilename;
            } else {
                // long
                if (file_exists($prjXmlLongPathFilename)) {
                    $isManifestFileFound = true;
                    $prjXmlPathFilename = $prjXmlLongPathFilename;
                } else {

//                    // 2025.01.22 is it needed ?
//                    //--- Search in each sub folder -------------------------------------

//                    //  example project file already/still in subfolder prjRootPath/administrator/component/project/
//
//                    foreach (Folder::folders($rootPath) as $folderName) {
//                        $subFolder = $rootPath . "/" . $folderName;
//
//                        [$isManifestFileFound, $prjXmlPathFilename]
//                             = $this->searchManifestFileInPath($subFolder, $prjId);
//
//                        if ($isManifestFileFound) {
//                            break;
//                        }
//                    }
                }
            }

        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing searchManifestFilePath: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }


		return [$isManifestFileFound, $prjXmlPathFilename];
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

