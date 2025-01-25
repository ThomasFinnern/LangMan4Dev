# LangMan4Dev

Joomla! language manager for extension developers  
Started Feb 2022

**Supports translation ID handling for joomla extension developers**

![controlpanelRSgallery2](https://github.com/ThomasFinnern/J_LangMan4ExtDevProject/blob/main/Documentation/J!4x/controlPanel/ControlPanel.02.png?raw=true)

It detects missing and surplus translation ids in source code and helps for first manual translations

It does not support auto translations 

## Use cases

1) **Developer translation IDs**
This component will match language translation IDs like COM_LANG4DEV_... defined in *.ini file against code occurences

   * Matches translation IDs defined in *.ini file against code occurences
   * Provides lines with missing Translation Ids for copy into *.ini
   * Supports list of "AD HOC" plain text written in Text::_('...') instead of a translation ID 

   The results are ID lists [matching, missing, surplus] and lines with mising IDS which can be copied nto the *.ini file direct

2) **Translation support**

   From original "en-GB" *.ini files translation files like "de-DE" can be created. They contain the same translation IDs but the translations string is empty

3) **Component user translations** (intention)
    The user of any component should be able to do the translation himself. He can add his own country language files. These are presented in a top bottom compare view where the items lines are prepeared and aligned in main file order.
    The user will see a prepared textarea with translation IDs and empty translation strings. He can save these changed files.

See more in [lang4dev documentation](https://github.com/ThomasFinnern/J_LangMan4ExtDevProject/blob/main/Documentation/J!4x/Lang4dev_Documentation_j4x.md)

## Limitations

  * This component allows to replace language files of foreign components but will not exchange lang items in the code of foreign components

## Possible improvements in the future

### Ideas for program improvements

* Optimise search from middle by first character instead of read all items at once (option later)
* Update button in project texts

### Ideas for functions in future

#### Missing project types

* packages
* indirect packages -> component includes modules and plugins but dows not tell as such
* templates try out, never tested
* joomla en-gb -> de-DE type of complete translations

#### Different folders for en-GB and nn-MM project

   One project for the source and a twin project for the destination translation files
   Example component with en-GB in folder .../A/... and component with de-DE in folder .../B/...

#### Changes on already translated items

A Source file copy may support check for changes within one text definition

? Several versions ? external twin file with links or side by side comment

-Indicate changes per item en-en ==> update (or info) on other language files

#### Translation collection

Collect old translation or translations from different sources into one collection
Refer to this collection if translation is not found
   "en-GB"="de-DE"
   "Exit"="Ausgang"

#### maintenance: check *.ini files for consistency

   *Space around equation [COM_... = "text"]
   *Open texts text definitions [COM_... = "text] [COM_... = text"]

#### Auto translations ? access to Leo api ? google.ie => user login


