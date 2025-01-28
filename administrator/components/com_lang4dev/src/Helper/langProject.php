<?php
/**
 * @package         LangMan4Dev
 * @subpackage      com_lang4dev
 * @author          Thomas Finnern <InsideTheMachine.de>
 * @copyright  (c)  2022-2025 Lang4dev Team
 * @license         GNU General Public License version 2 or later
 */

namespace Finnern\Component\Lang4dev\Administrator\Helper;

use Exception;
use Finnern\Component\Lang4dev\Administrator\Helper\langSubProject;
use Finnern\Component\Lang4dev\Administrator\Helper\projectType;
use Joomla\CMS\Factory;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;

use RuntimeException;

/**
 * @package     Finnern\Component\Lang4dev\Administrator\Helper
 *
 * @since       version
 */
class langProject
{
    public $prjName = '';
    public $prjRootPath = '';
    public $langIdPrefix = "";

    /** @var langSubProject [] */
    public $subProjects = [];

    //external
    //public $twinId = "";
    public $dbId = 0;

//	public $isSysFileFound = false;

    /**
     * @since __BUMP_VERSION__
     */
    public function __construct($prjName = '', $prjRootPath = '')
    {
        $this->prjName     = $prjName;
        $this->prjRootPath = $prjRootPath;
    }

    /**
     * @param $prjId
     * @param $prjType
     * @param $prjRootPath
     * @param $prjXmlFilePath
     *
     * @return \Finnern\Component\Lang4dev\Administrator\Helper\langSubProject
     *
     * @since version
     */
    public function addSubProject(
        string $prjId = '',
        eSubProjectType $prjType = eSubProjectType::PRJ_TYPE_NONE,
        basePrjPathFinder $oBasePrjPath = null,
        manifestLangFiles $oManifestFiles = null
    ) : langSubProject
    {
        $subPrj = new langSubProject (
            $prjId,
            $prjType,
            $oBasePrjPath,
            $oManifestFiles
        );

        // project only used when project path exist
        if ($subPrj->isLangPathDefined) {
            $this->subProjects [] = $subPrj;
        }

        return $subPrj;
    }

    // script- / install file, language files as list, transId

    // one file each sub (used mostly for eng_GB)

    /**
     * @param $langId
     * @param $isReadOriginal
     *
     * @return $this
     *
     * @throws Exception
     * @since version
     */
    public function readLangFiles($langId = 'en-GB', $isReadOriginal = false)
    {
        $isFilesRead = false;

        if ($langId == '') {
            $langId = 'en-GB';
        }

        try {
            // all subprojects
            foreach ($this->subProjects as $subProject) {
//	            // given lang ID
//	            foreach ($subProject->langFileNamesSet[$langId] as $langFile)
//	            {
//		            // Read translations
//		            $subProject->readLangFile($langId, $isReadOriginal);
//	            }

	            $subProject->readLangFiles($langId);
	            // $isFilesRead = true;
            }
        } catch (RuntimeException $e) {
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
    /**
     * @param $isReadOriginal
     *
     * @return false
     *
     * @throws Exception
     * @since version
     */
    public function readAllLangFiles($isReadOriginal = false)
    {
        $isFilesRead = false;

        try {
            // all subprojects
            foreach ($this->subProjects as $subProject) {
                // all existing
                foreach ($subProject->langIds as $langId) {
                    $this->readLangFiles($langId);
                }
            }
	        // $isFilesRead = true;
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing readAllLangFiles: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $isFilesRead;
    }

    /**
     *
     * @return $this
     *
     * @throws Exception
     * @since version
     */
    public function scanCode4TransIds()
    {
        $isFilesFound = false;

        try {
            foreach ($this->subProjects as $subProject) {
                $transIdLocations = $subProject->scanCode4TransIdsLocations();
                $isFilesFound     = count($transIdLocations) > 0;
            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing findFiles: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        //return $isFilesFound;
        return $this;
    }

    /**
     *
     * @return $this
     *
     * @throws Exception
     * @since version
     */
    public function scanCode4TransStrings()
    {
        $isFilesFound = false;

        try {
            foreach ($this->subProjects as $subProject) {
                $transStringsLocations = $subProject->scanCode4TransStringsLocations(); // scanCode4TransIdsLocations
                $isFilesFound          = count($transStringsLocations) > 0;
            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing findFiles: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        // return $isFilesFound;
        return $this;
    }

//    /**
//     *
//     * @return $this
//     *
//     * @throws Exception
//     * @since version
//     */
//    public function detectLangFiles()
//    {
//        try {
//            foreach ($this->subProjects as $subProject) {
//                $subProject->detectLangFiles();
//            }
//        } catch (RuntimeException $e) {
//            $OutTxt = '';
//            $OutTxt .= 'Error executing detectLangFiles: "' . '<br>';
//            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';
//
//            $app = Factory::getApplication();
//            $app->enqueueMessage($OutTxt, 'error');
//        }
//
//        return $this;
//    }

    /**
     * @param $mainLangId
     *
     * @return false
     *
     * @throws Exception
     * @since version
     */
    public function alignTranslationsByMain($mainLangId)
    {
        $isAligned = false;

        try {
            foreach ($this->subProjects as $subProject) {
                $subProject->alignTranslationsByMain($mainLangId);
            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing alignTranslationsByMain: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $isAligned;
    }

    /**
     *
     * @return array
     *
     * @throws Exception
     * @since version
     */
    public function LangFileNamesCollection()
    {
        $langFileSetsPrjs = [];

        try {
            foreach ($this->subProjects as $subProject) {
                $prjId                     = $subProject->prjId . ':'
                    . projectType::prjType2string($subProject->prjType);
                $langFileSetsPrjs [$prjId] = $subProject->langFileNamesSet;
            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing findFiles: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        // return $isFilesFound;
        return $langFileSetsPrjs;
    }

    /**
     * @param $langId
     *
     * @return array
     *
     * @throws Exception
     * @since version
     */
    public function getTransIdsClassified($langId = "en-GB")
    {
        $classified = [];

        if ($langId == '') {
            $langId = 'en-GB';
        }

        try {
            foreach ($this->subProjects as $subProject) {
                $classified [$subProject->getPrjIdAndTypeText()] = $subProject->getTransIdsClassified($langId);
            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing findFiles: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        // return $isFilesFound;
        return $classified;
    }

    /**
     *
     * @return array lines
     *
     * @since version
     */
    public function __toText()
    {
        $lines[] = '<h4>=== langProject ===============================</h4>';

        $lines [] = '$prjName = "' . $this->prjName . '"';
        $lines [] = '$prjRootPath = "' . $this->prjRootPath . '"';
        $lines [] = '$langIdPrefix = "' . $this->langIdPrefix . '"';
        $lines [] = '$dbId = "' . $this->dbId . '"';
        $lines [] = '<br>';

        // $lines[] = '------------------------------------------------';

        foreach ($this->subProjects as $subProject) {
            $subProjectLines = $subProject->__toText();
            array_push($lines, ...$subProjectLines);
        }

        $lines[] = '------------------------------------------------';

        //--- show found file list -----------------------------------------

        // All projects filenames by lang ID

        $langFileSetsPrjs = $this->LangFileNamesCollection();

        $lines[] = '--- Lang file list ----------------------------------';

        foreach ($langFileSetsPrjs as $prjId => $langFileSets) {
            $lines[] = '[' . $prjId . ']';

            foreach ($langFileSets as $langId => $langFiles) {
                $lines[] = '    [' . $langId . ']';

                foreach ($langFiles as $langFile) {
                    $lines[] = '        ' . $langFile;
                }
            }
        }

        $lines[] = '------------------------------------------------';

        //--- show manifest content -----------------------------------------

        $lines[] = '--- manifest content parts --------------------------';

        // A subproject is defined
        if (!empty ($this->subProjects[0])) {
            $prjXmlPathFilename = $this->subProjects[0]->oBasePrjPath->prjXmlPathFilename; // . '/lang4dev.xml';

            // $manifestData = new manifestData ($prjXmlPathFilename);
            $manifestLang = new manifestLangFiles ($prjXmlPathFilename);
            //$manifestText = implode("\n", $manifestData->__toText());
            $manifestText = $manifestLang->__toText();
            array_push($lines, ...$manifestText);
        }

        $lines[] = '------------------------------------------------<hr>';

//
//		//$lines [] = '$baseName = "' . $this->baseName . '"';
//		$lines [] = '$useLangSysIni = "' . ($this->useLangSysIni ? 'true' : 'false') . '"';
//		$lines [] = '$isLangInFolders = "' . ($this->isLangInFolders ? 'true' : 'false') . '"';
//		$lines [] = '$isLangIdPre2Name = "' . ($this->isLangIdPre2Name ? 'true' : 'false') . '"';
//
//		$lines []    = '--- $langIds ------------------------';
//		$langIdsLine = '';
//		foreach ($this->langIds as $langId)
//		{
//			$langIdsLine .= $langId . ', ';
//		}
//		$lines [] = $langIdsLine;
//
//		$lines [] = '--- $sourceLangFiles ------------------------';
//		foreach ($this->langFileNamesSet as $langId => $langFiles)
//		{
//			$lines [] = '[' . $langId . ']';
//
//			foreach ($langFiles as $langFile)
//			{
//				$lines [] = '   * ' . $langFile;
//			}
//		}

        return $lines;
    }

} // class

