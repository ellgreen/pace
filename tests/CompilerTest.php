<?php

namespace EllGreen\Pace\Tests;

use EllGreen\Pace\Compiler;
use Illuminate\Contracts\View\Factory;
use Illuminate\Filesystem\Filesystem;

class CompilerTest extends TestCase
{
    public function testCompileWithNonExistentDirectory()
    {
        $compiler = new Compiler(
            $factory = $this->mock(Factory::class),
            $filesystem = $this->mock(Filesystem::class)
        );

        $filesystem->shouldReceive('ensureDirectoryExists')->with('path/to')->once();
        $factory->shouldReceive('make')->with('view')->once()
            ->andReturn($html = '<p>Test</p>');
        $filesystem->shouldReceive('put')->with(
            $output = 'path/to/output.xml',
            $html
        )->once();

        $compiler->compile('view', $output);
    }
}
