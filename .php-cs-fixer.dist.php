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
		'no_superfluous_phpdoc_tags' => true,
		'no_empty_phpdoc' => true,
	]);

return $config;
