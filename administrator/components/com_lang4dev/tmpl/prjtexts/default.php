<?php

\defined('_JEXEC') or die;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
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
	<h3>Missing Lang Ids SYS</h3><br>
	<?php
	$newItemLines = implode("<br>", $this->sysLangIds['missing']);

	echo $newItemLines;
	?>
	<hr>
	<h3>Same Lang Ids SYS</h3><br>
	<?php
	$newItemLines = implode("<br>", $this->sysLangIds['same']);

	echo $newItemLines;
	?>
	<hr>
	<h3>Not Used Lang Ids SYS</h3><br>
	<?php
	$newItemLines = implode("<br>", $this->sysLangIds['notUsed']);

	echo $newItemLines;
	?>
	<hr>
	<h3>Temp Translation lines </h3><br>
	<?php
//		$linesArray = $this->testLangFile->translationLinesArray();
//		$fileLines = implode("<br>", $linesArray);
//
//		echo $fileLines;
    ?>
	<hr>
	<h3>Missing Translation IDs</h3><br>
	<?php
//		$newItemLines = implode("<br>", $this->transIds_new);
//
//		echo $newItemLines;
    ?>
	<hr>
	<h3>COM_LANG4DEV_TRANSLATIONS</h3><br>
	<?php
		$newItemLines = implode("<br>", $this->langFileNamesSetText);

		echo $newItemLines;
    ?>
	<hr>
<!-- ToDo: header -->
	<table>
		<tr>
			<th><?php echo Text::_('COM_LANG4DEV_LINE_NR') ?></th>
			<th><?php echo Text::_('COM_LANG4DEV_NAME') ?></th>
			<th><?php echo Text::_('COM_LANG4DEV_TRANSLATION') ?></th>
			<th><?php echo Text::_('COM_LANG4DEV_COMMENT_LINES_BEFORE') ?></th>
			<th><?php echo Text::_('COM_LANG4DEV_COMMENT_BEHIND') ?></th>
		</tr>
		<?php
		// remove : $langLocation may have index as name -> [multiple locations]
		$prjSysFiles  = $this->prjSysFiles;
		$langFile     = $this->prjSysFiles->retrieveLangFileTranslations('en-GB');
		$translations = $this->prjSysFiles->retrieveLangFileTranslations('en-GB')->translations;
		$this->prjSysFiles->retrieveLangFileTranslations('en-GB')->translations
		?>
		<?php foreach ($this->prjSysFiles->retrieveLangFileTranslations('en-GB')->translations as $i => $item) : ?>
			<tr>
				<td><?php echo $item->lineIdx; ?></td>
				<td><?php echo $item->name; ?></td>
				<td><?php echo $item->translationText; ?></td>
				<td><?php echo implode("<br>", $item->commentsBefore); ?></td>
				<td><?php echo $item->commentBehind; ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
<!-- ToDo: trailer -->

	<hr>
	<h3>COM_LANG4DEV_ID_LOCATIONS</h3><br>
	<table>
		<tr>
			<th><?php echo Text::_('COM_LANG4DEV_INDEX') ?></th>
			<th><?php echo Text::_('COM_LANG4DEV_NAME') ?></th>
			<th><?php echo Text::_('COM_LANG4DEV_LINE_NR') ?></th>
			<th><?php echo Text::_('COM_LANG4DEV_COLUMN') ?></th>
			<th><?php echo Text::_('COM_LANG4DEV_FILE_NAME') ?></th>
			<th><?php echo Text::_('COM_LANG4DEV_FILE_PATH') ?></th>
		</tr>
		<?php
		// remove : $langLocation may have index as name -> [multiple locations]
		$prjSysFiles = $this->prjSysFiles;
		$langLocations = $this->prjSysFiles->langLocations;

		?>
		<?php
		$idx = 1;
		foreach ($this->prjSysFiles->langLocations as $langLocation) : ?>
			<?php foreach ($langLocation as $item) : ?>
				<tr>
					<td><?php echo $idx; ?></td>

					<td><?php echo $item->name; ?></td>
					<td><?php echo $item->lineIdx; ?></td>
					<td><?php echo $item->colIdx; ?></td>
					<td><?php echo $item->file; ?></td>
					<td><?php echo $item->path; ?></td>
				</tr>
			<?php endforeach; ?>

			<?php $idx++; ?>
		<?php endforeach; ?>

	</table>



	<input type="hidden" name="task" value="" />
    <?php echo HTMLHelper::_('form.token'); ?>
</form>


