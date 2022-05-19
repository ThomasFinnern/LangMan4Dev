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

	public string $id = '';
	public string $translationText = '';
	public $commentLines = [];
	public string $commentBehind = '';
	public int $lineNr = -1;
	public bool $isPrepared = false; // TransId exist, no text though 

	/**
	 * @since __BUMP_VERSION__
	 */
	public function __construct(
		$id = '',
		$translationText = '',
		$commentLines = [],
		$commentBehind = '',
		$lineNr = -1,
		$isPrepared = false)
	{
		$this->id              = $id;
		$this->translationText = $translationText;
		$this->commentLines    = $commentLines;
		$this->commentBehind   = $commentBehind;
		$this->lineNr          = $lineNr;
		$this->isPrepared      = $isPrepared;
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
		$this->id              = '';
		$this->translationText = '';
		$this->commentLines    = [];
		$this->commentBehind   = '';
		$this->lineNr          = $lineNr;
		$this->isPrepared      = false;
	}

}
