<?php
/**
 * Translation items collected from code file
 *
 * @version       
 * @package       Lang4dev
 * @copyright (C) 2022-2022 Lang4dev Team
 * @license       
 */

namespace Finnern\Component\Lang4dev\Administrator\Helper;

use Joomla\CMS\Factory;

// no direct access
\defined('_JEXEC') or die;

/**
 * Keeps one location of one language item
 * with file name, location by line and column index
 *
 * @package Lang4dev
 *
 * @since __BUMP_VERSION__
 */
class langLocation
{
	public $name = '';
    public $file = '';
    public $path = '';
	public $lineIdx = -1;
	public $colIdx = -1;


	/**

	 * @since __BUMP_VERSION__
	 */
	public function __construct(
		$name = '',
		$file = '',
		$path = '',
		$lineIdx = -1,
		$colIdx =-1)
	{
		$this->name = $name;
		$this->file = $file;
		$this->path = $path;
		$this->lineIdx = $lineIdx;
		$this->colIdx = $colIdx;
	}

}