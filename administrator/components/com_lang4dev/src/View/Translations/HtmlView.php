<?php
/**
 * @package         LangMan4Dev
 * @subpackage      com_lang4dev
 * @author          Thomas Finnern <InsideTheMachine.de>
 * @copyright  (c)  2022-2025 Lang4dev Team
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Finnern\Component\Lang4dev\Administrator\View\Translations;

defined('_JEXEC') or die;

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

use function defined;

//use Finnern\Component\Lang4dev\Administrator\Helper\Lang4devHelper;

/**
 * View class for a list of lang4dev.
 *
 * @since __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
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

        $l4dConfig            = ComponentHelper::getComponent('com_lang4dev')->getParams();
        $this->isDebugBackend = $l4dConfig->get('isDebugBackend');
        $this->isDevelop      = $l4dConfig->get('isDevelop');

        //---  --------------------------------------------------------------
        /**
         * $Layout = Factory::getApplication()->input->get('layout');
         * Lang4devHelper::addSubmenu('config');
         * $this->sidebar = \JHtmlSidebar::render();
         **/

        $this->addToolbar($Layout);
        /**/

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
             *
             * break;
             *
             * case 'RawEdit':
             *
             * break;
             * /**/
            default:
                ToolBarHelper::cancel('lang4dev.cancel', 'JTOOLBAR_CLOSE');
                break;
        }

        // Set the title
        ToolBarHelper::title(Text::_('COM_LANG4DEV_SUBMENU_TRANSLATIONS'), 'book');

        // Options button.
        if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_lang4dev')) {
            $toolbar->preferences('com_lang4dev');
        }
    }

}

