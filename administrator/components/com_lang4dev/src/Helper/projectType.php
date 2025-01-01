<?php
/**
 * This class contains translations file
 * with references to empty lines and comments
 *
 * @version
 * @package       Lang4dev
 * @copyright  (c)  2022-2024 Lang4dev Team
 * @license
 */

namespace Finnern\Component\Lang4dev\Administrator\Helper;

use function defined;

// no direct access
defined('_JEXEC') or die;

enum eProjectType // : int
{
    case PRJ_TYPE_NONE;
    case  PRJ_TYPE_COMP_BACK; // PRJ_TYPE_COMPONENT
    case  PRJ_TYPE_COMP_BACK_SYS;
    case  PRJ_TYPE_COMP_SITE;
    case  PRJ_TYPE_MODEL;
    case  PRJ_TYPE_PLUGIN;
}

/**
 * Collect contents of all translation files for one base folder (existing)
 * Write the changes set including
 * The files uses is limited as *.ini are not useful
 *
 * @package     Finnern\Component\Lang4dev\Administrator\Helper
 * *
 * * @since       version
 */

class projectType
{
    // ToDo: create enum

    // const PRJ_TYPE_TEMPLATE = 1;

    /**
     * @param $prjId
     *
     * @return eProjectType
     *
     * @since version
     */
    public static function prjTypeByProjectId($prjId) : eProjectType
    {
        $prjType = eProjectType::PRJ_TYPE_NONE;

        switch (strtolower(substr($prjId, 0, 3))) {
            case "com":
                // Attention: lang projects have three here
                $prjType = eProjectType::PRJ_TYPE_COMP_BACK; // PRJ_TYPE_COMPONENT;
                break;
            case "mod":
                $prjType = eProjectType::PRJ_TYPE_MODEL;
                break;
            case "plg":
                $prjType = eProjectType::PRJ_TYPE_PLUGIN;
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
     * @return array eProjectType
     *
     * @since version
     */
    public static function prjTypesByProjectId($prjId) : array
    {
        $prjTypes = [];

        // ToDo: Package
        switch (strtolower(substr($prjId, 0, 3))) {
            case "com":
                // Attention: lang projects have three here
                $prjTypes[] = eProjectType::PRJ_TYPE_COMP_BACK;
                $prjTypes[] = eProjectType::PRJ_TYPE_COMP_BACK_SYS;
                $prjTypes[] = eProjectType::PRJ_TYPE_COMP_SITE;
                break;
            case "mod":
                $prjTypes[] = eProjectType::PRJ_TYPE_MODEL;
                break;
            case "plg":
                $prjTypes[] = eProjectType::PRJ_TYPE_PLUGIN;
                break;
            default:
                // dummy
                break;
        }

        return $prjTypes;
    }

    // *.Sys.ini files are not used for backend normal(separate type backend sys)  and site

    /**
     * @param $prjType
     *
     * @return bool
     *
     * @since version
     */
    public static function subPrjHasSysFiles($prjType) : bool
    {
        $hasSysFiles = true;

        if ($prjType == eProjectType::PRJ_TYPE_COMP_BACK) {
            $hasSysFiles = false;
        }

        if ($prjType == eProjectType::PRJ_TYPE_COMP_SITE) {
            $hasSysFiles = false;
        }

        return $hasSysFiles;
    }

    /**
     * @param $prjType
     *
     * @return array [bool, bool]
     *
     * @since version
     */
    public static function enabledByType($prjType) : array
    {
        $isSearchXml     = false;
        $isSearchInstall = false;

        if ($prjType == eProjectType::PRJ_TYPE_COMP_BACK_SYS
            || $prjType == eProjectType::PRJ_TYPE_MODEL
            || $prjType == eProjectType::PRJ_TYPE_PLUGIN
        ) {
            $isSearchXml = true;
        }

        if ($prjType == eProjectType::PRJ_TYPE_COMP_BACK_SYS
            || $prjType == eProjectType::PRJ_TYPE_MODEL
            || $prjType == eProjectType::PRJ_TYPE_PLUGIN
        ) {
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
    public static function getPrjTypeText($prjType) : string
    {
        $typename = '? type';

        switch ($prjType) {
            case eProjectType::PRJ_TYPE_NONE:
                $typename = 'type-none';
                break;

            case eProjectType::PRJ_TYPE_COMP_BACK_SYS:
                $typename = 'type-backend-sys';
                break;

            case eProjectType::PRJ_TYPE_COMP_BACK:
                $typename = 'type-backend';
                break;

            case eProjectType::PRJ_TYPE_COMP_SITE:
                $typename = 'type-site';
                break;

            case eProjectType::PRJ_TYPE_MODEL:
                $typename = 'type-model';
                break;

            case eProjectType::PRJ_TYPE_PLUGIN:
                $typename = 'type-plugin';
                break;
        }

        return $typename;
    }

    public static function int2prjType2(int $prjTypeInt) : eProjectType
    {
        $prjType = eProjectType::PRJ_TYPE_NONE;

        switch ($prjTypeInt) {
            case 0:
            $prjType = eProjectType::PRJ_TYPE_NONE;
                break;

            case 1:
            $prjType = eProjectType::PRJ_TYPE_COMP_BACK_SYS;
                break;

            case 2:
            $prjType = eProjectType::PRJ_TYPE_COMP_BACK;
                break;

            case 3:
            $prjType = eProjectType::PRJ_TYPE_COMP_SITE;
                break;

            case 4:
            $prjType = eProjectType::PRJ_TYPE_MODEL;
                break;

            case 5:
            $prjType = eProjectType::PRJ_TYPE_PLUGIN;
                break;
        }

        return $prjType;
    }

    public static function prjType2int(eProjectType $prjType) : string
    {
        $prjTypeInt = 0;

        switch ($prjType) {
            case eProjectType::PRJ_TYPE_NONE:
                $prjTypeInt = 0;
                break;

            case eProjectType::PRJ_TYPE_COMP_BACK_SYS:
                $prjTypeInt = 1;
                break;

            case eProjectType::PRJ_TYPE_COMP_BACK:
                $prjTypeInt = 2;
                break;

            case eProjectType::PRJ_TYPE_COMP_SITE:
                $prjTypeInt = 3;
                break;

            case eProjectType::PRJ_TYPE_MODEL:
                $prjTypeInt = 4;
                break;

            case eProjectType::PRJ_TYPE_PLUGIN:
                $prjTypeInt = 5;
                break;
        }

        return $prjTypeInt;
    }


} // class