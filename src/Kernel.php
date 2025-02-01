<?php

namespace App;

use Doctrine\Deprecations\Deprecation;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function __construct(string $environment, bool $debug)
    {
        parent::__construct($environment, $debug);

        // Ignore unfixable Doctrine deprecations
        Deprecation::ignoreDeprecations(
            'https://github.com/doctrine/orm/pull/11211', // The ORM changed from arrays to named data objects in 3.x, stof/doctrine-extensions-bundle (slug) still use array access
        );
    }
}
