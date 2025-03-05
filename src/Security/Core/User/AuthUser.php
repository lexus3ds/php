<?php

namespace App\Core\User;

use Symfony\Component\Security\Core\User\OidcUser;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthUser implements UserInterface {


    private array $roles;


    public function __construct(public OidcUser $oidcUser, public string $jwt, public array $roles_with_attrs) {
        $this->roles = array_map(fn($role)=>"ROLE_".$role,array_keys($roles_with_attrs));
    }


    public function getRoles(): array {
        return $this->roles;
    }

    public function eraseCredentials(): void {
        
    }

    public function getUserIdentifier(): string {
        //сейчас имя пользователя через Oidc, который настроен на preferred_username
        return $this->oidcUser->getUserIdentifier();
    }

}