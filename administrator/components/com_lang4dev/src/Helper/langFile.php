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
//use Joomla\CMS\Filesystem\Folder;

use Finnern\Component\Lang4dev\Administrator\Helper\langTranslation;
use Finnern\Component\Lang4dev\Administrator\Helper\langPathFileName;

use Joomla\CMS\Filesystem\Folder;
use RuntimeException;
// use function defined;

// no direct access
defined('_JEXEC') or die;

// ToDo: put extract of translation (id, text) into own class which may be exchanged

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
class langFile extends langPathFileName
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
	const LINE_TYPE__INVALID_ID = 5;


// base class -> public $langId = 'en-GB'; // 'en-GB'  // lang ID
// base class -> public $langPathFileName = 'd:/xampp/htdocs/joomla4x/administrator/components/com_lang4dev/language/en-GB/com_lang4dev.ini';
// base class -> 	public $isSysFile = false;  # lang file type (normal/sys)

	public $translations = [];  // All translations
	public $translationDoubles = []; // Same langId a sceond time ? Maybe with different text ?
	public $translationSurplus = []; // TransId not in main translation
	public $header = [];  // Start comments on translation file
	public $trailer = [];  // Last lines after last translation

	/**
	 * @since __BUMP_VERSION__
	 */
	public function __construct($langPathFileName = '')
	{
		parent::__construct ($langPathFileName);

		# load file if exists
		if ($this->isValidPathFileName($langPathFileName, true))
		{
			$this->readFileContent($langPathFileName);
		}
	}

	public function clear()
	{
		// parent::clear();  Name is still needed

		$this->translations        = [];  # All translations
		$this->translationDoubles  = [];
		$this->header              = [];  # Start comments on translation file
		$this->trailer             = [];  # Trailing comments on translation file
	}

	public function readFileContent($filePath = '')
    {
        $isAssigned = false;

        try {

            if ( ! empty ($filePath)) {
                // ToDo: do we want to assign a different  file content or do we want to restart with new files
                $this->langPathFileName = $filePath;
            } else {
                $filePath = $this->langPathFileName;
            }

            if ( ! is_file($filePath)) {

                //--- path does not exist -------------------------------

                $OutTxt = 'Warning: langFile.readFileContent: File does not exist "' . $filePath . '"<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'warning');

            } else
            {
	            $lines = file($filePath);

	            $this->assignTranslationLines($lines);

	            $isAssigned = true;
            }

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
				
				$lineType = $this->check4LineType ($line);
				
                // Inside header
                if ($isHeaderActive) {
					
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
                if ($lineType == self::LINE_TYPE__COMMENT || $lineType == self::LINE_TYPE__INVALID_ID) {
                    $nextItem->commentsBefore [] = $line;
                } else {

		            // Remove comment indicator on prepared translations
					if ($lineType == self::LINE_TYPE__PREPARED)
					{
						$line = substr($line, 1);
						$nextItem->isPrepared = true;

					}

                    //--- translation split --------------------------------------

	                [$isValid, $transId, $transText, $commentBehind] = $this->extractTranslation ($line);

	                if ($isValid)
	                {
		                $nextItem->transId = $transId;
		                $nextItem->translationText = $transText;
		                $nextItem->commentBehind = $commentBehind;
		                $nextItem->lineNr = $lineNr;
	                }

                    //--- save as new or existing ----------------------------------------

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
            $OutTxt .= 'Error executing assignTranslationLines: ' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $isAssigned;
    }

	private function check4LineType ($line=''){
		
		$lineType = self::LINE_TYPE__NONE;

		// comment or preparation line ?
		if (str_starts_with($line, ';'))
		{
			// strip further comment from behind

			$idx = strrpos($line, ';');

			if($idx >= 0) {
				
				$line = trim(substr($line, $idx+1));

			}

			// determine if valid translation is existing behind coment character 
			[$isValid, $transId, $transText] = $this->extractTranslation ($line);

			if ($isValid) {
				$lineType = self::LINE_TYPE__PREPARED;
			} else {
				$lineType = self::LINE_TYPE__COMMENT;
			}

		} else {
			//--- no comment line ----------------------------------------------
			
			// determine if valid translation is existing  
			[$isValid, $transId, $transText] = $this->extractTranslation ($line);

			if ($isValid) {
				$lineType = self::LINE_TYPE__TRANS_ID;

			} else {
				// not defined line ? empty or invlaid text
				$lineType = self::LINE_TYPE__INVALID_ID;
			}
			
		}
		
		return $lineType;
	}

// needs line to have removed pre text and does not cara about post comment in line  
	public function extractTranslation  ($line){

		$isValid = false;
		$transId = "";
		$transText = "";
		$commentBehind = "";

		//--- translation split --------------------------------------
		try
		{
//			if(strstr($line, '=')) {}
			[$pName, $pTranslation] = array_pad(explode('=', $line, 2), 2,'');

			if (empty ($pTranslation))
			{
				$tran = $pName;
			}

			if ( ! empty ($pTranslation))
			{
				$transId         = trim($pName);
				$translationPart = trim($pTranslation);

				// Extract translation between double quotas
				// preg_match:
				preg_match("/(?:(?:\"(?:\\\\\"|[^\"])+\")|(?:'(?:\\\'|[^'])+'))/is",
					$translationPart, $match);
				if (!empty ($match))
				{
					$transText = trim(substr($match[0], 1, -1));
				}

				$isValid = $this->checkTransId($transId);
				$isValid &= $this->checkTransText($transText);

				if ($isValid)
				{

					$length = strlen($transText) + 2; // 2*" + ' '
					$behind = trim(substr($translationPart, $length));

					$idx = strpos($behind, ';');
					if ($idx !== false)
					{
						$commentBehind = trim(substr($behind, $idx + 1));
					}

				}
			}

		} catch (\RuntimeException $e) {
			$OutTxt = '';
			$OutTxt .= 'Error executing extractTranslation line part: "' . $line . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return [$isValid, $transId, $transText, $commentBehind];
	}

	private function checkTransId(string $transId)
	{
		$iSValid = false;

		try {
		// only upper case and underscores allowed

		$iSValid = true;

		} catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing checkTransId: "' . $transId . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $iSValid;
	}

	private function checkTransText(string $transText)
	{
		$iSValid = false;

		try {
		// match first " with last ?
		// allow \" in between

		$iSValid = true;

		} catch (\RuntimeException $e) {
			$OutTxt = '';
			$OutTxt .= 'Error executing checkTransText: "' . $transText . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $iSValid;
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
	public function writeToFile($filePath = "", $doBackup = false)
	{

		$isSaved = false;

		if (empty ($filePath))
		{
			$filePath = $this->langPathFileName;
		}

		try
		{

			// not on dos:$filePath = Folder::makeSafe($filePath);

			// backup ?
			if ($doBackup)
			{
				//
				if (File::exists($filePath))
				{
					File::copy($filePath, File::stripExt($filePath) . '.bak');
				}
			}

			// all lines standard translation
			$linesArray = $this->translationLinesArray();

			// ToDo: Check surplus (obsolete) translations to append (see *.py)
			// call translationLines?All?Array instead

			// prepare one string
			$fileLines = implode("\n", $linesArray);

			// write to file
			$isSaved = File::write($filePath, $fileLines);

		}
		catch (RuntimeException $e)
		{
			$OutTxt = 'Error executing writeToFile: "' . '<br>';
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

			$transId = $mainTrans->transId;

			// if not exist create dummy element
			if(empty ($this->translations [$transId]))
			{
				//--- create prepared item -------------------------

				$translationText = '' ;
				$commentsBefore = $this->prepareCommentsBefore ($mainTrans->commentsBefore);
				$commentBehind = $this->prepareComment($mainTrans->commentBehind);
				$lineNr = $mainTrans->lineNr;
				// mark as prepared
				$isPrepared = true;

				$nextItem = new langTranslation($transId,  $translationText,  $commentsBefore,
					$commentBehind, $lineNr, $isPrepared);

				$alignedTranslations [$transId] = $nextItem;

			} else {
				//--- Item exist, use it ------------------------

				$translation = $this->translations [$transId];

				// New line number from main
				$translation->lineNr = $mainTrans->lineNr;

				$alignedTranslations [$transId] = $translation;
			}

		}

		// ToDo: what about not used translations ? throw away ? or own category ?

				
		$this->translations = $alignedTranslations;
	}

	public function prepareCommentsBefore ($mainCommentsBefore=[]) {
		$commentsBefore = [];

		foreach  ($mainCommentsBefore as $commentLine) {

			$commentsBefore[] = $this->prepareComment ($commentLine);

		}

		return $commentsBefore;
	}

	public function prepareComment ($mainComment='') {

		$comment = '';

		if (strlen($mainComment) > 0) {

			$comment = '%Main%' . $mainComment . '%';
		}

		return $comment;
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

	// Prepare for a different language by throwing away the translations and keeping the IDS
	public function resetToPreparedTranslations()
	{
		$this->translationDoubles = [];
		$this->translationSurplus = [];

		// Attention ToDo: how to change the header
		// Attention ToDo: how to change the trailer

		foreach ($this->translations as $translation) {

			$translation->translationText = '';
			$translation->isPrepared = true;

			// Attention ToDo: how to change the commentsBefore lines
			// Attention ToDo: how to change the commentBehind
		}

		return;
	}




    public function __toText () {

        $lines = [];

        $lines [] = 'langId = "' . $this->langId . '"';
        $lines [] = 'langPathFileName = "' . $this->langPathFileName . '"';
        $lines [] = '$isSysFile = "' . $this->isSysFile . '"';

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
