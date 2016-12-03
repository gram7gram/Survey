<?php

namespace Gram\SurveyBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class ContainerAwareTestCase extends KernelTestCase
{
    /** @var ContainerInterface */
    protected $container;

    protected function setUp()
    {
        self::bootKernel();
        $this->container = static::$kernel->getContainer();
    }
}