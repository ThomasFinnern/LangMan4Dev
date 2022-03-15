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
 * Keeps one translation (line) of one language item
 * with above comments, behind comments
 * ? empty lines ?
 *
 * @package Lang4dev
 *
 * @since __BUMP_VERSION__
 */
class langTranslation
{
	public $name = '';
    public $translation = '';
    public $commentBefore = [];
    public $commentBehind = '';
    public $lineIdx = -1;


	/**

	 * @since __BUMP_VERSION__
	 */
	function __construct(
		$name = '',
		$translation = '',
		$commentBefore = [],
		$commentBehind = '',
		$lineIdx = -1)
	{
		$this->name = $name;
		$this->translation = $translation;
		$this->commentBefore = $commentBefore;
		$this->commentBehind = $commentBehind;
		$this->lineIdx = $lineIdx;
	}

}