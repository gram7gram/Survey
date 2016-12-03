<?php
namespace Gram\SurveyBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CompleteSurveyRESTControllerTest extends WebTestCase
{

    public function testPostAction()
    {
        $client = static::createClient();

        $json = $this->getValidJson();

        $survey = json_decode($json, true);
        $survey['user']['email'] = md5(uniqid()) . '@xx.xx';

        $client->request('POST', '/spa/v1/completed_surveys', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode($survey));

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function getValidJson()
    {
        $file = __DIR__ . '/../../../../../app/Resources/tests/CompletedSurvey_valid.json';

        $this->assertTrue(file_exists($file), 'Test file does not exist');

        $json = file_get_contents($file);

        return $json;
    }
}