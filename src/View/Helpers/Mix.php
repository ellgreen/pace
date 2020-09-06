<?php

namespace EllGreen\Pace\View\Helpers;

use EllGreen\Pace\Structure;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

class Mix
{
    private const MANIFEST_NAME = 'mix-manifest.json';

    private Structure $structure;
    private Filesystem $filesystem;

    public function __construct(Structure $structure, Filesystem $filesystem)
    {
        $this->structure = $structure;
        $this->filesystem = $filesystem;
    }

    public function __invoke(string $path): string
    {
        $manifest = $this->manifest();

        if (! $manifest->has($path)) {
            throw new Exception("Entry does not exist in mix manifest for: {$path}");
        }

        return $manifest->get($path);
    }

    private function manifest(): Collection
    {
        $path = $this->structure->build().'/'.self::MANIFEST_NAME;

        /** @noinspection PhpParamsInspection */
        throw_unless(
            $this->filesystem->exists($path),
            Exception::class,
            "Mix manifest does not exist: {$path}"
        );

        return collect(json_decode($this->filesystem->get($path), $assoc = true));
    }
}
