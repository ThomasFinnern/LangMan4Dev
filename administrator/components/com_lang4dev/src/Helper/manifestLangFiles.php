<?php
/**
 * @package         LangMan4Dev
 * @subpackage      com_lang4dev
 * @author          Thomas Finnern <InsideTheMachine.de>
 * @copyright  (c)  2019-2025 RSGallery2 Team
 * @license         GNU General Public License version 2 or later
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

/**
 * @package     Finnern\Component\Lang4dev\Administrator\Helper
 *
 * @since       version
 */
class manifestLangFiles extends manifestData
{
//	public $prjXmlFilePath = '';
//	public $prjXmlPathFilename = '';
//
//	private $manifest = false; // XML: false or SimpleXMLElement

    // is old paths definition is used ==> language files in joomla base paths instead of inside component
    /**
     * @var bool
     * @since version
     */
    public $isLangAtStdJoomla = false; // not inside component folder

    // [$langId][] = $stdPath . '/' . language name
    // relative to install or web root
	public $stdLangFilePaths = [];
	public $stdLangFiles = [];

    // [$langId][] = $stdPath . '/' . language name
    // relative to install or web root
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
        // import manifest file
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
                // old type language files definition in XML
                //---------------------------------------------------------------

                //--- site/standard -----------------------------------------------

                // <languages folder="site/com_joomgallery/languages">
                // 	 <language tag="en-GB">en-GB/com_joomgallery.ini</language>
                // </languages>

                // extract path and names from text in XML
                $stdLanguagesXml = $this->getByXml('languages', []);

                if (!empty($stdLanguagesXml)) {
                    // add items to
                    [$this->stdLangFilePaths, $this->stdLangFiles] =
                        $this->extractLangPathsByXML($stdLanguagesXml);
                }

                //--- backend -----------------------------------------------

                // <administration>
                //	 <languages folder="administrator/com_joomgallery/languages">
                //	   <language tag="en-GB">en-GB/com_joomgallery.ini</language>
                //	   <language tag="en-GB">en-GB/com_joomgallery.sys.ini</language>
                //	   <language tag="en-GB">en-GB/com_joomgallery.exif.ini</language>
                //	   <language tag="en-GB">en-GB/com_joomgallery.iptc.ini</language>
                //	 </languages>
                // </administration>

                // extract path and names from text in XML
                $administration = $this->getByXml('administration', []);
                if (!empty($stdLanguagesXml)) {
                    $stdLanguagesXml   = $administration->languages;

                    if (!empty($stdLanguagesXml)) {
                        // add items to
                        [$this->adminLangFilePaths, $this->adminLangFiles] =
                            $this->extractLangPathsByXML($stdLanguagesXml);
                    }
                }

                //---------------------------------------------------------------
                // new type language files definition by files in folder language
                //---------------------------------------------------------------

                //--- site/standard -----------------------------------------------

                // 	<files folder="components/com_rsgallery2">
                //		<!--folder>forms</folder-->
                //		<folder>language</folder>

                // Not already defined by XML
                if (empty($this->stdLangFilePaths)) {

                    // determine path (install script / is installed)

                    // like in install script
                    if (! $this->isInstalled) {

                        //--- language folder path ------------------------------------------------

//                        $langFolder = (string) $manifestXml->xpath("/extension/files/folder[contains(text(),'language')]");
//                        // lang folder given
//                        if (!empty($langFolder)) {
//                            // attribute folder for not installed components
//                            $subPath    = "";
//                            $subPathXml = $manifestXml->xpath("/extension/files/@folder");
//                            if (!empty($subPathXml)) {
//                                if (!empty($subPathXml[0])) {
//                                    $subPath = (string)$subPathXml[0];
//                                }
//                            }

                        // defaultLangPathRelative from XML
                        $langPath = $this->prjXmlFilePath .'/' . $this->defaultLangPathRelative;

                    } else {
                        // expected inside component
                        $langPath = $this->prjXmlFilePath .'/' . 'language';
                    }

                    //--- search for lang ID folders and files ------------------------------

                    // add items to
                    [$this->stdLangFilePaths, $this->stdLangFiles] =
                        $this->Search4LangPathInFolders($langPath);

                }

                //--- backend -----------------------------------------------

                //  <administration>
                // 		<files folder="administrator/components/com_rsgallery2/">
                // 			<folder>language</folder>

                // Not already defined by XML
                if (empty($this->adminLangFilePaths)) {

                    // determine path (install script / is installed)

                    // like in install script
                    if (! $this->isInstalled) {

                        //--- language folder path ------------------------------------------------

                        $langFolder = '';
                        $langFolderXml =  $manifestXml->xpath("/extension/administration/files/folder[contains(text(),'language')]");
                        if ( ! empty($langFolderXml)) {
                            $langFolder = (string)$langFolderXml[0];
                        }
//                        // lang folder given
//                        if (!empty($langFolder)) {
//                            // attribute folder for not installed components
//                            $subPath = "";
//                            $subPathXml = $manifestXml->xpath("//administration/files/@folder");
//                            if (!empty($subPathXml)) {
//                                if (!empty($subPathXml[0])) {
//                                    $subPath = (string)$subPathXml[0];
//                                }
//                            }

                        // defaultLangPathRelative from XML
                        $langPath = $this->prjXmlFilePath .'/' . $this->adminLangPathRelative;

                    } else {
                        // expected inside component
                        $langPath = $this->prjXmlFilePath .'/' . 'language';
                    }

                    //--- search for lang ID folders and files ------------------------------

                    // add items to
                    [$this->adminLangFilePaths, $this->adminLangFiles] =
                        $this->Search4LangPathInFolders($langPath);

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

    // new:

    /**
     * Are language files not i n component language foldeer but
     * in standard joomla language folders
     * ? true for plugins, modules
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

    /**
     * Old type language files definition in XML
     * Adds path to $this->stdLangFilePaths
     * Adds language file anem to $this->stdLangFiles
     *
     * @param   mixed  $stdLanguages xml object from manifest
     *
     * @return void
     *
     * @since version
     */
    public function extractLangPathsByXML(mixed $stdLanguages): array
    {
        $langFilePaths = [];
        $langFiles     = [];

        try {
            if (count($stdLanguages) > 0) {
                //<languages folder="site/com_joomgallery/languages">
                //	<language tag="en-GB">en-GB/com_joomgallery.ini</language>
                //</languages>

                // lang files path will be defined in XML and copied to joomla standard path not component
                $this->isLangAtStdJoomla = true;

                //--- collect files from xml definition ------------------------------

                $stdPath = (string)$stdLanguages['folder'];

                foreach ($stdLanguages->language as $language) {
// old
//                $langId             = (string)$language['tag'];
//                $subFolder[$langId] = $stdPath . '/' . (string)$language; // $language[0]
//                $this->stdLangFilePaths[] = $subFolder;
//                $this->stdLangFiles[]     = basename((string)$language);

                    $langId = (string)$language['tag'];
                    $test   = $stdPath . '/' . (string)$language;

                    $langFilePaths[$langId][] = $stdPath . '/' . (string)$language;
                    $langFiles[]              = basename((string)$language);
                }
            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing Search4LangPathInFolders: ' . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return [$langFilePaths, $langFiles];
    }

    /**
     * New type language files definition by files in folder language
     *
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
    private function Search4LangPathInFolders(string $langPath) : array
    {
        $langFilePaths = [];
        $langFiles     = [];

        try {
            //--- collect folder in path from xml definition ------------------------------

            foreach (Folder::folders($langPath) as $folderName) {
                // sanitize lang name like en-GB
                if (strlen($folderName) == 5 && $folderName[2] == '-') {
                    $langId    = $folderName;
                    $subFolder = $langPath . "/" . $folderName;

                    foreach (Folder::files($subFolder, '\.ini$') as $fileName) {
                        $langFilePaths[$langId][] = $subFolder . '/' . $fileName;
                        $langFiles[]              = $fileName;
                    }
                }
            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing Search4LangPathInFolders: ' . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return [$langFilePaths, $langFiles];
    }

} // class

