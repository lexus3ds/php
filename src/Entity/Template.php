<?php

namespace App\Entity;

use App\Repository\TemplateRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: TemplateRepository::class)]
#[ORM\Table(name:"TSPEC_TEMPLATE", schema:"PDD")]
class Template {
    public function __construct(
        #[ORM\Id]
        #[ORM\Column]
        public string $id,

        #[ORM\Column(name:"template_type")]
        public string $type,

        #[ORM\Column(name:"template_extension")]
        public string $extension,
        
        #[ORM\Column(name:"template_version")]
        public string $version,
        
        #[ORM\Column(name:"status")]
        public string $status,
        
        #[ORM\Column(name:"deleted")]
        public bool $deleted,
        
        #[ORM\Column(name:"published_by")]
        public string $publishedBy,
        
        #[ORM\Column(name:"published_date_time")]
        public DateTime $publishedWhen,
        
        #[ORM\Column(name:"created_by")]
        public string $createdBy,
        
        #[ORM\Column(name:"created_date_time")]
        public DateTime $createdWhen,
        
        #[ORM\Column(name:"changed_by")]
        public string $changedBy,
        
        #[ORM\Column(name:"changed_date_time")]
        public DateTime $changedWhen,
                                           
                                                        //    ref_structure_id
                                                        //       ref_template_id
                                                        //          section_print_order_ready
                                                        //             section_print_order_by
                                                        //                section_print_order_date_time
                                                        //                   prev_id
                                                        //                       disable_aux_sections
                                                        //                          disable_atta        
    ) {
    }
}