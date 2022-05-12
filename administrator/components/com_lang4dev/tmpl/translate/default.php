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
            <!-- h5 class="card-title"></h5-->
            <p class="card-text">
                <?php



                ?>
            </p>
        </div>
    </div>
    <?php

	}

	?>


    <hr>

    <input type="hidden" name="task" value="" />
    <?php echo HTMLHelper::_('form.token'); ?>
</form>


