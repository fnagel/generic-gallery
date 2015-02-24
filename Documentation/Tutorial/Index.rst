.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


Tutorial
--------


Custom marker (how to render any content)
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Its possible to render additional content marker. There are two
possibilities:

#. Render an specific image or an image specific field (description,
   title, etc).

#. Render any content as long as its possible by standard TypoScript
   notation.

Both cases need a marker which will be used within the templates.

**Image specific**

Needs an  *index* to specify which image should be rendered. Use
*content* to define if a image (IMG conf) or a specific generic marker
(default generic\_gallery marker including ###) should be used.

::

   plugin.tx_genericgallery_pi1.gallery.example {
           custom {
                   1 {
                           marker = ###CUSTOM_IMAGE###
                           index = 3
                           content {
                                   maxW = 666
                                   maxH = 666
                           }
                   }

                   2 {
                           marker = ###CUSTOM_IMAGE_TEXT###
                           index = 3
                           content = ###IMAGE_DESCRIPTION###
                   }
           }
   }


**Image specific**

Just use generic TypoScript within  *content.*

::

   plugin.tx_genericgallery_pi1.gallery.example {
           custom {
                   1 {
                           marker = ###CUSTOM_FCE###
                           content {
                                   example = TEXT
                                   example.value = This works perfectly!
                                   example.wrap = <p>|</p>

                                   example2 = RECORDS
                                   example2.source = 233
                                   example2.tables = tt_content
                           }
                   }
           }
   }



How to crop pictures
^^^^^^^^^^^^^^^^^^^^

Remember you are able to use common imageConf arrays, see
`http://wiki.typo3.org/TSref/imgResource
<http://wiki.typo3.org/TSref/imgResource>`_

Example URL:

::

   thumb {
           1 {
            width = 40c-0
                  height = 40c-0
         }
   }



How to define own marker (with field backfall)
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Simply add you own marker in the array and define any FAL field
available in the DB.

Please note you need to start with the prefix IMAGE\_. You are able to
define a fallback if a field is empty by using the // notation.

::

   plugin.tx_genericgallery_pi1.gallery.example {
        marker {
               IMAGE_DESCRIPTION = description // caption // alt_text // title
                IMAGE_ALT = alt_text // title
                  IMAGE_EXIF_MODEL = exif_Model
                  IMAGE_EXIF_DATE = exif_DateTime // exif_DateTimeOriginal
       }
   }



How to use AJAX functionality
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Use following URL with parameter uid (marker ###UID###) and type (json
or xml) within your Java-Script AJAX requests.

Example URL:

::

   index.php?eID=generic_gallery&uid=###UID###&type=json



How to use Exif & IPTC
^^^^^^^^^^^^^^^^^^^^^^

Currently not possible as FAL does not provide these information.


Preview is cropped / has overflow:hidden
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This is a problem because of the Busy Noggin Framework
(EXT:templavoila\_framework ). It adds a BE CSS file
(typo3conf/ext/templavoila\_framework/core\_templates/css/backend.css)
which sets an overflow: hidden and height: 45px to all previews. Just
copy that file, change it and add following to your pageTS:

::

   mod.web_txtemplavoilaM1.stylesheet = ../fileadmin/templates/be_css/backend.css

