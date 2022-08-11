<?php
/**
 * @package     Lang4dev
 * @subpackage  com_lang4dev
 * @copyright (C) 2022-2022 Lang4dev Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 */

namespace Finnern\Component\Lang4dev\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Association\AssociationServiceInterface;
use Joomla\CMS\Categories\CategoryServiceInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Table\Table;

/**
 * Lang4dev Component Subp Model
 *
 * @since __BUMP_VERSION__
 */
class SubprojectsModel extends ListModel
{
	/**
	 * Constructor.
	 *
	 * @param   array                $config   An optional associative array of configuration settings.
	 * @param   MVCFactoryInterface  $factory  The factory.
	 *
	 * @see     \JControllerLegacy
	 * @since __BUMP_VERSION__
	 */
	public function __construct($config = array(), MVCFactoryInterface $factory = null)
	{
		//  which fields are needed for filter function
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
				'prjId', 'a.prjId',
				'subPrjType', 'a.subPrjType',
				'parent_id', 'a.parent_id',
				'lang_path_type', 'a.lang_path_type',

				'created', 'a.created',
				'created_by', 'a.created_by',

                'published', 'a.published',

//				'modified', 'a.modified',
//				'modified_by', 'a.modified_by',

				'parent_id', 'a.parent_id',
//				'lft', 'a.lft',

				'hits', 'a.hits',
//				'tag',
				'a.access',
				'image_count'
			);
		}

		if (Associations::isEnabled())
		{
			$config['filter_fields'][] = 'association';
		}

		parent::__construct($config, $factory);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since __BUMP_VERSION__
	 */
	protected function populateState($ordering = 'a.id', $direction = 'asc')
	{
		$app = Factory::getApplication();

		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout'))
		{
			$this->context .= '.' . $layout;
		}

		//$forcedLanguage = $app->input->get('forcedLanguage', '', 'cmd');
		//// Adjust the context to support forced languages.
		//if ($forcedLanguage)
		//{
		//	$this->context .= '.' . $forcedLanguage;
		//}

		$extension = $app->getUserStateFromRequest($this->context . '.filter.extension', 'extension', 'com_lang4dev', 'cmd');
		$this->setState('filter.extension', $extension);
		$parts = explode('.', $extension);

		// Extract the component name
		$this->setState('filter.component', $parts[0]);

		// Extract the optional section name
		$this->setState('filter.section', (count($parts) > 1) ? $parts[1] : null);

		$search   = $this->getUserStateFromRequest($this->context . '.search', 'filter_search');
		$this->setState('filter.search', $search);

		// List state information.
		parent::populateState($ordering, $direction);

		//// Force a language.
		//if (!empty($forcedLanguage))
		//{
		//	$this->setState('filter.language', $forcedLanguage);
		//}
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since __BUMP_VERSION__
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.extension');
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.access');
//		$id .= ':' . $this->getState('filter.language');
//		$id .= ':' . $this->getState('filter.level');
//		$id .= ':' . $this->getState('filter.tag');

		return parent::getStoreId($id);
	}

	/**
	 * Method to get a database query to list subprojects.
	 *
	 * @return  \DatabaseQuery object.
	 *
	 * @since __BUMP_VERSION__
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

        $app  = Factory::getApplication();
        $user = $app->getIdentity();

        // Select the required fields from the table.
		$query->select(
			$this->getState(
				/**/
				'list.select',
				'a.id, '
				. 'a.title, '
				. 'a.alias, '
				. 'a.prjId, '

				. 'a.subPrjType, '
				. 'a.root_path, '

				. 'a.prefix, '
				. 'a.notes, '
				. 'a.isLangAtStdJoomla, '

				. 'a.prjXmlPathFilename, '
				. 'a.installPathFilename, '

                . 'a.parent_id,'
                . 'a.twin_id,'

				. 'a.lang_path_type,'
				. 'a.lang_ids,'

				. 'a.params, '
				. 'a.ordering,'

				. 'a.checked_out, '
				. 'a.checked_out_time, '
				. 'a.created, '
				. 'a.created_by, '
				. 'a.created_by_alias, '
				. 'a.modified, '
				. 'a.modified_by, '

				. 'a.published,'

				. 'a.approved,'
				. 'a.asset_id,'
				. 'a.access,'

				. 'a.version'
			)
		);
		$query->from('#__lang4dev_subprojects AS a');

//		/* Count child images */
//		$query->select('COUNT(img.gallery_id) as image_count')
//			->join('LEFT', '#__rsg2_images AS img ON img.gallery_id = a.id'
//			);

//		// Join over the language
//		$query->select('l.title AS language_title, l.image AS language_image')
//			->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = a.language');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor')
			->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the asset groups.
		$query->select('ag.title AS access_level')
			->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

		// Join over the users for the author.
		$query->select('ua.name AS author_name')
			->join('LEFT', '#__users AS ua ON ua.id = a.created_by');

//		// Join over the associations.
//		$assoc = $this->getAssoc();
//
//		if ($assoc)
//		{
//			$query->select('COUNT(asso2.id)>1 as association')
//				->join('LEFT', '#__associations AS asso ON asso.id = a.id AND asso.context=' . $db->quote('com_lang4dev.item'))
//				->join('LEFT', '#__associations AS asso2 ON asso2.key = asso.key')
//				->group('a.id, l.title, uc.name, ag.title, ua.name');
//		}

//		// Filter on the level.
//		if ($level = $this->getState('filter.level'))
//		{
//			$query->where('a.level <= ' . (int) $level);
//		}

		// Filter by access level.
		if ($access = $this->getState('filter.access'))
		{
			$query->where('a.access = ' . (int) $access);
		}

		// Implement View Level Access
		if (!$user->authorise('core.admin'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
		}

//		// Filter by published state
//		$published = (string) $this->getState('filter.published');
//
//		if (is_numeric($published))
//		{
//			$query->where('a.published = ' . (int) $published);
//		}
//		elseif ($published === '')
//		{
//			$query->where('(a.published IN (0, 1))');
//		}

		// Filter by search in name and others
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			// yyyy continue
			$search = $db->quote('%' . $db->escape($search, true) . '%');
			$query->where(
				'a.name LIKE ' . $search
				. ' OR a.alias LIKE ' . $search
				. ' OR a.description LIKE ' . $search
				. ' OR a.notes LIKE ' . $search
				. ' OR a.created LIKE ' . $search
				. ' OR a.modified LIKE ' . $search
			);
		}

		// exclude root gallery record
//		$query->where('a.id > 1');

		/**
		// Filter on the language.
		if ($language = $this->getState('filter.language'))
		{
			$query->where('a.language = ' . $db->quote($language));
		}
		/**/

		// Filter by a single tag.
		/**
		$tagId = $this->getState('filter.tag');

		if (is_numeric($tagId))
		{
			$query->where($db->quoteName('tagmap.tag_id') . ' = ' . (int) $tagId)
				->join(
					'LEFT', $db->quoteName('#__contentitem_tag_map', 'tagmap')
					. ' ON ' . $db->quoteName('tagmap.content_item_id') . ' = ' . $db->quoteName('a.id')
					. ' AND ' . $db->quoteName('tagmap.type_alias') . ' = ' . $db->quote($extension . '.category')
				);
		}
		/**/

		// Add the list ordering clause
        $listOrdering = $this->getState('list.ordering', 'a.ordering');
        $listDirn = $db->escape($this->getState('list.direction', 'DESC'));
		// $listDirn = $db->escape($this->getState('list.direction', 'ASC'));

        if ($listOrdering == 'a.access')
        {
            $query->order('a.access ' . $listDirn . ', a.id ' . $listDirn);
        }
        else
        {
            $query->order($db->escape($listOrdering) . ' ' . $listDirn);
        }

		// Group by on Galleries for \JOIN with component tables to count items
		$query->group(
		/**/
			'a.id, '
			. 'a.title, '
			. 'a.prjId, '
			. 'a.alias, '
            . 'a.notes, '
            . 'a.isLangAtStdJoomla, '
            . 'a.root_path, '
            . 'a.twin_id,'

            . 'a.params, '

            . 'a.checked_out, '
			. 'a.checked_out_time, '
			. 'a.created, '
			. 'a.created_by, '
			. 'a.created_by_alias, '
			. 'a.modified, '
			. 'a.modified_by, '

            . 'a.ordering,'

            . 'a.approved,'
            . 'a.asset_id,'
            . 'a.access, '

			. 'uc.name, '
		    . 'ua.name '

		);

		return $query;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @param   Table  $table  A Table object.
	 *
	 * @return  void
	 *
	 * @since __BUMP_VERSION__
	 */
	protected function prepareTable($table)
	{
		$date = Factory::getDate();
		$user = Factory::getApplication()->getIdentity();

		if (empty($table->id))
		{
			// Set the values
			$table->created    = $date->toSql();
			$table->created_by = $user->id;

			// Set ordering to the last item if not set
			if (empty($table->ordering))
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true)
					->select('MAX(ordering)')
					->from('#__banners');

				$db->setQuery($query);
				$max = $db->loadResult();

				$table->ordering = $max + 1;
			}
		}
		else
		{
			// Set the values
			$table->modified    = $date->toSql();
			$table->modified_by = $user->id;
		}

		// Increment the content version number.
		//$table->version++;
	}

	/**
	 * Method to determine if an association exists
	 *
	 * @return  boolean  True if the association exists
	 *
	 * @since __BUMP_VERSION__
	 */
	public function getAssoc()
	{
		static $assoc = null;

		if (!is_null($assoc))
		{
			return $assoc;
		}

		$extension = $this->getState('filter.extension');

		$assoc = Associations::isEnabled();
		$extension = explode('.', $extension);
		$component = array_shift($extension);
		$cname = str_replace('com_', '', $component);

		if (!$assoc || !$component || !$cname)
		{
			$assoc = false;

			return $assoc;
		}

		$componentObject = $this->bootComponent($component);

		if ($componentObject instanceof AssociationServiceInterface && $componentObject instanceof CategoryServiceInterface)
		{
			$assoc = true;

			return $assoc;
		}

		$hname = $cname . 'HelperAssociation';
		\JLoader::register($hname, JPATH_SITE . '/components/' . $component . '/helpers/association.php');

		$assoc = class_exists($hname) && !empty($hname::$category_association);

		return $assoc;
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since __BUMP_VERSION__
	 */
	public function getItems()
	{
		$items = parent::getItems();

		if ($items != false)
		{
			/**
			$extension = $this->getState('filter.extension');

			$this->countItems($items, $extension);
			/**/
		}

		return $items;
	}

	/**
	 * Method to load the countItems method from the extensions
	 *
	 * @param   \stdClass[]  &$items     The category items
	 * @param   string       $extension  The category extension
	 *
	 * @return  void
	 *
	 * @since __BUMP_VERSION__
	 */
	/**
	public function countItems(&$items, $extension)
	{
		$parts     = explode('.', $extension, 2);
		$section   = '';

		if (count($parts) > 1)
		{
			$section = $parts[1];
		}

		$component = Factory::getApplication()->bootComponent($parts[0]);

		if ($component instanceof CategoryServiceInterface)
		{
			$component->countItems($items, $section);
		}
	}
	/**/

} // class
