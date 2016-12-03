<?php

namespace Gram\SurveyBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SurveyRESTController extends Controller
{

    /**
     * @ApiDoc(
     *  section="Survey",
     *  description="Get survey by id",
     *  method="GET",
     *  requirements={
     *      {"name"="id", "dataType"="integer"},
     *  },
     *  statusCodes={
     *      200 = "",
     *      404 = "",
     *      500 = "",
     *  },
     * )
     * @param $id
     * @return Response
     */
    public function getAction($id)
    {
        $trans = $this->get('translator');
        $surveyRest = $this->get('survey.rest_service');

        try {

            $survey = $surveyRest->find($id);
            if (!$survey) {
                return new JsonResponse([
                    'errors' => [$trans->trans('Survey not found', [], 'GramSurveyBundle')]
                ], Response::HTTP_NOT_FOUND);
            }

            $surveysArr = $surveyRest->serialize($survey);

            return new JsonResponse($surveysArr, Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse([
                'errors' => [$e->getMessage()]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @ApiDoc(
     *  section="Survey",
     *  description="Get survey by promocode",
     *  method="GET",
     *  requirements={
     *      {"name"="promocode", "dataType"="string"},
     *  },
     *  statusCodes={
     *      200 = "",
     *      404 = "",
     *      500 = "",
     *  },
     * )
     * @param $promocode
     * @return Response
     */
    public function getByCodeAction($promocode)
    {
        $trans = $this->get('translator');
        $surveyRest = $this->get('survey.rest_service');

        try {

            $survey = $surveyRest->findByPromocode($promocode);
            if (!$survey) {
                return new JsonResponse([
                    'errors' => [$trans->trans('Survey not found', [], 'GramSurveyBundle')]
                ], Response::HTTP_NOT_FOUND);
            }

            $surveysArr = $surveyRest->serialize($survey);

            return new JsonResponse($surveysArr, Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse([
                'errors' => [$e->getMessage()]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}