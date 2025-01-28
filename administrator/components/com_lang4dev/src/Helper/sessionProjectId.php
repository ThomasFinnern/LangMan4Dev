<?php
/**
 * @package         LangMan4Dev
 * @subpackage      com_lang4dev
 * @author          Thomas Finnern <InsideTheMachine.de>
 * @copyright  (c)  2022-2025 Lang4dev Team
 * @license         GPL2
 */

namespace Finnern\Component\Lang4dev\Administrator\Helper;

use Joomla\CMS\Factory;

use function defined;

// no direct access
defined('_JEXEC') or die;

/**
 * Reads and writes user selected project id and subproject id from / to session
 *
 * The initial ID is the highest project id in db and zero for the sub id
 * Zero on sub id tells that all subprojects are displayed to the user
 *
 * @package Lang4dev
 */
class sessionProjectId
{
    /**
     * @var string
     * @since version
     */
    protected $prjId = '-1';
    protected $subPrjActive = '0';

    /**
     *
     *
     * @since version
     */
    public function clear()
    {
        $prjId        = '-1';
        $subPrjActive = '0';

        return;
    }

    /**
     * setIds
     *
     * @return
     * @since __BUMP_VERSION__
     */
    public function setIds($prjId = '-1', $subPrjActive = '0')
    {
        $this->prjId        = $prjId;
        $this->subPrjActive = $subPrjActive;

        $session = Factory::getSession();
        $data    = $session->set('_lang4dev.prjId', $prjId);
        $data    = $session->set('_lang4dev.subPrjActive', $subPrjActive);

        return;
    }

    /**
     *
     *
     * @since version
     */
    public function resetIds()
    {
        // default values
        //$this->setIds();

        $this->clear();

        $session = Factory::getSession();
        $session->clear('_lang4dev.prjId');
        $session->clear('_lang4dev.subPrjActive');

        return;
    }

    /**
     * getIds
     *
     * @return integer [] project id, subproject id
     * @since __BUMP_VERSION__
     */
    public function getIds()
    {
        //--- already set in class ? ---------------------

        $prjId        = $this->prjId;
        $subPrjActive = $this->subPrjActive;

        // Is not set
        if ($prjId < 0) {
            //--- try session if set ---------------------------------

            $session = Factory::getSession();
            $prjId   = (int)$session->get('_lang4dev.prjId', '-1');
            if ($prjId > 0) {
                $subPrjActive = (int)$session->get('_lang4dev.subPrjActive', '0');
            }

            // Is not set (in control) => use latest
            if ($prjId <= 0) {
                //--- retrieve last created from DB ---------------------------------

                $prjId        = $this->highestProjectId_DB();
                $subPrjActive = 0; // view all
            }
        }

        return [$prjId, $subPrjActive];
    }

    /**
     *
     * @return integer highest ID of created projects
     *
     * @since version
     */
    private function highestProjectId_DB()
    {
        $max = 0; // indicates nothing found in DB

        $db    = Factory::getDbo();
        $query = $db->getQuery(true)
            ->select('MAX(id)')
            ->from($db->quoteName('#__lang4dev_projects'));
        $db->setQuery($query);
        $max = $db->loadResult();

        return (int)$max;
    }

}