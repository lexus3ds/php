<?php 

// src/Security/ApiKeyAuthenticator.php
namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\AccessToken\Oidc\OidcTokenHandler;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

use Psr\Log\LoggerInterface;

class Auth1 //extends AbstractAuthenticator
 {


    public function __construct(
        private LoggerInterface $logger,
    ) {
    }


    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool
    {
        $this->logger->info("supports");
        // "auth-token" is an example of a custom, non-standard HTTP header used in this application
        return true;// $request->headers->has('auth-token');
    }

    public function authenticate(Request $request): Passport
    {
        $this->logger->info("try auth");

        // $apiToken = $request->headers->get('auth-token');
        // if (null === $apiToken) {
        //     // The token header was empty, authentication fails with HTTP Status
        //     // Code 401 "Unauthorized"
        //     throw new CustomUserMessageAuthenticationException('No API token provided');
        // }

        // implement your own logic to get the user identifier from `$apiToken`
        // e.g. by looking up a user in the database using its API key
        $userIdentifier = null;

        // new OidcTokenHandler()->getUserBadgeFrom(

        return new SelfValidatingPassport(new UserBadge("john_admin", null, ['ROLEADMIN']));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        $this->logger->info("succcc".$token.$firewallName);
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {

        $this->logger->info("faulllllllll");
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function createToken(Passport $passport, string $firewallName): TokenInterface
    {
        // read the attribute value
        return new CustomOauthToken($passport->getUser(), $passport->getAttribute('scope'));
    }
}