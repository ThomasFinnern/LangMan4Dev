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
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
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

	public $adminPathOnDevelopment = "";
    public $sitePathOnDevelopment = "";

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
    public function readManifestData($prjXmlPathFilename = '')
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

    public function langFileOrigins() // $isLangFilesOnServer=true
    {
        // defined by folder language in xml

        $this->isLangAtStdJoomla  = false;
        $this->stdLangFilePaths   = [];
        $this->adminLangFilePaths = [];

        try {
            $manifest = $this->manifest;

            if (!empty ($manifest)) {
                //--- old standard -----------------------------------------------
                //<languages folder="site/com_joomgallery/languages">
                //	<language tag="en-GB">en-GB/com_joomgallery.ini</language>
                //</languages>

                $this->isLangOriginRead = true;

                $stdLanguages = $this->get('languages', []);
                if (count($stdLanguages) > 0) {
                    // lang files path will be defined in XML and copied to joomla standard path not component
                    $this->isLangAtStdJoomla = true;

                    //--- collect files from installation ------------------------------

                    $stdPath = (string) $stdLanguages['folder'];

                    foreach ($stdLanguages->language as $language) {

                        $langId             = (string)$language['tag'];
                        $subFolder[$langId] = $stdPath . '/' . (string)$language; // $language[0]

                        $this->stdLangFilePaths[] = $subFolder;
	                    $this->stdLangFiles[] = basename((string)$language);

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

                $administration = $this->get('administration', []);
                $stdLanguages   = $administration->languages;
                if (count($stdLanguages) > 0) {
                    // lang files path will be defined in XML anf copied to joomla standard path
                    $this->isLangAtStdJoomla = true;

                    //--- collect files from installation ------------------------------

                    $stdPath = (string) $stdLanguages['folder'];

                    foreach ($stdLanguages->language as $language) {
                        $langId             = (string)$language['tag'];
                        $subFolder[$langId] = $stdPath . '/' . (string)$language; // $language[0]

                        $this->adminLangFilePaths[] = $subFolder;
						$this->adminLangFiles[] = basename((string)$language);
                    }
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

