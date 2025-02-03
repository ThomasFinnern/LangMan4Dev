<?php
/**
 * @package         LangMan4Dev
 * @subpackage      com_lang4dev
 * @author          Thomas Finnern <InsideTheMachine.de>
 * @copyright  (c)  2022-2025 Lang4dev Team
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Finnern\Component\Lang4dev\Administrator\View\ProjectsRaw;

defined('_JEXEC') or die;

require_once(__DIR__ . '/../../Helper/selectProject.php');

use Finnern\Component\Lang4dev\Administrator\Helper\manifestLangFiles;
use Finnern\Component\Lang4dev\Administrator\Helper\sessionProjectId;
use Finnern\Component\Lang4dev\Administrator\Helper\sessionTransLangIds;
use Finnern\Component\Lang4dev\Administrator\Model\ProjectsRawModel;
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
 * View class for a raw json list of a selected project .
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

    /**
     * @var
     * @since version
     */
    protected $project;

    protected $isDebugBackend;
    protected $isDevelop;
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

        /** @var ProjectsRawModel $model */
        $model         = $this->getModel();
        $this->project = $model->getProject($prjId, $subPrjActive);
        $project       = $this->project;

        //--- collect content ---------------------------------------------------

        // read translations
        // $project->readLangFiles($this->mainLangId);
        $project->readAllLangFiles();

        $project->alignTranslationsByMain($this->mainLangId);

        //--- found lang files list -----------------------------------------

	    $this->langFileSetsPrjs = $project->LangFileNamesCollection();

	    //--- manifest file content ----------------------------------------

	    if ( ! empty ($project->subProjects[0]))
	    {
		    $prjXmlPathFilename = $project->subProjects[0]->oBasePrjPath->prjXmlPathFilename; // . '/lang4dev.xml';

		    $this->manifestLang = new manifestLangFiles ($prjXmlPathFilename);
	    }


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


                break;
        }

        // Set the title
        ToolBarHelper::title(Text::_('PROJECTS_RAW'), 'flag');

        // Options button.
        if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_lang4dev')) {
            $toolbar->preferences('com_lang4dev');
        }
    }

}

