<?php

namespace Gram\SurveyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table("survey_bundle_question")
 * @ORM\Entity(repositoryClass="Gram\SurveyBundle\Entity\CompletedSurveyRepository")
 */
class Question
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @JMS\Groups({"spa_v1_survey", "spa_v1_open_survey"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     *
     * @JMS\Groups({"spa_v1_survey", "spa_v1_completed_survey", "spa_v1_open_survey"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     *
     * @JMS\Groups({"spa_v1_survey", "spa_v1_completed_survey", "spa_v1_open_survey"})
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="ordering", type="integer")
     *
     * @JMS\Groups({"spa_v1_survey", "spa_v1_completed_survey", "spa_v1_open_survey"})
     */
    private $order;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_responent_answer_allowed", type="boolean")
     *
     * @JMS\Groups({"spa_v1_survey", "spa_v1_completed_survey", "spa_v1_open_survey"})
     * @JMS\SerializedName("isRespondentAnswerAllowed")
     */
    private $isRespondentAnswerAllowed;

    /**
     * @var QuestionType
     *
     * @ORM\ManyToOne(targetEntity="Gram\SurveyBundle\Entity\QuestionType")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     *
     * @JMS\Groups({"spa_v1_survey"})
     */
    private $type;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Gram\SurveyBundle\Entity\QuestionChoice", mappedBy="question")
     */
    private $choices;

    /**
     * @var Survey
     *
     * @ORM\ManyToOne(targetEntity="Gram\SurveyBundle\Entity\Survey", inversedBy="questions")
     * @ORM\JoinColumn(name="survey_id", referencedColumnName="id")
     */
    private $survey;

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
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Question
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Gram\SurveyBundle\Entity\QuestionType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     *
     * @param QuestionType $type
     * @return Question
     */
    public function setType(QuestionType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Add choices
     *
     * @param \Gram\SurveyBundle\Entity\QuestionChoice $choices
     * @return Question
     */
    public function addChoice(QuestionChoice $choices)
    {
        $this->choices[] = $choices;

        return $this;
    }

    /**
     * Remove choices
     *
     * @param QuestionChoice $choices
     */
    public function removeChoice(QuestionChoice $choices)
    {
        $this->choices->removeElement($choices);
    }

    public function getChoices()
    {
        return $this->choices;
    }

    /**
     * Get Survey
     *
     * @return Survey
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * Set $survey
     *
     * @param Survey $survey
     * @return Question
     */
    public function setSurvey(Survey $survey = null)
    {
        $this->survey = $survey;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int $order
     * @return $this
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isRespondentAnswerAllowed()
    {
        return $this->isRespondentAnswerAllowed;
    }

    /**
     * @param boolean $isRespondentAnswerAllowed
     * @return $this
     */
    public function setIsRespondentAnswerAllowed($isRespondentAnswerAllowed)
    {
        $this->isRespondentAnswerAllowed = $isRespondentAnswerAllowed;

        return $this;
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("choices")
     * @JMS\Type("array")
     * @JMS\Groups({"spa_v1_survey"})
     */
    public function getAnswerChoices()
    {
        $choices = $this->choices->filter(function (QuestionChoice $questionChoice) {
            return !$questionChoice->isRespondentAnswer();
        })->toArray();

        $return = [];
        /** @var QuestionChoice $questionChoice */
        foreach ($choices as $questionChoice) {
            $choice = $questionChoice->getChoice();
            $return[] = [
                'id' => $choice->getId(),
                'name' => $choice->getName(),
                'canTerminateSurvey' => $choice->canTerminateSurvey(),
            ];
        }

        return $return;
    }

}