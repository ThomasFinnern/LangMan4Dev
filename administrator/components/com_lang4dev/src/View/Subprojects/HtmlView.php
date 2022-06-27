<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_lang4dev
 *
 * @copyright (C) 2022-2022 Lang4dev Team
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Finnern\Component\Lang4dev\Administrator\View\Subprojects;

\defined('_JEXEC') or die;

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

use Joomla\Component\Content\Administrator\Extension\ContentComponent;

use Finnern\Component\Lang4dev\Administrator\Helper\Lang4devHelper;
/**
 * View class for a list of lang4dev.
 *
 * @since __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
    /**
     * An array of items
     *
     * @var  array
     */
    protected $items;

    /**
     * The model state
     *
     * @var  \JObject
     */
    protected $state;

    /**
     * The pagination object
     *
     * @var    Pagination
     * @since __BUMP_VERSION__
     */
    protected $pagination;
    /**
     * Form object for search filters
     *
     * @var  \JForm
     */
    public $filterForm;

    /**
     * The active search filters
     *
     * @var  array
     */
    public $activeFilters;


//    protected $isDebugBackend;
    protected $isDevelop;


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

		$l4dConfig = ComponentHelper::getComponent('com_lang4dev')->getParams();
		$this->isDevelop = $l4dConfig->get('isDevelop');

        $this->items         = $this->get('Items');
        $errors = $this->get('Errors');
        $this->state         = $this->get('State');
        $errors = $this->get('Errors');
        $this->filterForm    = $this->get('FilterForm');
        $errors = $this->get('Errors');
        $this->pagination    = $this->get('Pagination');
        $errors = $this->get('Errors');
        $this->activeFilters = $this->get('ActiveFilters');

		//---  --------------------------------------------------------------
		/**
		HTMLHelper::_('sidebar.setAction', 'index.php?option=com_lang4dev&view=config&layout=RawView');
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
    	$canDo = \Joomla\Component\Content\Administrator\Helper\ContentHelper::getActions('com_content', 'category', $this->state->get('filter.category_id'));
        $user = Factory::getUser();

        // Get the toolbar object instance
        $toolbar = Toolbar::getInstance('toolbar');

        // on develop show open tasks if existing
        if (!empty ($this->isDevelop)) {
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
                // Set the title
                ToolBarHelper::title(Text::_('COM_LANG4DEV_SUBMENU_SUBPROJECTS_PANEL'), 'edit');


	            if ($canDo->get('core.create')) {
	                ToolBarHelper::addNew('subproject.add');
                }
                /**
                if ($canDo->get('core.edit.state') || count($this->transitions)) {
                    $dropdown = $toolbar->dropdownButton('status-group')
                        ->text('JTOOLBAR_CHANGE_STATUS')
                        ->toggleSplit(false)
                        ->icon('fa fa-ellipsis-h')
                        ->buttonClass('btn btn-action')
                        ->listCheck(true);

                    $childBar = $dropdown->getChildToolbar();

                    if ($canDo->get('core.edit.state')) {
                        $childBar->publish('images.publish')->listCheck(true);

                        $childBar->unpublish('images.unpublish')->listCheck(true);

                        $childBar->archive('images.archive')->listCheck(true);

                        $childBar->checkin('images.checkin')->listCheck(true);

                        $childBar->trash('images.trash')->listCheck(true);

                        // $toolbar->standardButton('refresh')
                        // 	->text('JTOOLBAR_REBUILD')
                        // 	->task('image.rebuild');

                    }


//                    if ($this->state->get('filter.published') == ContentComponent::CONDITION_TRASHED
//                        && $canDo->get('core.delete'))
                    if ($canDo->get('core.delete')) {
                        $toolbar->delete('images.delete')
                            ->text('JTOOLBAR_EMPTY_TRASH')
                            ->message('JGLOBAL_CONFIRM_DELETE')
                            ->listCheck(true);
                    }

                    ToolBarHelper::custom('imagesProperties.PropertiesView', 'next', 'next', 'COM_LANG4DEV_ADD_IMAGE_PROPERTIES', true);
                    // ToolBarHelper::editList('image.edit');


                }
                /**/

	            if ($canDo->get('core.delete'))
	            {
		            $toolbar->delete('subprojects.delete')
			            ->text('JTOOLBAR_EMPTY_TRASH')
			            ->message('JGLOBAL_CONFIRM_DELETE')
			            ->listCheck(true)
		            ;
	            }

//icon-ellipsis-h

                ToolBarHelper::cancel('lang4dev.cancel', 'JTOOLBAR_CLOSE');

                // Options button.
                if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_lang4dev')) {
                    $toolbar->preferences('com_lang4dev');
                }

            break;
        }  // switch

    }

}

