<?php

namespace EllGreen\Pace\View;

use EllGreen\Pace\Structure;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Mix
{
    private const MANIFEST_NAME = 'mix-manifest.json';

    private Structure $structure;

    public function __construct(Structure $structure)
    {
        $this->structure = $structure;
    }

    public function path(string $path, string $buildPath): string
    {
        $manifestPath = Str::of($this->structure->path($buildPath))
            ->finish('/')
            ->append(self::MANIFEST_NAME);


        $entry = $this->manifest($manifestPath)->filter(function ($value, $key) use ($path) {
           if ($key === $path) {
               return true;
           }

           return $key === '/'.$path;
        })->first();

        throw_unless(
            isset($entry),
            Exception::class,
            "Entry does not exist in mix manifest for: {$path}"
        );

        return $entry;
    }


    public function manifest(string $path): Collection
    {
        throw_unless(
            file_exists($path),
            Exception::class,
            "Mix manifest does not exist: {$path}"
        );

        return collect(json_decode(file_get_contents($path), $assoc = true));
    }
}
