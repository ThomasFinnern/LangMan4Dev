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

