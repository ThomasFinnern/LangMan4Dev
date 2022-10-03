<?php
/**
 * @package       Joomla.Administrator
 * @subpackage    com_lang4dev
 *
 * @copyright (C) 2022-2022 Lang4dev Team
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

HTMLHelper::_('stylesheet', 'com_lang4dev/backend/controlPanel.css', array('version' => 'auto', 'relative' => true));
// ToDo Use below instead
//HTMLHelper::_('stylesheet', 'com_lang4dev/backend/maintenance.css', array('version' => 'auto', 'relative' => true));

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
    Route::_('index.php?option=com_lang4dev&view=subprojects'),
    Text::_('COM_LANG4DEV_SUB_PROJECTS'),
    Text::_('COM_LANG4DEV_SUB_PROJECTS_DESC') . '                        ',
    array('icon-moon', 'icon-edit'),
    'viewProjects'
);

function DisplayButton($button)
{
    global $imageClass;
    $imageClass = 'fas fa-list';
    $imageClass = 'fas fa-image';

    // <button type="button" class="btn btn-primary">Primary</button>
    ?>
	<div class="rsg2-icon-button-container" style="border: #0a53be;">
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

function DisplayControlButtons($cmdButtons)
{
    foreach ($cmdButtons as $Button) {
        DisplayButton($Button);
    }
}

?>
<form action="<?php
echo Route::_('index.php?option=com_lang4dev&view=maintenance'); ?>" method="post" name="adminForm"
      id="item-form" class="form-validate">

    <?php
    echo 'default.php: ' . realpath(dirname(__FILE__));
    ?>

	<div class="main-vertical-stack" style="display: flex; flex-direction: column; justify-content: space-between">
		<div class="vertical-header">
			<h2><?php
                echo Text::_('COM_LANG4DEV_MAINTENANCE'); ?></h2>
			<strong><?php
                echo Text::_('COM_LANG4DEV_MAINTENANCE_DESC'); ?></strong>
		</div>
		<div class="horizontal-buttons" style="display: flex; flex-direction: row; align-content: space-between; ">
            <?php
            //--- Control buttons ------------------
            DisplayControlButtons($cmdButtons);
            ?>
		</div>
		<div class="vertical-empty-part3" style="min-height: 20px;">
		</div>
	</div>
	</div>
	<hr>

	<h2>Lang4Dev</h2>

	<div style="color:red;">ToDo: select a project input</div>
	<div style="color:red;">ToDo:</div>

	<hr>


	<input type="hidden" name="task" value=""/>
    <?php
    echo HTMLHelper::_('form.token'); ?>
</form>


