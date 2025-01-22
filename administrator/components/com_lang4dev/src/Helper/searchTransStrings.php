<?php
/**
 * This class handles version management for Lang4dev
 *
 * @version
 * @package       Lang4dev
 * @copyright  (c)  2022-2024 Lang4dev Team
 * @license
 */

namespace Finnern\Component\Lang4dev\Administrator\Helper;

use Exception;
use Joomla\CMS\Factory;
use Joomla\Filesystem\Folder;

use Finnern\Component\Lang4dev\Administrator\Helper\transIdLocations;
use Joomla\String\Normalise;
use RuntimeException;

use function defined;

// no direct access
defined('_JEXEC') or die;

/**
 * Search language constants (Items) in given folders
 * The files uses is limited as *.ini are not useful
 *
 * @package Lang4dev
 */
class searchTransStrings
{
//	public $fileTypes = 'php, xml';
//	public $langIdPrefix = '';
//	public $searchPaths = [];
    public $transStringLocations;
//
//	public $useLangSysIni = false;
//	public $prjXmlPathFilename = "";
    public $installPathFilename = "";
//
//	protected $name = 'Lang4dev';
    public $searchPaths;
    private $langIdPrefix;

    public $useLangSysIni;
    public $prjXmlPathFilename;



    /**
     * @since __BUMP_VERSION__
     */
    public function __construct($langIdPrefix = 'COM_LANG4DEV_', $searchPaths = array())
    {
        // ToDo: check for uppercase and trailing '_'

        $this->transStringLocations = new transIdLocations();
        $this->langIdPrefix         = $langIdPrefix;

        // if ( !empty ($searchPaths)) ... ???
        $this->searchPaths = $searchPaths;
    }

    // Attention the removing of comments may lead to wrong
    // Index in line for found '*/'
    // find and collect strings in TEXT::_('<string>') which need to be translated
    /**
     *
     * @return \Finnern\Component\Lang4dev\Administrator\Helper\transIdLocations
     *
     * @throws Exception
     * @since version
     */
    public function findAllTranslationStrings()
    {
        // ToDo: log $langIdPrefix, $searchPaths

        $this->transStringLocations = new transIdLocations();

        try {
            /*--------------------------------------------------------------
            checks
            --------------------------------------------------------------*/

            //--- langIdPrefix --------------------------------------------------

            //--- paths given --------------------------------------------------

            //--- paths exist --------------------------------------------------

            //--- All paths --------------------------------------------------

            foreach ($this->searchPaths as $searchPath) {
                //--- paths exist --------------------------------------------------

                $isPathsExisting = is_dir($searchPath);

                if ($isPathsExisting) {
                    //--- search in path -------------------------------

                    $this->searchTransStrings_in_Path($searchPath, $this->langIdPrefix);
                } else {
                    // ToDo: ????
                }
            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing findAllTranslationStrings: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $this->transStringLocations; // ? a lot to return ?
    }

    /**
     * @param $searchPath
     *
     *
     * @throws Exception
     * @since version
     */
    public function searchTransStrings_in_Path($searchPath)
    {
        try {
            #--- All files (*.php, *.xml) in folder -------------------------------------

            foreach ($this->filesInDir($searchPath) as $fileName) {
                $filePath = $searchPath . DIRECTORY_SEPARATOR . $fileName;
                $ext      = pathinfo($filePath, PATHINFO_EXTENSION);

                //--- prevent project sys files -----------------------------------

                // ToDo: installPathFilename Is it set ? construct ND PROPERTY
                if ($ext == 'php' && $filePath == $this->installPathFilename) {
                    continue;
                }

                //--- scan content of valid files -----------------------------------

                if ($ext == 'php') {
                    $this->searchTransStrings_in_PHP_file($fileName, $searchPath);
                }
            }

            #--- All sub folders in folder -------------------------------------

            foreach ($this->folderInDir($searchPath) as $folderName) {
                $subFolder = $searchPath . DIRECTORY_SEPARATOR . $folderName;
                $this->searchTransStrings_in_Path($subFolder);
            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing searchTransStrings_in_Path: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }
    }

    /**
     * @param $folder
     *
     * @return array|bool
     *
     * @throws Exception
     * @since version
     */
    public function filesInDir($folder)
    {
        $files = [];

        try {
            // php, xml
            //$regEx = '\.xml$|\.html$';
            $regEx = '\.php|\.xml$';
            $files = Folder::files($folder, $regEx);
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing filesInDir: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $files;
    }

    // Multiple items in one line

    /**
     * @param $fileName
     * @param $path
     *
     *
     * @throws Exception
     * @since version
     */
    public function searchTransStrings_in_PHP_file($fileName, $path)
    {
        $isInComment = false;

        try {
            $lineNr = 0;

            // Read all lines
            $filePath = $path . DIRECTORY_SEPARATOR . $fileName;

            $lines = file($filePath);

            // content found
            // ToDo: 		foreach ($lines as $lineNr => $line)
            foreach ($lines as $line) {
                $lineNr = $lineNr + 1;

                //--- remove comments --------------

                $bareLine = $this->removeCommentPHP($line, $isInComment);

                //--- find items --------------

                if (strlen($bareLine) > 0) {
                    $items = $this->searchTextStrings_in_line_PHP($bareLine);

                    //--- add items
                    foreach ($items as $item) {
                        $item->file   = $fileName;
                        $item->path   = $path;
                        $item->lineNr = $lineNr;

                        $this->transStringLocations->addItem($item);
                    }
                    /**
                     * $items = $this->searchEchoStrings_in_line_PHP($bareLine);
                     *
                     * //--- add items
                     * foreach ($items as $item)
                     * {
                     * $item->file    = $fileName;
                     * $item->path    = $path;
                     * $item->lineNr = $lineNr;
                     *
                     * $this->transStringLocations->addItem($item);
                     * }
                     * /**/
                }
            }
        } catch (RuntimeException $e) {
            $OutTxt = 'Error executing searchTransIdsIn_PHP_file: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }
        // return $this->transStringLocations;
    }

    /**
     * @param $line
     * @param $isInComment
     *
     * @return false|mixed|string
     *
     * @throws Exception
     * @since version
     */
    public function removeCommentPHP($line, &$isInComment)
    {
        $bareLine = $line;

        try {
            // No inside a '/*' comment
            if (!$isInComment) {
                //--- check for comments ---------------------------------------

                $doubleSlash   = '//';
                $slashAsterisk = '/*';

                $doubleSlashIdx   = strpos($line, $doubleSlash);
                $slashAsteriskIdx = strpos($line, $slashAsterisk);

                // comment exists, keep start of string
                if ($doubleSlashIdx != false || $slashAsteriskIdx != false) {
                    if ($doubleSlashIdx != false && $slashAsteriskIdx == false) {
                        $bareLine = strstr($line, $doubleSlash, true);
                    } else {
                        if ($doubleSlashIdx == false && $slashAsteriskIdx != false) {
                            $bareLine    = strstr($line, $slashAsterisk, true);
                            $isInComment = true;
                        } else {
                            //--- both found ---------------------------------

                            // which one is first
                            if ($doubleSlashIdx < $slashAsteriskIdx) {
                                $bareLine = strstr($line, $doubleSlash, true);
                            } else {
                                $bareLine    = strstr($line, $slashAsterisk, true);
                                $isInComment = true;
                            }
                        }
                    }
                } // No comment indicator

            } else {
                //--- Inside a '/*' comment

                $bareLine = '';

                $asteriskSlash    = '*/';
                $asteriskSlashIdx = strpos($line, $asteriskSlash);

                // end found ?
                if ($asteriskSlashIdx != false) {
                    // Keep end of string
                    $bareLine = strstr($line, $asteriskSlash);

                    // handle rest of string
                    $isInComment = false;
                    $bareLine    = $this->removeCommentPHP($bareLine, $isInComment);
                }
            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing removeCommentPHP: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $bareLine;
    }

    // Multiple items in one line
    // Must be cleaned from comments first
    /**
     * @param $line
     *
     * @return array
     *
     * @throws Exception
     * @since version
     */
    public function searchTextStrings_in_line_PHP($line)
    {
        $items   = [];
        $matches = [];

        try {
            // find all words then iterate through array
// https://stackoverflow.com/questions/4722007/php-preg-match-to-find-whole-words

            // Python solution
            // py$searchRegex = "\\b" + $this->langIdPrefix + "\\w+";
            // Finds multiple words per line
            // $searchRegex = '/' . $this->langIdPrefix . "\w+/";
            // $searchRegex = '/' . 'Text::_\(\'(.*)\'/';
            $searchRegex = '/' . 'Text::_\([\'"](.*?)[\'"]' . '/';

            // test find all words then iterate through array
            preg_match_all($searchRegex, $line, $matchGroups);

            if (!empty($matchGroups)) // if (count ($matchGroups) > 0)
            {
                $idx = 0;

                // all items found in line
                foreach ($matchGroups[1] as $string) {
                    $name = $this->createTransID($string);

                    if (strlen($name) > 0) {
                        $colIdx = strpos($line, $string, $idx);

                        $item         = new transIdLocation ($name, '', '', -1, $colIdx);
                        $item->string = $string; // additional

                        // ? same twice ?
                        $items [] = $item;

                        // search behind last find
                        $idx = $colIdx + strlen($name);
                    }
                }
            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing searchLangIdsInLinePHP: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $items;
    }

    // Multiple items in one line
    // Must be cleaned from comments first
    // covers only strings in one line
    /**
     * @param $line
     *
     * @return array
     *
     * @throws Exception
     * @since version
     */
    public function searchEchoStrings_in_line_PHP($line)
    {
        $items   = [];
        $matches = [];

        try {
            // find all words then iterate through array
// https://stackoverflow.com/questions/4722007/php-preg-match-to-find-whole-words

            // will only find first string and not a combination of sub parts in echo code
            $searchRegex = '/' . 'Text::_\([\'"](.*?)[\'"]' . '/';
            $searchRegex = '/' . 'echo.*[\'"](.*?)[\'"]' . '/';

            // test find all words then iterate through array
            preg_match_all($searchRegex, $line, $matchGroups);

            if (!empty($matchGroups)) // if (count ($matchGroups) > 0)
            {
                $idx = 0;

                // all items found in line
                foreach ($matchGroups[1] as $string) {
                    $name = $this->createTransID($string);

                    if (strlen($name) > 0) {
                        $colIdx = strpos($line, $string, $idx);

                        $item         = new transIdLocation ($name, '', '', -1, $colIdx);
                        $item->string = $string; // additional

                        // ? same twice ?
                        $items [] = $item;

                        // search behind last find
                        $idx = $colIdx + strlen($name);
                    }
                }
            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing searchLangIdsInLinePHP: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $items;
    }

    // looking for user text not translation IDs like COM_LANG4DEV_....
    //
    /**
     * @param $string
     *
     * @return string
     *
     * @throws Exception
     * @since version
     */
    public function createTransID($string)
    {
        // $transId = '??? ' . $string;
        $transId = '';

        try {
            // ToDo: Move to start of search ? or not needed at all
            $prefix = $this->langIdPrefix;
            if (!str_ends_with($prefix, '_')) {
                $prefix = $prefix . '_';
            }

            // not translation ID (all ASCII are upper, find one lower case)
            $isNotLikeTranslationID = preg_match("/[a-z]/", $string);

            if ($isNotLikeTranslationID) {
                $underscore     = Normalise::toUnderscoreSeparated($string);
                $uppercase      = strToUpper($underscore);
                $prefixAddition = $prefix . $uppercase;

                // toDo ? any strange chars left ?

                $transId = $prefixAddition;
            }
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing createTransID: "' . $string . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $transId;
    }

    /**
     * @param $folder
     *
     * @return array|false
     *
     * @throws Exception
     * @since version
     */
    public function folderInDir($folder)
    {
        $folders = [];

        try {
            // ToDo: leave out 'language' folder
            // php, xml
            $folders = Folder::folders($folder);
        } catch (RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing folderInDir: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $folders;
    }

}