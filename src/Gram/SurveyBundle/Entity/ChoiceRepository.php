<?php

namespace Gram\SurveyBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class ChoiceRepository extends EntityRepository
{
    /**
     * @param $surveyId
     * @return array
     */
    public function countAnswerStatistic($surveyId)
    {
        $qb = $this->createQueryBuilder('choice');

        $qb
            ->select('choice.id as choiceId')
            ->addSelect('choice.name as choiceName')
            ->addSelect('question.id as questionId')
            ->addSelect('question.name as questionName')
            ->addSelect('(' . $this->countAnswer() . ') AS countAnswer')
            ->addSelect('(' . $this->countRespondent() . ') AS countRespondent')
            ->join('choice.questionChoice', 'questionChoice')
            ->join('questionChoice.question', 'question')
            ->join('question.survey', 'survey')
            ->where('survey.id = :surveyId')
            ->setParameter('surveyId', $surveyId);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return string
     */
    private function countAnswer()
    {
        return "SELECT count(c.id)
        FROM GramSurveyBundle:Choice c
        INNER JOIN GramSurveyBundle:AnswerChoice ac WITH c.id = ac.choice
        INNER JOIN GramSurveyBundle:Answer a WITH ac.answer = a.id
        WHERE a.question = question.id AND c.id = choice.id
        ";
    }

    private function countRespondent()
    {
        return "SELECT count(cs.id)
        FROM GramSurveyBundle:CompletedSurvey cs
        INNER JOIN GramSurveyBundle:Survey sur WITH sur.id = cs.survey
        WHERE sur.id = survey.id
        ";
    }
}
