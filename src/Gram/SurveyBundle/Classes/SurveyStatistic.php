<?php

namespace Gram\SurveyBundle\Classes;

use Gram\SurveyBundle\Entity\Choice;
use Gram\SurveyBundle\Entity\Question;
use Gram\SurveyBundle\Entity\QuestionType;
use Gram\SurveyBundle\Entity\Survey;

class SurveyStatistic
{
    /**
     * @param Survey $entity
     * @param $statistics
     * @return array
     */
    public function countStatisticForQuestion(Survey $entity, $statistics)
    {
        $surveyArrayFormat = [];
        $statisticArray = [];
        $countRespondent = 0;

        foreach ($statistics as $statistic) {
            $statisticArray[$statistic['questionId']][$statistic['choiceId']] = [
                'count' => $statistic['countAnswer'],
            ];

            $countRespondent = $statistic['countRespondent'];
        }

        $surveyArrayFormat['id'] = $entity->getId();
        $surveyArrayFormat['createDateTime'] = $entity->getCreateDate()->format('Y-m-d H:i:s');
        $surveyArrayFormat['dateTimeStatistic'] = (new \DateTime())->format('Y-m-d H:i:s');
        $surveyArrayFormat['countRespondents'] = $countRespondent;
        $surveyArrayFormat['name'] = $entity->getName();
        $surveyArrayFormat['description'] = $entity->getDescription();

        /** @var Question $question */
        foreach ($entity->getQuestions() as $question) {
            /** @var QuestionType $type */
            $type = $question->getType();

            $totalCountAnswer = 0;
            foreach ($question->getChoices() as $choices) {
                $count = $statisticArray[$question->getId()][$choices->getChoice()->getId()]['count'];
                $totalCountAnswer += $count;
            }

            $choicesArray = [];
            foreach ($question->getChoices() as $choices) {
                /** @var Choice $choice */
                $choice = $choices->getChoice();
                $statistic = $statisticArray[$question->getId()][$choice->getId()];
                $choicesArray[] = [
                    'id' => $choice->getId(),
                    'name' => $choice->getName(),
                    'count' => $statistic['count'],
                    'percent' => $totalCountAnswer ? sprintf("%.2f", $statistic['count'] / $totalCountAnswer * 100) : 0,
                ];
            }

            $questionArray = [
                'id' => $question->getId(),
                'name' => $question->getName(),
                'description' => $question->getDescription(),
                'choices' => $choicesArray
            ];

            if ($type) {
                $questionArray['type'] = [
                    'id' => $type->getId(),
                    'name' => $type->getName(),
                    'key' => $type->getKey(),
                ];
            }

            $surveyArrayFormat['questions'][] = $questionArray;
        }

        return $surveyArrayFormat;
    }
}