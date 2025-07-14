<?php
/**
 * @package         LangMan4Dev
 * @subpackage      plg_webservices_lang4dev
 * @author          Thomas Finnern <InsideTheMachine.de>
 * @copyright  (c)  2022-2025 Lang4dev Team
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Finnern\Component\Lang4dev\Api\View\Lang4dev;

use Finnern\Component\Lang4dev\Api\Helper\Lang4devHelper;
use Finnern\Component\Lang4dev\Api\Serializer\Lang4devSerializer;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\MVC\View\JsonApiView as BaseApiView;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use Joomla\Registry\Registry;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * The projects view
 *
 * @since  4.0.0
 */
class JsonapiView extends BaseApiView
{
	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 *
	 * @since   4.0.0
	 */
	public function displayItem($tpl = null)
	{
		$testLang4devText = "testLang4devText";

		// Serializing the output
		//$result = json_encode($this->_output);
		$result = json_encode($testLang4devText);

		// Pushing output to the document
		$this->getDocument()->setBuffer($result);

		return $this->getDocument()->render();
	}

	public function display($tpl = null)
	{
		$testLang4devText = "testLang4devText";

		zzzz();

		// Serializing the output
		//$result = json_encode($this->_output);
		$result = json_encode($testLang4devText);

		// Pushing output to the document
		$this->getDocument()->setBuffer($result);

		return $this->getDocument()->render();
	}

// ToDo: Later The hidden gem of the API view is another string array property, $relationship. In that view you list all the field names returned by your model which refer to related data.


}
