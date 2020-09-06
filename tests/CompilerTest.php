<?php

namespace EllGreen\Pace\Tests;

use EllGreen\Pace\Compiler;
use EllGreen\Pace\Sitemap\Page;
use Illuminate\Filesystem\Filesystem;
use Mockery\MockInterface;

class CompilerTest extends TestCase
{
    public function testCompileWithNonExistentDirectory()
    {
        $compiler = new Compiler($filesystem = $this->mock(Filesystem::class));

        $page = tap($this->mock(Page::class), function (MockInterface $page) {
            $page->shouldReceive('outputPath')
                ->andReturn('path/to/output')
                ->twice();

            $page->shouldReceive('render')->andReturn('<p>Test</p>')->once();
        });

        $filesystem->shouldReceive('ensureDirectoryExists')->with('path/to')->once();
        $filesystem->shouldReceive('put')->with('path/to/output', '<p>Test</p>');

        $compiler->compile($page);
    }
}
