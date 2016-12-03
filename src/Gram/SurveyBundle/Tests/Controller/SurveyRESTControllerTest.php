<?php
namespace Gram\SurveyBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SurveyRESTControllerTest extends WebTestCase
{

    public function testgetByIdAction()
    {
        $client = static::createClient();

        $client->request('GET', '/spa/v1/surveys/' . 1);

        $response = $client->getResponse();

        if ($response->isSuccessful()) {
            $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        } else {
            $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        }
    }

    public function testgetByPromocodeAction()
    {
        $client = static::createClient();

        $client->request('GET', '/spa/v1/surveys/' . md5(uniqid()));

        $response = $client->getResponse();

        if ($response->isSuccessful()) {
            $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        } else {
            $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        }
    }
}