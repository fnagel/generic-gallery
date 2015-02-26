.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt

.. _configuration:

Configuration Reference
=======================

Feel free to use the constant editor (go to: module template, root page,
constant editor, beautyofcode) to edit this settings. Add a Typoscript
template to overwrite these settings at any point in your sitetree(s).


.. _configuration-typoscript:

TypoScript Reference
--------------------

.. only:: html

   .. contents::
      :local:
      :depth: 1


Properties
^^^^^^^^^^

.. container:: ts-properties

   ===================================================== ===================================================================== ======================= ==================
   Property                                              Data type                                                             :ref:`t3tsref:stdwrap`  Default
   ===================================================== ===================================================================== ======================= ==================
   view.templateRootPath_                                              :ref:`t3tsref:data-type-string`                                       no                      :code:`EXT:beautyofcode/Resources/Public/Javascript/vendor/syntax_highlighter/v2/`
   scripts_                                              :ref:`t3tsref:data-type-string`                                       no                      :code:`scripts/`
   styles_                                               :ref:`t3tsref:data-type-string`                                       no                      :code:`styles/`
   includeAsDomReady_                                    :ref:`t3tsref:data-type-boolean`                                                              :code:`false`
   brushes_                                              :ref:`t3tsref:data-type-string`                                       no                      :code:`Xml,JScript,CSharp,Plain`
   theme_                                                :ref:`t3tsref:data-type-string`                                       no                      :code:`Default`
   showLabel_                                            :ref:`t3tsref:data-type-boolean`                                                              :code:`1`
   defaults.tab-size_                                    :ref:`t3tsref:data-type-integer`                                                              :code:`4`
   defaults.gutter_                                      :ref:`t3tsref:data-type-boolean`                                                              :code:`1`
   defaults.collapse_                                    :ref:`t3tsref:data-type-boolean`                                                              :code:`0`
   ===================================================== ===================================================================== ======================= ==================


Property details
^^^^^^^^^^^^^^^^

.. only:: html

   .. contents::
      :local:
      :depth: 1



The TypoScript coniguration defines the available gallery types in BE.
You will need a new array for each gallery.



.. _ts-plugin-tx-beautyofcode-baseUrl:

baseUrl
"""""""

:typoscript:`plugin.tx_beautyofcode.settings.baseUrl =` :ref:`t3tsref:data-type-string`

Enter path to the resources directory by using EXT: or FILE: or absolute path
(http://your.domain.de/fileadmin/.../res/). Make sure the relative paths res/
and styles/ exists.

Leave empty to use online repository. If you use online repository you shall
not edit settings styles and scripts.  More info about online hosting see:
http://alexgorbatchev.com/wiki/SyntaxHighlighter:Hosting

.. _ts-plugin-tx-beautyofcode-scripts:

scripts
"""""""

:typoscript:`plugin.tx_beautyofcode.settings.scripts =` :ref:`t3tsref:data-type-string`

Path to syntax highlighter core file and to the brushes (Java-Script files),
relative to the baseUrl. Leave empty or default when using online repository.


.. _ts-plugin-tx-extensionkey-substelementUid:

styles
""""""

:typoscript:`plugin.tx_beautyofcode.settings.styles =` :ref:`t3tsref:data-type-string`

Path to syntax highlighter css files (css themes), relative to the baseUrl_.
Leave empty or default when using online repository.

includeAsDomReady
"""""""""""""""""

:typoscript:`plugin.tx_beautyofcode.settings.includeAsDomReady =` :ref:`t3tsref:data-type-boolean`

If using standalone version it's possible to add a JS domReady instead of
injecting the code at the bottom of the body. Useful when using minification
scripts. Possible options: false, jquery or standalone

brushes
"""""""

:typoscript:`plugin.tx_beautyofcode.settings.brushes =` :ref:`t3tsref:data-type-string`

Loaded programming languages: Define which programming languages should be
available. Less is more: every brush is lazy loaded a single js file.

**SyntaxHighlighter:** Add a separated list out of: AS3, Bash, ColdFusion, Cpp,
CSharp, Css, Delphi, Diff, Erlang, Groovy, Java, JavaFX, JScript, Perl, Php,
PowerShell, Python, Ruby, Scala, Typoscript, Sql, Vb, Xml.

**Prism:** Add a separated list out of: markup, clike, csharp, javascript, plain,
bash, c, coffeescript, cpp, css, gherkin, go, groovy, http, java, php, python, ruby, scss, sql, typoscript

**Prism Note #1:** Prism uses its brushes in a dependency manner. For example the `clike`
brush is necessary to load before `php`(and therefore must be defined *before*
`php` in the `brushes` configuration.

**Prism Note #2:** Furthermore, there currently is a bug in Prism which prevents
proper line numbering and line highlighting. We're aware of this issue and keep
an eye on this and will provide an update to Prism once this issue is fixed. For
more information please head over to the `corresponding github issue entry <PrismLineNumberingIssue_>`_.
To ensure proper functionality, you **must** provide the `markup` brush after
the `php` Prism component/brush (like in the default configuration).

theme
"""""

:typoscript:`plugin.tx_beautyofcode.settings.theme =` :ref:`t3tsref:data-type-string`

Define a theme (which is basically a CSS file). Following themes are avaiable:
Midnight, RDark, Default, Django, Eclipse, Emacs, FadeToGrey, FelixNagelv3
(which is dark minimal).

showLabel
"""""""""

:typoscript:`plugin.tx_beautyofcode.settings.showLabel =` :ref:`t3tsref:data-type-boolean`

If set to false the label is hidden.

defaults.tab-size
"""""""""""""""""

:typoscript:`plugin.tx_beautyofcode.settings.defaults.tab-size =` :ref:`t3tsref:data-type-integer`

Specify a tabulator size. Tabulator chars will be changed to spaces.

defaults.gutter
"""""""""""""""

:typoscript:`plugin.tx_beautyofcode.settings.defaults.gutter =` :ref:`t3tsref:data-type-boolean`

Show or hide gutter. Helps user to recognize correct line.

defaults.collapse
"""""""""""""""""

:typoscript:`plugin.tx_beautyofcode.settings.defaults.collapse =` :ref:`t3tsref:data-type-boolean`

Allows you to force highlighted elements on the page to be collapsed. A link
"show source" is displayed instead (not customizable yet).

.. _PrismLineNumberingIssue: https://github.com/LeaVerou/prism/issues/149