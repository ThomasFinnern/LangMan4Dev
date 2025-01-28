<?php

// Not used ??

/**
 * @package         LangMan4Dev
 * @subpackage      com_lang4dev
 * @author          Thomas Finnern <InsideTheMachine.de>
 * @copyright  (c)  2022-2025 Lang4dev Team
 * @license         GNU General Public License version 2 or later
 */

namespace Finnern\Component\Lang4dev\Administrator\Helper;

use Joomla\CMS\Factory;

use function defined;

// no direct access
defined('_JEXEC') or die;

/**
 * Keeps one location of one language item
 * with file name, location by line and column index
 *
 * Different data view on single translation item as by language files
 *
 * @package Lang4dev
 *
 * @since   __BUMP_VERSION__
 */
class langTranslationSet
{
    /**
     * @var mixed|string
     * @since version
     */
    public $name = '';
    /**
     * @var array
     * @since version
     */
    public $translationsets = [];
    public $path = '';

    // ? expected languages for save
    // public langIdsRequired;

    /**
     * @since __BUMP_VERSION__
     */
    function __construct(
        $name = '',
        $lineNr = -1,
        $colIdx = -1
    ) {
        $this->name = $name;

        $this->translationsets = [];
    }

    // ToDo: get langIds ...

}