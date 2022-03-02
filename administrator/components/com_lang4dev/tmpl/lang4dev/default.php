<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_lang4dev
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Session\Session;

$button = new stdClass();
$button->link = Route::_('index.php?option=com_lang4dev2&task=test.dummySearch');
$button->classButton = 'dummyTask';
$button->textTitle = 'test search';
$button->textInfo = 'first search for own lnguage com_...';

$imageClass='image';

function DisplayButton($button)
{
	// <button type="button" class="btn btn-primary">Primary</button>
	?>
	<div class="rsg2-icon-button-container">;
		<button type="button" class="btn btn-warning">
		<a href="<?php echo $button->link; ?>" class="<?php echo $button->classButton; ?>">
			<figure class="rsg2-icon">
				<span class="<?php echo $imageClass; ?> icoMoon" style="font-size:30px;"></span>
				<figcaption class="rsg2-text">
	                <div class="maint-title"><strong><?php echo $button->textTitle; ?></strong></div>
	                <div class="maint-text"><?php echo $button->textInfo; ?></div>
	            </figcaption>
		    </figure>
		</a>
		</button>
	</div>
	<?php
}


?>

    <form action="<?php echo Route::_('index.php?option=com_lang4dev'); ?>"
          method="post" name="adminForm" id="adminForm" class="form-validate">

		<?php
        //--- Logo -----------------------------

        DisplayLogo();

        //--- Control buttons ------------------

        DisplayControlButtons();

        echo '<hr>';

		?>

		<h2>Lang4Dev</h2>



	    <?php DisplayButton($button); ?>

	    <input type="hidden" name="task" value="" />
	    <?php echo HTMLHelper::_('form.token'); ?>

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
    echo '    <div class="lang4dev_logo">';
//	             echo HTMLHelper::_('image', 'com_rsgallery2/RSG2_logo.big.png', Text::_('COM_RSGALLERY2_MAIN_LOGO_ALT_TEXT'), null, true);
    echo HTMLHelper::_('image', 'com_lang4dev/Lang4Dev_Logo.svg', Text::_('COM_LANG4DEV_MAIN_LOGO_ALT_TEXT'), null, true);
    echo '     </div>';
//	echo '<p class="test">';
//	echo '</p>

    echo '<div class="clearfix"></div>';
}

//--- Control buttons ------------------

/**
 * @param $buttons
 *
 *
 * @since __BUMP_VERSION__
 */
function DisplayControlButtons()
{
	echo '<h4>Buttons</h4>';
}

