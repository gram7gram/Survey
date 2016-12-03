<?php

namespace Gram\SurveyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table("survey_bundle_answer_join_choice")
 * @ORM\Entity()
 */
class AnswerChoice
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
     * @ORM\ManyToOne(targetEntity="\Gram\SurveyBundle\Entity\Choice", inversedBy="answerChoice")
     * @ORM\JoinColumn(name="choice_id", referencedColumnName="id")
     *
     * @JMS\Groups({"spa_v1_survey"})
     */
    private $choice;

    /**
     * @var Answer
     *
     * @ORM\ManyToOne(targetEntity="\Gram\SurveyBundle\Entity\Answer", inversedBy="choices")
     * @ORM\JoinColumn(name="answer_id", referencedColumnName="id")
     *
     * @JMS\Groups({"spa_v1_survey"})
     */
    private $answer;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_respondent_answer", type="boolean", nullable=false, options={"default"=false})
     *
     * @JMS\Groups({"spa_v1_survey"})
     * @JMS\SerializedName("isRespondentAnswer")
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
     * @return $this
     */
    public function setChoice(Choice $choice = null)
    {
        $this->choice = $choice;

        return $this;
    }

    /**
     * Get answer
     *
     * @return \Gram\SurveyBundle\Entity\Answer
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set answer
     *
     * @param \Gram\SurveyBundle\Entity\Answer $answer
     * @return $this
     */
    public function setAnswer(Answer $answer = null)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Set isRespondentAnswer
     *
     * @param boolean $isRespondentAnswer
     * @return $this
     */
    public function setIsRespondentAnswer($isRespondentAnswer)
    {
        $this->isRespondentAnswer = $isRespondentAnswer;

        return $this;
    }

    /**
     * Get isRespondentAnswer
     *
     * @return boolean
     */
    public function isRespondentAnswer()
    {
        return $this->isRespondentAnswer;
    }
}