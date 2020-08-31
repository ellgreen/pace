<?php

namespace EllGreen\Pace;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class Builder
{
    private Compiler $compiler;
    private Structure $structure;
    private Filesystem $filesystem;

    public function __construct(Compiler $compiler, Structure $structure, Filesystem $filesystem)
    {
        $this->compiler = $compiler;
        $this->structure = $structure;
        $this->filesystem = $filesystem;
    }

    public function build($pagesRelDir = ''): void
    {
        $pagesRelDir = Str::of($pagesRelDir)->ltrim('/')->rtrim('/');
        $outputDir = Str::of($this->structure->build())->finish('/');
        $pagesDir = $pagesRelDir->prepend($this->structure->pages().'/');

        foreach ($this->filesystem->files($pagesDir) as $file) {
            // Relative name of view
            // pages/index.blade.php         -> index
            // pages/contact/index.blade.php -> contact/index
            $relativeName = Str::of($file->getPathname())
                ->after($this->structure->pages().'/')
                ->beforeLast('.blade.php');

            $view = $relativeName->replace('/', '.')->prepend('pages.');
            $buildPath = $outputDir->finish('/')->append($relativeName);


            if (! $relativeName->basename()->exactly('index')) {
                $buildPath = $buildPath->append('/index');
            }

            $buildPath = $buildPath->append('.html');
            $this->compiler->compile($view, $buildPath);
        }

        foreach ($this->filesystem->directories($pagesDir) as $directory) {
            $this->build(
                Str::of($directory)->after($this->structure->pages())
            );
        }
    }
}
