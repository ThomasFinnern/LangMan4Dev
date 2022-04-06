<?php

\defined('_JEXEC') or die;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;


function renderSubProjectMissing ($missing)
{
	if (count ($missing) > 0)
	{



	}
}

function renderSubProjectStatistic ($missing, $same, $notUsed, $doubles) {

?>
	<hr>
    <div class="row g-4">
        <div class="col">
	        <h3>Missing Translation IDs <?php echo ' (' . count ($missing) . ')'; ?></h3><br>
            <?php
            if (count ($missing) > 0)
            {
	            $newItemLines = implode("<br>", $missing);
	            echo $newItemLines;
            } else {
            	echo '<strong>%</strong>';
            }
            ?>
        </div>
        <div class="col">
            <h3>Same Lang Ids<?php echo ' (' . count ($same) . ')'; ?></h3><br>
            <?php
            if (count ($same) > 0)
            {
//	            // ToDo: hide with button
//	            $newItemLines = implode("<br>", $same);
//	            echo $newItemLines;
            } else {
	            echo '<strong>???</strong>';
            }
            ?>
        </div>
        <div class="col">
            <h3>Not Used Lang Ids<?php echo ' (' . count ($notUsed) . ')'; ?></h3><br>
            <?php
            if (count ($notUsed) > 0)
            {
	            $newItemLines = implode("<br>", $notUsed);
	            echo $newItemLines;
            } else {
	            echo '<strong>%</strong>';
            }
            ?>
        </div>
		<?php if (! empty ($doubles)): ?>
<!--        <div class="col">-->
<!--            <h3>Double Translation Ids<?php echo ' (' . count ($missing) . ')'; ?></h3><br>-->
<!--            --><?php
////            $newItemLines = implode("<br>", $transIdsClassified['double']);
////            echo $newItemLines;
//            ?>
<!--        </div>-->
		<?php endif; ?>
    </div>

<?PHP

}


$prjFiles  = $this->prjFiles; // ToDo: remove
$langFile     = $this->prjFiles->getLangFile('en-GB');  // ToDo: remove
$translations = $langFile->translations;
$transIdLocations = $prjFiles->getTransIdLocations();
$transIdsClassified = $prjFiles->getTransIdsClassified();

?>
<form action="<?php echo Route::_('index.php?option=com_lang4dev&view=prjtexts'); ?>" method="post" name="adminForm" id="item-form" class="form-validate">


	<?php
	$idx = 1;

	$subProjects = $this->project->subProjects;

	foreach ($subProjects as $subProject) {

		$transIdsClassified = $subProject->getTransIdsClassified();

		// ToDo: interface parameters
		$missing = $transIdsClassified['missing'];
		$same    = $transIdsClassified['same'];
		$notUsed = $transIdsClassified['notUsed'];
		$doubles  = $transIdsClassified['doubles'];

		// ToDo: Use constants ?
		renderSubProjectStatistic ($missing, $same, $notUsed, $doubles);
		renderSubProjectMissing ($missing);

	}

	?>


	<hr>
	<h3>COM_LANG4DEV_TRANSLATIONS</h3><br>
	<?php
		$newItemLines = implode("<br>", $prjFiles->__toText());

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
		// remove : $transIdLocation may have index as name -> [multiple locations]
		?>
		<?php foreach ($translations as $i => $item) : ?>
			<tr>
				<td><?php echo $item->lineNr; ?></td>
				<td><?php echo $item->transId; ?></td>
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
		// remove : $transIdLocation may have index as name -> [multiple locations]
		//$prjSysFiles = $this->prjFiles;

		?>
		<?php
		$idx = 1;
		foreach ($transIdLocations as $transIdLocation) : ?>
			<?php foreach ($transIdLocation as $item) : ?>
				<tr>
					<td><?php echo $idx; ?></td>

					<td><?php echo $item->name; ?></td>
					<td><?php echo $item->lineNr; ?></td>
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


