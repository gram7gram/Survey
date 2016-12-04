<?php

namespace Gram\SurveyBundle\Services;

use Gram\SurveyBundle\Entity\Answer;
use Gram\SurveyBundle\Entity\AnswerChoice;
use Gram\SurveyBundle\Entity\Choice;
use Gram\SurveyBundle\Entity\CompletedSurvey;
use Gram\SurveyBundle\Entity\CompletedSurveyRepository;
use Gram\SurveyBundle\Entity\Question;
use Gram\SurveyBundle\Entity\QuestionChoice;
use Gram\UserBundle\Entity\Address;
use Gram\UserBundle\Entity\Contacts;
use Gram\UserBundle\Entity\Individual;
use Gram\UserBundle\Entity\User;
use Gram\UserBundle\Services\EmailService;
use JMS\Serializer\SerializationContext;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormInterface;

class CompletedSurveyService
{
    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function createSurvey(FormInterface $form)
    {
        $trans = $this->container->get('translator');
        $em = $this->container->get('doctrine')->getManager();

        $id = $form->get('survey')->get('id')->getData();
        if (!$id) {
            throw new \Exception($trans->trans('Survey was not found', [], 'GramSurveyBundle'), 404);
        }

        $survey = $em->getRepository('GramSurveyBundle:Survey')->find($id);
        if (!$survey) {
            throw new \Exception($trans->trans('Survey was not found', [], 'GramSurveyBundle'), 404);
        }

        $em->beginTransaction();

        try {

            $user = $this->createUser($form->get('user'));

            if ($user->getId() && $survey->getId()) {
                $completedSurvey = $em->getRepository('GramSurveyBundle:CompletedSurvey')->findOneBy([
                    'user' => $user,
                    'survey' => $survey
                ]);
                if ($completedSurvey) {
                    throw new \Exception($trans->trans('Completed survey already exists', [], 'GramSurveyBundle'), 422);
                }
            }

            $completedSurvey = new CompletedSurvey();
            $completedSurvey->setUser($user);
            $completedSurvey->setSurvey($survey);

            $em->persist($completedSurvey);

            foreach ($form->get('answers') as $answer) {
                $this->createAnswer($completedSurvey, $answer);
            }

            $em->flush();

            $em->commit();


        } catch (\Exception $e) {
            $em->rollback();
            throw $e;
        }

        $this->notify($completedSurvey);

        return $completedSurvey;
    }

    private function createUser(FormInterface $userForm)
    {
        $em = $this->container->get('doctrine')->getManager();
        $trans = $this->container->get('translator');
        $repo = $em->getRepository('GramUserBundle:User');

        $id = $userForm->get('id')->getData();
        if ($id) {
            $user = $repo->find($id);
            if (!$user) {
                throw new \Exception($trans->trans('User was not found', [], 'GramSurveyBundle'), 404);
            }
        } else {
            $um = $this->container->get('fos_user.user_manager');
            /** @var User $user */
            $user = $um->createUser();
            $user->setEmail($userForm->get('email')->getData());
            $user->setUsername($user->getEmail());
            $user->setPassword(md5(uniqid()));
            $user->setEnabled(false);

            $existingUser = $um->findUserByEmail($user->getEmail());
            if ($existingUser) {
                throw new \Exception($trans->trans('User email is already in use', [], 'GramSurveyBundle'), 422);
            }

            $individual = $this->createIndividual($userForm->get('individual'));

            $user->setIndividual($individual);

            $em->persist($user);
        }

        return $user;
    }

    private function createIndividual(FormInterface $individualForm)
    {
        $em = $this->container->get('doctrine')->getManager();
        $trans = $this->container->get('translator');
        $repo = $em->getRepository('GramUserBundle:Individual');

        $id = $individualForm->get('id')->getData();
        if ($id) {
            $individual = $repo->find($id);
            if (!$individual) {
                throw new \Exception($trans->trans('Individual was not found', [], 'GramSurveyBundle'), 404);
            }
        } else {
            $individual = new Individual();
            $individual->setFirstName($individualForm->get('firstName')->getData());
            $individual->setLastName($individualForm->get('lastName')->getData());
            $individual->setAge($individualForm->get('age')->getData());

            $address = $this->createAddress($individualForm->get('address'));
            $contacts = $this->createContacts($individualForm->get('contacts'));

            $individual->setContacts($contacts);
            $individual->setAddress($address);

            $em->persist($individual);
        }

        return $individual;
    }

    private function createAddress(FormInterface $addressForm)
    {
        $em = $this->container->get('doctrine')->getManager();
        $trans = $this->container->get('translator');
        $repo = $em->getRepository('GramUserBundle:Address');

        $id = $addressForm->get('id')->getData();
        if ($id) {
            $address = $repo->find($id);
            if (!$address) {
                throw new \Exception($trans->trans('Address was not found', [], 'GramSurveyBundle'), 404);
            }
        } else {
            $address = new Address();
            $address->setCity($addressForm->get('city')->getData());
            $em->persist($address);
        }

        return $address;
    }

    private function createContacts(FormInterface $contactsForm)
    {
        $em = $this->container->get('doctrine')->getManager();
        $trans = $this->container->get('translator');
        $repo = $em->getRepository('GramUserBundle:Contacts');

        $id = $contactsForm->get('id')->getData();
        if ($id) {
            $contacts = $repo->find($id);
            if (!$contacts) {
                throw new \Exception($trans->trans('Contacts was not found', [], 'GramSurveyBundle'), 404);
            }
        } else {
            $contacts = new Contacts();
            $contacts->setMobilePhone($contactsForm->get('mobilePhone')->getData());

            $em->persist($contacts);
        }

        return $contacts;
    }

    private function createAnswer(CompletedSurvey $completedSurvey, FormInterface $answerForm)
    {
        $em = $this->container->get('doctrine')->getManager();
        $trans = $this->container->get('translator');

        $questionRepo = $em->getRepository('GramSurveyBundle:Question');
        $answerData = $answerForm->getData();

        $questionArr = $answerData['question'];
        $question = $questionRepo->find($questionArr['id']);
        if (!$question) {
            throw new \Exception($trans->trans('Question was not found', [], 'GramSurveyBundle'), 404);
        }

        $answer = new Answer();
        $answer
            ->setQuestion($question)
            ->setCompletedSurvey($completedSurvey);

        $em->persist($answer);

        foreach ($answerForm->get('choices') as $choice) {
            $this->createChoice($answer, $choice);
        }

        $completedSurvey->addAnswer($answer);

        $em->persist($completedSurvey);
    }

    private function createChoice(Answer $answer, FormInterface $choiceForm)
    {
        $em = $this->container->get('doctrine')->getManager();
        $trans = $this->container->get('translator');

        $choiceRepo = $em->getRepository('GramSurveyBundle:Choice');
        $questionChoiceRepo = $em->getRepository('GramSurveyBundle:QuestionChoice');

        $choiceData = $choiceForm->getData();
        if (isset($choiceData['id'])) {
            $choice = $choiceRepo->find($choiceData['id']);
            if (!$choice) {
                throw new \Exception($trans->trans('Question choice was not found', [], 'GramSurveyBundle'), 404);
            }
        } elseif (isset($choiceData['name'])) {
            $choice = new Choice();
            $choice->setName($choiceData['name']);

            $em->persist($choice);
        } else {
            throw new \Exception($trans->trans('Question choice was not found', [], 'GramSurveyBundle'), 422);
        }

        $answerChoice = new AnswerChoice();
        $answerChoice
            ->setAnswer($answer)
            ->setChoice($choice)
            ->setIsRespondentAnswer(!$choice->getId());

        $question = $answer->getQuestion();

        if (!$question->isRespondentAnswerAllowed() && $answerChoice->isRespondentAnswer()) {
            throw new \Exception($trans->trans('Responent answer is not allowed in question', [
                '%QUESTION%' => $question->getName()
            ], 'GramSurveyBundle'), 422);
        }

        $questionChoice = !$answerChoice->isRespondentAnswer()
            ? $questionChoiceRepo->findOneBy([
                'question' => $answer->getQuestion()->getId(),
                'choice' => $choice->getId(),
                'isRespondentAnswer' => false
            ])
            : false;

        if (!$questionChoice) {
            $questionChoice = new QuestionChoice();
            $questionChoice->setChoice($choice)
                ->setQuestion($answer->getQuestion())
                ->setIsRespondentAnswer($answerChoice->isRespondentAnswer());

            $em->persist($questionChoice);
        }

        $answer->addChoice($answerChoice);
        $em->persist($answerChoice);
    }

    public function findAll($filter, $page, $limit)
    {
        $em = $this->container->get('doctrine')->getManager();
        /** @var CompletedSurveyRepository $repo */
        $repo = $em->getRepository('GramSurveyBundle:CompletedSurvey');

        $entities = $repo->findByFilter($filter, $page, $limit);

        return $entities;
    }

    public function countAll($filter)
    {
        $em = $this->container->get('doctrine')->getManager();
        /** @var CompletedSurveyRepository $repo */
        $repo = $em->getRepository('GramSurveyBundle:CompletedSurvey');

        $amount = $repo->countByFilter($filter);

        return $amount;
    }

    public function serialize($survey)
    {
        $serializer = $this->container->get('serializer');

        $content = $serializer->serialize($survey, 'json',
            SerializationContext::create()
                ->setGroups(['spa_v1_completed_survey'])
                ->enableMaxDepthChecks());

        $surveyArr = json_decode($content, true);

        return $surveyArr;
    }

    public function notify(CompletedSurvey $entity)
    {

        $recipients = $this->container->getParameter('survey_recipients');
        $emailer = $this->container->get('user.email_service');
        $trans = $this->container->get('translator');
        $temp = $this->container->get('templating');
        $completedSurveyService = $this->container->get('survey.completed_survey_rest_service');

        $survey = $completedSurveyService->serialize($entity);
        $html = $temp->render('@GramSurvey/email/survey.html.twig', [
            'completedSurvey' => $survey,
        ]);

        $emailer->send($recipients, EmailService::DEFAULT_TEMPLATE, [
            'subject' => $trans->trans('Report survey email title', [], 'GramSurveyBundle'),
            'html' => $html,
        ]);
    }

}