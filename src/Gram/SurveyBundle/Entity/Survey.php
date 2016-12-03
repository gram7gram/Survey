<?php

namespace Gram\SurveyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Table("survey_bundle_survey")
 * @ORM\Entity(repositoryClass="Gram\SurveyBundle\Entity\SurveyRepository")
 */
class Survey
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @JMS\Groups({"spa_v1_survey", "spa_v1_completed_survey", "spa_v1_open_survey"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text")
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
     * @var DateTime
     *
     * @ORM\Column(name="create_date", type="datetime")
     *
     * @JMS\Groups({"spa_v1_survey", "spa_v1_completed_survey", "spa_v1_open_survey"})
     * @JMS\SerializedName("createDate")
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     */
    private $createDate;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Gram\SurveyBundle\Entity\Question", mappedBy="survey")
     * @ORM\OrderBy({"order"="ASC"})
     *
     * @JMS\Groups({"spa_v1_survey", "spa_v1_completed_survey", "spa_v1_open_survey"})
     */
    private $questions;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Gram\SurveyBundle\Entity\CompletedSurvey", mappedBy="survey")
     */
    private $completedSurveys;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Gram\SurveyBundle\Entity\OpenSurvey", mappedBy="survey")
     */
    private $openSurveys;

    /**
     * @var string
     *
     * @ORM\Column(name="promocode", type="string", length=6, nullable=false)
     */
    private $promocode;

    public function __construct()
    {
        $this->createDate = new \DateTime();
        $this->questions = new ArrayCollection();
        $this->completedSurveys = new ArrayCollection();
        $this->openSurveys = new ArrayCollection();
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
     * @return Survey
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Survey
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return Survey
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Add questions
     *
     * @param \Gram\SurveyBundle\Entity\Question $questions
     * @return Survey
     */
    public function addQuestion(Question $questions)
    {
        $this->questions[] = $questions;

        return $this;
    }

    /**
     * Remove questions
     *
     * @param \Gram\SurveyBundle\Entity\Question $questions
     */
    public function removeQuestion(Question $questions)
    {
        $this->questions->removeElement($questions);
    }

    /**
     * Get questions
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Add completedSurveys
     *
     * @param CompletedSurvey $completedSurveys
     * @return Survey
     */
    public function addCompletedSurvey(CompletedSurvey $completedSurveys)
    {
        $this->completedSurveys[] = $completedSurveys;

        return $this;
    }

    /**
     * @param CompletedSurvey $completedSurveys
     */
    public function removeCompletedSurvey(CompletedSurvey $completedSurveys)
    {
        $this->completedSurveys->removeElement($completedSurveys);
    }

    /**
     * @return ArrayCollection
     */
    public function getCompletedSurveys()
    {
        return $this->completedSurveys;
    }

    /**
     * @return int
     */
    public function getCountChoices()
    {
        $count = 0;
        foreach ($this->questions as $question) {
            $count += count($question->getChoices());
        }

        return $count;
    }

    /**
     * @return ArrayCollection
     */
    public function getOpenSurveys()
    {
        return $this->openSurveys;
    }

    /**
     * @return string
     */
    public function getPromocode()
    {
        return $this->promocode;
    }

    /**
     * @param string $promocode
     */
    public function setPromocode($promocode)
    {
        $this->promocode = $promocode;
    }

}