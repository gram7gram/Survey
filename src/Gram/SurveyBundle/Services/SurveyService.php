<?php

namespace Gram\SurveyBundle\Services;

use Gram\SurveyBundle\Entity\Choice;
use Gram\SurveyBundle\Entity\Question;
use Gram\SurveyBundle\Entity\QuestionChoice;
use Gram\SurveyBundle\Entity\Survey;
use Gram\SurveyBundle\Entity\SurveyRepository;
use Gram\SurveyBundle\Entity\SurveyStatus;
use Gram\SurveyBundle\Entity\SurveyType;
use Gram\UserBundle\Entity\User;
use JMS\Serializer\SerializationContext;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class SurveyService
{
    const SURVEY_FOR_ALL = 'all';

    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function createSurvey(FormInterface $form, Request $request = null)
    {

        $survey = new Survey();;

        $this->updateSurvey($survey, $form, $request);

        return $survey;
    }

    public function updateSurvey(Survey $survey, FormInterface $form, Request $request = null)
    {
        $em = $this->container->get('doctrine')->getManager();

        $canBeEdited = !$survey->getId();

        $em->beginTransaction();

        try {

            $this->cleaup($survey, $form);

            $em->commit();

            $em->beginTransaction();

            if ($canBeEdited) {
                if ($form->has('name')) {
                    $survey->setName($form->get('name')->getData());
                }

                if ($form->has('description')) {
                    $survey->setDescription($form->get('description')->getData());
                }

                if ($form->has('questions')) {
                    $this->createQuestions($survey, $form->get('questions'));
                }
            }

            $em->flush();
            $em->commit();

        } catch (\Exception $e) {
            $em->rollback();
            throw $e;
        }

        $em->refresh($survey);
    }

    private function cleaup(Survey $survey, FormInterface $surveyForm)
    {
        $em = $this->container->get('doctrine')->getManager();
        $questions = $survey->getQuestions()->toArray();

        $formQuestionIds = [];
        $formQuestionChoiceIds = [];
        /** @var FormInterface $questionForm */
        foreach ($surveyForm->get('questions') as $questionForm) {
            if ($questionForm->has('id')) {
                $formQuestionIds[] = intval($questionForm->get('id')->getData());
            }

            /** @var FormInterface $choiceForm */
            foreach ($questionForm->get('choices') as $choiceForm) {
                if ($choiceForm->has('id')) {
                    $formQuestionChoiceIds[] = intval($choiceForm->get('id')->getData());
                }
            }
        }

        $survey->getQuestions()->clear();

        /** @var Question $question */
        foreach ($questions as $question) {
            $choices = $question->getChoices()->toArray();
            $question->getChoices()->clear();

            /** @var QuestionChoice $questionChoice */
            foreach ($choices as $questionChoice) {
                if (in_array(intval($questionChoice->getChoice()->getId()), $formQuestionChoiceIds)) continue;

                $em->remove($questionChoice->getChoice());
                $em->remove($questionChoice);
            }

        }
        $em->flush();

        /** @var Question $question */
        foreach ($questions as $question) {
            if (in_array(intval($question->getId()), $formQuestionIds)) continue;

            $em->remove($question);
        }
        $em->flush();
    }

    private function createQuestions(Survey $survey, FormInterface $questionsForm)
    {
        $em = $this->container->get('doctrine')->getManager();
        $trans = $this->container->get('translator');

        $questionTypeRepo = $em->getRepository('GramSurveyBundle:QuestionType');

        $questions = [];

        foreach ($questionsForm as $questionForm) {

            $questiontypeArr = $questionForm->get('type')->getData();
            if (!isset($questiontypeArr['id'])) {
                throw new \Exception($trans->trans('Question type was not found', [], 'GramSurveyBundle'), 400);
            }
            $questionType = $questionTypeRepo->find($questiontypeArr['id']);
            if (!$questionType) {
                throw new \Exception($trans->trans('Question type was not found', [], 'GramSurveyBundle'), 404);
            }

            $questionData = $questionForm->getData();
            if (!isset($questionData['name'])) {
                throw new \Exception($trans->trans('Question was not found', [], 'GramSurveyBundle'), 400);
            }

            $question = new Question();
            $question
                ->setType($questionType)
                ->setSurvey($survey)
                ->setName(trim($questionData['name']))
                ->setDescription(isset($questionData['description'])
                    ? trim($questionData['description'])
                    : null)
                ->setOrder(isset($questionData['order'])
                    ? intval($questionData['order'])
                    : 0)
                ->setIsRespondentAnswerAllowed(isset($questionData['isRespondentAnswerAllowed'])
                    ? boolval($questionData['isRespondentAnswerAllowed'])
                    : false);

            $em->persist($question);

            $this->createQuestionChoices($question, $questionForm->get('choices'));

            $em->persist($question);

            $questions[] = $question;
        }

        foreach ($questions as $question) {
            $survey->addQuestion($question);
        }

        $em->persist($survey);
    }

    private function createQuestionChoices(Question $question, FormInterface $choicesForm)
    {

        $em = $this->container->get('doctrine')->getManager();
        $trans = $this->container->get('translator');

        $choicesData = $choicesForm->getData();
        if (count($choicesData) < 2) {
            throw new \Exception($trans->trans('Question should have at least 2 options', [], 'GramSurveyBundle'), 400);
        }

        /** @var array $choiceData */
        foreach ($choicesData as $choiceData) {

            if (!isset($choiceData['name'])) {
                throw new \Exception($trans->trans('Question choice was not found', [], 'GramSurveyBundle'), 404);
            }

            $choice = new Choice();
            $choice
                ->setName(trim($choiceData['name']))
                ->setCanTerminateSurvey(isset($choiceData['canTerminateSurvey'])
                    ? boolval($choiceData['canTerminateSurvey'])
                    : false);

            $questionChoice = $this->createQuestionChoice($question, $choice);

            $em->persist($choice);

            $question->addChoice($questionChoice);
        }

        $em->persist($question);
    }

    private function createQuestionChoice(Question $question, Choice $choice)
    {
        $em = $this->container->get('doctrine')->getManager();

        $questionChoice = new QuestionChoice();
        $questionChoice
            ->setChoice($choice)
            ->setQuestion($question);

        $em->persist($questionChoice);

        return $questionChoice;
    }

    public function findAll($filter, $page, $limit)
    {
        $em = $this->container->get('doctrine')->getManager();
        /** @var SurveyRepository $repo */
        $repo = $em->getRepository('GramSurveyBundle:Survey');

        if (!isset($filter['user'])) {
            $filter['user'] = $this->getUser()->getId();
        }

        $entities = $repo->findByFilter($filter, $page, $limit);

        return $entities;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->container->get('user.user_service')->getUser();
    }

    public function findByPromocode($promocode)
    {
        $em = $this->container->get('doctrine')->getManager();
        /** @var SurveyRepository $repo */
        $repo = $em->getRepository('GramSurveyBundle:Survey');

        $entities = $repo->findOneJoinedByPromocode($promocode);

        return $entities;
    }

    public function find($id)
    {
        $em = $this->container->get('doctrine')->getManager();
        /** @var SurveyRepository $repo */
        $repo = $em->getRepository('GramSurveyBundle:Survey');

        $entities = $repo->findOneJoined($id);

        return $entities;
    }

    public function countAll($filter)
    {
        $em = $this->container->get('doctrine')->getManager();
        /** @var SurveyRepository $repo */
        $repo = $em->getRepository('GramSurveyBundle:Survey');

        if (!isset($filter['user'])) {
            $filter['user'] = $this->getUser()->getId();
        }

        $amount = $repo->countByFilter($filter);

        return $amount;
    }

    public function serialize($survey)
    {
        $serializer = $this->container->get('serializer');

        $content = $serializer->serialize($survey, 'json',
            SerializationContext::create()->setGroups(['spa_v1_survey']));

        $surveyArr = json_decode($content, true);

        return $surveyArr;
    }

}