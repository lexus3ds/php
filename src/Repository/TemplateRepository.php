<?php

namespace App\Repository;

use App\Entity\Template;
use App\Filter\DataFilter;
use App\QuerySupport\SortOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class TemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private MvalRepository $valRepository)
    {
        parent::__construct($registry, Template::class);
    }

    public function getDataByFiterPaged(DataFilter $filter, int $page = 0, int $size = 10, SortOrder $sort = null): array
    {
        $queryBuilder = $this->createQueryBuilder("t");

        if ($filter->extension) {
            $queryBuilder->where('t.extension = :p_ext')->setParameter('p_ext', $filter->extension);
        }

        if ($filter->createdBy) {
            $lastWhere = $queryBuilder->andWhere('t.createdBy like :p_created_by')->setParameter('p_created_by', '%' . addcslashes($filter->createdBy, '_%') . '%');
        }

        if ($sort && $sort->field && $sort->direction) {
            $queryBuilder->orderBy('t.' . $sort->field, $sort->direction);
        }

        $this->addAuthWhere($filter, $queryBuilder);

        $queryBuilder->setMaxResults($size)->setFirstResult($page * $size);

        echo ($queryBuilder->getQuery()->getSQL());

        $paginator = new Paginator($queryBuilder);
        $totalResults = count($paginator);

        return [
            'content' => iterator_to_array($paginator),
            'totalElements' => $totalResults,
            'page' => $page,
            'size' => $size,
            'pages' => ceil($totalResults / $size),
        ];
    }

    private function addAuthWhere(DataFilter $filter, QueryBuilder $queryBuilder): void
    {
        if ($filter->auth) {
            $orWhere = $queryBuilder->expr()->orX();
            $i = 0;
            foreach ($filter->auth as $authFilter) {
                $andWhere = $queryBuilder->expr()->andX();
                $andWhere->add($queryBuilder->expr()->in('t.extension', $authFilter['extension']));

                $initiatorIn = [];
                $initiatorLike = [];
                foreach ($authFilter['initiator'] as $initiator) {
                    var_dump($initiator);
                    if (str_contains($initiator, '*'))
                        $initiatorLike[] = $initiator;
                    else
                        $initiatorIn[] = $initiator;
                }

                $mvalSub = 'mval' . $i++;
                $sub = $this->valRepository->createQueryBuilder($mvalSub);

                $subOr = $sub->expr()->orX();
                $initIndex = 0;
                if ($initiatorIn)
                    $subOr->add($queryBuilder->expr()->in($mvalSub . '.fieldValue', $initiatorIn));
                foreach ($initiatorLike as $initiator) {
                    $subOr->add(
                        $sub->expr()->like(
                            $mvalSub . '.fieldValue',
                            "'".str_replace('*', '%', $initiatorLike[$initIndex++])."'"
                        )
                    );
                }

                $andWhere->add(
                    $queryBuilder->expr()->in(
                        't.id',
                        $sub->select($mvalSub . '.docId')
                            ->where($subOr)
                            ->andWhere($mvalSub . '.fieldName = \'INITIATOR_CODE\'')
                            ->getDQL()
                    )
                );

                $orWhere->add($andWhere);
            }

            $queryBuilder->andWhere($orWhere);
        }
    }

}