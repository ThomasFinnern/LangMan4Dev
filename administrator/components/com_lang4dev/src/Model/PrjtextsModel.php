<?php
/**
 * @package     lang4dev administrator
 * @subpackage  com_foos
 *
 * @copyright (C) 2022-2022 Lang4dev Team
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Finnern\Component\Lang4dev\Administrator\Model;

\defined('_JEXEC') or die;

use Finnern\Component\Lang4dev\Administrator\Helper\langProject;
use Finnern\Component\Lang4dev\Administrator\Helper\projectType;
use Finnern\Component\Lang4dev\Administrator\Helper\sessionProjectId;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\MVC\Model\BaseModel;
//use Joomla\CMS\MVC\Model\ListModel;


/**
 * Item Model for a Configuration items (options).
 *
 * @since __BUMP_VERSION__
 */
class PrjTextsModel extends AdminModel
{


	/**
	 * Method to get the row form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  \JForm|boolean  A JForm object on success, false on failure
	 *
	 * @since __BUMP_VERSION__
	 */
	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_lang4dev.prjtexts', 'prjtexts', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	public function getProject($prjId, $subPrjActive)
	{
		$project = new langProject ();

		//--- collect parent project ----------------------------------

		// ?? id is enough for now ($prjId)

		//--- all sub ids ----------------------------------------------

		$subIds = $this->collectSubProjectIdsData ($prjId);

		//--- add sub projects ------------------------------------

		foreach ($subIds as $subId) {

			//---  ----------------------------------------
			;

			/**
			$subPrj = $project->addSubProject('com_lang4dev',
				projectType::PRJ_TYPE_COMP_BACK_SYS,
				JPATH_ADMINISTRATOR . '/components/com_lang4dev'
			);
			/**/

			$subPrj = $project->addSubProject($subId->prjId,
				$subId->subPrjType,
				$subId->root_path,
				//$subId->prjXmlPathFilename,
				$subId->prjXmlPathFilename,
			);


		}

		return $project;
	}

	private function collectSubProjectIdsData($prjId)
	{
		$subIds = [];

		try {

			//--- collect data from manifest -----------------
			$db = Factory::getDbo();

			$query = $db->getQuery(true)
				->select($db->quoteName('id'))
				->select($db->quoteName('prjId'))
				->select($db->quoteName('subPrjType'))
				->select($db->quoteName('root_path'))
				->select($db->quoteName('prjXmlPathFilename'))
				->select($db->quoteName('installPathFilename'))

				->where($db->quoteName('parent_id') . ' = ' . (int) $prjId)
				->from($db->quoteName('#__lang4dev_subprojects'))
				->order($db->quoteName('subPrjType') . ' ASC')
			;

			// Get the options.
			$subIds = $db->setQuery($query)->loadObjectList();

		} catch (\RuntimeException $e) {
			$OutTxt = '';
			$OutTxt .= 'Error executing collectSubProjectIds: ' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $subIds;
	}

}

