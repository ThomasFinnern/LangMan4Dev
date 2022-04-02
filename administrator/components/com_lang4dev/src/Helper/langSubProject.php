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

class langSubProject
{
	public $prjId = "";
	public $prjType = "";
	public $prjRootPath = '';
	public $prjXmlFilePath = '';

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

    public function findFiles () {

        $isFilesFound = false;

        try {
            //--- Assign from function call variables ------------------------------------

	        $finder = new sysFilesContent();

	        $finder->prjId          = $this->prjId;
	        $finder->prjType        = $this->prjType;
	        $finder->prjRootPath    = $this->prjRootPath;
	        $finder->prjXmlFilePath = $this->prjXmlFilePath;

	        // use sysFilesContent
            // new ...;

            $isFilesFound = $finder ->findFiles();
                        
            if($isFilesFound) {

	            $this->prjRootPath    = $finder->prjRootPath   ;
	            $this->prjXmlFilePath = $finder->prjXmlFilePath;
	            $this->prjId          = $finder->prjId         ;
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


        return $isFilesFound;
    }


} // class

