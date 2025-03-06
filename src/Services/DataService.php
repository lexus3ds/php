<?php

namespace App\Services;

use App\Authority\TemplateAuthority;
use App\QuerySupport\SortOrder;
use App\Repository\TemplateRepository;
use App\Core\User\AuthUser;
use App\Filter\DataFilter;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DataService
{

    public function __construct(
        private LoggerInterface $logger,
        private Security $security,
        private TemplateRepository $templateRepository,
        private TemplateAuthority $templateAuthority,
    ) {
        $this->logger->info("0----Data Service constructor");
    }

    public function getUserData(): array
    {

        $user = $this->security->getUser();

        $this->logmethodcall("DataService.getUserData()", $user);

        if (!$this->security->isGranted("ROLE_PDD_USERTYPE.READ"))
            throw new AccessDeniedException("ERROR Acess Denied in DataService.getUserData()");

        return
            [
                'user name' => $user->getUserIdentifier(),
                'user roles ROLE_converted' => $user->getRoles(),
                'all user data' => $user
            ];

    }

    public function getDataByFilter(DataFilter $filter, int $page = 0, int $size = 10, SortOrder $sort = null): array
    {

        $this->logmethodcall("DataService.getDataByFilter()", $this->security->getUser());

        if (!$this->security->isGranted("ROLE_PDD_TEMPLATE.CRUD"))
            throw new AccessDeniedException("ERROR Acess Denied in DataService.getDataByFilter()");

        //массив полномочий для ограничений выборки
        $filter->auth = $this->templateAuthority->buildAuthFilters();

        //TODO dto transform

        return $this->templateRepository->getDataByFiterPaged($filter, $page, $size, $sort);
    }

    private function logmethodcall(string $method, AuthUser $user)
    {

        $this->logger->info("0----on" . $method . "call username=" . json_encode($user->getUserIdentifier()));

        $this->logger->info("0----on" . $method . "call roles=" . json_encode($user->getRoles()));

    }

}