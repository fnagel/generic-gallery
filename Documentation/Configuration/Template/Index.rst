.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


Template
^^^^^^^^

There are 3 submarker available:

#. **content part** (which defines what is rendered as FE FCE output)

#. **code part** (which renders its content within a Java-Script tag in
   head or footer)

#. **files part** (in which you can add any header data like link and
   style tags or script sources)

It's possible to define rows by using more than one ###IMAGE\_1###
marker like this: ###IMAGE\_2###, ###IMAGE\_3###, … . Generic Gallery
will count the amount of image markers within the ###ROW### marker.
This functionality is available for main subparts ###CONTENT### and
###CODE###. You are able to render different ranges via TypoScript,
for example 1-1000, 2-1000 or 1-1. This enables you to render the
first image in the HTML and add all missing pictures of a gallery via
Java-Script.Please take a look at the examples within the  */res*
directory to learn how to use the templating mechanism and the
corresponding TypoScript.


Template Marker
"""""""""""""""

Please note that all marker should be wrapped with # like this:
###UID###. See demo templates ( */res/templates/* ) for examples. This
is a list of each subpart and its available marker:


TEMPLATE\_FILES
~~~~~~~~~~~~~~~

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Name
         Name:

   Description
         Description:


.. container:: table-row

   Name
         UID

   Description
         Uid of the plugin element


.. container:: table-row

   Name
         \_LOCALIZED\_UID

   Description
         Uid of the localized plugin element


.. container:: table-row

   Name
         IMAGE\_MAX\_WIDTH

   Description
         Max width of all rendered images


.. container:: table-row

   Name
         IMAGE\_MAX\_HEIGHT

   Description
         Max height of all rendered images


.. container:: table-row

   Name
         XXX

   Description
         Your custom marker. See TS option  *custom* .


.. ###### END~OF~TABLE ######


TEMPLATE\_CODE & TEMPLATE\_CONTENT
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Name
         Name:

   Description
         Description:


.. container:: table-row

   Name
         ROW

   Description
         Subpart marker for each row marker


.. container:: table-row

   Name
         UID

   Description
         Uid of the plugin element


.. container:: table-row

   Name
         \_LOCALIZED\_UID

   Description
         Uid of the localized plugin element


.. container:: table-row

   Name
         IMAGE\_MAX\_WIDTH

   Description
         Max width of all rendered images


.. container:: table-row

   Name
         IMAGE\_MAX\_HEIGHT

   Description
         Max height of all rendered images


.. container:: table-row

   Name
         XXX

   Description
         Your custom markers. See TS option  *custom* .


.. ###### END~OF~TABLE ######


ROW
~~~

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Name
         Name:

   Description
         Description:


.. container:: table-row

   Name
         IMAGE\_X

   Description
         Subpart marker for each defined image marker


.. ###### END~OF~TABLE ######


IMAGE\_1 and all following
~~~~~~~~~~~~~~~~~~~~~~~~~~

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Name
         Name:

   Description
         Description:


.. container:: table-row

   Name
         FILEPATH

   Description
         Path to the file (direct link to the original file)


.. container:: table-row

   Name
         IMAGE

   Description
         Path to the generated image file, configured by the TS option  *image*


.. container:: table-row

   Name
         IMAGE\_WIDTH

   Description
         Width of the IMAGE marker picture (uses getimagesize() to retrieve
         real diemnsions, even if maxH or maxW is used in TS)


.. container:: table-row

   Name
         IMAGE\_HEIGHT

   Description
         Height of the IMAGE marker picture


.. container:: table-row

   Name
         IMAGE\_XXX

   Description
         User defined markers (own marker, with backfall) See TS option marker.


.. container:: table-row

   Name
         XXX

   Description
         Your custom markers. See TS option  *custom* .


.. container:: table-row

   Name
         COUNT

   Description
         Index of the image (starting with 1)


.. container:: table-row

   Name
         TXT

   Description
         RTE descriptions of the image if defined


.. container:: table-row

   Name
         TITLE

   Description
         Title of the image if defined


.. container:: table-row

   Name
         LINK

   Description
         Hyperlink of the image if defined


.. container:: table-row

   Name
         THUMB\_X

   Description
         Filepath to the thumb image


.. container:: table-row

   Name
         THUMB\_X\_WIDTH

   Description
         Width of the rendered thumb image


.. container:: table-row

   Name
         THUMB\_X\_HEIGHT

   Description
         Height of the rendered thumb image


.. ###### END~OF~TABLE ######

