.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


Updade Guide
------------

Please take a look in the ChnageLog
(typo3conf/ext/generic\_gallery/ChangeLog).



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

