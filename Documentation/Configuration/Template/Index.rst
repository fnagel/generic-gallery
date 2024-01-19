.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


Templating
^^^^^^^^^^

This extension uses Extbase / Fluid default functionality for templating, even though, there is come custom configuration to handle gallery types.

So by default, TYPO3 will search in the default template root path of the extension: `EXT:generic_gallery/Resources/Private/Templates/GalleryCollection/Show.html`

If you change the template path like this: `plugin.tx_genericgallery.view.templateRootPaths.0 = EXT:my_extension/Resources/Private/Templates/GenericGallery/`,
TYPO3 will search for `EXT:my_extension/Resources/Private/Templates/GenericGallery/GalleryCollection/Show.html`.


**How to configure the template file for each gallery type**

This extension allows to define multiple gallery types. Each gallery type needs a custom template file.
To accomplish this, there are two TypoScript settings: `template` and `templateItem` (used for the item detail view)

You can change the path for each gallery type by using the following TS:
`plugin.tx_genericgallery.settings.gallery.myGalleryType.template = EXT:my_extension/Resources/Private/Templates/SomeFolder/SomeFile.html`

See the example TypoScript files for more info!


.. tip::

   When using more than one gallery type, you should always use `template` (and `templateItem`) TS settings
   in order to configure the template file for each gallery type!



.. _configuration-template:

Available variables
"""""""""""""""""""

- `uid`: current plugin content element UID (localized)
- `galleryType`: current plugin gallery type (single, images, collection)
- `data.content`: current plugin content element data
- `data.page`: current page data
- `data.pageLayout`: current page layout
- `data.pageBackendLayout`: current page backend layout

- `item`: Single image item in detail view
- `collection`: Image collection array in list view


ViewHelpers
"""""""""""

**ForGroup**

A pair of ViewHelpers to assist you when building groups of items.


**Example**

.. code-block:: xml

	<f:for each="{color1: 'red', color2: 'green', color3: 'blue', color4: 'yellow'}" as="item" iteration="iterator">
		<gg:forGroup.begin iteration="{iterator}" max="2">
			<ul class="colors">
		</gg:forGroup.begin>

		<li class="span6" style="color: {item};>
			{item}
		</li>

		<gg:forGroup.end iteration="{iterator}" max="2">
			</ul>
		</gg:forGroup.end>
	</f:for>


Result:

.. code-block:: xml

	<ul class="colors">
		<li class="span6" style="color: red;>
			red
		</li>
		<li class="span6" style="color: green;>
			green
		</li>
	</ul>
	<ul class="colors">
		<li class="span6" style="color: blue;>
			blue
		</li>
		<li class="span6" style="color: yellow;>
			yellow
		</li>
	</ul>


FAL Metadata
""""""""""""

Use the :code:`imageData` property for image meta data. The :code:`imageData` array provides the FAL meta data merged
with inline file reference meta data. In addition :code:`imageData` processes some EXIF meta data to be more usable
(some properties are transformed into a human readable format).

Use :code:`item.image.properties` for raw FAL meta data.

.. tip::

	Which meta data is available depends on your installation and extensions.


**Example**

This examples is tested with EXT::code:`metadata`.

.. code-block:: xml

	<f:for each="{collection}" as="item" iteration="iterator">
		<figure>
			<f:image src="{item.image.uid}" alt="{item.imageData.description}" />
			<p>
				{item.imageData.camera_model} {item.imageData.shutter_speed_value} {item.imageData.aperture_value}
				{item.imageData.focal_length} {item.imageData.iso_speed_ratings} ({item.imageData.flash})
			</p>
		</figure>
	</f:for>
