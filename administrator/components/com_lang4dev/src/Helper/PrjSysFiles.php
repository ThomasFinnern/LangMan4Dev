<?php
/**
 * Paths to extension.xml, script.php and *.sys.ini files
 *
 * @version
 * @package       Lang4dev
 * @copyright (C) 2022-2022 Lang4dev Team
 * @license
 */


namespace Finnern\Component\Lang4dev\Administrator\Helper;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;

use Finnern\Component\Lang4dev\Administrator\Helper\langFileNamesSet;

// no direct access
\defined('_JEXEC') or die;

/**
 *
 *
 *
 *
 * @package Lang4dev
 */
class prjSysFiles extends langFileNamesSet
{
    public $prjName = '';
	public $prjRootPath = '';
	public $prjXmlFilePath = '';
	public $installFile = '';

    protected $langFiles = []; // $langId -> translations
    protected $langLocations = [];

    /**
     * @since __BUMP_VERSION__
     */
    public function __construct($prjName='', $prjRootPath = '', $prjXmlFilePath = '',
                                $isFindFiles=false, $installFile='script.xml')
    {
        $this->isSysFiles = true;

        $this->prjRootPath = $prjRootPath;

        $this->prjName = $prjName;
        $this->prjXmlFilePath = $prjXmlFilePath;

        $this->installFile = $installFile;

        if ($isFindFiles) {
            $this->findFiles ();
        }

        // $this->clear();
    }

    public function clear () {

        parent::clear ();
        $this->isSysFiles = true;

        $this->prjRootPath = '';
        $this->prjFile = '';
        $this->installFile = '';
        $this->langSysFiles = [];

    }

    public function prjXmlPathFilename () {
        return $this->prjXmlFilePath . DIRECTORY_SEPARATOR . $this->prjName . '.xml';
    }

    public function findFiles ($prjRootPath = '', $prjName='', $prjXmlFilePath = '') {

        $isFilesFound = false;

        try {
            //--- Assign from function call variables ------------------------------------

            if ($prjRootPath != '') {
                $this->prjRootPath = $prjRootPath;
            }

            if ($prjName != '') {
                $this->prjName = $prjName;
            }

            if ($prjXmlFilePath != '') {
                $this->prjXmlFilePath = $prjXmlFilePath;
            }

            //--- find project xml file  ------------------------------------

            // Path may be in project root folder or in $prjXmlFilePath
            $isFileFound = false;

            // By expected path first
            if(strlen($this->prjXmlFilePath) > 5) {
                $isFileFound = $this->searchXmlProjectFile($this->prjXmlFilePath);
            }
            // Not found, find from root
            if ( ! $isFileFound) {
                $isFileFound = $this->searchXmlProjectFile($this->prjRootPath);
            }

            if ( ! $isFileFound) {
                $OutTxt = 'Error XmlProjectFile not found in path: "' . $this->prjXmlFilePath
                    . '" or root path: "'. $this->prjRootPath . '"'. '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');

                return $isFilesFound;
            }

            //--- find install file  ------------------------------------

            $isFileFound = $this->findInstallFile();

            if ( ! $isFileFound) {
                $OutTxt = 'Error InstallFile not found in path: "' . $this->prjXmlFilePath . '"'. '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');

                return $isFilesFound;
            }

            //---   ------------------------------------

            //$this->detectLangBasePath($this->prjRootPath);
            $this->detectLangBasePath($this->prjXmlFilePath, $this->isSysFiles);
            $this->searchLangFiles();

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


    public function searchXmlProjectFile ($searchPath) {

        $isFileFound = false;

        // expected path and file name
        $prjXmlPathFilename = $searchPath . DIRECTORY_SEPARATOR . $this->prjName . '.xml';

        try {

            //--- ? path to file given ? --------------------------------------
            // d:\Entwickl\2022\_github\LangMan4Dev\administrator\components\com_lang4dev\lang4dev.xml

            if (is_file ($prjXmlPathFilename)) {

                $this->prjXmlFilePath = $searchPath;
                $isFileFound = true;
            }
            else {
                #--- All sub folders in folder -------------------------------------

                foreach (Folder::folders($searchPath) as $folderName) {

                    $subFolder = $searchPath . DIRECTORY_SEPARATOR . $folderName;

                    $isPathFound = $this->searchXmlProjectFile($subFolder);

                    if ($isPathFound) {
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

        return $isFileFound;
    }


    // Expects it parallel to project xml file
    public function findInstallFile () {

        $isFileFound = false;
        $installFile = '';

        try {

            //--- fast guess script name --------------------------------

            $installFile = $this->prjXmlFilePath . DIRECTORY_SEPARATOR . 'script.php';

            if (is_file ($installFile)) {
                $isFileFound = true;
            }
            else
            {
                //--- extract from project xml file --------------------------

                $prjXmlPathFilename = $this->prjXmlPathFilename();
                $fileName = $this->extractInstallFileName ($prjXmlPathFilename);

                $installFile = $this->prjXmlFilePath . DIRECTORY_SEPARATOR . $fileName;

                                                            // d:\Entwickl\2022\_github\LangMan4Dev\administrator\components\com_lang4dev\install_langman4dev.php
                if (is_file ($installFile)) {
                    $isFileFound = true;
                }
            }

            if ($isFileFound) {

                $this->installFile = $installFile;
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

    // read content of language file
    public function retrieveLangFileTranslations ($langId='en-GB', $isReadOriginal=false) {


        // if not chached or $isReadOriginal

        if (empty($this->langFiles [$langId]) || $isReadOriginal) {

            $langFileName =  $this->langFileNames [$langId];

            // $langFile = new langFile ($langFileName);
            $langFile = new langFile ();
            $langFile->assignFileContent($langFileName, $langId);

            $this->langFiles [$langId] = $langFile;
        }

        // if (empty($langFiles [$langId]) 0=> return empty ? ...

        return $this->langFiles [$langId];
    }

    public function searchLangLocations ($isScanOriginal=false) {


	    if (empty($this->langLocations) || $isScanOriginal) {

		    $langFile = new langFile ();

		    $oSearchLangLocations = new searchLangLocations ();

		    // scan project XML
		    $prjXmlItems = $oSearchLangLocations->searchLangIdsInFileXml(
			    dirname($this->prjXmlFilePath), baseName($this->prjXmlFilePath));
		    
	        // scan install file
			$installItems = $oSearchLangLocations->searchLangIdsInFilePHP(
				dirname($this->installFile), baseName($this->installFile));

		    $this->langLocations = array_merge ($installItems, $prjXmlItems);
	    }

	    // if (empty($langFiles [$langId]) 0=> return empty ? ...

	    return $this->langLocations;
    }




    public function __toText ()
    {

        $lines = [];

        $lines [] = 'prjName = "' . $this->prjName . '"';
        $lines [] = 'prjRootPath = "' . $this->prjRootPath . '"';
        $lines [] = '$prjXmlFilePath = "' . $this->prjXmlFilePath . '"';
        $lines [] = '$installFile = "' . $this->installFile . '"';
        $lines [] = '---';
        $lines = array_merge ($lines, parent::__toText());

        return $lines;
    }

} // class