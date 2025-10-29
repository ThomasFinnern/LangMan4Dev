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
use Finnern\Component\Lang4dev\Administrator\Helper\basePrjPathFinder;
use Finnern\Component\Lang4dev\Administrator\Helper\eSubProjectType;
use Finnern\Component\Lang4dev\Administrator\Helper\langProject;
use Finnern\Component\Lang4dev\Administrator\Helper\manifestLangFiles;
use Finnern\Component\Lang4dev\Administrator\Helper\projectType;
use Finnern\Component\Lang4dev\Administrator\Helper\sessionProjectId;
use JForm;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\MVC\Model\BaseModel;
use RuntimeException;

use function defined;

//use Joomla\CMS\MVC\Model\ListModel;

/**
 * Item Model for a Configuration items (options).
 *
 * @since __BUMP_VERSION__
 */

// ToDo: create one base model ProjectBaseModel for PrjTextsModel and TranslateModel to derive from ==> DRY method

/**
 * @package     Finnern\Component\Lang4dev\Administrator\Model
 *
 * @since       version
 */
class PrjTextsModel extends AdminModel
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
    public $typeAlias = 'com_lang4dev.prjtexts';

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
            'com_lang4dev.prjtexts',
            'prjtexts',
            array('control' => 'jform', 'load_data' => $loadData)
        );

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     *
     * @param $prjDbId
     * @param $subPrjActive
     *
     * @return langProject
     *
     * @since version
     */
    public function getProject($prjDbId, $subPrjActive) : langProject
    {
        $langProject = new langProject ();

        //--- get parent langProject ----------------------------------

        $this->addPrjDbData($langProject, $prjDbId);

        //--- all sub ids ----------------------------------------------

        $dbSubProjects = $this->subPrjsDbData($prjDbId);

        //--- add subprojects ------------------------------------

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

            $prjXmlPathFilename = $dbSub->prjXmlPathFilename;
            $oManifestLangFiles = new manifestLangFiles ($prjXmlPathFilename);

            $oBasePrjPath = new basePrjPathFinder (
                $dbSub->prjId,
                dirname($prjXmlPathFilename));

            $subPrj = $langProject->addSubProject(
                $dbSub->prjId,
                projectType::int2prjType( $dbSub->subPrjType),
                $oBasePrjPath,
                $oManifestLangFiles
            );

            $subPrj->installPathFilename = $dbSub->installPathFilename;
            $subPrj->langIdPrefix        = $dbSub->langIdPrefix;
	        // Not needed ? 2022.12.25 $subPrj->isLangAtStdJoomla   = $dbSub->isLangAtStdJoomla;
        }

        return $langProject;
    }

    /**
     * @param $parent_id
     *
     * @return array|mixed
     *
     * @throws Exception
     * @since version
     */
    private function subPrjsDbData($parent_id)
    {
        $dbSubProjects = [];

        try {
            //--- collect data from manifest -----------------
            $db = Factory::getDbo();

            $query = $db->createQuery()
                ->select($db->quoteName('id'))
                ->select($db->quoteName('prjId'))
                ->select($db->quoteName('subPrjType'))
                ->select($db->quoteName('langIdPrefix'))
                ->select($db->quoteName('isLangAtStdJoomla'))
                ->select($db->quoteName('root_path'))
                ->select($db->quoteName('prjXmlPathFilename'))
                ->select($db->quoteName('installPathFilename'))
                ->where($db->quoteName('parent_id') . ' = ' . (int)$parent_id)
                ->from($db->quoteName('#__lang4dev_subprojects'))
                ->order($db->quoteName('subPrjType') . ' ASC');

            // Get the options.
            $dbSubProjects = $db->setQuery($query)->loadObjectList();
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing collectSubProjectIds: ' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $dbSubProjects;
    }

    /**
     * retrieve project variables "'name', 'title', 'root_path'"
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

            $query = $db->createQuery()
                ->select($db->quoteName('name'))
                ->select($db->quoteName('title'))
                ->select($db->quoteName('root_path'))
                ->where($db->quoteName('id') . ' = ' . (int)$prjId)
                ->from($db->quoteName('#__lang4dev_projects'))//				->order($db->quoteName('subPrjType') . ' ASC')
            ;

            // Get the options.
            $prjDb = $db->setQuery($query)->loadObject();

            if ( ! empty ($prjDb->name)) {
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
            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing collectSubProjectIds: ' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return;
    }

}

