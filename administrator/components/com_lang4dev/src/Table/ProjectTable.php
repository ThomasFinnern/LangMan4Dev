<?php
/**
 * @package       Joomla.Administrator
 * @subpackage    com_lang4dev
 *
 * @copyright  (c)  2022-2024 Lang4dev Team
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
 * Project table
 *
 * @since __BUMP_VERSION__
 */
class ProjectTable extends Table
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
        $this->typeAlias = 'com_lang4dev.projects';

        parent::__construct('#__lang4dev_projects', 'id', $db);

	    $this->created = Factory::getDate()->toSql();
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
	 * Stores a Project.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success, false on failure.
	 *
	 * @since __BUMP_VERSION__
	 */
	public function store($updateNulls = true)
	{
		$date   = Factory::getDate()->toSql();
		$userId = Factory::getUser()->id;

		// Set created date if not set.
		if (!(int) $this->created) {
			$this->created = $date;
		}

		if ($this->id) {
			// Existing item
			$this->modified_by = $userId;
			$this->modified    = $date;
		} else {
			// Field created_by field can be set by the user, so we don't touch it if it's set.
			if (empty($this->created_by)) {
				$this->created_by = $userId;
			}

			if (!(int) $this->modified) {
				$this->modified = $date;
			}

			if (empty($this->modified_by)) {
				$this->modified_by = $userId;
			}

			// Text must be preset
			if ($this->note == null) {
				$this->note = '';
			}


		}

//		// Verify that the alias is unique
//		$table = Table::getInstance('ContactTable', __NAMESPACE__ . '\\', array('dbo' => $this->getDbo()));
//
//		if ($table->load(array('alias' => $this->alias, 'catid' => $this->catid)) && ($table->id != $this->id || $this->id == 0)) {
//			// Is the existing contact trashed?
//			$this->setError(Text::_('COM_CONTACT_ERROR_UNIQUE_ALIAS'));
//
//			if ($table->published === -2) {
//				$this->setError(Text::_('COM_CONTACT_ERROR_UNIQUE_ALIAS_TRASHED'));
//			}
//
//			return false;
//		}

//		// Verify that the alias is unique
//		$table = new static($this->getDbo());
//
//		if ($table->load(array('alias' => $this->alias)) && ($table->id != $this->id || $this->id == 0)) {
//			$this->setError(Text::_('COM_LANG4DEV_ERROR_UNIQUE_ALIAS'));
//
//			return false;
//		}

		return parent::store($updateNulls);
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
	    $date   = Factory::getDate()->toSql();
	    $userId = Factory::getUser()->id;

	    try {
            parent::check();
        } catch (Exception $e) {
            $this->setError($e->getMessage());

            return false;
        }

        // Check for valid name.
        if (trim($this->name) == '') {
            throw new UnexpectedValueException(sprintf('The project name is empty')); // COM_CONTACT_WARNING_PROVIDE_VALID_NAME
        }

	    // Set name
	    $this->name = htmlspecialchars_decode($this->name, ENT_QUOTES);

	    // Check for valid name.
	    if (trim($this->title) == '') {
		    throw new UnexpectedValueException(sprintf('The title is empty')); // COM_CONTACT_WARNING_PROVIDE_VALID_TITLE
	    }

	    $this->title = htmlspecialchars_decode($this->title, ENT_QUOTES);

	    //--- alias -------------------------------------------------------------

	    // Generate a valid alias
	    $this->generateAlias();


        //--- twin id: check if twin exists -------------------------------------

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

        if (empty($this->params)) {
            $this->params = '{}';
        }

	    if (!(int)$this->checked_out_time) {
		    $this->checked_out_time = null;
	    }

	    // Set created date if not set.
	    if (!(int) $this->created) {
		    $this->created = $date;
	    }

	    if (empty($this->created_by)) {
		    $this->created_by = $userId;
	    }

	    if (!(int) $this->modified) {
		    $this->modified = $this->created;
	    }

	    if (empty($this->modified_by)) {
		    $this->modified_by = $this->created_by;
	    }

        return true;
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
        //$return = parent::delete($pk, $children);
        $return = parent::delete($pk);

        if ($return) {
            // ToDo: subProject
//            $helper = new TagsHelper;
//            $helper->tagDeleteInstances($pk);
        }

        return $return;
    }

	/**
	 * Generate a valid alias from title / date.
	 * Remains public to be able to check for duplicated alias before saving
	 *
	 * @return  string
	 */
	public function generateAlias()
	{
		if (empty($this->alias)) {
			$this->alias = $this->title;
		}

		$this->alias = ApplicationHelper::stringURLSafe($this->alias, $this->language);

		if (trim(str_replace('-', '', $this->alias)) == '') {
			$this->alias = Factory::getDate()->format('Y-m-d-H-i-s');
		}

		return $this->alias;
	}


} // class
