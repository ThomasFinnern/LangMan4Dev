<?php
/**
 * @package         LangMan4Dev
 * @subpackage      com_lang4dev
 * @author          Thomas Finnern <InsideTheMachine.de>
 * @copyright  (c)  2022-2025 Lang4dev Team
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Finnern\Component\Lang4dev\Administrator\Controller;

defined('_JEXEC') or die;

use Exception;
use Finnern\Component\Lang4dev\Administrator\Helper\sessionProjectId;
use Finnern\Component\Lang4dev\Administrator\Helper\sessionTransLangIds;
use JInput;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\Filesystem\File;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Session\Session;
use Joomla\Utilities\ArrayHelper;

use Finnern\Component\Lang4dev\Administrator\Helper\langFile;
use Finnern\Component\Lang4dev\Administrator\Helper\langPathFileName;

use function defined;

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
     *                                         Recognized key values include 'name', 'default_task', 'model_path', and
     *                                         'view_path' (this list is not meant to be comprehensive).
     * @param   MVCFactoryInterface  $factory  The factory.
     * @param   CMSApplication       $app      The JApplication for the dispatcher
     * @param   JInput               $input    Input
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
     * @return  BaseDatabaseModel  The model.
     *
     * @since __BUMP_VERSION__
     */
    public function getModel($name = 'Translate', $prefix = 'Administrator', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }

    /**
     * Standard cancel, back to list view
     *
     * @param   null  $key
     *
     * @return bool
     *
     * @since __BUMP_VERSION__
     */
    public function cancel($key = null)
    {
        Session::checkToken() or die(Text::_('JINVALID_TOKEN'));
        //$link = 'index.php?option=com_lang4dev&view=translation';
        $link = 'index.php?option=com_lang4dev';
        $this->setRedirect($link);

        return true;
    }

    /**
     *
     * @return bool|void
     *
     * @throws Exception
     * @since version
     */
    public function selectSourceLangId()
    {
        Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

        // source id valid ?

        // get project / subproject id

        // set source lang ID in project db

        $OutTxt = "Source Lang Id for translation has changed:";
        $app    = Factory::getApplication();
        $app->enqueueMessage($OutTxt, 'info');

        $link = 'index.php?option=com_lang4dev&view=translate';
        $this->setRedirect($link);

        return true;
    }

    /**
     *
     * @return bool|void
     *
     * @throws Exception
     * @since version
     */
    public function selectTargetLangId()
    {
        Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

        // target id valid ?

        // get project / subproject id

        // set source lang ID in  project db

        $OutTxt = "TargetLangId for translation has changed:";
        $app    = Factory::getApplication();
        $app->enqueueMessage($OutTxt, 'info');

        $link = 'index.php?option=com_lang4dev&view=translate';
        $this->setRedirect($link);

        return true;
    }

    /**
     *
     * @return bool|void
     *
     * @throws Exception
     * @since version
     */
    public function createLangId()
    {
        Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

        // User is allowed to change
        // ToDo: $canCreateFile = ...;
        $canCreateFile = true;

        // ToDo: try / catch

        if (!$canCreateFile) {
            $OutTxt = Text::_('COM_LANG4DEV_TRANSLATE_CREATE_LANG_INVALID_RIGHTS');
            $app    = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        } else {
            // ToDo: try ...

            $input = Factory::getApplication()->input;
            $data  = $input->post->get('jform', array(), 'array');

            $targetLangId = $data ['selectTargetLangId'];
            $sourceLangId = $data ['selectSourceLangId'];

            // form lang file names (wrong lang ID)
            $langPathFileNames = $input->get('mainLangFiles', array(), 'ARRAY');

            // check for valid form ?
            $isTargetVerified = $this->isValidLangIdName($targetLangId);
            $isSourceVerified = $this->isValidLangIdName($sourceLangId);

            if ($isTargetVerified && $isSourceVerified) {
                $createdFileNames = [];

                // load source files by using form target lang files names
                foreach ($langPathFileNames as $langPathFileName) {
                    // create empty lang file with just a filename
                    $langFile = new langfile(); // empty lang file
                    $langFile->setLangPathFileName($langPathFileName);

                    // Exchange lang ID with source lang ID
                    $langFile->setLangID($sourceLangId);

                    // Read main translations
                    $isRead = $langFile->readFileContent();

                    // file did exist and was read
                    if ($isRead) {
                        //--- create new lang file ---------------------------------------

                        // change name
                        $langFile->setLangID($targetLangId);

                        // remove translations (attention comments may still be old language)
                        $langFile->resetToPreparedTranslations();

                        // prepare new path
                        $langFile->createLangFolder();

                        // write results
                        $isWritten = $langFile->writeToFile();

                        if ($isWritten) {
                            $createdFileNames [] = $langFile->getlangSubPrjPathFileName();
                        } else {
                            // Message on not written

                            $OutTxt = Text::_('COM_LANG4DEV_TRANSLATE_COULD_NOT_WRITE_LANG_FILE')
                                . ': "' . $langFile->getlangFileName() . '"';
                            $app    = Factory::getApplication();
                            $app->enqueueMessage($OutTxt, 'error');
                        }
                    }

                    if (count($createdFileNames) > 0) {
                        $OutTxt = Text::_('COM_LANG4DEV_TRANSLATE_CREATED_LANG_FILES') . '\n'
                            . implode(',\n', $createdFileNames);
                        $app    = Factory::getApplication();
                        $app->enqueueMessage($OutTxt, 'info');
                    }
                }

                if (count($langPathFileNames) == 0) {
                    $OutTxt = Text::_('COM_LANG4DEV_TRANSLATE_CREATED_LANG_ID_NO_FILES') . '\n'
                        . implode(',\n', $createdFileNames);
                    $app    = Factory::getApplication();
                    $app->enqueueMessage($OutTxt, 'info');
                }
            } else {
                //--- invalid lang ID names ------------------------

                if (!$isTargetVerified) {
                    $OutTxt = Text::_('COM_LANG4DEV_TRANSLATE_WRONG_TARGET_LANG_ID')
                        . ': "' . $targetLangId . '"';
                    $app    = Factory::getApplication();
                    $app->enqueueMessage($OutTxt, 'error');
                }

                if (!$isSourceVerified) {
                    $OutTxt = Text::_('COM_LANG4DEV_TRANSLATE_WRONG_SOURCE_LANG_ID')
                        . ': "' . $sourceLangId . '"';
                    $app    = Factory::getApplication();
                    $app->enqueueMessage($OutTxt, 'error');
                }
            }
        }

        $link = 'index.php?option=com_lang4dev&view=translate';
        $this->setRedirect($link);

        return true;
    }

    /**
     *
     * @return bool|void
     *
     * @throws Exception
     * @since version
     */
    public function saveLangEdits()
    {
        Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

        // User is allowed to change
        // ToDo: $canSave = ...;
        $canSave = true;

        if (!$canSave) {
            $OutTxt = Text::_('COM_LANG4DEV_TRANSLATE_SAVE_EDITED_INVALID_RIGHTS');
            $app    = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        } else {
            // ToDo: get $doBackup from config
            $doBackup = true;

            $input = Factory::getApplication()->input;

            $ids = $this->input->get('cid', array(), 'array');
            $ids = ArrayHelper::toInteger($ids);

            if (empty($ids)) {
                $this->app->enqueueMessage(Text::_('COM_LANG4DEV_NO_LANG_FIELD_SELECTED_FOR_SAVE'), 'warning');
            }

            // lang file names
            $langPathFileNames = $input->get('langPathFileNames', array(), 'ARRAY');
            $filesCount        = count($langPathFileNames);

            // lang filed edited text
            $langsText   = $input->get('langsText', array(), 'ARRAY');
            $editedCount = count($langsText);

            // The Ids point to the selected (and text) file to save

            foreach ($ids as $idx) {
                if ($idx < $filesCount) {
                    // $langPathFileName = $langPathFileNames [$idx];
                    // $isNameVerified = $this->verifyLangFileName($langPathFileName);

                    $oLangPathFileName = new langPathFileName ($langPathFileNames [$idx]);
                    $isNameVerified    = $oLangPathFileName->check4ValidPathFileName();

                    if ($isNameVerified) {
                        // convert text into lines
                        $langText = preg_split("/((\r?\n)|(\r\n?))/", $langsText [$idx]);

                        //=======================================
                        // write file
                        //=======================================

                        // create lang file parts from text
                        $langFile = new langFile ();
                        $langFile->assignTranslationLines($langText);

                        // write lang file
                        $langFile->setLangPathFileName($langPathFileNames[$idx]);
                        $isWritten = $langFile->writeToFile('', $doBackup);

                        //--- messages -----------------------------------

                        // ToDo: on debug show complete path
                        $debug = 1;
                        if (empty ($debug)) {
                            $langFileName = $langFile->getLangFileName();
                        } else {
                            $langFileName = $langFile->getLangPathFileName();
                        }

                        // Message on not found items
                        if (count($langFile->translations) == 0) {
                            $OutTxt = Text::_('COM_LANG4DEV_TRANSLATE_NO_VALID_ITEMS_FOUND_EMPTY_LANG_FILE')
                                . ': "' . $langFileName . '"';
                            $app    = Factory::getApplication();
                            $app->enqueueMessage($OutTxt, 'error');
                        }

                        if ($isWritten) {
                            // Success message
                            $OutTxt = Text::_('COM_LANG4DEV_TRANSLATE_SUCCESS_FILE_SAVED' . ':' . $langFileName);
                            $app    = Factory::getApplication();
                            $app->enqueueMessage($OutTxt, 'info');
                        } else {
                            // Success message
                            $OutTxt = Text::_('COM_LANG4DEV_TRANSLATE_ERROR_FILE_NOT_SAVED' . ':' . $langFileName);
                            $app    = Factory::getApplication();
                            $app->enqueueMessage($OutTxt, 'info');
                        }
                    } else { // ! $isNameVerified

                        $OutTxt = Text::_('COM_LANG4DEV_TRANSLATE_ERROR_INVALID_FILE_NAME')
                            . ': "' . $oLangPathFileName->getLangPathFileName() . '"';
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




    // ToDo: part of langFile / langFileNames class ?
    // ends on ini and does exist
    /**
     * @param   string  $langPathFileName
     *
     * @return bool
     *
     * @since version
     */
    private function verifyLangFileName(string $langPathFileName = '')
    {
        $isNameVerified = true;

        if (!str_ends_with($langPathFileName, '.ini')) {
            $isNameVerified = false;
        } else {
            // ToDo: name/path has valid lang ID

            // ToDo: flag if it must exist
            if (!is_file($langPathFileName)) {
                $isNameVerified = false;
            }
        }

        return $isNameVerified;
    }

    /**
     * @param $langId
     *
     * @return bool
     *
     * @since version
     */
    private function isValidLangIdName($langId)
    {
        $isNameVerified = true;

        // check string length
        if (strlen($langId) != 5) {
            $isNameVerified = false;
        } else {
            // '-' at the right offset
            if (substr($langId, 2, 1) != '-') {
                $isNameVerified = false;
            }

            // only char or - ^[a-zA-Z\-]*$
            if (!preg_match('/[^a-zA-Z]/', $langId)) {
                $isNameVerified = false;
            }
        }

        // ToDo: compare with a list ....

        return $isNameVerified;
    }

    /**
     *
     * @return bool|void
     *
     * @throws Exception
     * @since version
     */
    public function selectProject()
    {
        Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

        $canCreateFile = true;

        if (!$canCreateFile) {
            $OutTxt = Text::_('COM_LANG4DEV_TRANSLATE_SELECT_PROJECT_INVALID_RIGHTS');
            $app    = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        } else {
            $input = Factory::getApplication()->input;
            $data  = $input->post->get('jform', array(), 'array');

            $prjId        = (int)$data ['selectProject'];
            $subPrjActive = (int)$data ['selectSubproject'];

            // $prjId, $subPrjActive

            $sessionProjectId = new sessionProjectId();
            $sessionProjectId->setIds($prjId, $subPrjActive);
        }

        $OutTxt = "Project for translation has changed:";
        $app    = Factory::getApplication();
        $app->enqueueMessage($OutTxt, 'info');

        $link = 'index.php?option=com_lang4dev&view=translate';
        $this->setRedirect($link);

        return true;
    }

    /**
     *
     * @return bool|void
     *
     * @throws Exception
     * @since version
     */
    public function selectLangIds()
    {
        Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

        $canCreateFile = true;

        if (!$canCreateFile) {
            $OutTxt = Text::_('COM_LANG4DEV_TRANSLATE_SELECT_LANG_IDS_PROJECT_INVALID_RIGHTS');
            $app    = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        } else {
            $input = Factory::getApplication()->input;
            $data  = $input->post->get('jform', array(), 'array');

            $mainLangId  = $data ['selectSourceLangId'];
            $transLangId = $data ['selectTargetLangId'];

            // $prjId, $subPrjActive

            $sessionTransLangIds = new sessionTransLangIds ();
            $sessionTransLangIds->setIds($mainLangId, $transLangId);
        }

        $OutTxt = "Lang Id for translate has changed:";
        $app    = Factory::getApplication();
        $app->enqueueMessage($OutTxt, 'info');

        $link = 'index.php?option=com_lang4dev&view=translate';
        $this->setRedirect($link);

        return true;
    }

}

