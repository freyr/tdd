<?php

declare(strict_types=1);

namespace Freyr\TDD\Domain;

class Product
{
    public int $id;
    public string $name;

    public function __construct($id, $name)
    {
        $this->id = (int)$id;
        $this->name = $name;
    }
}