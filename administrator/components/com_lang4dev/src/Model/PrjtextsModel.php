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

		//--- get parent project ----------------------------------

		$this->AddDbData($project, $prjId);

		//--- all sub ids ----------------------------------------------

		$subIds = $this->collectSubProjectIdsData ($prjId);

		//--- add subprojects ------------------------------------

		foreach ($subIds as $subId) {

			//--- regard user selection ----------------------------------------

			// special selection
			if ($subPrjActive != 0) {

				//  not the one the user selected
				if ($subPrjActive != $subId->id) {

					continue;
				}
			}

			//--- load data of valid sub project ----------------------------------------

			$subPrj = $project->addSubProject($subId->prjId,
				$subId->subPrjType,
				$subId->root_path,
				$subId->prjXmlPathFilename
			);

			$subPrj->installPathFilename = $subId->installPathFilename;
			$subPrj->prlangIdPrefixefix = $subId->prefix;

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
				->select($db->quoteName('prefix'))
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

	private function AddDbData(langProject $project, $prjId)
	{
		$project->dbId = $prjId;

		try {

			//--- collect data from manifest -----------------
			$db = Factory::getDbo();

			$query = $db->getQuery(true)
				->select($db->quoteName('name'))
				->select($db->quoteName('title'))
				->select($db->quoteName('root_path'))

				->where($db->quoteName('id') . ' = ' . (int) $prjId)
				->from($db->quoteName('#__lang4dev_projects'))
//				->order($db->quoteName('subPrjType') . ' ASC')
			;

			// Get the options.
			$prjDb = $db->setQuery($query)->loadObject();

			// $project->prjName = $prjDb->name;
			$project->prjName = $prjDb->title;
			$project->prjRootPath = $prjDb->root_path;

			/** doesn't have these ... *
			$project->prjXmlPathFilename = $prjDb->prjXmlPathFilename;
			$project->installPathFilename = $prjDb->installPathFilename;
			$project->prefix = $prjDb->prefix;
			$project->subPrjType = $prjDb->subPrjType;
			/**/

			// = prjType ???
			// Not in DB actually: $project->langIdPrefix = $prjDb->;
			// $project->isSysFileFound = $prjDb->;

		} catch (\RuntimeException $e) {
			$OutTxt = '';
			$OutTxt .= 'Error executing collectSubProjectIds: ' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return;
	}

}

