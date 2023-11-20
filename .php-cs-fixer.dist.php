<?php

$config = new PhpCsFixer\Config();
$config
	->setRiskyAllowed(true)
	->setRules([
		'@PSR2' => true,
		'no_unused_imports' => true,
		'array_syntax' => [
			'syntax' => 'short',
		],
		// PHPdocs / type hinting
		'no_empty_phpdoc' => true,
        // @todo Disabled until https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/issues/4512 due to some PHP docs are needed!
        'no_superfluous_phpdoc_tags' => false,
	]);

return $config;
