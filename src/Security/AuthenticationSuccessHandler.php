<?php

namespace App\Security;

use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Http\AccessToken\HeaderAccessTokenExtractor;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {
        $this->logger->info("0-------------Succ Handler constructur");
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?Response
    {

        $extractor = new HeaderAccessTokenExtractor(); 

        $at = $extractor->extractAccessToken($request);

        
        $user = json_encode($token->getUser());

        $this->logger->info("request");
        $this->logger->info($request);
        $this->logger->info("token");
        $this->logger->info($at);
        $this->logger->info("user");
        $this->logger->info($user);
        $this->logger->info($token->getUser()->getUserIdentifier());

        // $token->getUser()->getRoles(); 

        // $token->getUser()->setRoles(['ROLE_TEST']);

        return null;
    }
}