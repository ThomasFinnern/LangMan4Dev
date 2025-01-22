<?php
/**
 * @package     Finnern\Component\Lang4dev\Administrator\Helper
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Finnern\Component\Lang4dev\Administrator\Helper;

use Exception;
use Joomla\CMS\Factory;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;
use Joomla\CMS\Language\Text;

use Finnern\Component\Lang4dev\Administrator\Helper\manifestData;
use RuntimeException;

use function defined;

// no direct access
defined('_JEXEC') or die;

// https://www.php.net/manual/de/simplexml.examples-basic.php

class manifestLangFiles extends manifestData
{
//	public $prjXmlFilePath = '';
//	public $prjXmlPathFilename = '';
//
//	private $manifest = false; // XML: false or SimpleXMLElement

    // is old paths definition is used ==> language files in joomla base paths instead of inside component
    public $isLangAtStdJoomla = false; // not inside component folder
	public $stdLangFilePaths = [];
	public $stdLangFiles = [];
	public $adminLangFilePaths = [];
	public $adminLangFiles = [];

//	public $adminPathOnDevelopment = "";
//    public $sitePathOnDevelopment = "";

    // it is read but may not be existing
    private $isLangOriginRead = false;

    /**
     * @since __BUMP_VERSION__
     */
    public function __construct($prjXmlPathFilename = '')
    {
        parent::__construct($prjXmlPathFilename);


    }

    /**
     * @param $prjXmlPathFilename
     *
     * @return bool
     *
     * @throws Exception
     * @since version
     */
    public function readManifestData($prjXmlPathFilename = '') : bool
    {
        $isValidXml = parent::readManifestData($prjXmlPathFilename);

        try {
            if ($isValidXml) {
                $this->langFileOrigins();
            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing readManifestData: "' . $prjXmlPathFilename . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $isValidXml;
    }

    // $isLangFilesOnServer=true
    public function langFileOrigins() : bool
    {
        // defined by folder language in xml

        $this->isLangAtStdJoomla  = false;
        $this->stdLangFilePaths   = [];
        $this->adminLangFilePaths = [];

        try {
            $manifestXml = $this->manifestXml;

            if (!empty ($manifestXml)) {

                // it is read but may not be existing
                $this->isLangOriginRead = true;

                // ToDo: use xpath for faster access and smaller code

                //---------------------------------------------------------------
                // old standard
                //---------------------------------------------------------------

                //--- site/standard -----------------------------------------------

                //<languages folder="site/com_joomgallery/languages">
                //	<language tag="en-GB">en-GB/com_joomgallery.ini</language>
                //</languages>

                $stdLanguages = $this->getByXml('languages', []);
                if (!empty($stdLanguages)) {
                    if (count($stdLanguages) > 0) {
                        // lang files path will be defined in XML and copied to joomla standard path not component
                        $this->isLangAtStdJoomla = true;

                        //--- collect files from installation ------------------------------

                        $stdPath = (string)$stdLanguages['folder'];

                        foreach ($stdLanguages->language as $language) {
                            $langId             = (string)$language['tag'];
                            $subFolder[$langId] = $stdPath . '/' . (string)$language; // $language[0]

                            $this->stdLangFilePaths[] = $subFolder;
                            $this->stdLangFiles[]     = basename((string)$language);
                        }
                    }
                }

                //--- backend -----------------------------------------------

                //<administration>
                //	<languages folder="administrator/com_joomgallery/languages">
                //	    <language tag="en-GB">en-GB/com_joomgallery.ini</language>
                //	    <language tag="en-GB">en-GB/com_joomgallery.sys.ini</language>
                //	    <language tag="en-GB">en-GB/com_joomgallery.exif.ini</language>
                //	    <language tag="en-GB">en-GB/com_joomgallery.iptc.ini</language>
                //	</languages>
                //</administration>

                $administration = $this->getByXml('administration', []);
                $stdLanguages   = $administration->languages;
                if (!empty($stdLanguages)) {
                    if (count($stdLanguages) > 0) {
                        // lang files path will be defined in XML anf copied to joomla standard path
                        $this->isLangAtStdJoomla = true;

                        //--- collect files from installation ------------------------------

                        $stdPath = (string)$stdLanguages['folder'];

                        foreach ($stdLanguages->language as $language) {
                            $langId             = (string)$language['tag'];
                            $subFolder[$langId] = $stdPath . '/' . (string)$language; // $language[0]

                            $this->adminLangFilePaths[] = $subFolder;
                            $this->adminLangFiles[]     = basename((string)$language);
                        }
                    }
                }

                //---------------------------------------------------------------
                // new standard
                //---------------------------------------------------------------

                //--- site/standard -----------------------------------------------

                // 	<files folder="components/com_rsgallery2">
                //		<!--folder>forms</folder-->
                //		<folder>language</folder>

                $langFolder = $manifestXml->xpath("/extension/files/folder[contains(text(),'language')]");
                // lang folder given
                if (!empty($langFolder)) {

                    // attribute folder for not installed components
                    $subPath = "";
                    $subPathXml = $manifestXml->xpath("/extension/files/@folder");
                    if (!empty($subPathXml)){
                        if (!empty($subPathXml[0])){
                            $subPath = (string) $subPathXml[0];
                        }
                    }

                    //--- search for lang ID folders in project or files ------------------------------

                    [$stdLangFilePaths, $stdLangFiles] = $this->Search4LangIdFolderOrFiles ($this->prjDefaultPath, $subPath);

                    $this->stdLangFilePaths = $stdLangFilePaths;
                    $this->stdLangFiles     = $stdLangFiles;

                }

                //--- backend -----------------------------------------------

                //  <administration>
                // 		<files folder="administrator/components/com_rsgallery2/">
                // 			<folder>language</folder>

                $langFolder = $manifestXml->xpath("/extension/administration/files/folder[contains(text(),'language')]");
                // lang folder given
                if (!empty($langFolder)) {

                    // attribute folder for not installed components
                    $subPath = "";
                    $subPathXml = $manifestXml->xpath("//administration/files/@folder");
                    if (!empty($subPathXml)){
                        if (!empty($subPathXml[0])){
                            $subPath = (string) $subPathXml[0];
                        }
                    }

                    //--- search for lang ID folders in project or files ------------------------------

                    [$adminLangFilePaths, $adminLangFiles] = $this->Search4LangIdFolderOrFiles ($this->prjAdminPath, $subPath);

                    $this->adminLangFilePaths = $adminLangFilePaths;
                    $this->adminLangFiles     = $adminLangFiles;

                }


            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing langFileOrigins: ' . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $this->isLangAtStdJoomla;
    }

    /**
     * Collect language IDs like en-GB from folder name (ToDo: or file names)
     * The given sub path is needed if the component is not installed already
     * Installed components try to use 'language' folder
     *
     * @param   string  $subPath
     *
     * @return array[]
     *
     * @since version
     */
    private function Search4LangIdFolderOrFiles(string $rootPath, string $subPath) : array
    {
        $stdLangFilePaths = [];
        $stdLangFiles = [];









        return [$stdLangFilePaths, $stdLangFiles];
    }

    // new: lang files within component

    /**
     *
     * @return bool|mixed
     *
     * @since version
     */
    public function getIsLangAtStdJoomla()
    {
        if ( ! $this->isLangOriginRead) {
            $this->langFileOrigins();
        }

        return $this->isLangAtStdJoomla;
    }

    /**
     *
     * @return array
     *
     * @since version
     */
    public function __toText()
    {
        // $lines = [];

        $lines = parent::__toText();
        //$parentLines = parent::__toText();
        //array_push($lines, ...$parentLines);

        $lines[] = '--- manifestLangFiles ---------------------------';

        $lines[] = 'lang files '
            . ($this->isLangAtStdJoomla ? ' joomla standard folders' : ' inside component');

        if (count($this->stdLangFiles) > 0) {
            $lines[] = '[site lang files]';
            foreach ($this->stdLangFiles as $idx => $langFile) {
                $lines[] = ' * [' . $idx . '] ' . json_encode($langFile);
            }
        }

        if (count($this->adminLangFiles) > 0) {
            $lines[] = '[admin lang files]';
            foreach ($this->adminLangFiles as $idx => $langFile) {
                $lines[] = ' * [' . $idx . '] ' . json_encode($langFile);
            }
        }

        $lines[] = 'lang file paths  '
            . ($this->isLangAtStdJoomla ? ' joomla standard folders' : ' inside component');

        if (count($this->stdLangFilePaths) > 0) {
            $lines[] = '[site lang file paths]';
            foreach ($this->stdLangFilePaths as $idx => $langFilePath) {
                $lines[] = ' * [' . $idx . '] ' . json_encode($langFilePath);
            }
        }

        if (count($this->adminLangFilePaths) > 0) {
            $lines[] = '[admin lang file paths]';
            foreach ($this->adminLangFilePaths as $idx => $langFilePath) {
                $lines[] = ' * [' . $idx . '] ' . json_encode($langFilePath);
            }
        }

        return $lines;
    }

 } // class

