<?php
/**
 * This class contains translations file
 * with references to empty lines and comments
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

//use Finnern\Component\Lang4dev\Administrator\Helper\langFiles;

// no direct access
\defined('_JEXEC') or die;


// ToDo: divide root path / project path .....


/**
 * File path and name with parts:
 *   - Keep complete path and name of file
 *   - Extract parts to identify
 *        - System File (*.sys.ini)
 *        - Language ID like 'en-GB'
 *        - Language ID precedes the file name (en-GB.com_name.ini)
 *   - Create path from extracted parts
 *   -
 *   -
 *
 *
 * @package Lang4dev
 */
class langPathFileName
{
	protected $langPathFileName = '???.ini';
	protected $langId = 'en-GB'; // 'en-GB'  // lang ID
	public $isIdPreceded = false;
	public $isSysFile = false;  # lang file type (normal/sys)

	// ToDo: base path variable 
	// ToDo: get without base path ? base path as parmeter ? 
	
	/**
	 * @since __BUMP_VERSION__
	 */
	public function __construct($langPathFileName = '')
	{
		$this->setLangPathFileName ($langPathFileName);
	}

	public function clear()
	{
		$this->langPathFileName = '???.ini';
		$this->langId           = '??-??'; #'en-GB'  # lang ID
		$this->isIdPreceded     = false;
		$this->isSysFile        = false;  # lang file type (normal/sys)

	}

//	public function getRootLangPathFileName (){
	public function getlangPathFileName (){
		return $this->langPathFileName;
	}

	public function getlangFileName (){
		return basename($this->langPathFileName);
	}

	// base path below language file (where project xml is expected)
	public function getProjectPath (){

		$projectPath = "";

		$langPathFileName = $this->langPathFileName;
		try
		{
			$fullPath     = dirname($langPathFileName);
			$langIDPath   = dirname($fullPath);
			$languagePath = dirname($langIDPath);
			$projectPath  = dirname($languagePath);

        } catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing assignTranslationLines: ' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $projectPath;
	}

	public function setLangPathFileName ($langPathFileName = ''){

		$this->langPathFileName = $langPathFileName;

		if ($langPathFileName != '')
		{
			$this->ExtractNameParts();
		}

	}

	public function getlangID (){ return $this->langId;}

	private function ExtractNameParts()
	{
		$fileName = basename ($this->langPathFileName);
		$fullPath = dirname($this->langPathFileName);

		// lang id
		$this->langId = dirname($fullPath);

		// is id preceded
		$this->isIdPreceded = str_starts_with ($fileName, $this->langId );

		// sys file
		$this->isSysFile = str_ends_with($fileName, '.sys.ini') ;

		//


	}

	private function ComposePath()
	{


	}

	public function createlangPathFileName ($basename, $isIdPreceded, $isSystFile) {}
	public function replaceLangId ($langId) {}

	public function isValidPathFileName ($langPathFileName = '', $isMustExist=false) {


		//if (strlen($langPathFileName))
		$isNameVerified = true;

		if ( ! str_ends_with ($langPathFileName, '.ini')) {

			$isNameVerified = false;

		} else {

			// ToDo: name/path has valid lang ID



			// File must exist
			if ($isMustExist && ! File::exists ($langPathFileName)) {

				$isNameVerified = false;

			}
		}

		return $isNameVerified;
	}

} // class
