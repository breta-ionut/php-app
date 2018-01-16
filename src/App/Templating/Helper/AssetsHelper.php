<?php

namespace App\Templating\Helper;

use Symfony\Component\Asset\PackageInterface;
use Symfony\Component\Templating\Helper\Helper;

/**
 * The assets helper.
 */
class AssetsHelper extends Helper
{
    /**
     * The application's assets package.
     *
     * @var PackageInterface
     */
    private $package;

    /**
     * The helper constructor.
     *
     * @param PackageInterface $package
     */
    public function __construct(PackageInterface $package)
    {
        $this->package = $package;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'assets';
    }

    /**
     * Returns the absolute or the root-relative version of the given local path.
     *
     * @param string $path
     *
     * @return string
     */
    public function url(string $path): string
    {
        return $this->package->getUrl($path);
    }
}
