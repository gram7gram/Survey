<?php

namespace Gram\SurveyBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SurveyController extends Controller
{

    /**
     * @Template()
     * @return array
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $code = $this->container->getParameter('active_survey_promocode');
        $survey = null;
        if ($code) {
            $survey = $em->getRepository('GramSurveyBundle:Survey')
                ->findOneJoinedByPromocode($code);
            if (!$survey) {
                throw $this->createNotFoundException();
            }
        }
        return [
            'survey' => $survey
        ];
    }

    /**
     * @Template()
     * @return array
     */
    public function infoAction()
    {
        return [];
    }

}