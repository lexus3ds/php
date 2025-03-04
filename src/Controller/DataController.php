<?php

namespace App\Controller;

use App\Services\DataService;
use AuthUser;
use DataFilter;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Psr\Log\LoggerInterface;

use OpenApi\Attributes as OA;

#phpinfo();

#[Route('/api')]
#[Security(name: 'Bearer')]
class DataController extends AbstractController
{

    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    #[Route('/data/filter', methods: ['POST'])]
    #[IsGranted('ROLE_PDD_TSPEC_DICTIONARY.CRUD', message: 'Ошибка доступа')]
    public function getDataByFilter(#[MapRequestPayload] DataFilter $filter, DataService $dataService, UserInterface $user, ): JsonResponse
    {

        $this->logger->info(json_encode($filter));

        $this->logmethodcall("DataController.getDataByFilter()", $user);

        $data = $dataService->getDataByFilter();

        return $this->json($data);
    }

    #[Route('/user-data', methods: ['GET'])]
    #[IsGranted('ROLE_PDD_TSPEC_DICTIONARY.CRUD', message: 'Ошибка доступа')]
    public function getUserData(DataService $dataService, UserInterface $user, ): JsonResponse
    {
        $this->logmethodcall("DataController.getUserData()", $user);

        $data = $dataService->getUserData();

        return $this->json($data);

    }


    private function logmethodcall(string $method, AuthUser $user)
    {

        $this->logger->info("0----on" . $method . "call username=" . json_encode($user->getUserIdentifier()));

        $this->logger->info("0----on" . $method . "call roles=" . json_encode($user->getRoles()));

    }
}