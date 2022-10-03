<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\String\Inflector;

HTMLHelper::_('behavior.multiselect');
// HTMLHelper::_('bootstrap.framework');

//HTMLHelper::_('stylesheet', 'com_rsgallery2/backend/images.css', array('version' => 'auto', 'relative' => true));
//HTMLHelper::_('script', 'com_rsgallery2/backend/images.js', ['version' => 'auto', 'relative' => true]);

$user      = Factory::getApplication()->getIdentity();
$userId    = $user->get('id');
$extension = $this->escape($this->state->get('filter.extension'));
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

$saveOrder = $listOrder == 'a.ordering';

if (strpos($listOrder, 'publish_up') !== false) {
    $orderingColumn = 'publish_up';
} elseif (strpos($listOrder, 'publish_down') !== false) {
    $orderingColumn = 'publish_down';
} elseif (strpos($listOrder, 'modified') !== false) {
    $orderingColumn = 'modified';
} else {
    $orderingColumn = 'created';
}

$parts     = explode('.', $extension, 2);
$component = $parts[0];
$section   = null;

/**/
if (count($parts) > 1) {
    $section = $parts[1];

    $inflector = Inflector::getInstance();

    if (!$inflector->isPlural($section)) {
        $section = $inflector->toPlural($section);
    }
}
/**/

if ($saveOrder && !empty($this->items)) {
    $saveOrderingUrl = 'index.php?option=com_lang4dev&task=projects.saveOrderAjax&tmpl=component&' . Session::getFormToken(
        ) . '=1';
    HTMLHelper::_('draggablelist.draggable');
}

?>
<form action="<?php
echo Route::_('index.php?option=com_lang4dev&view=projects'); ?>"
      method="post" name="adminForm" id="adminForm">

	<div>
        <?php
        // Search tools bar
        // echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this));
        ?>
        <?php
        if (empty($this->items)) : ?>

		<div class="card border-danger mb-3"
		">
		<div class="card-header"><?php
            echo Text::_('NOTICE'); ?></div>
		<div class="card-body">
			<!--div class="alert alert-info"-->
			<span class="fa fa-info-circle" aria-hidden="true"></span><span
					class="sr-only"><?php
                echo Text::_('INFO'); ?></span>
            <?php
            echo Text::_('COM_LANG4DEV_CREATE_YOUR_FIRST_PROJECT'); ?>
			<!--/div-->
		</div>
	</div>

    <?php
    else : ?>

		<table class="table" id="projectList">
			<caption id="captionTable" class="sr-only">
                <?php
                echo Text::_('COM_LANG4DEV_TABLE_CAPTION'); ?>, <?php
                echo Text::_('JGLOBAL_SORTED_BY'); ?>
			</caption>
			<thead>
			<tr>
				<td style="width:1%" class="text-center">
                    <?php
                    echo HTMLHelper::_('grid.checkall'); ?>
				</td>

				<th scope="col" style="width:1%" class="text-center d-none d-md-table-cell">
                    <?php
                    echo HTMLHelper::_(
                        'searchtools.sort',
                        '',
                        'a.ordering',
                        $listDirn,
                        $listOrder,
                        null,
                        'asc',
                        'JGRID_HEADING_ORDERING',
                        'icon-menu-2'
                    ); ?>
				</th>

				<th scope="col" style="width:3%" class="text-center d-none d-md-table-cell">
	                                        <span class="small"
	                                              title="<?php
                                                  echo $this->escape("Remove when order is fixed"); ?>">
	                                            <?php
                                                echo Text::_('JGRID_HEADING_ORDERING'); ?>
	                                        </span>
				</th>

				<th>
                    <?php
                    echo Text::_('JGLOBAL_TITLE'); ?>
				</th>

				<th>
                    <?php
                    echo Text::_('JGRID_HEADING_ID'); ?>
				</th>

				<th>
                    <?php
                    echo Text::_('COM_LANG4DEV_SUBPROJECT_ROOT_PATH'); ?>
				</th>

			</tr>
			</thead>

			<tbody <?php
            if ($saveOrder) : ?> class="js-draggable" data-url="<?php
            echo $saveOrderingUrl; ?>" data-direction="<?php
            echo strtolower($listDirn); ?>" data-nested="false"<?php
            endif; ?>>
            <?php
            foreach ($this->items as $i => $item) : ?>

                <?php
                // Get permissions
                $canEdit    = $user->authorise('core.edit', $extension . '.project.' . $item->id);
                $canCheckin = $user->authorise(
                        'core.admin',
                        'com_checkin'
                    ) || $item->checked_out == $userId || $item->checked_out == 0;
                $canEditOwn = $user->authorise(
                        'core.edit.own',
                        $extension . '.project.' . $item->id
                    ) && $item->created_by == $userId;
                $canChange  = $user->authorise('core.edit.state', $extension . '.project.' . $item->id) && $canCheckin;

                $editLink = Route::_('index.php?option=com_lang4dev&task=project.edit&id=' . $item->id);
                //$editLink = Route::_('index.php?option=com_lang4dev&view=subproject&layout=edit&id=' . $item->id);
                // $editGalleryLink = Route::_("index.php?option=com_lang4dev&task=gallery.edit&id=" . $item->gallery_id);

                $created_by  = Factory::getUser($item->created_by);
                $modified_by = Factory::getUser($item->modified_by);
                if (empty($modified_by->name)) {
                    $modified_by = $created_by;
                }

                ?>

				<tr class="row<?php
                echo $i % 2; ?>">
					<td class="text-center d-none d-md-table-cell">
                        <?php
                        echo HTMLHelper::_('grid.id', $i, $item->id); ?>
					</td>
					<td class="order text-center d-none d-md-table-cell">
                        <?php
                        $iconClass = '';
                        if (!$canChange) {
                            $iconClass = ' inactive';
                        } elseif (!$saveOrder) {
                            $iconClass = ' inactive tip-top hasTooltip" title="' . HTMLHelper::_(
                                    'tooltipText',
                                    'JORDERINGDISABLED'
                                );
                        }
                        ?>
						<span class="sortable-handler<?php
                        echo $iconClass ?>">
											<span class="fa fa-ellipsis-v" aria-hidden="true"></span>
										</span>
                        <?php
                        if ($canChange && $saveOrder) : ?>
							<input type="text" style="display:none" name="order[]" size="5"
							       value="<?php
                                   echo $item->ordering; ?>" class="width-20 text-area-order">
                        <?php
                        endif; ?>
					</td>
					<td class="small d-none d-md-table-cell">
                        <?php
                        echo $item->ordering; ?>
					</td>

					<td class="small d-none d-md-table-cell">
                        <?php
                        echo $i . ': ' . $item->name; ?>
					</td>

					<th scope="row">
                        <?php
                        if ($item->checked_out) : ?>
                            <?php
                            echo HTMLHelper::_(
                                'jgrid.checkedout',
                                $i,
                                $item->editor,
                                $item->checked_out_time,
                                'projects.',
                                $canCheckin
                            ); ?>
                        <?php
                        endif; ?>
                        <?php
                        if ($canEdit || $canEditOwn) : ?>
                            <?php
                            $editIcon = $item->checked_out ? '' : '<span class="fa fa-pencil-square mr-2" aria-hidden="true"></span>'; ?>
							<a class="hasTooltip" href="<?php
                            echo $editLink; ?>"
							   title="<?php
                               echo Text::_('JACTION_EDIT'); ?> <?php
                               echo $this->escape(addslashes($item->title)); ?>">
                                <?php
                                echo $editIcon; ?>
                                <?php
                                echo $this->escape($item->title); ?></a>
                        <?php
                        else : ?>
                            <?php
                            echo $this->escape($item->title); ?>
                        <?php
                        endif; ?>

						<span class="small" title="<?php
                        echo $this->escape(""); ?>">
											<?php
                                            if (empty($item->note)) : ?>
                                                <?php
                                                echo Text::sprintf(
                                                    'JGLOBAL_LIST_ALIAS',
                                                    $this->escape($item->alias)
                                                ); ?>
                                            <?php
                                            else : ?>
                                                <?php
                                                echo Text::sprintf(
                                                    'JGLOBAL_LIST_ALIAS_NOTE',
                                                    $this->escape($item->alias),
                                                    $this->escape($item->note)
                                                ); ?>
                                            <?php
                                            endif; ?>
										</span>
					</th>


				</tr>

            <?php
            endforeach; ?>
			</tbody>
		</table>
    <?php
    endif; ?>
	</div>

	<input type="hidden" name="extension" value="<?php
    echo $extension; ?>">
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0">
    <?php
    echo HTMLHelper::_('form.token'); ?>
</form>


