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
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;

use Finnern\Component\Lang4dev\Administrator\Helper\langTranslation;

// no direct access
\defined('_JEXEC') or die;

/**
* Collect all translation files of one folder (existing) - write
* The files uses is limitet as *.ini are not useful
*
 * ToDo: ? can we distinguish betweenheader and comment to first item ?
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
        $this->translations = [];  # All translations
        $this->surplusTranslations = [];
        $this->langId = '????'; #'en-GB'  # lang ID
        $this->isSystType = False;  # lang file type (normal/sys)
	}

	public function assignFileContent($filePath = '')
	{

		// ToDo: try catch ...

		if ( ! empty ($filePath))
		{
			// ToDo: do we want to assign a different  file content or do we want to restart with new files
			$this->langPathFileName = $filePath;
		}
		else
		{
			$filePath = $this->langPathFileName;
		}

		if ( ! is_file($filePath))
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
		$nextItem = new langTranslation();

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
					continue;
				}
				else
				{
					# first item line
					$isHeaderActive = false;
				}
			}

			//--- content lines -----------------------------

			// Comment or empty line
			if (str_starts_with($line, ';') || strlen($line) < 1)
			{
				// ? ToDo: surplus comment needed/helpful ?
				// see *.py project
				//  surplus_text = '; surplus / obsolete translation'
				$nextItem->commentsBefore [] = $line;
			}
			else
			{
				//--- translation split --------------------------------------

				[$pName, $pTranslation] = explode('=', $line, 2);

				$nextItem->name = $transId = trim($pName);

				$translationPart = trim($pTranslation);

				// Extract translation between double quotas
				// preg_match:
				preg_match("/(?:(?:\"(?:\\\\\"|[^\"])+\")|(?:'(?:\\\'|[^'])+'))/is",
					$translationPart,$match);
				if (!empty ($match)) {
					$nextItem->translationText = substr($match[0], 1, -1);
				}

				//--- comment behind -----------------------------------------

				$Length = strlen ($nextItem->translationText) + 2; // 2*" + ' '
				$rest = trim (substr ($translationPart, $Length));

				$idx = strpos ($rest, ';');
				if ($idx !== false) {
					$commentBehind = trim (substr ($rest, $idx +1));
					$nextItem->commentBehind = $commentBehind;
				}

				//---  ----------------------------------------


				# Is new element
				if ( ! in_array($transId, $this->translations))
				{
					// todo: own class, save with line as id for telling lines od double entries
					$this->translations[$transId] = $nextItem;

					# init next item
					$nextItem = new langTranslation();
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
					$nextItem = new langTranslation();
				}
			}  // content


		} // all lines
		// ToDo: try catch ...

		return;
	}

	public function translationIdExists ($transId) {

		$IsExisting = false;

		if ( ! empty ($this->translations[$transId])) {
			$IsExisting = true;
		}

		return $IsExisting;
	}


	public function getTranslation ($transId) {

		$translation = new langTranslation();

		if ( ! empty ($this->translations[$transId])) {
			$translation = $this->translations[$transId];
		}

		return $translation;
	}

	public function setTranslation ($transId, $translation)
	{
		// ToDo: try catch
		if (!empty ($translation) && !empty ($translation->transId))
		{
			$transId = $translation->transId;

			$this->translations[$transId] = $translation;

		}
	}

	public function translationsToFile($filePath="", $doBackup=false) {

		$isSaved = false;

		if (empty ($filePath))
		{
			$filePath = $this->langPathFileName;
		}

		try {

			// backup ?
			if ($doBackup) {
				//
				File::copy($filePath , File::stripExt($filePath) . '.bak');
			}

			$fileLines = $this->collectedTranslationLines();
			File::write($filePath, $fileLines);


			// ToDo: Check surplus (obsolete) translations to append (see *.py)

		}
		catch (\RuntimeException $e)
		{
			$OutTxt = 'Error executing translationsToFile: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $isSaved;
	}

	// ToDo: Set var in interface for double entries -> call function ? if (! doClean) ... ?
	public function collectedTranslationLines($isWriteEmptyTranslations = false) {

		$collectedLines = [];
		try {

			// header
			foreach ($this->header as $line)
			{
				$collectedLines [] = $line;
			}

			// translation lines
            $idx = 0;
            // check each line for existing translation
            foreach ($this->translations as $transId => $translation)
            {
	            $idx ++;

                // pre comment lines
                foreach ($translation->commentsBefore as $commentBefore)
                {
	                $collectedLines [] = $commentBefore;
                }

                //--- translation -----------------------

                $translationText = $translation->translationText;
                $line = $transId . '="' . $translationText . '"';

                //--- comment behind --------------------

	            $commentBehind = $translation->commentBehind;
				if (strlen ($commentBehind) > 0) {
					$line .= ' ; ' . $commentBehind;
				}


                // write existing translation
                if (strlen($line) > 0)
                {
	                $collectedLines[] = $line;
                }
                else
                {
	                if ($isWriteEmptyTranslations)
	                {
		                $collectedLines[] = $line;
	                }
                }
	        }


		}
		catch (\RuntimeException $e)
		{
			$OutTxt = 'Error executing collectedTranslationLines: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return implode("\n", $collectedLines);
	}


	// ToDo: public function collectedObsoleteLines() {}
	// ToDo: public function removeDoubleItems() {}





} // class