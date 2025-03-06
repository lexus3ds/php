<?php

namespace App\Authority;

use Symfony\Bundle\SecurityBundle\Security;

class TemplateAuthority
{

    public function __construct(private Security $security)
    {
    }

    public function buildAuthFilters(): array
    {
        $roles_ava = array_filter(
            $this->security->getUser()->roles_with_attrs,
            fn($key): bool => str_contains($key, 'PDD_TEMPLATE'),
            ARRAY_FILTER_USE_KEY
        );

        if (array_any(array_keys($roles_ava), fn($key): bool => str_ends_with($key, 'ALL'))) {
            return [];
        }

        $authFilters = [];

        foreach ($roles_ava as $role_key => $role_combinations) {
            foreach ($role_combinations as $combination) {
                $auth_filter = [];
                foreach ($combination as $attr_name => $attr_values) {
                    switch ($attr_name) {
                        case 'PDD_TEMPLATE.TEMPLATE_EXTENSION':
                            $auth_filter['extension'] = $attr_values;
                            break;
                        case 'PDD_TEMPLATE.INITIATOR_CODE':
                            $auth_filter['initiator'] = $attr_values;
                            break;
                    }
                }
                $authFilters[] = $auth_filter;
            }
        }

        return $authFilters;
    }
}