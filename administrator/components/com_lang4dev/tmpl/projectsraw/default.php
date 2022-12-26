<?php

defined('_JEXEC') or die;

use Finnern\Component\Lang4dev\Administrator\Helper\langPathFileName;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;


function renderProjectSelection($form)
{
    ?>
	<br>
	<div class="d-flex flex-row py-0 my-0 justify-content-between">
		<div class="mx-2 py-0 flex-fill ">
            <?php
            echo $form->renderField('selectProject'); ?>
		</div>
	</div>
    <?php

    return;
}


?>
<form action="<?php echo Route::_('index.php?option=com_lang4dev&view=projectsraw'); ?>"
      method="post" name="adminForm" id="adminForm">

    <?php

    // ToDo: tell main lang and info

    renderProjectSelection($this->form);

    ?>
    <HR>

	<?php

    //echo '<br>';
    echo '<h2>' . Text::_('COM_LANG4DEV_PROJECT_RAW') . '</h2>';
	//echo '<pre><code>';
    echo '<div class="bg-white">';
	echo '<pre>';
    //echo '<code>';
	echo json_encode($this->project, JSON_PRETTY_PRINT);
	//echo '</code></pre>';
	echo '</pre>';
	//echo '</code>';
	echo '</div>';
	//echo '<br>';

    ?>

    <input type="hidden" name="task" value=""/>
    <?php
    echo HTMLHelper::_('form.token'); ?>
</form>


