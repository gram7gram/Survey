<?php

namespace Gram\SurveyBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Gram\SurveyBundle\Entity\CompletedSurvey;
use Gram\UserBundle\Services\EmailService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CompletedSurveyListener
{

    /** @var ContainerInterface */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if (!($entity instanceof CompletedSurvey)) return;

        $recipients = $this->container->getParameter('survey_recipients');
        $emailer = $this->container->get('user.email_service');
        $trans = $this->container->get('translator');
        $temp = $this->container->get('templating');

        $html = $temp->render('@GramSurvey/email/survey.html.twig', [
            'completedSurvey' => $entity,
        ]);

        file_put_contents('/tmp/' . md5(uniqid()) . '.html', $html);

        $emailer->send($recipients, EmailService::DEFAULT_TEMPLATE, [
            'subject' => $trans->trans('Report survey email title', [], 'GramSurveyBundle'),
            'html' => $html,
        ]);
    }
}