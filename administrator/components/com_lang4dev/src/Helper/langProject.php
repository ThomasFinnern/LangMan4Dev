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

use Finnern\Component\Lang4dev\Administrator\Helper\langSubProject;

class langProject
{
    public $prjName = '';
    public $prjRootPath = '';
    public $prjXmlFilePath = '';
//    public $installFile = '';
	public $isSysFiles = false;

	public $type='';

	public $subProjects = [];

    /**
     * @since __BUMP_VERSION__
     */
    public function __construct($prjName = '', $prjRootPath = '', $prjXmlFilePath = '',
	    $isSysFiles = false, $isFindFiles = false, $installFile = 'script.xml')
    {
	    $this->prjName = $prjName;
	    $this->prjRootPath = $prjRootPath;
	    $this->prjXmlFilePath = $prjXmlFilePath;
	    $this->isSysFiles = $isSysFiles;

        $this->installFile = $installFile;

        if ($isFindFiles) {
            $this->findFiles();
        }

        // $this->clear(); 
    }

    public function clear()
    {

        parent::clear();
        $this->isSysFiles = false;

        $this->prjRootPath = '';
        $this->prjFile = '';
        $this->installFile = '';
        $this->langSysFiles = [];

    }

    public function addSubProject($prjName = '', $prjRootPath = '',
                                  $prjType = '', // ToDo: enum from sub ?
                                  $prjXmlFilePath = '',
								  $isSysFiles = false,
								  $isFindFiles = false,
                                  $installFile = 'script.xml')
    {
		$subPrj = new langSubProject (
			$prjName,
			$prjType,
			$prjXmlFilePath,
			$installFile
		);




		return $subPrj;
    }


    public function prjXmlPathFilename()
    {
        return $this->prjXmlFilePath . DIRECTORY_SEPARATOR . $this->prjName . '.xml';
    }

    public function findFiles($prjRootPath = '', $prjName = '', $prjXmlFilePath = '')
    {


    }

} // class

