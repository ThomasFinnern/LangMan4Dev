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

use Finnern\Component\Lang4dev\Administrator\Helper\translation;
use RuntimeException;
use function defined;

// no direct access
defined('_JEXEC') or die;

/**
 * Collect all translation files of one folder (existing) - write
 * The files uses is limitet as *.ini are not useful
 *
 * ToDo: ? can we distinguish between header and comment to first item ?
 *
 * @package     Finnern\Component\Lang4dev\Administrator\Helper
 *
 * @since       version
 */
class langFile
{
	/* valid from PHP 8.1 on
	enum LineType
	{
		case none;
		case Comment;
		case Prepared;
		case NoText;
		case TransId;
	}
	/**/

	const LINE_TYPE__NONE = 0;
	const LINE_TYPE__TRANS_ID = 1;
	const LINE_TYPE__COMMENT = 2;
	const LINE_TYPE__PREPARED = 4;
	const LINE_TYPE__NO_TEXT = 5;


	public $langId = 'en-GB'; // 'en-GB'  // lang ID
	public $langPathFileName = 'd:/xampp/htdocs/joomla4x/administrator/components/com_lang4dev/language/en-GB/com_lang4dev.ini';

	public $translations = [];  // All translations
	public $translationDoubles = []; // Line to item
	public $translationSurplus = []; // TransId not in main translation
	public $header = [];  // Start comments on translation file
	public $trailer = [];  // Last lines after last translation

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
			$this->readFileContent($langPathFileName);
		}
	}

	public function clear()
	{

		$this->translations        = [];  # All translations
		$this->translationDoubles  = [];
		$this->header              = [];  # Start comments on translation file
		$this->trailer             = [];  # Trailing comments on translation file
		$this->langId              = '????'; #'en-GB'  # lang ID
		$this->isSystType          = false;  # lang file type (normal/sys)
	}

	public function readFileContent($filePath = '', $langId = 'en-GB')
    {
        $isAssigned = false;

        try {

            // ToDo: try catch ...

            $this->langId = $langId;

            if (!empty ($filePath)) {
                // ToDo: do we want to assign a different  file content or do we want to restart with new files
                $this->langPathFileName = $filePath;
            } else {
                $filePath = $this->langPathFileName;
            }

            if (!is_file($filePath)) {

                //--- path does not exist -------------------------------

                $OutTxt = 'Warning: langFile.readFileContent: File does not exist "' . $filePath . '"<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'warning');

                return;
            }
			
			$lines = file($filePath);

			$this->assignTranslationLines ($lines);

        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing readFileContent: "' . '<br>';
            $OutTxt .= 'File: "' . $filePath . '"<br>';
            $OutTxt .= 'langId: "' . $langId . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $isAssigned;
    }

		
	public function assignTranslationLines ($lines) {

		try {

			$this->clear();

            // Keep first comments as header

            $isHeaderActive = true;

            # init new item
            $nextItem = new langTranslation();

            // all lines
            foreach ($lines as $lineNr => $line) {
                $line = trim($line);
				
				$lineType = check4Linetype ($line);
				
                // Inside header
                if ($isHeaderActive) {
					
					$isCommentLine = check4CommentLine ($line);
					
                    # Comment line
					if ($lineType == self::LINE_TYPE__COMMENT) {
					
                        $this->header [] = $line;
                        continue;
                    } else {
                        # first item line or empty line
                        $isHeaderActive = false;

                        // add first empty line
                        if (strlen($line) == 0) {
                            $this->header [] = $line;
                            continue;
                        }
                    }
                }


                //--- content lines -----------------------------

                // Comment or empty line
                // if (str_starts_with($line, ';') || strlen($line) < 1) {
                if ($lineType == self::LINE_TYPE__COMMENT || $lineType == self::LINE_TYPE__NO_TEXT) {
                    $nextItem->commentsBefore [] = $line;
                } else {

		            // Remove comment indicator on prepared translations
					if ($lineType == self::LINE_TYPE__PREPARED)
					{
						$line = substr($line, 1);
						$nextItem->isPrepared = true;

					}

                    //--- translation split --------------------------------------

                    [$pName, $pTranslation] = explode('=', $line, 2);

                    $nextItem->transId = $transId = trim($pName);

                    $translationPart = trim($pTranslation);

                    // Extract translation between double quotas
                    // preg_match:
                    preg_match("/(?:(?:\"(?:\\\\\"|[^\"])+\")|(?:'(?:\\\'|[^'])+'))/is",
                        $translationPart, $match);
                    if (!empty ($match)) {
                        $nextItem->translationText = substr($match[0], 1, -1);
	                    $nextItem->lineNr = $lineNr+1;
                    }

                    //--- comment behind -----------------------------------------

                    $Length = strlen($nextItem->translationText) + 2; // 2*" + ' '
                    $rest = trim(substr($translationPart, $Length));

                    $idx = strpos($rest, ';');
                    if ($idx !== false) {
                        $commentBehind = trim(substr($rest, $idx + 1));
                        $nextItem->commentBehind = $commentBehind;
                    }

                    //---  ----------------------------------------

                    # Is new element
                    if (!in_array($transId, $this->translations)) {
                        // todo: own class, save with line as id for telling lines od double entries
                        $this->translations[$transId] = $nextItem;

                        # init next item
                        $nextItem = new langTranslation();
                    } else {
                        // Element does already exist in file

                        // Same same ?
                        if ($this->translations[$transId]->translationText == $nextItem->translationText) {
                            $logText = "Existing element found in Line " . $lineNr . ": " . $transId
                                . " = " . $this->translations[$transId];
                        } else {
                            // Different text
                            $logText = "Existing mismatching element found in Line " . $lineNr . ":\r\n"
                                . "1st: " . $transId . " = " . $this->translations[$transId]->translationText
                                . "2nd: " . $transId . " = " . $nextItem->translationText;
                        }

                        $app = Factory::getApplication();
                        $app->enqueueMessage($logText, 'warning');

                        // save double by line number
                        $this->translationDoubles [$lineNr] = $nextItem;

                        # init next item
                        $nextItem = new langTranslation();
                    }
                }  // content

            } // all lines

            //--- trailing lines -----------------------------

            if ($nextItem->lineNr == -1 && count($nextItem->commentsBefore) > 0) {

                $this->trailer = $nextItem->commentsBefore;
            }

            $isAssigned = true;

        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing detectInstallFile: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $isAssigned;
    }

	private function check4Linetype ($line=''){
		
		$lineType = self::LINE_TYPE__NONE;

		// comment or preparation line ?
		if (str_starts_with($line, ';'))
		{
			// strip further comment from behind

			$idx = strrpos($line, ';');

			if($idx >= 0) {
				$line = trim(substr($line, 0, $idx));

			}

			// check for upper case and underscore, then '=' followed by ....






		}
		
		
		return $lineType;
	}



	public function translationIdExists($transId)
	{

		$IsExisting = false;

		if (!empty ($this->translations[$transId]))
		{
			$IsExisting = true;
		}

		return $IsExisting;
	}

	public function getTranslationItem($transId)
	{

		$translation = new langTranslation();

		if (!empty ($this->translations[$transId]))
		{
			$translation = $this->translations[$transId];
		}

		return $translation;
	}

	public function setTranslationItem($translation)
	{
		// ToDo: try catch

		// ToDO: keep line number overwrite translation
		if (!empty ($translation) && !empty ($translation->transId))
		{
			$transId = $translation->transId;

			$this->translations[$transId] = $translation;

		}
	}

	/**
	 * Overwrites given tranlsation variables
	 * On $doClean the line index is kept. Any other
	 * variable is reset before standard assignment
	 * @param         $transId
	 * @param         $translationText
	 * @param   bool  $doClean
	 * @param   null  $commentsBefore
	 * @param   null  $commentBehind
	 * @param   int   $lineNr
	 *
	 *
	 * @since version
	 */
	public function setTranslationText($transId, $translationText, $doClean=true, $commentsBefore=null, $commentBehind=null, $lineNr=-1)
	{
		// ToDo: try catch

		if ( ! empty ($this->translations[$transId])) {
			$translation = $this->translations[$transId];

			if ($doClean) {
				// keep line index
				$translation->clean();
			}

			$translation->translationText = $translationText;

			if (!empty ($commentsBefore))
			{
				$translation->commentsBefore = $commentsBefore;
			}


			if (!empty ($commentBehind))
			{
				$translation->commentBehind = $commentBehind;
			}

			if (!empty ($commentsBefore))
			{
				$translation->lineNr = $lineNr;
			}


		}  else {
			$logText = 'Translation with ID: "' . $transId . '" does not exist';

			// ToDO: warning enqueue
			$app = Factory::getApplication();
			$app->enqueueMessage($logText, 'warning');

		}

	}

	/**
	 * @param   string  $filePath
	 * @param   false   $doBackup
	 *
	 * @return bool
	 *
	 * @throws \Exception
	 * @since version
	 */
	public function translationsToFile($filePath = "", $doBackup = false)
	{

		$isSaved = false;

		if (empty ($filePath))
		{
			$filePath = $this->langPathFileName;
		}

		try
		{

			// backup ?
			if ($doBackup)
			{
				// 
				File::copy($filePath, File::stripExt($filePath) . '.bak');
			}

			$linesArray = $this->translationLinesArray();
			$fileLines = implode("\n", $linesArray);

			File::write($filePath, $fileLines);

			// ToDo: Check surplus (obsolete) translations to append (see *.py)

			$isSaved = true;
		}
		catch (RuntimeException $e)
		{
			$OutTxt = 'Error executing translationsToFile: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $isSaved;
	}

	/**
	 * seperateByTransIds
	 *
	 * Joke: sort like Cinderella put the good ones in the pot, the bad ones in the crop
	 *
	 * Each input name is sorted into one of three types
	 *  * missing:    transId item is not defined in lang file 
	 *  * translated: transId item has a translation item matching in lang file 
	 *  * notUsed:    not used translations in lang file
	 *
	 * @param $TransIds
	 *
	 *
	 * @since version
	 */
	public function separateByTransIds ($TransIds) {

		$missing = [];
		$translated    = [];
		$notUsed = [];

		try {

			//--- missing and translated ------------------------------------

			foreach ($TransIds as $TransId) {

				// ID is missing
				if (empty ($this->translations[$TransId])) {
					$missing[] = $TransId;
				} else {
					// ID is translated
					$translated[] = $TransId;
				}
			}

			//--- not used in code ------------------------------------

			foreach ($this->getItemNames () as $TransId)
			{
				// ID is not used
				if ( ! in_array ($TransId, $translated)) {
					$notUsed[] = $TransId;
				}
			}

		}
		catch (\RuntimeException $e)
		{
			$OutTxt = 'Error executing ' . __CLASS__ .  '::' . __FUNCTION__  . ': "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return [$missing, $translated, $notUsed];
	}


	//
	public function alignTranslationsByMain ($mainTranslations) {
		
		$alignedTranslations = [];
		
		foreach ($mainTranslations as $mainTrans) {
		
			//--- if already exist -> use definition ------------
		
			// New line number from main
		
			// if not exist create dummy element
		
				// mark as prepared

			$alignedTranslations [] = $mainTrans;
		}
				
		$this->translations = $alignedTranslations;
	}

	// ToDo: Set var in interface for double entries -> call function ? if (! doClean) ... ?
	public function translationLinesArray($isWriteEmptyTranslations = false)
	{

		$collectedLines = [];
		try
		{

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
				$idx++;

				// pre comment lines
				foreach ($translation->commentsBefore as $commentBefore)
				{
					$collectedLines [] = $commentBefore;
				}

				//--- translation -----------------------

				$translationText = $translation->translationText;
				$line            = $transId . '="' . $translationText . '"';
				
				// do comment not translated lines (TransId exist, no text though)
				if ($translation->isPrepared) {
					$line = ";" . $line;
				}

				//--- comment behind --------------------

				$commentBehind = $translation->commentBehind;
				if (strlen($commentBehind) > 0)
				{
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

			// trailer
			foreach ($this->trailer as $line)
			{
				$collectedLines [] = $line;
			}

		}
		catch (RuntimeException $e)
		{
			$OutTxt = 'Error executing translationLinesArray: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $collectedLines;
	}

	public function getItemNames ()
	{
		$names = [];

		try {
			// ToDo: cache it for second use, reset cache after assign /read
			foreach ($this->translations as $transId => $translation)
			{
				$names [] = $transId;
			}

		}
		catch (\RuntimeException $e)
		{
			$OutTxt = 'Error executing getItemNames: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $names;
	}

	public function getDoubleItemNames ()
	{
		$names = [];

		try {

			// ToDo: cache it for second use, reset cache after assign /read
			foreach ($this->translationDoubles as $lineId => $translation)
			{
				$names [] = $translation->transId;
			}

		}
		catch (\RuntimeException $e)
		{
			$OutTxt = 'Error executing getDoubleItemNames: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $names;
	}


	public function cleanTranslations() {

		$this->removeEmptyLines();
		$this->removeDoubleItems();

	}

	public function removeDoubleItems() {

		$this->translationDoubles = [];

	}

	public function removeEmptyLines() {

		// ToDo: fill out ...

	}



	public function collectDoubles()
	{
		$doubles = [];

		$doublesNames = $this->getDoubleItemNames();


		foreach ($doublesNames as $transId) {

			// ? not needed ? $doubles [$transId] = [];
			$doubles [$transId][] = $this->translations [$transId];

			foreach ($this->translationDoubles as $lineId => $translation)
			{
				if ($translation->transId == $transId)
				{
					$doubles [$transId][] = $translation;
				}
			}
		}

		return $doubles;
	}

    public function __toText () {

        $lines = [];

        $lines [] = 'langId = "' . $this->langId . '"';
        $lines [] = 'langPathFileName = "' . $this->langPathFileName . '"';
        $lines [] = '$isSystType = "' . $this->isSystType . '"';

        $lines [] = '--- $header ------------------------';
        foreach ($this->header as $headerLine) {
            $lines [] = $headerLine;
        }

        $lines [] = '--- $translations ------------------------';
        foreach ($this->translations as $transId => $translation) {
            $lines [] = $transId . '="' .  $translation . '"';
        }

        $lines [] = '--- $translations doubles ------------------------';
        foreach ($this->translationDoubles as $transId => $translation) {
            $lines [] = $transId . '="' .  $translation . '"';
        }

        $lines [] = '--- $trailer ------------------------';
        foreach ($this->trailer as $trailerLine) {
            $lines [] = $trailerLine;
        }

        return $lines;
    }




} // class
