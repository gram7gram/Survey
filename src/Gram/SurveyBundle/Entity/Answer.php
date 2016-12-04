<?php

namespace Gram\SurveyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table("survey_bundle_answer")
 * @ORM\Entity()
 */
class Answer
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @JMS\Groups({"spa_v1_completed_survey"})
     */
    private $id;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Gram\SurveyBundle\Entity\AnswerChoice", mappedBy="answer")
     */
    private $choices;

    /**
     * @var CompletedSurvey
     *
     * @ORM\ManyToOne(targetEntity="Gram\SurveyBundle\Entity\CompletedSurvey", inversedBy="answers")
     * @ORM\JoinColumn(name="completed_survey_id", referencedColumnName="id")
     *
     * @JMS\Groups({"spa_v1_survey"})
     */
    private $completedSurvey;

    /**
     * @var Question
     *
     * @ORM\ManyToOne(targetEntity="Gram\SurveyBundle\Entity\Question")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     *
     * @JMS\Groups({"spa_v1_completed_survey"})
     */
    private $question;

    public function __construct()
    {
        $this->choices = new ArrayCollection();
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
     * Add choices
     *
     * @param AnswerChoice $choices
     * @return Answer
     */
    public function addChoice(AnswerChoice $choices)
    {
        $this->choices[] = $choices;

        return $this;
    }

    /**
     * Remove choices
     *
     * @param AnswerChoice $choices
     */
    public function removeChoice(AnswerChoice $choices)
    {
        $this->choices->removeElement($choices);
    }

    /**
     * Get choices
     *
     * @return ArrayCollection
     */
    public function getChoices()
    {
        return $this->choices;
    }

    /**
     * Get Completed Survey
     *
     * @return \Gram\SurveyBundle\Entity\CompletedSurvey
     */
    public function getCompletedSurvey()
    {
        return $this->completedSurvey;
    }

    /**
     * Set Completed Survey
     *
     * @param CompletedSurvey $completedSurvey
     * @return Answer
     */
    public function setCompletedSurvey(CompletedSurvey $completedSurvey = null)
    {
        $this->completedSurvey = $completedSurvey;

        return $this;
    }

    /**
     * Get question
     *
     * @return Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set question
     *
     * @param Question $question
     * @return Answer
     */
    public function setQuestion(Question $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("choices")
     * @JMS\Groups({"spa_v1_completed_survey"})
     */
    public function getAnswerChoices()
    {
        $result = [];

        if (!$this->choices->isEmpty()) {
            /** @var AnswerChoice $choices */
            foreach ($this->choices as $choices) {
                $choice = $choices->getChoice();
                $result[] = [
                    'id' => $choice->getId(),
                    'name' => $choice->getName(),
                    'isRespondentAnswer' => $choices->isRespondentAnswer()
                ];
            }
        }

        return $result;
    }

}