<?php

namespace App\Entity;

use App\Repository\TermDictionaryRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: TermDictionaryRepository::class)]
#[ORM\Table(name:"TSPEC_TERM_DICT", schema:"PDD")]
class TermDictionary {
    public function __construct(
        #[ORM\Id]
        #[ORM\Column]
        public string $id,
        #[ORM\Column(name:"term_name")]
        public string $name,
        #[ORM\Column(name:"term_description")]
        public string $description,
    ) {
    }
}