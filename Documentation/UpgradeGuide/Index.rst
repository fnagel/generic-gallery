﻿.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


Upgrade Guide
-------------

.. contents:: Within this page
   :local:
   :depth: 3


Update to 6.0.0
^^^^^^^^^^^^^^^

**Overview**

- TYPO3 12.4 LTS support

- Add support for PHP 8.2

- Added Twitter Bootstrap 5 example templates

- Bugfixes and CGL improvements


**How to upgrade**

#. Clear all caches

#. Use upgrade wizard to update image field name of existing plugins




Update to 5.2.0
^^^^^^^^^^^^^^^

**Overview**

- Add support for PHP 8.1

- Bugfix for deleted image collection records

- Bugfix for SQL mode issue

- Bugfixes and minor CGL improvements


**How to upgrade**

#. Clear all caches




Update to 5.1.0
^^^^^^^^^^^^^^^

**Overview**

- Add some more extension manager configuration options

- Improve TCA with palettes and descriptions

- Migrated EM update class to upgrade wizards (you might need to run those again)

- Code quality improvements and clean-up

- Some more improvements and fixes


**How to upgrade**

#. Adjust your EM configuration if needed

#. Clear all caches



Update from 5.0.0 to 5.0.1
^^^^^^^^^^^^^^^^^^^^^^^^^^

**Overview**

- Bugfix for PHP 7.4

- Removed support for PHP < 7.4

- Removed support for TYPO3 < 11.4

- Added basic lint and php-cs tests


**How to upgrade**

#. Clear all caches



Update from 4.x to 5.0.0
^^^^^^^^^^^^^^^^^^^^^^^^

**Overview**

- TYPO3 11.5 LTS support

- Removed support for TYPO3 9.x & 10.x

- Support for PHP 8.0

- Add custom pagination partial

- Added routing for pagination


**How to upgrade**

#. Update templates due to pagination widget removal

#. Update pagination routing

#. Clear all caches



Update from 4.1.0 to 4.2.0
^^^^^^^^^^^^^^^^^^^^^^^^^^

**Overview**

- TYPO3 10.4 LTS support

- Support for PHP 7.3

- Bugfix for single type gallery items with disabled image

- Rename TS and TSconfig files to newer file extensions

- Move TSconfig file to correct folder

- Some minor improvements and fixes


**How to upgrade**

#. "Change fields" in DB using "Analyze Database" in Install tool / Maintenance module

#. Adjust your TS / TSconfig file includes if needed

#. Clear all caches



Update from 4.0.x to 4.1.0
^^^^^^^^^^^^^^^^^^^^^^^^^^

**Overview**

- TYPO3 10.3 support

- Add new template variables:
    - `data.content`: current plugin content element data
    - `data.page`: current page data
    - `data.pageLayout`: current page layout
    - `data.pageBackendLayout`: current page backend layout



Update from 3.x.x to 4.0.0
^^^^^^^^^^^^^^^^^^^^^^^^^^

**Overview**

- TYPO3 10.2 support

- Remove TYPO3 8.x support

- Update default template to match Bootstrap 4 markup

- Add option to add icon for each gallery type

- Lots of improvements, minor fixes and code clean up


**How to upgrade**

#. Update your templates from `{item.uid}` to  `{item.identifier}`

#. Clear all caches



Update from 2.2.x to 3.0.0
^^^^^^^^^^^^^^^^^^^^^^^^^^

**Overview**

- TYPO3 9.x support

- Remove TYPO3 7.x support

- Remove PHP < 7.0 support

- Changed PHP namespace to `FelixNagel`

- Switch to PSR-2 CGL

- Add Bootstrap 4 examples


**How to upgrade**

#. Adjust your class auto loading or class overwrites to new namespace / composer package name (if needed for your setup)

#. Use "Clear all caches including PHP opcode cache" and "Dump Autoload Information" in the install tool (if needed for your setup)

#. Adjust VH namespace in your overwrite templates

#. Add extension routing configuration to your site configuration (TYPO3 9.x only)

#. Clear all caches



Update from 2.1.x to 2.2
^^^^^^^^^^^^^^^^^^^^^^^^

**Overview**

- Tested in PHP 7.2

- Fix TS generation in BE context

- Extract pagination configuration to TS

- Fix issue with flash meta data

- Add file collection type for images only (enable in EM config)

- Some minor bugfixes


**How to upgrade**

Clear the cache using install tool.



Update from 2.0.x to 2.1
^^^^^^^^^^^^^^^^^^^^^^^^

**Overview**

- Added TYPO3 8.7 LTS support

- Adjust RTE and link TCA configuration

- Make use of gallery item sorting field, see https://github.com/fnagel/generic-gallery/issues/6


**How to upgrade**

Clear the cache using install tool.


Update from 1.4.x to 2.0
^^^^^^^^^^^^^^^^^^^^^^^^

**Overview**

- Added TYPO3 8.x support

- Removed TYPO3 6.x support

- Removed TypoLink ViewHelper -> use EXT:fluid default instead

- `GalleryCollection` now inherits ObjectStorage -> easier usage in Fluid (e.g. paginate VH)

- Added pagination to default gallery template

- Removed Bootstrap JS & CSS file include from Bootstrap example configuration

- Added new content element wizard for extension plugin


**How to upgrade**

Update your templates and TypoScript if needed. Clear the cache using install tool.


Update from 1.4.1 to 1.4.3
^^^^^^^^^^^^^^^^^^^^^^^^^^

**Overview**

- Fixed documentation rendering

- Fix for use with TemplaVoila

- Fix for backend preview (page module)


**How to upgrade**

Just clear the cache.


Update from 1.4.0 to 1.4.1
^^^^^^^^^^^^^^^^^^^^^^^^^^

**Overview**

- Bugfix for multiple plugins on one page (when using item detail action)

- Improved TCA and extension configuration (adjusted to match latest TYPO3 API, thanks to Markus Klein!)

- Fixed composer.json and added correct PHP dependency (thanks to Markus Klein!)

- Small improvements to built in templates


**How to upgrade**

Just clear the cache and check your templates. You may need to clear cache in install tool or even re-install the
extension in case you experiencing issues with backend forms!

Make sure to use new detail action link argument `contentElement`:
:code:`<f:link.action action="show" controller="GalleryItem" arguments="{item: item.uid, contentElement: uid}"></f:link>`


Update from 1.3.x to 1.4.0
^^^^^^^^^^^^^^^^^^^^^^^^^^

**New features overview**

- Bugfix for "Merge FAL file meta data with file reference data"

- Support for TYPO3 link validator

- Use built-in image cropping (since TYPO3 7.2)


**How to upgrade**

Just clear the cache and check your templates. Make sure to use new image rendering condition:
:code:`<f:image image="{f:if(condition: '{item.imageReference}', then: '{item.imageReference}', else: '{item.image}')}" />`


Update from 1.2.x to 1.3.0
^^^^^^^^^^^^^^^^^^^^^^^^^^

**New features overview**

- TYPO3 7.6 compatibility

- Merge FAL file meta data with inline meta data (file reference)


**How to upgrade**

Just clear the cache and check your templates. Make sure to use :code:`{item.imageData.description}` instead of
:code:`{item.image.properties.description}` when you want to use merged meta data.


Update from 1.1.0 to 1.2.0
^^^^^^^^^^^^^^^^^^^^^^^^^^

**New features overview**

- TYPO3 7.4 compatibility

- Improve and fix examples

- Update examples to Twitter Bootstrap 3.3.5


**How to upgrade**

Just clear the cache.



Update from 1.0.x to 1.1.0
^^^^^^^^^^^^^^^^^^^^^^^^^^

**New features overview**

- TYPO3 7.x compatibility

- Better backend previews (for example collection image preview)


**How to upgrade**

Just clear the cache in install tool.



Update from 0.4.x to 1.0.x
^^^^^^^^^^^^^^^^^^^^^^^^^^

**New features overview**

- Migration from pibase to extbase / fluid

- Better FAL support

- Image detail view

- Image collection support

- Twitter Bootstrap Examples


**How to upgrade**

First of all you need to upgrade TYPO3 to 6.x and migrate all DAM
records to FAL. This task exceeds the scope of this documentation.

- Update via Extension Manager

- Run update script in Extension Manager

- Adjust TypoScript

  - Change TypoScript path

    - from :code:`plugin.tx_genericgallery_pi1.gallery`

    - to :code:`plugin.tx_genericgallery.settings.gallery`
  - Change gallery settings to new specification

- Update your templates to match fluid structure


See demos: :code:`/Configuration/TypoScript/Examples`


.. tip::

	You will need to install EXT:filemetadata and EXT:metadata in order to use EXIF data




Update from 0.3.x to 0.4.0
^^^^^^^^^^^^^^^^^^^^^^^^^^

First of all you need to upgrade TYPO3 to 6.x and migrate all DAM
records to FAL. This task exceeds the scope of this documentation.

- You will need to install EXT:filemetadata and EXT:metadata

- Generic Gallery itself needs no DB migration but you will need to
  update yours TS and templates.

- All ###DAM\_\* marker have been removed. Use custom markers instead.


Update from 0.3.3 to 0.3.4
^^^^^^^^^^^^^^^^^^^^^^^^^^

Please make sure your non admin BE users still have access to all
fields. You might need to change permissions of your BE user group.


Update from 0.3.2 to 0.3.3
^^^^^^^^^^^^^^^^^^^^^^^^^^

As there has been some changes to the RTE (IRRE description field)
processing you probably need to link your hyperlinks within the RTE
text again. At least if you like to have proper (RealUrl aware) links
and not wrong ones like: http://domain.com/index.php?id=123


Update from 0.2.0 to 0.3.0
^^^^^^^^^^^^^^^^^^^^^^^^^^

Some template marker have changed to match extension marker naming
more precise:

- *###WIDTH###* to  *###IMAGE\_WIDTH###*

- *###HEIGHT###* to  *###IMAGE\_HEIGHT###*

No other changes necessary, but recommended (TS option  *marker* is
more useful than  *damMarker* ).

