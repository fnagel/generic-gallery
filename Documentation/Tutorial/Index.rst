.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


Tutorial
--------


.. only:: html

	.. contents:: Within this page
		:local:
		:depth: 3



How to use Exif & IPTC
^^^^^^^^^^^^^^^^^^^^^^

You need to install EXT:filemetadata and use an extension like EXT:metadata or similar to extract the
meta data from your files and make it available via FAL properties.
See :ref:`configuration-template` for more information on how to use the meta data in your templates.


How to add existing collections
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

It's possible to disable inline editing of collection records in the plugin.
Disable :code:`use_inline_collection` in the site settings (setup module) or extension manager (before version 8).


Custom marker (how to render any content)
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Use a TypoScript object of any type with help of the cObject ViewHelper:
https://docs.typo3.org/typo3cms/ExtbaseGuide/Fluid/ViewHelper/CObject.html


How to crop pictures
^^^^^^^^^^^^^^^^^^^^

Use the default image ViewHelper configuration:
https://docs.typo3.org/typo3cms/ExtbaseGuide/Fluid/ViewHelper/Image.html


Linkvalidator configuration
^^^^^^^^^^^^^^^^^^^^^^^^^^^

This extension adds some default page TsConfig in order to config the needed fields, see:
`/Configuration/TypoScript/pageTsConfig.ts`


Preview is cropped / has overflow:hidden
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This is a problem because of the Busy Noggin Framework
(EXT:templavoila\_framework ). It adds a BE CSS file
(typo3conf/ext/templavoila\_framework/core\_templates/css/backend.css)
which sets an overflow: hidden and height: 45px to all previews. Just
copy that file, change it and add following to your pageTS:

`mod.web_txtemplavoilaM1.stylesheet = ../fileadmin/templates/be_css/backend.css`
