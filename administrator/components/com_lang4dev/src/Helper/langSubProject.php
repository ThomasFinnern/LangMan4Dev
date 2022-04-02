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

use Finnern\Component\Lang4dev\Administrator\Helper\sysFilesContent;

class langSubProject extends langFileNamesSet
{
	public $prjId = "";
	public $prjType = "";
	public $prjRootPath = '';
	public $prjXmlFilePath = '';
	public $installFile = '';
    public $isSysFiles = false;

	public $prjXmlFile = "";
	public $prjScriptFile = "";

	protected $langFiles = []; // $langId -> translation file(s)
	protected $langLocations = [];

	/*   */
	const PRJ_TYPE_NONE = 0;
	const PRJ_TYPE_COMP_SYS = 1;
	const PRJ_TYPE_COMP_BACK = 2;
	const PRJ_TYPE_COMP_SITE = 3;
	const PRJ_TYPE_MODEL = 4;
	const PRJ_TYPE_PLUGIN = 5;
	// const PRJ_TYPE_TEMPLATE = 1;


	public function __construct($prjId='',
		$prjType= '', // ToDo: enum from sub ?
								$prjRootPath = '',
                                $prjXmlFilePath = '')
    {

	    $this->prjId = $prjId;
	    $this->prjRootPath = $prjRootPath;
	    $this->prjType = $prjType;

	    $this->prjXmlFilePath = $prjXmlFilePath;

//	    $this->prjXmlFile = $prjXmlFile;
//	    $this->prjScriptFile = $prjScriptFile;

    }

    public function findSysFiles () {

        $isFilesFound = false;

        try {

            //--- pre check type

            if ($this->prjType == langSubProject::PRJ_TYPE_COMP_SYS) {
                $this->isSysFiles = true;
            }

            //--- Assign from function call variables ------------------------------------

	        $finder = new sysFilesContent();

	        $finder->prjId          = $this->prjId;
	        $finder->prjType        = $this->prjType;
	        $finder->prjRootPath    = $this->prjRootPath;
	        $finder->prjXmlFilePath = $this->prjXmlFilePath;

	        // use sysFilesContent
            // new ...;

            $isFilesFound = $finder ->findSysFiles();

            // take results
            if($isFilesFound) {
                // Path and name
	            $this->prjXmlFilePath = $finder->prjXmlFilePath;
	            $this->installFile    = $finder->installFile         ;
            }

            //---   ------------------------------------

            //$this->detectLangBasePath($this->prjRootPath);
            $this->detectLangBasePath($this->prjXmlFilePath, $this->isSysFiles);
            $this->searchLangFiles();

        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'Error executing findSysFiles: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }


        return $isFilesFound;
    }

    // read content of language file  ==> get translation in langFiles
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


} // class

