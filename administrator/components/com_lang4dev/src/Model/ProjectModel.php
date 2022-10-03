<?php
/**
 * @package       Joomla.Administrator
 * @subpackage    com_lang4dev
 *
 * @copyright (C) 2022-2022 Lang4dev Team
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Finnern\Component\Lang4dev\Administrator\Model;

defined('_JEXEC') or die;

use Exception;
use Finnern\Component\Lang4dev\Administrator\Helper\langSubProject;
use Finnern\Component\Lang4dev\Administrator\Helper\manifestLangFiles;
use Finnern\Component\Lang4dev\Administrator\Helper\projectType;
use JForm;
use Joomla\CMS\Access\Rules;
use Joomla\CMS\Association\AssociationServiceInterface;
use Joomla\CMS\Categories\CategoryServiceInterface;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\LanguageHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Table\Table;
use Joomla\CMS\UCM\UCMType;
use Joomla\CMS\Workflow\Workflow;
use Joomla\Registry\Registry;
use Joomla\String\StringHelper;
use Joomla\Utilities\ArrayHelper;
use Finnern\Component\Lang4dev\Administrator\Helper\subPrjPath;
use JTableCategory;

use function defined;

// associations: use Finnern\Component\Lang4def\Administrator\Helper\Lang4devHelper;

/**
 * Lang4dev Component Project Model
 *
 * @since __BUMP_VERSION__
 */
class ProjectModel extends AdminModel
{
    /**
     * The prefix to use with controller messages.
     *
     * @var    string
     * @since __BUMP_VERSION__
     */
    protected $text_prefix = 'COM_LANG4DEV';

    /**
     * The type alias for this content type. Used for content version history.
     *
     * @var      string
     * @since __BUMP_VERSION__
     */
    public $typeAlias = 'com_lang4dev.project';

    /**
     * The context used for the associations table
     *
     * @var      string
     * @since __BUMP_VERSION__
     */
    protected $associationsContext = 'com_lang4dev.project';

    /**
     * Override parent constructor.
     *
     * @param   array                $config   An optional associative array of configuration settings.
     * @param   MVCFactoryInterface  $factory  The factory.
     *
     * @see     \Joomla\CMS\MVC\Model\BaseDatabaseModel
     * @since   __BUMP_VERSION__
     *
     * public function __construct($config = array(), MVCFactoryInterface $factory = null)
     * {
     * $extension = Factory::getApplication()->input->get('extension', 'com_lang4dev');
     * $this->typeAlias = $extension . '.category';
     *
     * // Add a new batch command
     * $this->batch_commands['flip_ordering'] = 'batchFlipordering';
     *
     * parent::__construct($config, $factory);
     * }
     * /**/

    /**
     * Method to test whether a record can be deleted.
     *
     * @param   object  $record  A record object.
     *
     * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
     *
     * @since __BUMP_VERSION__
     */
    protected function canDelete($record)
    {
//		if (empty($record->id) || $record->published != -2)
        if (empty($record->id)) {
            return false;
        }

        return Factory::getApplication()->getIdentity()->authorise(
            'core.delete',
            $record->extension . '.category.' . (int)$record->id
        );
    }

    /**
     * Method to test whether a record can have its state changed.
     *
     * @param   object  $record  A record object.
     *
     * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
     *
     * @since __BUMP_VERSION__
     */
    protected function canEditState($record)
    {
        $app  = Factory::getApplication();
        $user = $app->getIdentity();

        // Check for existing category.
        if (!empty($record->id)) {
            return $user->authorise('core.edit.state', $record->extension . '.category.' . (int)$record->id);
        }

        // New category, so check against the parent.
        if (!empty($record->parent_id)) {
            return $user->authorise('core.edit.state', $record->extension . '.category.' . (int)$record->parent_id);
        }

        // Default to component settings if neither category nor parent known.
        return $user->authorise('core.edit.state', $record->extension);
    }

    /**
     * Method to get a table object, load it if necessary.
     *
     * @param   string  $type    The table name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return  Table  A JTable object
     *
     * @since __BUMP_VERSION__
     */
    public function getTable($type = 'Project', $prefix = 'Lang4devTable', $config = array())
    {
        return parent::getTable($type, $prefix, $config);
    }

    /**
     * Auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @return  void
     *
     * @since __BUMP_VERSION__
     */
    protected function populateState()
    {
        $app = Factory::getApplication();

        $parentId = $app->input->getInt('parent_id');
        $this->setState('category.parent_id', $parentId);

        // Load the User state.
        $pk = $app->input->getInt('id');
        $this->setState($this->getName() . '.id', $pk);

        $extension = $app->input->get('extension', 'com_lang4dev');
        $this->setState('category.extension', $extension);
        $parts = explode('.', $extension);

        // Extract the component name
        $this->setState('category.component', $parts[0]);

        // Extract the optional section name
        $this->setState('category.section', (count($parts) > 1) ? $parts[1] : null);

        // Load the parameters.
        $params = ComponentHelper::getParams('com_lang4dev');
        $this->setState('params', $params);
    }

    /**
     * Method to get a single record.
     *
     * @param   integer  $pk  The id of the primary key.
     *
     * @return  mixed  Object on success, false on failure.
     *
     * @since __BUMP_VERSION__
     */
    public function getItem($pk = null)
    {
        $item = parent::getItem($pk);

        // Load associated foo items
        $assoc = Associations::isEnabled();

        if ($assoc) {
            $item->associations = array();

            if ($item->id != null) {
                $associations = Associations::getAssociations(
                    'com_lang4dev',
                    '#__com_lang4dev_project',
                    'com_lang4dev.item',
                    $item->id,
                    'id',
                    null
                );

                foreach ($associations as $tag => $association) {
                    $item->associations[$tag] = $association->id;
                }
            }
        }

        return $item;
    }

    /**
     * Method to get a category.
     *
     * @param   integer  $pk  An optional id of the object to get, otherwise the id from the model state is used.
     *
     * @return  mixed    Category data object on success, false on failure.
     *
     * @since __BUMP_VERSION__
     *
     * public function getItem($pk = null)
     * {
     * if ($result = parent::getItem($pk))
     * {
     * // Prime required properties.
     * if (empty($result->id))
     * {
     * $result->parent_id = $this->getState('category.parent_id');
     * $result->extension = $this->getState('category.extension');
     * }
     *
     * // Convert the metadata field to an array.
     * $registry = new Registry($result->metadata);
     * $result->metadata = $registry->toArray();
     *
     * // Convert the created and modified dates to local user time for display in the form.
     * $tz = new \DateTimeZone(Factory::getApplication()->get('offset'));
     *
     * if ((int) $result->created_time)
     * {
     * $date = new Date($result->created_time);
     * $date->setTimezone($tz);
     * $result->created_time = $date->toSql(true);
     * }
     * else
     * {
     * $result->created_time = null;
     * }
     *
     * if ((int) $result->modified_time)
     * {
     * $date = new Date($result->modified_time);
     * $date->setTimezone($tz);
     * $result->modified_time = $date->toSql(true);
     * }
     * else
     * {
     * $result->modified_time = null;
     * }
     *
     * if (!empty($result->id))
     * {
     * //                $result->tags = new TagsHelper;
     * //                $result->tags->getTagIds($result->id, $result->extension . '.category');
     * }
     * }
     *
     * /**
     * $assoc = $this->getAssoc();
     *
     * if ($assoc)
     * {
     * if ($result->id != null)
     * {
     * $result->associations = ArrayHelper::toInteger(GalleriesHelper::getAssociations($result->id, $result->extension));
     * }
     * else
     * {
     * $result->associations = array();
     * }
     * }
     * /**
     *
     * return $result;
     * }
     * /**/

    /**
     * Method to get the row form.
     *
     * @param   array    $data      Data for the form.
     * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
     *
     * @return  JForm|boolean  A JForm object on success, false on failure
     *
     * @since __BUMP_VERSION__
     */
    public function getForm($data = array(), $loadData = true)
    {
        /**
         * $extension = $this->getState('category.extension');
         * $jinput = Factory::getApplication()->input;
         *
         * // A workaround to get the extension into the model for save requests.
         * if (empty($extension) && isset($data['extension']))
         * {
         * $extension = $data['extension'];
         * $parts = explode('.', $extension);
         *
         * $this->setState('category.extension', $extension);
         * $this->setState('category.component', $parts[0]);
         * $this->setState('category.section', @$parts[1]);
         * }
         * /**/
        // Get the form.
//		$form = $this->loadForm('com_lang4dev.category' . $extension, 'category', array('control' => 'jform', 'load_data' => $loadData));
        $form = $this->loadForm('com_lang4dev.project', 'project', array('control' => 'jform', 'load_data' => $loadData)
        );

        if (empty($form)) {
            return false;
        }

        /**
         * // Modify the form based on Edit State access controls.
         * if (empty($data['extension']))
         * {
         * $data['extension'] = $extension;
         * }
         *
         * $categoryId = $jinput->get('id');
         * $parts      = explode('.', $extension);
         * $assetKey   = $categoryId ? $extension . '.category.' . $categoryId : $parts[0];
         *
         * if (!Factory::getApplication()->getIdentity()->authorise('core.edit.state', $assetKey))
         * {
         * // Disable fields for display.
         * $form->setFieldAttribute('ordering', 'disabled', 'true');
         * $form->setFieldAttribute('published', 'disabled', 'true');
         *
         * // Disable fields while saving.
         * // The controller has already verified this is a record you can edit.
         * $form->setFieldAttribute('ordering', 'filter', 'unset');
         * $form->setFieldAttribute('published', 'filter', 'unset');
         * }
         * /**/
        return $form;
    }

    /**
     * A protected method to get the where clause for the reorder
     * This ensures that the row will be moved relative to a row with the same extension
     *
     * @param   JTableCategory  $table  Current table instance
     *
     * @return  array  An array of conditions to add to ordering queries.
     *
     * @since __BUMP_VERSION__
     */
    protected function getReorderConditions($table)
    {
        return [
            $this->_db->quoteName('extension') . ' = ' . $this->_db->quote($table->extension),
        ];
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed  The data for the form.
     *
     * @since __BUMP_VERSION__
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $app  = Factory::getApplication();
        $data = $app->getUserState('com_lang4dev.edit.' . $this->getName() . '.data', array());

        if (empty($data)) {
            $data = $this->getItem();

            // Pre-select some filters (Status, Language, Access) in edit form if those have been selected in Category Manager
            if (!$data->id) {
                // Check for which extension the Category Manager is used and get selected fields
                $extension = substr($app->getUserState('com_lang4dev.galleries.filter.extension'), 4);
                $filters   = (array)$app->getUserState('com_lang4dev.galleries.' . $extension . '.filter');

                $data->set(
                    'published',
                    $app->input->getInt(
                        'published',
                        ((isset($filters['published']) && $filters['published'] !== '') ? $filters['published'] : null)
                    )
                );
//				$data->set('language', $app->input->getString('language', (!empty($filters['language']) ? $filters['language'] : null)));
                $data->set(
                    'access',
                    $app->input->getInt(
                        'access',
                        (!empty($filters['access']) ? $filters['access'] : $app->get('access'))
                    )
                );
            }
        }

        // $this->preprocessData('com_lang4dev.category', $data);
        $this->preprocessData('com_lang4dev.project', $data);

        return $data;
    }

    /**
     * Method to preprocess the form.
     *
     * @param   JForm   $form   A JForm object.
     * @param   mixed   $data   The data expected for the form.
     * @param   string  $group  The name of the plugin group to import.
     *
     * @return  void
     *
     * @throws  Exception if there is an error in the form event.
     *
     * protected function preprocessForm(\JForm $form, $data, $group = 'content')
     * {
     * $lang = Factory::getLanguage();
     * $component = $this->getState('category.component');
     * $section = $this->getState('category.section');
     * $extension = Factory::getApplication()->input->get('extension', null);
     *
     * // Get the component form if it exists
     * $name = 'category' . ($section ? ('.' . $section) : '');
     *
     * // Looking first in the component forms folder
     * $path = Path::clean(JPATH_ADMINISTRATOR . "/components/$component/forms/$name.xml");
     *
     * // Looking in the component models/forms folder (J! 3)
     * if (!file_exists($path))
     * {
     * $path = Path::clean(JPATH_ADMINISTRATOR . "/components/$component/models/forms/$name.xml");
     * }
     *
     * // Old way: looking in the component folder
     * if (!file_exists($path))
     * {
     * $path = Path::clean(JPATH_ADMINISTRATOR . "/components/$component/$name.xml");
     * }
     *
     * if (file_exists($path))
     * {
     * $lang->load($component, JPATH_BASE, null, false, true);
     * $lang->load($component, JPATH_BASE . '/components/' . $component, null, false, true);
     *
     * if (!$form->loadFile($path, false))
     * {
     * throw new \Exception(Text::_('JERROR_LOADFILE_FAILED'));
     * }
     * }
     *
     * $componentInterface = Factory::getApplication()->bootComponent($component);
     *
     * if ($componentInterface instanceof CategoryServiceInterface)
     * {
     * $componentInterface->prepareForm($form, $data);
     * }
     * else
     * {
     * // Try to find the component helper.
     * $eName = str_replace('com_', '', $component);
     * $path = Path::clean(JPATH_ADMINISTRATOR . "/components/$component/helpers/category.php");
     *
     * if (file_exists($path))
     * {
     * $cName = ucfirst($eName) . ucfirst($section) . 'HelperCategory';
     *
     * \JLoader::register($cName, $path);
     *
     * if (class_exists($cName) && is_callable(array($cName, 'onPrepareForm')))
     * {
     * $lang->load($component, JPATH_BASE, null, false, false)
     * || $lang->load($component, JPATH_BASE . '/components/' . $component, null, false, false)
     * || $lang->load($component, JPATH_BASE, $lang->getDefault(), false, false)
     * || $lang->load($component, JPATH_BASE . '/components/' . $component, $lang->getDefault(), false, false);
     * call_user_func_array(array($cName, 'onPrepareForm'), array(&$form));
     *
     * // Check for an error.
     * if ($form instanceof \Exception)
     * {
     * $this->setError($form->getMessage());
     *
     * return false;
     * }
     * }
     * }
     * }
     *
     * // Set the access control rules field component value.
     * $form->setFieldAttribute('rules', 'component', $component);
     * $form->setFieldAttribute('rules', 'section', $name);
     *
     * // Association category items
     * if ($this->getAssoc())
     * {
     * $languages = LanguageHelper::getContentLanguages(false, true, null, 'ordering', 'asc');
     *
     * if (count($languages) > 1)
     * {
     * $addform = new \SimpleXMLElement('<form />');
     * $fields = $addform->addChild('fields');
     * $fields->addAttribute('name', 'associations');
     * $fieldset = $fields->addChild('fieldset');
     * $fieldset->addAttribute('name', 'item_associations');
     *
     * foreach ($languages as $language)
     * {
     * $field = $fieldset->addChild('field');
     * $field->addAttribute('name', $language->lang_code);
     * $field->addAttribute('type', 'modal_category');
     * $field->addAttribute('language', $language->lang_code);
     * $field->addAttribute('label', $language->title);
     * $field->addAttribute('translate_label', 'false');
     * $field->addAttribute('extension', $extension);
     * $field->addAttribute('select', 'true');
     * $field->addAttribute('new', 'true');
     * $field->addAttribute('edit', 'true');
     * $field->addAttribute('clear', 'true');
     * }
     *
     * $form->load($addform, false);
     * }
     * }
     *
     * // Trigger the default form events.
     * parent::preprocessForm($form, $data, $group);
     * }
     * /**@since __BUMP_VERSION__
     * @see     \JFormField
     */

    /**
     * Transform some data before it is displayed ? Saved ?
     * extension development 129 bottom
     *
     * @param   JTable  $table
     *
     * @since __BUMP_VERSION__
     */
    /**/
    /**
     * @param $table
     *
     *
     * @throws Exception
     * @since version
     */
    protected function prepareTable($table)
    {
        $date        = Factory::getDate()->toSql();
        $table->name = htmlspecialchars_decode($table->name, ENT_QUOTES);

        if (empty($table->id)) {
            // $table->generateAlias ();

            // Set ordering to the last item if not set
            if (empty($table->ordering)) {
                $db    = $this->getDbo();
                $query = $db->getQuery(true)
                    ->select('MAX(ordering)')
                    ->from($db->quoteName('#__lang4dev_projects'));
                $db->setQuery($query);
                $max = $db->loadResult();

                $table->ordering = $max + 1;

                // Set the values
                $table->date   = $date;
                $table->userid = Factory::getApplication()->getIdentity()->id;
            }

            //$table->ordering = $table->getNextOrder('gallery_id = ' . (int) $table->gallery_id); // . ' AND state >= 0');

            // Set the values
            $table->created    = $date;
            $table->created_by = Factory::getApplication()->getIdentity()->id;
        } else {
            // Set the values
            $table->modified    = $date;
            $table->modified_by = Factory::getApplication()->getIdentity()->id;
        }
        /**
         * // Set the publish date to now
         * if ($table->published == Workflow::CONDITION_PUBLISHED && (int) $table->publish_up == 0)
         * {
         * $table->publish_up = Factory::getDate()->toSql();
         * }
         *
         * if ($table->published == Workflow::CONDITION_PUBLISHED && intval($table->publish_down) == 0)
         * {
         * $table->publish_down = null;
         * }
         *
         * // Increment the content version number.
         * // $table->version++;
         * /**/
    }

    /**
     * Method to save the form data.
     *
     * @param   array  $data  The form data.
     *
     * @return  boolean  True on success.
     *
     * @since __BUMP_VERSION__
     */
    public function save($data)
    {
        $table = $this->getTable();
        $pk    = (!empty($data['id'])) ? $data['id'] : (int)$this->getState($this->getName() . '.id');
        //$isNew      = true;
        $context = $this->option . '.' . $this->name;
        $input   = Factory::getApplication()->input;

        if (!empty($data['tags']) && $data['tags'][0] != '') {
            $table->newTags = $data['tags'];
        }

        /** -> table *
         * // no default value
         * if (empty($data['description']))
         * {
         * $data['description'] = '';
         * }
         *
         * // no default value
         * if (empty($data['params']))
         * {
         * $data['params'] = '';
         * }
         * /**/

        // Include the plugins for the save events.
        PluginHelper::importPlugin($this->events_map['save']);

        // Load the row if saving an existing category.
        if ($pk > 0) {
            $table->load($pk);
            $isNew = false;
        }

//		// Set the new parent id if parent id not matched OR while New/Save as Copy .
//		if ($table->parent_id != $data['parent_id'] || $data['id'] == 0)
//		{
//			$table->setLocation($data['parent_id'], 'last-child');
//		}
//
//		// ToDo: use name instead of title ?
//		// Alter the title for save as copy
//		if ($input->get('task') == 'save2copy')
//		{
//			$origTable = clone $this->getTable();
//			$origTable->load($input->getInt('id'));
//
//			if ($data['title'] == $origTable->title)
//			{
//				list($title, $alias) = $this->generateNewTitle($data['parent_id'], $data['alias'], $data['title']);
//				$data['title'] = $title;
//				$data['alias'] = $alias;
//			}
//			else
//			{
//				if ($data['alias'] == $origTable->alias)
//				{
//					$data['alias'] = '';
//				}
//			}
//
//			$data['published'] = 0;
//		}

//        // Automatic handling of alias for empty fields
//        if (in_array($input->get('task'), array('apply', 'save', 'save2new')) && (!isset($data['id']) || (int) $data['id'] == 0))
//        {
//            if ($data['alias'] == null)
//            {
//                if (Factory::getApplication()->get('unicodeslugs') == 1)
//                {
//                    $data['alias'] = \JFilterOutput::stringURLUnicodeSlug($data['title']);
//                }
//                else
//                {
//                    $data['alias'] = \JFilterOutput::stringURLSafe($data['title']);
//                }
//
//                $table = Table::getInstance('Content', 'JTable');
//
//                if ($table->load(array('alias' => $data['alias'], 'catid' => $data['catid'])))
//                {
//                    $msg = Text::_('COM_CONTENT_SAVE_WARNING');
//                }
//
//                list($title, $alias) = $this->generateNewTitle($data['catid'], $data['alias'], $data['title']);
//                $data['alias'] = $alias;
//
//                if (isset($msg))
//                {
//                    Factory::getApplication()->enqueueMessage($msg, 'warning');
//                }
//            }
//        }

//        // Bind the data.
//		if (!$table->bind($data))
//		{
//			$this->setError($table->getError());
//
//			return false;
//		}
//
//		// Bind the rules.
//		if (isset($data['rules']))
//		{
//			$rules = new Rules($data['rules']);
//			$table->setRules($rules);
//		}
//
//		// Check the data.
//		if (!$table->check())
//		{
//			$this->setError($table->getError());
//
//			return false;
//		}

        // Trigger the before save event.
//		$result = Factory::getApplication()->triggerEvent($this->event_before_save, array($context, &$table, $isNew, $data));
//
//		if (in_array(false, $result, true))
//		{
//			$this->setError($table->getError());
//
//			return false;
//		}

        // Store the data.
//		if (!$table->store())
//		{
//
//			$this->setError($table->getError());
//
//			return false;
//		}

        if (parent::save($data)) {
            /**
             * $assoc = $this->getAssoc();
             *
             * if ($assoc)
             * {
             * // Adding self to the association
             * $associations = $data['associations'] ?? array();
             *
             * // Unset any invalid associations
             * $associations = ArrayHelper::toInteger($associations);
             *
             * foreach ($associations as $tag => $id)
             * {
             * if (!$id)
             * {
             * unset($associations[$tag]);
             * }
             * }
             *
             * // Detecting all item menus
             * $allLanguage = $table->language == '*';
             *
             * if ($allLanguage && !empty($associations))
             * {
             * Factory::getApplication()->enqueueMessage(Text::_('com_lang4dev_ERROR_ALL_LANGUAGE_ASSOCIATED'), 'notice');
             * }
             *
             * // Get associationskey for edited item
             * $db    = $this->getDbo();
             * $query = $db->getQuery(true)
             * ->select($db->quoteName('key'))
             * ->from($db->quoteName('#__associations'))
             * ->where($db->quoteName('context') . ' = ' . $db->quote($this->associationsContext))
             * ->where($db->quoteName('id') . ' = ' . (int) $table->id);
             * $db->setQuery($query);
             * $oldKey = $db->loadResult();
             *
             * // Deleting old associations for the associated items
             * $query = $db->getQuery(true)
             * ->delete($db->quoteName('#__associations'))
             * ->where($db->quoteName('context') . ' = ' . $db->quote($this->associationsContext));
             *
             * if ($associations)
             * {
             * $query->where('(' . $db->quoteName('id') . ' IN (' . implode(',', $associations) . ') OR '
             * . $db->quoteName('key') . ' = ' . $db->quote($oldKey) . ')');
             * }
             * else
             * {
             * $query->where($db->quoteName('key') . ' = ' . $db->quote($oldKey));
             * }
             *
             * $db->setQuery($query);
             *
             * try
             * {
             * $db->execute();
             * }
             * catch (\RuntimeException $e)
             * {
             * $this->setError($e->getMessage());
             *
             * return false;
             * }
             *
             * // Adding self to the association
             * if (!$allLanguage)
             * {
             * $associations[$table->language] = (int) $table->id;
             * }
             *
             * if (count($associations) > 1)
             * {
             * // Adding new association for these items
             * $key = md5(json_encode($associations));
             * $query->clear()
             * ->insert('#__associations');
             *
             * foreach ($associations as $id)
             * {
             * $query->values(((int) $id) . ',' . $db->quote($this->associationsContext) . ',' . $db->quote($key));
             * }
             *
             * $db->setQuery($query);
             *
             * try
             * {
             * $db->execute();
             * }
             * catch (\RuntimeException $e)
             * {
             * $this->setError($e->getMessage());
             *
             * return false;
             * }
             * }
             * }
             * /**/

//            // Trigger the after save event.
//            Factory::getApplication()->triggerEvent($this->event_after_save, array($context, &$table, $isNew, $data));
//
//            // Rebuild the path for the category:
//            if (!$table->rebuildPath($table->id)) {
//                $this->setError($table->getError());
//
//                return false;
//            }
//
//            // Rebuild the paths of the category's children:
//            if (!$table->rebuild($table->id, $table->lft, $table->level, $table->path)) {
//                $this->setError($table->getError());
//
//                return false;
//            }
//
//            $this->setState($this->getName() . '.id', $table->id);
//
//            // Clear the cache
//            $this->cleanCache();

            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @return integer highest ID of created projects
     *
     * @since version
     */
    public function highestProjectId_DB()
    {
        $max = 0; // indicates nothing found in DB

        $db    = Factory::getDbo();
        $query = $db->getQuery(true)
            ->select('MAX(id)')
            ->from($db->quoteName('#__lang4dev_projects'));
        $db->setQuery($query);
        $max = $db->loadResult();

        return (int)$max;
    }

    /**
     *
     * @return int
     *
     * @throws Exception
     * @since version
     */
    public function justSavedId()
    {
        // On save the id is kept in session state
        $newId = (int)$this->getState($this->getName() . '.id');

        return $newId;
    }

    /**
     * @param $oSubPrjPath
     *
     * @return array
     *
     * @since version
     */
    public function subProjectsByPrjId($oSubPrjPath)
    {
        $subProjects = [];

        // List of integers (com has an array of three)
        $prjTypes = projectType::prjTypesByProjectId($oSubPrjPath->prjId);

        foreach ($prjTypes as $prjType) {
            //--- new sub project -------------------------------------------------

            $langSubProject = new langSubProject (
                $oSubPrjPath->prjId,
                $prjType,
                $oSubPrjPath->getRootPath(),
                $oSubPrjPath->getRootManifestPath()
            );

            //--- collect new sub project ------------------

            $subProjects[] = $langSubProject;
        }

        return $subProjects;
    }

    /**
     * Delete #__content_frontpage items if the deleted articles was featured
     *
     * @param   object  $pks  The primary key related to the contents that was deleted.
     *
     * @return  boolean
     *
     * @since   3.7.0
     */
    public function delete(&$pks)
    {
        $return = parent::delete($pks);

        if ($return) {
            // delete subproject by parent id
            $db    = $this->getDbo();
            $query = $db->getQuery(true)
                ->delete($db->quoteName('#__lang4dev_subprojects'))
                ->whereIn($db->quoteName('parent_id'), $pks);
            $db->setQuery($query);
            $db->execute();

            // Clear the cache
            $this->cleanCache();
        }

        return $return;
    }

} // class
