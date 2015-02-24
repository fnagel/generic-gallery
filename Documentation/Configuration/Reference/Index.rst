.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


Reference
^^^^^^^^^


TypoScript
""""""""""

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property:

   Data type
         Data type:

   Description
         Description:


.. container:: table-row

   Property
         baseWrap.wrap

   Data type
         stdWrap

   Description
         Change plugin base wrap (default: <div class=”tx-genericgallery-pi1”
         /)


.. container:: table-row

   Property
         gallery.

   Data type
         array

   Description
         Array for each defined gallery; needed


.. container:: table-row

   Property
         gallery.example

   Data type
         array

   Description
         Array with gallery type option, name array as you like; needed


.. container:: table-row

   Property
         gallery.example.name

   Data type
         string

   Description
         Name of the gallery type, needed for BE selectmenu


.. container:: table-row

   Property
         gallery.example.template

   Data type
         string

   Description
         Path to template file, example: fileadmin/galleries/template.html


.. container:: table-row

   Property
         gallery.example.codeToFooter

   Data type
         boolean

   Description
         Adds JS code before ending body tag instead of header; default: 0


.. container:: table-row

   Property
         gallery.example.shuffleImages

   Data type
         Boolean

   Description
         Shuffle images (random ordering); you need to configure page caching!


.. container:: table-row

   Property
         gallery.example.marker

   Data type
         array

   Description
         User defined markers with backfall. Use a notation like
         *IMAGE\_DESCRIPTION = description // caption // alt\_text // title*
         where each is a DAM field.


.. container:: table-row

   Property
         gallery.example.custom

   Data type
         array

   Description
         Define custom markers via standard TypoScript. Plesae see FAQ “
         `Custom marker <#1.5.4.Custom%20marker%20(how%20to%20render%20any%20co
         ntent)|outline>`_ ” for more info


.. container:: table-row

   Property
         gallery.example.dateFormat

   Data type
         string

   Description
         Format date with PHP format, see
         `http://php.net/manual/function.strftime.php
         <http://php.net/manual/function.strftime.php>`_ . Used for Exif time
         stamp converting.


.. container:: table-row

   Property
         gallery.example.ajax

   Data type
         boolean

   Description
         Enable AJAX functionality for this gallery type


.. container:: table-row

   Property
         gallery.example.image

   Data type
         array

   Description
         IMG conf array, use standard TYPO3 configuration like width, height,
         maxH or maxW


.. container:: table-row

   Property
         gallery.example.thumb

   Data type
         array

   Description
         Array of generated thumbs, add as many configurations as you like


.. container:: table-row

   Property
         gallery.example.thumb.1

   Data type
         array

   Description
         IMG conf array, see above


.. container:: table-row

   Property
         gallery.example.range.content

   Data type
         string

   Description
         Define which pictures of a set should be rendered within the
         ###CONTENT### subpart marker, use notation like: 1-1000, 2-1000, 1-10


.. container:: table-row

   Property
         gallery.example.range.code

   Data type
         string

   Description
         Same as above but for subpart marker ###CODE###


.. ###### END~OF~TABLE ######

[tsref:plugin.tx\_genericgallery\_pi1]

