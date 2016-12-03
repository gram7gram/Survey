<?php

namespace Gram\UserBundle\Entity;

use JMS\Serializer\Annotation as JMS;

class Contacts
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
     * @JMS\SerializedName("mobilePhone")
     */
    private $mobilePhone;

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
    public function getMobilePhone()
    {
        return $this->mobilePhone;
    }

    /**
     * @param string $mobilePhone
     */
    public function setMobilePhone($mobilePhone)
    {
        $this->mobilePhone = $mobilePhone;
    }

}