<?php
/**
 * This class handles the location of one language item found
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
 * keeps one location of one language item
 *    name, file, line, index
 *
 * @package Lang4dev
 */
class langItem
{
	public $name = '';
    public $file = '';
    public $path = '';
	public $lineIdx = -1;
	public $colIdx = -1;


	/**

	 * @since __BUMP_VERSION__
	 */
	function __construct(
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