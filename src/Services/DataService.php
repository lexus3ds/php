<?php

namespace App\Services;

use App\Entity\TermDictionary;
use App\Repository\TermDictionaryRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DataService {

    public function __construct(
        private LoggerInterface $logger,
        private Security $security,
        private TermDictionaryRepository $termDictionaryRepository,
    ) {
        $this->logger->info("0----Data Service constructor");
    }
    
    public function getUserData() : array {

        $user = $this->security->getUser();

        $this->logger->info("on DataService.getUserData() call roles=".json_encode($user->getRoles()));

        $this->logger->info("on DataService.getUserData() call username=".json_encode($user->getUserIdentifier()));

        if(!$this->security->isGranted("ROLE_PDD_EVALM_DICTIONARY.READ")) throw new AccessDeniedException("ERROR Acess Denied in DataService.getDataByFilter()");
        
        return 
            [ 
                'user name' => $user->getUserIdentifier(),
                'user roles ROLE_converted' => $user->getRoles(),
                'all user data' => $user 
            ];
    
    }

    public function getDataByFilter() : array {

        $user = $this->security->getUser();

        $this->logger->info("on DataService.getDataByFilter() call roles=".json_encode($user->getRoles()));

        $this->logger->info("on DataService.getDataByFilter() call username=".json_encode($user->getUserIdentifier()));

        if(!$this->security->isGranted("ROLE_PDD_TSPEC_DICTIONARY.CRUD")) throw new AccessDeniedException("ERROR Acess Denied in DataService.getDataByFilter()");
        
        return $this->termDictionaryRepository->createQueryBuilder("a")->select("term")->from(TermDictionary::class,'term')->setMaxResults(10)->getQuery()->execute();
    }

}