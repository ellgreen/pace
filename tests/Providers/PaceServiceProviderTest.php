<?php

namespace EllGreen\Pace\Tests\Providers;

use EllGreen\Pace\Providers\PaceServiceProvider;
use EllGreen\Pace\Structure;
use EllGreen\Pace\Tests\TestCase;
use Illuminate\Container\Container;
use Illuminate\Contracts\View\Factory;
use Mni\FrontYAML\Bridge\CommonMark\CommonMarkParser;
use Mni\FrontYAML\Bridge\Symfony\SymfonyYAMLParser;
use Mni\FrontYAML\Parser;

class PaceServiceProviderTest extends TestCase
{
    public function testRegister()
    {
        $container = new Container;

        PaceServiceProvider::register($container, '/root');

        $this->assertTrue($container->has(Structure::class));
        $this->assertTrue($container->has(Factory::class));
        $this->assertTrue($container->has('view'));

        $container->instance(SymfonyYAMLParser::class, $this->mock(SymfonyYAMLParser::class));
        $container->instance(CommonMarkParser::class, $this->mock(CommonMarkParser::class));
        $this->assertInstanceOf(Parser::class, $container->get(Parser::class));
    }
}
