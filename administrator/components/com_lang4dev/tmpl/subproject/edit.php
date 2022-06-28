<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_lang4dev
 *
 * @copyright   (C) 2022 - 2022 Lang4dev Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');

$app = Factory::getApplication();
$input = $app->input;

//$assoc = Associations::isEnabled();
// Are associations implemented for this extension?
$extensionassoc = array_key_exists('item_associations', $this->form->getFieldsets());

// Fieldsets to not automatically render by /layouts/joomla/edit/params.php
$this->ignore_fieldsets = array('jmetadata', 'item_associations');
$this->useCoreUI = true;

// In case of modal
$isModal = $input->get('layout') == 'modal';
$layout  = $isModal ? 'modal' : 'edit';
$tmpl    = $isModal || $input->get('tmpl', '', 'cmd') === 'component' ? '&tmpl=component' : '';

$assoc = false; // ToDo: check how it is used
?>

<form action="<?php echo Route::_('index.php?option=com_lang4dev&view=subproject&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">

	<?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<div>
		<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'general', Text::_('COM_LANG4DEV_SUB_PROJECT')); ?>
		<div class="row">
            <div class="col-lg-9">
                <?php // echo'-------------- lg-9.start: ><br>'; ?>
	            <div class="card">
                    <div class="card-body">
	                    <div class="card-header"  style="background-color: lightgrey;">
		                    <?php echo Text::_('COM_LANG4DEV_SUBPROJECT_BASE'); ?>
	                    </div>
	                    <br>

	                    <fieldset class="adminform">
                            <?php
                            // echo'-------------- prjId/type: ><br>';
                            echo $this->form->renderField('id');
                            echo $this->form->renderField('prjId');
                            echo $this->form->renderField('subPrjType');
                            echo $this->form->renderField('root_path');


                            // echo $this->form->renderField('twin_id');

                            echo $this->form->renderField('notes');

                            // echo'<br>-------------- end: ><br>';

                            ?>

	                        <!--div class="card  text-dark bg-light mb-3  border-success"-->
		                        <div class="card-header"  style="background-color: lightgrey;">
			                        <?php echo Text::_('JDETAILS'); ?>
		                        </div>
								<br>

		                        <!--div class="card-body"-->
			                        <!--h5 class="card-title"><?php echo Text::_('JDETAILS'); ?></h5-->
			                        <?php
			                        echo $this->form->renderField('prefix');

			                        echo $this->form->renderField('prjXmlPathFilename');
			                        echo $this->form->renderField('installPathFilename');

			                        echo $this->form->renderField('parent_id');
			                        echo $this->form->renderField('twin_id');

			                        ?>

		                        <!--/div-->
		                        <!--/div-->

                        </fieldset>
                    </div>
                </div>
                <?php // echo'-------------- lg-9.end: ><br>'; ?>
            </div>
		</div>

		<?php echo LayoutHelper::render('joomla.edit.params', $this); ?>

		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'publishing', Text::_('INFO')); ?>
        <div class="row">
            <div class="col-12 col-lg-6">
                <fieldset id="fieldset-publishingdata" class="options-form">
                    <legend><?php echo Text::_('JGLOBAL_FIELDSET_PUBLISHING'); ?></legend>
                    <div>
                        <?php // echo LayoutHelper::render('joomla.edit.publishingdata', $this); ?>
                    </div>
                </fieldset>
            </div>
            <div class="col-12 col-lg-6">
                <fieldset id="fieldset-metadata" class="options-form">
                    <legend><?php echo Text::_('JGLOBAL_FIELDSET_METADATA_OPTIONS'); ?></legend>
                    <div>
                        <?php echo LayoutHelper::render('joomla.edit.metadata', $this); ?>
                    </div>
                </fieldset>
            </div>
        </div>
		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php if ( ! $isModal && $assoc && $extensionassoc) : ?>
			<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'associations', Text::_('JGLOBAL_FIELDSET_ASSOCIATIONS')); ?>
			<?php echo $this->loadTemplate('associations'); ?>
			<?php echo HTMLHelper::_('uitab.endTab'); ?>
		<?php elseif ($isModal && $assoc && $extensionassoc) : ?>
			<div class="hidden"><?php echo $this->loadTemplate('associations'); ?></div>
		<?php endif; ?>

		<?php if ($this->canDo->get('core.admin')) : ?>

			<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'rules', Text::_('JGLOBAL_ACTION_PERMISSIONS_LABEL')); ?>
			<?php echo $this->form->getInput('rules'); ?>
			<?php echo HTMLHelper::_('uitab.endTab'); ?>
		<?php endif; ?>

		<?php echo HTMLHelper::_('uitab.endTabSet'); ?>

		<?php echo $this->form->getInput('extension'); ?>
		<input type="hidden" name="task" value="">
		<input type="hidden" name="forcedLanguage" value="<?php echo $input->get('forcedLanguage', '', 'cmd'); ?>">
		<?php echo HTMLHelper::_('form.token'); ?>
	</div>
</form>
