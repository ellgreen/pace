<?php

namespace EllGreen\Pace\Tests\View\Helpers;

use EllGreen\Pace\Sitemap\Page;
use EllGreen\Pace\Tests\TestCase;
use EllGreen\Pace\View\Helpers\Page as PageHelper;
use Exception;

class PageTest extends TestCase
{
    public function testGet()
    {
        $page = $this->mock(Page::class);
        $page->shouldReceive('metadata')->once()->andReturn(collect([
            'test' => $expected = 'test_value'
        ]));

        $pageHelper = new PageHelper($page);

        $this->assertSame($expected, $pageHelper->test);
    }

    public function testGetNonExistentMetadata()
    {
        $page = $this->mock(Page::class);
        $page->shouldReceive('metadata')->once()->andReturn(collect());

        $pageHelper = new PageHelper($page);

        $this->expectException(Exception::class);
        $pageHelper->test;
    }
}
