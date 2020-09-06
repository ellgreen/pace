<?php

namespace EllGreen\Pace\Sitemap;

use EllGreen\Pace\Structure;
use EllGreen\Pace\View\Helpers\Page as PageHelper;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Mni\FrontYAML\Parser;

class Page
{
    const TYPE_BLADE = 'blade.php';
    const TYPE_MARKDOWN = 'md';

    private Structure $structure;
    private Factory $factory;
    private Parser $parser;
    private Filesystem $filesystem;
    private string $path;

    private ?string $viewName = null;
    private ?string $outputPath = null;
    private ?Collection $metadata = null;

    public function __construct(
        Structure $structure,
        Factory $factory,
        Parser $parser,
        Filesystem $filesystem
    ) {
        $this->structure = $structure;
        $this->factory = $factory;
        $this->parser = $parser;
        $this->filesystem = $filesystem;
    }

    public function setPath(string $path)
    {
        $this->path = $path;
    }

    public function viewName(): ?string
    {
        if (! $this->path()->endsWith('.'.self::TYPE_BLADE)) {
            return null;
        }

        return $this->viewName ??= $this->path()
            ->after($this->structure->views())
            ->before('.')
            ->replace('/', '.')
            ->trim('.')
            ->__toString();
    }

    public function outputPath()
    {
        return $this->outputPath ??= $this->path()
            ->after($this->structure->pages())
            ->prepend($this->structure->build())
            ->before('.')
            ->finish('/index')
            ->append('.html')
            ->__toString();
    }

    public function isMarkdown(): bool
    {
        return $this->path()->endsWith('.'.self::TYPE_MARKDOWN);
    }

    public function render(): string
    {
        if ($this->isMarkdown()) {
            return $this->renderMarkdown();
        }

        return $this->factory->make($this->viewName(), [
            'page' => new PageHelper($this)
        ]);
    }

    private function renderMarkdown(): string
    {
        $fileData = $this->filesystem->get($this->path()->__toString());
        $parsed = $this->parser->parse($fileData);

        $this->metadata = collect($parsed->getYAML());

        if (isset($this->metadata()['extends'])) {
            return $this->factory->make($this->metadata()['extends'], [
                'markdown' => $parsed->getContent(),
                'page' => new PageHelper($this),
            ]);
        }

        return $parsed->getContent();
    }

    public function metadata(): Collection
    {
        if (! isset($this->metadata)) {
            throw new Exception('There is no metadata for this page.');
        }

        return $this->metadata;
    }

    public function path(): Stringable
    {
        if (! isset($this->path)) {
            throw new Exception('Page path not set.');
        }

        return Str::of($this->path);
    }
}
