<?php

namespace EllGreen\Pace;

use EllGreen\Pace\View\Helpers\Sharer;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class Builder
{
    private Compiler $compiler;
    private Structure $structure;
    private Filesystem $filesystem;
    private Sharer $sharer;

    public function __construct(
        Compiler $compiler,
        Structure $structure,
        Filesystem $filesystem,
        Sharer $sharer
    ) {
        $this->compiler = $compiler;
        $this->structure = $structure;
        $this->filesystem = $filesystem;
        $this->sharer = $sharer;
    }

    public function build($buildDir = 'build'): void
    {
        $this->structure->setBuildDir($buildDir);

        $this->sharer->share();

        $this->buildPages();
    }

    public function buildPages($pagesRelDir = '')
    {
        $pagesRelDir = Str::of($pagesRelDir)->ltrim('/')->rtrim('/');
        $outputDir = Str::of($this->structure->build());
        $pagesDir = $pagesRelDir->prepend($this->structure->pages().'/');

        foreach ($this->filesystem->files($pagesDir) as $file) {
            // Relative name of view
            // pages/index.blade.php         -> index
            // pages/contact/index.blade.php -> contact/index
            $relativeName = Str::of($file->getPathname())
                ->after($this->structure->pages().'/')
                ->beforeLast('.blade.php');

            $view = $relativeName->replace('/', '.')->prepend('pages.');
            $outputPath = $outputDir->finish('/')->append($relativeName);

            if (! $relativeName->basename()->exactly('index')) {
                $outputPath = $outputPath->append('/index');
            }

            $outputPath = $outputPath->append('.html');
            $this->compiler->compile($view, $outputPath);
        }

        foreach ($this->filesystem->directories($pagesDir) as $directory) {
            $this->buildPages(Str::of($directory)->after($this->structure->pages()));
        }
    }
}
