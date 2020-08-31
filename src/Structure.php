<?php

namespace EllGreen\Pace;

class Structure
{
    public string $root;

    public function __construct(string $root)
    {
        $this->root = rtrim($root, '/');
    }

    public function resources()
    {
        return $this->root.'/resources';
    }

    public function views()
    {
        return $this->resources().'/views';
    }

    public function pages()
    {
        return $this->views().'/pages';
    }

    public function build()
    {
        return $this->root.'/build';
    }

    public function buildProd()
    {
        return $this->root.'/build_prod';
    }

    public function bootstrap()
    {
        return $this->root.'/bootstrap';
    }

    public function cache()
    {
        return $this->bootstrap().'/cache';
    }

    public function cachedViews()
    {
        return $this->cache().'/views';
    }
}
