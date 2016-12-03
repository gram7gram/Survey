<?php

namespace Gram\SurveyBundle\Controller;

use Gram\SurveyBundle\Form\CompletedSurvey\CompletedSurveyRESTType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CompletedSurveyRESTController extends Controller
{

    /**
     * @ApiDoc(
     *  section="Survey",
     *  description="Create completed survey",
     *  method="POST",
     *  statusCodes={
     *      201 = "",
     *      500 = "",
     *  },
     * )
     *
     * @param Request $request
     * @return Response
     */
    public function postAction(Request $request)
    {

        $errorService = $this->get('rest.form_errors_serializer');
        $restService = $this->get('rest.rest_service');
        $surveyRest = $this->get('survey.completed_survey_rest_service');

        $form = $this->createForm(CompletedSurveyRESTType::class, null, [
            'action' => $this->generateUrl('survey_rest_completed_survey_post'),
            'method' => 'POST'
        ]);

        $restService->handleRequestAndForm($request, $form);

        if ($form->isValid()) {

            try {
                $survey = $surveyRest->createSurvey($form);
                $surveyArr = $surveyRest->serialize($survey);

                return new JsonResponse($surveyArr, Response::HTTP_CREATED);

            } catch (\Exception $e) {
                return new JsonResponse([
                    'errors' => [$e->getMessage()]
                ], $e->getCode() > 0 ? $e->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return new JsonResponse(
            $errorService->serializeFormErrors($form),
            Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}