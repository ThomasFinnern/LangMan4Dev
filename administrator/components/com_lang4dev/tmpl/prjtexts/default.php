<?php

\defined('_JEXEC') or die;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;


function renderSubProjectMissing ($missing)
{
	if (count ($missing) > 0)
	{
		?>
		<div class="card bg-light border">
			<h3 class="card-header bg-white" >
				<?php echo "Prepared Missing Ids"; ?>
			</h3>
			<div class="card-body">
				<!-- h5 class="card-title"></h5-->
				<p class="card-text">
					<?php

					foreach ($missing as $TransId)
					{
						echo $TransId . '=""<br>';
					}

					?>
				</p>
			</div>
		</div>
		<?php
	}


}

function renderSubProjectStatistic ($missing, $same, $notUsed, $doubles) {

?>
    <div class="row g-4">
        <div class="col">
	        <div class="d-inline-flex position-relative">
				<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
				    <?php echo count ($missing); ?>
				    <span class="visually-hidden">Count missing</span>
				</span>
		        <h3>Missing Translation IDs&nbsp;&nbsp;&nbsp;</h3>
	        </div>
	        <br>

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
	        <div class="d-inline-flex position-relative">
				<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
					<?php echo count ($same); ?>
					<span class="visually-hidden">count same</span>
				</span>
                <h3>Same Lang Ids&nbsp;&nbsp;&nbsp;</h3>
	        </div>
	        <br>

            <?php
            if (count ($same) > 0)
            {
	            // ToDo: hide with button
				?>
		        <a class="btn "  style="color: black; background-color: #ced4da;" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
			        <?php echo Text::_('COM_LANG4DEV_SHOW_SAME'); ?>
		        </a>
	            <div class="collapse" id="collapseExample">
		            <br>
		            <?php
		            $newItemLines = implode("<br>", $same);
		            echo $newItemLines;
		            ?>
	            </div>
	        <?php
            } else {
	            echo '<strong>???</strong>';
            }
            ?>
        </div>
        <div class="col">
	        <div class="d-inline-flex position-relative">
				<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
					<?php echo count ($notUsed); ?>
					<span class="visually-hidden">count same</span>
				</span>
		        <h3>Not Used Lang Ids&nbsp;&nbsp;&nbsp;</h3>
	        </div>
	        <br>

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

		$title = $subProject->prjId . ': ' . $subProject->getPrjTypeText();
// style="width: 18rem; bg-light .bg-transparent bg-secondary text-white

		?>
		<div class="card ">
			<h2 class="card-header " style="background-color: #ced4da;">
				<?php echo $title; ?>
			</h2>
			<div class="card-body">
			    <!-- h5 class="card-title"></h5-->
				<p class="card-text">
				<?php
				$transIdsClassified = $subProject->getTransIdsClassified();

				// ToDo: interface parameters
				$missing = $transIdsClassified['missing'];
				$same    = $transIdsClassified['same'];
				$notUsed = $transIdsClassified['notUsed'];
				$doubles  = $transIdsClassified['doubles'];

				// ToDo: Use constants ?
				renderSubProjectStatistic ($missing, $same, $notUsed, $doubles);
				renderSubProjectMissing ($missing);
				?>
				</p>
			</div>
		</div>
		<?php

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


