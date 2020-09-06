<?php

namespace EllGreen\Pace\Tests;

use EllGreen\Pace\Builder;
use EllGreen\Pace\Compiler;
use EllGreen\Pace\Sitemap\Page;
use EllGreen\Pace\Sitemap\Sitemap;
use EllGreen\Pace\Structure;
use EllGreen\Pace\View\Helpers\Sharer;
use EllGreen\Pace\View\ViewData;
use Illuminate\Filesystem\Filesystem;
use Mockery\MockInterface;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use Symfony\Component\Finder\SplFileInfo;

class BuilderTest extends TestCase
{
    private vfsStreamDirectory $vfs;
    private Builder $builder;
    private MockInterface $compiler;
    private Structure $structure;
    private Filesystem $filesystem;
    private Sharer $sharer;
    private MockInterface $sitemap;

    protected function setUp(): void
    {
        $this->vfs = vfsStream::setup();

        $this->builder = new Builder(
            $this->compiler = $this->mock(Compiler::class),
            $this->structure = new Structure($this->vfs->url()),
            $this->filesystem = new Filesystem,
            $this->sharer = $this->mock(Sharer::class),
            $this->sitemap = $this->mock(Sitemap::class)
        );

        vfsStream::copyFromFileSystem(__DIR__.'/data', $this->vfs);
    }

    public function testCleanUpRemovesIndexHtml()
    {
        $this->assertFileExists($file = $this->structure->build().'/index.html');
        $this->builder->cleanupBuildDir();
        $this->assertFileDoesNotExist($file);
    }

    public function testCleanUpRemovesDirectory()
    {
        $this->assertDirectoryExists($dir = $this->structure->build().'/about');
        $this->builder->cleanupBuildDir();
        $this->assertFileDoesNotExist($dir);
    }

    public function testCleanUpDoesNotRemoveNonHtmlFiles()
    {
        $this->builder->cleanupBuildDir();
        $this->assertFileExists($this->structure->build().'/app.css');
        $this->assertFileExists($this->structure->build().'/app.js');
    }

    public function testBuildWithNoPages()
    {
        $this->sharer->shouldReceive('share')->once();
        $this->sitemap->shouldReceive('generate')->andReturn(collect([]));
        $this->compiler->shouldReceive('compile')->never();

        $this->builder->build();
    }

    public function testBuildCompilesPage()
    {
        $this->sharer->shouldReceive('share')->once();
        $this->sitemap->shouldReceive('generate')->andReturn(collect([
            $page = $this->mock(Page::class),
        ]));
        $this->compiler->shouldReceive('compile')->with($page)->once();

        $this->builder->build();
    }

    public function xtestBuild()
    {
        $buildPath = $this->structure->path('build_test');

        $this->compiler
            ->shouldReceive('compile')
            ->with('pages.index', $buildPath.'/index.html')
            ->once();

        $this->compiler
            ->shouldReceive('compile')
            ->with('pages.about', $buildPath.'/about/index.html')
            ->once();

        $this->compiler
            ->shouldReceive('compile')
            ->with('pages.contact.index', $buildPath.'/contact/index.html')
            ->once();

        $this->compiler
            ->shouldReceive('compile')
            ->with('pages.contact.map', $buildPath.'/contact/map/index.html')
            ->once();

        $this->builder->build('build_test');
    }
}
