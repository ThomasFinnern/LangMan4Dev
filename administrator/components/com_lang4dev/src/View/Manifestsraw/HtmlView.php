<?php
/**
 * @package         LangMan4Dev
 * @subpackage      com_lang4dev
 * @author          Thomas Finnern <InsideTheMachine.de>
 * @copyright  (c)  2022-2025 Lang4dev Team
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Finnern\Component\Lang4dev\Administrator\View\ManifestsRaw;

defined('_JEXEC') or die;

// require_once(__DIR__ . '/../../Helper/selectProject.php');

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
    protected $PrjManifestsData;

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

        //--- Collect manifests -------------------------------------------------------------------

        /** @var ProjectsRawModel $model */
        $model         = $this->getModel();
        $this->PrjManifestsData = $model->getManifests();

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
                ToolBarHelper::cancel('maintenance.cancel', 'JTOOLBAR_CLOSE');


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

