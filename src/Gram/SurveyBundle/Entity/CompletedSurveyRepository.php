<?php

namespace Gram\SurveyBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class CompletedSurveyRepository extends EntityRepository
{

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
                    INNER JOIN GramSurveyBundle:CompletedSurvey cs WITH cs.survey = ss.id
                    ORDER BY cs.createDate DESC
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
            ->addSelect('answer')
            ->addSelect('answerChoice')
            ->addSelect('choice')
            ->addSelect('question');

        $qb
            ->join('completedSurvey.answers', 'answer')
            ->join('answer.choices', 'answerChoice')
            ->join('answerChoice.choice', 'choice')
            ->join('answer.question', 'question');

        $qb->orderBy('completedSurvey.createDate', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param $filter
     * @return QueryBuilder
     */
    private function createFilterQuery($filter)
    {
        $qb = $this->createQueryBuilder('completedSurvey');
        $e = $qb->expr();
        $qb
            ->join('completedSurvey.survey', 'survey')
            ->join('survey.user', 'surveyCreator');

        if (isset($filter['user'])) {
            $qb
                ->andWhere($e->eq('completedSurvey.user', ':user'))
                ->setParameter('user', $filter['user']);
        }

        if (isset($filter['creator'])) {
            $qb
                ->andWhere($e->eq('surveyCreator.id', ':surveyCreator'))
                ->setParameter('surveyCreator', $filter['creator']);
        }

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

        $qb->select($e->count('completedSurvey.id'));

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findByIds($ids)
    {
        $qb = $this->createQueryBuilder('question');
        $qb->where($qb->expr()->in('question.id', $ids));

        return $qb->getQuery()->getResult();
    }

    public function findBySurvey($id)
    {
        $qb = $this->createQueryBuilder('completedSurvey');

        $qb
            ->addSelect('survey')
            ->addSelect('user')
            ->addSelect('individual')
            ->addSelect('answers')
            ->addSelect('question')
            ->addSelect('questionChoices')
            ->addSelect('questionChoice')
            ->addSelect('answersChoices')
            ->addSelect('answersChoice');

        $qb
            ->join('completedSurvey.survey', 'survey')
            ->join('completedSurvey.user', 'user')
            ->join('user.individual', 'individual')
            ->leftJoin('completedSurvey.answers', 'answers')
            ->leftJoin('answers.question', 'question')
            ->leftJoin('question.choices', 'questionChoices')
            ->leftJoin('questionChoices.choice', 'questionChoice')
            ->leftJoin('answers.choices', 'answersChoices')
            ->leftJoin('answersChoices.choice', 'answersChoice');

        $qb->andWhere('survey = :survey')
            ->setParameter('survey', intval($id));
        $qb
            ->orderBy('individual.lastName')
            ->orderBy('individual.firstName');

        return $qb->getQuery()->getResult();
    }

}