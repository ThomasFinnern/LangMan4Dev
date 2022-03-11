<?php
/**
 * This class handles version management for Lang4dev
 *
 * @version
 * @package       Lang4dev
 * @copyright (C) 2022-2022 Lang4dev Team
 * @license
 */

namespace Finnern\Component\Lang4dev\Administrator\Helper;

use JFolder;
use Joomla\CMS\Factory;

use Finnern\Component\Lang4dev\Administrator\Helper\langItems;
use Finnern\Component\Lang4dev\Administrator\Helper\langItem;
use Joomla\CMS\Filesystem\Folder;

// no direct access
\defined('_JEXEC') or die;

/**
 * Search language constants (Items) in given folders
 * The files uses is limitet as *.ini are not useful
 *
 * @package Lang4dev
 */
class searchLangItems
{
	public $fileTypes = 'php, xml';
	public $componentPrefix = '';
	public $searchPaths = array();
	public $langItems;
	protected $name = 'Lang4dev';

	/**
	 * @since __BUMP_VERSION__
	 */
	public function __construct($searchPaths = array(), $componentPrefix = 'COM_LANG4DEV_')
	{
		// ToDO: check for uppercase and trailing '_'

		$this->langItems       = new langItems();
		$this->componentPrefix = $componentPrefix;

		// if ( !empty ($searchPaths)) ... ???
		$this->searchPaths = $searchPaths;

	}

	// Attention the removing of comments may lead to wrong
	// Index in line for found '*/'
	public function findAllTranslationIds()
	{
		// ToDo: log $componentPrefix, $searchPaths

		$langItems = new langItems();

		try
		{
			/*--------------------------------------------------------------
			checks
			--------------------------------------------------------------*/

			//--- prefix --------------------------------------------------

			//--- paths given --------------------------------------------------

			//--- paths exist --------------------------------------------------

			//--- All paths --------------------------------------------------

			foreach ($this->searchPaths as $searchPath)
			{
				//--- paths exist --------------------------------------------------

				$isPathsExisting = is_dir($searchPath);

				if ($isPathsExisting)
				{
					//--- search in path -------------------------------

					$this->searchLangIdsInPath($searchPath, $this->componentPrefix);
				}
				else
				{
					//--- path does not exist -------------------------------

					$OutTxt = 'Error: findAllTranslationIds: Path  does not exist "' . $searchPath . '"<br>';

					$app = Factory::getApplication();
					$app->enqueueMessage($OutTxt, 'warning');

				}
			}

		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing findAllTranslationIds: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $langItems; // ? a lot to return ?
	}

	public function searchLangIdsInPath($searchPath)
	{
		try
		{
			#--- All files (*.php, *.xml) in folder -------------------------------------

			foreach ($this->filesInDir($searchPath) as $fileName)
			{
				$filePath = $searchPath . DIRECTORY_SEPARATOR . $fileName;

				$ext = pathinfo($filePath, PATHINFO_EXTENSION);
				if ($ext == 'php')
				{
					$this->searchLangIdsInFilePHP($fileName, $searchPath);
				}
				if ($ext == 'xml')
				{
					$this->searchLangIdsInFileXML($fileName, $searchPath);
				}
			}

			#--- All sub folders in folder -------------------------------------

			foreach ($this->folderInDir($searchPath) as $folderName)
			{
				$subFolder = $searchPath . DIRECTORY_SEPARATOR . $folderName;
				$this->searchLangIdsInPath($subFolder);
			}

		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing findAllTranslationIds: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}
	}

	public function filesInDir($folder)
	{
		$files = [];

		try
		{
			// php, xml
			//$regEx = '\.xml$|\.html$';
			$regEx = '\.php|\.xml$';
			$files = Folder::files($folder, $regE);
		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing findAllTranslationIds: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessaxge() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $files;
	}

	// Multiple items in one line

	public function searchLangIdsInFilePHP($fileName, $path)
	{
		$isInComment = false;

		try
		{
			$lineIdx = 0;

			// Read all lines
			$filePath = $path . DIRECTORY_SEPARATOR . $fileName;

			$lines = file($filePath);

			// content found
			foreach ($lines as $line)
			{
				//--- remove comments --------------

				$bareLine = $this->removeCommentPHP($line, $isInComment);

				//--- find items --------------

				if (strlen($bareLine) > 0)
				{

					$items = $this->searchLangIdsInLinePHP($bareLine);

					//--- add items
					foreach ($items as $item)
					{
						$item->file    = $fileName;
						$item->path    = $path;
						$item->lineIdx = $lineIdx;

						$this->langItems->addItem($item);
					}
				}

			}
		}
		catch (\RuntimeException $e)
		{
			$OutTxt = 'Error executing findAllTranslationIds: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

	}

	public function removeCommentPHP($line, &$isInComment)
	{
		$bareLine = $line;

		try
		{
			// No inside a '/*' comment
			if ( ! $isInComment)
			{
				//--- check for comments ---------------------------------------

				$doubleSlash = '//';
				$slashAsterisk = '/*';

				$doubleSlashIdx = strpos ($line, $doubleSlash);
				$slashAsteriskIdx = strpos ($line, $slashAsterisk);

				// comment exists, keep start of string
				if ($doubleSlashIdx != false || $slashAsteriskIdx != false)
				{
					if ($doubleSlashIdx != false && $slashAsteriskIdx == false) {
						$bareLine =  strstr ($line, $doubleSlash, true);
					}
					else
					{
						if ($doubleSlashIdx == false && $slashAsteriskIdx != false)
						{
							$bareLine = strstr($line, $slashAsterisk, true);
							$isInComment = true;
						}
						else
						{
							//--- both found ---------------------------------

							// which one is first
							if ($doubleSlashIdx < $slashAsteriskIdx) {
								$bareLine =  strstr ($line, $doubleSlash, true);
							} else {
								$bareLine = strstr($line, $slashAsterisk, true);
								$isInComment = true;
							}

						}

					}


				} // No comment indicator


			} else {
				//--- Inside a '/*' comment

				$bareLine = '';

				$asteriskSlash = '*/';
				$asteriskSlashIdx = strpos ($line, $asteriskSlash);

				// end found ?
				if ($asteriskSlashIdx != false)
				{
					// Keep end of string
					$bareLine = strstr($line, $asteriskSlash);

					// handle rest of string
					$isInComment = false;
					$bareLine = $this->removeCommentPHP($bareLine, $isInComment);
				}
			}

		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing findAllTranslationIds: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $bareLine;
	}

	// Multiple items in one line
	// Must be cleaned from comments first
	public function searchLangIdsInLinePHP($line)
	{
		$items = [];

		$matches = [];

		try
		{
			// test find all words then iterate through array
			// internet
			$searchRegex = "'~\w+(?:-\w+)*~'";
			preg_match_all($searchRegex, $line, $matches);

// https://stackoverflow.com/questions/4722007/php-preg-match-to-find-whole-words

			// Python solution
			// py$searchRegex = "\\b" + $this->componentPrefix + "\\w+";
			$searchRegex = "\b" . $this->componentPrefix . "\w+";
			$searchRegex = '/\b(\w+' . $this->componentPrefix . "\w+)\b/";
			$searchRegex = '/' . $this->componentPrefix . "\w+/";

			// test find all words then iterate through array
			preg_match_all($searchRegex, $line, $matches);
			foreach ($matches as $matchGroup) {
				$postSlug = $matchGroup[0];
				$postSlug = $postSlug;
			}

		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing findAllTranslationIds: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $items;
	}

	public function searchLangIdsInFileXML($fileName, $path)
	{
		$items = [];

		try
		{
			$lineIdx = 0;

			// Read all lines
			$filePath = $path . DIRECTORY_SEPARATOR . $fileName;

			$lines = file($filePath);

			// content found
			foreach ($lines as $line)
			{
				//--- remove comments --------------

				$bareLine = $this->removeCommentXML($line, $isInComment);

				//--- find items --------------

				if (strlen($bareLine) > 0)
				{

					$items = $this->searchLangIdsInLineXML($bareLine);

					//--- add items
					foreach ($items as $item)
					{
						$item->file    = $fileName;
						$item->path    = $path;
						$item->lineIdx = $lineIdx;

						$this->langItems . addItem($item);
					}
				}

			}
		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing findAllTranslationIds: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $items;
	}

	public function removeCommentXML($line, &$isInComment)
	{
		try
		{


		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing findAllTranslationIds: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

	}

	public function searchLangIdsInLineXML($line)
	{
		try
		{


		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing findAllTranslationIds: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

	}

	public function folderInDir($folder)
	{
		$folders = [];

		try
		{
			// ToDo: leave out 'language' folder
			// php, xml
			$folders = Folder::folders($folder);

		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing findAllTranslationIds: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $folders;
	}

}