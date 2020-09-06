<?php

namespace EllGreen\Pace;

use EllGreen\Pace\Sitemap\Page;
use Illuminate\Filesystem\Filesystem;

class Compiler
{
    private Filesystem $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function compile(Page $page): void
    {
        $this->filesystem->ensureDirectoryExists(dirname($page->outputPath()));

        $this->filesystem->put($page->outputPath(), $page->render());
    }
}
