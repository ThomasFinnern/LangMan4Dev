<?php
/**
 * List of translation items collected from code files 
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

 * @since __BUMP_VERSION__
 */

/**
 * Keeps a list of 'transIdLocation's
 *
 * As items may appear twice they are organized within
 * an associative array by name of item linking to
 * an array of the single item
 *
 * So there is a list of all appearances of each item
 * with file name, location, line and column index
 *
 * @package Lang4dev

 * @since __BUMP_VERSION__
 */
class transIdLocations
{

	public $items = [];
 
	public function __construct($items = [])
	{
		if ( !empty ($items))
		{
			$this->items = $items;
		}
	}

	public function addItem ($item)
	{
		$name = $item->name;
		$this->items[$name][] = $item;
	}

	public function getItem ($name, $idx)
	{
		return $this->items[$name][$idx];
	}

	public function getItems ($name)
	{
		return $this->items[$name];
	}

	public function getItemNames ()
	{
		$names = [];

		try {

			foreach ($this->items as $name => $val) {

				$names [] = $name;
			}

		}
		catch (\RuntimeException $e)
		{
			$OutTxt = 'Error executing getItemNames: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $names;
	}

	/*
	 * should be called by names list => similar is in langFiles
	 *
	public function getMissingTransIds ($transIds_file){

		$missing = [];

		$transIds_search = $this->getItemNames ();

		foreach ($transIds_search as $transId)
		{
			if (empty ($transIds_file[$transId])) {

				$missing [] = $transId;
			}
		}

		return $missing;
	}
	/**/

	public function _toText ()
	{
		$text = '';

		try {
			$text = json_encode ($this->items);
		}
		catch (\RuntimeException $e)
		{
			$OutTxt = 'Error executing _toText: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $text;
	}

	public function _toTextNames ($seperator = ', ') // $seperator ='/n'
	{
		$text = '';

		try {

			$names = $this->getItemNames ();

			$text = join ($seperator, $names);

		}
		catch (\RuntimeException $e)
		{
			$OutTxt = 'Error executing _toTextNames: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = Factory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $text;
	}

}