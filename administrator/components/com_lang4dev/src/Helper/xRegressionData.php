<?php
/**
 * @package       Joomla.Administrator
 * @subpackage    com_lang4dev
 *
 * @copyright  (c)  2022-2024 Lang4dev Team
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */

// Test several strings for developer text definitions

/*--- what is nt covered actually -------------------------------

more complex situations like may not appear to result as valid output

* TEXT::_ with unicode characters may fail with invalid character inside

* echo 'xxx' . 'yyy' \n ...
  echo with more then one line or more then one argument


*/

Text::_('Thats a convenient first draw of a user text');
Text::_('COM_LANG4DEV_NO_IMAGE_SELECTED') . ' ' . Text::_('COM_LANG4DEV_NO_IMAGE_SELECTED2');

Text::_('Just a next developers left over user text') . ' ' . Text::_('with a second text in tow');

Text::_('Then there are special characters like next') . ' ' . Text::_('a äÄ, o öÖ, u üÜ');

Text::_('so');
Text::_('short');

echo 'just echoed';
echo 'second in tow';


// ToDo: Add com_lang4dev transId tests again