<?php

namespace Gram\SurveyBundle\Controller;

use Gram\SurveyBundle\Classes\SurveyStatistic;
use Gram\SurveyBundle\Entity\ChoiceRepository;
use Gram\SurveyBundle\Entity\SurveyRepository;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SurveyStatisticsRESTController extends Controller
{

    /**
     * @ApiDoc(
     *  section = "Survey",
     *  method="GET",
     *  description="Get survey report",
     *  requirements={
     *      {"name"="id", "dataType"="integer"},
     *  },
     *  statusCodes = {
     *      200 = "Returned when successful",
     *      404 = "Returned when not found"
     *  },
     * )
     *
     * @param $id
     * @return Response
     */
    public function getAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $trans = $this->get('translator');
        $surveyRest = $this->get('survey.rest_service');
        /** @var SurveyRepository $surveyRepo */
        $surveyRepo = $em->getRepository('GramSurveyBundle:Survey');
        /** @var ChoiceRepository $choiceRepo */
        $choiceRepo = $em->getRepository('GramSurveyBundle:Choice');

        $entity = $surveyRepo->getOrderedSurveyById($id);
        if (!$entity) {
            return new JsonResponse([
                'errors' => [$trans->trans('Survey not found', [], 'GramSurveyBundle')]
            ], Response::HTTP_NOT_FOUND);
        }

        try {

            $statistic = $choiceRepo->countAnswerStatistic($id);

            $surveyStatistic = new SurveyStatistic();
            $statistic = $surveyStatistic->countStatisticForQuestion($entity, $statistic);
            $response = $surveyRest->serialize($statistic);

            return new JsonResponse($response);
        } catch (\Exception $e) {
            return new JsonResponse([
                'errors' => [$e->getMessage()]
            ], $e->getCode() > 0 ? $e->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}