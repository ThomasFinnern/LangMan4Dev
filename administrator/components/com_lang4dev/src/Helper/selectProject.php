<?php
/**
 * Select prepared project
 *
 * @version
 * @package       Lang4dev
 * @copyright (C) 2022-2022 Lang4dev Team
 * @license
 */

namespace Finnern\Component\Lang4dev\Administrator\Helper;

use Finnern\Component\Lang4dev\Administrator\Helper\langProject;

/**
 *
 * @return \Finnern\Component\Lang4dev\Administrator\Helper\langProject
 *
 * @since version
 */
function createPrj_RSG2()
{
    $prjLang4dev = new langProject ();

    $subPrj = $prjLang4dev->addSubProject(
        'com_lang4dev',
        projectType::PRJ_TYPE_COMP_BACK_SYS,
        JPATH_ADMINISTRATOR . '/components/com_lang4dev',
    );

    $subPrj = $prjLang4dev->addSubProject(
        'com_lang4dev',
        projectType::PRJ_TYPE_COMP_BACK,
        JPATH_ADMINISTRATOR . '/components/com_lang4dev'
    );

    $subPrj = $prjLang4dev->addSubProject(
        'com_lang4dev',
        projectType::PRJ_TYPE_COMP_SITE,
        JPATH_SITE . '/components/com_lang4dev'
    );

    return $prjLang4dev;
}

/**
 *
 * @return \Finnern\Component\Lang4dev\Administrator\Helper\langProject
 *
 * @since version
 */
function createPrj_JoomGallery()
{
    $prjJoomGallery = new langProject ();

    $subPrj = $prjJoomGallery->addSubProject(
        'com_joomgallery',
        projectType::PRJ_TYPE_COMP_BACK_SYS,
        JPATH_ADMINISTRATOR . '/components/com_joomgallery',
    );

    $subPrj = $prjJoomGallery->addSubProject(
        'com_joomgallery',
        projectType::PRJ_TYPE_COMP_BACK,
        JPATH_ADMINISTRATOR . '/components/com_joomgallery'
    );

    $subPrj = $prjJoomGallery->addSubProject(
        'com_joomgallery',
        projectType::PRJ_TYPE_COMP_SITE,
        JPATH_SITE . '/components/com_lang4dev'
    );

    $subPrj = $prjJoomGallery->addSubProject(
        'joomgallerycategories',
        projectType::PRJ_TYPE_PLUGIN,
        JPATH_PLUGINS . '/finder/joomgallerycategories'
    );

    $subPrj = $prjJoomGallery->addSubProject(
        'joomgalleryimages',
        projectType::PRJ_TYPE_PLUGIN,
        JPATH_PLUGINS . '/finder/joomgalleryimages'
    );

    $subPrj = $prjJoomGallery->addSubProject(
        'joomgallerycategories',
        projectType::PRJ_TYPE_PLUGIN,
        JPATH_PLUGINS . '/privacy/joomgalleryimages'
    );

    $subPrj = $prjJoomGallery->addSubProject(
        'web-joomgallery',
        projectType::PRJ_TYPE_PLUGIN,
        JPATH_PLUGINS . '/webservices/joomgallery'
    );

    return $prjJoomGallery;
}

/**
 *
 * @return \Finnern\Component\Lang4dev\Administrator\Helper\langProject
 *
 * @since version
 */
function createPrj_Lang4Dev()
{
    //--- lang4dev --------------------------------

    $prjLang4dev = new langProject ();

    $subPrj = $prjLang4dev->addSubProject(
        'com_lang4dev',
        projectType::PRJ_TYPE_COMP_BACK_SYS,
        JPATH_ADMINISTRATOR . '/components/com_lang4dev'
    );

    $subPrj = $prjLang4dev->addSubProject(
        'com_lang4dev',
        projectType::PRJ_TYPE_COMP_BACK,
        JPATH_ADMINISTRATOR . '/components/com_lang4dev'
    );

    return $prjLang4dev;
}

/**
 *
 * @return \Finnern\Component\Lang4dev\Administrator\Helper\langProject
 *
 * @since version
 */
function createPrj_Joomla4x()
{
    //--- lang4dev --------------------------------

    $prjLang4dev = new langProject ();

    // two paths ? (1) for trans eng, (2) trans ger destination

    return $prjLang4dev;
}

/**
 * @param $prjName
 *
 * @return \Finnern\Component\Lang4dev\Administrator\Helper\langProject
 *
 * @since version
 */
function selectProject($prjName = '')
{
    $langProject = new langProject ();

    switch ($prjName) {
        case 'lang4dev':
            $langProject = createPrj_Lang4Dev();
            break;

        case 'rsgallery2':
            $langProject = createPrj_RSG2();
            break;

        case 'joomgallery':
            $langProject = createPrj_JoomGallery();
            break;

        case 'joomla4x':
            $langProject = createPrj_Joomla4x();
            break;
    }

    return $langProject;
}