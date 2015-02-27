.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


Templating
^^^^^^^^^^

.. tip::

	Since version 1.0.0 Generic Gallery uses fluid as templating engine.
	This means you will need to recreate your templates when updating from previous versions.

	It's still possible to use grouped elements. For example four items wrapped in a :code:`<div>`.
	There's a view helper for that.


.. _configuration-template:


Nothing special here, just default Fluid templating.
Take a look at the default templates and examples!



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

Use the :code:`imageData` getter for prepared image meta data: :code:`item.imageData`
Prepared means you will receive some properties in a human readable format.

Use :code:`item.image.properties` for raw FAL meta data.

.. tip::

	Which meta data is available depends on your installation and extensions.


**Example**

This examples is tested with EXT::code:`metadata`.

.. code-block:: xml

	<f:for each="{collection}" as="item" iteration="iterator">
		<figure>
			<f:image src="{item.image.uid}" alt="{item.image.properties.description}" />
			<p>
				{item.imageData.camera_model} {item.imageData.shutter_speed_value} {item.imageData.aperture_value}
				{item.imageData.focal_length} {item.imageData.iso_speed_ratings} ({item.imageData.flash})
			</p>
		</figure>
	</f:for>





