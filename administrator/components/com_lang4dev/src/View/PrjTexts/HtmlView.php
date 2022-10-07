<?php
/**
 * @package       Joomla.Administrator
 * @subpackage    com_lang4dev
 *
 * @copyright (C) 2022-2022 Lang4dev Team
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Finnern\Component\Lang4dev\Administrator\View\PrjTexts;

defined('_JEXEC') or die;

require_once(__DIR__ . '/../../Helper/selectProject.php');

use Finnern\Component\Lang4dev\Administrator\Model\PrjTextsModel;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Path;
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

//use Finnern\Component\Lang4dev\Administrator\Helper\manifestData;
use Finnern\Component\Lang4dev\Administrator\Helper\manifestLangFiles;
use Finnern\Component\Lang4dev\Administrator\Helper\sessionProjectId;
use Finnern\Component\Lang4dev\Administrator\Helper\sessionTransLangIds;
use Finnern\Component\Lang4dev\Administrator\Helper\langFile;
use Finnern\Component\Lang4dev\Administrator\Helper\projectType;

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

    protected $mainLangId;
    protected $transIdsClassified;

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

        $this->isDoCommentIds = $l4dConfig->get('isDoComment_prepared_missing_ids');

        //--- session (config) ----------------------------------------------------------

        // main / translation language id
        $sessionTransLangIds = new sessionTransLangIds ();
        [$mainLangId, $transLangId] = $sessionTransLangIds->getIds();
        $this->mainLangId = $mainLangId;

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

        //--- Main and target lang file --------------------------------------------------------------

        $sessionTransLangIds = new sessionTransLangIds ();
        [$mainLangId, $transLangId] = $sessionTransLangIds->getIds();

        $this->form->setValue('selectSourceLangId', null, $mainLangId);

        //--- define project ------------------------------------------------------------

        /** @var PrjTextsModel $model */
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
        $project->readLangFiles($this->mainLangId);

        // scan code for occurrences of
        $project->scanCode4TransIds();
        $project->scanCode4TransStrings();

        //--- sort by types -----------------------------------------

        // ['missing', same, notUsed, doubles']
        $this->transIdsClassified = $project->getTransIdsClassified($this->mainLangId);

        /*-----------------------------------------------------------------
        Debug lines
        -----------------------------------------------------------------*/

        // ToDo: write to log and prepare for email to be send

        //--- show project with sub projects ... ---------------------------------

        if ($this->isDebugBackend) {
            echo '<br><h4>Project (sub) data</h4><br>';

            $projectText = implode("<br>", $this->project->__toText());

            echo $projectText . '<br>';
            echo '<hr>';
        }

//		//--- show found file list -----------------------------------------
//
//		if ($this->isDebugBackend)
//		{
//			//--- all projects filenames by lang ID  -----------------------------------------
//
//			$langFileSetsPrjs = $project->LangFileNamesCollection();
//
//			echo '<h4>Lang file list</h4>';
//
//			foreach ($langFileSetsPrjs as $prjId => $langFileSets)
//			{
//				echo '[' . $prjId . ']' . '<br>';
//
//				foreach ($langFileSets as $langId => $langFiles)
//				{
//					echo '&nbsp;&nbsp;&nbsp;[' . $langId . ']' . '<br>';
//
//					foreach ($langFiles as $langFile)
//					{
//						echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;' . $langFile . '<br>';
//					}
//				}
//			}
//
//			echo '<hr>';
//		}

        //--- manifest file ----------------------------------------

//		if ($this->isDebugBackend)
//		{
//			$prjXmlPathFilename = $project->subProjects[0]->prjXmlPathFilename; // . '/lang4dev.xml';
//
//			// $manifestData = new manifestData ($prjXmlPathFilename);
//			$manifestLang = new manifestLangFiles ($prjXmlPathFilename);
//			//$manifestText = implode("\n", $manifestData->__toText());
//			$manifestText = implode("<br>", $manifestLang->__toText());
//
//			//--- show manifest content -----------------------------------------
//
//			echo '<h4>manifest content parts</h4>';
//			echo $manifestText . '<br>';
//			echo '<hr>';
//
//		}

        /**
         * $Layout = Factory::getApplication()->input->get('layout');
         * Lang4devHelper::addSubmenu('config');
         * $this->sidebar = \JHtmlSidebar::render();
         **/

        $Layout = Factory::getApplication()->input->get('layout');
        //echo '$Layout: ' . $Layout . '<br>';

        $this->addToolbar($Layout);

        parent::display($tpl);

        return;
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
                . '<b>Tasks:</b> <br>'
                . '* use mainLangId from (config / session) used in files view ? <br>'
                . '* Use OutText / ? outtxt for AD HOC texts<br>'
//				. '* <br>'
//				. '* <br>'
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
        ToolBarHelper::title(Text::_('COM_LANG4DEV_SUBMENU_PROJECTS_TEXTS'), 'list');

        // Options button.
        if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_lang4dev')) {
            $toolbar->preferences('com_lang4dev');
        }
    }

}

