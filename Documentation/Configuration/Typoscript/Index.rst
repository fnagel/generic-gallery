.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt

.. _configuration-typoscript:


TypoScript Reference
=======================

Feel free to use the constant editor (go to: module template, root page,
constant editor, beautyofcode) to edit this settings. Add a Typoscript
template to overwrite these settings at any point in your sitetree(s).


TypoScript Reference
--------------------

.. contents:: Within this page
   :local:
   :depth: 1


Properties
^^^^^^^^^^

.. container:: ts-properties

   ===================================================== ===================================================================== ======================= ==================
   Property                                              Data type                                                             :ref:`t3tsref:stdwrap`  Default
   ===================================================== ===================================================================== ======================= ==================
   view.templateRootPath_                                :ref:`t3tsref:data-type-string`                                       no                      :code:`EXT:generic_gallery/Resources/Private/Templates/`
   view.partialRootPath_                                 :ref:`t3tsref:data-type-string`                                       no                      :code:`EXT:generic_gallery/Resources/Private/Partials/`
   view.layoutRootPath_                                  :ref:`t3tsref:data-type-string`                                       no                      :code:`EXT:generic_gallery/Resources/Private/Layouts/`
   settings.gallery_                                     array                                       						   no
   settings.gallery.name_                                :ref:`t3tsref:data-type-string`                                       no
   settings.gallery.template_                            :ref:`t3tsref:data-type-string`                                       no
   settings.gallery.itemTemplate_                        :ref:`t3tsref:data-type-string`                                       no                      :code:`EXT:generic_gallery/Resources/Private/Templates/GalleryItem/Show.html`
   ===================================================== ===================================================================== ======================= ==================


Property details
^^^^^^^^^^^^^^^^

.. contents::
   :local:
   :depth: 1



view.templateRootPath
"""""""""""""""""""""

:typoscript:`plugin.tx_genericgallery.view.templateRootPath =` :ref:`t3tsref:data-type-string`

Path to the templates. Overwrite these by using settings.gallery.template_ and settings.gallery.itemTemplate_.


view.partialRootPath
""""""""""""""""""""

:typoscript:`plugin.tx_genericgallery.view.partialRootPath =` :ref:`t3tsref:data-type-string`

Path to the partials.


view.layoutRootPath
"""""""""""""""""""

:typoscript:`plugin.tx_genericgallery.view.layoutRootPath =` :ref:`t3tsref:data-type-string`

Path to the layouts.


settings.gallery
""""""""""""""""

:code:`plugin.tx_genericgallery.settings.gallery` = array

Object with items predefine a gallery type. These items will be available in the plugin.


settings.gallery.name
"""""""""""""""""""""

:typoscript:`plugin.tx_genericgallery.settings.gallery.name =` :ref:`t3tsref:data-type-string`

Define a name for this gallery type.


settings.gallery.template
"""""""""""""""""""""""""

:typoscript:`plugin.tx_genericgallery.settings.gallery.template =` :ref:`t3tsref:data-type-string`

Define a template file for this gallery type.


settings.gallery.itemTemplate
"""""""""""""""""""""""""""""

:typoscript:`plugin.tx_genericgallery.settings.gallery.itemTemplate =` :ref:`t3tsref:data-type-string`

Define a detail view template file for this gallery type.
