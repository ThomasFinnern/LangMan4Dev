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
use Finnern\Component\Lang4dev\Administrator\Helper\langProject;
use Finnern\Component\Lang4dev\Administrator\Helper\manifestLangFiles;
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
     * The type alias for this content type. Used for content version history.
     *
     * @var      string
     * @since __BUMP_VERSION__
     */
    public $typeAlias = 'com_lang4dev.manifestsraw';

    /**
     * Collect all Projects from DB
     *
     * @return array|mixed
     *
     * @throws Exception
     * @since version
     */
    private function getProjectsDb() : array
    {
        $dbProjects = [];

        try {
            //--- collect data from manifest -----------------
            $db = Factory::getDbo();

            $query = $db->getQuery(true)
                ->select($db->quoteName('id'))
                ->select($db->quoteName('title'))
                ->select($db->quoteName('name'))
                ->select($db->quoteName('root_path'))
                ->select($db->quoteName('prjType'))
                ->from($db->quoteName('#__lang4dev_projects'))
                ->order($db->quoteName('id') . ' ASC');

            // Get the options.
            $dbProjects = $db->setQuery($query)->loadObjectList();
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing getProjectsDb: ' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $dbProjects;
    }

    /**
     * getPrjManifestsData
     * @param $prjDbId
     * @param $subPrjActive
     *
     * @return langProject
     *
     * @since version
     */
    public function getManifests() : array
    {
        $PrjManifestsData = [];

        //--- get projects ----------------------------------

        $projects = $this->getProjectsDb();

        if ( ! empty($projects)) {

            //--- manifests by project ----------------------------------------------

            foreach ($projects as $project) {

                // debug: restrict to one Id
                if ($project->id != 4) {

                    continue;
                }

                //--- xml path ----------------------------------------

                $oBasePrjPath = new basePrjPathFinder($project->name, trim($project->root_path));
                $prjXmlPathFilename = $oBasePrjPath->prjXmlPathFilename; // . '/lang4dev.xml';

                //---  ----------------------------------------

                $this->manifestLang = new manifestLangFiles ($prjXmlPathFilename);

                if (! empty($this->manifestLang)) {

                    $PrjManifestsData[] = [$project, $this->manifestLang];
                }

            }

        } // empty ...

        return $PrjManifestsData;
    }

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
        $form = null;
//        $this->loadForm(
//            'com_lang4dev.projectsraw',
//            'projectsraw',
//            array('control' => 'jform', 'load_data' => $loadData)
//        );

        if (empty($form)) {
            return false;
        }

        return $form;
    }


}
