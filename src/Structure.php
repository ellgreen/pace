<?php

namespace EllGreen\Pace;

use Exception;

class Structure
{
    private string $root;
    private string $buildDir;

    public function __construct(string $root)
    {
        $this->root = rtrim($root, '/');
    }

    public function path(string $relativePath = null)
    {
        $path = $this->root;

        if (isset($relativePath)) {
            $path .= '/'.rtrim($relativePath);
        }

        return $path;
    }

    public function resources()
    {
        return $this->path('resources');
    }

    public function views()
    {
        return $this->resources().'/views';
    }

    public function pages()
    {
        return $this->views().'/pages';
    }

    public function bootstrap()
    {
        return $this->path('bootstrap');
    }

    public function cache()
    {
        return $this->bootstrap().'/cache';
    }

    public function cachedViews()
    {
        return $this->cache().'/views';
    }

    public function build($fullPath = true)
    {
        if (! isset($this->buildDir)) {
            throw new Exception('Build directory has not been defined');
        }

        if (! $fullPath) {
            return $this->buildDir;
        }

        return $this->path($this->buildDir);
    }

    public function setBuildDir(string $buildPath)
    {
        $this->buildDir = rtrim($buildPath, '/');
    }
}
