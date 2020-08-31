<?php

namespace EllGreen\Pace\Tests;

use EllGreen\Pace\Structure;

class StructureTest extends TestCase
{
    public function testStructure()
    {
        $structure = new Structure('/root/');

        $this->assertSame('/root/bootstrap/cache/views', $structure->cachedViews());
    }

    public function testStructureWithTrailingSlash()
    {
        $structure = new Structure('/root/');

        $this->assertSame('/root', $structure->path());
    }
}
