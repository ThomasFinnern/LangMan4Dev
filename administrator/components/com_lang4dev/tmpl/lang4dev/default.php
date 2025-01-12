<?php
/**
 * @package       Joomla.Administrator
 * @subpackage    com_lang4dev
 *
 * @copyright  (c)  2022-2024 Lang4dev Team
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

//Text::script('COM_LANG4DEV_PLEASE_CHOOSE_A_GALLERY_FIRST', true);

//HTMLHelper::_('stylesheet', 'com_lang4dev/backend/controlPanel.css', array('version' => 'auto', 'relative' => true));
$this->document->getWebAssetManager()->useStyle('com_lang4dev.backend.controlPanel');

// command buttons
class cmdButton
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

$cmdButtons = [];

// projects
$cmdButtons[] = new cmdButton(
    Route::_('index.php?option=com_lang4dev&view=projects'),
    Text::_('COM_LANG4DEV_PROJECTS'),
    Text::_('COM_LANG4DEV_PROJECTS_DESC') . '                        ',
    array('fas fa-tasks', 'icon-flag'),
//    array('icon-equalizer', 'icon-edit'),
    'viewProjects'
);

// project texts
$cmdButtons[] = new cmdButton(
    Route::_('index.php?option=com_lang4dev&view=prjtexts'),
    Text::_('COM_LANG4DEV_PRJ_TEXTS'),
    Text::_('COM_LANG4DEV_PRJ_TEXTS_DESC') . '                        ',
//    array('icon-forward', 'icon-list'),
    array('icon-code', 'icon-list'),
    'viewPrjTexts'
);

// translate
$cmdButtons[] = new cmdButton(
    Route::_('index.php?option=com_lang4dev&view=translate'),
    Text::_('COM_LANG4DEV_TRANSLATE'),
    Text::_('COM_LANG4DEV_TRANSLATE_DESC') . '                        ',
    array('icon-forward', 'icon-flag'),
    'viewTranslate'
);

// translations
$cmdButtons[] = new cmdButton(
    Route::_('index.php?option=com_lang4dev&view=maintenance'),
    Text::_('COM_LANG4DEV_MAINTENANCE'),
    Text::_('COM_LANG4DEV_MAINTENANCE_DESC') . '                        ',
    array('icon-cog', 'icon-equalizer'),
    'viewTranslations'
);

/** ToDo: all in one joomla (german) translation */
// translations
$cmdButtons[] = new cmdButton(
	Route::_('index.php?option=com_lang4dev&view=translations'),
	Text::_('COM_LANG4DEV_TRANSLATIONS'),
	Text::_('COM_LANG4DEV_TRANSLATIONS_DESC') . '                        ',
	array('icon-flag', 'icon-book'),
	'viewTranslations'
);

?>

<form action="<?php echo Route::_('index.php?option=com_lang4dev'); ?>"
	      method="post" name="adminForm" id="adminForm" class="form-validate">


	<div class="main-horizontal-header">
		<?php
        //--- header: logo description ------------------
	    DisplayLogo();
	    DisplayDescr_123($this->extensionVersion);
        ?>
	</div>

	<div class="main-horizontal-bar">
		<div class="main-horizontal-row">

			<div class="horizontal-buttons">
                <?php
                //--- Control buttons ------------------
                DisplayControlButtons($cmdButtons);
                ?>
			</div>
		</div>
	</div>

	<div class="project_selection_container">

	    <?php renderProjectSelection($this->form); ?>
		<?php renderLangIdTexts($this->form); ?>
	</div>

	<input type="hidden" name="task" value=""/>
    <?php
    echo HTMLHelper::_('form.token'); ?>

</form>

<?php

//--- Logo -----------------------------

/**
 * Just displays the logo as svg
 *
 * @since __BUMP_VERSION__
 */
function DisplayLogo()
{
    echo '    <div class="lang4dev_logo img-fluid">';
//	             echo HTMLHelper::_('image', 'com_lang4dev/RSG2_logo.big.png', Text::_('COM_LANG4DEV_MAIN_LOGO_ALT_TEXT'), null, true);
    echo HTMLHelper::_(
        'image',
        'com_lang4dev/Lang4Dev_Logo.svg',
        Text::_('COM_LANG4DEV_MAIN_LOGO_ALT_TEXT'),
        null,
        true
    );
    echo '     </div>';
//	echo '<p class="test">';
//	echo '</p>

//    echo '<div class="clearfix"></div>';
}

//---  ------------------

function DisplayDescr_123($extensionVersion)
{
	?>
	<div class="header_title">
		<div class="header_title_header">
			<h2><?php echo Text::_('COM_LANG4DEV_LANG4DEV'); ?></h2>
			<strong><?php echo Text::_('COM_LANG4DEV_LANG4DEV_DESC'); ?></strong>
		</div>
		<div class="header_steps_text">
		    <div><span class="badge badge-pill bg-success">1</span> <?php echo Text::_('Define project'); ?></div>
		    <div><span class="badge badge-pill bg-success">2</span> <?php echo Text::_('Select project ->below'); ?></div>
		    <div><span class="badge badge-pill bg-success">3</span> <?php echo Text::_('Collect translation IDs'); ?></div>
		    <div><span class="badge badge-pill bg-success">4</span> <?php echo Text::_('Translate project'); ?></div>
		</div>
		<div class="version_text">Lang4Dev&nbsp;<?php
            echo 'V' . $extensionVersion; ?>
		</div>

	</div>
	<?php
}

function DisplayVersion($extensionVersion)
{
	?>

    <?php
}

function DisplayButton($button)
{
    global $imageClass;
    $imageClass = 'fas fa-list';
    $imageClass = 'fas fa-image';

    // <button type="button" class="btn btn-primary">Primary</button>
    ?>
	<div class="button-container">
		<button type="button" class="btn ">

			<a href="<?php
            echo $button->link; ?>" class="<?php
            echo $button->classButton; ?>">
				<figure class="lang4dev-icon">
                    <?php
                    foreach ($button->classIcons as $Idx => $imageClass) {
                        echo '            <span class="' . $imageClass . ' icoMoon icoMoon0' . $Idx . '" style="font-size:30px;"></span>'; // style="font-size:30px;"
                    }
                    ?>
					<figcaption class="rsg2-text">
						<div class="maint-title"><strong><?php
                                echo $button->textTitle; ?></strong></div>
						<div class="maint-text"><?php
                            echo $button->textInfo; ?></div>
					</figcaption>
				</figure>
			</a>

		</button>
	</div>
    <?php
}

function DisplayControlButtons($cmdButtons) : void
{
    foreach ($cmdButtons as $Button) {
        DisplayButton($Button);
    }
}

function renderProjectSelection($form) : void
{
    ?>
	<div class='project_selection'>
		<div class='project_selection_setting'>
            <?php echo $form->renderField('selectProject'); ?>
		</div>
		<div class='project_selection_setting'>
            <?php echo $form->renderField('selectSubproject'); ?>
		</div>
	</div>
    <?php

    return;
}

function renderLangIdTexts($form)
{
    // mx-2 py-0, mx-2 py-0 px-2
    ?>
	<div class='project_selection'>
		<div class='project_selection_setting'>
            <?php echo $form->renderField('selectSourceLangId'); ?>
		</div>
		<div class='project_selection_setting'>
            <?php echo $form->renderField('selectTargetLangId'); ?>
		</div>
	</div>
    <?php

    return;
}

