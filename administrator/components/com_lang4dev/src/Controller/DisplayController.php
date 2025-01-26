<?php
/**
 * @package       Joomla.Administrator
 * @subpackage    com_lang4dev
 *
 * @copyright  (c)  2022-2025 Lang4dev Team
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Finnern\Component\Lang4dev\Administrator\Controller;

defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\MVC\Controller\BaseController;

use function defined;

/**
 * Lang4dev master display controller.
 *
 * @since  __BUMP_VERSION__
 */
class DisplayController extends BaseController
{
    /**
     * The default view.
     *
     * @var    string
     * @since  __BUMP_VERSION__
     */
    protected $default_view = 'lang4dev';

    /**
     * Method to display a view.
     *
     * @param   boolean  $cachable   If true, the view output will be cached
     * @param   array    $urlparams  An array of safe URL parameters and their variable types, for valid values see {@link \JFilterInput::clean()}.
     *
     * @return  BaseController|bool  This object to support chaining.
     *
     * @throws  Exception
     * @since   __BUMP_VERSION__
     *
     */
    public function display($cachable = false, $urlparams = [])
    {
        return parent::display();
    }
}
