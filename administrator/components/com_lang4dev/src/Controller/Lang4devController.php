<?php
/**
 * @package     Lang4dev
 * @subpackage  com_lang4dev
 * @copyright (C) 2022-2022 Lang4dev Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 */

namespace Finnern\Component\Lang4dev\Administrator\Controller;

\defined('_JEXEC') or die;

use Finnern\Component\Lang4dev\Administrator\Helper\sessionProjectId;
use Finnern\Component\Lang4dev\Administrator\Helper\sessionTransLangIds;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

class Lang4devController extends AdminController // FormController
{
    /**
     * Constructor.
     *
     * @param   array                $config   An optional associative array of configuration settings.
     * Recognized key values include 'name', 'default_task', 'model_path', and
     * 'view_path' (this list is not meant to be comprehensive).
     * @param   MVCFactoryInterface  $factory  The factory.
     * @param   CMSApplication       $app      The JApplication for the dispatcher
     * @param   \JInput              $input    Input
     *
     * @since __BUMP_VERSION__
     */
    public function __construct($config = array(), MVCFactoryInterface $factory = null, $app = null, $input = null)
    {
        parent::__construct($config, $factory, $app, $input);

    }


    /**
	 * Standard cancel (may not be used)
	 *
	 * @param null $key
	 *
	 * @return bool
	 *
	 * @since __BUMP_VERSION__
	 */
	public function cancel($key = null)
	{
		Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

		$link = 'index.php?option=com_lang4dev';
		$this->setRedirect($link);

		return true;
	}

	public function selectProject ()
	{
		Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

		$canCreateFile = true;

		if ( ! $canCreateFile ) {

			$OutTxt = Text::_('COM_LANG4DEV_TRANSLATE_SELECT_PROJECT_INVALID_RIGHTS');
			$app    = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		} else {

			$input = Factory::getApplication()->input;
			$data  = $input->post->get('jform', array(), 'array');

			$prjId       = (int) $data ['selectProject'];
			$subPrjActive    = (int) $data ['selectSubproject'];

			// $prjId, $subPrjActive

			$sessionProjectId = new sessionProjectId();
			$sessionProjectId->setIds($prjId, $subPrjActive);
		}

		$OutTxt = "Project for prjTexts has changed:";
		$app = Factory::getApplication();
		$app->enqueueMessage($OutTxt, 'info');

		$link = 'index.php?option=com_lang4dev';
		$this->setRedirect($link);

		return true;
	}

	public function selectLangIds ()
	{
		Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

		$canCreateFile = true;

		if ( ! $canCreateFile ) {

			$OutTxt = Text::_('COM_LANG4DEV_TRANSLATE_SELECT_PROJECT_INVALID_RIGHTS');
			$app    = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		} else {

			$input = Factory::getApplication()->input;
			$data  = $input->post->get('jform', array(), 'array');

			$mainLangId  = $data ['selectSourceLangId'];
			$transLangId = $data ['selectTargetLangId'];

			// $mainLangId, $transLangId

			$sessionTransLangIds = new sessionTransLangIds ();
			$sessionTransLangIds->setIds($mainLangId, $transLangId);
		}

		$OutTxt = "Lang Id for prjText has changed:";
		$app = Factory::getApplication();
		$app->enqueueMessage($OutTxt, 'info');

		$link = 'index.php?option=com_lang4dev';
		$this->setRedirect($link);

		return true;
	}

}

