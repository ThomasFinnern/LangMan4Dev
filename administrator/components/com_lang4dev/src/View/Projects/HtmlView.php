<?php
/**
 * @package         LangMan4Dev
 * @subpackage      com_lang4dev
 * @author          Thomas Finnern <InsideTheMachine.de>
 * @copyright  (c)  2022-2025 Lang4dev Team
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Finnern\Component\Lang4dev\Administrator\View\Projects;

defined('_JEXEC') or die;

use JForm;
use JObject;
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

//use Finnern\Component\Lang4dev\Administrator\Helper\Lang4devHelper;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;

use function defined;

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
     * @var  JObject
     */
    protected $state;

    /**
     * The pagination object
     *
     * @var
     * @since __BUMP_VERSION__
     */
    protected $pagination;
    /**
     * Form object for search filters
     *
     * @var  JForm
     */
    public $filterForm;

    /**
     * The active search filters
     *
     * @var  array
     */
    public $activeFilters;

    /**
     * @var
     * @since version
     */
    protected $isDebugBackend;
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

        $l4dConfig       = ComponentHelper::getComponent('com_lang4dev')->getParams();
        $this->isDevelop = $l4dConfig->get('isDevelop');

        $this->items         = $this->get('Items');
        $this->state         = $this->get('State');
        $this->filterForm    = $this->get('FilterForm');
        $this->pagination    = $this->get('Pagination');
        $this->activeFilters = $this->get('ActiveFilters');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new GenericDataException(implode("\n", $errors), 500);
        }

        //---  --------------------------------------------------------------
        /**
         * $Layout = Factory::getApplication()->input->get('layout');
         * Lang4devHelper::addSubmenu('config');
         * $this->sidebar = \JHtmlSidebar::render();
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
        $canDo = \Joomla\Component\Content\Administrator\Helper\ContentHelper::getActions(
            'com_content',
            'category',
            $this->state->get('filter.category_id')
        );
        $user  = Factory::getUser();

        // Get the toolbar object instance
        $toolbar = Toolbar::getInstance('toolbar');

        // on develop show open tasks if existing
        if (!empty ($this->isDevelop)) {
            echo '<span style="color:red">'
                . '<b>Tasks:</b> <br>'
                . '* Add count subprojects as column<br>'
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
                ToolBarHelper::title(Text::_('COM_LANG4DEV_SUBMENU_PROJECTS_PANEL'), 'edit');

                if ($canDo->get('core.create')) {
                    ToolBarHelper::addNew('project.add');
                }
                /**
                 * if ($canDo->get('core.edit.state') || count($this->transitions)) {
                 * $dropdown = $toolbar->dropdownButton('status-group')
                 * ->text('JTOOLBAR_CHANGE_STATUS')
                 * ->toggleSplit(false)
                 * ->icon('fa fa-ellipsis-h')
                 * ->buttonClass('btn btn-action')
                 * ->listCheck(true);
                 *
                 * $childBar = $dropdown->getChildToolbar();
                 *
                 * if ($canDo->get('core.edit.state')) {
                 * $childBar->publish('images.publish')->listCheck(true);
                 *
                 * $childBar->unpublish('images.unpublish')->listCheck(true);
                 *
                 * $childBar->archive('images.archive')->listCheck(true);
                 *
                 * $childBar->checkin('images.checkin')->listCheck(true);
                 *
                 * $childBar->trash('images.trash')->listCheck(true);
                 *
                 * // $toolbar->standardButton('refresh')
                 * //    ->text('JTOOLBAR_REBUILD')
                 * //    ->task('image.rebuild');
                 *
                 * }
                 *
                 *
                 * //                    if ($this->state->get('filter.published') == ContentComponent::CONDITION_TRASHED
                 * //                        && $canDo->get('core.delete'))
                 * if ($canDo->get('core.delete')) {
                 * $toolbar->delete('images.delete')
                 * ->text('JTOOLBAR_EMPTY_TRASH')
                 * ->message('JGLOBAL_CONFIRM_DELETE')
                 * ->listCheck(true);
                 * }
                 *
                 * ToolBarHelper::custom('imagesProperties.PropertiesView', 'next', 'next', 'COM_LANG4DEV_ADD_IMAGE_PROPERTIES', true);
                 * // ToolBarHelper::editList('image.edit');
                 *
                 *
                 * }
                 * /**/

                if ($canDo->get('core.delete')) {
                    $toolbar->delete('projects.delete')
                        ->text('JTOOLBAR_EMPTY_TRASH')
                        ->message('JGLOBAL_CONFIRM_DELETE')
                        ->listCheck(true);
                }

                ToolBarHelper::cancel('lang4dev.cancel', 'JTOOLBAR_CLOSE');

                // Options button.
                if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_lang4dev')) {
                    $toolbar->preferences('com_lang4dev');
                }

                break;
        }  // switch

    }

}

