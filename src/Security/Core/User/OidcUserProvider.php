<?php

namespace App\Security\Core\User;

use AuthUser;
use Exception;
use Jose\Component\Signature\Serializer\Serializer;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\OidcUser;
use Symfony\Component\Security\Core\User\AttributesBasedUserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Http\AccessToken\HeaderAccessTokenExtractor;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class OidcUserProvider implements AttributesBasedUserProviderInterface
{

    public function __construct(
        private LoggerInterface $logger,
        private RequestStack $requestStack,
        private HttpClientInterface $rbacClient,
        #[Autowire('%mirror.endpoint.rbac%')]
        private string $rbacBaseUrl,
        private SerializerInterface $serializer

    ) {
        $this->logger->info("0----OIDC constructor call");
    }


    public function loadUserByIdentifier(string $identifier, array $attributes = []): UserInterface
    {
        $extractor = new HeaderAccessTokenExtractor();

        $at = $extractor->extractAccessToken($this->requestStack->getCurrentRequest());

        $this->logger->info("0----extracted access token " . $at);

        $this->logger->info("0----user detected " . $identifier);

        $this->logger->info("0----decoded token attributes " . json_encode($attributes));

        $this->logger->info("0----request RBAC roles for access token");
        $request = $this->rbacClient->request("GET", $this->rbacBaseUrl . '/user/roles-with-attributes/map', ['auth_bearer' => $at]);
        $roles_with_attrs = $request->toArray();
        $this->logger->info("0----RBAC roles requested " . json_encode($roles_with_attrs));

        $this->logger->info("0----prepare User instance");
        $user = new AuthUser(new OidcUser($attributes['preferred_username'], $attributes['realm_access']['roles'], ...$attributes), $at, roles_with_attrs: $roles_with_attrs);

        $this->logger->info("0----User instance with OIDCUser \n" . $this->serializer->serialize($user, 'json'));

        return $user;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {

        $this->logger->info("0----WARNING UserRefresh");
        if (($x = 1) === 1)
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
    public function supportsClass($class): bool
    {
        $this->logger->info("0----support call in provider " . $class);
        return true;
    }
}