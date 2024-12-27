<?php
/**
 * @package       Joomla.Administrator
 * @subpackage    com_lang4dev
 *
 * @copyright  (c)  2022-2024 Lang4dev Team
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Finnern\Component\Lang4dev\Administrator\Controller;

defined('_JEXEC') or die;

use Exception;
use Finnern\Component\Lang4dev\Administrator\Helper\sessionProjectId;
use Finnern\Component\Lang4dev\Administrator\Helper\sessionTransLangIds;
use Finnern\Component\Lang4dev\Administrator\Model\PrjTextsModel;
use JInput;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Session\Session;
use RuntimeException;

use function defined;

/**
 * The Galleries List Controller
 *
 * @since __BUMP_VERSION__
 */
class PrjTextsController extends AdminController
{
    /**
     * Constructor.
     *
     * @param   array                $config   An optional associative array of configuration settings.
     *                                         Recognized key values include 'name', 'default_task', 'model_path', and
     *                                         'view_path' (this list is not meant to be comprehensive).
     * @param   MVCFactoryInterface  $factory  The factory.
     * @param   CMSApplication       $app      The JApplication for the dispatcher
     * @param   JInput               $input    Input
     *
     * @since __BUMP_VERSION__
     */
    public function __construct($config = array(), MVCFactoryInterface $factory = null, $app = null, $input = null)
    {
        parent::__construct($config, $factory, $app, $input);
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
    public function getModel($name = 'PrjTexts', $prefix = 'Administrator', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }

    /**
     *
     *
     * @return
     *
     * @since __BUMP_VERSION__
     */

    // ToDo: is needed ?
    /**
     *
     * @return false|void
     *
     * @throws Exception
     * @since version
     */
    public function Search4PrjTexts()
    {
        $isOk = false;

        $msg     = "PrjTextController.Search4PrjTexts: ";
        $msgType = 'notice';

        Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

        $canAdmin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_lang4dev');
        if (!$canAdmin) {
            $msg     .= Text::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                // Message when used  ....

                $OutTxt = '';
                $OutTxt .= 'Error executing rebuild: "' . '<br>';
                //$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');

                /** @var PrjTextsModel $model */
                $model = $this->getModel();

                $isOk = $model->rebuild();
                if ($isOk) {
                    $msg .= Text::_('COM_LANG4DEV_GALLERIES_REBUILD_SUCCESS');
                } else {
                    $msg .= Text::_('COM_LANG4DEV_GALLERIES_REBUILD_FAILURE') . ': ' . $model->getError();
                }
            } catch (RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing rebuild: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = Factory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $link = 'index.php?option=com_lang4dev&view=galleries&layout=galleries_tree';
        $this->setRedirect($link, $msg, $msgType);

        return $isOk;
    }

    /**
     *
     * @return bool|void
     *
     * @throws Exception
     * @since version
     */
    public function selectProject()
    {
        Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

        $canCreateFile = true;

        if (!$canCreateFile) {
            $OutTxt = Text::_('COM_LANG4DEV_TRANSLATE_SELECT_PROJECT_INVALID_RIGHTS');
            $app    = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        } else {
            $input = Factory::getApplication()->input;
            $data  = $input->post->get('jform', array(), 'array');

            $prjId        = (int)$data ['selectProject'];
            $subPrjActive = (int)$data ['selectSubproject'];

            // $prjId, $subPrjActive

            $sessionProjectId = new sessionProjectId();
            $sessionProjectId->setIds($prjId, $subPrjActive);
        }

        $OutTxt = "Project for prjTexts has changed:";
        $app    = Factory::getApplication();
        $app->enqueueMessage($OutTxt, 'info');

        $link = 'index.php?option=com_lang4dev&view=prjTexts';
        $this->setRedirect($link);

        return true;
    }

    /**
     *
     * @return bool|void
     *
     * @throws Exception
     * @since version
     */
    public function selectLangIds()
    {
        Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

        $canCreateFile = true;

        if (!$canCreateFile) {
            $OutTxt = Text::_('COM_LANG4DEV_TRANSLATE_SELECT_PROJECT_INVALID_RIGHTS');
            $app    = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        } else {
            $input = Factory::getApplication()->input;
            $data  = $input->post->get('jform', array(), 'array');

            $mainLangId  = $data ['selectSourceLangId'];
            $transLangId = $data ['selectTargetLangId'];

            // $prjId, $subPrjActive

            $sessionTransLangIds = new sessionTransLangIds ();
            $sessionTransLangIds->setIds($mainLangId, $transLangId);
        }

        $OutTxt = "Lang Id for PrjText has changed:";
        $app    = Factory::getApplication();
        $app->enqueueMessage($OutTxt, 'info');

        $link = 'index.php?option=com_lang4dev&view=prjtexts';
        $this->setRedirect($link);

        return true;
    }

}
