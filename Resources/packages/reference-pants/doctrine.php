<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use BaksDev\Reference\Pants\Type\PantSize;
use BaksDev\Reference\Pants\Type\PantSizeType;
use Symfony\Config\DoctrineConfig;

return static function(DoctrineConfig $doctrine) {
	$doctrine->dbal()->type(PantSize::TYPE)->class(PantSizeType::class);
};