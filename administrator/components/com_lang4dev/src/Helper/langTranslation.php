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
 * prepared items have a twin in the main language and
 * no content (content may exist on later code development)
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
	public $isPrepared = false;

	/**
	 * @since __BUMP_VERSION__
	 */
	public function __construct(
		$transId = '',
		$translationText = '',
		$commentsBefore = [],
		$commentBehind = '',
		$lineNr = -1,
		$isPrepared = false)
	{
		$this->transId         = $transId;
		$this->translationText = $translationText;
		$this->commentsBefore  = $commentsBefore;
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

	/**
	 * @param $lineNr
	 *
	 *
	 * @since version
	 */
	public function init($lineNr = -1)
	{
		$this->transId         = '';
		$this->translationText = '';
		$this->commentsBefore  = [];
		$this->commentBehind   = '';
		$this->lineNr          = $lineNr;
		$this->isPrepared      = false;
	}

}