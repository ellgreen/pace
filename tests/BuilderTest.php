<?php

namespace EllGreen\Pace\Tests;

use EllGreen\Pace\Builder;
use EllGreen\Pace\Compiler;
use EllGreen\Pace\Structure;
use Illuminate\Filesystem\Filesystem;
use Mockery\MockInterface;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

class BuilderTest extends TestCase
{
    private vfsStreamDirectory $vfs;
    private Builder $builder;
    private MockInterface $compiler;
    private Structure $structure;
    private Filesystem $filesystem;

    protected function setUp(): void
    {
        $this->vfs = vfsStream::setup();

        $this->builder = new Builder(
            $this->compiler = $this->mock(Compiler::class),
            $this->structure = new Structure($this->vfs->url()),
            $this->filesystem = new Filesystem
        );

        vfsStream::copyFromFileSystem(__DIR__.'/data', $this->vfs);
    }

    public function testBuild()
    {
        $this->compiler
            ->shouldReceive('compile')
            ->with('pages.index', $this->structure->build().'/index.html')
            ->once();

        $this->compiler
            ->shouldReceive('compile')
            ->with('pages.about', $this->structure->build().'/about/index.html')
            ->once();

        $this->compiler
            ->shouldReceive('compile')
            ->with('pages.contact.index', $this->structure->build().'/contact/index.html')
            ->once();

        $this->compiler
            ->shouldReceive('compile')
            ->with('pages.contact.map', $this->structure->build().'/contact/map/index.html')
            ->once();

        $this->builder->build();
    }
}
