<?php

namespace App\Routing\Loader;

use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;

/**
 * Routes loader which knows how to handle a set of Yaml files.
 */
class YamlFilesLoader extends YamlFileLoader
{
    /**
     * {@inheritdoc}
     */
    public function load($files, $type = null)
    {
        if (!is_iterable($files)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected an iterable set of files, %s given!',
                is_object($files) ? get_class($files) : gettype($files)
            ));
        }

        $collection = new RouteCollection();
        foreach ($files as $file) {
            $collection->addCollection(parent::load($file, $type));
        }

        return $collection;
    }
}
