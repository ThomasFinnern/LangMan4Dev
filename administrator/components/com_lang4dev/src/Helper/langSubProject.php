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

class langSubProject
{
	public $prjId = "";
	public $prjRootPath = '';
	public $prjType = "";

	public $prjXmlFilePath = '';

	public $prjXmlFile = "";
	public $prjScriptFile = "";

	protected $langFiles = []; // $langId -> translation file(s)
	protected $langLocations = [];



    public function __construct($prjName='', $prjRootPath = '',
                                $prjType= '', // ToDo: enum from sub ?
                                $prjXmlFilePath = '',
                                $installFile='script.xml')
    {

	    $this->prjId = $prjId;
	    $this->prjRootPath = $prjRootPath;
	    $this->prjType = $prjType;

	    $this->prjXmlFilePath = $prjXmlFilePath;

	    $this->prjXmlFile = $prjXmlFile;
	    $this->prjScriptFile = $prjScriptFile;

    }

    public function findFiles ($prjRootPath = '', $prjName='', $prjXmlFilePath = '') {

        $isFilesFound = false;

        try {
            //--- Assign from function call variables ------------------------------------

	        // use sysFilesContent
           // new ...;

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


} // class

