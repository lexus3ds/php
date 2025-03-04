<?php

namespace App;

class RoleWithAttrs {

    public string $role;

    public array $attrs;

    public function __construct(string $role, array $attrs) {
        $this->role = $role;
        $this->attrs = $attrs;
    }
}