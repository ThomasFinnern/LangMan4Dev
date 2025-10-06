<?php
/**
 * @package         LangMan4Dev
 * @subpackage      com_lang4dev
 * @author          Thomas Finnern <InsideTheMachine.de>
 * @copyright  (c)  2022-2025 Lang4dev Team
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */


defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

$this->document->getWebAssetManager()->useStyle('com_lang4dev.backend.prjTexts');

$comment = '';
if ($this->isDoCommentIds) {
    $comment = ';';
}

$subProjectDataList = $this->subProjectDatas;

/*-----------------------------------------------------------------
HTML code
-----------------------------------------------------------------*/
?>


<form action="<?php
echo Route::_('index.php?option=com_lang4dev&view=prjtexts'); ?>"
      method="post" name="adminForm" id="adminForm">

	<div class="project_selection_container">

	    <?php renderProjectSelection($this->form); ?>
    	<?php renderLangIdTexts($this->form); ?>

	</div>

    <div class="prj_data_container">

		<?php if (count($subProjectDataList) > 0): ?>
			<?php
	        foreach ($subProjectDataList as $subProjectData) {
	            ?>
	            <div class="card ">
	                <h2 class="card-header ">
	                    <?php

	                    renderHeaderPrjIdType($subProjectData->prjIdAndTypeText, $subProjectData->langFileNames, $subProjectData->componentLangPath);

	                    ?>
	                </h2>

	                <div class="card-body">
	                    <!-- h5 class="card-title"></h5-->
	                    <p class="card-text">
	                        <?php

	                        renderDeveloperAdHocTexts($subProjectData->transStringsLocations, $comment);

	                        // fields ['missing, same, notUsed, doubles']
	                        $transIdsClassified = $this->transIdsClassified[$subProjectData->prjIdAndTypeText];

	                        // ??? renderMissingPreparedTransIds ($missing, $comment);

	                        // ToDo: Use constants ?
	                        renderSubProjectStatistic($transIdsClassified['missing'],
	                            $transIdsClassified['same'],
	                            $transIdsClassified['notUsed'],
	                            $transIdsClassified['doubles']);

	                        ?>
	                    </p>
	                </div>
	            </div>
		    <?php
		        }
		    ?>
		<?php else: ?>
	        <div class="prj_data_empty_container">
	            <div class="card ">
	                <h2 class="card-header ">
	                    <?php Text::_('COM_LANG4DEV_NO_SUB_PROJECTS_DEFINED_FOR_PROJECT'); ?>
	                </h2>

	                <div class="card-body">
	                    <?php Text::_('COM_LANG4DEV_NO_SUB_PROJECTS_DEFINED_FOR_PROJECT_DESC'); ?>
	                </div>
	            </div>
	        </div>
		<?php endif; ?>

		<?php
		if ($this->isDebugBackend)
		{
			renderDebug($this->langProject);
		}
		?>

	</div>

    <input type="hidden" name="task" value=""/>
    <?php
    echo HTMLHelper::_('form.token');
    ?>

</form>

<?php
function renderProjectSelection($form)
{
    ?>
	<div class="project_selection">
		<div class="project_selection_setting">
            <?php echo $form->renderField('selectProject'); ?>
		</div>
		<div class="project_selection_setting">
            <?php echo $form->renderField('selectSubproject'); ?>
		</div>
	</div>
    <?php

    return;
}

function renderLangIdTexts($form)
{
    // mx-2 py-0, mx-2 py-0 px-2
    ?>
	<div class='project_selection'>
		<div class='project_selection_setting'>
            <?php echo $form->renderField('selectSourceLangId'); ?>
		</div>
		<div class='project_selection_setting'>
            <?php echo $form->renderField('selectTargetLangId'); ?>
		</div>
	</div>
    <?php

    return;
}

function renderHeaderPrjIdType(
    $prjIdAndType = '',
    $fileNames = '',
    $path = '??? dummy path ??? may be a bit longer though ? '
) {
    ?>
	<!--div class="row g-2"-->
	<div class="row">
		<div class="prj_id_type_header">

	        <div class="prj_id_type_header_id_type">
				<div class="p-2 flex-grow-1">

	                <?php
	                echo $prjIdAndType; ?>

				</div>
			</div>

			<div class="prj_id_type_header_filenames fs-4">
	            <?php
	            foreach ($fileNames as $idx => $fileName) {
	                echo '&nbsp;' . ($idx+1) . ': ' . $fileName . '<br>';
	            }
	            ?>
			</div>

			<div class="prj_id_type_header_path fs-4">
	            <?php
	            echo $path; ?>
			</div>

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
            <?php
            echo count($missing); ?>
            <span class="visually-hidden">Count missing</span>
        </span>
		<h3><?php
            echo Text::_('COM_LANG4DEV_MISSING_TRANSLATION_IDS'); ?>&nbsp;&nbsp;&nbsp;</h3>
	</div>
	<br>

    <?php

    if (count($missing) > 0) {
        ?>
		<div class="card bg-light border">
			<h3 class="card-header bg-white">
                <?php
                echo Text::_('COM_LANG4DEV_MISSING_TRANS_IDS_PREPARED'); ?>
				<!--span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light"-->
				<span class="badge rounded-pill  bg-danger border border-light"
				      style="position: relative; top: -12px; left: +5px; ">
				    <?php
                    echo count($missing); ?>
				    <span class="visually-hidden">Count missing</span>
				</span>
			</h3>
			<a class="btn btn-sm" style="color: black; background-color: #ced4da;" data-bs-toggle="collapse"
			   href="#collapseMissing" role="button" aria-expanded="false" aria-controls="collapseMissing">
                <?php
                echo Text::_('COM_LANG4DEV_TOGGLE_MISSING_IDS'); ?>
			</a>
			<div class="collapse show" id="collapseMissing">
				<br>

				<div class="card-body">
					<!-- h5 class="card-title"></h5-->
					<p class="card-text">
                        <?php
                        foreach ($missing as $transId) {
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

function renderDeveloperAdHocTexts($transStringsLocations, $comment = '')
{
    $locationsCount = count($transStringsLocations);
    if ($locationsCount > 0) {
        ?>
        <div class="Ad_hoc_header">
	        <div class="Ad_hoc_title">
		        <div class="card bg-light border">
		            <h3 class="card-header bg-white">
		                <?php
		                echo Text::_('COM_LANG4DEV_DEVELOPER_AD_HOC_TEXTS'); ?>
		                <!--span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light"-->
		                <span class="badge rounded-pill  bg-danger border border-light"
		                      style="position: relative; top: -12px; left: +5px; ">
		                    <?php
		                    echo $locationsCount;
		                    ?>
		                    <span class="visually-hidden">Count missing</span>
		                </span>
		            </h3>
		        </div>
	        </div>

	        <div class="ad_hoc_description">
	            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php
	                echo Text::_('COM_LANG4DEV_DEVELOPER_AD_HOC_TEXTS_DESC'); ?>
	        </div>
        </div>
        <div class="card-body">
            <!-- h5 class="card-title"></h5-->
            <p class="card-text">
                <?php
                foreach ($transStringsLocations as $transIds) {
                    foreach ($transIds as $transId) {
                        /**
                         * echo '# ' . $transId->file
                         * . ' [L'. $transId->lineNr . 'C' . $transId->colIdx . '] in '
                         * . ' (' . $transId->path . ')<br>';
                         * echo $comment . $transId->name . '="' . $transId->string . '"<br>';
                         * /**/

                        /**/

                        echo $comment . $transId->name . '="' . $transId->string . '"'
                            . ' ;' . $transId->file
                            . ' [L' . $transId->lineNr . 'C' . $transId->colIdx . '] 
                        . <br>';
                        /**/
                    }
                }

                ?>
            </p>
        </div>
        <br>
        <br>

        <?php
    }

    return;
}

function renderSubProjectStatistic($missing, $same, $notUsed, $doubles, $comment = '')
{
    ?>
    <div class="row g-3">

        <div class="col">
            <div class="d-inline-flex position-relative">
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
                    <?php
                    echo count($missing); ?>
                    <span class="visually-hidden">Count missing</span>
                </span>
                <h3><?php
                    echo Text::_('COM_LANG4DEV_MISSING_TRANSLATION_IDS'); ?>&nbsp;&nbsp;&nbsp;
                </h3>
            </div>
            <br>

            <?php
            if (count($missing) > 0) {
                // hide with button
                ?>
                <a class="btn btn-sm" style="color: black; background-color: #ced4da;" data-bs-toggle="collapse"
                   href="#collapseMissing" role="button" aria-expanded="false" aria-controls="collapseMissing">
                    <?php
                    echo Text::_('COM_LANG4DEV_TOGGLE_MISSING_IDS'); ?>
                </a>
                <div class="collapse show" id="collapseMissing">
                    <br>
                    <?php
                    //		            $newItemLines = implode("<br>", $missing);
                    //		            echo $newItemLines;
                    foreach ($missing as $transId) {
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
                    <?php
                    echo count($notUsed); ?>
                    <span class="visually-hidden">count same</span>
                </span>
                <h3>
                    <?php
                    echo Text::_('COM_LANG4DEV_SURPLUS_TRANSLATIONS');
                    ?>&nbsp;&nbsp;&nbsp;
                </h3>
            </div>
            <br>

            <?php
            if (count($notUsed) > 0) {
                // hide with button
                ?>
                <a class="btn btn-sm" style="color: black; background-color: #ced4da;" data-bs-toggle="collapse"
                   href="#collapseNotUsed" role="button" aria-expanded="false" aria-controls="collapseNotUsed">
                    <?php
                    echo Text::_('COM_LANG4DEV_TOGGLE_NOT_USED_IDS'); ?>
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
                    <?php
                    echo count($same); ?>
                    <span class="visually-hidden">count same</span>
                </span>
                <h3><?php
                    echo Text::_('COM_LANG4DEV_MATCHING_TRANSLATION_IDS'); ?>&nbsp;&nbsp;&nbsp;</h3>
            </div>
            <br>

            <?php
            if (count($same) > 0) {
                // hide with button
                ?>
                <a class="btn btn-sm" style="color: black; background-color: #ced4da;" data-bs-toggle="collapse"
                   href="#collapseSame" role="button" aria-expanded="false" aria-controls="collapseSame">
                    <?php
                    echo Text::_('COM_LANG4DEV_TOGGLE_MATCHING_IDS'); ?>
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

        <?php
        if (!empty ($doubles)): ?>
            <!--        <div class="col">-->
            <!--            <h3>Double Translation Ids<?php
            echo ' (' . count($missing) . ')'; ?></h3><br>-->
            <!--            --><?php
////            $newItemLines = implode("<br>", $transIdsClassified['double']);
////            echo $newItemLines;
//            ?>
            <!--        </div>-->
        <?php
        endif; ?>
    </div>

    <?PHP
    return;
}


function renderDebug($langPrroject) {

    //--- show projectTexts with sub projects ... ---------------------------------
/*-----------------------------------------------------------------
Debug lines
-----------------------------------------------------------------*/
    ?>
    <hr>
    <br>
    <!--div class="row g-2"-->
    <div class="row">
        <h3>projectTexts (sub) data</h3><br>
        <div class="d-flex align-items-center">
            <div class="p-2 flex-grow-1">

                <?php
                $projectText = implode("<br>", $langPrroject->__toText());
                echo $projectText;
                ?>

            </div>

        </div>

    </div>
    <br>

    <?php
    return;
}
