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
		        // ToDo: prepare data in htmlview
			    foreach ($subProject->getLangIds () as $langId) {
			        $langFile = $subProject->getLangFile($langId);
				?>
				    <div class="card bg-light border">
					    <h3 class="card-header bg-white" >
						    <?php echo $langId; ?>
					    </h3>

				        <div class="card-body">

				            <div class="card-text">
						        <textarea id="w3review" name="w3review" rows="12" cols="120">

					                <?php
					                $linesArray = $langFile->translationLinesArray();
					                // ksort($linesArray);
					                foreach($linesArray as $line) {

					                	echo $line . '<br>';

					                }

					                // $fileLines = implode("<br>", $linesArray);
									// echo $fileLines;

					                ?>
				                </textarea>
					        </div>
				        </div>
			        </div>
			    <?php
			    }
			    ?>
		    </div>
x
	    </div>
	    <?php

	}

	?>


    <hr>

    <input type="hidden" name="task" value="" />
    <?php echo HTMLHelper::_('form.token'); ?>
</form>


