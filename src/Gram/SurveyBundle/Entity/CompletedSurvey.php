<?php

namespace Gram\SurveyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gram\UserBundle\Entity\User;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Table("survey_bundle_completed_survey")
 * @ORM\Entity(repositoryClass="Gram\SurveyBundle\Entity\CompletedSurveyRepository")
 */
class CompletedSurvey
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
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetime")
     *
     * @JMS\Groups({"spa_v1_completed_survey"})
     * @JMS\SerializedName("createDate")
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     */
    private $createDate;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Gram\UserBundle\Entity\User")
     *
     * @JMS\Groups({"spa_v1_completed_survey"})
     */
    private $user;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Gram\SurveyBundle\Entity\Answer", mappedBy="completedSurvey")
     *
     * @JMS\Groups({"spa_v1_completed_survey"})
     */
    private $answers;

    /**
     * @ORM\ManyToOne(targetEntity="Gram\SurveyBundle\Entity\Survey", inversedBy="completedSurveys")
     *
     * @JMS\Groups({"spa_v1_completed_survey"})
     */
    private $survey;

    public function __construct()
    {
        $this->createDate = new \DateTime();
        $this->answers = new ArrayCollection();
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
     * Get User
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set User
     *
     * @param User $user
     * @return CompletedSurvey
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * @param DateTime $createDate
     *
     * @return CompletedSurvey
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get Survey
     *
     * @return \Gram\SurveyBundle\Entity\Survey
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * Set Survey
     *
     * @param \Gram\SurveyBundle\Entity\Survey $survey
     * @return CompletedSurvey
     */
    public function setSurvey(Survey $survey = null)
    {
        $this->survey = $survey;

        return $this;
    }

    /**
     * Add answers
     *
     * @param \Gram\SurveyBundle\Entity\Answer $answers
     * @return CompletedSurvey
     */
    public function addAnswer(Answer $answers)
    {
        $this->answers[] = $answers;

        return $this;
    }

    /**
     * Remove answers
     *
     * @param \Gram\SurveyBundle\Entity\Answer $answers
     */
    public function removeAnswer(Answer $answers)
    {
        $this->answers->removeElement($answers);
    }

    /**
     * Get answers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnswers()
    {
        return $this->answers;
    }
}