<?php
/*
 * @package     Lang4dev
 * @subpackage  com_lang4dev
 * @copyright   (C) 2022-2022 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      lang4dev team
 * RSGallery is Free Software
 */

// used in upload


namespace Finnern\Component\Lang4dev\Administrator\Field;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

/**
 * Collects available subproject ids with titles and creates
 * contents of a dropdown box for the selection
 * Includes "Select subproject" as first entry
 * Sorted by ordering (the newest first)
 *
 * @since __BUMP_VERSION__
 */
class ProjectSelectField extends ListField
{
	/**
	 * Cached array of the category items.
	 *
	 * @var    array
	 * @since __BUMP_VERSION__
	 */
//	protected static $options = [];

    /**
     * The field type.
     *
     * @var string
     *
     * @since __BUMP_VERSION__
     */
	protected $type = 'ProjectSelect';

	/**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since __BUMP_VERSION__
	 *
	protected function getInput()
	{
		return $this->getOptions() ? parent::getInput() : '';
	}
	/**/

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return  string array  The field option objects.
     *
     * @since __BUMP_VERSION__
	 */
	protected function getOptions()
	{
		$subprojects = array();

		try
		{
			// $user = Factory::getApplication()->getIdentity(); // Todo: Restrict to accessible subprojects
			$db    = Factory::getDbo();

			$query = $db->getQuery(true)
                ->select('id AS value, title AS text')
                ->from($db->quoteName('#__lang4dev_projects'))
//				->where($db->quoteName('id') . ' != 1' )
//				->where($db->quoteName('published') . ' = 1')
				// ToDo: Use option in XML to select ASC/DESC
				->order('DSC');

			// Get the options.
			$subprojects = $db->setQuery($query)->loadObjectList();
		}
		catch (\RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		$options = $subprojects;

//        // Pad the option text with spaces using depth level as a multiplier.
//        for ($i = 0, $n = count($options); $i < $n; $i++) {
//            $options[$i]->text = str_repeat('- ', !$options[$i]->level ? 0 : $options[$i]->level - 1) . $options[$i]->text;
//        }

        // Put "Select an option" on the top of the list.
		array_unshift($options, HTMLHelper::_('select.option', '0', Text::_('COM_LANG4DEV_SELECT_PROJECT')));

        // Merge any additional options in the XML definition.
        $options = array_merge(parent::getOptions(), $options);

        return $options;
	}
}



