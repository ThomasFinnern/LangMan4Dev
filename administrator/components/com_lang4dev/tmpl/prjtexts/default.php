<?php

\defined('_JEXEC') or die;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

function renderProjectSelection ($form)
{
	?>
	<div class="d-flex flex-row py-0 my-0">
		<div class="mx-2 py-0 border border-primary">
			<?php echo $form->renderField('selectProject'); ?>
		</div>

		<div class="mx-2 py-0 border border-success">
			<?php echo $form->renderField('selectSubproject'); ?>
		</div>

	</div>
	<?php

	return;
}


function renderMissingPreparedTransIds ($missing, $comment = '')
{
	?>

    <div class="col">
        <div class="d-inline-flex position-relative">
			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
			    <?php echo count ($missing); ?>
			    <span class="visually-hidden">Count missing</span>
			</span>
			</span>
	        <h3><?php echo Text::_('COM_LANG4DEV_MISSING_TRANSLATION_IDS'); ?>&nbsp;&nbsp;&nbsp;</h3>
        </div>
        <br>

	<?php

	if (count ($missing) > 0)
	{
		?>
		<div class="card bg-light border">
			<h3 class="card-header bg-white" >
				<?php echo Text::_('COM_LANG4DEV_MISSING_TRANS_IDS_PREPARED'); ?>
				<!--span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light"-->
				<span class="badge rounded-pill  bg-danger border border-light"
						style="position: relative; top: -12px; left: +5px; ">
				    <?php echo count ($missing); ?>
				    <span class="visually-hidden">Count missing</span>
				</span>
			</h3>
            <a class="btn btn-sm"  style="color: black; background-color: #ced4da;" data-bs-toggle="collapse"
                href="#collapseMissing" role="button" aria-expanded="false" aria-controls="collapseMissing">
                <?php echo Text::_('COM_LANG4DEV_TOGGLE_MISSING_IDS'); ?>
            </a>
            <div class="collapse show" id="collapseMissing">
                <br>

			<div class="card-body">
				<!-- h5 class="card-title"></h5-->
				<p class="card-text">
					<?php
					foreach ($missing as $transId)
					{
						echo $comment . $transId . '=""<br>';
					}
					?>
				</p>
			</div>

            </div>

		</div>
		<br>

		<?php
	}

	return;
}

function renderDeveloperAdHocTexts ($transStringsLocations, $comment = '')
{
	$locationsCount = count ($transStringsLocations);
	if ($locationsCount > 0)
	{
		?>
		<div class="card bg-light border">
			<h3 class="card-header bg-white" >
				<?php echo Text::_('COM_LANG4DEV_DEVELOPER_AD_HOC_TEXTS'); ?>
				<!--span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light"-->
				<span class="badge rounded-pill  bg-danger border border-light"
				      style="position: relative; top: -12px; left: +5px; ">
				    <?php echo $locationsCount; ?>
				    <span class="visually-hidden">Count missing</span>
				</span>
			</h3>
			<div class="card-body">
				<!-- h5 class="card-title"></h5-->
				<p class="card-text">
					<?php
					foreach ($transStringsLocations as $transIds)
					{
						foreach ($transIds as $transId)
						{
							/**
							echo '# ' . $transId->file
									. ' [L'. $transId->lineNr . 'C' . $transId->colIdx . '] in '
								. ' (' . $transId->path . ')<br>';
							echo $comment . $transId->name . '="' . $transId->string . '"<br>';
							/**/

							/**/


							echo $comment . $transId->name . '="' . $transId->string . '"'
								. ' ;' .  $transId->file
								. ' [L'. $transId->lineNr . 'C' . $transId->colIdx . '] 
								. <br>';
							/**/
						}
					}

					?>
				</p>
			</div>
		</div>
		<br>
		<br>

		<?php
	}

	return;
}

function renderSubProjectStatistic ($missing, $same, $notUsed, $doubles, $comment = '') {

?>
    <div class="row g-3">

        <div class="col">
	        <div class="d-inline-flex position-relative">
				<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
				    <?php echo count ($missing); ?>
				    <span class="visually-hidden">Count missing</span>
				</span>
				</span>
		        <h3><?php echo Text::_('COM_LANG4DEV_MISSING_TRANSLATION_IDS'); ?>&nbsp;&nbsp;&nbsp;</h3>
	        </div>
	        <br>

	        <?php
            if (count ($missing) > 0)
            {
		        // hide with button
		        ?>
		        <a class="btn btn-sm"  style="color: black; background-color: #ced4da;" data-bs-toggle="collapse"
		           href="#collapseMissing" role="button" aria-expanded="false" aria-controls="collapseMissing">
			        <?php echo Text::_('COM_LANG4DEV_TOGGLE_MISSING_IDS'); ?>
		        </a>
		        <div class="collapse show" id="collapseMissing">
			        <br>
			        <?php
//		            $newItemLines = implode("<br>", $missing);
//		            echo $newItemLines;
			        foreach ($missing as $transId)
			        {
				        echo $comment . $transId . '=""<br>';
			        }

			        ?>
		        </div>
	            <?php
            } else {
            	echo '<strong>%</strong>';
            }
            ?>
        </div>

	    <div class="col">
		    <div class="d-inline-flex position-relative">
				<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
					<?php echo count ($notUsed); ?>
					<span class="visually-hidden">count same</span>
				</span>
			    <h3><?php echo Text::_('COM_LANG4DEV_SURPLUS_TRANSLATIONS'); ?>&nbsp;&nbsp;&nbsp;</h3>
		    </div>
		    <br>

		    <?php
		    if (count ($notUsed) > 0)
		    {
			    // hide with button
			    ?>
			    <a class="btn btn-sm"  style="color: black; background-color: #ced4da;" data-bs-toggle="collapse"
			       href="#collapseNotUsed" role="button" aria-expanded="false" aria-controls="collapseNotUsed">
				    <?php echo Text::_('COM_LANG4DEV_TOGGLE_NOT_USED_IDS'); ?>
			    </a>
			    <div class="collapse" id="collapseNotUsed">
				    <br>
				    <?php
				    $newItemLines = implode("<br>", $notUsed);
				    echo $newItemLines;
				    ?>
			    </div>
			    <?php
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
		        <h3><?php echo Text::_('COM_LANG4DEV_MATCHING_TRANSLATION_IDS'); ?>&nbsp;&nbsp;&nbsp;</h3>
	        </div>
	        <br>

            <?php
            if (count ($same) > 0)
            {
	            // hide with button
				?>
		        <a class="btn btn-sm"  style="color: black; background-color: #ced4da;" data-bs-toggle="collapse" 
		            href="#collapseSame" role="button" aria-expanded="false" aria-controls="collapseSame">
			        <?php echo Text::_('COM_LANG4DEV_TOGGLE_MATCHING_IDS'); ?>
		        </a>
	            <div class="collapse" id="collapseSame">
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

$comment = '';
if ($this->isDoCommentIds) {
	$comment = ';';
}

?>
<form action="<?php echo Route::_('index.php?option=com_lang4dev&view=prjtexts'); ?>"
      method="post" name="adminForm" id="adminForm">

	<?php

	// ToDo: tell main lang and info

	renderProjectSelection($this->form);

	$idx = 1;

	$subProjects = $this->project->subProjects;

	if(count ($subProjects) > 0)
	{
		foreach ($subProjects as $subProject) {

			$prjIdAndType = $subProject->getPrjIdAndTypeText ();

			//$transStringsLocations = $subProject->filteredTransStringsLocations();
			$transStringsLocations = $subProject->getTransStringsLocations();

			// style="width: 18rem; bg-light .bg-transparent bg-secondary text-white

			?>
			<div class="card ">
				<h2 class="card-header " style="background-color: #ced4da;">
					<?php echo $prjIdAndType; ?>
				</h2>
				<div class="card-body">
				    <!-- h5 class="card-title"></h5-->
					<p class="card-text">
					<?php

					// ['missing', same, notUsed, doubles']
					$transIdsClassified = $this->transIdsClassified[$prjIdAndType];

					// ToDo: interface parameters
					$missing = $transIdsClassified['missing'];
					$same    = $transIdsClassified['same'];
					$notUsed = $transIdsClassified['notUsed'];
					$doubles  = $transIdsClassified['doubles'];

					// renderMissingPreparedTransIds ($missing, $comment);

					renderDeveloperAdHocTexts ($transStringsLocations, $comment);

					// ToDo: Use constants ?
					renderSubProjectStatistic ($missing, $same, $notUsed, $doubles, $comment);

					?>
					</p>
				</div>
			</div>
			<?php

		}
	} else
	{

		// ToDo: use bootstrap card
		echo '<br>';
		echo '<h2>' . Text::_('COM_LANG4DEV_NO_SUB_PROJECTS_DEFINED_FOR_PROJECT') . '</h2>';
		echo ' ' . Text::_('COM_LANG4DEV_NO_SUB_PROJECTS_DEFINED_FOR_PROJECT_DESC') . ' ';
	}
	?>


	<input type="hidden" name="task" value="" />
    <?php echo HTMLHelper::_('form.token'); ?>
</form>


