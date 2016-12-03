<?php

namespace Gram\UserBundle\Entity;

use JMS\Serializer\Annotation as JMS;

class Address
{
    /**
     * @var integer
     *
     * @JMS\Groups({"spa_v1_completed_survey"})
     */
    private $id;

    /**
     * @var string
     *
     * @JMS\Groups({"spa_v1_completed_survey"})
     */
    private $city;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

}