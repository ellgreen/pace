<?php

namespace EllGreen\Pace;

use EllGreen\Pace\Sitemap\Page;
use EllGreen\Pace\Sitemap\Sitemap;
use EllGreen\Pace\View\Helpers\Sharer;
use Illuminate\Filesystem\Filesystem;

class Builder
{
    private Compiler $compiler;
    private Structure $structure;
    private Filesystem $filesystem;
    private Sharer $sharer;
    private Sitemap $sitemap;

    public function __construct(
        Compiler $compiler,
        Structure $structure,
        Filesystem $filesystem,
        Sharer $sharer,
        Sitemap $sitemap
    ) {
        $this->compiler = $compiler;
        $this->structure = $structure;
        $this->filesystem = $filesystem;
        $this->sharer = $sharer;
        $this->sitemap = $sitemap;
    }

    public function build($buildDir = 'build'): void
    {
        $this->structure->setBuildDir($buildDir);
        $this->cleanupBuildDir();

        $this->sharer->share();

        $this->sitemap->generate()->each(function (Page $page) {
            $this->compiler->compile($page);
        });
    }

    public function cleanupBuildDir()
    {
        foreach ($this->filesystem->allFiles($this->structure->build()) as $file) {
            if ($file->getExtension() !== 'html') {
                continue;
            }

            $this->filesystem->delete($file->getPathname());

            // Delete empty directories
            if (count($this->filesystem->allFiles($file->getPath(), $hidden = true)) === 0) {
                $this->filesystem->deleteDirectory($file->getPath());
            }
        };
    }
}
