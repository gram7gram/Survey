<?php

namespace Gram\SurveyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gram\UserBundle\Entity\User;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Table("survey_bundle_open_survey")
 * @ORM\Entity(repositoryClass="Gram\SurveyBundle\Entity\OpenSurveyRepository")
 */
class OpenSurvey
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @JMS\Groups({"spa_v1_open_survey"})
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Gram\UserBundle\Entity\User")
     *
     * @JMS\Groups({"spa_v1_open_survey"})
     */
    private $recipient;

    /**
     * @ORM\ManyToOne(targetEntity="Gram\SurveyBundle\Entity\Survey", inversedBy="openSurveys")
     * @ORM\JoinColumn(name="survey_id", referencedColumnName="id")
     *
     * @JMS\Groups({"spa_v1_open_survey"})
     */
    private $survey;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Gram\UserBundle\Entity\User")
     */
    private $creator;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="create_date", type="datetime")
     *
     * @JMS\Groups({"spa_v1_open_survey"})
     * @JMS\SerializedName("createDate")
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     */
    private $createDate;

    public function __construct()
    {
        $this->createDate = new \DateTime();
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
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * @param \DateTime $createDate
     *
     * @return $this
     */
    public function setCreateDate(\DateTime $createDate)
    {
        $this->createDate = $createDate;

        return $this;
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
     * Set Survey
     *
     * @param Survey $survey
     * @return $this
     */
    public function setSurvey(Survey $survey = null)
    {
        $this->survey = $survey;

        return $this;
    }

    /**
     * @return User
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param User $creator
     * @return $this
     */
    public function setCreator(User $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * @return User
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param User $recepient
     * @return $this
     */
    public function setRecipient(User $recepient = null)
    {
        $this->recipient = $recepient;

        return $this;
    }

}