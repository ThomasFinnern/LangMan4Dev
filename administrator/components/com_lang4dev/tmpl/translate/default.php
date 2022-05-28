<?php

\defined('_JEXEC') or die;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

function renderLangIdTexts ($form)
{
    ?>
    <div class="d-flex flex-row py-0 my-0">
        <div class="mx-2 py-0 border border-primary">
                <?php echo $form->renderField('selectSourceLangId'); ?>
        </div>

	    <div class="mx-2 py-0 border border-success">
                <?php echo $form->renderField('selectTargetLangId'); ?>
        </div>

	    <div class="mx-2 py-0 border border-warning">
                <?php echo $form->renderField('createLangId'); ?>
        </div>
    </div>
    <?php

    return;
}

function renderCheckAll ($form)
{
    ?>
    <div class="d-flex flex-row">
        <div class="mx-2 p-2">

	        <input class="form-check-input" id="checkall-toggle" type="checkbox" name="checkall-toggle" value=""
	               title="<?php echo Text::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)">
	        <label for="checkall-toggle"><?php echo Text::_('COM_LANG4DEV_CHECK_ALL_LANG_FILE_EDITS'); ?></label>
	        <br>

        </div>
    </div>
    <?php

    return;
}


function renderCheckLangEdited ($subPrjId, $idx, $checked=false)
{
	?>
	<div class="d-flex flex-row">
		<div class="py-2">

			<?php echo HTMLHelper::_('grid.id', $idx, $subPrjId, false, 'cid', 'cb', $subPrjId); ?>

		<!--input class="form-check-input cache-entry" type="checkbox"
		       id="cb<?php echo $idx; ?>" name="cid[]" value="<?php echo $subPrjId; ?>"-->
		<label class="form-check-label" for="cb<?php echo $idx; ?>">
			<?php echo Text::_('COM_LANG4DEV_CHECK_FOR_SAVE_OF_EDIT_FILE'); ?>
		</label>

		</div>
	</div>
	<?php

	return;
}



function renderLangTransFile ($langId, $langFile, $isMain=false, $editIdx=0){

	?>
	<div class="card bg-light border">
		<h3 class="card-header bg-white" >
			<?php echo $langId; ?>
		</h3>

		<div class="card-body">

			<div class="card-text">

				<?php
				if( ! $isMain)
				{
					$subPrjId = $langId;
					renderCheckLangEdited($subPrjId, $editIdx, $checked = false);
				}

				$linesArray = $langFile->translationLinesArray();
				$langText = '';

				// ksort($linesArray);
				foreach($linesArray as $line) {

					$langText .= $line . '&#10;';
				}


				// source text
				if($isMain) {
					?>

			        <textarea id="translations" name="<?php echo $langId; ?>" rows="12" cols="120"
					<textarea id="<?php echo $langId . '_' . $editIdx. '_main'; ?>"
					          name="langsEdited[]" rows="12" cols="120"
			                  style="overflow-x: scroll; " class="bg-warning" readonly
			        ><?php echo $langText; ?></textarea>

					<?php
				}  else {
					?>
					// target edit text
					<textarea id="<?php echo $langId . '_' . $editIdx. '_target'; ?>"
					          name="langEdited[]" rows="12" cols="120"
					          style="overflow-x: scroll; "
					><?php echo $langText; ?></textarea>
					<input type="text" name="langPathFileNames[]" value="<?php echo $langFile->langPathFileName; ?>" />

					<?php
				}
				?>
			</div>
		</div>
	</div>






	<?php

	return;
}



?>
<form action="<?php echo Route::_('index.php?option=com_lang4dev&view=translate'); ?>" method="post" name="adminForm" id="item-form" class="form-validate">

    <?php
//    echo 'default.php: ' . realpath(dirname(__FILE__));
    ?>
    <?php renderLangIdTexts ($this->form); ?>

    <?php renderCheckAll ($this->form); ?>

	<?php

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
			    <?php

			    $editIdx =0;

			    // first show main lang
				foreach ($subProject->getLangIds () as $langId) {
					
					if ($langId == $this->main_langId) {
						
						$langFile = $subProject->getLangFile($langId);
						renderLangTransFile ($langId, $langFile, true, $editIdx);
						$editIdx++;
					}
				}
				
				// second show translation  lang 
				foreach ($subProject->getLangIds () as $langId) {
					
					if ($langId == $this->trans_langId || $this->isShowTranslationOfAllIds) {
						
						$langFile = $subProject->getLangFile($langId);
						renderLangTransFile ($langId, $langFile, false, $editIdx);
						$editIdx++;
					}
				}
			    ?>
		    </div>

	    </div>
	    <?php

	}

	?>


    <hr>

    <input type="hidden" name="task" value="" />
    <?php echo HTMLHelper::_('form.token'); ?>
</form>


