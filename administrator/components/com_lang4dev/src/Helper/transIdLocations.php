<?php
/**
 * @package         LangMan4Dev
 * @subpackage      com_lang4dev
 * @author          Thomas Finnern <InsideTheMachine.de>
 * @copyright  (c)  2022-2025 Lang4dev Team
 * @license         GNU General Public License version 2 or later
 */

namespace Finnern\Component\Lang4dev\Administrator\Helper;

use Exception;
use Joomla\CMS\Factory;
use RuntimeException;

use function defined;

use Finnern\Component\Lang4dev\Administrator\Helper\transIdLocation;

// no direct access
defined('_JEXEC') or die;

/**
 * @since __BUMP_VERSION__
 */

/**
 * Keeps a list of 'transIdLocation's
 *
 * As items may appear twice they are organized within
 * an associative array by name of item linking to
 * an array of the single item
 *
 * So there is a list of all appearances of each item
 * with file name, location, line and column index
 *
 * @package Lang4dev
 * @since   __BUMP_VERSION__
 */
class transIdLocations
{
    /** @var array[string]array[transIdLocation] */
    public $items = [];

    /**
     * @param array[string]transIdLocation $items
     */
    public function __construct($items = [])
    {
        if (!empty ($items)) {
            $this->items = $items;
        }
    }

    /**
     * @param [string]transIdLocation $item
     *
     *
     * @since version
     */
    public function addItem($item)
    {
        $name                 = $item->name;
        $this->items[$name][] = $item;
    }

    /**
     * @param string $name
     * @param int $idx
     *
     * @return [string]array[transIdLocation]
     *
     * @since version
     */
    public function getItem($name, $idx)
    {
        // ToDo: fallback if not exist
        return $this->items[$name][$idx];
    }

    /**
     * @param $name
     *
     * @return array[transIdLocation]
     *
     * @since version
     */
    public function getItems($name)
    {
        return $this->items[$name];
    }

    /**
     *
     * @return array[string]
     *
     * @throws Exception
     * @since version
     */
    public function getItemNames()
    {
        $names = [];

        try {
            foreach ($this->items as $name => $val) {
                $names [] = $name;
            }
        } catch (RuntimeException $e) {
            $OutTxt = 'Error executing getItemNames: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $names;
    }

    /*
     * should be called by names list => similar is in ??? sourceLangFiles
     *
    public function getMissingTransIds ($transIds_file){

        $missing = [];

        $transIds_search = $this->getItemNames ();

        foreach ($transIds_search as $transId)
        {
            if (empty ($transIds_file[$transId])) {

                $missing [] = $transId;
            }
        }

        return $missing;
    }
    /**/

    /**
     *
     * @return false|string
     *
     * @throws Exception
     * @since version
     */
    public function _toText()
    {
        $text = '';

        try {
            $text = json_encode($this->items);
        } catch (RuntimeException $e) {
            $OutTxt = 'Error executing _toText: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $text;
    }

    /**
     * @param $separator
     *
     * @return string
     *
     * @throws Exception
     * @since version
     */
    public function _toTextNames($separator = ', ') // $separator ='/n'
    {
        $text = '';

        try {
            $names = $this->getItemNames();

            $text = join($separator, $names);
        } catch (RuntimeException $e) {
            $OutTxt = 'Error executing _toTextNames: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $text;
    }

}