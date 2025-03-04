<?php


// src/Security/ApiKeyAuthenticator.php
namespace App\Security;

use App\RoleWithAttrs;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\OidcUser;
use Symfony\Component\Security\Core\User\AttributesBasedUserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Http\AccessToken\HeaderAccessTokenExtractor;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use User;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class OidcUserProvider1 implements AttributesBasedUserProviderInterface
{

    public function __construct(
        private LoggerInterface $logger, private RequestStack $requestStack, 
        private HttpClientInterface $rbacClient,
        #[Autowire('%mirror.endpoint.rbac%')]
        private string $rbacBaseUrl,

    ) {
        $this->logger->info("0-------------OIDC constructur");
    }


    public function loadUserByIdentifier(string $identifier, array $attributes = []): UserInterface
    {
        $extractor = new HeaderAccessTokenExtractor(); 

        $at = $extractor->extractAccessToken($this->requestStack->getCurrentRequest());

        $this->logger->info("ident".$identifier.json_encode($attributes).$at);

        $this->logger->info("ident".$identifier.json_encode($attributes).$at);

        $request = $this->rbacClient->request("GET", $this->rbacBaseUrl.'/user/roles-with-attributes/map', ['auth_bearer' => $at]);

        $roles_with_attrs = $request->toArray();
        
        $this->logger->info(json_encode($roles_with_attrs));
       
        $user = new User(new OidcUser($attributes['preferred_username'], $attributes['realm_access']['roles'], $attributes['sub']), $at, roles_with_attrs: $roles_with_attrs);
        
        $this->logger->info(json_encode($user->getRoles()));
        $this->logger->info(json_encode($user->oidcUser->getRoles()));
       
        return $user;
    }

    public function refreshUser(UserInterface $user): UserInterface {
        
        $this->logger->info("0----WARNING UserRefresh");
        if(($x=1)===1)
            throw new Exception("0----WARNING UserRefresh");
        return $user;
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class): bool {
        $this->logger->info("0----support call in provider ".$class);
        return true;
    }
}