<?php
/**
 * @package       Joomla.Administrator
 * @subpackage    com_lang4dev
 *
 * @copyright (C) 2022-2022 Lang4dev Team
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Finnern\Component\Lang4dev\Administrator\View\Translate;

defined('_JEXEC') or die;

require_once(__DIR__ . '/../../Helper/selectProject.php');

use Finnern\Component\Lang4dev\Administrator\Helper\manifestLangFiles;
use Finnern\Component\Lang4dev\Administrator\Helper\sessionProjectId;
use Finnern\Component\Lang4dev\Administrator\Helper\sessionTransLangIds;
use Finnern\Component\Lang4dev\Administrator\Model\TranslateModel;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

//use Finnern\Component\Lang4dev\Administrator\Helper\Lang4devHelper;
use function defined;
use function Finnern\Component\Lang4dev\Administrator\Helper\selectProject;

/**
 * View class for a list of lang4dev.
 *
 * @since __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
    /**
     * The \Form object
     *
     * @var  Form
     */
    protected $form;

    protected $project;

    protected $isDebugBackend;
    protected $isDevelop;
    protected $isDoCommentIds;

    //protected $langFiles = [];

    protected $mainLangId;
    protected $transLangId;
    protected $isShowTranslationOfAllIds;
    protected $isEditAndSaveMainTranslationFile;

    /**
     * Method to display the view.
     *
     * @param   string  $tpl  A template file to load. [optional]
     *
     * @return  mixed  A string if successful, otherwise an \Exception object.
     *
     * @since __BUMP_VERSION__
     */
    public function display($tpl = null)
    {
        //--- config --------------------------------------------------------------------

        $l4dConfig            = ComponentHelper::getComponent('com_lang4dev')->getParams();
        $this->isDebugBackend = $l4dConfig->get('isDebugBackend');
        $this->isDevelop      = $l4dConfig->get('isDevelop');

        $this->isShowTranslationOfAllIds        = $l4dConfig->get('isShowTranslationOfAllIds');
        $this->isEditAndSaveMainTranslationFile = $l4dConfig->get('isEditAndSaveMainTranslationFile');

        //--- session (config) ----------------------------------------------------------

        // main / translation language id
        $sessionTransLangIds = new sessionTransLangIds ();
        [$mainLangId, $transLangId] = $sessionTransLangIds->getIds();
        $this->mainLangId  = $mainLangId;
        $this->transLangId = $transLangId;

        // selection of project and subproject
        $sessionProjectId = new sessionProjectId();
        [$prjId, $subPrjActive] = $sessionProjectId->getIds();

        //--- Form ----------------------------------------------------------------------

        $this->form = $this->get('Form');

        $errors = $this->get('Errors');

//        // Check for errors.
//        if (count($errors = $this->get('Errors')))
//        {
//            throw new GenericDataException(implode("\n", $errors), 500);
//        }

        $this->form->setValue('selectSourceLangId', null, $mainLangId);
        $this->form->setValue('selectTargetLangId', null, $transLangId);

        //--- define project -------------------------------------------------------------------

        /** @var TranslateModel $model */
        $model         = $this->getModel();
        $this->project = $model->getProject($prjId, $subPrjActive);
        $project       = $this->project;

        /* test projects *
        $project =
        $this->project = selectProject('lang4dev');
        // $this->project = selectProject('joomgallery');
        // $this->project = selectProject('rsgallery2');
        // $this->project = selectProject('joomla4x');
        /**/

        // script- / install file, language files as list
        // not any more: $project->findPrjFiles();
        // $project->detectLangFiles();

        //--- collect content ---------------------------------------------------

        // read translations
        // $project->readLangFiles($this->mainLangId);
        $project->readAllLangFiles();

        $project->alignTranslationsByMain($this->mainLangId);

        //--- show found file list -----------------------------------------

        if ($this->isDebugBackend) {
            //--- all projects filenames by lang ID  -----------------------------------------

            $langFileSetsPrjs = $project->LangFileNamesCollection();

            echo '<h4>Lang file list</h4>';

            foreach ($langFileSetsPrjs as $prjId => $langFileSets) {
                echo '[' . $prjId . ']' . '<br>';

                foreach ($langFileSets as $langId => $langFiles) {
                    echo '&nbsp;&nbsp;&nbsp;[' . $langId . ']' . '<br>';

                    foreach ($langFiles as $langFile) {
                        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;' . $langFile . '<br>';
                    }
                }
            }

            echo '<hr>';
        }

        //--- test manifest file ----------------------------------------

        if ($this->isDebugBackend) {
            $prjXmlPathFilename = $project->subProjects[0]->prjXmlPathFilename; // . '/lang4dev.xml';

            // $manifestData = new manifestData ($prjXmlPathFilename);
            $manifestLang = new manifestLangFiles ($prjXmlPathFilename);
            //$manifestText = implode("\n", $manifestData->__toText());
            $manifestText = implode("<br>", $manifestLang->__toText());

            //--- show manifest content -----------------------------------------

            echo '<h4>manifest content parts</h4>';
            echo $manifestText . '<br>';
            echo '<hr>';
        }

        //---  --------------------------------------------------------------
        /**
         * $Layout = Factory::getApplication()->input->get('layout');
         * Lang4devHelper::addSubmenu('config');
         * $this->sidebar = \JHtmlSidebar::render();
         **/

        $Layout = Factory::getApplication()->input->get('layout');
        //echo '$Layout: ' . $Layout . '<br>';

        $this->addToolbar($Layout);

        /**/

        return parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     *
     * @since __BUMP_VERSION__
     */
    protected function addToolbar($Layout)
    {
        // Get the toolbar object instance
        $toolbar = Toolbar::getInstance('toolbar');

        // on develop show open tasks if existing
        if (!empty ($this->isDevelop)) {
            echo '<span style="color:red">'
                . '<b>Tasks: </b><br>'
                . '* lang_ids by sub project ->existing <br>'
                . '* function getProject is double in prjText and more  model ? own class<br>'
                . '* source lang ID as list field of availables ? config ? project ... ? <br>'
                . '* Target lang id as list field of ISO 639 list<br>'
                . '* New lang id as list field of ISO 639 list<br>'
                . '* use isLangAtStdJoomla in detectLangFiles<br>'
                . '* use list of langIds to create a translation selection <br>'
                . '*  <br>'
//				. '* <br>'
//				. '* <br>'
//				. '* <br>'
                . '</span><br><br>';
        }

        switch ($Layout) {
            /**
             * case 'RawView':
             * ToolBarHelper::title(Text::_('COM_Lang4dev_MAINTENANCE')
             * . ': ' . Text::_('COM_Lang4dev_CONFIGURATION_RAW_VIEW'), 'screwdriver');
             * ToolBarHelper::cancel('config.cancel_rawView', 'JTOOLBAR_CLOSE');
             *
             *
             * break;
             *
             * case 'RawEdit':
             * ToolBarHelper::title(Text::_('COM_Lang4dev_MAINTENANCE')
             * . ': ' . Text::_('COM_Lang4dev_CONFIGURATION_RAW_EDIT'), 'screwdriver');
             * ToolBarHelper::apply('config.apply_rawEdit');
             * ToolBarHelper::save('config.save_rawEdit');
             * ToolBarHelper::cancel('config.cancel_rawEdit', 'JTOOLBAR_CLOSE');
             * break;
             * /**/
            default:
                ToolBarHelper::cancel('lang4dev.cancel', 'JTOOLBAR_CLOSE');

                ToolbarHelper::custom(
                    'translate.selectSourceLangId',
                    'icon-flag',
                    '',
                    'COM_LANG4DEV_TRANS_SELECT_SOURCE_LANG_ID',
                    false
                );
                ToolbarHelper::custom(
                    'translate.selectTargetLangId',
                    'icon-edit',
                    '',
                    'COM_LANG4DEV_TRANS_SELECT_TARGET_LANG_ID',
                    false
                );

                ToolbarHelper::custom(
                    'translate.createLangId',
                    'icon-copy',
                    '',
                    'COM_LANG4DEV_TRANSLATE_NEW_LANG_FILES',
                    false
                );
                ToolbarHelper::custom(
                    'translate.saveLangEdits',
                    'icon-save',
                    '',
                    'COM_LANG4DEV_TRANSLATE_SAVE_EDITED_LANG_FILES',
                    false
                ); /// ToDo: true);

                break;
        }

        // Set the title
        ToolBarHelper::title(Text::_('COM_LANG4DEV_SUBMENU_TRANSLATE'), 'flag');

        // Options button.
        if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_lang4dev')) {
            $toolbar->preferences('com_lang4dev');
        }
    }

}

