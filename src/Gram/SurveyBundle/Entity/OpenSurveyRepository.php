<?php

namespace Gram\SurveyBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class OpenSurveyRepository extends EntityRepository
{
    /**
     * @param array $filter
     * @param $limit
     * @param $page
     * @return array
     */
    public function findOnlySurveysByFilter(array $filter = [], $page = 0, $limit = 0)
    {
        $qb = $this->createFilterQuery($filter);
        $qb
            ->addSelect('survey')
            ->addSelect('recipient')
            ->addSelect('individual');

        $qb
            ->join('openSurvey.survey', 'survey')
            ->join('openSurvey.recipient', 'recipient');

        if ($limit > 0 && $page > 0) {
            $qb->setMaxResults($limit)
                ->setFirstResult($limit * ($page - 1));
        }

        $qb->orderBy('openSurvey.createDate', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param $filter
     * @return QueryBuilder
     */
    private function createFilterQuery($filter)
    {
        $qb = $this->createQueryBuilder('openSurvey');

        $qb
            ->join('openSurvey.creator', 'creator')
            ->join('openSurvey.survey', 'survey')
            ->join('openSurvey.recipient', 'recipient');

        if (isset($filter['user'])) {
            $qb
                ->andWhere('creator.id = :creator')
                ->setParameter('creator', $filter['user']);
        }

        if (isset($filter['recipient'])) {
            $qb
                ->andWhere('recipient.id = :recipient')
                ->setParameter('recipient', $filter['recipient']);
        }

        return $qb;
    }

    /**
     * @param array $filter
     * @param $limit
     * @param $page
     * @return array
     */
    public function findByFilter(array $filter = [], $page = 0, $limit = 0)
    {

        $uniqueIds = $this->getEntityManager()->createQueryBuilder()
            ->select('s.id')
            ->from('GramSurveyBundle:Survey', 's')
            ->where('s.id IN (
                    SELECT ss.id
                    FROM GramSurveyBundle:Survey ss
                    INNER JOIN GramSurveyBundle:OpenSurvey os WITH os.survey = ss.id
                    ORDER BY os.createDate DESC
            )')
            ->setMaxResults($limit)
            ->setFirstResult($limit * ($page - 1))
            ->getQuery()
            ->getArrayResult();

        if (!$uniqueIds) return [];

        $qb = $this->createFilterQuery($filter);

        $qb->andWhere('survey.id IN (:uniqueIds)')
            ->setParameter('uniqueIds', $uniqueIds);

        $qb
            ->addSelect('survey')
            ->addSelect('recipient')
            ->addSelect('individual');

        $qb
            ->join('recipient.individual', 'individual');

        $qb->orderBy('openSurvey.createDate', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param array $filter
     * @return array
     */
    public function countByFilter(array $filter = [])
    {
        $qb = $this->createFilterQuery($filter);
        $e = $qb->expr();

        $qb->select($e->count('openSurvey.id'));

        $qb->groupBy('survey.id');

        return count($qb->getQuery()->getResult());
    }
}