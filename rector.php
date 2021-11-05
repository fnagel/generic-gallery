<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Ssch\TYPO3Rector\Set\Typo3SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    // Paths to refactor; solid alternative to CLI arguments
    $parameters->set(Option::PATHS, [__DIR__]);

	$containerConfigurator->import(SetList::CODING_STYLE);
	$containerConfigurator->import(SetList::CODE_QUALITY);

	$containerConfigurator->import(SetList::PHP_53);
	$containerConfigurator->import(SetList::PHP_54);
	$containerConfigurator->import(SetList::PHP_55);
	$containerConfigurator->import(SetList::PHP_56);
	$containerConfigurator->import(SetList::PHP_70);
	$containerConfigurator->import(SetList::PHP_71);
	$containerConfigurator->import(SetList::PHP_72);
	$containerConfigurator->import(SetList::PHP_73);
	$containerConfigurator->import(SetList::PHP_74);
	$containerConfigurator->import(SetList::PHP_80);

	$containerConfigurator->import(Typo3SetList::TYPO3_104);
	$containerConfigurator->import(Typo3SetList::TCA_104);
	$containerConfigurator->import(Typo3SetList::TYPO3_11);

    // Define your target version which you want to support
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_80);

    // FQN classes are not imported by default. If you don't do it manually after every Rector run, enable it by:
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);

    // This will not import root namespace classes, like \DateTime or \Exception
    $parameters->set(Option::IMPORT_SHORT_CLASSES, false);

    // This will not import classes used in PHP DocBlocks, like in /** @var \Some\Class */
    $parameters->set(Option::IMPORT_DOC_BLOCKS, false);
};
