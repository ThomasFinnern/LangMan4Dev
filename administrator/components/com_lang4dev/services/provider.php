<?php
/**
 * @package       Joomla.Administrator
 * @subpackage    com_lang4dev
 *
 * @copyright (c) 2022-2023 Lang4dev Team
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Categories\CategoryFactoryInterface;
use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\CategoryFactory;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\HTML\Registry;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\CMS\Association\AssociationExtensionInterface;
use Joomla\CMS\Component\Router\RouterFactoryInterface;
use Joomla\CMS\Extension\Service\Provider\RouterFactory;

use Finnern\Component\Lang4dev\Administrator\Extension\Lang4devComponent;
use Finnern\Component\Lang4dev\Administrator\Helper\AssociationsHelper;

/**
 * The Lang4dev service provider.
 * https://github.com/joomla/joomla-cms/pull/20217
 *
 * @since  __BUMP_VERSION__
 */
return new class implements ServiceProviderInterface {
    /**
     * Registers the service provider with a DI container.
     *
     * @param   Container  $container  The DI container.
     *
     * @return  void
     *
     * @since   __BUMP_VERSION__
     */
    public function register(Container $container)
    {
//		$container->set(AssociationExtensionInterface::class, new AssociationsHelper);

//		$container->registerServiceProvider(new CategoryFactory('\\Finnern\\Component\\Lang4dev'));
        $container->registerServiceProvider(new MVCFactory('\\Finnern\\Component\\Lang4dev'));
        $container->registerServiceProvider(new ComponentDispatcherFactory('\\Finnern\\Component\\Lang4dev'));
//		$container->registerServiceProvider(new RouterFactory('\\Finnern\\Component\\Lang4dev'));

        $container->set(
            ComponentInterface::class,
            function (Container $container) {
                $component = new Lang4devComponent($container->get(ComponentDispatcherFactoryInterface::class));

                $component->setRegistry($container->get(Registry::class));

                $component->setMVCFactory($container->get(MVCFactoryInterface::class));
//				$component->setCategoryFactory($container->get(CategoryFactoryInterface::class));
//				$component->setAssociationExtension($container->get(AssociationExtensionInterface::class));
//				$component->setRouterFactory($container->get(RouterFactoryInterface::class));

                return $component;
            }
        );
    }
};
