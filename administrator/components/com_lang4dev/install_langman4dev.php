<?php

/**
 * @package       Joomla.Administrator
 * @subpackage    com_lang4dev
 *
 * @copyright  (c)  2022-2024 Lang4dev Team
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Installer\InstallerScript;

/**
 * Script file of Lang4Dev Component
 *
 * @since  __BUMP_VERSION__
 */
class Com_Lang4devInstallerScript extends InstallerScript
{
    /**
     * Minimum Joomla version to check
     *
     * @var    string
     * @since  __BUMP_VERSION__
     */
    private $minimumJoomlaVersion = '4.0';

    /**
     * Minimum PHP version to check
     *
     * @var    string
     * @since  __BUMP_VERSION__
     */
    private $minimumPHPVersion = JOOMLA_MINIMUM_PHP;

    /**
     * Method to install the extension
     *
     * @param   InstallerAdapter  $parent  The class calling this method
     *
     * @return  boolean  True on success
     *
     * @since  __BUMP_VERSION__
     */
    public function install($parent): bool
    {
        // echo Text::_('COM_LANG4DEV_INSTALLERSCRIPT_INSTALL');

        return true;
    }

    /**
     * Method to uninstall the extension
     *
     * @param   InstallerAdapter  $parent  The class calling this method
     *
     * @return  boolean  True on success
     *
     * @since  __BUMP_VERSION__
     */
    public function uninstall($parent): bool
    {
        // echo Text::_('COM_LANG4DEV_INSTALLERSCRIPT_UNINSTALL');

        return true;
    }

    /**
     * Method to update the extension
     *
     * @param   InstallerAdapter  $parent  The class calling this method
     *
     * @return  boolean  True on success
     *
     * @since  __BUMP_VERSION__
     *
     */
    public function update($parent): bool
    {
        // echo Text::_('COM_LANG4DEV_INSTALLERSCRIPT_UPDATE');

        $this->addDashboardMenu('lang4dev', 'lang4dev');

        return true;
    }

    /**
     * Function called before extension installation/update/removal procedure commences
     *
     * @param   string            $type    The type of change (install, update or discover_install, not uninstall)
     * @param   InstallerAdapter  $parent  The class calling this method
     *
     * @return  boolean  True on success
     *
     * @throws Exception
     * @since  __BUMP_VERSION__
     *
     */
    public function preflight($type, $parent): bool
    {
        if ($type !== 'uninstall') {
            // Check for the minimum PHP version before continuing
            if (!empty($this->minimumPHPVersion) && version_compare(PHP_VERSION, $this->minimumPHPVersion, '<')) {
                Log::add(
                    Text::sprintf('JLIB_INSTALLER_MINIMUM_PHP', $this->minimumPHPVersion),
                    Log::WARNING,
                    'jerror'
                );

                return false;
            }

            // Check for the minimum Joomla version before continuing
            if (!empty($this->minimumJoomlaVersion) && version_compare(JVERSION, $this->minimumJoomlaVersion, '<')) {
                Log::add(
                    Text::sprintf('JLIB_INSTALLER_MINIMUM_JOOMLA', $this->minimumJoomlaVersion),
                    Log::WARNING,
                    'jerror'
                );

                return false;
            }
        }

        // echo Text::_('COM_LANG4DEV_INSTALLERSCRIPT_PREFLIGHT');

        return true;
    }

    /**
     * Function called after extension installation/update/removal procedure commences
     *
     * @param   string            $type    The type of change (install, update or discover_install, not uninstall)
     * @param   InstallerAdapter  $parent  The class calling this method
     *
     * @return  boolean  True on success
     *
     * @since  __BUMP_VERSION__
     *
     */
    public function postflight($type, $parent)
    {
        // echo Text::_('COM_LANG4DEV_INSTALLERSCRIPT_POSTFLIGHT');

        return true;
    }

}
