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
	public $adminLangFilePaths = [];

	public $adminPathOnDevelopment = "";
	public $sitePathOnDevelopment = "";

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
	 * @throws \Exception
	 * @since version
	 */
	public function readManifestData($prjXmlPathFilename = '')
	{
		$isValidXml = parent::readManifestData($prjXmlPathFilename);

		try
		{
			if ($isValidXml)
			{

				$this->langFileOrigins();

			}

		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing readManifestData: "' . $prjXmlPathFilename . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $isValidXml;
	}
	/**
	 * example from joomla
	 *
	 *
	 * public function test()
	 * {
	 *
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
	 * }
	 * /**/

	/**
	 * @param $isOnServer bool
	 *                    OnServer true:  return path on server (base path when not local)
	 *                    OnServer false: return path on installation
	 *
	 *
	 * @since version
	 */
	public function langFileOrigins() // $isLangFilesOnServer=true
	{
		// defined by folder language in xml

		$this->isLangAtStdJoomla  = false;
		$this->stdLangFilePaths   = [];
		$this->adminLangFilePaths = [];

		try
		{
			$manifest = $this->manifest;

			if (!empty ($manifest))
			{
				//--- old standard -----------------------------------------------
				//<languages folder="site/com_joomgallery/languages">
				//	<language tag="en-GB">en-GB/com_joomgallery.ini</language>
				//</languages>

				$stdLanguages = $this->get('languages', []);
				if (count($stdLanguages) > 0)
				{
					// lang files path will be defined in XML and copied to joomla standard path not component
					$this->isLangAtStdJoomla = true;

					//--- collect files from installation ------------------------------

					$stdPath = $stdLanguages['folder'];

					foreach ($stdLanguages->language as $language)
					{

						$tag             = (string) $language['tag'];
						$subFolder[$tag] = (string) $language; // $language[0]

						$this->stdLangFilePaths[] = $subFolder;
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
				if (count($stdLanguages) > 0)
				{

					// lang files path will be defined in XML anf copied to joomla standard path
					$this->isLangAtStdJoomla = true;

					//--- collect files from installation ------------------------------

					$stdPath = $stdLanguages['folder'];

					foreach ($stdLanguages->language as $language)
					{

						$tag             = (string) $language['tag'];
						$subFolder[$tag] = (string) $language; // $language[0]

						$this->adminLangFilePaths[] = $subFolder;
					}
				}

				/**
				 * // lang files will be inside component path
				 * if ($this->isLangPathInXml) {
				 *
				 * // test for folder language in standard and administrator
				 *
				 *
				 *
				 * }
				 * /**/

			}
		}
		catch (RuntimeException $e)
		{
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
//		$this->isLangAtStdJoomla  = false;
//
//		try
//		{
//			// site
//			$stdLanguages = $this->get('languages', []);
//			if (count($stdLanguages) > 0)
//			{
//				// lang files path will be defined in XML and copied to joomla standard path not component
//				$this->isLangAtStdJoomla = true;
//
//			}
//
//			// administration
//			$administration = $this->get('administration', []);
//			$stdLanguages   = $administration->languages;
//			if (count($stdLanguages) > 0)
//			{
//				// lang files path will be defined in XML anf copied to joomla standard path
//				$this->isLangAtStdJoomla = true;
//
//			}
//		}
//		catch (\RuntimeException $e)
//		{
//			$OutTxt = '';
//			$OutTxt .= 'Error executing langFileOrigins: ' . '"<br>';
//			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';
//
//			$app = Factory::getApplication();
//			$app->enqueueMessage($OutTxt, 'error');
//		}

		$isLangAtStdJoomla = $this->langFileOrigins();
		$isLangAtStdJoomla = $this->isLangAtStdJoomla;

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

		if (count($this->stdLangFilePaths) > 0)
		{
			$lines[] = '[site lang files]';
			foreach ($this->stdLangFilePaths as $idx => $langFilePath)
			{
				$lines[] = ' * [' . $idx . '] ' . json_encode($langFilePath);
			}
		}

		if (count($this->adminLangFilePaths) > 0)
		{
			$lines[] = '[admin lang files]';
			foreach ($this->adminLangFilePaths as $idx => $langFilePath)
			{
				$lines[] = ' * [' . $idx . '] ' . json_encode($langFilePath);
			}
		}

		return $lines;
	}

} // class

