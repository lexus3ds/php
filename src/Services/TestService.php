<?php

namespace App\Services;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class TestService {

    public function __construct(
        private LoggerInterface $logger,
        private Security $security,
    ) {
        $this->logger->info("0-------------OIDC constructur");
    }
    
    #[IsGranted('TEST_IT', message:'-------------error access--------------')]
    public function testIt() : void {
        $this->logger->info("in test");
        if(!$this->security->isGranted("ROLE_PDD_TSPEC_DICTIONARY.CRUD")) throw new AccessDeniedException("ERROR Acess Denied in test IT");
    }

}