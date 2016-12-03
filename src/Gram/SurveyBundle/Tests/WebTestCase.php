<?php

namespace Gram\SurveyBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as SymfonyWebTestCase;
use Symfony\Component\BrowserKit\Cookie;

abstract class WebTestCase extends SymfonyWebTestCase
{
    /**
     * @param string $login
     * @return Client
     */
    protected function createAuthorizedClient($login = 'admin')
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $session = $container->get('session');
        /** @var $userManager \FOS\UserBundle\Doctrine\UserManager */
        $userManager = $container->get('fos_user.user_manager');
        /** @var $loginManager \FOS\UserBundle\Security\LoginManager */
        $loginManager = $container->get('fos_user.security.login_manager');
        $firewallName = $container->getParameter('fos_user.firewall_name');

        $user = $userManager->findUserBy(array('username' => $login));
        $loginManager->loginUser($firewallName, $user);

        $token = $container->get('security.token_storage')->getToken();

        // save the login token into the session and put it in a cookie
        $session->set('_security_' . $firewallName, serialize($token));

        $session->save();

        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

        return $client;
    }
}