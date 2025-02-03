<?php
/**
 * @package         LangMan4Dev
 * @subpackage      com_lang4dev
 * @author          Thomas Finnern <InsideTheMachine.de>
 * @copyright  (c)  2022-2025 Lang4dev Team
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

// HTMLHelper::_('stylesheet', 'com_lang4dev/backend/maintenance.css', array('version' => 'auto', 'relative' => true));
$this->document->getWebAssetManager()->useStyle('com_lang4dev.backend.maintenance');

class zoneContainer {
	public $textTitle;
	public $textInfo;
	public $classContainer;
	public $classTitle;

	public function __construct($textTitle='?', $textInfo='?', $classContainer='?', $classTitle='?')
	{
		$this->textTitle      = $textTitle;
		$this->textInfo       = $textInfo;
		$this->classContainer = $classContainer;
		$this->classTitle     = $classTitle;
	}

}
class zoneButtons
{
    public $link;
    public $textTitle;
    public $textInfo;
    public $classIcons;
    public $classButton;

    public function __construct(
        $link = '?',
        $textTitle = '?',
        $textInfo = '?',
        $classIcons = array('?', '?'),
        $classButton = '?'
    ) {
        $this->link        = $link;
        $this->textTitle   = $textTitle;
        $this->textInfo    = $textInfo;
        $this->classIcons  = $classIcons;
        $this->classButton = $classButton;
    }

}

//$rawDatabase_ZoneButtons[] = new zoneButtons(
//    '',
//    Route::_('index.php?option=com_rsgallery2&view=maintenance&layout=checkimageexif'),
//    Text::_('COM_RSGALLERY2_CHECK_IMAGE_EXIF'),
//    Text::_('COM_RSGALLERY2_CHECK_IMAGE_EXIF_DESC'),
//    // array('icon-images', 'icon-crop', 'fas fa-archive'),
//    ['icon-images', 'fas fa-camera-retro'],
//    'viewGalleriesRaw',
//);
//



//--- Raw database zone -----------------------------

$rawDatabase_Zone = new zoneContainer(Text::_('COM_LANG4DEV_RAW_DB_ZONE'), Text::_('COM_LANG4DEV_RAW_DB_ZONE_DESCRIPTION'), 'rawDb', 'rawDbZone');

//// projects
//$rawDatabase_ZoneButtons[] = new zoneButtons(
//    Route::_('index.php?option=com_lang4dev&view=projects'),
//    Text::_('COM_LANG4DEV_PROJECTS'),
//    Text::_('COM_LANG4DEV_PROJECTS_DESC') . '                        ',
//	array('fas fa-tasks', 'icon-flag'),
//    'viewProjects'
//);

// sub projects
$rawDatabase_ZoneButtons[] = new zoneButtons(
    Route::_('index.php?option=com_lang4dev&view=subprojects'),
    Text::_('COM_LANG4DEV_SUB_PROJECTS'),
    Text::_('COM_LANG4DEV_SUB_PROJECTS_DESC') . '                        ',
	array('fas fa-snowflake', 'icon-flag'),
//    array('icon-moon', 'icon-edit'),
    'viewProjects'
);

//--- Repair zone -----------------------------

//--- danger zone  -----------------------------

//--- developer test zone -----------------------------

$developer_Zone = new zoneContainer(Text::_('COM_LANG4DEV_DEVELOPER_ZONE'), Text::_('COM_LANG4DEV_DEVELOPER_ZONE_DESCRIPTION'), 'developer', 'developerZone');

$developer_ZoneButtons = [];

/**/
$developer_ZoneButtons[] =  new zoneButtons(
	Route::_('index.php?option=com_lang4dev&view=projectsraw'),
	Text::_('COM_LANG4DEV_PROJECTS_JSONIFY'),
	Text::_('COM_LANG4DEV_VIEW_ALL_PROJECTS_AS_JSON_ENCODED_OBJECTS'),
	array('icon-eye-open', 'icon-expand'),
	'view___'
);
/**/

/**/
$developer_ZoneButtons[] =  new zoneButtons(
    Route::_('index.php?option=com_lang4dev&view=manifestsRaw'),
    Text::_('COM_LANG4DEV_MANIFEST_RAW'),
    Text::_('COM_LANG4DEV_VIEW_ALL_MANIFEST_LANGUAGE_DATA'),
	array('icon-eye-open', 'icon-expand'),
	'view___'
);
/**/

/**
$developer_ZoneButtons[] =  new zoneButtons(
	Route::_('index.php?option=com_lang4dev&view=develop&layout=InstallMessage'),
	Text::_('COM_LANG4DEV_TEST_INSTALL_UPDATE_MESSAGE'),
	Text::_('COM_LANG4DEV_TEST_INSTALL_UPDATE_MESSAGE_DESC'),
	array('icon-eye-open', 'icon-expand'),
	'view___'
);
/**/

//$developer4Test_Zone = new zoneContainer(Text::_('COM_LANG4DEV_DEVELOP_TEST_ZONE'), Text::_('COM_LANG4DEV_DEVELOP_TEST_ZONE_DESCRIPTION'), 'devTest', 'devTestZone');
//
//$developer4Test_ZoneButtons = [];

//--- display .... -----------------------------

//function DisplayButton($button)
//{
//    global $imageClass;
//    $imageClass = 'fas fa-list';
//    $imageClass = 'fas fa-image';
//
//    // <button type="button" class="btn btn-primary">Primary</button>
//    ?>
<!--	<div class="rsg2-icon-button-container" style="border: #0a53be;">-->
<!---->
<!--		<button type="button" class="btn ">-->
<!---->
<!--			<a href="--><?php
//            echo $button->link; ?><!--" class="--><?php
//            echo $button->classButton; ?><!--">-->
<!--				<figure class="lang4dev-icon">-->
<!--                    --><?php
//                    foreach ($button->classIcons as $Idx => $imageClass) {
//                        echo '            <span class="' . $imageClass . ' icoMoon icoMoon0' . $Idx . '" style="font-size:30px;"></span>'; // style="font-size:30px;"
//                    }
//                    ?>
<!--					<figcaption class="rsg2-text">-->
<!--						<div class="maint-title"><strong>--><?php
//                                echo $button->textTitle; ?><!--</strong></div>-->
<!--						<div class="maint-text">--><?php
//                            echo $button->textInfo; ?><!--</div>-->
<!--					</figcaption>-->
<!--				</figure>-->
<!--			</a>-->
<!---->
<!--		</button>-->
<!--	</div>-->
<!--    --><?php
//}

function DisplayButton($button)
{

	echo '<div class="rsg2-icon-button-container">';

	/** 01 */
	echo '<a href="' . $button->link . '" class="' . $button->classButton . '">';
	echo '    <figure class="rsg2-icon">';
	foreach ($button->classIcons as $Idx => $imageClass )
	{
		echo '            <span class="' . $imageClass . ' icoMoon icoMoon0' . $Idx . '" style="font-size:30px;"></span>'; // style="font-size:30px;"
	}
	echo '        <figcaption class="rsg2-text">';
	echo '            <div class="maint-title">' . $button->textTitle  . '</div>';
	echo '            <div class="maint-text">' . $button->textInfo  . '</div>';
	echo '        </figcaption>';
	echo '    </figure>';
	echo '</a>';
	/**/

	/** 02 *
	echo '    <div class="flex-buttons-table">';
	echo '        <li class="quickicon quickicon-single col mb-3">';
	echo '            <a href="' . $button->link . '">';
	echo '                <div class="quickicon-icon d-flex align-items-end big">';
	foreach ($button->classIcons as $Idx => $imageClass )
	{
	echo '            <span class="' . $imageClass . ' iconMoon0' . $Idx . '" ></span>'; // style="font-size:30px;"
	}
	echo '                </div>';
	echo '                <div class="quickicon-text d-flex align-items-center">';
	echo '                    <span class="j-links-link">' . $button->textTitle  . '</span>';
	echo '            <span class="maint-text">' . $button->textInfo  . '</span>';
	echo '                </div>';
	echo '            </a>';
	echo '        </li>';
	echo '    </div>';
	/**/

	echo '</div>'; // rsg2-icon-button-container
}

//---  -----------------------------

function DisplayZone($Zone, $Buttons) {
	echo '<div class="icons-panel ' . $Zone->classContainer . '">';

	echo zoneTitle ($Zone->textTitle, $Zone->classTitle);
	echo zoneInfo ($Zone->textInfo);

	echo '<div class="rsg2-icon-bar">';

	foreach ($Buttons as $Button) {

		DisplayButton($Button);
	}

	echo '</div>';
	echo '</div>';
}





//---  -----------------------------

function zoneTitle ($title='Unknown title', $zoneClass='')
{
	$html[] = '<div class="icons-panel-title ' . $zoneClass . '">';
	//$html[] = '<h4>' . Text::_($title) . '</h4>';
	$html[] = '<header>' . Text::_($title) . '</header>';
	$html[] = '</div>';

	// implode($html);
	// implode(' ', $html);
	// implode('< /br>', $html);
	return implode($html);
}

//---  -----------------------------
function zoneInfo ($info='Unknown zone info')
{
	$html[] = '<div class="icons-panel-info ">';
	$html[] = '<strong>' . Text::_($info) . '</strong>';
	$html[] = '</div>';

	// implode($html);
	// implode(' ', $html);
	// implode('< /br>', $html);
	return implode($html);
}

?>
<form action="<?php echo Route::_('index.php?option=com_lang4dev&view=maintenance'); ?>"
      method="post" name="adminForm" id="item-form" class="form-validate"
      enctype="multipart/form-data">

    <div class="d-flex flex-row">
		<?php if (!empty($this->sidebar)) : ?>
            <div id="j-sidebar-container" class="">
				<?php echo $this->sidebar; ?>
            </div>
		<?php endif; ?>
        <!--div class="<?php echo (!empty($this->sidebar)) ? 'col-md-10' : 'col-md-12'; ?>"-->
        <div class="flex-fill">
            <div id="j-main-container" class="j-main-container">

                <div class="flex-main-row">

					<?php

					//---  -----------------------------

					// DisplayZone($RSG2_Zone, $RSG2_ZoneButtons);

					if ($this->isDevelop) {
						// DisplayZone($developer4Test_Zone, $developer4Test_ZoneButtons);
					}

					DisplayZone($rawDatabase_Zone, $rawDatabase_ZoneButtons);

					// DisplayZone($outdated_Zone, $outdated_ZoneButtons);

					// DisplayZone($repair_Zone, $repair_ZoneButtons);
					// DisplayZone($danger_Zone, $danger_ZoneButtons);

					if ($this->isDebugBackend) {
						DisplayZone($developer_Zone, $developer_ZoneButtons);
					}
					/**/

					?>
                </div>
            </div>
        </div>
    </div>


    <input type="hidden" name="task" value=""/>
    <?php
    echo HTMLHelper::_('form.token'); ?>
</form>


