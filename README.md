# J_LangMan4ExtDev

Joomla! language manager for extension developers

Started Feb 2022

**Supports language translation handling for joomla extensions**

## Use cases:

1) Developer translations
   1) Developer may use Translation IDs like COM_LANG4DEV_... in the TEXT::_('...') definition and place where these are expected.  
    This component will collect all and provide prepared lines to include in the lang files
   2) Write plain text in the TEXT::_('...') definition.  
    This component will collect all and provide prepared lines to include in the lang files. the lang IDS have to be adjusted though   
      
1) Component user translations
    * The user of another component should be able to do the translation himself
    He can add his own country language files. These are presented in a top bottom compare view where the items lines are prepeared and aligned in main file order
    The user will get a plain textarea to edit the items  
  
1) In general the translation of joomla! *.ini files to another language shall be possible
    * This is the long term intention of this component 

## limitations
  * This component may allow to replace language files of foreign components but will not exchange lang items in the code of foreign components 

### Rules for translation files

- Keep Comments
- Keep en-en file content order for other languages
- Not translated lines will be commented at start of line *

Base files:

- May be kept sorted

Database Project

- old source

⇒ Paths E. may be extra) component paths

=D auto load

• • •

Base files:

• • •

• • •

? renamed items => can't be be handled

### Database

... to be continued

### Base files

Collected files for extended but fast search. These may origin in older version or manual added files

==> Additional files to search

- Copy of previous versions
- Manual user files
- Scan 4 core translations

  - one file per long
  - update button

? Import: keep separate or

collect as is

a) 4 delta

- file layout in folders

or side by side

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
