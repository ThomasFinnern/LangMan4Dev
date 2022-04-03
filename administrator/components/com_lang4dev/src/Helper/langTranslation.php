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
	public $transId = '';
	public $translationText = '';
	public $commentsBefore = [];
	public $commentBehind = '';
	public $lineNr = -1;

	/**
	 * @since __BUMP_VERSION__
	 */
	public function __construct(
		$name = '',
		$translationText = '',
		$commentsBefore = [],
		$commentBehind = '',
		$lineNr = -1)
	{
		$this->name            = $name;
		$this->translationText = $translationText;
		$this->commentsBefore  = $commentsBefore;
		$this->commentBehind   = $commentBehind;
		$this->lineNr         = $lineNr;
	}

	/**
	 * remove all entries but keep line index
	 *
	 * @since version
	 */
	public function clean()
	{
		$this->init($this->lineNr);
	}

	public function init($lineNr = -1)
	{
		$this->name            = '';
		$this->translationText = '';
		$this->commentsBefore  = [];
		$this->commentBehind   = '';
		$this->lineNr         = $lineNr;
	}

}