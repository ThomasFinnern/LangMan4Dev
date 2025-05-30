<?php
/**
 * @package         LangMan4Dev
 * @subpackage      com_lang4dev
 * @author          Thomas Finnern <InsideTheMachine.de>
 * @copyright  (c)  2022-2025 Lang4dev Team
 * @license         GPL2
 */

namespace Finnern\Component\Lang4dev\Administrator\Helper;

use Exception;
use Joomla\CMS\Factory;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;
use RuntimeException;

use function defined;

//use Finnern\Component\Lang4dev\Administrator\Helper\sourceLangFiles;

// no direct access
defined('_JEXEC') or die;

// ToDo: divide root path / project path .....

/**
 * File path and name with parts:
 *   - Keep complete path and name of file
 *   - Extract parts to identify
 *        - System File (*.sys.ini)
 *        - Language ID like 'en-GB'
 *        - Language ID precedes the file name (en-GB.com_name.ini)
 *   - Create path from extracted parts
 *   -
 *   -
 *
 *
 * @package Lang4dev
 */
class langPathFileName
{
    // ToDo: ? plg / mod in administrator language ... ??? fall back ....

    /**
     * @var string
     * @since version
     */
    protected $langPathFileName = '???.ini';
    protected $langId = 'en-GB'; // 'en-GB'  // lang ID
    public $isIdPreceded = false;
    public $isSysFile = false;  # lang file type (normal/sys)

	// ToDo: ? on dev / server
	// public $isLangAtStdJoomla = false; // not inside component folder

	// ToDo: on old joomla style or on extension local  
	// public $isOnServer = false; // not inside component folder see function isLangAtStdJoomla()
	
    // ToDo: base path variable
    // ToDo: get without base path ? base path as parameter ?

    /**
     * @since __BUMP_VERSION__
     */
    public function __construct($langPathFileName = '')
    {
        if ($langPathFileName != '') {
            $this->setLangPathFileName($langPathFileName);
        } else {
            $this->langPathFileName = '';
        }
    }

    public function clear()
    {
        $this->langPathFileName = '???.ini';
        $this->langId           = '??-??'; #'en-GB'  # lang ID
        $this->isIdPreceded     = false;
        $this->isSysFile        = false;  # lang file type (normal/sys)

    }

//	public function getRootLangPathFileName (){

    /**
     *
     * @return mixed|string
     *
     * @since version
     */
    public function getlangPathFileName()
    {
        return $this->langPathFileName;
    }

    // part inside component

    /**
     *
     * @return string
     *
     * @since version
     */
    public function getlangSubPrjPathFileName()
    {
        $prjPath = self::extractProjectPath($this->langPathFileName);

        // path parts
        // 2022.06.07 // 2022.06.07 $langId[$fileName, $oldLangId, $isIdPreceded, $isSysFile] =
        [$fileName, $langId, $isIdPreceded, $isSysFile] =
            self::extractNameParts($this->langPathFileName);
        // 2022.06.07 $langId = $this->langId;

        // reformat subproject path
        $componentPath = self::createLangPathFileName($fileName, $prjPath, $langId, $isIdPreceded, $isSysFile);

        $subPrjPath = substr($componentPath, strlen($prjPath) + 1);

        return $subPrjPath;
    }

    /**
     *
     * @return string
     *
     * @since version
     */
    public function getlangFileName()
    {
        return basename($this->langPathFileName);
    }

    /**
     * without starting lang id ([en-GB.]com_lang4dev.ini
     * @return string
     *
     * @since version
     */
    public function getlangBaseFileName()
    {
	    $baseFileName = '';

	   [$fileName, $langId, $isIdPreceded, $isSysFile] =
		    self::extractNameParts($this->langPathFileName);

	    $baseFileName = $this->createLangBaseFileName($fileName, $langId, $isSysFile);

		return $baseFileName;
    }

    // base path below language file (where project xml is expected)

    /**
     * @param $langPathFileName
     *
     * @return string
     *
     * @throws Exception
     * @since version
     */
    public static function extractProjectPath($langPathFileName)
    {
        $projectPath = "";

        try {
//			$langIDPath   = dirname($langPathFileName);
//			$languagePath = dirname($langIDPath);
//			$projectPath  = dirname($languagePath);

            $projectPath = dirname($langPathFileName, 3);
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing extractProjectPath: ' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $projectPath;
    }

    /**
     * @param $langPathFileName
     *
     *
     * @since version
     */
    public function setLangPathFileName($langPathFileName = '')
    {
        // clean up backslashes
        $this->langPathFileName = str_replace('\\', '/', $langPathFileName);

	    // ToDo: ? on dev / server on old joomla style or on extension local  
	    //$this->isLangAtStdJoomla = false; // not inside component folder
		

        if ($langPathFileName != '') {
            // assign flags like is sys, langId retrieved from file path name
            $this->extractNameFlags();
        }
    }

    /**
     *
     * @return mixed|string
     *
     * @since version
     */
    public function getlangID()
    {
        return $this->langId;
    }

    /**
     * @param $langId
     *
     *
     * @since version
     */
    public function setlangID($langId)
    {
        // replace in 'path file name' on one/two places
        $this->replaceLangId($langId);
    }

    /**
     * @param $langId
     *
     *
     * @throws Exception
     * @since version
     */
    public function replaceLangId($langId)
    {
        // project path
        $prjPath = self::extractProjectPath($this->langPathFileName);

        // path parts
        [$fileName, $oldLangId, $isIdPreceded, $isSysFile] = self::extractNameParts($this->langPathFileName);

        // handle filenames with id in front 'en-GB.com_rsgallery2.ini'
        if (str_starts_with($fileName, $oldLangId)) {
            $fileName = $langId . '.' . substr($fileName, 5);
        }

        // create it again
        $langPathFileName = self::createLangPathFileName($fileName, $prjPath, $langId, $isIdPreceded, $isSysFile);

        $this->langId           = $langId;
        $this->langPathFileName = $langPathFileName;
    }

    /**
     * @param $langPathFileName
     *
     * @return array
     *
     * @since version
     */
    public static function extractNameParts($langPathFileName)
    {
        $fileName = basename($langPathFileName);
        $fullPath = dirname($langPathFileName);

        // lang id
        $langId = basename($fullPath);

        // is id preceded
        $isIdPreceded = str_starts_with($fileName, $langId);

        // remove langId from file name
        if ($isIdPreceded) {
            $fileName = substr($fileName, 6);
        }

        // sys file
        $isSysFile = str_ends_with($fileName, '.sys.ini');

        // remove extension
        if ($isSysFile) {
            $fileName = substr($fileName, 0, -8);
        } else {
            $fileName = substr($fileName, 0, -4);
        }
		
	    return [$fileName, $langId, $isIdPreceded, $isSysFile];
    }

    /**
     *
     * @return bool
     *
     * @throws Exception
     * @since version
     */
    public function isLangAtStdJoomla()
    {
        $isLangAtStdJoomla = false;

        $prjPath = self::extractProjectPath($this->langPathFileName);

        if (str_starts_with($prjPath, str_replace('\\', '/', JPATH_ADMINISTRATOR))) { // . '/' .'languages')) {
            $isLangAtStdJoomla = true;
        }

        if (str_starts_with($prjPath, str_replace('\\', '/', JPATH_ROOT))) { // . '/' .'languages')) {
            $isLangAtStdJoomla = true;
        }

        return $isLangAtStdJoomla;
    }

    /**
     *
     *
     * @since version
     */
    public function extractNameFlags()
    {
        [$fileName, $this->langId, $this->isIdPreceded, $this->isSysFile] =
            self::extractNameParts($this->langPathFileName);
		
    }

    /**
     * @param $fileName
     * @param $prjPath
     * @param $langId
     * @param $isIdPreceded
     * @param $isSysFile
     *
     * @return array|string|string[]
     *
     * @since version
     */
    public static function createLangBaseFileName($fileName, $langId, $isSysFile)
    {
        $langBaseFileName = '';

        // try
        {
	        $langBaseFileName = $fileName;

            // pre add lang id
            if ($isSysFile) {
	            $langBaseFileName .= '.sys';
            }

	        $langBaseFileName .= '.ini';

        }

        // catch

        return $langBaseFileName;
    }

	/**
	 * @param $fileName
	 * @param $prjPath
	 * @param $langId
	 * @param $isIdPreceded
	 * @param $isSysFile
	 *
	 * @return array|string|string[]
	 *
	 * @since version
	 */
	public static function createLangPathFileName($fileName, $prjPath, $langId, $isIdPreceded, $isSysFile)
	{
		$langPathFileName = '';

		// try
		{

			$langPath = $prjPath . '/language/' . $langId . '/';

			// pre add lang id
			if ($isIdPreceded) {
				$langPath .= $langId . '.';
			}

			$langPath .= $fileName;

			// pre add lang id
			if ($isSysFile) {
				$langPath .= '.sys';
			}

			$langPath .= '.ini';

			// clean up backslashes
			$langPathFileName = str_replace('\\', '/', $langPath);
		}

		// catch

		return $langPathFileName;
	}

	// use local filename insead of external one

    /**
     * @param $isMustExist
     *
     * @return bool
     *
     * @since version
     */
    public function check4ValidPathFileName($isMustExist = false)
    {
        return self::isValidPathFileName($this->getlangFileName(), $isMustExist);
    }

    /**
     * @param $langPathFileName
     * @param $isMustExist
     *
     * @return bool
     *
     * @since version
     */
    public static function isValidPathFileName($langPathFileName = '', $isMustExist = false)
    {
        $isNameVerified = true;

        if (!str_ends_with($langPathFileName, '.ini')) {
            $isNameVerified = false;
        } else {
            //
            if (strlen($langPathFileName) < 8) {
                $isNameVerified = false;
            }

            // accidentally includes path (Has slash in it)

            // ToDo: name/path has valid lang ID

            // sys exist at right place ?

            // File must exist
            if ($isMustExist && !is_file($langPathFileName)) {
                $isNameVerified = false;
            }
        }

        return $isNameVerified;
    }

    /**
     * @param $langPathFileName
     *
     * @return bool
     *
     * @throws Exception
     * @since version
     */
    public function createLangFolder($langPathFileName = '')
    {
        $isCreated = false;

        try {
            // use local one if nothing is given
            if ($langPathFileName = '') {
                $langPathFileName = $this->getlangFileName();
            }

            // does exist already ?
            $isCreated = is_dir($langPathFileName);

            // Needs creation
            if (!$isCreated) {
                $langIDPath   = dirname($langPathFileName);
                $languagePath = dirname($langIDPath);
                // $projectPath  = dirname($languagePath);

                // language path must exist
                if (is_dir($languagePath)) {
                    $isCreated = Folder::create($langIDPath);
                }
            }

            // error message on failure
            if (!$isCreated) {
                $OutTxt = '';
                $OutTxt .= 'Error in createLangFolder: can not create: ' . '<br>'
                    . ': "' . $langPathFileName . '"';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing createLangFolder: ' . '<br>'
                . ': "' . $langPathFileName . '"';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $isCreated;
    }

    /**
     * @param $langBaseFolder
     *
     * @return array
     *
     * @throws Exception
     * @since version
     */
    public static function allLangIds_FromSubFolderNames($langBaseFolder = '')
    {
        $folderLangIds = [];

        try {
            $folderLangIdsPaths = array_filter(glob($langBaseFolder . '/*'), 'is_dir');

            foreach ($folderLangIdsPaths as $folderLangIdsPath) {
                $testLangId = basename($folderLangIdsPath);

                // minmal length
                if (strlen($testLangId) > 4) {
                    // should have a '-' at pos 3
                    if ($testLangId[2] == '-') {
                        $folderLangIds [] = $testLangId;
                    }
                }
            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing allLangIds_FromSubFolderNames: ' . '<br>'
                . ': "' . $langBaseFolder . '"';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $folderLangIds;
    }

} // class
