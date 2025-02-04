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

use JForm;
use Joomla\CMS\MVC\Model\AdminModel;

use function defined;

// associations: use Finnern\Component\Lang4dev\Administrator\Helper\Lang4devHelper;

/**
 * Lang4dev Component Lang4dev Model
 *
 * @since __BUMP_VERSION__
 */
class Lang4devModel extends AdminModel
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
    public $typeAlias = 'com_lang4dev.lang4dev';

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
            'com_lang4dev.lang4dev',
            'lang4dev',
            array('control' => 'jform', 'load_data' => $loadData)
        );

        if (empty($form)) {
            return false;
        }

        return $form;
    }

}
