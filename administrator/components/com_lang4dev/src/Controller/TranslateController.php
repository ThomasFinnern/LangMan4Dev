<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_lang4dev
 *
 * @copyright (C) 2022-2022 Lang4dev Team
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Finnern\Component\Lang4dev\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Session\Session;

use Finnern\Component\Lang4dev\Administrator\Helper\langFile;
use Joomla\Utilities\ArrayHelper;

/**
 * The Galleries List Controller
 *
 * @since __BUMP_VERSION__
 */
class TranslateController extends AdminController
{
    /**
     * Constructor.
     *
     * @param   array                $config   An optional associative array of configuration settings.
     * Recognized key values include 'name', 'default_task', 'model_path', and
     * 'view_path' (this list is not meant to be comprehensive).
     * @param   MVCFactoryInterface  $factory  The factory.
     * @param   CMSApplication       $app      The JApplication for the dispatcher
     * @param   \JInput              $input    Input
     *
     * @since __BUMP_VERSION__
     */
    public function __construct($config = array(), MVCFactoryInterface $factory = null, $app = null, $input = null)
    {
        parent::__construct($config, $factory, $app, $input);

    }

    /**
	 * Proxy for getModel
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  The array of possible config values. Optional.
	 *
	 * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel  The model.
	 *
	 * @since __BUMP_VERSION__
	 */
	public function getModel($name = 'Gallery', $prefix = 'Administrator', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	/**
	 * Standard cancel, back to list view
	 *
	 * @param null $key
	 *
	 * @return bool
	 *
	 * @since __BUMP_VERSION__
	 */
	public function cancel($key = null)
	{
		Session::checkToken() or die(Text::_('JINVALID_TOKEN'));
		$link = 'index.php?option=com_lang4dev&view=translation';
		$this->setRedirect($link);

		return true;
	}

	public function selectSourceLangId () {
		Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

		// source id valid ?

		// get project / subproject id

		// set source lang ID in  project db

		$OutTxt = "selectSourceLangId for translation has started:";
		$app = Factory::getApplication();
		$app->enqueueMessage($OutTxt, 'info');

		$link = 'index.php?option=com_lang4dev&view=translate';
		$this->setRedirect($link);

		return true;
	}

	public function selectTargetLangId () {
		Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

		// target id valid ?

		// get project / subproject id

		// set source lang ID in  project db




		$OutTxt = "selectTargetLangId for translation has started:";
		$app = Factory::getApplication();
		$app->enqueueMessage($OutTxt, 'info');

		$link = 'index.php?option=com_lang4dev&view=translate';
		$this->setRedirect($link);

		return true;
	}

	public function createLangId () {
		Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

		// User is allowed to change
		// ToDo: $canSave = ...;
		$canSave = true;

		if ( ! $canSave ) {

			$OutTxt = Text::_('COM_LANG4DEV_TRANSLATE_CREATE_LANG_INVALID_RIGHTS');
			$app    = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		} else
		{
			

			// target id valid ?

			// get project / subproject id

			// set source lang ID in  project db

			$OutTxt = "createLangId for translation has started:";
			$app    = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'info');

		}

		$link = 'index.php?option=com_lang4dev&view=translate';
		$this->setRedirect($link);

		return true;
	}

	public function saveLangEdits () {
		Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

		// User is allowed to change
		// ToDo: $canSave = ...;
		$canSave = true;

		if ( ! $canSave ) {

			$OutTxt = Text::_('COM_LANG4DEV_TRANSLATE_SAVE_EDITED_INVALID_RIGHTS');
			$app    = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		} else {

			// ToDo: get $doBackup from config
			$doBackup = true;

			$input = Factory::getApplication()->input;

			$ids = $this->input->get('cid', array(), 'array');
			$ids = ArrayHelper::toInteger($ids);

			if (empty($ids))
			{
				$this->app->enqueueMessage(Text::_('COM_LANG4DEV_NO_LANG_FIELD_SELECTED_FOR_SAVE'), 'warning');
			}

			// lang file names
			$langPathFileNames = $input->get('langPathFileNames', array(), 'ARRAY');
			$filesCount = count($langPathFileNames);

			// lang filed edited text
			$langsText = $input->get('langsText', array(), 'ARRAY');
			$editedCount = count($langsText);


			// The Ids point to the selected (and text) file to save

			foreach ($ids as $idx) {

				if($idx < $filesCount) {

					$langPathFileName = $langPathFileNames [$idx];

					$isNameVerified = $this->verifyLangFileName($langPathFileName);

					if ($isNameVerified)
					{
						// convert text into lines
						$langText = preg_split("/((\r?\n)|(\r\n?))/", $langsText [$idx]);

						//=======================================
						// write file
						//=======================================

						// create lang file parts from text
						$langFile = new langFile ();
						$langFile->assignTranslationLines($langText);

						// write lang file
						$langFile->langPathFileName = $langPathFileNames[$idx];
						$isWritten = $langFile->writeToFile('', $doBackup);


						//--- messages -----------------------------------

						// ToDo: on debug show complete path
						$debug = 1;
						if (empty ($debug))
						{
							$langFileName = basename($langFile->langPathFileName);
						} else 	{
							$langFileName = $langFile->langPathFileName;
						}

						// Message on not found items
						if (count ($langFile->translations) == 0) {

							$OutTxt = Text::_('COM_LANG4DEV_TRANSLATE_NO_VALID_ITEMS_FOUND_EMPTY_LANG_FILE')
								. ': "' . $langFileName .'"';
							$app    = Factory::getApplication();
							$app->enqueueMessage($OutTxt, 'error');

						}


						if ($isWritten)
						{
							// Success message
							$OutTxt       = Text::_('COM_LANG4DEV_TRANSLATE_SUCCESS_FILE_SAVED' .':' . $langFileName);
							$app          = Factory::getApplication();
							$app->enqueueMessage($OutTxt, 'info');
						} else {
							// Success message
							$OutTxt       = Text::_('COM_LANG4DEV_TRANSLATE_ERROR_FILE_NOT_SAVED' .':' . $langFileName);
							$app          = Factory::getApplication();
							$app->enqueueMessage($OutTxt, 'info');
						}

					} else { // ! $isNameVerified

						$OutTxt = Text::_('COM_LANG4DEV_TRANSLATE_ERORR_INVALID_FILE_NAME')
							. ': "' . $langPathFileName .'"';
						$app    = Factory::getApplication();
						$app->enqueueMessage($OutTxt, 'error');
					}

				}

			}

		}

		$link = 'index.php?option=com_lang4dev&view=translate';
		$this->setRedirect($link);

		return true;
	}






	private function verifyLangFileName(string $langPathFileName = '')
	{
		$isNameVerified = true;

		if ( ! str_ends_with ($langPathFileName, '.ini')) {

			$isNameVerified = true;

		} else {

			if ( ! File::exists ($langPathFileName)) {

				$isNameVerified = true;

			}
		}

		return $isNameVerified;
	}
}

