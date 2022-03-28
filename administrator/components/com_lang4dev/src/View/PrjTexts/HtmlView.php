<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_lang4dev
 *
 * @copyright   Copyright (C) 2022 - 2022
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Finnern\Component\Lang4dev\Administrator\View\PrjTexts;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

use Finnern\Component\Lang4dev\Administrator\Helper\langFile;
use Finnern\Component\Lang4dev\Administrator\Helper\langProject;
//use Finnern\Component\Lang4dev\Administrator\Helper\langFileNamesSet;
//use Finnern\Component\Lang4dev\Administrator\Helper\langLocationsSearch;

/**
 * View class for a list of lang4dev.
 *
 * @since __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
	protected $isDevelop;
//	protected $prjLangLocations;

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
		$Layout = Factory::getApplication()->input->get('layout');
		//echo '$Layout: ' . $Layout . '<br>';

		$l4dConfig = ComponentHelper::getComponent('com_Lang4dev')->getParams();
		$this->isDevelop = $l4dConfig->get('isDevelop');

		//--- lang4dev --------------------------------

		$prjLang4dev = new langProject ();

		$subPrj = $prjLang4dev->addSubProject('Lang4Dev', JPATH_ADMINISTRATOR . '/components/com_lang4dev');
		$subPrj->findFiles();

		//--- lang4dev --------------------------------

		$prjRsgallery2 = new langProject ();

		$subPrj = $prjLang4dev->addSubProject('RSGallery2', JPATH_ADMINISTRATOR . '/components/com_lang4dev');
		$subPrj->findFiles();



//		//--- old --------------------------------
//
//		$prjSysFiles = new prjSysFiles('Lang4Dev', JPATH_ADMINISTRATOR . '/components/com_lang4dev');
//
//        $prjSysFiles->findFiles ();
//        $this->langFileNamesSetText = $prjSysFiles->__toText ();
//
//		// toDO: use selected main lang of user 'de-DE'
//		$prjLangFile =$prjSysFiles->retrieveLangFileTranslations();
//
//		// collect used project transIds
//		$prjSysFiles->searchLangLocations();
//		$prjTransIdNames = $prjSysFiles->getPrjTransIdNames();
//
//		[$missing, $same, $notUsed] =
//			$prjLangFile->separateByTransIds($prjTransIdNames);
//
//		$this->sysLangIds = [];
//		$this->sysLangIds['missing'] = $missing;
//		$this->sysLangIds['same'] = $same;
//		$this->sysLangIds['notUsed'] = $notUsed;
//
//		$this->prjSysFiles = $prjSysFiles;

        /**
		HTMLHelper::_('sidebar.setAction', 'index.php?option=com_Lang4dev&view=config&layout=RawView');
		/**
		$Layout = Factory::getApplication()->input->get('layout');
		Lang4devHelper::addSubmenu('config');
		$this->sidebar = \JHtmlSidebar::render();
		**/

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
				break;
		}

		// Options button.
		if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_lang4dev'))
		{
			$toolbar->preferences('com_Lang4dev');
		}
	}



}

