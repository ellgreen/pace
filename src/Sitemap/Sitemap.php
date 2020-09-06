<?php

namespace EllGreen\Pace\Sitemap;

use EllGreen\Pace\Structure;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

class Sitemap
{
    private PageFactory $pageFactory;
    private Structure $structure;
    private Filesystem $filesystem;

    public function __construct(PageFactory $pageFactory, Structure $structure, Filesystem $filesystem)
    {
        $this->structure = $structure;
        $this->filesystem = $filesystem;
        $this->pageFactory = $pageFactory;
    }

    public function generate(): Collection
    {
        $pages = new Collection;

        foreach ($this->getFiles() as $file) {
            $pages->add($this->pageFactory->path($file->getPathname()));
        }

        return $pages;
    }

    private function getFiles()
    {
        return $this->filesystem->allFiles($this->structure->pages());
    }
}
