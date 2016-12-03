<?php

namespace Gram\SurveyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table("survey_bundle_question_type")
 * @ORM\Entity()
 */
class QuestionType
{
    const MULTIPLE_OPTIONS = 'multiple';
    const SIGLE_OPTION = 'single';

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
     * @var string
     *
     * @ORM\Column(name="key", type="string", nullable=true)
     *
     * @JMS\Groups({"spa_v1_survey"})
     */
    private $key;

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
     * @return QuestionType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set key
     *
     * @param string $key
     * @return QuestionType
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }
}