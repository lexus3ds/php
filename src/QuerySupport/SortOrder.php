<?php

namespace App\QuerySupport;

class SortOrder
{

    public const SORT_ASC = "asc";
    public const SORT_DESC = "desc";

    public function __construct(public string $field, public ?string $direction = SORT_DESC)
    {
    }

    public static function by(?string $field, ?string $direction = SORT_DESC): SortOrder|null
    {
        if($field==null) return null;
        return new self($field, $direction === null ? SortOrder::SORT_DESC : $direction);
    }
}