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

use Finnern\Component\Lang4dev\Administrator\Helper\basePrjPathFinder;
use Finnern\Component\Lang4dev\Administrator\Helper\langSubProject;
use Finnern\Component\Lang4dev\Administrator\Helper\manifestLangFiles;
use Finnern\Component\Lang4dev\Administrator\Helper\projectType;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Router\Router;
use Joomla\CMS\Session\Session;
use Joomla\Component\Associations\Administrator\Helper\AssociationsHelper;
use Joomla\Component\Menus\Administrator\Model\MenuModel;
use Joomla\Input\Input;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

// ???? use Symfony\Component\Yaml\Yaml;


use function defined;

/**
 * The Gallery Controller
 *
 * @since __BUMP_VERSION__
 */
class subprojectController extends FormController
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
     * @param   Input                $input    Input
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
        // $link = Route::_('index.php?option=com_lang4dev&view=subprojects');
        $link = 'index.php?option=com_lang4dev&view=subprojects';
        $this->setRedirect($link);

        return true;
    }

    /**
     *
     * @return bool|void
     *
     * @since version
     */
    public function detectSubPrjDetails()
    {
        $result = false;

        Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

        // ToDo: form validate https://docs.joomla.org/Joomla_4_Tips_and_Tricks:_Form_Validation_Basics

        // try ...

        // check: does it need $input = ....
        $data = $this->input->post->get('jform', array(), 'array');

        // subproject
        /**/
        $prjId   = $data ['prjId'];
        $prjType = projectType::int2prjType((int)$data ['subPrjType']);
        $parent_id = $data ['parent_id'];
        $prjRootPath       = trim($data ['root_path']);
        $data ['root_path'] = $prjRootPath;
        /**/

        $prjXmlPathFilename = trim($data ['prjXmlPathFilename']);
        $oManifestFile = new manifestLangFiles ($prjXmlPathFilename);

        $oBasePrjPath = new basePrjPathFinder ($prjId, dirname($prjXmlPathFilename));

        //--- new sub project -------------------------------------------------

        $langSubProject = new langSubProject (
            $prjId,
            $prjType,
            $oBasePrjPath,
            $oManifestFile
        );

        //--- write to post data for save --------------------------------------

        $subPrjModel = $this->getModel();

        $isSubPrjSaved = $subPrjModel->saveSubProject($langSubProject, $parent_id);

        // Add new data to input before process by parent save()
        $this->input->post->set('jform', $data);

        $result = parent::save($key = null, $urlVar = 'id') && $isSubPrjSaved;

        //--- return to edit --------------------------------

        $id   = (int)$data ['id'];
        $link = 'index.php?option=com_lang4dev&view=subproject&layout=edit&id=' . $id;
        $this->setRedirect($link);

        return $result;
    }


}
