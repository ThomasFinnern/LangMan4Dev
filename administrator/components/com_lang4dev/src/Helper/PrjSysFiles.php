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
class PrjSysFiles extends langFileNamesSet
{
	public $basePath = '';
	public $prjFile = '';
	public $installFile = '';


    /**
     * @since __BUMP_VERSION__
     */
    public function __construct($basePath = '', $prjName='', $isFindFiles=false, $installFile='script.xml')
    {
        $this->basePath = $basePath;
        $this->prjFile = $prjName . '.xml';
        $this->prjFile = $prjName . '.xml';
        $this->installFile = $installFile;

        $this->langSysFiles = new langFileNamesSet ();

        if ($isFindFiles) {
            $this->findFiles ();
        }

        // $this->clear();
    }

    public function clear () {

        $this->basePath = '';
        $this->prjFile = '';
        $this->installFile = '';
        $this->langSysFiles = [];

    }

    public function findFiles ($basePath = '', $prjName='') {

        if ($basePath != '') {
            $this->basePath = $basePath;
        }

        if ($prjName != '') {
            $this->prjFile = $prjName . '.xml';
        }

        $this->detectInstallFile();

        $this->detectBasePath();
        $this->searchSysLangFiles();

    }

    public function detectInstallFile () {
        $installFile='';

        try {
            $filePath = $this->basePath . '/' . '';
            if (!is_file($filePath))
            {

                //--- path does not exist -------------------------------

                $OutTxt = 'Warning: langFile.assignFileContent: File does not exist "' . $filePath . '"<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'warning');

                return;
            }

            if ()
            ;





        }
        catch (\RuntimeException $e)
		{
            $OutTxt = '';
            $OutTxt .= 'Error executing detectInstallFile: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }



        $this->installFile = $installFile;
    }

    public function searchSysLangfiles () {







    }

} // class