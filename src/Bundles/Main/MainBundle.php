<?php

namespace Bundles\Main;

use App\Bundle\SchemaProviderAwareBundleTrait;
use App\Doctrine\SchemaProvider\SchemaProviderAwareInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * The MainBundle definition.
 */
class MainBundle extends Bundle implements SchemaProviderAwareInterface
{
    use SchemaProviderAwareBundleTrait;
}
