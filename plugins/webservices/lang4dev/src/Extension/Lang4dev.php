<?php

/**
 * @package         LangMan4Dev
 * @subpackage      plg_webservices_lang4dev
 * @author          Thomas Finnern <InsideTheMachine.de>
 * @copyright  (c)  2022-2025 Lang4dev Team
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Finnern\Plugin\WebServices\Lang4dev\Extension;

use Joomla\CMS\Event\Application\BeforeApiRouteEvent;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Router\ApiRouter;
use Joomla\Event\SubscriberInterface;
use Joomla\Router\Route;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Web Services adapter for com_lang4dev.
 *
 * @since  4.0.0
 */
final class Lang4dev extends CMSPlugin implements SubscriberInterface
{
    /**
     * Returns an array of events this subscriber will listen to.
     *
     * @return  array
     *
     * @since   5.1.0
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onBeforeApiRoute' => 'onBeforeApiRoute',
        ];
    }

    /**
     * Registers com_lang4dev's API's routes in the application
     *
     * @param   BeforeApiRouteEvent  $event  The event object
     *
     * @return  void
     *
     * @since   4.0.0
     */
    public function onBeforeApiRoute(BeforeApiRouteEvent $event): void
    {
        $router = $event->getRouter();

	    $defaults    = ['component' => 'com_lang4dev'];
	    // $getDefaults = array_merge(['public' => false], $defaults);
	    $getDefaults = array_merge(['public' => false], $defaults); // ToDo: Remove when tests finished, enabless acces without token

//	      new Route(['GET'], 'v1/example/items/:slug', 'item.displayItem',
//			    ['slug' => '(.*)'], ['option' => 'com_example']),

	    $router->addRoutes([
		    new Route(['GET'], 'v1/lang4dev', 'lang4dev.displayItem', [], $getDefaults),
	    ]);

        $router->createCRUDRoutes(
			'v1/lang4dev/project',
			'project',
			['component' => 'com_lang4dev'],
	        true // ToDo: Remove when tests finished
		);
	
        $router->createCRUDRoutes(
			'v1/lang4dev/projects',
			'projects',
			['component' => 'com_lang4dev'],
	        true // ToDo: Remove when tests finished
		);
	
        $router->createCRUDRoutes(
			'v1/lang4dev/subprojects',
			'subprojects',
			['component' => 'com_lang4dev'],
	        true // ToDo: Remove when tests finished
		);
	
        $this->createFieldsRoutes($router);

        $this->createContentHistoryRoutes($router);
	}

    /**
     * Create fields routes
     *
     * @param   ApiRouter  &$router  The API Routing object
     *
     * @return  void
     *
     * @since   4.0.0
     */
    private function createFieldsRoutes(&$router): void
    {
        $router->createCRUDRoutes(
            'v1/fields/content/articles',
            'fields',
            ['component' => 'com_fields', 'context' => 'com_content.article']
        );

        $router->createCRUDRoutes(
            'v1/fields/content/categories',
            'fields',
            ['component' => 'com_fields', 'context' => 'com_content.categories']
        );

        $router->createCRUDRoutes(
            'v1/fields/groups/content/articles',
            'groups',
            ['component' => 'com_fields', 'context' => 'com_content.article']
        );

        $router->createCRUDRoutes(
            'v1/fields/groups/content/categories',
            'groups',
            ['component' => 'com_fields', 'context' => 'com_content.categories']
        );
    }

    /**
     * Create contenthistory routes
     *
     * @param   ApiRouter  &$router  The API Routing object
     *
     * @return  void
     *
     * @since   4.0.0
     */
    private function createContentHistoryRoutes(&$router): void
    {
        $defaults    = [
            'component'  => 'com_contenthistory',
            'type_alias' => 'com_lang4dev.lang4dev',
            'type_id'    => 1,
        ];
        $getDefaults = array_merge(['public' => false], $defaults);

        $routes = [
            new Route(['GET'], 'v1/lang4dev/:id/contenthistory', 'history.displayList', ['id' => '(\d+)'], $getDefaults),
            new Route(['PATCH'], 'v1/lang4dev/:id/contenthistory/keep', 'history.keep', ['id' => '(\d+)'], $defaults),
            new Route(['DELETE'], 'v1/lang4dev/:id/contenthistory', 'history.delete', ['id' => '(\d+)'], $defaults),
        ];

        $router->addRoutes($routes);
    }
}

