<?php

namespace EllGreen\Pace\Tests;

use EllGreen\Pace\Structure;

class StructureTest extends TestCase
{
    public function testStructure()
    {
        $structure = new Structure('/root');

        $this->assertSame('/root/bootstrap/cache/views', $structure->cachedViews());
    }

    public function testStructureWithTrailingSlash()
    {
        $structure = new Structure('/root/');

        $this->assertSame('/root', $structure->path());
    }

    public function testGetRelativeBuildDir()
    {
        $structure = new Structure('/root');

        $this->assertSame('build', $structure->build(false));
    }

    public function testGetPages()
    {
        $structure = new Structure('/root');

        $this->assertSame('/root/resources/views/pages', $structure->pages());

    }
}
