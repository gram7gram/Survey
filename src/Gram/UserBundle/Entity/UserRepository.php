<?php

namespace Gram\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class UserRepository extends EntityRepository
{
    public function findByFilter(array $filter = [], $page = 0, $limit = 0)
    {
        $qb = $this->createFilterQuery($filter);

        if ($limit > 0 && $page > 0) {
            $qb->setMaxResults($limit)
                ->setFirstResult($limit * ($page - 1));
        }

        $qb
            ->orderBy('individual.lastName', 'DESC')
            ->addOrderBy('individual.firstName', 'DESC');

        return $qb->getQuery()->getResult();
    }

    private function createFilterQuery(array $filter = [])
    {
        $qb = $this->createQueryBuilder('u');
        $e = $qb->expr();

        $qb
            ->join('u.individual', 'individual');

        if (isset($filter['search'])) {
            $qb
                ->andWhere(
                    $e->orX(
                        $e->like($e->lower('individual.lastName'), ':q'),
                        $e->like($e->lower('individual.firstName'), ':q')
                    )

                )
                ->setParameter(':q', '%' . mb_strtolower(trim($filter['search']), 'UTF-8') . '%');
        }

        $qb->andWhere('u.enabled = TRUE');

        return $qb;
    }

    public function countByFilter(array $filter = [])
    {
        $qb = $this->createFilterQuery($filter);
        $e = $qb->expr();

        $qb->select($e->count('u.id'));

        return $qb->getQuery()->getSingleScalarResult();
    }


}
