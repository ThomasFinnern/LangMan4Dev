<?php

\defined('_JEXEC') or die;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;



function renderLangIdTexts ($form)
{
    ?>
    <div class="row g-4">
        <div class="col">
            <div class="d-inline-flex position-relative">

                <?php echo $form->renderField('selectSourceLangId'); ?>

            </div>
        </div>

        <div class="col">
            <div class="d-inline-flex position-relative">

                <?php echo $form->renderField('selectTargetLangId'); ?>

            </div>
        </div>

        <div class="col">
            <div class="d-inline-flex position-relative">

                <?php echo $form->renderField('createLangId'); ?>

            </div>
        </div>



    </div>
    <?php

    return;
}


function renderLangTransFile ($langId, $langFile, $isMain=false){

	?>
	<div class="card bg-light border">
		<h3 class="card-header bg-white" >
			<?php echo $langId; ?>
		</h3>

		<div class="card-body">

			<div class="card-text">

				<?php
				$linesArray = $langFile->translationLinesArray();
				$langText = '';
				// ksort($linesArray);
				foreach($linesArray as $line) {

					$langText .= $line . '&#10;';
				}
				?>

		        <textarea id="w3review" name="<?php echo $langId; ?>" rows="12" cols="120"
					<?php
					if($isMain)
					{
						echo 'class="bg-warning" readonly';
					}
					?>
		        ><?php echo $langText; ?></textarea>
			</div>
		</div>
	</div>






	<?php

	return;
}



?>
<form action="<?php echo Route::_('index.php?option=com_lang4dev&view=translate'); ?>" method="post" name="adminForm" id="item-form" class="form-validate">

    <?php
    echo 'default.php: ' . realpath(dirname(__FILE__));
    ?>
    <hr>
    <?php renderLangIdTexts ($this->form); ?>
    <hr>
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
			    <?php
				
				// first show main lang 
				foreach ($subProject->getLangIds () as $langId) {
					
					if ($langId == $this->main_langId) {
						
						$langFile = $subProject->getLangFile($langId);
						renderLangTransFile ($langId, $langFile, true);
					}
				}
				
				// second show translation  lang 
				foreach ($subProject->getLangIds () as $langId) {
					
					if ($langId == $this->trans_langId || $this->isShowTranslationOfAllIds) {
						
						$langFile = $subProject->getLangFile($langId);
						renderLangTransFile ($langId, $langFile, false);
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


