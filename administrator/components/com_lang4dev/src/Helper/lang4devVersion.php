<?php
/**
 * This class handles version management for Lang4dev
 *
 * @version       
 * @package       Lang4dev
 * @copyright (C) 2022-2022 Lang4dev Team
 * @license       
 */

namespace Finnern\Component\Lang4dev\Administrator\Helper;

use Joomla\CMS\Factory;

// no direct access
\defined('_JEXEC') or die;

/**
 * Version information class. Lives from the manifest file which it loads
 * (formerly based on the Joomla version class)
 *
 * @package Lang4dev
 */
class lang4devVersion
{
	protected $name = 'Lang4dev';
    // Main Release Level: x.y.z
	protected $version = '1.0.0';
	protected $creationDate = '01 Mar. 2022';
	protected $copyright = '01 Mar. 2022';

	/**
	
	 * @since __BUMP_VERSION__
    */
    function __construct()
    {
        //--- collect data from manifest -----------------
        $db = Factory::getDbo();
        $query = $db->getQuery(true)
			->select($db->quoteName('manifest_cache'))
            ->from($db->quoteName('#__extensions'))
            ->where($db->quoteName('element') . ' = ' . $db->quote('com_lang4dev'));
        $db->setQuery($query);

        $manifest = json_decode($db->loadResult(), true);

        //--- assign data from manifest -----------------

	    $this->name = $manifest['name'];
	    $this->creationDate = $manifest['creationDate'];
	    $this->copyright = $manifest['copyright'];
	    $this->version = $manifest['version'];
    }

    /**
     * @return string Long format version
     * @since __BUMP_VERSION__
     */
    function getLongVersion()
    {
        return $this->name . ' '
            . ' [' . $this->version . '] '
            . '(' . $this->creationDate . ')' . ' '
	        . $this->copyright
            ;
    }

    /**
     * @return string Short version format
     * @since __BUMP_VERSION__
     */
    function getShortVersion()
    {
	    return $this->name . ' '
		    . ' [' . $this->version . '] '
		    . '(' . $this->creationDate . ')' . ' '
		    ;
    }

    /**
     * Plain version
     * @return string PHP standardized version format
     * @since __BUMP_VERSION__
     */
    function getVersion()
    {
        return $this->version;
    }

    /**
     * checks if checked version is lower, equal or higher that the current version
     *
     * @param $version
     *
     * @return int -1 (lower), 0 (equal) or 1 (higher)
     * @since __BUMP_VERSION__
     */
    function checkVersion($version)
    {
        $check = version_compare($version, $this->version);

        return $check;
    }

}


