<?php

namespace App\Assets;

use Symfony\Component\Asset\Context\RequestStackContext;
use Symfony\Component\Asset\PackageInterface;
use Symfony\Component\Asset\PathPackage;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * The assets package factory.
 */
class PackageFactory
{
    /**
     * Creates an assets package.
     *
     * @param string       $basePath
     * @param string       $versionFormat
     * @param string|null  $version
     * @param RequestStack $requestStack
     *
     * @return PackageInterface
     */
    public static function factory(
        string $basePath,
        string $versionFormat,
        ?string $version,
        RequestStack $requestStack
    ): PackageInterface {
        if (null === $version) {
            $versionStrategy = new EmptyVersionStrategy();
        } else {
            $versionStrategy = new StaticVersionStrategy($version, $versionFormat);
        }

        $context = new RequestStackContext($requestStack);

        return new PathPackage($basePath, $versionStrategy, $context);
    }
}
