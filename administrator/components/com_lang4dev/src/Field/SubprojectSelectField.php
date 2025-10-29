<?php
/*
 * @package     Lang4dev
 * @subpackage  com_lang4dev
 * @copyright  (c)  2022-2025 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      lang4dev team
 * RSGallery is Free Software
 */

// used in upload

namespace Finnern\Component\Lang4dev\Administrator\Field;

defined('_JEXEC') or die;

use Finnern\Component\Lang4dev\Administrator\Helper\sessionProjectId;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use RuntimeException;

use function defined;

/**
 * @package         LangMan4Dev
 * @subpackage      com_lang4dev
 * @author          Thomas Finnern <InsideTheMachine.de>
 * @copyright  (c)  2019-2025 RSGallery2 Team
 * @license         GNU General Public License version 2 or later
 */
class SubprojectSelectField extends ListField
{

    protected $prjId = -1;

    /**
     * The field type.
     *
     * @var string
     *
     * @since __BUMP_VERSION__
     */
    protected $type = 'SubprojectSelect';

    /**/
    /**
     *
     * @return string
     *
     * @since version
     */
    protected function getInput()
    {
        //--- Set selection of project and subproject --------------------

        $sessionProjectId = new sessionProjectId();
        [$prjId, $subPrjActive] = $sessionProjectId->getIds();

        $this->setValue($subPrjActive);
        /**
         * $this->prjId = $prjId;
         *
         * if ($this->form->getValue('id', 0) == 0)
         * {
         * return '<span class="readonly">' . Text::_('COM_MENUS_ITEM_FIELD_ORDERING_TEXT') . '</span>';
         * }
         * else
         * {
         * return parent::getInput();
         * }
         * /**/
        /**
         * if($subPrjActive > 0)
         * {
         * return parent::getInput();
         * }
         * else {
         * if($subPrjActive == 0) {
         * return '<span class="readonly">' . Text::_('All subprojects') . '</span>';
         * } else
         * {
         * return '<span class="readonly">' . Text::_('??? -1 ???') . '</span>';
         * }
         * }
         * /**/

        return parent::getInput();
    }
    /**/

    /**
     * Method to get a list of options for a list input.
     *
     * @return  string []  The field option objects.
     *
     * @since __BUMP_VERSION__
     */
    protected function getOptions()
    {
        $subprojects = array();

        try {
            //--- Set selection of project and subproject --------------------

            $sessionProjectId = new sessionProjectId();
            [$prjId, $subPrjActive] = $sessionProjectId->getIds();

            $this->value = $subPrjActive;

            // $user = Factory::getApplication()->getIdentity(); // ToDo: Restrict to accessible subprojects
            $db = Factory::getDbo();

            $query = $db->createQuery()
                ->select($db->quoteName('id', 'value'))
                ->select($db->quoteName('title', 'text'))
                ->where($db->quoteName('parent_id') . ' = ' . (int)$prjId)
                ->from($db->quoteName('#__lang4dev_subprojects'))
                // ToDo: Use option in XML to select ASC/DESC
                ->order($db->quoteName('id') . ' DESC');

            // Get the options.
            $subprojects = $db->setQuery($query)->loadObjectList();
        } catch (RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        $options = $subprojects;

//        // Pad the option text with spaces using depth level as a multiplier.
//        for ($i = 0, $n = count($options); $i < $n; $i++) {
//            $options[$i]->text = str_repeat('- ', !$options[$i]->level ? 0 : $options[$i]->level - 1) . $options[$i]->text;
//        }

        // Put "Select an option" on the top of the list.
        array_unshift($options, HTMLHelper::_('select.option', '0', Text::_('COM_LANG4DEV_SELECT_SUB_PROJECT_ALL')));

        // Merge any additional options in the XML definition.
        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }
}



