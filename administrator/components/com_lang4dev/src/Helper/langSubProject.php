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
    public function __construct($prjName='', $prjRootPath = '',
                                $prjType= '', // ToDo: enum from sub ?
                                $prjXmlFilePath = '', $isFindFiles=false,
                                $installFile='script.xml')
    {

    }

    public function findFiles ($prjRootPath = '', $prjName='', $prjXmlFilePath = '') {

        $isFilesFound = false;

        try {
            //--- Assign from function call variables ------------------------------------

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

