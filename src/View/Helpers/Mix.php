<?php

namespace EllGreen\Pace\View\Helpers;

use EllGreen\Pace\Structure;
use Exception;
use Illuminate\Support\Collection;

class Mix
{
    private const MANIFEST_NAME = 'mix-manifest.json';

    private Structure $structure;

    public function __construct(Structure $structure)
    {
        $this->structure = $structure;
    }

    public function __invoke(string $path): string
    {
        $manifestPath = $this->structure->build().'/'.self::MANIFEST_NAME;

        $entry = $this->manifest($manifestPath)->filter(function ($_, $key) use ($path) {
           if ($key === $path) {
               return true;
           }

           return $key === '/'.$path;
        })->first();

        /** @noinspection PhpParamsInspection */
        throw_unless(
            isset($entry),
            Exception::class,
            "Entry does not exist in mix manifest for: {$path}"
        );

        return $entry;
    }


    private function manifest(string $path): Collection
    {
        /** @noinspection PhpParamsInspection */
        throw_unless(
            file_exists($path),
            Exception::class,
            "Mix manifest does not exist: {$path}"
        );

        return collect(json_decode(file_get_contents($path), $assoc = true));
    }
}
