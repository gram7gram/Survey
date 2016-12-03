<?php

namespace Gram\SurveyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table("survey_bundle_choice")
 * @ORM\Entity(repositoryClass="Gram\SurveyBundle\Entity\ChoiceRepository")
 */
class Choice
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @JMS\Groups({"spa_v1_survey"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     *
     * @JMS\Groups({"spa_v1_survey"})
     */
    private $name;

    /**
     * @var AnswerChoice
     *
     * @ORM\OneToMany(targetEntity="Gram\SurveyBundle\Entity\AnswerChoice", mappedBy="choice")
     */
    private $answerChoice;

    /**
     * @var QuestionChoice
     *
     * @ORM\OneToMany(targetEntity="Gram\SurveyBundle\Entity\QuestionChoice", mappedBy="choice")
     */
    private $questionChoice;

    /**
     * @var bool
     *
     * @ORM\Column(name="can_terminate_survey", type="boolean", nullable=false)
     *
     * @JMS\Groups({"spa_v1_survey"})
     * @JMS\SerializedName("canTerminateSurvey")
     */
    private $canTerminateSurvey;

    public function __construct()
    {
        $this->canTerminateSurvey = false;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return AnswerChoice
     */
    public function getAnswerChoice()
    {
        return $this->answerChoice;
    }

    /**
     * @return QuestionChoice
     */
    public function getQuestionChoice()
    {
        return $this->questionChoice;
    }

    /**
     * @return boolean
     */
    public function canTerminateSurvey()
    {
        return $this->canTerminateSurvey;
    }

    /**
     * @param boolean $canTerminateSurvey
     * @return $this
     */
    public function setCanTerminateSurvey($canTerminateSurvey)
    {
        $this->canTerminateSurvey = $canTerminateSurvey;

        return $this;
    }

}