<?php

namespace Gram\SurveyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table("survey_bundle_question_join_choice")
 * @ORM\Entity()
 */
class QuestionChoice
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
     * @var Choice
     *
     * @ORM\ManyToOne(targetEntity="\Gram\SurveyBundle\Entity\Choice", inversedBy="questionChoice")
     * @ORM\JoinColumn(name="choice_id", referencedColumnName="id")
     *
     * @JMS\Groups({"spa_v1_survey"})
     */
    private $choice;

    /**
     * @var Answer
     *
     * @ORM\ManyToOne(targetEntity="\Gram\SurveyBundle\Entity\Question", inversedBy="choices")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     *
     * @JMS\Groups({"spa_v1_survey"})
     */
    private $question;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_respondent_answer", type="boolean", nullable=false, options={"default"=false})
     *
     * @JMS\Groups({"spa_v1_survey"})
     */
    private $isRespondentAnswer;

    public function __construct()
    {
        $this->isRespondentAnswer = false;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get choice
     *
     * @return \Gram\SurveyBundle\Entity\Choice
     */
    public function getChoice()
    {
        return $this->choice;
    }

    /**
     * Set choice
     *
     * @param \Gram\SurveyBundle\Entity\Choice $choice
     * @return QuestionChoice
     */
    public function setChoice(Choice $choice = null)
    {
        $this->choice = $choice;

        return $this;
    }

    /**
     * Get question
     *
     * @return \Gram\SurveyBundle\Entity\Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set question
     *
     * @param \Gram\SurveyBundle\Entity\Question $question
     * @return QuestionChoice
     */
    public function setQuestion(Question $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isRespondentAnswer()
    {
        return $this->isRespondentAnswer;
    }

    /**
     * @param boolean $isRespondentAnswer
     * @return $this
     */
    public function setIsRespondentAnswer($isRespondentAnswer)
    {
        $this->isRespondentAnswer = $isRespondentAnswer;

        return $this;
    }

}