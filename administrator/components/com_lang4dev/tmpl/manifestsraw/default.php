<?php
/**
 * @package         LangMan4Dev
 * @subpackage      com_lang4dev
 * @author          Thomas Finnern <InsideTheMachine.de>
 * @copyright  (c)  2022-2025 Lang4dev Team
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */


defined('_JEXEC') or die;

use Finnern\Component\Lang4dev\Administrator\Helper\manifestLangFiles;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;


function renderPrjHeader($dbProjectData)
{
    ?>
	<br>
	<HR>
    <?php

	echo '=============================================' . '<br>';

    echo 'Title: ' . $dbProjectData->title . '<br>';
    echo 'Name: ' . $dbProjectData->name . '<br>';
    echo 'Id: ' . $dbProjectData->id . '<br>';
    echo 'root_path: ' . $dbProjectData->root_path . '<br>';

    echo '=============================================' . '<br>';

    return;
}

function renderManifest(manifestLangFiles $manifest)
{
    ?>
	<br>

    <?php

    echo implode("<br>", $manifest->__toText());

    return;
}

/**
 * @param   manifestLangFiles[]  $manifests
 *
 *
 * @since version
 */
function renderPrjManifests(array $prjMmanifests)
{
    ?>
    <?php
		// ToDo: Add card ...
		// add scss
    ?>

    <?php

    foreach ($prjMmanifests as $prjMmanifest) {

		[$dbPrjData, $manifest] = $prjMmanifest;

        renderPrjHeader($dbPrjData);
        renderManifest($manifest);

	}

    return;
}


?>
<form action="<?php echo Route::_('index.php?option=com_lang4dev&view=projectsraw'); ?>"
      method="post" name="adminForm" id="adminForm">

    <?php

    renderPrjManifests($this->PrjManifestsData);

    ?>
    <HR>


    <input type="hidden" name="task" value=""/>
    <?php
    echo HTMLHelper::_('form.token'); ?>
</form>


