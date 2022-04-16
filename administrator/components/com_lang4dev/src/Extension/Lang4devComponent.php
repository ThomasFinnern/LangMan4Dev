<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_lang4dev
 *
 * @copyright (C) 2022-2022 Lang4dev Team
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Finnern\Component\Lang4dev\Administrator\Extension;

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Association\AssociationServiceInterface;
use Joomla\CMS\Association\AssociationServiceTrait;
use Joomla\CMS\Categories\CategoryServiceInterface;
use Joomla\CMS\Categories\CategoryServiceTrait;
use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;
use Finnern\Component\Lang4dev\Administrator\Service\HTML\AdministratorService;
use Finnern\Component\Lang4dev\Administrator\Service\HTML\Icon;
use Psr\Container\ContainerInterface;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Component\Router\RouterServiceInterface;
use Joomla\CMS\Component\Router\RouterServiceTrait;

/**
 * Component class for com_lang4dev
 *
 * @since  __BUMP_VERSION__
 */
class Lang4devComponent extends MVCComponent implements BootableExtensionInterface, AssociationServiceInterface, RouterServiceInterface
{
	//use CategoryServiceTrait;
	use AssociationServiceTrait;
	use HTMLRegistryAwareTrait;
	use RouterServiceTrait;

	/**
	 * Booting the extension. This is the function to set up the environment of the extension like
	 * registering new class loaders, etc.
	 *
	 * If required, some initial set up can be done from services of the container, eg.
	 * registering HTML services.
	 *
	 * @param   ContainerInterface  $container  The container
	 *
	 * @return  void
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function boot(ContainerInterface $container)
	{
		$this->getRegistry()->register('lang4devadministrator', new AdministratorService);
		//$this->getRegistry()->register('fooicon', new Icon($container->get(SiteApplication::class)));
	}


}
