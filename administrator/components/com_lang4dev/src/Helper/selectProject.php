<?php
/**
 * @version
 * @package       Lang4dev
 * @copyright (C) 2022-2022 Lang4dev Team
 * @license
 */

namespace Finnern\Component\Lang4dev\Administrator\Helper;

use Finnern\Component\Lang4dev\Administrator\Helper\langProject;

function createPrj_RSG2 () {

	$prjRsgallery2 = new langProject ();

	$subPrj = $prjRsgallery2->addSubProject('com_rsgallery2',
		langSubProject::PRJ_TYPE_COMP_BACK_SYS,
		JPATH_ADMINISTRATOR . '/components/com_rsgallery2',
	);

	$subPrj = $prjRsgallery2->addSubProject('com_rsgallery2',
		langSubProject::PRJ_TYPE_COMP_BACK,
		JPATH_ADMINISTRATOR. '/components/com_rsgallery2'
	);

	$subPrj = $prjRsgallery2->addSubProject('com_rsgallery2',
		langSubProject::PRJ_TYPE_COMP_SITE,
		JPATH_SITE . '/components/com_rsgallery2'
	);

	return $prjRsgallery2;
}


function createPrj_JoomGallery () {

	$prjJoomGallery = new langProject ();

	$subPrj = $prjJoomGallery->addSubProject('com_joomgallery',
		langSubProject::PRJ_TYPE_COMP_BACK_SYS,
		JPATH_ADMINISTRATOR . '/components/com_joomgallery',
	);

	$subPrj = $prjJoomGallery->addSubProject('com_joomgallery',
		langSubProject::PRJ_TYPE_COMP_BACK,
		JPATH_ADMINISTRATOR. '/components/com_joomgallery'
	);

	$subPrj = $prjJoomGallery->addSubProject('com_joomgallery',
		langSubProject::PRJ_TYPE_COMP_SITE,
		JPATH_SITE . '/components/com_rsgallery2'
	);

	$subPrj = $prjJoomGallery->addSubProject('joomgallerycategories',
		langSubProject::PRJ_TYPE_PLUGIN,
		JPATH_PLUGINS . '/finder/joomgallerycategories'
	);

	$subPrj = $prjJoomGallery->addSubProject('joomgalleryimages',
		langSubProject::PRJ_TYPE_PLUGIN,
		JPATH_PLUGINS . '/finder/joomgalleryimages'
	);

	$subPrj = $prjJoomGallery->addSubProject('joomgallerycategories',
		langSubProject::PRJ_TYPE_PLUGIN,
		JPATH_PLUGINS . '/privacy/joomgalleryimages'
	);


	$subPrj = $prjJoomGallery->addSubProject('web-joomgallery',
		langSubProject::PRJ_TYPE_PLUGIN,
		JPATH_PLUGINS . '/webservices/joomgallery'
	);

	return $prjJoomGallery;
}

function createPrj_Lang4Dev ()
{
		//--- lang4dev --------------------------------

		$prjLang4dev = new langProject ();

		$subPrj = $prjLang4dev->addSubProject('com_lang4dev',
			langSubProject::PRJ_TYPE_COMP_BACK_SYS,
			JPATH_ADMINISTRATOR . '/components/com_lang4dev'
		);

		$subPrj = $prjLang4dev->addSubProject('com_lang4dev',
			langSubProject::PRJ_TYPE_COMP_BACK,
			JPATH_ADMINISTRATOR . '/components/com_lang4dev'
		);

	return $prjLang4dev;
}

function createPrj_Joomla4x ()
{
		//--- lang4dev --------------------------------

		$prjLang4dev = new langProject ();

		// two paths ? (1) for trans eng, (2) trans ger destination

	return $prjLang4dev;
}


	function selectProject ($prjName='') {

	$langProject = new langProject ();

	switch ($prjName) {
		case 'lang4dev':
			$langProject = createPrj_Lang4Dev();
		break;

		case 'rsgallery':
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