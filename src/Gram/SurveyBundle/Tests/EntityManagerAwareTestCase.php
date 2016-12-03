<?php

namespace Gram\SurveyBundle\Tests;

use Doctrine\ORM\EntityManager;

abstract class EntityManagerAwareTestCase extends ContainerAwareTestCase
{
    /** @var EntityManager */
    protected $em;

    protected function setUp()
    {
        parent::setUp();
        $this->em = $this->container->get('doctrine')->getManager();
    }

    protected function tearDown()
    {
        $this->em->getConnection()->close();
        $this->em->close();
    }


}