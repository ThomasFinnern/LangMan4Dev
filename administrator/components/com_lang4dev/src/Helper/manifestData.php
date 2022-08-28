<?php
/**
 * @package     Finnern\Component\Lang4dev\Administrator\Helper
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Finnern\Component\Lang4dev\Administrator\Helper;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Language\Text;

// no direct access
\defined('_JEXEC') or die;

// https://www.php.net/manual/de/simplexml.examples-basic.php

class manifestData
{
	public $prjXmlFilePath = '';
	public $prjXmlPathFilename = '';

	// local development folder or installed component
	public $isInstalled = false;

	protected $manifest = false; // XML: false or SimpleXMLElement

	/**
	 * @since __BUMP_VERSION__
	 */
	public function __construct($prjXmlPathFilename = '')
	{
		$this->prjXmlPathFilename = $prjXmlPathFilename;
		$this->prjXmlFilePath     = ""; // dirname($prjXmlPathFilename);

		// filename given
		if ($prjXmlPathFilename != '')
		{

			$this->readManifestData();

		}

	}

	public function readManifestData($prjXmlPathFilename = '')
	{
		$isValidXml = false;

		// use new file
		if ($prjXmlPathFilename != '')
		{
			$this->prjXmlPathFilename = $prjXmlPathFilename;
			$this->prjXmlFilePath     = dirname($prjXmlPathFilename);

			// ToDo: clear old data
		}
		else
		{

			// use given path name
			$prjXmlPathFilename = $this->prjXmlPathFilename;
		}

		try
		{
			// file exists
			if (File::exists($prjXmlPathFilename))
			{
				//// keep as alternative example, used in RSG" installer . Can't remeber why simplexml_load_file was not used
				//$context = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
				//$this->manifest = $xml = file_get_contents($prjXmlPathFilename, false, $context);

				// Read the file to see if it's a valid component XML file
				$this->manifest = simplexml_load_file($prjXmlPathFilename);

				// error reading ?
				if (!empty($this->manifest))
				{
					$isValidXml = true;
				}
				else
				{
					$OutTxt = Text::_('COM_LANG4DEV_FILE_IS_NOT_AN_XML_DOCUMENT' . ': ' . $prjXmlPathFilename);
					$app    = Factory::getApplication();
					$app->enqueueMessage($OutTxt, 'error');
				}

				if (str_starts_with ($prjXmlPathFilename, JPATH_ROOT))
				{
					$this->isInstalled = true;
				} else {
					$this->isInstalled = false;
				}
			}
			else
			{
				$OutTxt = Text::_('COM_LANG4DEV_FILE_DOES_NOR_EXIST' . ': ' . $prjXmlPathFilename);
				$app    = Factory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');
			}

		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing readManifestData: "' . $prjXmlPathFilename . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $isValidXml;
	}

	// info cast to string / int .. when using it (otherwise array is returned)
	public function get ($name, $default) {

//		return isset($this->manifest->$name) ? $this->manifest->$name : $default;
		$result = $this->manifest->$name;
		// return isset($this->manifest->$name) ? $this->manifest->$name : $default;
		return $result;
	}

    // return null on wrong path
	public function getByPath ($names, $default) {
		$result = $default;

		if ( ! is_array($names)) {
			$name =  array ($names);
		}

        $base = $this->manifest;
		foreach ($names as $name) {

            $base = isset($this->manifest->$name) ? $this->manifest->$name : null;

            if ($base == null) {
                break;
            }
		}

        if ($base != null) {
            $result = $base;
        }

		return $result;
	}

	public function getSriptFile () { return (string) $this->get('scriptfile', '');}
	public function getName ()      { return (string) $this->get('name', '');}

	// info cast to string / int .. when using it (otherwise array is returned)
	public function getXml ($name) {

		return isset($this->manifest->$name) ? $this->manifest->$name : null;
	}





//	protected function loadManifestFromData(\SimpleXMLElement $xml)
//	{
//		$test              = new stdClass();
//		$test->name        = (string) $xml->name;
//		$test->packagename = (string) $xml->packagename;
//		$test->update      = (string) $xml->update;
//		$test->authorurl   = (string) $xml->authorUrl;
//		$test->author      = (string) $xml->author;
//		$test->authoremail = (string) $xml->authorEmail;
//		$test->description = (string) $xml->description;
//		$test->packager    = (string) $xml->packager;
//		$test->packagerurl = (string) $xml->packagerurl;
//		$test->scriptfile  = (string) $xml->scriptfile;
//		$test->version     = (string) $xml->version;
//
////		if (isset($xml->files->file) && \count($xml->files->file)) {
////			foreach ($xml->files->file as $file) {}
////		}
//
////		// Handle cases where package contains folders
////		if (isset($xml->files->folder) && \count($xml->files->folder))
////		{
////			foreach ($xml->files->folder as $folder) {}
////		}
//	}
//
//	/**
//	 * Apply manifest data from a \SimpleXMLElement to the object.
//	 *
//	 * @param   \SimpleXMLElement  $xml  Data to load
//	 *
//	 * @return  void
//	 *
//	 * @since   3.1
//	 */
//	protected function loadManifestFromData2(\SimpleXMLElement $xml)
//	{
//		$test               = new stdClass();
//		$test->name         = (string) $xml->name;
//		$test->libraryname  = (string) $xml->libraryname;
//		$test->version      = (string) $xml->version;
//		$test->description  = (string) $xml->description;
//		$test->creationdate = (string) $xml->creationDate;
//		$test->author       = (string) $xml->author;
//		$test->authoremail  = (string) $xml->authorEmail;
//		$test->authorurl    = (string) $xml->authorUrl;
//		$test->packager     = (string) $xml->packager;
//		$test->packagerurl  = (string) $xml->packagerurl;
//		$test->update       = (string) $xml->update;
//
//		if (isset($xml->files) && isset($xml->files->file) && \count($xml->files->file))
//		{
//			foreach ($xml->files->file as $file)
//			{
//				$test->filelist[] = (string) $file;
//			}
//		}
//	}

	/**
	 * public function subProjectsByManifest ($oSubPrjPath){
	 *
	 * $subProjects = [];
	 *
	 * // ToDo: create small manifest class and extract all necessary infos
	 *
	 *
	 * //---
	 * $manifestPath = $oSubPrjPath->getRootManifestPath ();
	 * if (file_exists ($manifestPath))
	 * {
	 *
	 * // content of file
	 * $context = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
	 * $xml     = file_get_contents($manifestPath, false, $context);
	 *
	 * // Data is valid
	 * if ($xml)
	 * {
	 * //--- read xml to json ---------------------------------------------------
	 *
	 * $manifestByXml = simplexml_load_string($xml);
	 *
	 * if (!empty ($manifestByXml))
	 * {
	 * //Encode the SimpleXMLElement object into a JSON string.
	 * $jsonString = json_encode($manifestByXml);
	 * //Convert it back into an associative array
	 * $jsonArray = json_decode($jsonString, true);
	 *
	 * }
	 * }
	 * }
	 *
	 * return $subProjects;
	 * }
	 * /**/


	public function __toTextItem ($name='')
	{
		return $name . '="' . $this->get($name, '') . '"';
	}

	public function __toText () {

		$lines = [];

		$lines[] = $this->__toTextItem('name');

		//$test->name         = (string) $xml->name;

		$lines[] = $this->__toTextItem('author');
		$lines[] = $this->__toTextItem('authorEmail');
		$lines[] = $this->__toTextItem('authorUrl');
		$lines[] = $this->__toTextItem('creationDate');
		$lines[] = $this->__toTextItem('description');
		$lines[] = $this->__toTextItem('libraryname');
		$lines[] = $this->__toTextItem('packagename');
		$lines[] = $this->__toTextItem('packager');
		$lines[] = $this->__toTextItem('packagerurl');
		$lines[] = $this->__toTextItem('scriptfile');
		$lines[] = $this->__toTextItem('update');
		$lines[] = $this->__toTextItem('version');



		$lines[] = '';
		if ($this->isInstalled) {
			$lines[] = '( Manifest is within joomla ) ';
		} else {
			$lines[] = '( Manifest on dwevelopment path ) ';
		}
		$lines[] = '';


		return $lines;
	}




} // class




