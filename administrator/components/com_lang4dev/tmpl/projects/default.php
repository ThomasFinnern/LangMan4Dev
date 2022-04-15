<?php

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\String\Inflector;


$user      = Factory::getApplication()->getIdentity();
$userId    = $user->get('id');
$extension = $this->escape($this->state->get('filter.extension'));
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

$saveOrder = $listOrder == 'a.ordering';

if (strpos($listOrder, 'publish_up') !== false)
{
    $orderingColumn = 'publish_up';
}
elseif (strpos($listOrder, 'publish_down') !== false)
{
    $orderingColumn = 'publish_down';
}
elseif (strpos($listOrder, 'modified') !== false)
{
    $orderingColumn = 'modified';
}
else
{
    $orderingColumn = 'created';
}

$parts     = explode('.', $extension, 2);
$component = $parts[0];
$section   = null;

/**/
if (count($parts) > 1)
{
    $section = $parts[1];

    $inflector = Inflector::getInstance();

    if (!$inflector->isPlural($section))
    {
        $section = $inflector->toPlural($section);
    }
}
/**/

if ($saveOrder && !empty($this->items))
{
    $saveOrderingUrl = 'index.php?option=com_rsgallery2&task=images.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
    HTMLHelper::_('draggablelist.draggable');
}


?>
<form action="<?php echo Route::_('index.php?option=com_lang4dev&view=projects'); ?>"
      method="post" name="adminForm" id="item-form" class="form-validate">

    <div >
        <?php
        // Search tools bar
        // echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this));
        ?>
        <?php if (empty($this->items)) : ?>

        <div class="card border-danger mb-3" ">
            <div class="card-header"><?php echo Text::_('NOTICE'); ?></div>
            <div class="card-body">
                <!--div class="alert alert-info"-->
                    <span class="fa fa-info-circle" aria-hidden="true"></span><span class="sr-only"><?php echo Text::_('INFO'); ?></span>
                    <?php echo Text::_('COM_LANG4DEV_NO_PROJECT_CREATED'); // JGLOBAL_NO_MATCHING_RESULTS ?>
                <!--/div-->
            </div>
        </div>

        <?php else : ?>

        <table class="table" id="galleryList">
            <caption id="captionTable" class="sr-only">
                <?php echo Text::_('COM_RSGALLERY2_TABLE_CAPTION'); ?>, <?php echo Text::_('JGLOBAL_SORTED_BY'); ?>
            </caption>
            <thead>
                <tr>
                    <td style="width:1%" class="text-center">
                        <?php echo HTMLHelper::_('grid.checkall'); ?>
                    </td>

                    <th scope="col" style="width:1%" class="text-center d-none d-md-table-cell">
                        <?php echo HTMLHelper::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
                    </th>

                    <th scope="col" style="width:3%" class="text-center d-none d-md-table-cell">
                                        <span class="small" title="<?php echo $this->escape("Remove when order is fixed"); ?>">
                                            <?php echo Text::_('COM_RSGALLERY2_ORDER'); ?>
                                        </span>
                    </th>
                </tr>
            </thead>

            <tbody <?php if ($saveOrder) :?> class="js-draggable" data-url="<?php echo $saveOrderingUrl; ?>" data-direction="<?php echo strtolower($listDirn); ?>" data-nested="false"<?php endif; ?>>
            <?php
            foreach ($this->items as $i => $item) : ?>

                <?php echo $item->name; ?>

            <?php endforeach; ?>
            </tbody>
        </table>
            <?php endif; ?>
    </div>

    <input type="hidden" name="extension" value="<?php echo $extension; ?>">
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0">
    <?php echo HTMLHelper::_('form.token'); ?>
</form>


