<?php
/**
 * @package         LangMan4Dev
 * @subpackage      com_lang4dev
 * @author          Thomas Finnern <InsideTheMachine.de>
 * @copyright  (c)  2022-2025 Lang4dev Team
 * @license         GNU General Public License version 2 or later
 */

namespace Finnern\Component\Lang4dev\Administrator\Helper;

// no direct access
defined('_JEXEC') or die;

use Finnern\Component\Lang4dev\Administrator\Helper\manifestLangFiles;

enum eSubProjectType // : int
{
    case PRJ_TYPE_NONE;
    case  PRJ_TYPE_COMP_BACK; // PRJ_TYPE_COMPONENT
    case  PRJ_TYPE_COMP_BACK_SYS;
    case  PRJ_TYPE_COMP_SITE;
    case  PRJ_TYPE_MODULE;
    case  PRJ_TYPE_PLUGIN;
    case  PRJ_TYPE_WEB_ADMIN;
    case  PRJ_TYPE_WEB_SITE;
    case  PRJ_TYPE_TEMPLATE;
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

//    /**
//     * @param $prjId
//     *
//     * @return eProjectType
//     *
//     * @since version
//     */
//    public static function prjTypeByProjectId(string $prjId) : eProjectType
//    {
//        $prjType = eProjectType::PRJ_TYPE_NONE;
//
//        switch (strtolower(substr($prjId, 0, 3))) {
//            case "com":
//                // Attention: lang projects have three here
//                $prjType = eProjectType::PRJ_TYPE_COMP_BACK; // PRJ_TYPE_COMPONENT;
//                break;
//            case "mod":
//                $prjType = eProjectType::PRJ_TYPE_MODULE;
//                break;
//            case "plg":
//                $prjType = eProjectType::PRJ_TYPE_PLUGIN;
//                break;
//            case "/":
//            case "\\":
//                $prjType = eProjectType::PRJ_TYPE_WEB_ROOT;
//                break;
//
//            missing types
//
//            default:
//                // dummy
//                break;
//        }
//
//        return $prjType;
//    }

    /**
     * Return a list of language subprojects matching the project by first 3 characters of
     * project ID.
     *  *.Sys.ini files are not used for backend normal(separate type backend sys)  and site
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
                $prjTypes[] = eSubProjectType::PRJ_TYPE_COMP_BACK;
                $prjTypes[] = eSubProjectType::PRJ_TYPE_COMP_BACK_SYS;
                $prjTypes[] = eSubProjectType::PRJ_TYPE_COMP_SITE;
                break;
            case "mod":
                $prjTypes[] = eSubProjectType::PRJ_TYPE_MODULE;
                $prjTypes[] = eSubProjectType::PRJ_TYPE_COMP_BACK_SYS;
                break;
            case "plg":
                $prjTypes[] = eSubProjectType::PRJ_TYPE_PLUGIN;
                $prjTypes[] = eSubProjectType::PRJ_TYPE_COMP_BACK_SYS;
                break;
            // root web page'
            case "/":
            case "\\":
                $prjTypes[] = eSubProjectType::PRJ_TYPE_WEB_ADMIN;
                $prjTypes[] = eSubProjectType::PRJ_TYPE_WEB_SITE;
                break;
            // administrator
            case "adm":
                $prjTypes[] = eSubProjectType::PRJ_TYPE_WEB_ADMIN;
                break;
            // site
            case "sit":
                $prjTypes[] = eSubProjectType::PRJ_TYPE_WEB_SITE;
                break;
            // site
            case "tem":
                $prjTypes[] = eSubProjectType::PRJ_TYPE_TEMPLATE;
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

        if ($prjType == eSubProjectType::PRJ_TYPE_COMP_BACK) {
            $hasSysFiles = false;
        }

        if ($prjType == eSubProjectType::PRJ_TYPE_COMP_SITE) {
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

        if ($prjType == eSubProjectType::PRJ_TYPE_COMP_BACK_SYS
            || $prjType == eSubProjectType::PRJ_TYPE_MODULE
            || $prjType == eSubProjectType::PRJ_TYPE_PLUGIN
        ) {
            $isSearchXml = true;
        }

        if ($prjType == eSubProjectType::PRJ_TYPE_COMP_BACK_SYS
            || $prjType == eSubProjectType::PRJ_TYPE_MODULE
            || $prjType == eSubProjectType::PRJ_TYPE_PLUGIN
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
    public static function prjType2string($prjType) : string
    {
        $typename = '? type';

        switch ($prjType) {
            case eSubProjectType::PRJ_TYPE_NONE:
                $typename = 'type-none';
                break;

            case eSubProjectType::PRJ_TYPE_COMP_BACK_SYS:
                $typename = 'type-backend-sys';
                break;

            case eSubProjectType::PRJ_TYPE_COMP_BACK:
                $typename = 'type-backend';
                break;

            case eSubProjectType::PRJ_TYPE_COMP_SITE:
                $typename = 'type-site';
                break;

            case eSubProjectType::PRJ_TYPE_MODULE:
                $typename = 'type-model';
                break;

            case eSubProjectType::PRJ_TYPE_PLUGIN:
                $typename = 'type-plugin';
                break;

            case eSubProjectType::PRJ_TYPE_WEB_ADMIN:
                $typename = 'type-web-admin';
                break;

            case eSubProjectType::PRJ_TYPE_WEB_SITE:
                $typename = 'type-web-site';
                break;

            case eSubProjectType::PRJ_TYPE_TEMPLATE:
                $typename = 'type-templates';
                break;
        }

        return $typename;
    }

    public static function int2prjType(int $prjTypeInt) : eSubProjectType
    {
        $prjType = eSubProjectType::PRJ_TYPE_NONE;

        switch ($prjTypeInt) {
            case 0:
                $prjType = eSubProjectType::PRJ_TYPE_NONE;
                break;

            case 1:
                $prjType = eSubProjectType::PRJ_TYPE_COMP_BACK_SYS;
                break;

            case 2:
                $prjType = eSubProjectType::PRJ_TYPE_COMP_BACK;
                break;

            case 3:
                $prjType = eSubProjectType::PRJ_TYPE_COMP_SITE;
                break;

            case 4:
                $prjType = eSubProjectType::PRJ_TYPE_MODULE;
                break;

            case 5:
                $prjType = eSubProjectType::PRJ_TYPE_PLUGIN;
                break;

            case 6:
                $prjType = eSubProjectType::PRJ_TYPE_WEB_ADMIN;
                break;

            case 7:
                $prjType = eSubProjectType::PRJ_TYPE_WEB_SITE;
                break;

            case 8:
                $prjType = eSubProjectType::PRJ_TYPE_TEMPLATE;
                break;
        }

        return $prjType;
    }

    public static function prjType2int(eSubProjectType $prjType) : string
    {
        $prjTypeInt = 0;

        switch ($prjType) {
            case eSubProjectType::PRJ_TYPE_NONE:
                $prjTypeInt = 0;
                break;

            case eSubProjectType::PRJ_TYPE_COMP_BACK_SYS:
                $prjTypeInt = 1;
                break;

            case eSubProjectType::PRJ_TYPE_COMP_BACK:
                $prjTypeInt = 2;
                break;

            case eSubProjectType::PRJ_TYPE_COMP_SITE:
                $prjTypeInt = 3;
                break;

            case eSubProjectType::PRJ_TYPE_MODULE:
                $prjTypeInt = 4;
                break;

            case eSubProjectType::PRJ_TYPE_PLUGIN:
                $prjTypeInt = 5;
                break;

            case eSubProjectType::PRJ_TYPE_WEB_ADMIN:
                $prjTypeInt = 6;
                break;

            case eSubProjectType::PRJ_TYPE_WEB_SITE:
                $prjTypeInt = 7;
                break;

            case eSubProjectType::PRJ_TYPE_TEMPLATE:
                $prjTypeInt = 8;
                break;
        }

        return $prjTypeInt;
    }


    public static function isLangInManifest($prjType, manifestLangFiles $manifestFile) : bool {
        $isLangFound = false;

        switch ($prjType) {
            // case eSubProjectType::PRJ_TYPE_NONE:

            case eSubProjectType::PRJ_TYPE_COMP_BACK_SYS:
            case eSubProjectType::PRJ_TYPE_COMP_BACK:
                if ($manifestFile->prjAdminPath != '') {
                    $isLangFound = true;
                }
                break;

            case eSubProjectType::PRJ_TYPE_COMP_SITE:
                if ($manifestFile->prjDefaultPath != '') {
                    $isLangFound = true;
                }
                break;

            case eSubProjectType::PRJ_TYPE_MODULE:
            case eSubProjectType::PRJ_TYPE_PLUGIN:
            case eSubProjectType::PRJ_TYPE_WEB_ADMIN:
            case eSubProjectType::PRJ_TYPE_WEB_SITE:
            case eSubProjectType::PRJ_TYPE_TEMPLATE:
                $isLangFound = true;
                break;
        }

        return $isLangFound;
    }


} // class