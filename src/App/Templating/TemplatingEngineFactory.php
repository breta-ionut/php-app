<?php

namespace App\Templating;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Templating\Helper\SlotsHelper;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;

/**
 * The app's templating engine factory.
 */
class TemplatingEngineFactory
{
    /**
     * Instantiates the app's templating engine.
     *
     * @param string $projectDir
     *
     * @return EngineInterface
     */
    public static function factory(string $projectDir): EngineInterface
    {
        $loader = new FilesystemLoader([$projectDir.'/templates/%name%']);
        $engine = new PhpEngine(new TemplateNameParser(), $loader, [new SlotsHelper()]);

        return $engine;
    }
}
