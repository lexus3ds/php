<?php

namespace App\Filter;

class DataFilter
{
    public function __construct(public ?string $extension, public ?string $createdBy, public ?array $auth)
    {
    }
}