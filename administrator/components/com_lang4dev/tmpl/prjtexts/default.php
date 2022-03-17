<?php

\defined('_JEXEC') or die;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

?>
<form action="<?php echo Route::_('index.php?option=com_lang4dev&view=prjtexts'); ?>" method="post" name="adminForm" id="item-form" class="form-validate">

    <?php
    echo 'default.php: ' . realpath(dirname(__FILE__));
    echo '<br><br><hr>';
	//echo '<h3>names</h3><br>';
    //$names = $this->prjLangLocations->getItemNames ();
    //echo $this->prjLangLocations->_toTextNames('\n');
    //echo $this->prjLangLocations->_toTextNames('<br>');
    ?>
	<hr>
	<h3>Temp Translation lines </h3><br>
	<?php
		$linesArray = $this->testLangFile->translationLinesArray();
		$fileLines = implode("<br>", $linesArray);

		echo $fileLines;
    ?>
	<hr>
	<h3>Missing Translation IDs</h3><br>
	<?php
		$newItemLines = implode("<br>", $this->transIds_new);

		echo $newItemLines;
    ?>
	<hr>
	<table>
		<tr>
			<th>COM_LANG4DEV_NAME</th>
			<th>COM_LANG4DEV_LINE</th>
			<th>COM_LANG4DEV_COLUMN</th>
			<th>COM_LANG4DEV_FILE</th>
			<th>COM_LANG4DEV_PATH</th>
		</tr>
		<?php foreach ($this->prjLangLocations->items as $i => $langLocation) : ?>
			<?php foreach ($langLocation as $item) : ?>
			<tr>
				<td><?php echo $item->name; ?></td>
				<td><?php echo $item->lineIdx; ?></td>
				<td><?php echo $item->colIdx; ?></td>
				<td><?php echo $item->file; ?></td>
				<td><?php echo $item->path; ?></td>
			</tr>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</table>



    <input type="hidden" name="task" value="" />
    <?php echo HTMLHelper::_('form.token'); ?>
</form>


