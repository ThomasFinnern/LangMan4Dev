<?php
/**
 * @package         LangMan4Dev
 * @subpackage      com_lang4dev
 * @author          Thomas Finnern <InsideTheMachine.de>
 * @copyright  (c)  2022-2025 Lang4dev Team
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Finnern\Component\Lang4dev\Administrator\Model;

defined('_JEXEC') or die;

use Exception;
use Finnern\Component\Lang4dev\Administrator\Helper\langProject;
use Finnern\Component\Lang4dev\Administrator\Helper\projectType;
use JForm;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\AdminModel;
use RuntimeException;

use function defined;

// associations: use Finnern\Component\Lang4dev\Administrator\Helper\Lang4devHelper;

/**
 * Lang4dev Component Manifests raw Model
 *
 * @since __BUMP_VERSION__
 */
class ManifestsrawModel extends AdminModel
{
    /**
     * The prefix to use with controller messages.
     *
     * @var    string
     * @since __BUMP_VERSION__
     */
    protected $langIdPrefix = 'COM_LANG4DEV';

    /**
     * The type alias for this content type. Used for content version history.
     *
     * @var      string
     * @since __BUMP_VERSION__
     */
    public $typeAlias = 'com_lang4dev.manifestsraw';

    /**
     * Method to get the row form.
     *
     * @param   array    $data      Data for the form.
     * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
     *
     * @return  JForm|boolean  A JForm object on success, false on failure
     *
     * @since __BUMP_VERSION__
     */
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            'com_lang4dev.manifestsraw',
            'manifestsraw',
            array('control' => 'jform', 'load_data' => $loadData)
        );

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * @param $prjDbId
     * @param $subPrjActive
     *
     * @return langProject
     *
     * @since version
     */
    public function getProject($prjDbId, $subPrjActive) : langProject
    {
        $project = new langProject ();

        //--- get parent project ----------------------------------

        // retrieve project variables "'name', 'title', 'root_path'"
        $this->addPrjDbData($project, $prjDbId);

        //--- add all sub ids ----------------------------------------------

        // retrieve subproject variables of project
        $dbSubProjects = $this->subPrjsDbData($prjDbId);

        //--- add subprojects db data ------------------------------------

        foreach ($dbSubProjects as $dbSub) {

            //--- regard user selection ----------------------------------------

            // restrict not selected
            if ($subPrjActive != 0) {
                //  not the one the user selected
                if ($subPrjActive != $dbSub->id) {
                    continue;
                }
            }

            //--- create subproject with DB data ------------------------

            $subPrj = $project->addSubProject(
                $dbSub->prjId,
                projectType::int2prjType( $dbSub->subPrjType),
                $dbSub->root_path,
                $dbSub->prjXmlPathFilename
            );

            $subPrj->installPathFilename = $dbSub->installPathFilename;
            $subPrj->langIdPrefix        = $dbSub->langIdPrefix;
	        // Not needed ? 2022.12.25 $subPrj->isLangAtStdJoomla   = $dbSub->isLangAtStdJoomla;
        }

        return $project;
    }

    /**
     * DB: retrieve subproject variables of project
     *
     * @param $parent_id
     *
     * @return array|mixed
     *
     * @throws Exception
     * @since version
     */
    private function subPrjsDbData($parent_id)
    {
        $dbSubmanifests = [];

        try {
            //--- collect data from manifest -----------------
            $db = Factory::getDbo();

            $query = $db->getQuery(true)
                ->select($db->quoteName('id'))
                ->select($db->quoteName('prjId'))
                ->select($db->quoteName('subPrjType'))
                ->select($db->quoteName('langIdPrefix'))
                ->select($db->quoteName('isLangAtStdJoomla'))
                ->select($db->quoteName('root_path'))
                ->select($db->quoteName('prjXmlPathFilename'))
                ->select($db->quoteName('installPathFilename'))
                ->where($db->quoteName('parent_id') . ' = ' . (int)$parent_id)
                ->from($db->quoteName('#__lang4dev_submanifests'))
                ->order($db->quoteName('subPrjType') . ' ASC');

            // Get the options.
            $dbSubmanifests = $db->setQuery($query)->loadObjectList();
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing collectSubProjectIds: ' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $dbSubmanifests;
    }

    /**
     * DB: retrieve project variables "'name', 'title', 'root_path'"
     * @param   langProject  $project
     * @param                $prjId
     *
     *
     * @throws Exception
     * @since version
     */
    private function addPrjDbData(langProject $project, $prjId)
    {
        $project->dbId = $prjId;

        try {
            //--- collect data from manifest -----------------
            $db = Factory::getDbo();

            $query = $db->getQuery(true)
                ->select($db->quoteName('name'))
                ->select($db->quoteName('title'))
                ->select($db->quoteName('root_path'))
                ->where($db->quoteName('id') . ' = ' . (int)$prjId)
                ->from($db->quoteName('#__lang4dev_manifests'))//				->order($db->quoteName('subPrjType') . ' ASC')
            ;

            // Get the options.
            $prjDb = $db->setQuery($query)->loadObject();

            // $project->prjName = $prjDb->name;
            $project->prjName     = $prjDb->title;
            $project->prjRootPath = $prjDb->root_path;

            /** doesn't have these ... *
             * $project->prjXmlPathFilename = $prjDb->prjXmlPathFilename;
             * $project->installPathFilename = $prjDb->installPathFilename;
             * $project->langIdPrefix = $prjDb->langIdPrefix;
             * $project->subPrjType = $prjDb->subPrjType;
             * /**/

            // = prjType ???
            // Not in DB actually: $project->langIdPrefix = $prjDb->;
            // $project->isSysFileFound = $prjDb->;

        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing addPrjDbData: ' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return;
    }

}
