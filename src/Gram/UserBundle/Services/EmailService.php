<?php

namespace Gram\UserBundle\Services;

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

        $em = $this->container->get('doctrine')->getManager();
        $mailer = $this->container->get('mailer');

        foreach ($recipients as $email) {
            $user = $em->getRepository('GramUserBundle:User')->findOneBy([
                'email' => $email
            ]);
            if (!$user || !$user->getIsEmailSending()) continue;

            $message = \Swift_Message::newInstance();
            $message->setFrom($config['from']);
            $message->setBody($config['subject']);
            $message->setBody($config['html'], 'text/html');
            $message->setTo($email);

            $mailer->send($message);
        }

    }

}
