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
use function defined;

// no direct access
defined('_JEXEC') or die;

/**
 * Keeps one translation (line) of one language item
 * with above comments, behind comments
 * ? empty lines ?
 *
 * @package Lang4dev
 *
 * @since   __BUMP_VERSION__
 */
class langTranslation
{
	public $name = ''; // ToDo: rename to transId
	public $translationText = '';
	public $commentsBefore = [];
	public $commentBehind = '';
	public $lineIdx = -1;

	/**
	 * @since __BUMP_VERSION__
	 */
	public function __construct(
		$name = '',
		$translationText = '',
		$commentsBefore = [],
		$commentBehind = '',
		$lineIdx = -1)
	{
		$this->name            = $name;
		$this->translationText = $translationText;
		$this->commentsBefore  = $commentsBefore;
		$this->commentBehind   = $commentBehind;
		$this->lineIdx         = $lineIdx;
	}

	/**
	 * remove all entries but keep line index
	 *
	 * @since version
	 */
	public function clean()
	{
		$this->init($this->lineIdx);
	}

	public function init($lineIdx = -1)
	{
		$this->name            = '';
		$this->translationText = '';
		$this->commentsBefore  = [];
		$this->commentBehind   = '';
		$this->lineIdx         = $lineIdx;
	}

}