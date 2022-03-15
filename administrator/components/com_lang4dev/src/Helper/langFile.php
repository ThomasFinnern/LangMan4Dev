<?php
/**
 * This class collects lines of a joomla language file for read/write/update
 * It keeps references to empty lines and comments and the order of lines
 *
 *
 * @version
 * @package       Lang4dev
 * @copyright (C) 2022-2022 Lang4dev Team
 * @license
*/


namespace Finnern\Component\Lang4dev\Administrator\Helper;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\Folder;

use Finnern\Component\Lang4dev\Administrator\Helper\langTranslation;

// no direct access
\defined('_JEXEC') or die;

/**
* Collect all translation files of one folder (existing) - write
* The files uses is limitet as *.ini are not useful
*
* @package Lang4dev
*/
class langFile
{
	public $langPathFileName = 'd:/xampp/htdocs/joomla4x/administrator/components/com_lang4dev/language/en-GB/com_lang4dev.ini';

	public $header = [];  # Start comments on translation file
	public $translations = [];  # All translations
	public $surplusTranslations = [];
	public $langId = 'en-GB'; #'en-GB'  # lang ID
	public $isSystType = False;  # lang file type (normal/sys)

	/**
	 * @since __BUMP_VERSION__
	 */
	public function __construct($langPathFileName = '')
	{
		$this->langPathFileName = $langPathFileName;

		$this->clear();

		# load file if exists
		if (strlen($langPathFileName))
		{
			$this->assignFileContent($langPathFileName);
		}
	}
	public function clear () {

		$this->header = [];  # Start comments on translation file
        $this->translations = new \object ();  # All translations
        $this->surplusTranslations = new \object ();
        $this->langId = '????'; #'en-GB'  # lang ID
        $this->isSystType = False;  # lang file type (normal/sys)
	}

	public function assignFileContent($filePath = '')
	{

		// ToDo: try catch ...

		if (!is_file($filePath))
		{

			//--- path does not exist -------------------------------

			$OutTxt = 'Warning: langFile.assignFileContent: File does not exist "' . $filePath . '"<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'warning');

			return;
		}

		$this->clear();

		// Keep first comments as header

		$isHeaderActive = true;

		# init new item
		$nextItem = langTranslation();

		$lines = file($filePath);

		// all lines
		foreach ($lines as $lineIdx => $line)
		{
			$line = trim($line);

			// Inside header
			if ($isHeaderActive)
			{
				# Comment or empty line
				if (str_starts_with($line, ';') || strlen($line) < 1)
				{
					$this->header [] = $line;
				}
				else
				{
					# first item line
					$isHeaderActive = false;
				}
			}
			else
			{
				//--- content lines -----------------------------

				// Comment or empty line
				if (str_starts_with($line, ';') || strlen($line) < 1)
				{
					// ? ToDo: surplus comment needed/helpful ?
					// see *.py project
					//  surplus_text = '; surplus / obsolete translation'
					$nextItem->commentBefore [] = $line;
				}
				else
				{
					//--- translation split --------------------------------------

					[$pName, $pTranslation] = explode('=', $line, 1);
					$transId                = trim($pName);
					$translationPart = trim($pTranslation);

					// Extract translation between double quotas
					// preg_match:
					preg_match("/(?:(?:\"(?:\\\\\"|[^\"])+\")|(?:'(?:\\\'|[^'])+'))/is",
						$translationPart,$match);
					if (!empty ($match)) {
						$nextItem->translationText = $match[0];	
					}

					//--- comment behind -----------------------------------------
					
					$Length = strlen ($nextItem->translationText) + 2; // 2*" + ' '
					$rest = trim (substring ($translationPart, $Length));

					if ($idx = strstr ($rest, ';')) {
						$commentBehind = trim (substring ($rest, $idx+1));
						$nextItem->commentBehind = $commentBehind;
					}

					//---  ----------------------------------------


					# Is new element
					if (!in_array($transId, $this->translations))
					{
						// todo: own class, save with line as id for telling lines od double entries
						$this->translations[$transId] = $nextItem;
					}
					else
					{
						// Element does already exist in file

						// Same same ?
						if ($this->translations[$transId]->translationText == $nextItem->translationText)
						{
							$logText = "Existing element found in Line " . $lineIdx . ": " . $transId
								. " = " . $this->translations[$transId];
						}
						else
						{
							// Differernt
							$logText = "Existing mismatching element found in Line " . $lineIdx . ":\r\n"
								. "1st: " . $transId . " = " . $this->translations[$transId]->translationText
								. "2nd: " . $transId . " = " . $nextItem->translationText;
						}

						$app = Factory::getApplication();
						$app->enqueueMessage($logText, 'error');

						# init next item
						$nextItem = langTranslation();

					}
				}
			} // content

		} // all lines
		// ToDo: try catch ...

		return;
	}

	// public function getTranslation ($transId) {}
	// public function setTranslation ($transId, $translation) {}

	// public function translationsToFile($newFileName="") {}
	// public function collectedTranslationLines() {}
	// public function collectedObsoleteLines() {}





} // class