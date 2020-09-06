<?php

namespace EllGreen\Pace\Tests\Sitemap;

use EllGreen\Pace\Sitemap\PageFactory;
use EllGreen\Pace\Sitemap\Sitemap;
use EllGreen\Pace\Structure;
use EllGreen\Pace\Tests\TestCase;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;

class SitemapTest extends TestCase
{
    public function testGenerate()
    {
        $sitemap = new Sitemap(
            $pageFactory = $this->mock(PageFactory::class),
            new Structure('/root'),
            $filesystem = $this->mock(Filesystem::class)
        );

        $filesystem->shouldReceive('allFiles')->once()->andReturn([
            $file = $this->mock(SplFileInfo::class)
        ]);

        $file->shouldReceive('getPathname')->once()->andReturn($expected = 'path/to/view');
        $pageFactory->shouldReceive('path')->with($expected)->once();

        $sitemap->generate();
    }
}
