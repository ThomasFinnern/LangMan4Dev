<?php
/**
 * This class contains translations file
 * with references to empty lines and comments
 *
 * @version
 * @package       Lang4dev
 * @copyright (C) 2022-2022 Lang4dev Team
 * @license
 */


namespace Finnern\Component\Lang4dev\Administrator\Helper;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;

use Finnern\Component\Lang4dev\Administrator\Helper\langFile;

// no direct access
\defined('_JEXEC') or die;

/**
 * Collect file information and contents of all translation files 
 * for one base folder (existing)
 * Write the changes set inlcuding ToDo: complete sentence

 * The files uses is limitet as *.ini are not useful
 *
 * @package Lang4dev
 */
class langFiles
{
//	public $requiredLangIds = [];

	public string $prjRootPath = '';
	public $namesExisting = [];
	public $langFiles = []; // with content when read

    public bool $isSysType = false;  // lang file type (normal/sys)








} // class