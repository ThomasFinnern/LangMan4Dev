<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_lang4dev
 *
 * @copyright (C) 2022-2022 Lang4dev Team
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Finnern\Component\Lang4dev\Administrator\Model;

\defined('_JEXEC') or die;

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

// associations: use Finnern\Component\Lang4def\Administrator\Helper\Lang4devHelper;

/**
 * Lang4dev Component Project Model
 *
 * @since __BUMP_VERSION__
 */
class TranslateModel extends AdminModel
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
	public $typeAlias = 'com_lang4dev.translation';


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
		$form = $this->loadForm('com_lang4dev.translate', 'translate', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

}
