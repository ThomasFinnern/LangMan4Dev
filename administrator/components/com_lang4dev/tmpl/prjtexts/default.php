<?php

\defined('_JEXEC') or die;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

?>
<form action="<?php echo Route::_('index.php?option=com_lang4dev&view=prjtexts'); ?>" method="post" name="adminForm" id="item-form" class="form-validate">

    <?php
    echo 'default.php: ' . realpath(dirname(__FILE__));
    echo '<br><br><hr>';
	echo '<h3>names</h3><br>';
    //$names = $this->prjLangItems->getItemNames ();
    echo $this->prjLangItems->_toTextNames('\n');
    echo $this->prjLangItems->_toTextNames('<br>');
    ?>
	<hr>




    <input type="hidden" name="task" value="" />
    <?php echo HTMLHelper::_('form.token'); ?>
</form>


