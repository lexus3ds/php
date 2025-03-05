<?php

namespace App\Controller;

use App\Core\User\AuthUser;
use App\Filter\DataFilter;
use App\QuerySupport\SortOrder;
use App\Services\DataService;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Psr\Log\LoggerInterface;

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
    #[IsGranted('ROLE_PDD_TEMPLATE.CRUD', message: 'Ошибка доступа')]
    public function getDataByFilter(
        #[MapRequestPayload] DataFilter $filter,
        #[MapQueryParameter] ?int $page,
        #[MapQueryParameter] ?int $size, 
        #[MapQueryParameter] ?string $sort,
        #[MapQueryParameter] ?string $direction,
        DataService $dataService, UserInterface $user, ): JsonResponse
    {

        $this->logger->info(json_encode($filter));

        $this->logmethodcall("DataController.getDataByFilter()", $user);

        $data = $dataService->getDataByFilter($filter, $page, $size, SortOrder::by($sort, $direction));

        return $this->json($data);
    }

    #[Route('/user-data', methods: ['GET'])]
    #[IsGranted('ROLE_PDD_USERTYPE.READ', message: 'Ошибка доступа')]
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