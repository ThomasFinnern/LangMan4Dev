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

use Finnern\Component\Lang4dev\Administrator\Helper\sessionProjectId;
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
class SubprojectSelectField extends ListField
{

	protected $prjId=-1;

    /**
     * The field type.
     *
     * @var string
     *
     * @since __BUMP_VERSION__
     */
	protected $type = 'SubprojectSelect';

	protected function getInput()
	{
		//--- Set selection of project and sub project --------------------

		$sessionProjectId = new sessionProjectId();
		[$prjId, $subPrjActive] = $sessionProjectId->getIds();

		$this->setValue ($subPrjActive);
		$this->prjId = $prjId;

		/**
		if ($this->form->getValue('id', 0) == 0)
		{
			return '<span class="readonly">' . Text::_('COM_MENUS_ITEM_FIELD_ORDERING_TEXT') . '</span>';
		}
		else
		{
		return parent::getInput();
		}
		/**/
		if($subPrjActive > 0)
		{
			return parent::getInput();
		}
		else {
			if($subPrjActive == 0) {
				return '<span class="readonly">' . Text::_('All sub projects') . '</span>';
			} else
			{
				return '<span class="readonly">' . Text::_('??? -1 ???') . '</span>';
			}
		}
	}


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
				->select($db->quoteName('id', 'value'))
				->select($db->quoteName('title', 'text'))

				->where($db->quoteName('parent_id') . ' = ' . (int) $this->prjId)

                ->from($db->quoteName('#__lang4dev_subprojects'))
				// ToDo: Use option in XML to select ASC/DESC
				->order($db->quoteName('id') . ' DESC')
				;

			// Get the options.
			$subprojects = $db->setQuery($query)->loadObjectList();

			//--- Set selection of project and sub project --------------------

			$sessionProjectId = new sessionProjectId();
			[$prjId, $subPrjActive] = $sessionProjectId->getIds();

			$this->value = $subPrjActive;

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
		array_unshift($options, HTMLHelper::_('select.option', '0', Text::_('COM_LANG4DEV_SELECT_SUB_PROJECT_ALL')));

        // Merge any additional options in the XML definition.
        $options = array_merge(parent::getOptions(), $options);

        return $options;
	}
}



