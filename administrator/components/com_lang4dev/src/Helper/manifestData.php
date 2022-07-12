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

	private $manifest = false; // XML: false or SimpleXMLElement

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

	public function get ($name, $default) {

		return isset($this->manifest->$name) ? $this->manifest->$name : $default;
	}

	public function getSriptFile () { return $this->get('scriptfile', '');}
	public function getName ()      { return $this->get('name', '');}

	public function getXml ($name) {

		return isset($this->manifest->$name) ? $this->manifest->$name : null;
	}



	/**
	 * @param $isOnServer bool
	 *           OnServer true:  return path on server (base path when not local)
	 *           OnServer false: return path on installation
	 *
	 *
	 * @since version
	 */
	public function langFileOrigens() // $isOnServer=true
	{
		// defined by folder language in xml
		$isFilesLocal = false;
		$isFilesDefined = false;
		$stdLangFilePaths = [];
		$adminLangFilePaths = [];

		$manifest = $this->manifest;

		if (!empty ($manifest))
		{
			// lang files will be inside component
			$isFilesLocal = true; // new style expected

			//--- standard -----------------------------------------------
			//<languages folder="site/com_joomgallery/languages">
			//	<language tag="en-GB">en-GB/com_joomgallery.ini</language>
			//</languages>

			$stdLanguages = $this->get('languages', []);
			if (count ($stdLanguages) > 0) {
				// lang files will be on joomla standard path
				$isFilesLocal = false;

				//--- collect files from installation ------------------------------

				$stdPath = $stdLanguages['folder'];

				foreach ($stdLanguages->language as $language){

					$tag = (string) $language['tag'];
					$subFolder[$tag] = (string) $language; // $language[0]

					$stdLangFilePaths[] = $subFolder;
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


			$administrator =
			$stdLanguages = $this->get('administrator', []);
			if (count ($stdLanguages) > 0) {
				// lang files will be on joomla standard path
				$isFilesLocal = false;

				//--- collect files from installation ------------------------------

				$stdPath = $stdLanguages['folder'];

				foreach ($stdLanguages->language as $language){

					$tag = (string) $language['tag'];
					$subFolder[$tag] = (string) $language; // $language[0]

					$stdLangFilePaths[] = $subFolder;
				}
			}



			// lang files will be inside component path
			if ($isFilesLocal) {

				// test for folder language in standard and administrator



			}




			return [$isFilesLocal, $stdLangFilePaths];
		}

		/**
		 * // Copy language files from global folder
		 * if ($languages = $manifest->languages)
		 * {
		 * $folder        = (string) $languages->attributes()->folder;
		 * $languageFiles = $languages->language;
		 *
		 * $langTag = $languageFiles->attributes()->tag;
		 *
		 * foreach ($languageFiles as $languageFile)
		 *
		 * Folder::create($toPath . '/' . $folder . '/' . $languageFiles->attributes()->tag);
		 *
		 * foreach ($languageFiles as $languageFile)
		 * {
		 * $src = Path::clean($client->path . '/language/' . $languageFile);
		 * $dst = Path::clean($toPath . '/' . $folder . '/' . $languageFile);
		 *
		 * if (File::exists($src))
		 * {
		 * File::copy($src, $dst);
		 * }
		 * }
		 * }
		 * /**/
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

		/**
		$itemLanguages = $this->get('languages', []);
		if (count ($itemLanguages) > 0) {
			// lang files will be on joomla standard path
			$isFilesLocal = false;
		}
		else{
			// lang files will be inside component
			$isFilesLocal = true;

			// should test for folder language in standard and administrator
		}
		/**/

		[$isFilesLocal, $langFilePaths] = $this->langFileOrigens ();
		$lines[] = 'lang files ' . ($isFilesLocal ? ' inside component' : ' joomla standard folders');

		foreach ($langFilePaths as $idx => $langFilePath) {

			$lines[] = ' * [' . $idx . '] '. json_encode($langFilePath);
		}

		return $lines;
	}




} // class




