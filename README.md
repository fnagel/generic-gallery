Generic Gallery
===============

TYPO3 CMS Extension Generic Gallery (Extbase / Fluid rewrite)

_beta quality_


Features
--------

* Extbase / Fluid powered (NEW)
* Better FAL support (NEW)
* Image detail view (NEW)
* Single item support
* multiple images support
* image collection support (NEW)
* Twitter Bootstrap Examples


Installation
------------

* Install via EM
* Add static TS
* Configure TS and templates as you wish


Migration from pibase to extbase / fluid version
------------------------------------------------

* Run update script in EM
* Change TypoScript path
	* from `plugin.tx_genericgallery_pi1.gallery`
	* to `plugin.tx_genericgallery.settings.gallery`
* Adjust TypoScript gallery settings
* Update your templates to fluid structure

_See demos:_ `Configuration/TypoScript/Examples`


Roadmap
-------

* Fix some ToDo's
* Release v1.0.0 by pushing a tag (Gizzle Github Hook)


ToDo
----
* Test examples again after moving repo
* Inline RTE fails sometimes, incorrect buttonset
* Ensure TYPO3 CMS 7.0 compatibility
* Add documentation
* Fix inline @ToDo's


Future ideas
------------

* Add collection preview in BE
* Add TS type preview in BE
* Add unit tests
* Disable textItem position and width fields via TER or TS
