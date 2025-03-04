<?php

namespace App\Controller;

use App\Services\TestService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\User\OidcUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Http\Controller\SecurityTokenValueResolver;

#phpinfo();

class TestController extends AbstractController
{

    public function __construct(

    ) {
    }

    #[Route('/api/test-get')]
    #[IsGranted('ROLE_PDD_TSPEC_DICTIONARY.CRUD', message:'-------------error access--------------')]
    public function test(LoggerInterface $logger, UserInterface $user, TestService $testService,): JsonResponse
    {

        $testService->testIt();

        $logger->info("on test call roles=".json_encode($user->getRoles()));

        $logger->info("on test call ident= ".$user->getUserIdentifier());

        //$this->denyAccessUnlessGranted('ROLE_ADMINI');

        $number = random_int(0, 100);

        $data = [ 'id' => $number, 'name' => $user->getUserIdentifier().json_encode($user->getRoles())];

        return $this->json($data);
    }

    #[Route('/api/test-put/{id}',  methods:['PUT'], name: 'test api - test put')]
    public function test_put(int $id): JsonResponse
    {
        return new JsonResponse($id, 200);
    }
}