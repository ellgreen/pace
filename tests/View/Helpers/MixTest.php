<?php

namespace EllGreen\Pace\Tests\View\Helpers;

use EllGreen\Pace\Structure;
use EllGreen\Pace\Tests\TestCase;
use EllGreen\Pace\View\Helpers\Mix;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Mockery\MockInterface;

class MixTest extends TestCase
{
    private Mix $mix;
    private MockInterface $filesystem;

    protected function setUp(): void
    {
        $this->mix = new Mix(
            $structure = new Structure('/root'),
            $this->filesystem = $this->mock(Filesystem::class)
        );
    }

    public function testGetManifestEntry()
    {
        $this->filesystem->shouldReceive('exists')->once()->andReturnTrue();
        $this->filesystem->shouldReceive('get')->once()->andReturn(json_encode([
            '/app.css' => $expected = '/app.css?test'
        ]));

        $this->assertSame($expected, $this->mix->__invoke('/app.css'));
    }

    public function testManifestFileDoesNotExist()
    {
        $this->filesystem->shouldReceive('exists')->once()->andReturnFalse();

        $this->expectException(Exception::class);
        $this->mix->__invoke('/app.css');
    }

    public function testManifestEntryDoesNotExist()
    {
        $this->filesystem->shouldReceive('exists')->once()->andReturnTrue();
        $this->filesystem->shouldReceive('get')->once()->andReturn(json_encode([]));

        $this->expectException(Exception::class);
        $this->mix->__invoke('/app.css');
    }
}
