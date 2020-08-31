<?php

namespace EllGreen\Pace\Tests;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    use MockeryPHPUnitIntegration;

    public function mock(...$args): MockInterface
    {
        return Mockery::mock(...$args);
    }
}
