<?php

declare(strict_types=1);

use FelixNagel\GenericGallery\Index\Indexing\GalleryContentType;
use Lochmueller\Index\Indexing\Database\ContentType\ContentTypeInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $configurator, ContainerBuilder $containerBuilder) {
    // Search indexing integration needs EXT:index (package lochmueller/index)
    if (!interface_exists(ContentTypeInterface::class)) {
        $containerBuilder->removeDefinition(GalleryContentType::class);
    }
};
