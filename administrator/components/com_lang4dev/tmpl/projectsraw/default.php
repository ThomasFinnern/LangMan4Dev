<?php
/**
 * @package       Joomla.Administrator
 * @subpackage    com_lang4dev
 *
 * @copyright  (c)  2022-2024 Lang4dev Team
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */


defined('_JEXEC') or die;

use Finnern\Component\Lang4dev\Administrator\Helper\langPathFileName;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;


function renderProjectSelection($form)
{
    ?>
	<br>
	<div class="d-flex flex-row py-0 my-0 justify-content-between">
		<div class="mx-2 py-0 flex-fill ">
            <?php
            echo $form->renderField('selectProject'); ?>
		</div>

        <div class="mx-2 py-0 px-2 flex-fill ">
            <?php
            echo $form->renderField('selectSubproject'); ?>
        </div>

    </div>

	<?php

    return;
}


?>
<form action="<?php echo Route::_('index.php?option=com_lang4dev&view=projectsraw'); ?>"
      method="post" name="adminForm" id="adminForm">

    <?php

    // ToDo: tell main lang and info

    renderProjectSelection($this->form);

    ?>
    <HR>

	<?php
    //--- project ------------------------------------------------------
	$prjInfo = json_encode($this->project, JSON_PRETTY_PRINT);
	$subPrjInfo = json_encode(json_encode($this->project->subProjects), JSON_PRETTY_PRINT);
	if ($prjInfo == false) {
        $prjInfo = '{' . "\n";

            $prjInfo .= '   "prjName": "' . $this->project->prjName . '"' . "\n";
            $prjInfo .= '   "prjRootPath": "' . $this->project->prjRootPath . '"' . "\n";
            $prjInfo .= '   "langIdPrefix": "' . $this->project->langIdPrefix . '"' . "\n";
            //$prjInfo .= '   "subProjects": ' . json_encode($this->project->subProjects) . '"' . "\n";
            $prjInfo .= '   "subProjects": [';

			// imitate parts of json_encode subProjects
			if (count($this->project->subProjects)) {
                foreach ($this->project->subProjects as $subProject) {
                    $prjInfo .= $subProject->prjType->name . ", ";
                }
                $prjInfo = substr($prjInfo, 0, -2);
                $prjInfo .= ']' . "\n";
            } else {

                $prjInfo .= '   "subProjects": []' . "\n";
			}

            $prjInfo .= '   "dbId": ' . $this->project->dbId . "\n";

        $prjInfo .= '}';

	}
    ?>

    <h2><?php echo Text::_('COM_LANG4DEV_PROJECT_RAW'); ?></h2>

    <div class="bg-white">
		<pre>
<?php echo $prjInfo; ?>
		</pre>
	</div>

	<?php
	//--- found lang files list ------------------------------------------------------

	if ( ! empty ($this->langFileSetsPrjs))
	{
		echo '<hr>';

		echo '<h2>' . Text::_('COM_LANG4DEV_LANG_FILES_LIST') . '</h2>';

		//echo '<pre><code>';
		echo '<div class="bg-white">';
		echo '<pre>';
		//echo '<code>';
		//echo json_encode($this->project, JSON_PRETTY_PRINT);
		foreach ($this->langFileSetsPrjs as $prjId => $langFileSets)
		{
			echo '[' . $prjId . ']' . '<br>';

			foreach ($langFileSets as $langId => $langFiles)
			{
				echo '&nbsp;&nbsp;&nbsp;[' . $langId . ']' . '<br>';

				foreach ($langFiles as $langFile)
				{
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;' . $langFile . '<br>';
				}
			}
		}

		//echo '</code></pre>';
		echo '</pre>';
		//echo '</code>';
		echo '</div>';
		//echo '<br>';
	}
    ?>

<!--    //--- manifest file content -------------------------------------------------------->

    <?php
    if ( ! empty ($this->manifestLang))
    {
        echo '<hr>';

	    $manifestText = implode("<br>", $this->manifestLang->__toText());

	    echo '<h2>' . Text::_('COM_LANG4DEV_MANIFEST_FILE') . '</h2>';

	    //echo '<pre><code>';
	    echo '<div class="bg-white">';
	    echo '<pre>';
	    //echo '<code>';
	    echo $manifestText;
	    //echo '</code></pre>';
	    echo '</pre>';
	    //echo '</code>';
	    echo '</div>';
	    //echo '<br>';
    }
    ?>




    <input type="hidden" name="task" value=""/>
    <?php
    echo HTMLHelper::_('form.token'); ?>
</form>


