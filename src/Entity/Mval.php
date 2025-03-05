<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: MvalRepository::class)]
#[ORM\Table(name:"MVAL", schema:"PDD")]
class Mval {
    public function __construct(
        #[ORM\Id]
        #[ORM\Column]
        public string $id,

        #[ORM\Column(name:"doc_id")]
        public string $docId,

        #[ORM\Column(name:"field_name")]
        public string $fieldName,

        #[ORM\Column(name:"field_value")]
        public string $fieldValue,      
    ) {
    }
}