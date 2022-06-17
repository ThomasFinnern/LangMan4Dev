<?php
/**
 * @package     Lang4dev
 * @subpackage
 *
 * @version
 * @copyright (C) 2022-2022 Lang4dev Team
 * @license   GPL2
 */

namespace Finnern\Component\Lang4dev\Administrator\Helper;

use Joomla\CMS\Factory;

// no direct access
\defined('_JEXEC') or die;

/**
 * Reads and writes user selected project id and sub project id from / to session
 *
 * The initial ID is the highest project id in db and zero for the sub id
 * Zero on sub id tells that all sub projects are displayed to the user
 *
 * @package Lang4dev
 */

class sessionProjectId
{
	protected $prjId = '-1';
	protected $subPrjId = '0';



	public function clear() {

		$prjId = '-1';
		$subPrjId = '0';

		return;
	}

	/**
	 * setIds
	 *
	 * @return
	 * @since __BUMP_VERSION__
	 */
	public function setIds($prjId = '-1', $subPrjId = '0')
	{

		$this->prjId = $prjId;
		$this->subPrjId = $subPrjId;

		$session = Factory::getSession();
		$data = $session->set('_lang4dev.prjId', $prjId);
		$data = $session->set('_lang4dev.subPrjId', $subPrjId);

		return;
	}

	public function resetIds()
	{
		// default values
		//$this->setIds();

		$this->clear ();

		$session = Factory::getSession();
		$session->clear('_lang4dev.prjId');
		$session->clear('_lang4dev.subPrjId');

		return;
	}

	/**
	 * getIds
	 *
	 * @return integer [] project id, sub project id
	 * @since __BUMP_VERSION__
	 */
	public function getIds()
	{
		//--- already set in class ? ---------------------

		$prjId = $this->prjId;
		$subPrjId = $this->subPrjId;

		// Is not set
		if ($prjId < 0)
		{
			//--- try session if set ---------------------------------

			$session = Factory::getSession();
			$prjId   = (int) $session->get('_lang4dev.prjId', '-1');
			if ($prjId > 0)
			{
				$subPrjId = (int) $session->get('_lang4dev.subPrjId', '0');
			}

			// Is not set
			if ($prjId < 0)
			{
				//--- retrieve last created from DB ---------------------------------

				$prjId = $this->maxPrjId_DB ();
				$subPrjId = 0; // view all
			}
		}

		return [$prjId, $subPrjId];
	}

	/**
	 *
	 * @return integer highest ID of created projects
	 *
	 * @since version
	 */
	private function maxPrjId_DB()
	{
		$max = 0; // indicates nothing found in DB

		$db = $this->getDbo();
		$query = $db->getQuery(true)
			->select('MAX(id)')
			->from($db->quoteName('#__lang4dev_projects'));
		$db->setQuery($query);
		$max = $db->loadResult();

		return (int) $max;
	}

}