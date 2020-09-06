<?php

namespace EllGreen\Pace\View\Helpers;

use EllGreen\Pace\Sitemap\Page as SitemapPage;
use Exception;

class Page
{
    private SitemapPage $page;

    public function __construct(SitemapPage $page)
    {
        $this->page = $page;
    }

    public function __get($name)
    {
        if (($value = $this->page->metadata()->get($name)) !== null) {
            return $value;
        }

        throw new Exception("Metadata does not exist: {$name}");
    }
}
