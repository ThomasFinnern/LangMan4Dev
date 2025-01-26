<?php
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
 * @package Lang4dev
 *
 * @since   __BUMP_VERSION__
 */
class transIdLocation
{
    /** @var string */
    public $name = '';
    /** @var string */
    public $file = '';
    /** @var string */
    public $path = '';
    /** @var int */
    public $lineNr = -1;
    /** @var int */
    public $colIdx = -1;

    public $string;

    // ToDo: constructor property promation PHP 8.x https://stitcher.io/blog/constructor-promotion-in-php-8
    /**
     * @since __BUMP_VERSION__
     */
    public function __construct(
        $name = '',
        $file = '',
        $path = '',
        $lineNr = -1,
        $colIdx = -1
    ) {
        $this->name   = $name;
        $this->file   = $file;
        $this->path   = $path;
        $this->lineNr = $lineNr;
        $this->colIdx = $colIdx;
    }

}