<?php

namespace Gram\UserBundle\Services;

use Gram\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EmailService
{
    const DEFAULT_TEMPLATE = 'default';

    /**
     * @var ContainerInterface $container
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function send(array $recipients, $template = self::DEFAULT_TEMPLATE, $config = [])
    {
        if (!$config || !isset($config['from'])) {
            $emailFrom = $this->container->getParameter('email_from');
            $nameFrom = $this->container->getParameter('email_name');
            $config['from'] = [
                $emailFrom => $nameFrom
            ];
        }

        $mailer = $this->container->get('mailer');
        $um = $this->container->get('fos_user.user_manager');

        foreach ($recipients as $email) {
            /** @var User $user */
            $user = $um->findUserByEmail($email);
            if (!$user || !$user->canSendEmail()) continue;

            $message = \Swift_Message::newInstance();
            $message->setFrom($config['from']);
            $message->setBody($config['subject']);
            $message->setBody($config['html'], 'text/html');
            $message->setTo($email);

            $mailer->send($message);
        }

    }

}
