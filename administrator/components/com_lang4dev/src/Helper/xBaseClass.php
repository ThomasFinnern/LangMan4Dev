<?php
/**
 * This class contains translations file
 * with references to empty lines and comments
 *
 * @version
 * @package       Lang4dev
 * @copyright  (c)  2022-2024 Lang4dev Team
 * @license
 */

namespace Finnern\Component\Lang4dev\Administrator\Helper;

use Joomla\CMS\Factory;
use Joomla\Filesystem\Folder;

use Finnern\Component\Lang4dev\Administrator\Helper\langFiles;

use function defined;

// no direct access
defined('_JEXEC') or die;

/**
 * Collect contents of all translation files for one base folder (existing)
 * Write the changes set inlcuding
 * The files uses is limitet as *.ini are not useful
 *
 * @package Lang4dev
 */
class langFilesSet
{
    public $fileTypes = 'php, xml';

} // class