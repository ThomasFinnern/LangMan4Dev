<?php
/**
 * @package       Joomla.Administrator
 * @subpackage    com_lang4dev
 *
 * @copyright (C) 2022-2022 Lang4dev Team
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Finnern\Component\Lang4dev\Administrator\Controller;

defined('_JEXEC') or die;

use Exception;
use Finnern\Component\Lang4dev\Administrator\Model\SubprojectModel;
use JInput;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Router\Router;
use Joomla\CMS\Session\Session;
use Joomla\Component\Associations\Administrator\Helper\AssociationsHelper;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

// ???? use Symfony\Component\Yaml\Yaml;

use Finnern\Component\Lang4dev\Administrator\Helper\langSubProject;
use Finnern\Component\Lang4dev\Administrator\Helper\projectType;

use function defined;

/**
 * The Gallery Controller
 *
 * @since __BUMP_VERSION__
 */
class subprojectsController extends FormController
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
     * @param   JInput               $input    Input
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
    public function getModel(
        $name = 'Subprojects',
        $prefix = 'Administrator',
        $config = array('ignore_request' => true)
    ) {
        return parent::getModel($name, $prefix, $config);
    }

    /**
     *
     *
     * @throws Exception
     * @since version
     */
    public function delete()
    {
        // Check for request forgeries
        $this->checkToken();

        $user = $this->app->getIdentity();
        $ids  = $this->input->get('cid', array(), 'array');

        /**
         * // Access checks.
         * foreach ($ids as $i => $id)
         * {
         * if (!$user->authorise('core.delete', 'com_lang4dev.????.' . (int) $id))
         * {
         * // Prune items that you can't delete.
         * unset($ids[$i]);
         * $this->app->enqueueMessage(Text::_('JERROR_CORE_DELETE_NOT_PERMITTED'), 'notice');
         * }
         * }
         * /**/
        $canDelete = true;

        if (!$canDelete) {
            $OutTxt = Text::_('COM_LANG4DEV_DELETE_INVALID_RIGHTS');
            $app    = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        } else {
            if (empty($ids)) {
                $this->app->enqueueMessage(Text::_('JERROR_NO_ITEMS_SELECTED'), 'error');
            } else {
                /** @var SubprojectModel $model */
                $model = $this->getModel('subproject');

                // Make sure the item ids are integers
                $cids = ArrayHelper::toInteger($ids);

                // Remove the items.
                if (!$model->delete($cids)) {
                    $this->setMessage($model->getError(), 'error');
                } else {
                    $this->setMessage(Text::plural('COM_LANG4DEV_N_SUB_PROJECTS_DELETED', count($cids)));
                }
            }
        }

        $link = 'index.php?option=com_lang4dev&view=subprojects';
        $this->setRedirect($link);
    }

} // class
