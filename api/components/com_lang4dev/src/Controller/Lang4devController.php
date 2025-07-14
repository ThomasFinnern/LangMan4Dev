<?php
/**
 * @package         LangMan4Dev
 * @subpackage      plg_webservices_lang4dev
 * @author          Thomas Finnern <InsideTheMachine.de>
 * @copyright  (c)  2022-2025 Lang4dev Team
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Finnern\Component\Lang4dev\Api\Controller;

use Joomla\CMS\Filter\InputFilter;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\MVC\Controller\ApiController;
use Joomla\CMS\MVC\View\JsonApiView;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use Joomla\String\Inflector;

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


	public function displayItem($id = null)
	{
		$viewType   = $this->app->getDocument()->getType();
		$viewName   = $this->input->get('view', $this->default_view);
		$viewLayout = $this->input->get('layout', 'default', 'string');

		try {
			/** @var JsonApiView $view */
			$view = $this->getView(
				$viewName,
				$viewType,
				'',
				['base_path' => $this->basePath, 'layout' => $viewLayout, 'contentType' => $this->contentType]
			);
		} catch (\Exception $e) {
			throw new \RuntimeException($e->getMessage());
		}

		$modelName = $this->input->get('model', Inflector::singularize($this->contentType));

		// Create the model, ignoring request data so we can safely set the state in the request from the controller
		$model = $this->getModel($modelName, '', ['ignore_request' => true, 'state' => $this->modelState]);

		if (!$model) {
			throw new \RuntimeException(Text::_('JLIB_APPLICATION_ERROR_MODEL_CREATE'));
		}

		try {
			$modelName = $model->getName();
		} catch (\Exception $e) {
			throw new \RuntimeException($e->getMessage());
		}

		// Push the model into the view (as default)
		$view->setModel($model, true);

		$view->setDocument($this->app->getDocument());
		// works if function in jsonApi is set
		// $view->displayItem();
		// works if function in jsonApi is set
		$view->display();

		return $this;
	}





}