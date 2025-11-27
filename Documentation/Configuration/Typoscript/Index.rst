.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt

.. _configuration-typoscript:


TypoScript Reference
=======================

Feel free to use the constant editor (go to: module template, root page,
constant editor, generic_gallery) to edit this settings. Add a TypoScript
template to overwrite these settings at any point in your site tree(s).


TypoScript Reference
--------------------

.. contents:: Within this page
   :local:
   :depth: 1


Properties
^^^^^^^^^^

.. container:: ts-properties

   ===================================================== ===================================================================== ======================= ====================================
   Property                                              Data type                                                             :ref:`t3tsref:stdwrap`  Default
   ===================================================== ===================================================================== ======================= ====================================
   `settings.gallery`                                    array                                                                 no
   `settings.gallery.name`                               :ref:`t3tsref:data-type-string`                                       no
   `settings.gallery.icon`                               :ref:`t3tsref:data-type-string`                                       no                      `extensions-generic-gallery`
   `settings.gallery.template`                           :ref:`t3tsref:data-type-string`                                       no                      Fallback to Extbase's default behavior
   `settings.gallery.itemTemplate`                       :ref:`t3tsref:data-type-string`                                       no                      Fallback to Extbase's default behavior
   `settings.gallery.paginate`                           array                                                                 no                      Fallback to Fluid's default behavior
   ===================================================== ===================================================================== ======================= ====================================


Property details
^^^^^^^^^^^^^^^^

.. contents::
   :local:
   :depth: 1


settings.gallery
""""""""""""""""

:code:`plugin.tx_genericgallery.settings.gallery` = array

Object with items predefine a gallery type. These items will be available in the plugin.


settings.gallery.name
"""""""""""""""""""""

:typoscript:`plugin.tx_genericgallery.settings.gallery.name =` :ref:`t3tsref:data-type-string`

Define a name for this gallery type.


settings.gallery.icon
"""""""""""""""""""""

:typoscript:`plugin.tx_genericgallery.settings.gallery.icon =` :ref:`t3tsref:data-type-string`

Define a icon for this gallery type. Use registrated TYPO3 icon key.


settings.gallery.template
"""""""""""""""""""""""""

:typoscript:`plugin.tx_genericgallery.settings.gallery.template =` :ref:`t3tsref:data-type-string`

Define a template file for this gallery type.
Use something like `EXT:my_extension/Resources/Private/Templates/SomeFolder/SomeFile.html`

If not set, the Extbase's default behavior will be used:
TYPO3 uses the template root path from `plugin.tx_genericgallery.view.templateRootPaths` and adds `GalleryCollection/Show.html` to it.


settings.gallery.itemTemplate
"""""""""""""""""""""""""""""

:typoscript:`plugin.tx_genericgallery.settings.gallery.itemTemplate =` :ref:`t3tsref:data-type-string`

Define a detail view template file for this gallery type.
Use something like `EXT:my_extension/Resources/Private/Templates/SomeFolder/SomeFile.html`

If not set, the Extbase's default behavior will be used:
TYPO3 uses the template root path from `plugin.tx_genericgallery.view.templateRootPaths` and adds `GalleryItem/Show.html` to it.
