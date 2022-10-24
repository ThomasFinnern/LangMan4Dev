# J_LangMan4ExtDev

Joomla! language manager for extension developers

Started Feb 2022

**Supports language translation handling for joomla extensions**

## Use cases:

1) **Developer translation IDs**
This component will match languages IDs like COM_LANG4DEV_... defined in *.ini file against code occurences

   * Matches translation IDs defined in *.ini file against code occurences
   * Provides lines with missing Translation Ids for copy into *.ini
   * Supports AD HOC Translation IDs written insisde Text::_('...') but ID is not found in *.ini

   The results are ID lists [matching, missing, surplus] and lines with mising IDS which can be copied nto the *.ini file direct

   See more in [Project texts](#Project-texts)

2) **Translation support**

   From original "en-GB" *.ini files translation files like "de-DE" can be created. They contain the same translation IDs but the translations string is empty

3) **Component user translations** (intention)  
    The user of another component should be able to do the translation himself. He can add his own country language files. These are presented in a top bottom compare view where the items lines are prepeared and aligned in main file order. 
    The user will get aprepared textarea with translation IDs and empty translation strings. He can save these changed files 

## limitations
  * This component may allow to replace language files of foreign components but will not exchange lang items in the code of foreign components 

## Draft of possible functions

Possible functions

- Create/Update other language files from en_en files
- Import translations by link to older version (J4x <- J3x) *

- Merge old lang files
- Create new lang files
- Update new items to files
- Projects

- He can add his own country language files.  

- Import base file to local

- Import by folders

- Import by link to external project

- Scan project for new com_... definitions

- Scan project for empty"""

- Delta view en-en <=> other language

- Support several translation projects

- Edit translation item as files

- Search in core files

- Present

- Edit as items side by side

- Import base file to local

- Check file consistency

- Import base files

-
- Search for translations by name a) for resulting com- --- name b) also in Joomla core

  ```
  1) Use com_name
  2) To be copied
  ```

### ideas for functions in far future

#### Changes on already translated items

A Source file copy may support check for changes within one text definition

? Several versions ? external twin file with links or side by side comment

- Indicate changes per item en-en ==> update (or info) on other language files

#### Auto translations ? access to Leo api ? google.ie ⇒ user login.

#### Program idea (may be ommitted later):

- Optimise search from middle by first character instead of read all items at once (option later) *
