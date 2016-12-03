<?php

namespace Gram\SurveyBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class SurveyRepository extends EntityRepository
{
    /**
     * @param array $filter
     * @param $limit
     * @param $page
     * @return array
     */
    public function findByFilter(array $filter = [], $page = 0, $limit = 0)
    {
        $qb = $this->createFilterQuery($filter);
        $qb
            ->addSelect('question')
            ->addSelect('questionType');

        $qb
            ->join('survey.questions', 'question')
            ->join('question.type', 'questionType');

        if ($limit > 0 && $page > 0) {
            $qb->setMaxResults($limit)
                ->setFirstResult($limit * ($page - 1));
        }

        $qb->orderBy('survey.createDate', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param $filter
     * @return QueryBuilder
     */
    private function createFilterQuery($filter)
    {
        $qb = $this->createQueryBuilder('survey');

        return $qb;
    }

    /**
     * @param array $filter
     * @return array
     */
    public function countByFilter(array $filter = [])
    {
        $qb = $this->createFilterQuery($filter);
        $e = $qb->expr();

        $qb->select($e->count('survey.id'));

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param $id
     * @return Survey|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneJoined($id)
    {
        $qb = $this->createQueryBuilder('s');
        $e = $qb->expr();

        $qb
            ->addSelect('question')
            ->addSelect('questionChoices')
            ->addSelect('choice')
            ->addSelect('questionType');

        $qb
            ->join('s.questions', 'question')
            ->join('question.choices', 'questionChoices')
            ->join('questionChoices.choice', 'choice')
            ->join('question.type', 'questionType');

        $qb->where($e->eq('s.id', ':id'))
            ->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param $promocode
     * @return Survey|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneJoinedByPromocode($promocode)
    {
        $qb = $this->createQueryBuilder('s');
        $e = $qb->expr();

        $qb
            ->addSelect('question')
            ->addSelect('questionChoices')
            ->addSelect('choice')
            ->addSelect('questionType');

        $qb
            ->join('s.questions', 'question')
            ->join('question.choices', 'questionChoices')
            ->join('questionChoices.choice', 'choice')
            ->join('question.type', 'questionType');

        $qb->where($e->eq('s.promocode', ':promocode'))
            ->setParameter('promocode', $promocode);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param $id
     * @param bool $withoutRespondentAnswer
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getOrderedSurveyById($id, $withoutRespondentAnswer = true)
    {
        $qb = $this->createQueryBuilder('survey');

        $this->joinAddSelect($qb, $withoutRespondentAnswer);

        return $qb
            ->where('survey.id = :surveyId')
            ->orderBy('questions.id')
            ->addOrderBy('choices.id')
            ->setParameter('surveyId', $id)
            ->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->getOneOrNullResult();
    }

    /**
     * @param QueryBuilder $qb
     */
    private function joinAddSelect(QueryBuilder $qb)
    {
        $qb
            ->innerJoin('survey.questions', 'questions')
            ->addSelect('questions')
            ->innerJoin('questions.choices', 'choices')
            ->addSelect('choices')
            ->innerJoin('choices.choice', 'choice')
            ->addSelect('choice')
            ->innerJoin('questions.type', 'questionType')
            ->addSelect('questionType');
    }

}