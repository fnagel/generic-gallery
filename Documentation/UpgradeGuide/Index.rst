.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


Upgrade Guide
-------------

.. only:: html

	.. contents:: Within this page
		:local:
		:depth: 3



Update from 1.2.x to 1.3.0
^^^^^^^^^^^^^^^^^^^^^^^^^^

**New features overview**

- TYPO3 7.5 compatibility

- Merge FAL file meta data with inline meta data (file reference)


**How to upgrade**

Just clear the cache and check your templates. Make sure to use :code:`{item.imageData.description}` instead of
:code:`{item.image.properties.description}` when ou want to use merged meta data.



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

