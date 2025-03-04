<?php

use Symfony\Component\Security\Core\User\OidcUser;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface {


    private array $roles;


    public function __construct(public OidcUser $oidcUser, public string $jwt, public array $roles_with_attrs) {

        // var_dump(($roles_with_attrs));
        // var_dump(array_values($roles_with_attrs));
        // var_dump($roles_with_attrs[0]);
        // var_dump($roles_with_attrs['PDD_TEMPLATE.CRUD'][0]['PDD_TEMPLATE.TEMPLATE_EXTENSION']);

        $this->roles = array_map(fn($role)=>"ROLE_".$role,array_keys($roles_with_attrs));
    }


    public function getRoles(): array {
        return $this->roles;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials(): void {
        
    }

    /**
     * Returns the identifier for this user (e.g. username or email address).
     *
     * @return non-empty-string
     */
    public function getUserIdentifier(): string {
        return $this->oidcUser->getUserIdentifier();
    }

}