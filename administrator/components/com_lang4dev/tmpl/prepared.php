<?php
// no direct access

/**
 * @package         LangMan4Dev
 * @subpackage      com_lang4dev
 * @author          Thomas Finnern <InsideTheMachine.de>
 * @copyright  (c)  2022-2025 Lang4dev Team
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

// HTMLHelper::_('bootstrap.framework');

HTMLHelper::_('stylesheet', 'com_lang4dev/backend/imagesProperties.css', array('version' => 'auto', 'relative' => true)
);
HTMLHelper::_('script', 'com_lang4dev/backend/imagesProperties.js', ['version' => 'auto', 'relative' => true]);

?>

<form action="<?php
echo Route::_('index.php?option=com_lang4dev&view=Maintenance&layout=Prepared'); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">
	<div class="d-flex flex-row">
        <?php
        if (!empty($this->sidebar)) : ?>
			<div id="j-sidebar-container" class="">
                <?php
                echo $this->sidebar; ?>
			</div>
        <?php
        endif; ?>
		<!--div class="<?php
        echo (!empty($this->sidebar)) ? 'col-md-10' : 'col-md-12'; ?>"-->
		<div class="flex-fill">
			<div id="j-main-container" class="j-main-container">

                <?php
                echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'PreparedButNotReady')); ?>

                <?php
                echo HTMLHelper::_(
                    'bootstrap.addTab',
                    'myTab',
                    'PreparedButNotReady',
                    Text::_('COM_LANG4DEV_MAINT_PREPARED_NOT_READY', true)
                ); ?>
				<p></p>
				<legend><strong><?php
                        echo Text::_('COM_LANG4DEV_MAINT_PREPARED_NOT_READY_DESC'); ?></strong></legend>
				<p>
				<h3><?php
                    echo Text::_('COM_LANG4DEV_MANIFEST_INFO_VIEW'); ?></h3></p>

                <?php

                try {
                } catch (RuntimeException $e) {
                    $OutTxt = '';
                    $OutTxt .= 'Error rawEdit view: "' . 'PreparedButNotReady' . '"<br>';
                    $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                    $app = Factory::getApplication();
                    $app->enqueueMessage($OutTxt, 'error');
                }

                ?>

                <?php
                echo HTMLHelper::_('bootstrap.endTab'); ?>

                <?php
                echo HTMLHelper::_('bootstrap.endTabSet'); ?>

				<!--input type="hidden" name="option" value="com_lang4dev" />
				<input type="hidden" name="rsgOption" value="maintenance" /-->

				<input type="hidden" name="task" value=""/>
                <?php
                echo HTMLHelper::_('form.token'); ?>
			</div>
		</div>
	</div>

    <?php
    echo HTMLHelper::_('form.token'); ?>
</form>


