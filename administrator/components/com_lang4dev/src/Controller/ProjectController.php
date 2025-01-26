<?php
/**
 * @package         LangMan4Dev
 * @subpackage      com_lang4dev
 * @author          Thomas Finnern <InsideTheMachine.de>
 * @copyright  (c)  2022-2025 Lang4dev Team
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Finnern\Component\Lang4dev\Administrator\Controller;

defined('_JEXEC') or die;

use Exception;
use Finnern\Component\Lang4dev\Administrator\Model\ProjectModel;
use Finnern\Component\Lang4dev\Administrator\Model\SubprojectModel;
use Finnern\Component\Lang4dev\Administrator\Helper\langProject;
use Finnern\Component\Lang4dev\Administrator\Helper\projectType;
use Finnern\Component\Lang4dev\Administrator\Helper\basePrjPathFinder;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Router\Router;
use Joomla\CMS\Session\Session;
use Joomla\Component\Menus\Administrator\Model\MenuModel;
use Joomla\Input\Input;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;


use function defined;

/**
 * The Gallery Controller
 *
 * @since __BUMP_VERSION__
 */
class projectController extends FormController
{
    /**
     * The extension for which the galleries apply.
     *
     * @var    string
     * @since __BUMP_VERSION__
     */
    protected $extension;

    /**
     * Constructor.
     *
     * @param   array                $config   An optional associative array of configuration settings.
     * @param   MVCFactoryInterface  $factory  The factory.
     * @param   CMSApplication       $app      The JApplication for the dispatcher
     * @param   Input               $input    Input
     *
     * @since  __BUMP_VERSION__
     * @see    \JControllerLegacy
     */
    public function __construct($config = array(), MVCFactoryInterface $factory = null, $app = null, $input = null)
    {
        parent::__construct($config, $factory, $app, $input);

        if (empty($this->extension)) {
            $this->extension = $this->input->get('extension', 'com_lang4dev');
        }
    }

    /**
     * Proxy for getModel
     *
     * @param   string  $name    The model name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  The array of possible config values. Optional.
     *
     * @return  BaseDatabaseModel  The model.
     *
     * @since __BUMP_VERSION__
     */
    public function getModel($name = 'Project', $prefix = 'Administrator', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }

    /**
     * Standard cancel, back to list view
     *
     * @param   null  $key
     *
     * @return bool
     *
     * @since __BUMP_VERSION__
     */
    public function cancel($key = null)
    {
        Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

        $link = 'index.php?option=com_lang4dev&view=projects';
        $this->setRedirect($link);

        return true;
    }


    /**
     * Override parent save method to store form data with right key as expected by edit category page
     *
     * @param   string  $key     The name of the primary key of the URL variable.
     * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
     *
     * @return  boolean  True if successful, false otherwise.
     *
     * @since   3.10.3
     */
    public function save($key = null, $urlVar = null)
    {
        //--- save this ----------------------------------

        $result = parent::save($key, $urlVar);

        //--- detect sub projects -----------------------

        /** @var ProjectModel $model */
        $prjModel = $this->getModel();

        /** @var SubprojectModel $subPrjModel */
        $subPrjModel = $this->getModel('Subproject');

        // detect subprojects and write to DB
        $subProjects = $prjModel->detectSubProjects($subPrjModel);

        //--- no subProjects found ? -----------------------

        if (count($subProjects) == 0) {
                $OutTxt = "Error on detect subprojects for project. \n"
                    . 'Could not create subprojects "' . 'count(\$subProjects)==0';
                $app    = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'warning');
        }

        return $result;
    }

    /**
     *
     *
     * @throws Exception
     * @since version
     */
    public function detectSubProjects()
    {
        Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

        // User is allowed to change
        // ToDo: $canCreateFile = ...;
        $canCreateFile = true;
        $id            = -1;
        $prjId         = '';
        $prjRootPath   = '';

        if (!$canCreateFile) {
            $OutTxt = Text::_('COM_LANG4DEV_TRANSLATE_CREATE_LANG_INVALID_RIGHTS');
            $app    = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        } else {
            //--- detect sub projects ----------------------------------

            /** @var ProjectModel $model */
            $prjModel = $this->getModel();

            /** @var SubprojectModel $subPrjModel */
            $subPrjModel = $this->getModel('Subproject');

            // detect subprojects and write to DB
            $subProjects = $prjModel->detectSubProjects($subPrjModel);

            //--- no subProjects found ? -----------------------

            if (count($subProjects) == 0) {
                $OutTxt = "Error on detect subprojects for project. \n"
                    . 'Could not create subprojects "' . 'count(\$subProjects)==0';
                $app    = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'warning');
            }

            $link = 'index.php?option=com_lang4dev&view=project&layout=edit&id=' . $id;
            $this->setRedirect($link);
        }
    }

}
