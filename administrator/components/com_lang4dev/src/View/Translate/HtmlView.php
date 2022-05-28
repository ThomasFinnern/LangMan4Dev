<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_lang4dev
 *
 * @copyright (C) 2022-2022 Lang4dev Team
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Finnern\Component\Lang4dev\Administrator\View\Translate;

\defined('_JEXEC') or die;

require_once(__DIR__ . '/../../Helper/selectProject.php');
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

use Finnern\Component\Lang4dev\Administrator\Helper\Lang4devHelper;
use function Finnern\Component\Lang4dev\Administrator\Helper\selectProject;


/**
 * View class for a list of lang4dev.
 *
 * @since __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
	protected $project;

	protected $isDebugBackend;
	protected $isDevelop;
	protected $isDoCommentIds;

	protected $langfiles = [];

	protected $main_langId;
	protected $trans_langId;
	protected $isShowTranslationOfAllIds;


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

		$l4dConfig = ComponentHelper::getComponent('com_lang4dev')->getParams();
		$this->isDebugBackend = $l4dConfig->get('isDebugBackend');
		$this->isDevelop = $l4dConfig->get('isDevelop');

		$this->main_langId = $l4dConfig->get('main_langId');
		$this->trans_langId = $l4dConfig->get('trans_langId');
		$this->isShowTranslationOfAllIds = $l4dConfig->get('isShowTranslationOfAllIds');

        //--- Form --------------------------------------------------------------------

        $this->form = $this->get('Form');
//        $errors = $this->get('Errors')
//        $this->item = $this->get('Item');
//        $this->state = $this->get('State');

        //$section = $this->state->get('gallery.section') ? $this->state->get('gallery.section') . '.' : '';
        //$this->canDo = ContentHelper::getActions($this->state->get('gallery.component'), $section . 'gallery', $this->item->id);
//        $this->canDo = ContentHelper::getActions('com_lang4dev', 'project', $this->item->id);
//        $this->assoc = $this->get('Assoc');

        $errors = $this->get('Errors');

//        // Check for errors.
//        if (count($errors = $this->get('Errors')))
//        {
//            throw new GenericDataException(implode("\n", $errors), 500);
//        }

        //--- project --------------------------------------------------------------------

//		$this->isDoCommentIds = $l4dConfig->get('isDoComment_prepared_missing_ids');

		$project =
		$this->project = selectProject('lang4dev');
//		$this->project = selectProject('lang4dev');
//		$this->project = selectProject('joomgallery');
////		$this->project = selectProject('joomla4x');


		// ? use config lang ids or found ids
		
		// init required langIds 
		/**
		foreach ($project->subProjects as $subProject)
		{
			
			$this->subProjects->langIds = config;
			
		}
		/**/

        $project->findPrjFiles();

		$project->detectLangFiles();

		// collect content
		$project->readAllLangFiles();

		$project->alignTranslationsByMain($this->main_langId);

		//---  --------------------------------------------------------------
		/**
		HTMLHelper::_('sidebar.setAction', 'index.php?option=com_lang4dev&view=config&layout=RawView');
		/**
		$Layout = Factory::getApplication()->input->get('layout');
		Lang4devHelper::addSubmenu('config');
		$this->sidebar = \JHtmlSidebar::render();
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
		if (!empty ($this->isDevelop))
		{
			echo '<span style="color:red">'
				. 'Tasks: <br>'
				. '* <br>'
				. '* <br>'
//				. '* <br>'
//				. '* <br>'
//				. '* <br>'
//				. '* <br>'
				. '</span><br><br>';
		}

		switch ($Layout)
		{
			/**
			case 'RawView':
				ToolBarHelper::title(Text::_('COM_Lang4dev_MAINTENANCE')
					. ': ' . Text::_('COM_Lang4dev_CONFIGURATION_RAW_VIEW'), 'screwdriver');
				ToolBarHelper::cancel('config.cancel_rawView', 'JTOOLBAR_CLOSE');


				break;

			case 'RawEdit':
				ToolBarHelper::title(Text::_('COM_Lang4dev_MAINTENANCE')
					. ': ' . Text::_('COM_Lang4dev_CONFIGURATION_RAW_EDIT'), 'screwdriver');
				ToolBarHelper::apply('config.apply_rawEdit');
				ToolBarHelper::save('config.save_rawEdit');
				ToolBarHelper::cancel('config.cancel_rawEdit', 'JTOOLBAR_CLOSE');
				break;
			/**/
			default:
                ToolBarHelper::cancel('lang4dev.cancel', 'JTOOLBAR_CLOSE');

                ToolbarHelper::custom('translate.selectSourceLangId', 'icon-flag', '', 'COM_LANG4DEV_TRANS_SELECT_SOURCE_LANG_ID', false);
                ToolbarHelper::custom('translate.selectTargetLangId', 'icon-edit', '', 'COM_LANG4DEV_TRANS_SELECT_TARGET_LANG_ID', false);

                ToolbarHelper::custom('translate.createLangId', 'icon-copy', '', 'COM_LANG4DEV_TRANSLATE_ADD_LANG_FILE', false);

				ToolbarHelper::custom('translate.saveLangEdits', 'icon-save', '', 'COM_LANG4DEV_TRANSLATE_SAVE_EDITED_LANG_FILES', false); /// ToDo: true);



                break;
		}

		// Set the title
		ToolBarHelper::title(Text::_('COM_LANG4DEV_SUBMENU_TRANSLATE'), 'flag');

		// Options button.
		if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_lang4dev'))
		{
			$toolbar->preferences('com_lang4dev');
		}
	}



}

