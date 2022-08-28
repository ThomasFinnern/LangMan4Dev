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
use Joomla\CMS\Filesystem\Folder;

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
class projectType
{
	/*   */
	const PRJ_TYPE_NONE = 0;
	const PRJ_TYPE_COMPONENT = 1;
	const PRJ_TYPE_COMP_BACK = 1;
	const PRJ_TYPE_COMP_BACK_SYS = 2;
	const PRJ_TYPE_COMP_SITE = 3;
	const PRJ_TYPE_MODEL = 4;
	const PRJ_TYPE_PLUGIN = 5;

	// const PRJ_TYPE_TEMPLATE = 1;

	/**
	 * @param $prjId
	 *
	 * @return int
	 *
	 * @since version
	 */
	public static function prjTypeByProjectId($prjId)
	{

		$prjType = self::PRJ_TYPE_NONE;

		switch (strtolower(substr($prjId, 0, 3)))
		{
			case "com":
				// Attention: lang projects have three here
				$prjType = self::PRJ_TYPE_COMPONENT;
				break;
			case "mod":
				$prjType = self::PRJ_TYPE_NONE;
				break;
			case "plg":
				$prjType = self::PRJ_TYPE_NONE;
				break;
			default:
				// dummy
				break;
		}

		return $prjType;
	}

	/**
	 * @param $prjId
	 *
	 * @return array
	 *
	 * @since version
	 */
	public static function prjTypesByProjectId($prjId)
	{

		$prjTypes = [];

		switch (strtolower(substr($prjId, 0, 3)))
		{
			case "com":
				// Attention: lang projects have three here
				$prjTypes[] = self::PRJ_TYPE_COMP_BACK;
				$prjTypes[] = self::PRJ_TYPE_COMP_BACK_SYS;
				$prjTypes[] = self::PRJ_TYPE_COMP_SITE;
				break;
			case "mod":
				$prjTypes[] = self::PRJ_TYPE_NONE;
				break;
			case "plg":
				$prjTypes[] = self::PRJ_TYPE_NONE;
				break;
			default:
				// dummy
				break;
		}

		return $prjTypes;
	}

	// *.Sys.ini files are not used for backend normal(seperate type backend sys)  and site

	/**
	 * @param $prjType
	 *
	 * @return bool
	 *
	 * @since version
	 */
	public static function subPrjHasSysFiles($prjType)
	{
		$hasSysFiles = true;

		if ($prjType == projectType::PRJ_TYPE_COMP_BACK)
		{
			$hasSysFiles = false;
		}

		if ($prjType == projectType::PRJ_TYPE_COMP_SITE)
		{
			$hasSysFiles = false;
		}

		return $hasSysFiles;
	}

	/**
	 * @param $prjType
	 *
	 * @return bool[]
	 *
	 * @since version
	 */
	public static function enabledByType($prjType)
	{
		$isSearchXml     = false;
		$isSearchInstall = false;

		if ($prjType == projectType::PRJ_TYPE_COMP_BACK_SYS
			|| $prjType == projectType::PRJ_TYPE_MODEL
			|| $prjType == projectType::PRJ_TYPE_PLUGIN
		)
		{
			$isSearchXml = true;
		}

		if ($prjType == projectType::PRJ_TYPE_COMP_BACK_SYS
			|| $prjType == projectType::PRJ_TYPE_MODEL
			|| $prjType == projectType::PRJ_TYPE_PLUGIN
		)
		{
			$isSearchInstall = true;
		}

		return [$isSearchXml, $isSearchInstall];
	}

	/**
	 * @param $prjType
	 *
	 * @return string
	 *
	 * @since version
	 */
	public static function getPrjTypeText($prjType)
	{
		$typename = '? type';

		switch ($prjType)
		{

			case self::PRJ_TYPE_NONE:
				$typename = 'type-none';
				break;

			case self::PRJ_TYPE_COMP_BACK_SYS:
				$typename = 'type-backend-sys';
				break;

			case self::PRJ_TYPE_COMP_BACK:
				$typename = 'type-backend';
				break;

			case self::PRJ_TYPE_COMP_SITE:
				$typename = 'type-site';
				break;

			case self::PRJ_TYPE_MODEL:
				$typename = 'type-model';
				break;

			case self::PRJ_TYPE_PLUGIN:
				$typename = 'type-plugin';
				break;

		}

		return $typename;
	}

} // class