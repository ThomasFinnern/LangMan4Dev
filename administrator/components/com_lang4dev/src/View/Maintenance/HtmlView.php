<?php
/**
 * @package       Joomla.Administrator
 * @subpackage    com_lang4dev
 *
 * @copyright (c) 2022-2023 Lang4dev Team
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Finnern\Component\Lang4dev\Administrator\View\Maintenance;

defined('_JEXEC') or die;

use JForm;
use JObject;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

use Finnern\Component\Lang4dev\Administrator\Helper\lang4devVersion;
use JPagination;

use function defined;

/**
 * View class for a list of lang4dev.
 *
 * @since  __BUMP_VERSION__
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
     * The pagination object
     *
     * @var  JPagination
     */
    protected $pagination;

    /**
     * The model state
     *
     * @var  JObject
     */
    protected $state;

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

    protected $extensionVersion;

    protected $isDebugBackend;
    protected $isDevelop;

    /**
     * Method to display the view.
     *
     * @param   string  $tpl  A template file to load. [optional]
     *
     * @return  void
     *
     * @since   __BUMP_VERSION__
     */
    public function display($tpl = null): void
    {
        $oVersion = new lang4devVersion();
        // $this->extensionVersion = $oVersion->getShortVersion(); // getLongVersion, getVersion
        $this->extensionVersion = $oVersion->getVersion(); // getLongVersion, getVersion

        $l4dConfig            = ComponentHelper::getComponent('com_lang4dev')->getParams();
        $this->isDebugBackend = $l4dConfig->get('isDebugBackend');
        $this->isDevelop      = $l4dConfig->get('isDevelop');

        /**
         * $this->items = $this->get('Items');
         *
         * $this->pagination = $this->get('Pagination');
         *
         * $this->filterForm = $this->get('FilterForm');
         * $this->activeFilters = $this->get('ActiveFilters');
         * $this->state = $this->get('State');
         *
         * // Check for errors.
         * if (count($errors = $this->get('Errors'))) {
         * throw new GenericDataException(implode("\n", $errors), 500);
         * }
         *
         * // Preprocess the list of items to find ordering divisions.
         * // ToDo: Complete the ordering stuff with nested sets
         * foreach ($this->items as &$item) {
         * $item->order_up = true;
         * $item->order_dn = true;
         * }
         *
         * if (!count($this->items) && $this->get('IsEmptyState')) {
         * $this->setLayout('emptystate');
         * }
         *
         * // We don't need toolbar in the modal window.
         * if ($this->getLayout() !== 'modal') {
         * $this->addToolbar();
         * $this->sidebar = \JHtmlSidebar::render();
         * } else {
         * // In article associations modal we need to remove language filter if forcing a language.
         * // We also need to change the category filter to show show categories with All or the forced language.
         * if ($forcedLanguage = Factory::getApplication()->input->get('forcedLanguage', '', 'CMD')) {
         * // If the language is forced we can't allow to select the language, so transform the language selector filter into a hidden field.
         * $languageXml = new \SimpleXMLElement('<field name="language" type="hidden" default="' . $forcedLanguage . '" />');
         * $this->filterForm->setField($languageXml, 'filter', true);
         *
         * // Also, unset the active language filter so the search tools is not open by default with this filter.
         * unset($this->activeFilters['language']);
         *
         * // One last changes needed is to change the category filter to just show categories with All language or with the forced language.
         * $this->filterForm->setFieldAttribute('category_id', 'language', '*,' . $forcedLanguage, 'filter');
         * }
         * }
         * /**/

        $this->addToolbar();

        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     *
     * @since __BUMP_VERSION__
     */
    protected function addToolbar()
    {
        // Get the toolbar object instance
        $toolbar = Toolbar::getInstance('toolbar');

        // on develop show open tasks if existing
        if (!empty ($this->isDevelop)) {
            echo '<span style="color:red">'
                . '<b>Tasks:</b> <br>'
                . '* <br>'
//				. '* <br>'
//				. '* <br>'
//				. '* <br>'
                . '</span><br><br>';
        }

        // Set the title
        ToolBarHelper::title(Text::_('COM_LANG4DEV_SUBMENU_MAINTENANCE'), 'equalizer');

        // Options button.
        if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_lang4dev')) {
            $toolbar->preferences('com_lang4dev');
        }
    }

}
