<?php

namespace App\Security;

use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\AccessToken\Oidc\OidcTokenHandler;
use Symfony\Component\Security\Http\AccessToken\Oidc\OidcUserInfoTokenHandler;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AccessTokenHandler implements AccessTokenHandlerInterface {

    public function __construct(
        private LoggerInterface $logger,
    ) {
        $this->logger->info("0-------------TokenHandler constructur");
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $this->logger->info(sprintf("%s", $accessToken));

        return new UserBadge(
            "test", null, ['ROLE']
            //fn (string $userIdentifier) => new User($userIdentifier, $payload->getRoles())
        );
    }
}