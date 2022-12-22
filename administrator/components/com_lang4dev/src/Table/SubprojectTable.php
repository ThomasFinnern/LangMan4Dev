<?php
/**
 * @package       Joomla.Administrator
 * @subpackage    com_lang4dev
 *
 * @copyright (C) 2022-2022 Lang4dev Team
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Finnern\Component\Lang4dev\Administrator\Table;

defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Nested;
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;
use Joomla\Registry\Registry;
use Joomla\String\StringHelper;
use UnexpectedValueException;

use function defined;

/**
 * Subproject table
 *
 * @since __BUMP_VERSION__
 */
class SubprojectTable extends Table
{
    /**
     * Constructor
     *
     * @param   DatabaseDriver  $db  Database connector object
     *
     * @since __BUMP_VERSION__
     */
    public function __construct(DatabaseDriver $db)
    {
        $this->typeAlias = 'com_lang4dev.subprojects';

        parent::__construct('#__lang4dev_subprojects', 'id', $db);

        $this->access = (int)Factory::getApplication()->get('access');
    }

    /**
     * Overloaded bind function
     *
     * @param   array  $array   Named array
     * @param   mixed  $ignore  An optional array or space separated list of properties
     *                          to ignore while binding.
     *
     * @return  mixed  Null if operation was satisfactory, otherwise returns an error string
     *
     * @see     \JTable::bind
     * @since   __BUMP_VERSION__
     */
    public function bind($array, $ignore = '')
    {
        if (isset($array['params']) && is_array($array['params'])) {
            $registry        = new Registry($array['params']);
            $array['params'] = (string)$registry;
        }

        return parent::bind($array, $ignore);
    }

    /**
     * Overloaded check method to ensure data integrity.
     *
     * @return  boolean  True on success.
     *
     * @throws  UnexpectedValueException
     * @since __BUMP_VERSION__
     */
    public function check()
    {
        try {
            parent::check();
        } catch (Exception $e) {
            $this->setError($e->getMessage());

            return false;
        }

        // Check for valid project id.
        if (trim($this->prjId) == '') {
            throw new UnexpectedValueException(sprintf('The prjId is empty'));
        }

        // //--- alias -------------------------------------------------------------

        // // ToDo: aliases must be singular see below store ?
        // if (empty($this->alias)) {
        // $this->alias = $this->name;
        // }

        // $this->alias = ApplicationHelper::stringURLSafe($this->alias, $this->language);

        // // just minuses -A use date
        // if (trim(str_replace('-', '', $this->alias)) == '')
        // {
        // $this->alias = Factory::getDate()->format('Y-m-d-H-i-s');
        // }

//        //--- twin id: check if twin exists -------------------------------------
//
//        $this->twin_id = (int)$this->twin_id;
//
//        // Nested does not allow parent_id = 0, override this.
//        if ($this->twin_id > 0) {
//            // Get the DatabaseQuery object
//            $query = $this->_db->getQuery(true)
//                ->select('1')
//                ->from($this->_db->quoteName($this->_tbl))
//                ->where($this->_db->quoteName('id') . ' = ' . $this->twin_id);
//
//            $query->setLimit(1);
//
//            if (empty ($this->_db->setQuery($query)->loadResult())) {
//                $this->setError(Text::_('JLIB_DATABASE_ERROR_INVALID_PARENT_ID'));
//
//                return false;
//            }
//        }

        //---   ---------------------------------------------

        // Clean up description -- eliminate quotes and <> brackets

//        if (!empty($this->description))
//        {
//            // Only process if not empty
//            $bad_characters = array("\"", '<', '>');
//            $this->description = StringHelper::str_ireplace($bad_characters, '', $this->description);
//        }        else         {
//            $this->description = '';
//        }
        if (empty($this->note)) {
            $this->note = '';
        }

        if (empty($this->lang_ids)) {
            $this->lang_ids = '';
        }

        if (empty($this->params)) {
            $this->params = '{}';
        }

        if (!(int)$this->checked_out_time) {
            $this->checked_out_time = null;
        }

        // if (!(int) $this->publish_up)
        // {
        // $this->publish_up = null;
        // }

        // if (!(int) $this->publish_down)
        // {
        // $this->publish_down = null;
        // }

        return true;
    }

    /**
     * Stores a Subproject.
     *
     * @param   boolean  $updateNulls  True to update fields even if they are null.
     *
     * @return  boolean  True on success, false on failure.
     *
     * @since __BUMP_VERSION__
     */
    public function store($updateNulls = false)
    {
        $date = Factory::getDate();
        $app  = Factory::getApplication();
        $user = $app->getIdentity();

        if ($this->id) {
            // Existing item
            $this->modified    = $date->toSql();
            $this->modified_by = $user->get('id');
        } else {
            // New tag. A tag created and created_by field can be set by the user,
            // so we don't touch either of these if they are set.
            if (!(int)$this->created) {
                $this->created = $date->toSql();
            }

            if (empty($this->created_by)) {
                $this->created_by = $user->get('id');
            }

            if (!(int)$this->modified) {
                $this->modified = $this->created;
            }

            if (empty($this->modified_by)) {
                $this->modified_by = $this->created_by;
            }

            // Text must be preset
            if ($this->note == null) {
                $this->note = '';
            }
        }

        // Verify that the alias is unique
        $table = new static($this->getDbo());

        if ($table->load(array('alias' => $this->alias)) && ($table->id != $this->id || $this->id == 0)) {
            $this->setError(Text::_('COM_LANG4DEV_ERROR_UNIQUE_ALIAS'));

            return false;
        }

        return parent::store($updateNulls);
    }

    /**
     * Method to delete a node and, optionally, its child nodes from the table.
     *
     * @param   integer  $pk        The primary key of the node to delete.
     * @param   boolean  $children  True to delete child nodes, false to move them up a level.
     *
     * @return  boolean  True on success.
     *
     * @since __BUMP_VERSION__
     */
    public function delete($pk = null, $children = false)
    {
        $return = parent::delete($pk, $children);

        if ($return) {
//            $helper = new TagsHelper;
//            $helper->tagDeleteInstances($pk);
        }

        return $return;
    }

} // class
