<?php
/**
 * @package       lang4dev administrator
 * @subpackage    com_foos
 *
 * @copyright  (c)  2022-2025 Lang4dev Team
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Finnern\Component\Lang4dev\Administrator\Model;

defined('_JEXEC') or die;

use JForm;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\MVC\Model\BaseModel;

use function defined;

//use Joomla\CMS\MVC\Model\ListModel;

/**
 * Item Model for a Configuration items (options).
 *
 * @since __BUMP_VERSION__
 */
class MaintenanceModel extends AdminModel
{

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
            'com_lang4dev.maintenance',
            'maintenance',
            array('control' => 'jform', 'load_data' => $loadData)
        );

        if (empty($form)) {
            return false;
        }

        return $form;
    }

}

