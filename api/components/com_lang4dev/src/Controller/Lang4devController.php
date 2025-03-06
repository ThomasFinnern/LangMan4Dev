<?php
namespace Finnern\Component\Lang4dev\Api\Controller;

use Joomla\CMS\Filter\InputFilter;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\MVC\Controller\ApiController;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * The projects controller
 *
 * @since  4.0.0
 */
class Lang4devController extends ApiController
{
    /**
     * The content type of the item.
     *
     * @var    string
     * @since  4.0.0
     */
//    protected $contentType = 'lang4dev.projects';
    protected $contentType = 'lang4dev';

    /**
     * The default view for the display method.
     *
     * @var    string
     * @since  3.0
     */
    protected $default_view = 'lang4dev';


    // Implement other methods like read, update, delete as needed
}