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
        return [];
    }

}